<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->string('status')->nullable(true);
            $table->string('description')->nullable(true);
            $table->string('path')->nullable(true);
            $table->integer('count')->nullable(true);
            $table->boolean('nn_approval')->default(false);
            $table->morphs('reporter');
            $table->string('den_degree')->nullable(true);
            $table->json('lat_lang')->nullable(true);
            $table->string('image')->nullable(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reports');
    }
}
