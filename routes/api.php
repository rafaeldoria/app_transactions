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
    return response()->json([
        '-- APP TRANSACTIONS --'
    ]);
});

Route::get('/documentation', function (){
    return [
        'transaction_app_flow' => 'https://miro.com/app/board/uXjVNn0S_Eg=/?share_link_id=615403998221',
        'postman_api' => 'https://api.postman.com/collections/2773038-aaa970db-ea9d-496a-aff3-6e5f2456dcc0?access_key=APP_POSTMAN_KEY'
    ];
});

Route::resource('/user', UserController::class);

Route::resource('/document', DocumentController::class);
Route::get('/document/getByUser/{user_id}', [DocumentController::class, 'getDocumentByUser'])->name('document.get_by_user');

Route::get('/wallet', [WalletController::class, 'index'])->name('wallet.index');
Route::get('/wallet/{id}', [WalletController::class, 'show'])->name('wallet.show');
Route::get('/wallet/getByUser/{user_id}', [WalletController::class, 'getWalletByUser'])->name('wallet.get_by_user');
Route::put('/wallet/{id}', [WalletController::class, 'update'])->name('wallet.update');

Route::resource('/transaction', TransactionController::class);
Route::put('/transaction/confirmer/{id}', [TransactionController::class, 'transactionConfirmer'])->name('transaction.confirmer');
Route::get('/transaction/getByUser/{user_id}', [TransactionController::class, 'getTransactionByUser'])->name('transaction.get_by_user');

Route::get('/mail/transaction/confirmed/{user}', [MailController::class, 'confirmedTransaction']);