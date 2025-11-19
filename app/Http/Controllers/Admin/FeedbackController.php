<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Feedback;

class FeedbackController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth','role:admin']);
    }

    public function index(Request $request)
    {
        $show = $request->query('show','active'); // active|trashed|all
        $rating = $request->query('rating','all'); // all|5|4|3below
        $q = $request->query('q', null);

        $query = Feedback::query();
        if ($show === 'trashed') {
            $query = Feedback::onlyTrashed();
        } elseif ($show === 'all') {
            $query = Feedback::withTrashed();
        }

        if ($rating === '5') { $query->where('rating',5); }
        if ($rating === '4') { $query->where('rating',4); }
        if ($rating === '3below') { $query->where('rating','<=',3); }

        if ($q) {
            $query->where(function($qq) use ($q){
                $qq->whereHas('tutee', function($q2) use ($q){ $q2->where('name','like', "%{$q}%"); })
                   ->orWhereHas('tutor', function($q3) use ($q){ $q3->where('name','like', "%{$q}%"); })
                   ->orWhere('comment','like', "%{$q}%");
            });
        }

        $items = $query->with(['tutor','tutee'])->orderBy('created_at','desc')->paginate(50);
        $items->appends($request->query());

        return view('admin.feedback.index', compact('items','show','rating','q'));
    }

    public function destroy(Feedback $feedback)
    {
        $feedback->delete();
        return back()->with('success','Review soft-deleted.');
    }

    public function restore($id)
    {
        $item = Feedback::withTrashed()->findOrFail($id);
        $item->restore();
        return back()->with('success','Review restored.');
    }
}
