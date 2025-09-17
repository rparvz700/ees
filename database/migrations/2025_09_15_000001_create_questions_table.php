<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuestionsTable extends Migration
{
    public function up()
    {
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->year('year'); // which year's survey this belongs to
            $table->unsignedSmallInteger('number')->unique(); // e.g., 1..100
            $table->text('text'); // actual question text
            $table->json('dimensions')->nullable(); // ["Leadership","Attrition Risk"] // e.g., Leadership, Skills, Wellbeing
            $table->boolean('reverse_coded')->default(false); // whether answer scoring is reversed
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('questions');
    }
}
