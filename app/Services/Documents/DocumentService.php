<?php

namespace App\Services\Documents;

use App\Models\Document;
use App\Repositories\DocumentRepository;
use Exception;

class DocumentService
{
    public function index()
    {
        return (new DocumentRepository)->index();
    }

    public function show($documentId)
    {
        return (new DocumentRepository)->show($documentId);
    }

    public function store(array $data)
    {
        $document = (new DocumentRepository)->store($data);
        return $document;
    }

    public function update(int $documentId, $data)
    {
        $document = $this->show($documentId);
        (new DocumentRepository)->update($document, $data);
        return $document;
    }

    public function getbyuser(int $userId)
    {
        return (new DocumentRepository)->getByUser($userId);
    }
}
