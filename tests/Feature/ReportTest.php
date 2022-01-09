<?php

namespace Tests\Feature;

use App\Models\Reports;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Tests\TestCase;

class ReportTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_report_example()
    {
        $response = $this->post('api/user/getFireRequestFromUser?description=des&lat_lang=lat&users_id=2');

    }
}
