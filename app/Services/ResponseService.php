<?php

namespace App\Services;

use Exception;
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
                $url = $this->getUrl($route, $entityId);
                break;
            
            case 'destroy':
                $msg = 'Data deleted success!';
                $url = $this->getUrl($route, $entityId);
                break;

            case 'update':
                $msg = 'Data updated success!';
                $url = $this->getUrl($route, $entityId);
                break;

            default:
                $msg = 'Success request!';
                $url = $this->getUrl($route, $entityId);
                break;
        }

        if($msg!==''){$status = true;}

        return [
            'status' => $status,
            'msg' => $msg,
            'url' => $url,
            'method' => $method
        ];
    }

    public function getUrl($route, $entityId){
        return $entityId != null ? route($route,$entityId) : route($route);
    }

    public function exception($route, $entityId, $exception){
        try {
            $status = false;
            $statusCode = 500;
            $error = $exception->getMessage();
            
            $url = $this->getUrl($route, $entityId);
            
            $statusCode = match ($exception->getCode()) {
                -401, -403, -404 => abs($exception->getCode()),
                default => Response::HTTP_INTERNAL_SERVER_ERROR,
            };

            
            if(isset($exception->errorInfo[1])){
                $error = match ($exception->errorInfo[1]) {
                    1062 => 'Duplicate value',
                    1054 => 'Column not found',
                    1452 => 'Updating the user_id is not allowed.',
                    default => $exception->getMessage(),
                };
            }
            

            return response()->json([
                'status' => $status,
                'statusCode' => $statusCode,
                'error' => $error,
                'url' => $url
            ], $statusCode);
        } catch (\Throwable | Exception $exception) {
            return response()->json([
                'status' => 'false',
                'statusCode' => Response::HTTP_INTERNAL_SERVER_ERROR,
                'error' => $exception->getMessage(),
                'url' => ''
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
            return $exception->getMessage();
        }
        
    }

    public function setStatudCode($config = []){
        return match($config) {
            'store' => Response::HTTP_CREATED,
            'destroy' => Response::HTTP_NO_CONTENT,
            default => Response::HTTP_OK
        };
    }
}
