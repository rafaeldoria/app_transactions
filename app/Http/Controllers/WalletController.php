<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ResponseService;
use App\Resources\Wallets\WalletResourceCollection;
use App\Resources\Wallets\WalletResource;
use App\Services\Wallets\WalletService;

class WalletController extends Controller
{
    public function index()
    {
        try {
            $wallets = (new WalletService)->index();
        } catch (\Throwable |\Exception $e) {
            return ResponseService::exception('wallet.index', null, $e);
        }
        return new WalletResourceCollection($wallets);
    }

    public function show(int $id) 
    {   
        try {
            $wallet = (new WalletService)->show($id);
            if (!$wallet) {
                throw new \Exception('Not found', -404);
            }
        } catch (\Throwable|\Exception $e) {
            return ResponseService::exception('wallet.show', $id, $e);
        }
        return new WalletResource($wallet,[
            'type' => 'show',
            'route' => 'wallet.show'
        ]);
    }

    public function update(Int $id, Request $request)
    {
        try {
            $wallet = (new WalletService)->update($id, $request->all());
        } catch (\Throwable|\Exception $e) {
            return ResponseService::exception('wallet.update',$id,$e);
        }
        return new WalletResource($wallet,[
            'type' => 'update',
            'route' => 'wallet.update'
        ]);
    }

    public function getWalletByUser(Int $user_id)
    {
        try {
            $wallet = (new WalletService)->getWalletByUser($user_id);
        } catch (\Throwable|\Exception $e) {
            return ResponseService::exception('wallet.get_by_user',$user_id,$e);
        }
        return new WalletResource($wallet,[
            'type' => 'show',
            'route' => 'wallet.get_by_user'
        ]);
    }
}
