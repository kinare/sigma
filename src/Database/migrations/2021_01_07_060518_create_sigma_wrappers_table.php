<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSigmaWrappersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sigma_wrappers', function (Blueprint $table) {
            $table->id();
            $table->string('entityID');
            $table->string('upStreamID');
            $table->string('downStreamID');
            $table->string('provider');
            $table->string('description')->nullable();
            $table->boolean('disabled');
            $table->string('comments')->nullable();
            $table->boolean('Get')->default(true);
            $table->boolean('Edit')->default(true);
            $table->boolean('Insert')->default(true);
            $table->boolean('Delete')->default(true);
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
        Schema::dropIfExists('sigma_wrappers');
    }
}
