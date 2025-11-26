
@extends('layouts.app')

@section('content')
<div class="page-header mb-4">
	<div class="d-flex align-items-center mb-2">
		<i class="bi bi-pencil-square text-primary me-3" style="font-size: 2.2rem;"></i>
		<h1 class="fw-bold mb-0">Edit Profile</h1>
	</div>
</div>
<div class="bg-light py-4" style="min-height: 100vh;">
	<div class="container-xl">
		<div class="row">
			<div class="col-12">
				<div class="card border border-2 rounded-3 mb-4" style="background: #fff;">
					<div class="card-body p-4">
						@if(session('success'))
							<div class="alert alert-success">{{ session('success') }}</div>
						@endif
						<form method="POST" action="{{ route('tutor.profile.update') }}" enctype="multipart/form-data" class="mt-3">
							@csrf
							<div class="row g-4">
								<div class="col-12">
									<label for="bio" class="form-label fw-semibold">Bio</label>
									<textarea class="form-control shadow-sm" id="bio" name="bio" rows="2" placeholder="Tell us about yourself...">{{ old('bio', $profile->bio ?? '') }}</textarea>
								</div>
								<div class="col-md-6">
									<label for="rate" class="form-label fw-semibold">Session Rate</label>
									<input type="number" step="0.01" min="0" class="form-control shadow-sm" id="rate" name="rate" value="{{ old('rate', $profile->rate ?? '') }}" placeholder="₱0.00">
									@php $isFree = empty($profile->rate) || $profile->rate == 0; @endphp
									@if($isFree)
										<span class="badge bg-success">Free</span>
									@else
										<span class="badge bg-info text-dark">Paid</span>
									@endif
									<div class="form-text">Leave blank or set to 0 if you offer free sessions. Enter amount with peso sign (₱) as needed.</div>
								</div>
								<div class="col-md-6">
									<label for="education_level" class="form-label fw-semibold">Education Level</label>
									@php use App\Helpers\EducationLevel; @endphp
									<select class="form-select shadow-sm" id="education_level" name="education_level">
										<option value="">Select education level</option>
										@foreach(EducationLevel::options() as $key => $label)
											<option value="{{ $key }}" @if(old('education_level', $profile->education_level ?? '') == $key) selected @endif>{{ $label }}</option>
										@endforeach
									</select>
								</div>
								<div class="col-md-6">
									<label for="email" class="form-label fw-semibold">Email</label>
									<input type="email" class="form-control shadow-sm" id="email" name="email" value="{{ old('email', Auth::user()->email) }}">
								</div>
								<div class="col-md-6">
									<label for="password" class="form-label fw-semibold">New Password</label>
									<input type="password" class="form-control shadow-sm" id="password" name="password" autocomplete="new-password" placeholder="Leave blank to keep current password">
								</div>
								<div class="col-md-4">
									<label for="facebook" class="form-label fw-semibold">Facebook</label>
									<input type="url" class="form-control shadow-sm" id="facebook" name="facebook" value="{{ old('facebook', $profile->facebook ?? '') }}">
								</div>
								<div class="col-md-4">
									<label for="instagram" class="form-label fw-semibold">Instagram</label>
									<input type="url" class="form-control shadow-sm" id="instagram" name="instagram" value="{{ old('instagram', $profile->instagram ?? '') }}">
								</div>
								<div class="col-md-4">
									<label for="other_link" class="form-label fw-semibold">Other (Website/Contact)</label>
									<input type="url" class="form-control shadow-sm" id="other_link" name="other_link" value="{{ old('other_link', $profile->other_link ?? '') }}" placeholder="Other social or contact link">
								</div>
							</div>
							<div class="mt-4 mb-3">
								<label for="profile_image" class="form-label fw-semibold">Profile Photo</label>
								<input type="file" class="form-control shadow-sm" id="profile_image" name="profile_image" accept="image/*">
								@if($profile && $profile->profile_image)
									<div class="mt-2">
										<img src="{{ asset('images/profile/' . $profile->profile_image) }}" alt="Current Profile Photo" class="rounded-circle border" style="width:80px;height:80px;object-fit:cover;">
										<span class="text-muted small ms-2">Current photo</span>
									</div>
								@endif
								<div class="form-text">Upload a clear photo (JPG, PNG, max 2MB).</div>
							</div>
							<div class="d-flex gap-2">
								<button type="submit" class="btn btn-primary px-4 py-2 shadow"><i class="bi bi-save"></i> Save Changes</button>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection



