@if(Auth::check() && Auth::user()->isStudent())
    <div class="card mb-3">
        <div class="card-body">
            <h5>Request a Booking</h5>
            <form method="POST" action="{{ route('tutors.book', $user->id) }}">
                @csrf
                <div class="mb-2">
                    <label>Scheduled At</label>
                    <input type="datetime-local" name="scheduled_at" class="form-control" required>
                </div>
                <div class="mb-2">
                    <label>Notes (optional)</label>
                    <textarea name="notes" class="form-control"></textarea>
                </div>
                <button class="btn btn-primary">Send Request</button>
            </form>
        </div>
    </div>
@else
    <p><a href="{{ route('login') }}">Sign in</a> as a tutee to request a booking.</p>
@endif
