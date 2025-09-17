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
            $table->string('department')->nullable();   // department name as-is (no foreign key)
            $table->string('group')->nullable();        // group/role (Tech, Non-Tech, etc.)
            
            // Submission state
            $table->boolean('submitted')->default(false);
            $table->timestamp('submitted_at')->nullable();
            
            // Validation / flags
            $table->json('flags')->nullable();

            // Link to question & answer value
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
