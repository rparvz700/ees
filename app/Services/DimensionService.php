<?php
namespace App\Services;


use App\Models\Submission;
use App\Models\Dimension;
use Illuminate\Support\Facades\Log;


class DimensionService
{
    /**
    * Calculate the dimension score for a submission and a given dimension.
    * Formula: sum(responses for questions in the dimension) / number_of_questions
    * Returns null if no questions (or no responses) found.
    */
    public function calculateDimensionScore(Submission $submission, Dimension $dimension): ?float
    {
        // eager load questions to minimize queries
        $questions = $dimension->questions()->pluck('id');


        if ($questions->isEmpty()) {
        return null;
    }


        $sum = $submission->responses()
        ->whereIn('question_id', $questions)
        ->sum('answer'); // assuming numeric answers stored in `answer`


        $count = $questions->count();


        if ($count === 0) {
        return null;
    }


        return round($sum / $count, 2);
    }


    /**
    * Returns an associative array of [dimension_id => score]
    */
    public function calculateAllDimensionsForSubmission(Submission $submission)
    {
        $result = [];
        $dimensions = Dimension::with('questions')->get();


        foreach ($dimensions as $dimension) {
            $score = $this->calculateDimensionScore($submission, $dimension);
            $result[$dimension->id] = [
            'slug' => $dimension->slug,
            'name' => $dimension->name,
            'score' => $score,
        ];
        }


        return $result;
    }
}