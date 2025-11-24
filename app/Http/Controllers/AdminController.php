<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request; use App\Models\User; use App\Models\Booking; use Illuminate\Support\Facades\Storage;
class AdminController extends Controller { public function users(){ $users = User::orderBy('role')->get(); return view('admin.users', compact('users')); } public function toggleActive($id){ $user = User::findOrFail($id); $user->is_active = !$user->is_active; $user->save(); return back()->with('success','Updated'); } public function deleteUser($id){ $user = User::findOrFail($id); $user->delete(); return back()->with('success','Deleted'); } public function pdfReport(){ return view('admin.reports'); }}
