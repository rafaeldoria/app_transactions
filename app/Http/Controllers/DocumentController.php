<?php

namespace App\Http\Controllers;

use App\Models\Document;
use Illuminate\Http\Request;
use App\Services\ResponseService;
use App\Resources\Document\DocumentResource;
use App\Resources\Document\DocumentResourceCollection;

class DocumentController extends Controller
{
    protected $document;

    public function __construct(Document $document)
    {
        $this->document = $document;
    }

    public function index()
    {
        try {
            $documents = $this->document->all();
        } catch (\Throwable |\Exception $e) {
            return ResponseService::exception('document.get', null, $e);
        }
        return new DocumentResourceCollection($documents);
    }

    public function show(Document $document)
    {   
        try {
            
        } catch (\Throwable|\Exception $e) {
            return ResponseService::exception('document.get', null, $e);
        }
        return new DocumentResource($document,[
            'type' => 'show',
            'route' => 'document.show'
        ]);
    }

    public function store(Request $request)
    {
        try {
            $document = $this->document->create($request->all());
        } catch (\Throwable|\Exception $e) {
            return ResponseService::exception('document.store',null,$e);
        }
        return new DocumentResource($document,[
            'type' => 'store',
            'route' => 'document.store'
        ]);
    }

    public function getDocumentByUser(Int $user_id)
    {
        try {
            $document = $this->document->where('user_id', $user_id)
                ->whereNull('deleted_at')
                ->first();
        } catch (\Throwable|\Exception $e) {
            return ResponseService::exception('document.get_by_user',null,$e);
        }
        return new DocumentResource($document,[
            'type' => 'show',
            'route' => 'document.get_by_user'
        ]);
    }

    public function update(Document $document, Request $request)
    {
        try {
            $document->update($request->all());
        } catch (\Throwable|\Exception $e) {
            return ResponseService::exception('document.update',null,$e);
        }
        return new DocumentResource($document,[
            'type' => 'update',
            'route' => 'document.update'
        ]);
    }
}
