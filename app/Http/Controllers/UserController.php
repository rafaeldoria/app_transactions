<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Services\WalletService;
use App\Services\ResponseService;
use Illuminate\Http\JsonResponse;
use App\Resources\User\UserResource;
use App\Resources\User\UserResourceCollection;

class UserController extends Controller
{
    protected $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function index()
    {
        try {
            $users = $this->user->all();
        } catch (\Throwable |\Exception $e) {
            return ResponseService::exception('users.index', null, $e);
        }
        return new UserResourceCollection($users);
    }

    public function show(User $user)
    {   
        try {
            
        } catch (\Throwable|\Exception $e) {
            return ResponseService::exception('users.show', null, $e);
        }
        return new UserResource($user,[
            'type' => 'show',
            'route' => 'user.show'
        ]);
    }

    public function store(Request $request)
    {
        try {
            $user = $this->user->create($request->all());
            (new WalletService)->createWallet($user);
        } catch (\Throwable|\Exception $e) {
            return ResponseService::exception('user.store',null,$e);
        }
        return new UserResource($user,[
            'type' => 'store',
            'route' => 'user.store'
        ]);
    }

    public function update(User $user, Request $request)
    {
        try {
            $user->update($request->all());
        } catch (\Throwable|\Exception $e) {
            return ResponseService::exception('user.update',$user->id,$e);
        }
        return new UserResource($user,[
            'type' => 'update',
            'route' => 'user.update'
        ]);
    }

    public function destroy(User $user)
    {
        try {
            $user->delete();
        } catch (\Throwable|\Exception $e) {
            return ResponseService::exception('user.destroy',$user->id,$e);
        }
        return new UserResource($user,[
            'type' => 'destroy',
            'route' => 'user.destroy'
        ]); 
    }
}
