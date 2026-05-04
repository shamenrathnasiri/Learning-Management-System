
<?php

use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GradingController;
use App\Http\Controllers\LessonController;
use App\Http\Controllers\QuizAttemptController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/lessons', [LessonController::class, 'index'])->name('lessons.index');
    Route::post('/lessons/{lesson}/enroll', [LessonController::class, 'enroll'])->name('lessons.enroll');
    Route::middleware('role:tutor,admin')->group(function () {
        Route::get('/quizzes/create', [QuizController::class, 'createHub'])->name('quizzes.create');
        Route::post('/quizzes', [QuizController::class, 'storeHub'])->name('quizzes.store');
        Route::get('/quizzes/analytics', [QuizController::class, 'analytics'])->name('quizzes.analytics');
        Route::get('/lessons/create', [LessonController::class, 'create'])->name('lessons.create');
        Route::post('/lessons', [LessonController::class, 'store'])->name('lessons.store');
    });
    Route::get('/lessons/{lesson}', [LessonController::class, 'show'])->name('lessons.show');
    Route::get('/quizzes/{quiz}', [QuizController::class, 'show'])->name('quizzes.show');
    Route::post('/quizzes/{quiz}/attempt', [QuizAttemptController::class, 'store'])->name('quizzes.attempt.store');
    Route::get('/quiz-attempts/{quizAttempt}', [QuizAttemptController::class, 'show'])->name('quiz-attempts.show');

    Route::middleware('role:tutor,admin')->group(function () {
        Route::resource('lessons', LessonController::class)->except(['index', 'show', 'create', 'store']);
        Route::get('/lessons/{lesson}/quizzes/create', [QuizController::class, 'create'])->name('lessons.quizzes.create');
        Route::post('/lessons/{lesson}/quizzes', [QuizController::class, 'store'])->name('lessons.quizzes.store');
        Route::get('/quizzes/{quiz}/edit', [QuizController::class, 'edit'])->name('quizzes.edit');
        Route::put('/quizzes/{quiz}', [QuizController::class, 'update'])->name('quizzes.update');
        Route::post('/quizzes/{quiz}/duplicate', [QuizController::class, 'duplicate'])->name('quizzes.duplicate');
        Route::post('/quizzes/{quiz}/publish', [QuizController::class, 'publish'])->name('quizzes.publish');
        Route::delete('/quizzes/{quiz}', [QuizController::class, 'destroy'])->name('quizzes.destroy');

        // Grading routes
        Route::get('/quizzes/{quiz}/grading', [GradingController::class, 'indexAttempts'])->name('grading.index-attempts');
        Route::get('/quiz-attempts/{attempt}/grading', [GradingController::class, 'show'])->name('grading.show');
        Route::post('/quiz-attempts/{attempt}/grading/bulk-grade', [GradingController::class, 'bulkGrade'])->name('grading.bulk-grade');
        Route::post('/quiz-attempt-answers/{answer}/grade', [GradingController::class, 'grade'])->name('grading.grade');
    });

    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::resource('users', AdminUserController::class)->except(['show']);
    });

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


require __DIR__.'/auth.php';
