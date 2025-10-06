<?php
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\ResponseController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function(){
    
    Route::get('/home', function () {
        $activeMenu = 'dashboard'; // highlight dashboard in sidebar
        return view('dashboard', compact('activeMenu'));
    })->name('dashboard');

    Route::get('/', function () {
       return redirect()->route('dashboard');
    });
    // announce
    Route::get('/announce/create', [ResponseController::class, 'announceCreate'])->name('announce.create');
    Route::post('/announce/store', [ResponseController::class, 'announceStore'])->name('announce.store');
    // questions
    //Route::resource('questions', QuestionController::class); // This is a Laravel shortcut that automatically generates all 7 CRUD routes for the questions resource, handled by QuestionController.
    Route::get('/questions/', [QuestionController::class, 'index'])->name('questions.index');
    Route::get('/questions/form', [QuestionController::class, 'form'])->name('questions.form');
    Route::post('/questions/save', [QuestionController::class, 'save'])->name('questions.save');
    // responses
    Route::resource('responses', ResponseController::class)->only(['index']);
    Route::get('responses/{id}', [ResponseController::class, 'show'])->name('responses.show');
    Route::get('responses/create/{code}', [ResponseController::class, 'create'])->name('responses.create');
    Route::post('responses/store/{id}', [ResponseController::class, 'store'])->name('responses.store');
    
    Route::get('questions-list', [QuestionController::class, 'list'])->name('questions.list');
    Route::get('responses-list', [ResponseController::class, 'list'])->name('responses.list');

    Route::get('/reports/', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/view/{reportName}/{year}', [ReportController::class, 'show'])->name('reports.show');
});

Auth::routes(['register' => false]);