<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;


class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth','role:admin']);
    }

    /**
     * Bulk delete selected users (soft-delete)
     */
    public function bulkDelete(Request $request)
    {
        $ids = $request->input('ids', []);
        if (empty($ids)) {
            return back()->with('error', 'No users selected.');
        }
        $deleted = User::whereIn('id', $ids)->delete();
        return back()->with('success', ($deleted ?: count($ids)) . ' users deleted.');
    }

    /**
     * Bulk restore selected users (from trashed)
     */
    public function bulkRestore(Request $request)
    {
        $ids = $request->input('ids', []);
        if (empty($ids)) {
            return back()->with('error', 'No users selected.');
        }
        $restored = User::withTrashed()->whereIn('id', $ids)->restore();
        return back()->with('success', ($restored ?: count($ids)) . ' users restored.');
    }

    /**
     * Display a listing of users with filters and search.
     */
    public function index(Request $request)
    {
        $show = $request->query('show','active'); // active|trashed|all
        $role = $request->query('role','both'); // tutor|tutee|both
        $education = $request->query('education_level', '');
        $search = $request->query('q', null);

        $query = User::query();
        if ($show === 'trashed') {
            $query = User::onlyTrashed();
        } elseif ($show === 'all') {
            $query = User::withTrashed();
        }

        if ($role === 'tutor') {
            $query->where('role','tutor');
        } elseif ($role === 'tutee') {
            $query->where('role','tutee');
        }

        $validLevels = ['kindergarten','elementary','junior_high','senior_high','college','other'];
        if ($education && in_array($education, $validLevels)) {
            $query->where('education_level', $education);
        }

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('student_id', 'like', "%{$search}%");
            });
        }

        $users = $query->orderBy('name')->paginate(50);
        $users->appends($request->query());

        return view('admin.users.index', compact('users','show','role','education','search'));
    }


    /**
     * Soft-delete or permanently delete a user
     */
    public function destroy($id)
    {
        $user = User::withTrashed()->findOrFail($id);
        if ($user->trashed()) {
            $user->forceDelete();
            return back()->with('success', 'User permanently deleted.');
        } else {
            $user->delete();
            return back()->with('success', 'User soft-deleted.');
        }
    }

    /**
     * Toggle user active/inactive status
     */
    public function toggleActive($id)
    {
        $user = User::withTrashed()->findOrFail($id);
        $user->is_active = !$user->is_active;
        $user->save();
        $status = $user->is_active ? 'activated' : 'deactivated';
        return back()->with('success', "User {$status} successfully.");
    }

    /**
     * Restore a soft-deleted user
     */
    public function restore($id)
    {
        $user = User::withTrashed()->findOrFail($id);
        $user->restore();
        return back()->with('success','User restored.');
    }

    /**
     * Show a read-only user detail view for admins
     */
    public function show(User $user)
    {
        // Load relations that might be useful
        $user->load(['subjects']);

        // feedback counts (given / received)
        $feedbackGiven = $user->feedbacksGiven()->count();
        $feedbackReceived = $user->feedbacksReceived()->count();

        // Review metrics
        $avgRating = $user->feedbacksReceived()->avg('rating');
        $avgRating = $avgRating ? round($avgRating, 2) : null;
        $totalReviews = $user->feedbacksReceived()->count();
        $count5 = $user->feedbacksReceived()->where('rating',5)->count();
        $count4 = $user->feedbacksReceived()->where('rating',4)->count();
        $count3below = $user->feedbacksReceived()->where('rating','<=',3)->count();

        $latestReviews = $user->feedbacksReceived()->with('tutee')->orderBy('created_at','desc')->take(5)->get();

        // Booking and availability stats
        $bookingsAsTutor = $user->bookingsAsTutor()->count();
        $bookingsAsTutee = $user->bookingsAsTutee()->count();
        $availabilities = $user->availabilities()->count();
        $completedSessions = $user->bookingsAsTutor()->where('status','completed')->count();
        $cancelledSessions = $user->bookingsAsTutor()->where('status','cancelled')->count();

        // Determine roles (tutor/tutee) by role flag or relations
        $isTutor = ($user->role === 'tutor') || $user->subjects()->exists() || $user->availabilities()->exists();
        $isTutee = ($user->role === 'tutee') || $user->bookingsAsTutee()->exists();

        // Last activity timestamp (best-effort from related models)
        $candidates = [];
        if ($user->updated_at) $candidates[] = $user->updated_at->toDateTimeString();
        $b1 = $user->bookingsAsTutor()->max('updated_at'); if ($b1) $candidates[] = $b1;
        $b2 = $user->bookingsAsTutee()->max('updated_at'); if ($b2) $candidates[] = $b2;
        $a1 = $user->availabilities()->max('updated_at'); if ($a1) $candidates[] = $a1;
        $f1 = $user->feedbacksGiven()->max('updated_at'); if ($f1) $candidates[] = $f1;
        $f2 = $user->feedbacksReceived()->max('updated_at'); if ($f2) $candidates[] = $f2;
        $lastActivity = null;
        if (!empty($candidates)) {
            rsort($candidates);
            $lastActivity = $candidates[0];
        }

        return view('admin.users.show', compact(
            'user','feedbackGiven','feedbackReceived',
            'bookingsAsTutor','bookingsAsTutee','availabilities','completedSessions','cancelledSessions','isTutor','isTutee','lastActivity',
            'avgRating','totalReviews','count5','count4','count3below','latestReviews'
        ));
    }
}
