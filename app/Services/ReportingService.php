<?php
namespace App\Services;

use App\Models\Submission;
use App\Models\Dimension;
use Illuminate\Support\Facades\DB;

class ReportingService
{
    protected $dimensionService;
    protected $engagementService;

    public function __construct(DimensionService $dimensionService, EngagementService $engagementService)
    {
        $this->dimensionService = $dimensionService;
        $this->engagementService = $engagementService;
    }

    /**
     * Aggregate EEI by department
     * Returns: [ department_id => ['avg_eei' => x, 'count' => n] ]
     */
    public function departmentEEIReport(int $surveyYear = null)
    {
        $query = Submission::query();

        if ($surveyYear) {
            $query->where('year', $surveyYear);
        }

        // Only include submissions that already have EEI calculated
        $query->whereNotNull('eei');

        // Group by department and compute averages
        $rows = $query->selectRaw('department, AVG(eei) as avg_eei, COUNT(*) as total_employees')
                    ->groupBy('department')
                    ->get();

        // Convert to unified tabular array
        $result = [];
        foreach ($rows as $row) {
            $result[] = [
                'Department'      => $row->department ?? 'Unknown',
                'Average EEI'     => round($row->avg_eei, 2),
                'Total Employees' => (int) $row->total_employees,
            ];
        }

        // Optional: sort alphabetically by department
        usort($result, fn($a, $b) => strcmp($a['Department'], $b['Department']));

        return $result;
    }


    public function departmentDimensionScoresReport(int $surveyYear = null)
    {
        $query = Submission::query();

        if ($surveyYear) {
            $query->where('year', $surveyYear);
        }

        // Only include submissions that already have dimension_scores set
        $query->whereNotNull('dimension_scores');

        $grouped = [];

        $query->chunk(500, function ($submissions) use (&$grouped) {
            foreach ($submissions as $submission) {
                $dept = $submission->department ?? 'Unknown';

                $scores = json_decode($submission->dimension_scores, true);
                if (!is_array($scores)) {
                    continue;
                }

                foreach ($scores as $dimension => $score) {
                    if (!isset($grouped[$dept][$dimension])) {
                        $grouped[$dept][$dimension] = ['sum' => 0, 'count' => 0];
                    }

                    $grouped[$dept][$dimension]['sum'] += $score;
                    $grouped[$dept][$dimension]['count']++;
                }
            }
        });

        // Build final result: each department as one row
        $result = [];
        foreach ($grouped as $dept => $dimensions) {
            $row = ['Department' => $dept];
            foreach ($dimensions as $dimension => $v) {
                $row[$dimension] = round($v['sum'] / $v['count'], 2);
            }
            $result[] = $row;
        }

        return $result;
    }

    public function employeeDimensionScoresReport(int $surveyYear = null)
    {
        $query = Submission::query();

        if ($surveyYear) {
            $query->where('year', $surveyYear);
        }

        // Only include submissions that already have dimension_scores set
        $query->whereNotNull('dimension_scores');

        $result = [];

        $query->chunk(500, function ($submissions) use (&$result) {
            foreach ($submissions as $submission) {
                $employee = $submission->hr_id ?? 'Unknown';
                $dept = $submission->department ?? 'Unknown';

                $scores = json_decode($submission->dimension_scores, true);
                if (!is_array($scores)) {
                    continue;
                }

                // Build row: include employee and department info
                $row = [
                    'Employee HR ID' => $employee,
                    'Department'    => $dept,
                ];

                // Add dimension scores dynamically
                foreach ($scores as $dimension => $score) {
                    $row[$dimension] = round($score, 2);
                }

                $result[] = $row;
            }
        });

        // Optional: sort alphabetically by Employee Code
        usort($result, fn($a, $b) => strcmp($a['Employee HR ID'], $b['Employee HR ID']));

        return $result;
    }


}