<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSigmaFieldsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sigma_fields', function (Blueprint $table) {
            $table->id();
            $table->string('EntityID');
            $table->string('UpStreamFieldID');
            $table->string('DownStreamFieldID');
            $table->integer('ConversionType');
            $table->boolean('Key')->default(false);
            $table->boolean('MandatoryValue')->default(false);
            $table->string('Comments');
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
        Schema::dropIfExists('sigma_fields');
    }
}
