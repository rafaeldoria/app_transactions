<?php

namespace App\Repositories;

use App\Models\Document;
use App\Repositories\Interfaces\IDocumentRepository;
use Exception;

class DocumentRepository extends BaseRepository implements IDocumentRepository
{
    public function index()
    {
        return Document::all();
    }

    public function show(Int $id)
    {
        $document = Document::find($id);
        if (!$document) {
            throw new \Exception('Not found', -404);
        }
        return $document;
    }

    public function store(array $data)
    {
        return Document::create($data);
    }

    public function update($document, array $data)
    {
        $document->update($data);
    }

    public function getByUser(int $user_id)
    {
        return Document::where('user_id', $user_id)
            ->whereNull('deleted_at')
            ->first();
    }
}
