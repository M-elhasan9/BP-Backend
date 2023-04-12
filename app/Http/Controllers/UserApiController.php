<?php

namespace App\Http\Controllers;

use App\Http\Requests\ApiReportsRequest;
use App\Http\Requests\ApiUserLogInRequest;
use App\Http\Requests\ApiUserSendCodeRequest;
use App\Http\Requests\SubscribesRequest;
use App\Models\Fire;
use App\Models\Report;
use App\Models\Subscribe;
use App\Models\User;
use App\Ntfs\FireNearUser;
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\HasImage;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

use Kreait\Firebase\Exception\Messaging\InvalidArgument;
use Kreait\Firebase\Exception\Messaging\NotFound;
use File;
use NotificationChannels\Fcm\Exceptions\CouldNotSendNotification;


class UserApiController extends BaseApiController
{
    public function login(Request $request)
    {
        $fcm_token = $request->input("fcm_token");
        $phone = $request->input("phone");
        $code = $request->input("code");

        $user = User::query()->where("phone", $phone)->firstOrFail();


        if ($user->code === $code) {
            $user->tokens()->delete();

            $token = $user->createToken($request->header('User-Agent'));
            $user->fcm_token = $fcm_token;
            $user->save();
            $user->refresh();


            return $this->sendJsonResponse(["token" => $token->plainTextToken, "user" => $user]);
        } else {
            return $this->sendError("The code you entered is incorrect", 411);
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
        $user->code ="123456";// (string)rand(100000, 999999);
        $user->save();
        return $this->sendJsonResponse();
        if ($this->sendMassage($phone, $user->code)) {
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

    public function addUserReport(ApiReportsRequest $request)
    {
        $description = $request->input("description");
        $lat = $request->input("lat");
        $lang = $request->input("lng");
        $user_id = auth()->id();

        $report = new Report();

        $report->reporter_id = $user_id;
        $report->reporter_type = User::class;

        $report->description = $description;
        $report->lat_lang = ['lat' => $lat, 'lng' => $lang];

        $storedImagePath= $this->storeImage();

        $response = Http::get('http://nn.yesilkalacak.com/check', [
            'path' => $storedImagePath,
        ]);
        $r = $response->json();

        if (isset($r['error'])) {
            return $this->sendError($r['error'], 422);
        } else {
            if (isset($r['detect'])&&isset($r['decree'])){
            $report->nn_approval = $r['detect'];
            $report->den_degree = $r['decree'];
            }else{
                $report->nn_approval = false;
                $report->den_degree = false;
            }
            $report->image = $storedImagePath. "RES.jpg";
        }

        $report->save();
        $report->refresh();

        $this->fireNearMe($report, $lat, $lang);

        return $this->sendJsonResponse($report->toArray());

    }

    public function addUserSubscribe(SubscribesRequest $request)
    {
        $description = $request->input("description");
        $lat = $request->input("lat");
        $lang = $request->input("lng");

        $user_id = $request->user()->id;

        $subscribe = new Subscribe();
        $subscribe->user_id = $user_id;
        $subscribe->description = $description;
        $subscribe->lat_lang = ['lat' => $lat, 'lng' => $lang];

        $subscribe->save();

        return $this->sendJsonResponse($subscribe->toArray());
    }

    public function deleteUserSubscribe(Request $request, $id)
    {
        $subscribe = Subscribe::query()
            ->where('user_id', $request->user()->id)
            ->where('id', $id)
            ->delete();

        return $this->sendJsonResponse();

    }

    public function getSubscribes(Request $request)
    {
        $subscribes = Subscribe::query()->where('user_id', $request->user()->id)->get();

        return $this->sendJsonResponse($subscribes->toArray());
    }

    public function getActiveFires()
    {
        $confirmedFires = Fire::query()->where('status', '=', 2)->get();

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


    public function checkAndNotifyUsersNearReportFire(Report $suggestReport, $notify = true) // todo call this when change status
    {
        $users_ids = User::query()->pluck('id');

        foreach ($users_ids as $user_id) {
            $result = $this->getFiresNearUser($user_id, [$suggestReport]);
            if (isset($result)) {
                if ($notify) {

                    $user = User::query()->findOrFail($user_id);

                    if (isset($user->fcm_token)) {
                        try {
                            $fire_id = $result['fire']['id'];
                            $user->notify(new FireNearUser('fire_near_user', $fire_id));
                        } catch (NotFound $e) {
                            // $token is not registered to the project (any more)
                            // Handle the token (e.g. delete it in a local database)
                            $user->fcm_token = null;
                            $user->save();
                        } catch (InvalidArgument $e) {
                            // $token is not a *valid* registration token, meaning
                            // the format is invalid, OR the message was invalid

                            $user->fcm_token = null;
                            $user->save();
                        } catch (CouldNotSendNotification $e) {

                            $user->fcm_token = null;
                            $user->save();
                        }
                    }


                }

                return $result;
            }
        }

        return null;

    }


    public function getFiresNearUser($user_id, $suggestReports = null)
    {
        $subscribes = Subscribe::query()->where('user_id', $user_id)->get(); //TODO:
        $confirmedFires = $suggestReports ?? Report::query()->where('status', '=', 'Confirmed')->get();

        foreach ($subscribes as $sub) {
            foreach ($confirmedFires as $fire) {
                $lat1 = $sub->lat_lang['lat'];
                $lon1 = $sub->lat_lang['lng'];
                //
                $lat2 = $fire->lat_lang['lat'];
                $lon2 = $fire->lat_lang['lng'];
                //
                $distanceRange = 10;   //0.11;
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
