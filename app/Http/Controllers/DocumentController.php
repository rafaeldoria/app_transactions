<?php

namespace App\Http\Controllers;

use App\Models\Document;
use Illuminate\Http\Request;
use App\Services\ResponseService;
use App\Resources\Documents\DocumentResource;
use App\Resources\Documents\DocumentResourceCollection;
use App\Services\Documents\DocumentService;

class DocumentController extends Controller
{
    public function index()
    {
        try {
            $documents = (new DocumentService())->index();
        } catch (\Throwable |\Exception $e) {
            return ResponseService::exception('document.index', null, $e);
        }
        return new DocumentResourceCollection($documents);
    }

    public function show(int $id)
    {   
        try {
            $document = (new DocumentService())->show($id);
            if (!$document) {
                throw new \Exception('Not found', -404);
            }
        } catch (\Throwable|\Exception $e) {
            return ResponseService::exception('document.show', $id, $e);
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
            $document = (new DocumentService())->getbyuser($user_id);
        } catch (\Throwable|\Exception $e) {
            return ResponseService::exception('document.get_by_user',$user_id,$e);
        }
        return new DocumentResource($document,[
            'type' => 'show',
            'route' => 'document.get_by_user'
        ]);
    }

    public function update(Int $id, Request $request)
    {
        try {
            $document = (new DocumentService)->update($id, $request->all());
            if (!$document) {
                throw new \Exception('Not found', -404);
            }
            $document->update($request->all());
        } catch (\Throwable|\Exception $e) {
            return ResponseService::exception('document.update',$id,$e);
        }
        return new DocumentResource($document,[
            'type' => 'update',
            'route' => 'document.update'
        ]);
    }
}
