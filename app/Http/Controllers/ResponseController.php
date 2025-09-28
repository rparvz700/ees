<?php

namespace App\Http\Controllers;

use App\Models\Response;
use App\Models\Question;
use App\Models\Submission;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;

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
            // Add more employees...
        ];
        // ✅ Validate incoming data
        $validated = $request->validate([
            'year'       => 'required|integer|min:2025|max:2100',
            'department' => 'required|string|max:255',
            'is_tech'    => 'required|string|in:Tech,Non-Tech,Other',
        ]);
        
        // ✅ Check if year already announced
        if (Submission::where('year', $validated['year'])->exists()) {
            return redirect()
            ->back()
            ->with('error', 'Year ' . $validated['year'] . ' already announced before!');
        }

        // ✅ Filter employees based on department + is_tech
        $matchedEmployees = collect($employees)->filter(function ($emp) use ($validated) {
            return $emp['department'] === $validated['department']
                && $emp['is_tech'] == $validated['is_tech'];
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
                    'department'    => $validated['department'],
                    'is_tech'       => ($validated['is_tech'] == 'Tech') ? true : false
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
        $submission = Submission::findOrFail($submissionId);
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
        return view('responses.show', compact('submission', 'activeMenu'));
    }

}
