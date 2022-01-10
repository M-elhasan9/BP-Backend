<?php

namespace App\Http\Controllers;

use App\Http\Requests\ApiReportsRequest;
use App\Http\Requests\ApiUserLogInRequest;
use App\Http\Requests\ApiUserSendCodeRequest;
use App\Http\Requests\ReportsRequest;
use App\Models\Reports;
use App\Models\User;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\HasImage;
use Illuminate\Support\Facades\Storage;

class UserApiController extends BaseApiController
{
    public function login(ApiUserLogInRequest $request)
    {
        $phone = $request->input("phone");
        $code = $request->input("code");

        $user = User::query()->where("phone", $phone)->firstOrFail();


        if ($user->code === $code) {
            //$user->tokens()->delete();
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
        DB::transaction(function () use ($request) {

            $description = $request->input("description");
            $lat_lang = $request->input("lat_lang");
            $user_id = $request->user()->id;
            $user = User::query()->where('id', $user_id)->get()->first();

            $image = $request->file('image');
            $destinationPath = "public/images/fires";


            $report = new Reports();
            $report->user_id = $user->id;
            $report->description = $description;
            $report->lat_lang = $lat_lang;

//            $filename = $user->id . '_' . $report->id . '.' . $image->extension();
//            Storage::putFileAs($destinationPath, $image, $filename);
            $report->save();
        });

    }

}
