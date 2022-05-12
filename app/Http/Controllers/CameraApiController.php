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
        $degree = $request->input("degree");
        $count = $request->input("count");

        $report = new Report();

        $report->reporter_id = $stream;
        $report->reporter_type = Camera::class;

        $report->image = $path;
        $report->den_degree = $degree;
        $report->count = $count;
        $report->nn_approval = true;
        $report->description = $description;
        $report->lat_lang = Camera::query()->where('id','=',$stream)->first()->lat_lang;

        $report->save();


        //$this->getTheSameFires($stream);



        return $this->sendJsonResponse();

    }


    public function getTheSameFires($camera_id)
    {
        $camera = Camera::query()->where('id', $camera_id)->get(); //TODO:
        $oldReports = Report::query()->get();

            foreach ($oldReports as $oldReport) {
                $lat1 = $camera->lat_lang['lat'];
                $lon1 = $camera->lat_lang['lng'];
                //
                $lat2 = $oldReport->lat_lang['lat'];
                $lon2 = $oldReport->lat_lang['lng'];
                //
                $distanceRange = 1;
                //

                $distance = $this->point2point_distance($lat1, $lon1, $lat2, $lon2);
                if ($distance <= $distanceRange) {
                    return array('distance' => $distance, 'fire' => $oldReport->toArray(), 'subscribe' => $camera);
                }

        }

        return null;
    }


    public function point2point_distance($lat1, $lon1, $lat2, $lon2, $unit = 'K')
    {
        $theta = $lon1 - $lon2;
        $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
        $dist = acos($dist);
        $dist = rad2deg($dist);
        $miles = $dist * 60 * 1.1515;
        $unit = strtoupper($unit);

        if ($unit == "K") {
            return ($miles * 1.609344);
        } else if ($unit == "N") {
            return ($miles * 0.8684);
        } else {
            return $miles;
        }
    }

}
