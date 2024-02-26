<?php

namespace App\Services;

use Illuminate\Http\Response;

class ResponseService
{
    public function default($config = [], $entityId = null, $method = 'GET') : array {
        $route = $config['route'];
        $status = false;
        $msg = '';
        $url = '';

        switch ($config['type']) {
            case 'store':
                $msg = 'Data inserted success!';
                $url = route($route);
                break;
            
            case 'show':
                $msg = 'Request made success!';
                $url = self::getUrl($route, $entityId);
                break;
            
            case 'destroy':
                $msg = 'Data deleted success!';
                $url = self::getUrl($route, $entityId);
                break;

            case 'update':
                $msg = 'Data updated success!';
                $url = self::getUrl($route, $entityId);
                break;

            default:
                $msg = 'Success request!';
                $url = self::getUrl($route, $entityId);
                break;
        }

        return [
            'status' => $status,
            'msg' => $msg,
            'url' => $url,
            'method' => $method
        ];
    }

    public static function getUrl($route, $entityId){
        return $entityId != null ? route($route,$entityId) : route($route);
    }

    public function exception($route, $entityId, $exception){
        $status = false;
        $statusCode = 500;
        $error = '';
        $url = '';

        switch ($exception->getCode()) {
            case -401:
            case -403:
            case -404:
                $status = false;
                $statusCode = abs($exception->getCode());
                $error = $exception->getMessage();
                $url = self::getUrl($route, $entityId);
                break;

            default:
                if(app()->bound('sentry')){
                    $sentry = app('sentry');
                    $user = auth()->user();
                    if($user){
                        $sentry->user_context(['id' => $user->id, 'name' => $user->name]);
                    }
                    $sentry->captureException($exception);
                }
                $status = false;
                $statusCode = 500;
                $error = $exception->getMessage();
                $url = self::getUrl($route, $entityId);
                break;
        }

        return response()->json([
            'status' => $status,
            'statusCode' => $statusCode,
            'error' => $error,
            'url' => $url
        ], $statusCode);
    }

    public function setStatudCode($config = []){
        return match($config) {
            'store' => Response::HTTP_CREATED,
            'destroy' => Response::HTTP_NO_CONTENT,
            default => Response::HTTP_OK
        };
    }
}
