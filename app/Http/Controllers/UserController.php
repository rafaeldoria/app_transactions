<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Services\ResponseService;
use App\Resources\Users\UserResource;
use App\Resources\Users\UserResourceCollection;
use App\Services\Users\UserService;

class UserController extends Controller
{
    public function index()
    {
        try {
            $users = (new UserService())->index();
        } catch (\Throwable |\Exception $e) {
            return ResponseService::exception('users.index', null, $e);
        }
        return new UserResourceCollection($users);
    }

    public function show(Int $id)
    {   
        try {
            $user = (new UserService())->show($id);
            if (!$user) {
                throw new \Exception('Not found', -404);
            }
        } catch (\Throwable|\Exception $e) {
            return ResponseService::exception('users.show', $id, $e);
        }
        return new UserResource($user,[
            'type' => 'show',
            'route' => 'user.show'
        ]);
    }

    public function store(Request $request)
    {
        try {
            $user = (new UserService())->store($request->all());
        } catch (\Throwable|\Exception $e) {
            return ResponseService::exception('user.store',null,$e);
        }
        return new UserResource($user,[
            'type' => 'store',
            'route' => 'user.store'
        ]);
    }

    public function update(Int $id, Request $request)
    {
        try {
            $user = (new UserService)->update($id, $request->all());
        } catch (\Throwable|\Exception $e) {
            return ResponseService::exception('user.update',$id,$e);
        }
        return new UserResource($user,[
            'type' => 'update',
            'route' => 'user.update'
        ]);
    }

    public function destroy(Int $id)
    {
        try {
            $user = (new UserService)->destroy($id);
            $user->id = $id;
        } catch (\Throwable|\Exception $e) {
            return ResponseService::exception('user.destroy',$id,$e);
        }
        return new UserResource($user,[
            'type' => 'destroy',
            'route' => 'user.destroy'
        ]); 
    }
}
