<?php

namespace App\Http\Controllers;

use App\Models\Fire;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Http\Request;
use Prologue\Alerts\Facades\Alert;

abstract class BaseApiController extends Controller
{

    use AuthorizesRequests, DispatchesJobs;

    public function fireNearMe($report, $lat, $lng)
    {
        $query = Fire::query()->whereRaw("ST_Distance_Sphere( point(JSON_EXTRACT(lat_lang, '$.lng'),JSON_EXTRACT(lat_lang, '$.lat')), point($lng,$lat) )<1000")
            ->where('status', '=', 2)->first();

        if ($query == null) {
            $fire = new Fire();
            $fire->lat_lang = $report->lat_lang;
           // $fire->den_degree = $report->den_degree;
            $fire->save();
            $report->fire_id = $fire->id;
            Alert::error('New Fire')->flash();
        } else {
            $report->fire_id = $query->id;
        }
        $report->save();
    }

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
