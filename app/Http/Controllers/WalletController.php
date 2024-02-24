<?php

namespace App\Http\Controllers;

use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class WalletController extends Controller
{
    protected $wallet;

    public function __construct(Wallet $wallet)
    {
        $this->wallet = $wallet;
    }

    public function index() : JsonResponse
    {
        return response()->json($this->wallet->all());
    }

    public function show(Wallet $wallet) : JsonResponse 
    {   
        return response()->json($wallet);
    }

    public function getWalletByUser(Int $user_id) : JsonResponse
    {
        $wallet = $this->wallet->where('user_id', $user_id)
            ->whereNull('deleted_at')
            ->first();
        
        return response()->json($wallet);
    }

    public function update(Wallet $wallet, Request $request) : JsonResponse
    {
        $wallet->update($request->all());
        return response()->json($wallet);
    }
}
