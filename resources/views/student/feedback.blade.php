@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <!-- Session Details Card -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-gradient text-white">
                    <h5 class="mb-0"><i class="bi bi-star-fill me-2"></i>Rate Your Tutoring Session</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p class="mb-1 text-muted small">Tutor</p>
                            <h6 class="mb-0">{{ $booking->tutor->name }}</h6>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-1 text-muted small">Subject</p>
                            <h6 class="mb-0">{{ $booking->subject->name }}</h6>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <p class="mb-1 text-muted small">Session Date</p>
                            <h6 class="mb-0">{{ \Carbon\Carbon::parse($booking->session_date)->format('F j, Y g:i A') }}</h6>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-1 text-muted small">Duration</p>
                            <h6 class="mb-0">{{ $booking->duration }} minutes</h6>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Feedback Form Card -->
            <div class="card shadow-sm">
                <div class="card-body">
                    <form action="{{ route('feedback.store', $booking) }}" method="POST">
                        @csrf

                        <!-- Star Rating -->
                        <div class="mb-4">
                            <label class="form-label fw-bold">How would you rate this session?</label>
                            <div class="star-rating">
                                <input type="radio" id="star5" name="rating" value="5" required />
                                <label for="star5" title="5 stars"><i class="bi bi-star-fill"></i></label>
                                
                                <input type="radio" id="star4" name="rating" value="4" />
                                <label for="star4" title="4 stars"><i class="bi bi-star-fill"></i></label>
                                
                                <input type="radio" id="star3" name="rating" value="3" />
                                <label for="star3" title="3 stars"><i class="bi bi-star-fill"></i></label>
                                
                                <input type="radio" id="star2" name="rating" value="2" />
                                <label for="star2" title="2 stars"><i class="bi bi-star-fill"></i></label>
                                
                                <input type="radio" id="star1" name="rating" value="1" />
                                <label for="star1" title="1 star"><i class="bi bi-star-fill"></i></label>
                            </div>
                            @error('rating')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Comment -->
                        <div class="mb-4">
                            <label for="comment" class="form-label fw-bold">Share your experience</label>
                            <textarea 
                                class="form-control @error('comment') is-invalid @enderror" 
                                id="comment" 
                                name="comment" 
                                rows="5" 
                                placeholder="Tell us about your tutoring session. What went well? What could be improved?"
                            >{{ old('comment') }}</textarea>
                            @error('comment')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-send-fill me-2"></i>Submit Feedback
                            </button>
                            <a href="{{ route('student.bookings') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left me-2"></i>Back to Bookings
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Star Rating Styles */
.star-rating {
    display: flex;
    flex-direction: row-reverse;
    justify-content: flex-end;
    gap: 0.25rem;
    font-size: 2.5rem;
}

.star-rating input {
    display: none;
}

.star-rating label {
    cursor: pointer;
    color: #e0e0e0;
    transition: all 0.2s ease;
}

.star-rating label:hover,
.star-rating label:hover ~ label,
.star-rating input:checked ~ label {
    color: var(--secondary-color);
}

.star-rating label:hover {
    transform: scale(1.1);
}

.star-rating input:checked ~ label {
    color: var(--secondary-color);
    filter: drop-shadow(0 0 5px rgba(234, 179, 8, 0.5));
}
</style>
@endsection
