<?php
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\ResponseController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function(){
    Route::get('/home', function () {
        $activeMenu = 'dashboard'; // highlight dashboard in sidebar
        return view('dashboard', compact('activeMenu'));
    })->name('dashboard');

    // questions
    Route::resource('questions', QuestionController::class); // This is a Laravel shortcut that automatically generates all 7 CRUD routes for the questions resource, handled by QuestionController.

    // responses
    Route::resource('responses', ResponseController::class)->only(['index','create','store','show']);
    // We only need index, create, store, and show for responses. No edit/update/delete.
    Route::get('questions-list', [QuestionController::class, 'list'])->name('questions.list');
});

Auth::routes(['register' => false]);