<?php

namespace App\Http\Controllers;

use App\Models\Response;
use App\Models\Question;
use App\Models\Submission;
use App\Models\Dimension;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use App\Services\ReportingService;
use App\Services\DimensionService;
use App\Services\EngagementService;


class ReportController extends Controller
{
    // public function check()
    // {
    //     $submission = Submission::find(1);

    //     $reporting = new ReportingService();

    //     // Per-dimension scores
    //     $dimensionScores = $reporting->calculateDimensionScores($submission);
    //     /*
    //     [
    //     ["dimension" => "Skills", "score" => 4.25],
    //     ["dimension" => "Performance", "score" => 3.80],
    //     ...
    //     ]
    //     */

    //     // Overall EEI
    //     $eei = $reporting->calculateEEI($submission); // e.g. 4.02
    //     dd($dimensionScores, $eei);
    // }

    public function index()
    {
        
        // $engagementService = new EngagementService(new DimensionService());
        // $submission = Submission::find(8);

        // $eei = $engagementService->calculateEEIForSubmission($submission);
        // $dimensionBreakdown = $engagementService->calculateSubmissionEngagement($submission);

        // //dd($eei, $dimensionBreakdown);
        // $engagementService->storeSubmissionEEI($submission);
        $reportingService = new ReportingService(new DimensionService(), new EngagementService(new DimensionService()));
        $report1 = $reportingService->departmentEEIReport(2025);
        $report2 = $reportingService->departmentDimensionScoresReport(2025);
        // dd($report1, $report2);
        $reportList = [
            'report1' => $report1,
            'report2' => $report2,
        ];
        $activeMenu = 'reports'; // highlight reports in sidebar
        return view('reports.index', compact('reportList', 'activeMenu'));
    }

    public function show(Request $request, $reportName)
    {
        $year = $request->query('year', null);
        $reportingService = new ReportingService(new DimensionService(), new EngagementService(new DimensionService()));
        $data = [];
        if ($reportName === 'Department EEI Report') {
            $data = $reportingService->departmentEEIReport($year);
        } elseif ($reportName === 'Department Dimension Scores Report') {
            $data = $reportingService->departmentDimensionScoresReport($year);
        } elseif ($reportName === 'Employee Dimension Scores Report') {
            $data = $reportingService->employeeDimensionScoresReport($year);
        }
        else {
            return response()->json(['error' => 'Unknown report name'], 400);
        }
        //dd($data);
        return view('reports.show', compact('reportName', 'data', 'year'));
    }

}
