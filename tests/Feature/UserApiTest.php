<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserApiTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_example()
    {
        $response = $this->post('/api/user/sendCode', ["phone" => "+905451130300 "]);
        $response->assertSuccessful();
        $user = User::query()->where("phone", "+905451130300")->first();
        $this->assertNotNull($user);
        $this->assertNotNull($user->code);

        $response = $this->post("api/user/login", ["phone" => "+905451130300","code"=>"123456"]);
        $response->assertStatus(411);
        $user=$user->fresh();
        $this->assertNull($user->token);

        $response = $this->post("api/user/login", ["phone" => "+905451130300","code"=>$user->code]);
        $response->assertSuccessful();
        $user=$user->fresh();
    }

}
