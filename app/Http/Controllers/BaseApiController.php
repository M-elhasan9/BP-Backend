<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Http\Request;

abstract class BaseApiController extends Controller
{

    use AuthorizesRequests, DispatchesJobs;

    protected function getLimitedQuery(Builder $query): Builder
    {
        return $query->limit($this->getLimit())->offset($this->getOffset());
    }

    protected function getLimit()
    {
        $limit = $this->getRequest()->input('limit', 10);
        if (!is_numeric($limit) || $limit <= 0) {
            $limit = 10;
        }
        return $limit;
    }

    protected function getRequest(): Request
    {
        return app('request');
    }

    protected function getOffset()
    {
        $offset = $this->getRequest()->input('offset', 0);
        if (!is_numeric($offset) || $offset < 0) {
            $offset = 0;
        }
        return $offset;
    }

    protected function sendJsonResponse($result = null, $count = null)
    {
        $response = [
            'success' => true,
            'data' => $result,
        ];

        if ($count != null)
            $response['count'] = $count;

        return $response;
    }


    protected function sendError($errormessage, $code, $errordata = null)
    {
        $response = [
            'success' => false,
            'message' => $errormessage,
        ];
        if ($errordata != null) {
            $response['data'] = $errordata;
        }
        return response()->json($response, $code);
    }

    protected function getLoggedInUser()
    {
        return $this->getRequest()->user();
    }
}
