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
        Schema::create('sitelist', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->text('url');
            $table->text('cache')->nullable();
            $table->string('screenshot')->default('img/Untitled-1.png');
            $table->boolean('wordpress_active');
            $table->boolean('tracking')->default('1');
            $table->boolean('active')->default('0');

            $table->dateTime('last_check')->nullable();
            $table->dateTime('last_change')->nullable();
            $table->dateTime('last_down')->nullable();
            $table->integer('downcount')->default(0);

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
        Schema::dropIfExists('sitelist');
    }
};
