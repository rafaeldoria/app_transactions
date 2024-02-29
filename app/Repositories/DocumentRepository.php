<?php

namespace App\Repositories;

use Exception;
use App\Models\Document;
use Illuminate\Database\QueryException;
use App\Repositories\Interfaces\IDocumentRepository;

class DocumentRepository extends BaseRepository implements IDocumentRepository
{
    public function index()
    {
        $document = new Document();
        return $document->all();
    }

    public function show(Int $documentId)
    {
        $document = new Document();
        $document = $document->find($documentId);
        if (!$document) {
            throw new Exception('Not found', -404);
        }
        return $document;
    }

    public function store(array $data)
    {
        $document = new Document();
        return $document->create($data);
    }

    public function update($document, array $data)
    {
        $document->update($data);
    }

    public function getByUser(int $userId)
    {
        $document = new Document();
        $document = $document->where('user_id', $userId)
            ->whereNull('deleted_at')
            ->first();
            
        if (!$document) {
            throw new Exception('Not found', -404);
        }
        return $document;
    }
}
