<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSigmaProvidersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sigma_providers', function (Blueprint $table) {
            $table->id();
            $table->string('Provider');
            $table->string('connectorGroup');
            $table->string('authType');
            $table->string('providerType');
            $table->string('company');
            $table->string('baseConnectionPath');
            $table->string('authUrl');
            $table->string('userName')->nullable();
            $table->string('password')->nullable();
            $table->string('version');
            $table->string('remarks')->nullable();
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
        Schema::dropIfExists('sigma_providers');
    }
}
