<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateResponsesTable extends Migration
{
    public function up()
    {
        Schema::create('responses', function (Blueprint $table) {
            $table->id();

            // Survey meta at time of submission
            $table->json('question_order')->nullable(); // randomized order of questions shown
                       
            // Validation / flags
            $table->json('flags')->nullable();

            // Link to question,submission & answer value
            $table->foreignId('submission_id')->constrained('submissions')->onDelete('cascade');
            $table->foreignId('question_id')->constrained('questions')->onDelete('cascade');
            $table->unsignedTinyInteger('answer'); // Likert scale 1..5

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('responses');
    }
}
