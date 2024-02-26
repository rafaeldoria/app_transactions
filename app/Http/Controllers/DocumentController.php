<?php

namespace App\Http\Controllers;

use Exception;
use Throwable;
use Illuminate\Http\Request;
use App\Services\ResponseService;
use App\Services\Documents\DocumentService;
use App\Resources\Documents\DocumentResource;
use App\Resources\Documents\DocumentResourceCollection;

class DocumentController extends Controller
{
    public function index()
    {
        try {
            $documents = (new DocumentService())->index();
        } catch (Throwable |Exception $exception) {
            return (new ResponseService)->exception('document.index', null, $exception);
        }
        return new DocumentResourceCollection($documents);
    }

    public function show(int $documentId)
    {   
        try {
            $document = (new DocumentService())->show($documentId);
            if (!$document) {
                throw new Exception('Not found', -404);
            }
        } catch (Throwable|Exception $exception) {
            return (new ResponseService)->exception('document.show', $documentId, $exception);
        }
        return new DocumentResource($document,[
            'type' => 'show',
            'route' => 'document.show'
        ]);
    }

    public function store(Request $request)
    {
        try {
            $document = (new DocumentService())->store($request->all());
        } catch (Throwable|Exception $exception) {
            return (new ResponseService)->exception('document.store',null,$exception);
        }
        return new DocumentResource($document,[
            'type' => 'store',
            'route' => 'document.store'
        ]);
    }

    public function getDocumentByUser(Int $userId)
    {
        try {
            $document = (new DocumentService())->getbyuser($userId);
        } catch (Throwable|Exception $exception) {
            return (new ResponseService)->exception('document.get_by_user',$userId,$exception);
        }
        return new DocumentResource($document,[
            'type' => 'show',
            'route' => 'document.get_by_user'
        ]);
    }

    public function update(Int $documentId, Request $request)
    {
        try {
            $document = (new DocumentService)->update($documentId, $request->all());
            if (!$document) {
                throw new Exception('Not found', -404);
            }
            $document->update($request->all());
        } catch (Throwable|Exception $exception) {
            return (new ResponseService)->exception('document.update',$documentId,$exception);
        }
        return new DocumentResource($document,[
            'type' => 'update',
            'route' => 'document.update'
        ]);
    }
}
