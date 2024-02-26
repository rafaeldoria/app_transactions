<?php

namespace App\Http\Controllers;

use Exception;
use Throwable;
use Illuminate\Http\Request;
use App\Services\ResponseService;
use App\Services\Wallets\WalletService;
use App\Resources\Wallets\WalletResource;
use App\Resources\Wallets\WalletResourceCollection;

class WalletController extends Controller
{
    public function index()
    {
        try {
            $wallets = (new WalletService)->index();
        } catch (Throwable |Exception $exception) {
            return (new ResponseService)->exception('wallet.index', null, $exception);
        }
        return new WalletResourceCollection($wallets);
    }

    public function show(int $walletId) 
    {   
        try {
            $wallet = (new WalletService)->show($walletId);
            if (!$wallet) {
                throw new Exception('Not found', -404);
            }
        } catch (Throwable|Exception $exception) {
            return (new ResponseService)->exception('wallet.show', $walletId, $exception);
        }
        return new WalletResource($wallet,[
            'type' => 'show',
            'route' => 'wallet.show'
        ]);
    }

    public function update(Int $walletId, Request $request)
    {
        try {
            $wallet = (new WalletService)->update($walletId, $request->all());
        } catch (Throwable|Exception $exception) {
            return (new ResponseService)->exception('wallet.update',$walletId,$exception);
        }
        return new WalletResource($wallet,[
            'type' => 'update',
            'route' => 'wallet.update'
        ]);
    }

    public function getWalletByUser(Int $userId)
    {
        try {
            $wallet = (new WalletService)->getWalletByUser($userId);
        } catch (Throwable|Exception $exception) {
            return (new ResponseService)->exception('wallet.get_by_user',$userId,$exception);
        }
        return new WalletResource($wallet,[
            'type' => 'show',
            'route' => 'wallet.get_by_user'
        ]);
    }
}
