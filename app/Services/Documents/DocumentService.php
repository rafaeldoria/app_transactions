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

    public function show($id)
    {
        return (new DocumentRepository)->show($id);
    }

    public function store(array $data)
    {
        $document = (new DocumentRepository)->store($data);
        return $document;
    }

    public function update(int $id, $data)
    {
        $document = $this->show($id);
        (new DocumentRepository)->update($document, $data);
        return $document;
    }

    public function getbyuser(int $user_id)
    {
        return (new DocumentRepository)->getByUser($user_id);
    }
}
