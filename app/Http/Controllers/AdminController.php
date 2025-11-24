<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Booking;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
	/**
	 * Display a listing of users.
	 */
	public function users()
	{
		$users = User::orderBy('role')->get();
		return view('admin.users', compact('users'));
	}

	/**
	 * Toggle the active status of a user.
	 * Add authorization and validation as needed.
	 */
	public function toggleActive($id)
	{
		$user = User::findOrFail($id);
		$user->is_active = !$user->is_active;
		$user->save();
		return back()->with('success', 'Updated');
	}

	/**
	 * Delete a user.
	 * Add authorization and validation as needed.
	 */
	public function deleteUser($id)
	{
		$user = User::findOrFail($id);
		$user->delete();
		return back()->with('success', 'Deleted');
	}

	/**
	 * Show the PDF report view.
	 */
	public function pdfReport()
	{
		return view('admin.reports');
	}
}
