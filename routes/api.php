<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MailController;
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
Route::get('/document/getByUser/{user_id}', [DocumentController::class, 'getDocumentByUser'])->name('document.get_by_user');

Route::get('/wallet', [WalletController::class, 'index'])->name('wallet.index');
Route::get('/wallet/{wallet}', [WalletController::class, 'show'])->name('wallet.show');
Route::get('/wallet/getByUser/{user_id}', [WalletController::class, 'getWalletByUser'])->name('wallet.get_by_user');
Route::put('/wallet/{wallet}', [WalletController::class, 'update'])->name('wallet.update');

Route::resource('/transaction', TransactionController::class);
Route::put('/transaction/confirmer/{transaction}', [TransactionController::class, 'transactionConfirmer'])->name('transaction.confirmer');

Route::get('/mail/transaction/confirmed/{user}', [MailController::class, 'confirmedTransaction']);