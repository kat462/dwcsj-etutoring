@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="card p-4 shadow">
        <h3>Edit Profile</h3>
        
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form method="POST" action="{{ route('tutor.profile.update') }}" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <label>Bio</label>
                <textarea name="bio" class="form-control">{{ old('bio', $profile->bio ?? '') }}</textarea>
                @error('bio')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="mb-3">
                <label>Education Level</label>
                <select name="education_level" class="form-control">
                    <option value="">--Select--</option>
                    <option value="Basic Ed" {{ old('education_level', $profile->education_level ?? '') == 'Basic Ed' ? 'selected' : '' }}>Basic Ed</option>
                    <option value="College" {{ old('education_level', $profile->education_level ?? '') == 'College' ? 'selected' : '' }}>College</option>
                </select>
                @error('education_level')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="mb-3">
                <label>Facebook URL</label>
                <input type="url" name="facebook" class="form-control" value="{{ old('facebook', $profile->facebook ?? '') }}">
                @error('facebook')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="mb-3">
                <label>Instagram URL</label>
                <input type="url" name="instagram" class="form-control" value="{{ old('instagram', $profile->instagram ?? '') }}">
                @error('instagram')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="mb-3">
                <label>Other Link</label>
                <input type="url" name="other_link" class="form-control" value="{{ old('other_link', $profile->other_link ?? '') }}">
                @error('other_link')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="mb-3">
                <label>Profile Image</label>
                <input type="file" name="profile_image" class="form-control">
                @error('profile_image')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
                @if($profile && $profile->profile_image)
                    <small class="text-muted">Current: {{ $profile->profile_image }}</small>
                @endif
            </div>

            <button type="submit" class="btn btn-success">Save Changes</button>
            <a href="{{ route('tutor.profile.show') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>
@endsection
