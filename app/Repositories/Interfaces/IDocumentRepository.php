<?php

namespace App\Repositories\Interfaces;

interface IDocumentRepository
{
    public function getByUser(int $documentId);
}
