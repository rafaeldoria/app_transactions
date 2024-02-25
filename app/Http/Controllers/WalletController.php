<?php

namespace App\Http\Controllers;

use App\Models\Wallet;
use App\Resources\Wallet\WalletResource;
use Illuminate\Http\Request;
use App\Services\ResponseService;
use App\Resources\Wallet\WalletResourceCollection;

class WalletController extends Controller
{
    protected $wallet;

    public function __construct(Wallet $wallet)
    {
        $this->wallet = $wallet;
    }

    public function index()
    {
        try {
            $wallets = $this->wallet->all();
        } catch (\Throwable |\Exception $e) {
            return ResponseService::exception('wallet.index', null, $e);
        }
        return new WalletResourceCollection($wallets);
    }

    public function show(Wallet $wallet) 
    {   
        try {
            
        } catch (\Throwable|\Exception $e) {
            return ResponseService::exception('wallet.show', null, $e);
        }
        return new WalletResource($wallet,[
            'type' => 'show',
            'route' => 'wallet.show'
        ]);
    }

    public function getWalletByUser(Int $user_id)
    {
        try {
            $wallet = $this->wallet->where('user_id', $user_id)
                ->whereNull('deleted_at')
                ->first();
        } catch (\Throwable|\Exception $e) {
            return ResponseService::exception('wallet.get_by_user',null,$e);
        }
        return new WalletResource($wallet,[
            'type' => 'show',
            'route' => 'wallet.get_by_user'
        ]);
    }

    public function update(Wallet $wallet, Request $request)
    {
        try {
            $wallet->update($request->all());
        } catch (\Throwable|\Exception $e) {
            return ResponseService::exception('wallet.update',null,$e);
        }
        return new WalletResource($wallet,[
            'type' => 'update',
            'route' => 'wallet.update'
        ]);
    }
}
