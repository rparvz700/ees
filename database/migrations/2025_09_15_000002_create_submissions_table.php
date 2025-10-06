<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubmissionsTable extends Migration
{
    public function up()
    {
        Schema::create('submissions', function (Blueprint $table) {
            $table->id();
            $table->year('year'); // which year's survey this belongs to
            $table->string('employee_code')->unique(); // Unique code per employee submission
            $table->string('hr_id'); // Employee HR system ID
            $table->string('department')->nullable();   // department name as-is (no foreign key)
            $table->boolean('is_tech')->default(false);        // group/role (Tech, Non-Tech, etc.)
            // Submission state
            $table->boolean('submitted')->default(false);
            $table->timestamp('submitted_at')->nullable();
            $table->float('eei')->nullable(); // Employee Engagement Index
            $table->json('dimension_scores')->nullable(); // JSON field to store per-dimension scores
            $table->json('reverse_inconsistency')->nullable(); // JSON field to store reverse coding inconsistency analysis
            $table->boolean('is_identical')->default(false); 
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('submissions');
    }
}
