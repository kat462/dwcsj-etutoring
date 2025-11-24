@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="card p-4 shadow">
        <div class="text-center">
            <img src="{{ asset('images/profile/' . ($profile->profile_image ?? 'No_profile.png')) }}" 
                 alt="Profile Image" class="rounded-circle" width="120">
            <h3 class="mt-2">{{ auth()->user()->name }}</h3>
            <p><strong>Education:</strong> {{ $profile->education_level ?? 'N/A' }}</p>
            <p>{{ $profile->bio ?? 'No bio yet.' }}</p>

            <p>
                @if($profile && $profile->facebook)
                    <a href="{{ $profile->facebook }}" target="_blank">Facebook</a> |
                @endif
                @if($profile && $profile->instagram)
                    <a href="{{ $profile->instagram }}" target="_blank">Instagram</a> |
                @endif
                @if($profile && $profile->other_link)
                    <a href="{{ $profile->other_link }}" target="_blank">Other</a>
                @endif
            </p>

            <a href="{{ route('tutor.profile.edit') }}" class="btn btn-success">Edit Profile</a>
        </div>
    </div>
</div>
@endsection
