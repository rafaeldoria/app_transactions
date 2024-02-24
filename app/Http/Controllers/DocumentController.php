<?php

namespace App\Http\Controllers;

use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;

class DocumentController extends Controller
{
    protected $document;

    public function __construct(Document $document)
    {
        $this->document = $document;
    }

    public function index() : JsonResponse
    {
        return response()->json($this->document->all());
    }

    public function show(Document $document) : JsonResponse 
    {   
        return response()->json($document);
    }

    public function store(Request $request) : JsonResponse
    {
        $document = $this->document->create($request->all());
        return response()->json($document, Response::HTTP_CREATED);
    }

    public function getDocumentByUser(Int $user_id) : JsonResponse
    {
        $document = $this->document->where('user_id', $user_id)
            ->whereNull('deleted_at')
            ->first();
        
        return response()->json($document);
    }

    public function update(Document $document, Request $request) : JsonResponse
    {
        $document->update($request->all());
        return response()->json($document);
    }
}
