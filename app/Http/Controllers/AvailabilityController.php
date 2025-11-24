<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Availability;
use Illuminate\Support\Facades\Auth;

class AvailabilityController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $availabilities = Availability::where('user_id', $user->id)->orderBy('date')->orderBy('start_time')->get();
        return view('tutor.availabilities.index', compact('availabilities'));
    }

    public function create()
    {
        return view('tutor.availabilities.create');
    }

    public function store(Request $request)
    {
        $this->authorize('create', \App\Models\Availability::class);

        $request->validate([
            'date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required',
        ]);

        $user = Auth::user();

        Availability::create([
            'user_id' => $user->id,
            'date' => $request->date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
        ]);

        return redirect()->route('tutor.availabilities.index')->with('success','Availability added');
    }

    public function destroy($id)
    {
        $a = Availability::findOrFail($id);
        $this->authorize('delete', $a);
        $a->delete();
        return back()->with('success','Deleted');
    }

    /**
     * Get tutor's availabilities as calendar events (JSON feed)
     */
    public function calendarEvents()
    {
        $user = Auth::user();
        $availabilities = Availability::where('user_id', $user->id)->get();

        /** @var \App\Models\Availability $availability */
        $events = $availabilities->map(function ($availability) {
            $date = $availability->date;
            $startTime = $availability->start_time;
            $endTime = $availability->end_time;

            return [
                'id' => $availability->id,
                'title' => 'Available',
                'start' => "{$date}T{$startTime}",
                'end' => "{$date}T{$endTime}",
                'backgroundColor' => '#10b981',
                'borderColor' => '#059669',
                'extendedProps' => [
                    'availability_id' => $availability->id,
                    'is_booked' => $availability->is_booked,
                ],
            ];
        });

        return response()->json($events);
    }

    /**
     * Get all availabilities for a specific tutor (for student calendar)
     */
    public function getTutorCalendarEvents($tutorId)
    {
        $availabilities = Availability::where('user_id', $tutorId)->get();

        /** @var \App\Models\Availability $availability */
        $events = $availabilities->map(function ($availability) {
            $date = $availability->date;
            $startTime = $availability->start_time;
            $endTime = $availability->end_time;

            return [
                'id' => $availability->id,
                'title' => 'Available',
                'start' => "{$date}T{$startTime}",
                'end' => "{$date}T{$endTime}",
                'backgroundColor' => '#3b82f6',
                'borderColor' => '#1e40af',
            ];
        });

        return response()->json($events);
    }

    /**
     * Update availability time (via calendar drag/resize)
     */
    public function updateTime(Request $request, $id)
    {
        /** @var \App\Models\Availability $availability */
        $availability = Availability::findOrFail($id);
        $this->authorize('update', $availability);

        $request->validate([
            'start' => 'required|date_format:Y-m-d\TH:i:s',
            'end' => 'required|date_format:Y-m-d\TH:i:s',
        ]);

        $start = \Carbon\Carbon::parse($request->start);
        $end = \Carbon\Carbon::parse($request->end);

        $availability->update([
            'date' => $start->toDateString(),
            'start_time' => $start->toTimeString(),
            'end_time' => $end->toTimeString(),
        ]);

        return response()->json(['success' => true, 'message' => 'Availability updated']);
    }

    /**
     * Show calendar view for a tutor (student perspective)
     */
    public function getTutorCalendar($tutorId)
    {
        $tutor = \App\Models\User::findOrFail($tutorId);
        
        if ($tutor->role !== 'tutor') {
            abort(404);
        }
        
        return view('student.tutor_calendar', compact('tutor'));
    }
}
