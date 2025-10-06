<?php

namespace App\Services;

use App\Models\Submission;

class EngagementService
{
    protected $dimensionService;

    public function __construct(DimensionService $dimensionService)
    {
        $this->dimensionService = $dimensionService;
    }

    /**
     * Calculate the overall EEI (Employee Engagement Index) for a submission.
     * This averages across all dimension scores.
     */
    public function calculateEEIForSubmission(Submission $submission)
    {
        // Get all dimension scores
        $dimensionScores = $this->dimensionService->calculateAllDimensionScores($submission);

        if (empty($dimensionScores)) {
            return null;
        }

        // Average across all dimensions
        $sum = array_sum($dimensionScores);
        $count = count($dimensionScores);

        return $count > 0 ? round($sum / $count, 2) : null;
    }

    /**
     * Calculate engagement scores for each dimension of a submission.
     * Returns array like: [ 'teamwork' => 3.75, 'leadership' => 4.20 ]
     */
    public function calculateSubmissionEngagement(Submission $submission)
    {
        return $this->dimensionService->calculateAllDimensionScores($submission);
    }

    public function storeSubmissionEEI(Submission $submission)
    {
        $eei = $this->calculateEEIForSubmission($submission);
        $dimensionScores = $this->calculateSubmissionEngagement($submission);
        $inconsistentDimensions = $this->dimensionService->analyzeReverseCodingConsistency($submission);
        dd($inconsistentDimensions);
        // Update submission with EEI and dimension scores
        $submission->eei = $eei;
        $submission->dimension_scores = !empty($dimensionScores) ? json_encode($dimensionScores) : null;
        $submission->reverse_inconsistency = !empty($inconsistentDimensions) ? $inconsistentDimensions->toJson(JSON_PRETTY_PRINT) : null;
        $submission->save();

        return $submission;
    }
}
