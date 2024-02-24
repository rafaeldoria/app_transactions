<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WalletController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\TransactionController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::get('/', function (){
    return '-- APP TRANSACTIONS --';
});

Route::resource('/user', UserController::class);

Route::resource('/document', DocumentController::class);
Route::get('/document/getByUser/{user_id}', [DocumentController::class, 'getDocumentByUser']);

Route::get('/wallet', [WalletController::class, 'index']);
Route::get('/wallet/{wallet}', [WalletController::class, 'show']);
Route::get('/wallet/getByUser/{user_id}', [WalletController::class, 'getWalletByUser']);
Route::put('/wallet/{wallet}', [WalletController::class, 'update']);

Route::resource('/transaction', TransactionController::class);
Route::put('/transaction/toconfirm/{transaction}', [TransactionController::class, 'toConfirmTransaction']);