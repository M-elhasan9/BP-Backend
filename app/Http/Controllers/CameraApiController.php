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

        $report->image = $path;
        $report->description = $description;
        $report->lat_lang = Camera::query()->where('id','=',$stream)->first()->lat_lang;

        $report->save();
        $report->refresh();

        return $this->sendJsonResponse($report->toArray());

    }




    private function storeImage()
    {
        if ($image = request()->file('image')) {

            $uploadFolder = 'fires';

            $path = storage_path('app/public') . "/" . $uploadFolder . "/";

            if (!file_exists($path)) {
                File::makeDirectory($path, 0755, true);
            }

            $filename = time() . '.' . $image->getClientOriginalExtension();
            $simage = Image::make($image);

            $simage->resize(2048, 2048, function ($constraint) {
                $constraint->aspectRatio();
            });

            $simage->save($path . 'large' . $filename);

            return $uploadFolder . "/" . 'large' . $filename;
        }
        return null;
    }

}
