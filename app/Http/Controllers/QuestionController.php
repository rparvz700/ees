<?php

namespace App\Http\Controllers;

use App\Models\Question;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;

class QuestionController extends Controller
{
    public function list(Request $request)
    {
        $query = Question::query();
        return DataTables::of($query)
            ->make(true);
    }

    public function index()
    {
        $activeMenu = 'questions';
        $questions = Question::paginate(20);
        return view('questions.index', compact('questions', 'activeMenu'));
    }

    // public function create()
    // {
    //     $activeMenu = 'questions';
    //     return view('questions.create', compact('activeMenu'));
    // }

    // public function store(Request $request)
    // {   
    //     // ✅ Validation
    //     $validated = $request->validate([
    //         'year' => 'required|integer|min:2025|max:2100',
    //         'questions' => 'required|array|min:1',
    //         'questions.*.number' => 'required|integer|min:1',
    //         'questions.*.dimension' => 'required|array|min:1',
    //         'questions.*.dimension.*' => 'string|max:255',
    //         'questions.*.reverse_coded' => 'required|boolean',
    //         'questions.*.text' => 'required|string|max:1000',
    //     ]);
        
    //     DB::beginTransaction();
    //     try {
    //         foreach ($validated['questions'] as $q) {
                
    //             Question::updateOrCreate(
    //                 ['number' => $q['number'] ?? null], // condition to check existence
    //                 [
    //                     'year' => $validated['year'],
    //                     'number' => $q['number'],
    //                     'dimension' => $q['dimension'],
    //                     'reverse_coded' => $q['reverse_coded'],
    //                     'text' => $q['text'],
    //                 ]
    //             );
    //         }
            
    //         DB::commit();

    //         return redirect()
    //             ->route('questions.index')
    //             ->with('success', 'Questions saved successfully!');
    //     } catch (\Throwable $e) {
    //         DB::rollBack();
    //         return back()
    //             ->withInput()
    //             ->withErrors(['error' => 'Failed to save questions: ' . $e->getMessage()]);
    //     }
    // }


    // public function edit(Question $question)
    // {
    //     $activeMenu = 'questions';
    //     return view('questions.edit', compact('question', 'activeMenu'));
    // }

    // public function update(Request $request, Question $question)
    // {
    //     $data = $request->validate([
    //         'year' => 'required|digits:4|integer',
    //         'number' => 'required|integer|unique:questions,number,' . $question->id,
    //         'text' => 'required|string',
    //         'dimension' => 'nullable|string',
    //         'reverse_coded' => 'boolean',
    //     ]);

    //     $question->update($data);
    //     return redirect()->route('questions.index')->with('success', 'Question updated successfully.');
    // }

    // public function destroy(Question $question)
    // {
    //     $question->delete();
    //     return redirect()->route('questions.index')->with('success', 'Question deleted successfully.');
    // }

    public function form()
    {
        $activeMenu = 'questions';

        // Fetch all existing questions (order by number for nicer display)
        $questions = Question::orderBy('number')->get();

        return view('questions.form', compact('activeMenu', 'questions'));
    }

    public function save(Request $request)
    {
        // ✅ Validation
        $validated = $request->validate([
            'questions' => 'required|array|min:1',
            'questions.*.number' => 'required|integer|min:1',
            'questions.*.dimension' => 'required|array|min:1',
            'questions.*.dimension.*' => 'string|max:255',
            'questions.*.reverse_coded' => 'required|boolean',
            'questions.*.text' => 'required|string|max:1000',
        ]);

        DB::beginTransaction();
        try {
            foreach ($validated['questions'] as $q) {
                 \Log::info("Creating question ", $q);
                Question::updateOrCreate(
                    ['number' => $q['number'] ?? null], // condition to check existence
                    [
                        'number' => $q['number'],
                        'dimension' => $q['dimension'],
                        'reverse_coded' => $q['reverse_coded'],
                        'text' => $q['text'],
                    ]
                );
                \Log::info('Created question id', ['id' => $new->id ?? null]);
                
            }

            DB::commit();

            return redirect()
                ->route('questions.index')
                ->with('success', 'Questions saved successfully!');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->withErrors(['error' => 'Failed to save questions: ' . $e->getMessage()]);
        }
    }
}
