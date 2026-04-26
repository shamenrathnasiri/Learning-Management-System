<?php

namespace App\Http\Controllers;

use App\Models\Lesson;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();

        $stats = [
            'students' => User::query()->where('role', 'student')->count(),
            'tutors' => User::query()->where('role', 'tutor')->count(),
            'admins' => User::query()->where('role', 'admin')->count(),
            'lessons' => Lesson::query()->count(),
            'quizzes' => Quiz::query()->count(),
            'attempts' => QuizAttempt::query()->count(),
        ];

        $recentLessons = Lesson::query()->with('tutor')->latest()->take(6)->get();
        $recentAttempts = QuizAttempt::query()->with(['quiz.lesson', 'student'])->latest()->take(6)->get();

        return view('dashboard', compact('user', 'stats', 'recentLessons', 'recentAttempts'));
    }
}
