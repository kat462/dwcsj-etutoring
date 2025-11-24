<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AllowedStudentId;
use Illuminate\Support\Facades\DB;

class AllowedStudentIdController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth','role:admin']);
    }

    public function index(Request $request)
    {
        $show = $request->query('show','active'); // active|trashed|all
        $education = $request->query('education_level', null); // null, new levels
        $search = $request->query('q', null);
        $used = $request->query('used', null); // 'used'|'unused'|null

        $query = AllowedStudentId::query();
        if ($show === 'trashed') {
            $query = AllowedStudentId::onlyTrashed();
        } elseif ($show === 'all') {
            $query = AllowedStudentId::withTrashed();
        }

        $validLevels = ['kindergarten','elementary','junior_high','senior_high','college','other'];
        if ($education && in_array($education, $validLevels)) {
            $query->where('education_level', $education);
        }

        if ($search) {
            $query->where('student_id', 'like', "%{$search}%");
        }

        if ($used === 'used') {
            $query->where('used', true);
        } elseif ($used === 'unused') {
            $query->where('used', false);
        }

        $items = $query->orderBy('student_id')->paginate(50);
        $items->appends($request->query());

        return view('admin.allowed_student_ids.index', compact('items','show','education','search','used'));
    }

    public function create()
    {
        return view('admin.allowed_student_ids.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'student_id' => ['required','string','max:255','unique:allowed_student_ids,student_id'],
            'education_level' => ['required','in:kindergarten,elementary,junior_high,senior_high,college,other'],
        ]);

        AllowedStudentId::create(array_merge($data, ['used' => false]));

        return redirect()->route('admin.allowed-student-ids.index')->with('success','Allowed Student ID added.');
    }

    public function edit(AllowedStudentId $allowed_student_id)
    {
        return view('admin.allowed_student_ids.edit', ['item' => $allowed_student_id]);
    }

    public function update(Request $request, AllowedStudentId $allowed_student_id)
    {
        $data = $request->validate([
            'education_level' => ['required','in:kindergarten,elementary,junior_high,senior_high,college,other'],
            'used' => ['nullable','boolean'],
        ]);

        $allowed_student_id->update($data);

        return redirect()->route('admin.allowed-student-ids.index')->with('success','Updated.');
    }

    public function destroy(AllowedStudentId $allowed_student_id)
    {
        $allowed_student_id->delete();
        return back()->with('success','Deleted.');
    }

    public function restore($id)
    {
        $item = AllowedStudentId::withTrashed()->findOrFail($id);
        $item->restore();
        return back()->with('success','Restored.');
    }

    public function import(Request $request)
    {
        $request->validate(['csv' => ['required','file','mimes:csv,txt']]);
        $preview = $request->boolean('preview', false);

        $file = $request->file('csv');
        $handle = fopen($file->getRealPath(), 'r');
        if ($handle === false) {
            return back()->with('error', 'Unable to open uploaded file.');
        }

        $rowNumber = 0;
        $stats = [
            'rows' => 0,
            'inserted' => 0,
            'updated' => 0,
            'restored' => 0,
            'skipped_duplicate' => 0,
            'invalid' => 0,
            'errors' => [],
        ];

        while (($data = fgetcsv($handle)) !== false) {
            $rowNumber++;
            // Skip empty lines
            if (count($data) === 1 && trim($data[0]) === '') { continue; }

            $stats['rows']++;

            // Normalize fields
            $studentId = isset($data[0]) ? trim($data[0]) : '';
            $eduRaw = isset($data[1]) ? trim($data[1]) : '';

            // Skip header row if detected (common headers)
            if ($rowNumber === 1) {
                $lower0 = strtolower($studentId);
                if (in_array($lower0, ['student_id','student id','id'])) {
                    // treat as header, don't count as a data row
                    $stats['rows']--;
                    continue;
                }
            }

            if ($studentId === '') {
                $stats['invalid']++;
                $stats['errors'][] = "Row {$rowNumber}: empty student_id";
                continue;
            }

            $edu = strtolower($eduRaw ?: 'college');
            if (!in_array($edu, ['basic','college'])) {
                // If invalid education level, record error but default to college
                $stats['errors'][] = "Row {$rowNumber}: invalid education_level '{$eduRaw}', defaulted to 'college'";
                $edu = 'college';
            }

            $exists = AllowedStudentId::withTrashed()->where('student_id', $studentId)->first();
            if ($exists) {
                // If exists and identical education level and not trashed, treat as duplicate
                if (! $exists->trashed() && $exists->education_level === $edu) {
                    $stats['skipped_duplicate']++;
                    continue;
                }

                // Would restore if trashed
                if ($exists->trashed()) {
                    $stats['restored']++;
                    if (! $preview) { $exists->restore(); }
                }

                // Would update if education level differs
                if ($exists->education_level !== $edu) {
                    $stats['updated']++;
                    if (! $preview) {
                        $exists->education_level = $edu;
                        $exists->save();
                    }
                }

                continue;
            }

            // Would insert
            if ($preview) {
                $stats['inserted']++;
            } else {
                try {
                    AllowedStudentId::create(['student_id' => $studentId, 'education_level' => $edu, 'used' => false]);
                    $stats['inserted']++;
                } catch (\Exception $e) {
                    $stats['invalid']++;
                    $stats['errors'][] = "Row {$rowNumber}: exception on insert ({$e->getMessage()})";
                }
            }
        }

        fclose($handle);

        // Flash the import report (as array) so view can present detailed info
        return back()->with('import_report', $stats)->with('success', "Import finished: {$stats['inserted']} inserted, {$stats['skipped_duplicate']} duplicates skipped.");
    }

    public function resetUsed(Request $request)
    {
        $scope = $request->input('scope','all'); // all or selected
        $selectAll = $request->boolean('select_all', false);
        $show = $request->input('show', 'active');
        $education = $request->input('education_level', null);
        $search = $request->input('q', null);
        $used = $request->input('used', null);

        if ($selectAll) {
            $query = AllowedStudentId::withTrashed();
            if ($show === 'trashed') {
                $query = AllowedStudentId::onlyTrashed();
            } elseif ($show === 'active') {
                $query = AllowedStudentId::whereNull('deleted_at');
            }
            if ($education && in_array($education, ['basic','college'])) {
                $query->where('education_level', $education);
            }
            if ($search) {
                $query->where('student_id', 'like', "%{$search}%");
            }
            if ($used === 'used') {
                $query->where('used', true);
            } elseif ($used === 'unused') {
                $query->where('used', false);
            }
            $count = $query->count();
            $query->update(['used' => false]);
            return back()->with('success', "{$count} used flags reset.");
        }

        if ($scope === 'selected') {
            $ids = $request->input('ids', []);
            AllowedStudentId::whereIn('id',$ids)->update(['used'=>false]);
            return back()->with('success', count($ids) . ' used flags reset.');
        } else {
            AllowedStudentId::withTrashed()->update(['used'=>false]);
            return back()->with('success','Used flags reset.');
        }
    }

    /**
     * Bulk delete (soft-delete) selected IDs
     */
    public function bulkDelete(Request $request)
    {
        $ids = $request->input('ids', []);
        $selectAll = $request->boolean('select_all', false);
        $show = $request->input('show', 'active');
        $education = $request->input('education_level', null);
        $search = $request->input('q', null);
        $used = $request->input('used', null);

        if ($selectAll) {
            $query = AllowedStudentId::query();
            if ($show === 'trashed') {
                $query = AllowedStudentId::onlyTrashed();
            } elseif ($show === 'all') {
                $query = AllowedStudentId::withTrashed();
            }
            if ($education && in_array($education, ['basic','college'])) {
                $query->where('education_level', $education);
            }
            if ($search) {
                $query->where('student_id', 'like', "%{$search}%");
            }
            if ($used === 'used') {
                $query->where('used', true);
            } elseif ($used === 'unused') {
                $query->where('used', false);
            }
            $count = $query->count();
            $query->delete();
            return back()->with('success', "{$count} IDs deleted.");
        }

        if (empty($ids)) {
            return back()->with('error', 'No IDs selected.');
        }

        $deleted = AllowedStudentId::whereIn('id', $ids)->delete();
        return back()->with('success', ($deleted ?: count($ids)) . ' IDs deleted.');
    }

    /**
     * Bulk restore selected IDs (from trashed)
     */
    public function bulkRestore(Request $request)
    {
        $ids = $request->input('ids', []);
        $selectAll = $request->boolean('select_all', false);
        $show = $request->input('show', 'active');
        $education = $request->input('education_level', null);
        $search = $request->input('q', null);
        $used = $request->input('used', null);

        if ($selectAll) {
            $query = AllowedStudentId::withTrashed();
            if ($show === 'trashed') {
                $query = AllowedStudentId::onlyTrashed();
            } elseif ($show === 'active') {
                $query = AllowedStudentId::whereNull('deleted_at');
            }
            if ($education && in_array($education, ['basic','college'])) {
                $query->where('education_level', $education);
            }
            if ($search) {
                $query->where('student_id', 'like', "%{$search}%");
            }
            if ($used === 'used') {
                $query->where('used', true);
            } elseif ($used === 'unused') {
                $query->where('used', false);
            }
            $count = $query->count();
            $query->restore();
            return back()->with('success', "{$count} IDs restored.");
        }

        if (empty($ids)) {
            return back()->with('error', 'No IDs selected.');
        }

        AllowedStudentId::withTrashed()->whereIn('id', $ids)->restore();
        return back()->with('success', count($ids) . ' IDs restored.');
    }

    /**
     * Export filtered or selected results as CSV
     * Accepts: ids[] for explicit IDs OR select_all=1 with the same filters as index
     */
    public function export(Request $request)
    {
        $ids = $request->input('ids', []);
        $selectAll = $request->boolean('select_all', false);
        $show = $request->input('show', 'active');
        $education = $request->input('education_level', null);
        $search = $request->input('q', null);
        $used = $request->input('used', null);

        // Build query matching index filters
        $query = AllowedStudentId::query();
        if ($show === 'trashed') {
            $query = AllowedStudentId::onlyTrashed();
        } elseif ($show === 'all') {
            $query = AllowedStudentId::withTrashed();
        }

        if ($education && in_array($education, ['basic','college'])) {
            $query->where('education_level', $education);
        }

        if ($search) {
            $query->where('student_id', 'like', "%{$search}%");
        }

        if ($used === 'used') {
            $query->where('used', true);
        } elseif ($used === 'unused') {
            $query->where('used', false);
        }

        // If explicit IDs were provided, limit to them
        if (!empty($ids)) {
            $query->whereIn('id', $ids);
        }

        $query->orderBy('student_id');

        $filename = 'allowed_student_ids_' . date('Ymd_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function() use ($query) {
            $out = fopen('php://output', 'w');
            // Header row
            fputcsv($out, ['id','student_id','education_level','used','deleted_at','created_at','updated_at']);

            // Stream rows via cursor to avoid memory issues
            /** @var \App\Models\AllowedStudentId $row */
            foreach ($query->cursor() as $row) {
                fputcsv($out, [
                    $row->id,
                    $row->student_id,
                    $row->education_level,
                    $row->used ? '1' : '0',
                    $row->deleted_at ? $row->deleted_at->toDateTimeString() : '',
                    $row->created_at ? $row->created_at->toDateTimeString() : '',
                    $row->updated_at ? $row->updated_at->toDateTimeString() : '',
                ]);
            }

            fclose($out);
        };

        return response()->stream($callback, 200, $headers);
    }
}
