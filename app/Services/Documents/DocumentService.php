<?php

namespace App\Services\Documents;

use App\Models\Document;
use Illuminate\Support\Facades\Redis;
use App\Repositories\DocumentRepository;
use App\Services\Redis\RedisService;

class DocumentService
{
    protected $redis;

    public function __construct()
    {
        $this->redis = new RedisService();
    }

    public function index()
    {
        $key = 'document.service.repository.index';
        if(!$this->redis->exists($key) || app()->environment() === 'testing'){
            
            $document = (new DocumentRepository)->index();
            if(!app()->environment() === 'testing'){
                $this->redis->set($key, $document, 20);
            }
            return $document;
        }
        $dataArray = json_decode($this->redis->get($key), true);
        return collect($dataArray);
    }

    public function show($documentId)
    {
        $key = 'document.service.repository.show.' . $documentId;
        if(!$this->redis->exists($key) || app()->environment() === 'testing'){
            $document = (new DocumentRepository)->show($documentId);

            if(!app()->environment() === 'testing'){
                $this->redis->set($key, $document, 30);
            }

            return $document;
        }
        $dataArray = json_decode($this->redis->get($key), true);
        return collect($dataArray);
    }

    public function store(array $data)
    {
        $document = (new DocumentRepository)->store($data);
        return $document;
    }

    public function update(int $documentId, $data)
    {
        $document = $this->show($documentId);
        if(!$document instanceof Document){
            $dataArray = $document;
            if(!is_array($document)){
                $dataArray = json_decode($document, true);
            }
            $document = new Document();
            $document->fill($dataArray);
            $document->id = $dataArray['id'];
        }
        (new DocumentRepository)->update($document, $data);
        return $document;
    }

    public function getbyuser(int $userId)
    {
        $key = 'document.service.repository.getbyuser.' . $userId;
        if(!$this->redis->exists($key) || app()->environment() === 'testing'){
            $document = (new DocumentRepository)->getByUser($userId);

            if(!app()->environment() === 'testing'){
                $this->redis->set($key, $document, 30);
            }

            return $document;
        }
        $dataArray = json_decode($this->redis->get($key), true);
        return collect($dataArray);
    }
}
