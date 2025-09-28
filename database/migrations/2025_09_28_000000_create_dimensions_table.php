<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


class CreateDimensionsTable extends Migration
{
    public function up()
    {
        Schema::create('dimensions', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->string('slug')->unique();
        $table->text('description')->nullable();
        $table->float('weight')->default(1); // optional
        $table->timestamps();
        });
    }


    public function down()
    {
        Schema::dropIfExists('dimensions');
    }
}