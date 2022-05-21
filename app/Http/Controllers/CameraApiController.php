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

        $this->fireNearMe($report, $lat, $lng);

        return $this->sendJsonResponse();

    }


}
