<?php

namespace App\Http\Controllers;

use App\Models\Response;
use App\Models\Question;
use App\Models\Submission;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;

use App\Services\DimensionService;
use App\Services\EngagementService;

class ResponseController extends Controller
{
    public function list(Request $request)
    {
        $query = Submission::query();
        return DataTables::of($query)
        ->addColumn('actions', function ($submission) 
            { return view('responses.partials.actions', compact('submission'))->render(); }) 
        ->rawColumns(['actions'])
        ->make(true);
    }
    // announcements
    public function announceCreate()
    {
        $activeMenu = 'announce';
        return view('responses.announce_create', compact('activeMenu'));
    }

    public function announceStore(Request $request)
    {
        $employees = [
                        ['name' => 'A', 'hr_id' => '1001', 'department' => 'Automation', 'is_tech' => true],
                        ['name' => 'B', 'hr_id' => '1002', 'department' => 'Automation', 'is_tech' => true],
                        ['name' => 'C', 'hr_id' => '1003', 'department' => 'Automation', 'is_tech' => true],
                        ['name' => 'D', 'hr_id' => '1004', 'department' => 'IT Dev', 'is_tech' => true],
                        ['name' => 'E', 'hr_id' => '1005', 'department' => 'IT Dev', 'is_tech' => true],
                        ['name' => 'F', 'hr_id' => '1006', 'department' => 'IT Infra', 'is_tech' => true],
                        ['name' => 'G', 'hr_id' => '1007', 'department' => 'IT Infra', 'is_tech' => true],
                        ['name' => 'H', 'hr_id' => '1008', 'department' => 'HR', 'is_tech' => false],
                        ['name' => 'I', 'hr_id' => '1009', 'department' => 'HR', 'is_tech' => false],
                        ['name' => 'J', 'hr_id' => '1010', 'department' => 'Finance', 'is_tech' => false],
                        ['name' => 'K', 'hr_id' => '1011', 'department' => 'Finance', 'is_tech' => false],
                        ['name' => 'L', 'hr_id' => '1012', 'department' => 'Finance', 'is_tech' => false],
                        ['name' => 'M', 'hr_id' => '1013', 'department' => 'Marketing', 'is_tech' => false],
                        ['name' => 'N', 'hr_id' => '1014', 'department' => 'Marketing', 'is_tech' => false],
                        ['name' => 'O', 'hr_id' => '1015', 'department' => 'Sales', 'is_tech' => false],
                        ['name' => 'P', 'hr_id' => '1016', 'department' => 'Sales', 'is_tech' => false],
                        ['name' => 'Q', 'hr_id' => '1017', 'department' => 'Customer Support', 'is_tech' => false],
                        ['name' => 'R', 'hr_id' => '1018', 'department' => 'Customer Support', 'is_tech' => false],
                        ['name' => 'S', 'hr_id' => '1019', 'department' => 'Automation', 'is_tech' => true],
                        ['name' => 'T', 'hr_id' => '1020', 'department' => 'IT Dev', 'is_tech' => true],
                        ['name' => 'U', 'hr_id' => '1021', 'department' => 'IT Infra', 'is_tech' => true],
                        ['name' => 'V', 'hr_id' => '1022', 'department' => 'HR', 'is_tech' => false],
                        ['name' => 'W', 'hr_id' => '1023', 'department' => 'Finance', 'is_tech' => false],
                        ['name' => 'X', 'hr_id' => '1024', 'department' => 'Marketing', 'is_tech' => false],
                        ['name' => 'Y', 'hr_id' => '1025', 'department' => 'Sales', 'is_tech' => false],
                        ['name' => 'Z', 'hr_id' => '1026', 'department' => 'Customer Support', 'is_tech' => false],
                        ['name' => 'AA', 'hr_id' => '1027', 'department' => 'Automation', 'is_tech' => true],
                        ['name' => 'AB', 'hr_id' => '1028', 'department' => 'IT Dev', 'is_tech' => true],
                        ['name' => 'AC', 'hr_id' => '1029', 'department' => 'IT Infra', 'is_tech' => true],
                        ['name' => 'AD', 'hr_id' => '1030', 'department' => 'HR', 'is_tech' => false],
                        ['name' => 'AE', 'hr_id' => '1031', 'department' => 'Finance', 'is_tech' => false],
                        ['name' => 'AF', 'hr_id' => '1032', 'department' => 'Marketing', 'is_tech' => false],
                        ['name' => 'AG', 'hr_id' => '1033', 'department' => 'Sales', 'is_tech' => false],
                        ['name' => 'AH', 'hr_id' => '1034', 'department' => 'Customer Support', 'is_tech' => false],
                        ['name' => 'AI', 'hr_id' => '1035', 'department' => 'Automation', 'is_tech' => true],
                        ['name' => 'AJ', 'hr_id' => '1036', 'department' => 'IT Dev', 'is_tech' => true],
                        ['name' => 'AK', 'hr_id' => '1037', 'department' => 'IT Infra', 'is_tech' => true],
                        ['name' => 'AL', 'hr_id' => '1038', 'department' => 'HR', 'is_tech' => false],
                        ['name' => 'AM', 'hr_id' => '1039', 'department' => 'Finance', 'is_tech' => false],
                        ['name' => 'AN', 'hr_id' => '1040', 'department' => 'Marketing', 'is_tech' => false],
                        ['name' => 'AO', 'hr_id' => '1041', 'department' => 'Sales', 'is_tech' => false],
                        ['name' => 'AP', 'hr_id' => '1042', 'department' => 'Customer Support', 'is_tech' => false],
                        ['name' => 'AQ', 'hr_id' => '1043', 'department' => 'Automation', 'is_tech' => true],
                        ['name' => 'AR', 'hr_id' => '1044', 'department' => 'IT Dev', 'is_tech' => true],
                        ['name' => 'AS', 'hr_id' => '1045', 'department' => 'IT Infra', 'is_tech' => true],
                        ['name' => 'AT', 'hr_id' => '1046', 'department' => 'HR', 'is_tech' => false],
                        ['name' => 'AU', 'hr_id' => '1047', 'department' => 'Finance', 'is_tech' => false],
                        ['name' => 'AV', 'hr_id' => '1048', 'department' => 'Marketing', 'is_tech' => false],
                        ['name' => 'AW', 'hr_id' => '1049', 'department' => 'Sales', 'is_tech' => false],
                        ['name' => 'AX', 'hr_id' => '1050', 'department' => 'Customer Support', 'is_tech' => false],
                    ];

        // ✅ Validate incoming data
        $validated = $request->validate([
            'year'       => 'required|integer|min:2025|max:2100',
            'department' => 'required|string',
            'is_tech'    => 'required|in:Tech,Non-Tech'
        ]);
        
        // ✅ Check if year already announced
        if (Submission::where('year', $validated['year'])->exists()) {
            return redirect()
            ->back()
            ->with('error', 'Year ' . $validated['year'] . ' already announced before!');
        }

        // ✅ Filter employees based on department + is_tech
        $matchedEmployees = collect($employees)->filter(function ($emp) use ($validated) {
            return $emp['department'] !== $validated['department'];
        });


        if ($matchedEmployees->isEmpty()) {
            return redirect()
                ->back()
                ->withErrors(['error' => 'No employees found for the given criteria.']);
        }

        DB::beginTransaction();
        try {
            foreach ($matchedEmployees as $emp) {
                do {
                    $code = strtoupper(bin2hex(random_bytes(4)));
                } while (Submission::where('employee_code', $code)->exists());

                Submission::create([
                    'hr_id'         => $emp['hr_id'],
                    'employee_code' => $code,
                    'submitted'     => false,
                    'year'          => $validated['year'],
                    'department'    => $emp['department'],
                    'is_tech'       => ($emp['is_tech'] == 'Tech') ? true : false
                ]);
            }

            DB::commit();

            return redirect()
                ->route('responses.index')
                ->with('success', 'Announcement for year ' . $validated['year'] . ' completed successfully!');
        } catch (\Throwable $e) {
            DB::rollBack();
            return redirect()
                ->back()
                ->withErrors(['error' => 'Failed to announce: ' . $e->getMessage()]);
        }
    }



