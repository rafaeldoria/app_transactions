<?php

namespace App\Http\Controllers;

use Exception;
use Throwable;
use App\Models\User;
use Illuminate\Http\Request;
use App\Services\ResponseService;
use App\Services\Users\UserService;
use App\Resources\Users\UserResource;
use App\Resources\Users\UserResourceCollection;

class UserController extends Controller
{
    public function index()
    {
        try {
            $users = (new UserService())->index();
        } catch (Throwable |Exception $exception) {
            return (new ResponseService)->exception('user.index', null, $exception);
        }
        return new UserResourceCollection($users);
    }

    public function show(Int $userId)
    {   
        try {
            $user = (new UserService())->show($userId);
            if (!$user) {
                throw new Exception('Not found', -404);
            }
        } catch (Throwable|Exception $exception) {
            return (new ResponseService)->exception('user.show', $userId, $exception);
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
        } catch (Throwable|Exception $exception) {
            return (new ResponseService)->exception('user.store',null,$exception);
        }
        return new UserResource($user,[
            'type' => 'store',
            'route' => 'user.store'
        ]);
    }

    public function update(Int $userId, Request $request)
    {
        try {
            $user = (new UserService)->update($userId, $request->all());
        } catch (Throwable|Exception $exception) {
            return (new ResponseService)->exception('user.update',$userId,$exception);
        }
        return new UserResource($user,[
            'type' => 'update',
            'route' => 'user.update'
        ]);
    }
}
