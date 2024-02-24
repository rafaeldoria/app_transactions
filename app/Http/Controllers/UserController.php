<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\WalletService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    protected $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function index() : JsonResponse
    {
        return response()->json($this->user->all());
    }

    public function show(User $user) : JsonResponse 
    {   
        return response()->json($user);
    }

    public function store(Request $request) : JsonResponse
    {
        $user = $this->user->create($request->all());
        (new WalletService)->createWallet($user);
        return response()->json($user, Response::HTTP_CREATED);
    }

    public function update(User $user, Request $request) : JsonResponse
    {
        $user->update($request->all());
        return response()->json($user);
    }

    public function destroy(User $user) : JsonResponse
    {
        $user->delete();
        return response()->json([], Response::HTTP_NO_CONTENT);
    }
}
