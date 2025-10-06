<?php
namespace App\Services;


use App\Models\Submission;
use App\Models\Response;
use Illuminate\Support\Facades\Log;


class DimensionService
{
    /**
    * Calculate the dimension score for a submission and a given dimension.
    * Formula: sum(responses for questions in the dimension) / number_of_questions
    * Returns null if no questions (or no responses) found.
    */
    // public function calculateDimensionScore(Submission $submission)    {

    //     $responses = $submission->responses()->with('question')->get();
    //     $questions = $responses->pluck('question.id');

    //     if ($questions->isEmpty()) {
    //         return null;
    //     }


    //     $sum = $submission->responses()
    //     ->whereIn('question_id', $questions)
    //     ->sum('answer'); // assuming numeric answers stored in `answer`


    //     $count = $questions->count();


    //     if ($count === 0) {
    //         return null;
    //     }


    //     return round($sum / $count, 2);
    // }

    public function calculateDimensionScore(Submission $submission, string $dimension)
    {
        // Load responses with questions
        $responses = $submission->responses()
            ->whereHas('question', function ($query) use ($dimension) {
                $query->whereJsonContains('dimension', $dimension);
            })
            ->with('question')
            ->get();

        if ($responses->isEmpty()) {
            return null;
        }

        // Transform answers considering reverse_coded
        $scores = $responses->map(function ($response) {
            if ($response->question && $response->question->reverse_coded) {
                return 5 - $response->answer;
            }
            return $response->answer;
        });

        $sum = $scores->sum();
        $count = $scores->count();

        if ($count === 0) {
            return null;
        }

        return round($sum / $count, 2);
    }




    /**
    * Returns an associative array of [dimension_id => score]
    */
    public function calculateAllDimensionScores(Submission $submission)
    {
        // Load all responses with questions
        $responses = $submission->responses()->with('question')->get();

        if ($responses->isEmpty()) {
            return [];
        }

        $dimensionScores = [];

        // Group responses by each dimension in their question
        foreach ($responses as $response) {
            if (!$response->question) {
                continue;
            }

            // Decode JSON field (could be array of strings)
            $questionDimensions = is_array($response->question->dimension)
                ? $response->question->dimension
                : json_decode($response->question->dimension, true);
            
            if (empty($questionDimensions)) {
                continue;
            }

            // Calculate answer (consider reverse_coded)
            $answer = $response->question->reverse_coded
                ? 5 - $response->answer
                : $response->answer;

            // Add to each dimension bucket
            foreach ($questionDimensions as $dim) {
                if (!isset($dimensionScores[$dim])) {
                    $dimensionScores[$dim] = [
                        'sum'   => 0,
                        'count' => 0,
                    ];
                }

                $dimensionScores[$dim]['sum']   += $answer;
                $dimensionScores[$dim]['count'] += 1;
            }
        }

        // Compute averages
        $result = [];
        foreach ($dimensionScores as $dim => $data) {
            if ($data['count'] > 0) {
                $result[$dim] = round($data['sum'] / $data['count'], 2);
            }
        }

        return $result;
    }

    // public function analyzeReverseCodingConsistency($submission)
    // {   dd($submission);
    //     $responses = collect($submission['responses'] ?? []);

    //     if ($responses->isEmpty()) {
    //         return collect(); // nothing to analyze
    //     }

    //     // Group responses by dimension
    //     $grouped = $responses->groupBy('dimension');
        
    //     // Find dimensions where reverse-coded answers don't match expected opposite trend
    //     $inconsistentDimensions = $grouped->filter(function ($responses) {
    //         $normal = $responses->where('reverse_coded', false)->pluck('answer')->filter()->values();
    //         $reverse = $responses->where('reverse_coded', true)->pluck('answer')->filter()->values();
    //         echo "\nNormal: "; print_r($normal->toArray());
    //         echo "\nReverse: "; print_r($reverse->toArray());
    //         if ($normal->isEmpty() || $reverse->isEmpty()) {
    //             return false; // skip if no pair to compare
    //         }

    //         $normalAvg = $normal->avg();
    //         $reverseAvg = $reverse->avg();

    //         // Expected: high normal + low reverse ≈ 6 (for 5-point scale)
    //         $expectedSum = 6;
    //         $difference = abs(($normalAvg + $reverseAvg) - $expectedSum);

    //         // If they are too close (both high or both low), mark inconsistent
    //         return $difference > 1.5;
    //     })->keys();
    //     dd($inconsistentDimensions);
    //     // Optional: store as JSON summary for later auditing
    //     // $submission->reverse_inconsistency = $inconsistentDimensions->toJson(JSON_PRETTY_PRINT);
    //     // $submission->save();

    //     // Return just the inconsistent dimension names
    //     return $inconsistentDimensions->values();
    // }
    public function analyzeReverseCodingConsistency(Submission $submission)
    {
        // Ensure responses and their questions are loaded
        $submission->loadMissing('responses.question');

        $responses = $submission->responses;

        if ($responses->isEmpty()) {
            return collect(); // nothing to analyze
        }

        // Flatten each response into (dimension, answer, reverse_coded)
        $flat = $responses->flatMap(function ($resp) {
            $question = $resp->question;
            if (!$question) {
                return []; // skip if relation missing
            }

            $dims = $question->dimensions ?? [];
            if (!is_array($dims)) {
                $dims = json_decode($dims, true) ?: [$dims];
            }

            return collect($dims)->map(function ($dim) use ($resp, $question) {
                return [
                    'dimension' => $dim,
                    'answer' => $resp->answer,
                    'reverse_coded' => (bool) $question->reverse_coded,
                ];
            });
        });

        // Group by dimension
        $grouped = $flat->groupBy('dimension');

        // Find inconsistent dimensions
        $inconsistentDimensions = $grouped->filter(function ($items) {
            $normal = $items->where('reverse_coded', false)->pluck('answer')->filter();
            $reverse = $items->where('reverse_coded', true)->pluck('answer')->filter();
            dd($normal->toArray(), $reverse->toArray());
            if ($normal->isEmpty() || $reverse->isEmpty()) {
                return false; // skip if no data for both
            }

            $normalAvg = $normal->avg();
            $reverseAvg = $reverse->avg();

            // For 5-point scale: high normal + low reverse ≈ 6
            $expectedSum = 6;
            $diff = abs(($normalAvg + $reverseAvg) - $expectedSum);

            // If too close (both high or both low), it's inconsistent
            return $diff > 1.5;
        })->keys();

        // Optional: store inconsistent dimensions back into submission
        // $submission->reverse_inconsistency = $inconsistentDimensions->values()->toJson(JSON_PRETTY_PRINT);
        // $submission->save();

        // Return inconsistent dimension names
        return $inconsistentDimensions->values();
    }

}