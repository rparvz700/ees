<?php
namespace App\Services;


use App\Models\Submission;
use App\Models\Dimension;


class EngagementService
{
    protected $dimensionService;


    // Default expected number of dimensions (10) can be configured
    protected $expectedDimensions;


    public function __construct(DimensionService $dimensionService)
    {
        $this->dimensionService = $dimensionService;
        $this->expectedDimensions = config('ees.dimensions_count', 10);
    }


    /**
    * Calculate EEI for a single submission (employee)
    * EEI = sum(all dimension scores) / expected_dimensions
    */
    public function calculateEEIForSubmission(Submission $submission): ?float
    {
        $dimensionsScores = $this->dimensionService->calculateAllDimensionsForSubmission($submission);


        $sum = 0;
        $counted = 0;


        foreach ($dimensionsScores as $d) {
            if (!is_null($d['score'])) {
                $sum += $d['score'];
                $counted++;
        }
    }


    // if no dimension had responses, return null
    if ($counted === 0) {
        return null;
    }


    // Use expectedDimensions as denominator so EEI is comparable across survey versions
    $denominator = max($this->expectedDimensions, $counted);


        return round($sum / $denominator, 2);
    }


    /**
    * Convenience: returns both dimension scores and EEI
    */
    public function calculateSubmissionEngagement(Submission $submission): array
    {
        $dimensions = $this->dimensionService->calculateAllDimensionsForSubmission($submission);
        $eei = $this->calculateEEIForSubmission($submission);


        return [
            'dimensions' => $dimensions,
            'eei' => $eei,
        ];
    }
}