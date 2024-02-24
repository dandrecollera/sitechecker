<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('imagehistory', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('siteid');
            $table->string('screenshot');
            $table->timestamps();

            $table->foreign('siteid')->references('id')->on('sitelist')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('imagehistory');
    }
};
