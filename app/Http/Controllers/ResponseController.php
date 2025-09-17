<?php

namespace App\Http\Controllers;

use App\Models\Response;
use App\Models\Question;
use Illuminate\Http\Request;

class ResponseController extends Controller
{
    public function index()
    {   $activeMenu = 'responses';
        $responses = Response::with('question')->latest()->paginate(15);
        return view('responses.index', compact('responses', 'activeMenu'));
    }

    public function create()
    {
        // Get latest survey year questions
        $activeMenu = 'submit-responses';
        $year = Question::max('year');
        $questions = Question::where('year', $year)->orderBy('number')->get();
        return view('responses.create', compact('questions', 'year', 'activeMenu'));
    }

    public function store(Request $request)
    {
        $year = Question::max('year');
        $questions = Question::where('year', $year)->get();

        $data = $request->validate([
            'department' => 'nullable|string',
            'group' => 'nullable|string',
            'answers' => 'required|array',
            'answers.*' => 'required|integer|min:1|max:5',
        ]);

        foreach ($questions as $q) {
            Response::create([
                'question_order' => $questions->pluck('id')->toArray(),
                'department' => $data['department'],
                'group' => $data['group'],
                'submitted' => true,
                'submitted_at' => now(),
                'flags' => ['valid' => true],
                'question_id' => $q->id,
                'answer' => $data['answers'][$q->id] ?? null,
            ]);
        }

        return redirect()->route('responses.index')->with('success', 'Survey submitted successfully.');
    }

    public function show(Response $response)
    {
        $activeMenu = 'responses';
        $response->load('question'); // eager load related question
        return view('responses.show', compact('response', 'activeMenu'));
    }

}
