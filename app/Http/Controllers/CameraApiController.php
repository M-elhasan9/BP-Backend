<?php

namespace App\Http\Controllers;

use App\Http\Requests\CameraReportsRequest;
use App\Models\Camera;
use App\Models\Fire;
use App\Models\Report;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;

class CameraApiController extends BaseApiController
{

    public function addCameraReport(CameraReportsRequest $request)
    {
        $stream = $request->input("stream");
        $description = $request->input("description");
        $path = $request->input("path");
        $degree = $request->input("degree");
        $count = $request->input("count");

        $currentCamera = Camera::query()->where('id', '=', $stream)->first();
        $report = new Report();

        $report->reporter_id = $stream;
        $report->reporter_type = Camera::class;

        $report->image = $path;
        $report->den_degree = $degree;
        $report->count = $count;
        $report->nn_approval = true;
        $report->description = $description;

        $report->lat_lang = $currentCamera->lat_lang;
        $report->save();

        $lat = $currentCamera->lat_lang->lat;
        $lng = $currentCamera->lat_lang->lng;

        $query = Fire::query()->whereRaw("ST_Distance_Sphere( point(JSON_EXTRACT(lat_lang, '$.lng'),JSON_EXTRACT(lat_lang, '$.lat')), point($lng,$lat) )<1000")
            ->where('status', '=', 2)->first();

        if ($query == null) {
            $fire = new Fire();
            $fire->lat_lang = $currentCamera->lat_lang;
            $fire->status = 2;
            $fire->den_degree = $degree;
            $fire->save();
            $report->fire_id = $fire->id;
            $report->save();
        } else {
            $report->fire_id = $query->id;
            $report->save();
        }


        return $this->sendJsonResponse();

    }


}
