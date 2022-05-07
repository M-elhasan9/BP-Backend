<?php

namespace App\Http\Controllers;

use App\Http\Requests\ApiReportsRequest;
use App\Http\Requests\ApiUserLogInRequest;
use App\Http\Requests\ApiUserSendCodeRequest;
use App\Http\Requests\ReportsRequest;
use App\Http\Requests\SubscribesRequest;
use App\Models\Reports;
use App\Models\Subscribes;
use App\Models\User;
use FireNearUser;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\HasImage;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;


use File;


class UserApiController extends BaseApiController
{
    public function login(Request $request)
    {
        $phone = $request->input("phone");
        $code = '112233'; // $request->input("code"   );

        $user = User::query()->where("phone", $phone)->firstOrFail();


        if (true) { // $user->code == $code) {
            //  $user->tokens()->delete();

            $token = $user->createToken($request->header('User-Agent'));


            return $this->sendJsonResponse(["token" => $token->plainTextToken, "user" => $user]);
        } else {
            return $this->sendError("Girdiğiniz kod yanliş", 411);
        }
    }

    public function sendCode(ApiUserSendCodeRequest $request)
    {
        $phone = $request->input("phone");
        $user = User::query()->where("phone", $phone)->first();

        if ($user == null) {
            $user = new User();
            $user->phone = $phone;
            $user->save();
        }
        $user->code = (string)rand(100000, 999999);
        $user->save();
        if ($this->sendMassage($phone, $user->code)) {
            return $this->sendJsonResponse();
        } else {
            return $this->sendError("Bir hata oluştu,Lütfen tekrar deneyiniz", 411);
        }

    }

    private function sendMassage($phone, $code)
    {
        $api_key = config('app.turkeysmskey');
        $title = "8507013986";
        $text = "Uygulamaya giris kodu:" . $code;
        $sentto = $phone;

        $data = array("api_key" => $api_key, "title" => $title, "text" => $text, "sentto" => $sentto);
        $json = json_encode($data);

        $client = new Client();
        $params = [
            'body' => $json
        ];
        $uri = 'https://www.turkeysms.com.tr/api/v3/gonder/add-content';
        $rep = $client->request('POST', $uri, $params)->getBody()->getContents();

        $reponse = $this->print_r_reverse($rep);

        if ($reponse["result"] == true) {
            return true;
        }
        return false;
    }

    private function print_r_reverse($input)
    {
        $output = str_replace(['[', ']'], ["'", "'"], $input);
        $output = preg_replace('/=> (?!Array)(.*)$/m', "=> '$1',", $output);
        $output = preg_replace('/^\s+\)$/m', "),\n", $output);
        $output = rtrim($output, "\n,");
        return eval("return $output;");
    }

    public function getUser()
    {
        return $this->getLoggedInUser();
    }

    public function addReport(ApiReportsRequest $request)
    {
        $description = $request->input("description");
        $lat = $request->input("lat");
        $lang = $request->input("lang");
        $user_id = auth()->id();

        $report = new Reports();
        $report->user_id = $user_id;
        $report->description = $description;
        $report->lat_lang = json_encode(array(['lat' => $lat, 'lang' => $lang]));
        $report->image = $this->storeImage();

        $report->save();
        $report->refresh();

        return $this->sendJsonResponse($report->toArray());

    }

    public function addSubscribe(SubscribesRequest $request)
    {
        $description = $request->input("description");
        $lat = $request->input("lat");
        $lang = $request->input("lang");

        $user_id = $request->user()->id;

        $subscribe = new Subscribes();
        $subscribe->user_id = $user_id;
        $subscribe->description = $description;
        $subscribe->lat_lang = json_encode(array(['lat' => $lat, 'lang' => $lang]));

        $subscribe->save();
        return $this->sendJsonResponse($subscribe->toArray());
    }

    public function deleteSubscribe(Request $request, $id)
    {
        $subscribe = Subscribes::query()
            ->where('user_id', $request->user()->id)
            ->where('id', $id)
            ->delete();

        return $this->sendJsonResponse();

    }

    public function getSubscribes(SubscribesRequest $request)
    {
        $subscribes = Subscribes::query()->where('user_id', $request->user()->id)->get();

        return $this->sendJsonResponse($subscribes->toArray());
    }

    public function getConfirmedReports()
    {
        $confirmedFires = Reports::query()->where('status', '=', 'Confirmed')->get();

        return $this->sendJsonResponse($confirmedFires->toArray());
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


    public function getFiresNearMe(Request $request)
    {


        return $this->sendJsonResponse($this->getFiresNearUser($request->user()->id));
    }


    public function checkAndNotifyUsersNearReportFire(Reports $suggestReport, $notify = true) // todo call this when change status
    {
        $users_ids = User::query()->pluck('id');

        foreach ($users_ids as $user_id) {
            $result = $this->getFiresNearUser($user_id, [$suggestReport]);
            if (isset($result)) {
                if ($notify) {
                    User::query()->findOrFail($user_id)->notify(new FireNearUser );
                }

                return $result;
            }
        }

        return null;

    }


    public function getFiresNearUser($user_id, $suggestReports = null)
    {
        $subscribes = Subscribes::query()->where('user_id', $user_id)->get();
        $confirmedFires = $suggestReports ?? Reports::query()->where('status', '=', 'Confirmed')->get();

        foreach ($subscribes as $sub) {
            foreach ($confirmedFires as $fire) {
                $lat1 = $sub->lat_lang['lat'];
                $lon1 = $sub->lat_lang['lang'];
                //
                $lat2 = $fire->lat_lang['lat'];
                $lon2 = $fire->lat_lang['lang'];
                //
                $distanceRange = 1;   //0.11;
                //

                $distance = $this->point2point_distance($lat1, $lon1, $lat2, $lon2);
                if ($distance <= $distanceRange) {
                    return array('distance' => $distance, 'fire' => $fire->toArray(), 'subscribe' => $sub);
                }
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