    public function index()
    {
        $activeMenu = 'responses';
        $submissions = Submission::paginate(20);
        return view('responses.index', compact('submissions', 'activeMenu'));
    }

    // public function create()
    // {
    //     // Get latest survey year questions
    //     $activeMenu = 'submit-responses';
    //     $questions = Question::where('year', $year)->orderBy('number')->get();
    //     return view('responses.create', compact('questions', 'year', 'activeMenu'));
    // }
    public function create($code = null)
    {
        if ($code) {
            // Editing existing submission by employee_code
            $submission = Submission::with('responses.question')
                ->where('employee_code', $code)
                ->firstOrFail();

            // Prepare an array of responses keyed by question_id for easier form population
            $responsesByQuestion = $submission->responses->keyBy('question_id');
        } else {
            // New submission
            $submission = null;
            $responsesByQuestion = collect();
        }

        $draft = true;
        if ($submission && $submission->submitted) {
            $draft = false; // Final submission, disable draft features
        }
        
        // Load all questions (ordered by number)
        $questions = Question::orderBy('number')->get();

        return view('responses.create', compact(
            'submission',
            'questions',
            'responsesByQuestion',
            'draft'
        ));
    }



    public function store(Request $request, $submissionId)
    {
        // Determine if this is a draft or final submission
        $isDraft = $request->input('action') === 'draft';
        
        // Get submission and questions
        $submission = Submission::with('responses.question')->findOrFail($submissionId);

        $questions = Question::orderBy('number')->get();
        //dd($request->input('action'));
        // Validation rules differ based on draft vs submit
        if ($isDraft) {
            // Draft validation - more lenient
            $data = $request->validate([
                'submission_id' => 'required|exists:submissions,id',
                'responses' => 'nullable|array',
                'responses.*.question_id' => 'required|exists:questions,id',
                'responses.*.answer' => 'nullable|integer|min:1|max:5',
            ]);
        } else {
            // Submit validation - strict, all questions must be answered
            $data = $request->validate([
                'submission_id' => 'required|exists:submissions,id',
                'responses' => 'required|array',
                'responses.*.question_id' => 'required|exists:questions,id',
                'responses.*.answer' => 'required|integer|min:1|max:5',
            ]);
            
            // Additional check: ensure all questions are answered for submit
            $submittedQuestionIds = collect($data['responses'])->pluck('question_id')->toArray();
            $allQuestionIds = $questions->pluck('id')->toArray();
            
            if (count(array_diff($allQuestionIds, $submittedQuestionIds)) > 0) {
                return back()->withErrors(['responses' => 'All questions must be answered before submitting.']);
            }
        }
        
        // Delete existing responses for this submission (to handle updates)
        Response::where('submission_id', $submissionId)->delete();
        
        // Save responses
        if (!empty($data['responses'])) {
            foreach ($data['responses'] as $questionId => $responseData) {
                // Skip if no answer provided (for draft mode)
                if (!isset($responseData['answer']) || $responseData['answer'] === null) {
                    continue;
                }
                
                Response::create([
                    'submission_id' => $submissionId,
                    'question_id' => $questionId,
                    'answer' => $responseData['answer'],
                    'question_order' => $questions->pluck('id')->toArray(),
                    'flags' => ['valid' => true, 'is_draft' => $isDraft],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
        
        // Update submission status
        $submission->update([
            'submitted' => !$isDraft,
            'submitted_at' => $isDraft ? null : now(),
            'updated_at' => now(),
        ]);
        
        // Redirect with appropriate message
        if ($isDraft) {
            return redirect()->back()
            ->with('success', 'Your progress has been saved as draft. You can continue later.')
            ->with('draft', true);
        } else {
            $engagementService = new EngagementService(new DimensionService());
            // $submission = Submission::find(8);

            $eei = $engagementService->calculateEEIForSubmission($submission);
            $dimensionBreakdown = $engagementService->calculateSubmissionEngagement($submission);

            //dd($submission->toArray());
            $engagementService->storeSubmissionEEI($submission);
            return redirect()->back()
            ->with('success', 'Your Survey is complete. Thank you for your participation!')
            ->with('draft', false);
        }
    }

    public function show($id)
    {
        $submission = Submission::with('responses.question')->findOrFail($id);
        //dd(json_encode($submission->toArray()));
 
        $activeMenu = 'responses';
        //$response->load('response'); // eager load related question

        $totalResponses = count($submission['responses']);
        $eei = $submission->eei ?? 0;
        $answerStats = collect($submission['responses'])
                        ->groupBy('answer')
                        ->map->count()
                        ->sortDesc();
        $highestIdenticalAnswer = $answerStats->keys()->first();
        $highestCount = $answerStats->first();

        
        $uniqueDimensions = collect($submission['responses'])->pluck('question.dimension')->flatten()->unique();


        return view('responses.show', compact('submission', 'activeMenu'));
    }

}
