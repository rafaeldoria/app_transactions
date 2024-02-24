<?php

namespace Tests\Featurea\API;

use Tests\TestCase;
use App\Models\User;
use App\Models\Document;
use Illuminate\Http\Response;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DocumentControllerTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;
    
    /**
     * Test getting an API all documents.
     */
    public function test_get_all_documents_endpoint(): void
    {
        $user = User::factory()->create();
        Document::factory(3)->for($user)->create();
        $response = $this->getJson('/api/document');

        $response->assertStatus(Response::HTTP_OK)
                ->assertJsonCount(3)
                ->assertSee(['id','type','value', 'user_id']);
        
        $response->assertJson(function (AssertableJson $json){
            $json->whereType('0.id', 'integer');
            $json->whereType('0.type', 'integer');
            $json->whereType('0.value', 'string');
            $json->whereType('0.user_id', 'integer');
        });
    }

    /**
     * Test getting an API Document.
     */
    public function test_get_document_endpoint(): void
    {
        $user = User::factory()->create();
        $document = Document::factory()->for($user)->create();
        
        $response = $this->getJson('api/document/' . $document->id);

        $response->assertStatus(Response::HTTP_OK)
                ->assertJsonStructure(['id','type','value','user_id']);
    }

    /**
     * Test getting an API Document
     * by User.
     */
    public function test_get_document_by_user_endpoint(): void
    {
        $user = User::factory()->create();
        Document::factory()->for($user)->create();
        
        $response = $this->getJson('api/document/getByUser/' . $user->id);

        $response->assertStatus(Response::HTTP_OK)
                ->assertJsonStructure(['id','type','value','user_id']);
    }

    /**
     * Test storing an API Document 
     */
    public function test_post_document_endpoint(): void
    {
        $user = User::factory()->create();
        $documentData = [
            'type' => Document::__TYPE_CPF__,
            'value' => rand(00000000001,99999999999),
            'user_id' => $user->id
        ];

        $response = $this->postJson('api/document', $documentData);

        $response->assertStatus(Response::HTTP_CREATED);

        $this->assertDatabaseHas('documents', [
            'type' => $documentData['type'],
            'value' => $documentData['value'],
            'user_id' => $documentData['user_id'],
        ]);

        $document = Document::where('user_id', $documentData['user_id'])->first();
        $this->assertEquals($document['value'], $documentData['value']);
    }

    /**
     * Test error create a document
     * with value repeated
     */
    public function test_it_throws_error_when_create_document_with_duplicate_value()
    {
        $value = '13245678901';
        $user_1 = User::factory()->create();
        Document::create([
            'type' => Document::__TYPE_CPF__,
            'value' => $value,
            'user_id' => $user_1->id
        ]);

        $user_2 = User::factory()->create();
        $documentData = [
            'type' => Document::__TYPE_CPF__,
            'value' => $value,
            'user_id' => $user_2->id
        ];

        $response = $this->postJson('api/document', $documentData);

        $response->assertStatus(Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    /**
     *  Test updating an API Wallet
     */
    public function test_update_document_endpoint(): void
    {
        $user = User::factory()->create();
        Document::factory()->for($user)->create();
        $document = $this->getJson('api/document/getByUser/' . $user->id);

        $updatedDocument = [
            'user_id' => $user->id,
            'type' => Document::__TYPE_CNPJ__,
            'value' => rand(00000000000001,99999999999999),
        ];
        $response = $this->json('PUT', 'api/document/' . $document['id'], $updatedDocument);

        $response->assertStatus(Response::HTTP_OK);
        $this->assertDatabaseHas('documents', [
            'id' => $document['id'],
            'user_id' => $document['user_id'],
            'type' => $updatedDocument['type'],
            'value' => $updatedDocument['value']
        ]);
        $document = $this->getJson('api/document/' . $document['id']);
        $this->assertEquals($updatedDocument['value'], $document['value']);
        $this->assertEquals(Document::__TYPE_CNPJ__, $document['type']);
    }
}
