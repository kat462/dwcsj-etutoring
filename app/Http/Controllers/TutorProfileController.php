<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TutorProfile;
use Illuminate\Support\Facades\Auth;

class TutorProfileController extends Controller
{
    public function show()
    {
        $profile = Auth::user()->profile;
        return view('tutor.profile', compact('profile'));
    }

    public function edit()
    {
        $profile = Auth::user()->profile;
        return view('tutor.profile_edit', compact('profile'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'bio' => 'nullable|string|max:500',
            'education_level' => 'nullable|string',
            'facebook' => 'nullable|url',
            'instagram' => 'nullable|url',
            'other_link' => 'nullable|url',
            'profile_image' => 'nullable|image|max:2048',
        ]);

        $user = Auth::user();
        $profile = $user->profile ?? new TutorProfile(['user_id' => $user->id]);

        $data = $request->only([
            'bio', 'education_level', 'facebook', 'instagram', 'other_link'
        ]);

        if ($request->hasFile('profile_image')) {
            $filename = time().'_'.$request->file('profile_image')->getClientOriginalName();
            $request->file('profile_image')->move(public_path('images/profile'), $filename);
            $data['profile_image'] = $filename;
        }

        $profile->fill($data);
        $profile->save();

        return redirect()->route('tutor.profile.show')->with('success', 'Profile updated!');
    }

    /**
     * Public-facing tutor profile view (by user id)
     */
    public function publicShow($id)
    {
        $user = \App\Models\User::with(['profile','subjects','availabilities'])->findOrFail($id);

        // Average rating and latest reviews
        $avgRating = $user->feedbacksReceived()->avg('rating');
        $avgRating = $avgRating ? round($avgRating, 2) : null;
        $reviews = $user->feedbacksReceived()->with('tutee')->orderBy('created_at', 'desc')->take(5)->get();

        return view('tutors.show', compact('user','avgRating','reviews'));
    }

    /**
     * Public-facing paginated reviews for a tutor
     */
    public function publicReviews(Request $request, $id)
    {
        $user = \App\Models\User::findOrFail($id);

        $rating = $request->query('rating', 'all'); // all|5|4|3below

        $query = $user->feedbacksReceived()->with('tutee')->orderBy('created_at', 'desc');
        if ($rating === '5') {
            $query->where('rating', 5);
        } elseif ($rating === '4') {
            $query->where('rating', 4);
        } elseif ($rating === '3below') {
            $query->where('rating', '<=', 3);
        }

        $reviews = $query->paginate(20);
        $reviews->appends($request->query());

        return view('tutors.reviews', compact('user','reviews','rating'));
    }
}
