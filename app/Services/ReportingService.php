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
        // Example fast aggregation using DB: compute employee EEI first in subquery (if responses are normalized as numbers)
        // Simpler implementation: load submissions and compute per submission (good for medium-size orgs)

        $query = Submission::query()->with('responses');
        if ($surveyYear) {
            $query->where('year', $surveyYear);
        }

        $grouped = [];

        $query->chunk(500, function ($submissions) use (&$grouped) {
            foreach ($submissions as $submission) {
                $dept = $submission->department ?? 'unknown';
                $eng = $this->engagementService->calculateEEIForSubmission($submission);

                if (is_null($eng)) continue;

                if (!isset($grouped[$dept])) {
                    $grouped[$dept] = ['sum' => 0, 'count' => 0];
                }

                $grouped[$dept]['sum'] += $eng;
                $grouped[$dept]['count']++;
            }
        });

        // compute averages
        $result = [];
        foreach ($grouped as $dept => $v) {
            $result[$dept] = [
                'avg_eei' => round($v['sum'] / $v['count'], 2),
                'count' => $v['count'],
            ];
        }

        return $result;
    }

    /**
     * Tech vs Non-Tech aggregation
     */
    public function groupEEIByTechFlag(int $surveyYear = null)
    {
        $query = Submission::query()->with('responses');
        if ($surveyYear) {
            $query->where('year', $surveyYear);
        }

        $buckets = [
            'tech' => ['sum' => 0, 'count' => 0],
            'non_tech' => ['sum' => 0, 'count' => 0],
        ];

        $query->chunk(500, function ($submissions) use (&$buckets) {
            foreach ($submissions as $submission) {
                $flag = $submission->is_tech ? 'tech' : 'non_tech';
                $eng = $this->engagementService->calculateEEIForSubmission($submission);
                if (is_null($eng)) continue;

                $buckets[$flag]['sum'] += $eng;
                $buckets[$flag]['count'] += 1;
            }
        });

        return [
            'tech' => $buckets['tech']['count'] ? round($buckets['tech']['sum'] / $buckets['tech']['count'], 2) : null,
            'non_tech' => $buckets['non_tech']['count'] ? round($buckets['non_tech']['sum'] / $buckets['non_tech']['count'], 2) : null,
        ];
    }

    /**
     * Attrition risk: returns list of employees with high risk based on Q91-Q100
     * Example: sum of answers in Q91-Q100 / number_of_questions -> higher means higher risk
     */
    public function attritionRiskList(int $surveyYear = null, float $threshold = 4.0)
    {
        // assume questions with IDs or tag 'attrition' exist; here we look up by question tags
        $attritionQuestionIds = DB::table('questions')->where('tag', 'attrition')->pluck('id');

        $query = Submission::query()->with(['responses' => function ($q) use ($attritionQuestionIds) {
            $q->whereIn('question_id', $attritionQuestionIds);
        }]);

        if ($surveyYear) $query->where('year', $surveyYear);

        $highRisk = [];

        $query->chunk(500, function ($subs) use (&$highRisk, $attritionQuestionIds, $threshold) {
            foreach ($subs as $sub) {
                $sum = 0;
                $count = count($attritionQuestionIds);

                $answers = $sub->responses->pluck('answer', 'question_id');

                foreach ($attritionQuestionIds as $qId) {
                    $sum += $answers->get($qId, 0);
                }

                if ($count === 0) continue;

                $avg = $sum / $count;

                if ($avg >= $threshold) {
                    $highRisk[] = [
                        'submission_id' => $sub->id,
                        'employee_code' => $sub->employee_code,
                        'department' => $sub->department,
                        'attrition_score' => round($avg, 2),
                    ];
                }
            }
        });

        return $highRisk;
    }
}