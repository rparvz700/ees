<?php

namespace App\Http\Controllers;

use App\Models\Question;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class QuestionController extends Controller
{
    public function list(Request $request)
    {
        $query = Question::query();
        return DataTables::of($query)
            ->addColumn('actions', function ($question) {
                return view('questions.partials.actions', compact('question'))->render();
            })
            ->rawColumns(['actions'])
            ->make(true);
    }

    public function index()
    {
        $activeMenu = 'questions';
        $questions = Question::orderBy('year', 'desc')->orderBy('number')->paginate(10);
        return view('questions.index', compact('questions', 'activeMenu'));
    }

    public function create()
    {
        $activeMenu = 'questions';
        return view('questions.create', compact('activeMenu'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'year' => 'required|digits:4|integer',
            'number' => 'required|integer|unique:questions,number',
            'text' => 'required|string',
            'dimension' => 'nullable|string',
            'reverse_coded' => 'boolean',
        ]);

        Question::create($data);
        return redirect()->route('questions.index')->with('success', 'Question created successfully.');
    }

    public function edit(Question $question)
    {
        $activeMenu = 'questions';
        return view('questions.edit', compact('question', 'activeMenu'));
    }

    public function update(Request $request, Question $question)
    {
        $data = $request->validate([
            'year' => 'required|digits:4|integer',
            'number' => 'required|integer|unique:questions,number,' . $question->id,
            'text' => 'required|string',
            'dimension' => 'nullable|string',
            'reverse_coded' => 'boolean',
        ]);

        $question->update($data);
        return redirect()->route('questions.index')->with('success', 'Question updated successfully.');
    }

    public function destroy(Question $question)
    {
        $question->delete();
        return redirect()->route('questions.index')->with('success', 'Question deleted successfully.');
    }
}
