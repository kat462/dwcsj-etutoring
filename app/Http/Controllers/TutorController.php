<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request; use Illuminate\Support\Facades\Auth; use App\Models\Subject; use App\Models\Availability; use App\Models\User;
class TutorController extends Controller {
    public function editProfile(){ $user = Auth::user(); $subjects = Subject::all(); return view('tutor.profile', compact('user','subjects')); }
    public function updateProfile(Request $req){ $user = Auth::user(); $req->validate(['name'=>'required']); $user->update($req->only('name','course','education_level','facebook_url','instagram_url','linkedin_url','bio')); if($req->subject_ids) $user->subjects()->sync($req->subject_ids); return back()->with('success','Profile updated'); }
    public function addAvailability(Request $req){ $req->validate(['date'=>'required|date','start_time'=>'required','end_time'=>'required']); Auth::user()->availabilities()->create($req->only('date','start_time','end_time')); return back()->with('success','Availability added'); }
    public function removeAvailability($id){ $slot = Availability::findOrFail($id); if($slot->user_id!=Auth::id()) abort(403); if($slot->is_booked) return back()->with('error','Slot booked'); $slot->delete(); return back()->with('success','Removed'); }
    public function respondBooking(Request $req, $id){ $booking = \App\Models\Booking::findOrFail($id); $avail = $booking->availability; if($avail->user_id!=Auth::id()) abort(403); $req->validate(['action'=>'required|in:accept,decline']); $booking->status = ($req->action=='accept') ? 'accepted' : 'declined'; $booking->save(); if($req->action=='accept'){ $avail->is_booked = true; $avail->save(); } return back()->with('success','Response saved'); }
}
