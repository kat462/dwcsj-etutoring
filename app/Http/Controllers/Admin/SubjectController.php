<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Subject;

class SubjectController extends Controller
{
    public function index()
    {
        $subjects = Subject::orderBy('name')->get();
        return view('admin.subjects', compact('subjects'));
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255|unique:subjects,name']);
        Subject::create(['name' => $request->name]);
        return redirect()->route('admin.subjects.index')->with('success', 'Subject added.');
    }

    public function destroy(Subject $subject)
    {
        $subject->delete();
        return redirect()->route('admin.subjects.index')->with('success', 'Subject deleted.');
    }
}
