<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Subject;
use Illuminate\Support\Facades\Auth;

class TutorSubjectController extends Controller
{
    public function index()
    {
        $subjects = Subject::all();
        $tutorSubjects = Auth::user()->subjects()->pluck('subject_id')->toArray();
        return view('tutor.subjects', compact('subjects', 'tutorSubjects'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        $user->subjects()->sync($request->subjects ?? []);
        return redirect()->back()->with('success', 'Subjects updated successfully!');
    }
}
