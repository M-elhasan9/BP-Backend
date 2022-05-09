<?php

namespace App\Http\Controllers;

use App\Http\Requests\CameraReportsRequest;
use App\Models\Camera;
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

        $report = new Report();

        $report->reporter_id = $stream;
        $report->reporter_type = Camera::class;

        $report->path = $path;
        $report->description = $description;

        $report->save();
        $report->refresh();

        return $this->sendJsonResponse($report->toArray());

    }

}
