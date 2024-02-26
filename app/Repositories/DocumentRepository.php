<?php

namespace App\Repositories;

use App\Models\Document;
use App\Repositories\Interfaces\IDocumentRepository;
use Exception;

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
        return $document->where('user_id', $userId)
            ->whereNull('deleted_at')
            ->first();
    }
}
