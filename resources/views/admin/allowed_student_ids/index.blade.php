@extends('layouts.app')

@section('content')
<div class="page-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h3>Allowed Student IDs</h3>
            <p class="text-muted mb-0">Manage IDs that can register to the system.</p>
        </div>
        <div>
            <a href="{{ route('admin.allowed-student-ids.create') }}" class="btn btn-outline-primary">Add ID</a>
        </div>
    </div>
</div>

<div class="mb-3 d-flex gap-3 align-items-center">
    <form method="GET" class="d-flex gap-2 align-items-center">
        <label class="mb-0">Show:</label>
        <select name="show" onchange="this.form.submit()" class="form-select w-auto">
            <option value="active" {{ $show === 'active' ? 'selected' : '' }}>Active</option>
            <option value="trashed" {{ $show === 'trashed' ? 'selected' : '' }}>Trashed</option>
            <option value="all" {{ $show === 'all' ? 'selected' : '' }}>All</option>
        </select>

        <label class="mb-0 ms-3">Education:</label>
        <select name="education_level" onchange="this.form.submit()" class="form-select w-auto">
            <option value="">All</option>
            <option value="kindergarten" {{ (isset($education) && $education === 'kindergarten') ? 'selected' : '' }}>Kindergarten</option>
            <option value="elementary" {{ (isset($education) && $education === 'elementary') ? 'selected' : '' }}>Elementary</option>
            <option value="junior_high" {{ (isset($education) && $education === 'junior_high') ? 'selected' : '' }}>Junior High School</option>
            <option value="senior_high" {{ (isset($education) && $education === 'senior_high') ? 'selected' : '' }}>Senior High School</option>
            <option value="college" {{ (isset($education) && $education === 'college') ? 'selected' : '' }}>College</option>
            <option value="other" {{ (isset($education) && $education === 'other') ? 'selected' : '' }}>Other</option>
        </select>

        <label class="mb-0 ms-3">Search:</label>
        <input type="search" name="q" value="{{ isset($search) ? $search : '' }}" placeholder="Student ID" class="form-control form-control-sm w-auto ms-2" />

        <label class="mb-0 ms-3">Used:</label>
        <select name="used" onchange="this.form.submit()" class="form-select w-auto">
            <option value="">All</option>
            <option value="used" {{ (isset($used) && $used === 'used') ? 'selected' : '' }}>Used</option>
            <option value="unused" {{ (isset($used) && $used === 'unused') ? 'selected' : '' }}>Unused</option>
        </select>
    </form>

    <form method="POST" action="{{ route('admin.allowed-student-ids.import') }}" enctype="multipart/form-data" class="d-flex align-items-center gap-2">
        @csrf
        <input type="file" name="csv" accept=".csv" required />
        <div class="form-check ms-2">
            <input class="form-check-input" type="checkbox" name="preview" id="preview" value="1" {{ request()->old('preview') ? 'checked' : '' }} />
            <label class="form-check-label small" for="preview">Dry run (preview only)</label>
        </div>
        <button class="btn btn-sm btn-secondary" type="submit">Import CSV</button>
        <a href="/samples/allowed_student_ids_sample.csv" class="btn btn-sm btn-link ms-2" download>Download sample CSV</a>
        <form method="POST" action="{{ route('admin.allowed-student-ids.export') }}" class="ms-2" style="display:inline-block">
            @csrf
            <input type="hidden" name="select_all" value="1" />
            <input type="hidden" name="show" value="{{ request()->get('show','active') }}" />
            <input type="hidden" name="education_level" value="{{ request()->get('education_level','') }}" />
            <input type="hidden" name="q" value="{{ request()->get('q','') }}" />
            <input type="hidden" name="used" value="{{ request()->get('used','') }}" />
            <button type="submit" class="btn btn-sm btn-link">Export CSV (Filtered)</button>
        </form>
    </form>
</div>

@if(session('import_report'))
    @php($r = session('import_report'))
    <div class="card mb-3">
        <div class="card-body">
            <h5 class="card-title">Import Report</h5>
            <p class="mb-1"><strong>Rows processed:</strong> {{ $r['rows'] }}</p>
            <p class="mb-1"><strong>Inserted:</strong> {{ $r['inserted'] }}</p>
            <p class="mb-1"><strong>Updated:</strong> {{ $r['updated'] }}</p>
            <p class="mb-1"><strong>Restored:</strong> {{ $r['restored'] }}</p>
            <p class="mb-1"><strong>Duplicates skipped:</strong> {{ $r['skipped_duplicate'] }}</p>
            <p class="mb-1"><strong>Invalid rows:</strong> {{ $r['invalid'] }}</p>
            @if(!empty($r['errors']))
                <hr>
                <h6>Errors / Notes</h6>
                <ul class="small mb-0">
                    @foreach($r['errors'] as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>
@endif

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

    <form id="bulk-actions-form">
    @csrf
    <table class="table table-striped">
    <thead>
        <tr>
            <th style="width:40px"><input type="checkbox" id="select-all" /></th>
            <th>Student ID</th>
            <th>Education Level</th>
            <th>Used</th>
            <th>Deleted</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @forelse($items as $item)
            <tr>
                <td><input type="checkbox" class="select-item" value="{{ $item->id }}" /></td>
                <td>{{ $item->student_id }}</td>
                <td>{{ ucfirst($item->education_level) }}</td>
                <td>{{ $item->used ? 'Yes' : 'No' }}</td>
                <td>{{ $item->deleted_at ? $item->deleted_at->toDateTimeString() : '' }}</td>
                <td>
                    @if(!$item->trashed())
                        <button type="button" class="btn btn-sm btn-outline-primary" id="export-selected">Export Selected</button>
                        <button type="button" class="btn btn-sm btn-outline-secondary" id="export-matching">Export Matching</button>
                        <a href="{{ route('admin.allowed-student-ids.edit', $item) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                        <form method="POST" action="{{ route('admin.allowed-student-ids.destroy', $item) }}" style="display:inline-block" onsubmit="return confirm('Delete this ID?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger">Delete</button>
                        </form>
                    @else
                        <form method="POST" action="{{ route('admin.allowed-student-ids.restore', $item->id) }}" style="display:inline-block">
                            @csrf
                            <button class="btn btn-sm btn-success">Restore</button>
                        </form>
                    @endif
                </td>
            </tr>
        @empty
            <tr><td colspan="6">No records found.</td></tr>
        @endforelse
    </tbody>
    </table>

    <div id="select-all-banner" class="alert alert-info d-none">
        <span id="select-all-text"></span>
        <a href="#" id="select-all-across" class="ms-2">Select all <span id="total-count">{{ $items->total() }}</span> matching results</a>
    </div>

    <div class="d-flex gap-2 mb-3">
        <button type="button" class="btn btn-sm btn-danger" id="bulk-delete">Delete Selected</button>
        <button type="button" class="btn btn-sm btn-success" id="bulk-restore">Restore Selected</button>
        <button type="button" class="btn btn-sm btn-warning" id="bulk-reset-used">Reset Used (Selected)</button>
        <form method="POST" action="{{ route('admin.allowed-student-ids.resetUsed') }}" id="reset-all-form" style="display:inline-block">
            @csrf
            <input type="hidden" name="scope" value="all" />
            <button class="btn btn-sm btn-outline-secondary" type="submit">Reset Used (All)</button>
        </form>
    </div>

    <div>
        {{ $items->links() }}
    </div>
    </form>

    <script>
        (function(){
            const selectAll = document.getElementById('select-all');
            const checkboxes = document.querySelectorAll('.select-item');
            const selectAllBanner = document.getElementById('select-all-banner');
            const selectAllText = document.getElementById('select-all-text');
            const selectAllAcrossLink = document.getElementById('select-all-across');
            const totalCount = {{ $items->total() }};
            const pageCount = {{ $items->count() }};
            let selectAllAcross = false;

            function updateBannerForPageSelection() {
                selectAllBanner.classList.remove('d-none');
                selectAllText.textContent = `All ${pageCount} items on this page are selected.`;
                selectAllAcrossLink.classList.remove('d-none');
            }

            function updateBannerForAllSelection() {
                selectAllBanner.classList.remove('d-none');
                selectAllText.textContent = `All ${totalCount} matching results are selected.`;
                selectAllAcrossLink.classList.add('d-none');
            }

            selectAll && selectAll.addEventListener('change', function(){
                checkboxes.forEach(cb => cb.checked = this.checked);
                selectAllAcross = false;
                if (this.checked) {
                    updateBannerForPageSelection();
                } else {
                    selectAllBanner.classList.add('d-none');
                }
            });

            selectAllAcrossLink && selectAllAcrossLink.addEventListener('click', function(e){
                e.preventDefault();
                selectAllAcross = true;
                updateBannerForAllSelection();
            });

            function getSelectedIds(){
                return Array.from(document.querySelectorAll('.select-item:checked')).map(i => i.value);
            }

            function submitPost(url, data){
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = url;
                form.style.display = 'none';
                const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                const inputToken = document.createElement('input'); inputToken.name = '_token'; inputToken.value = token; form.appendChild(inputToken);
                for (const k in data){
                            // Export selected or matching
                            document.getElementById('export-selected').addEventListener('click', function(){
                                const ids = getSelectedIds();
                                if (!ids.length && !selectAllAcross){ alert('No IDs selected.'); return; }
                                const data = selectAllAcross ? { select_all: 1, show: '{{ request()->get('show','active') }}', education_level: '{{ request()->get('education_level','') }}', q: '{{ request()->get('q','') }}', used: '{{ request()->get('used','') }}' } : { ids: ids };
                                submitPost('{{ route('admin.allowed-student-ids.export') }}', data);
                            });

                            document.getElementById('export-matching').addEventListener('click', function(){
                                const data = { select_all: 1, show: '{{ request()->get('show','active') }}', education_level: '{{ request()->get('education_level','') }}', q: '{{ request()->get('q','') }}', used: '{{ request()->get('used','') }}' };
                                submitPost('{{ route('admin.allowed-student-ids.export') }}', data);
                            });
                    const v = data[k];
                    if (Array.isArray(v)){
                        v.forEach(val => { const inp = document.createElement('input'); inp.name = k+'[]'; inp.value = val; form.appendChild(inp); });
                    } else {
                        const inp = document.createElement('input'); inp.name = k; inp.value = v; form.appendChild(inp);
                    }
                }
                document.body.appendChild(form);
                form.submit();
            }

            document.getElementById('bulk-delete').addEventListener('click', function(){
                const ids = getSelectedIds();
                if (!ids.length && !selectAllAcross){ alert('No IDs selected.'); return; }
                if (!confirm('Delete selected IDs? This is a soft-delete.')) return;
                const data = selectAllAcross ? { select_all: 1, show: '{{ request()->get('show','active') }}', education_level: '{{ request()->get('education_level','') }}', q: '{{ request()->get('q','') }}', used: '{{ request()->get('used','') }}' } : { ids: ids };
                submitPost('{{ route('admin.allowed-student-ids.bulkDelete') }}', data);
            });

            document.getElementById('bulk-restore').addEventListener('click', function(){
                const ids = getSelectedIds();
                if (!ids.length && !selectAllAcross){ alert('No IDs selected.'); return; }
                if (!confirm('Restore selected IDs?')) return;
                const data = selectAllAcross ? { select_all: 1, show: '{{ request()->get('show','active') }}', education_level: '{{ request()->get('education_level','') }}', q: '{{ request()->get('q','') }}', used: '{{ request()->get('used','') }}' } : { ids: ids };
                submitPost('{{ route('admin.allowed-student-ids.bulkRestore') }}', data);
            });

            document.getElementById('bulk-reset-used').addEventListener('click', function(){
                const ids = getSelectedIds();
                if (!ids.length && !selectAllAcross){ alert('No IDs selected.'); return; }
                if (!confirm('Reset "used" flag for selected IDs?')) return;
                const data = selectAllAcross ? { select_all: 1, show: '{{ request()->get('show','active') }}', education_level: '{{ request()->get('education_level','') }}', q: '{{ request()->get('q','') }}', used: '{{ request()->get('used','') }}' } : { scope: 'selected', ids: ids };
                submitPost('{{ route('admin.allowed-student-ids.resetUsed') }}', data);
            });
        })();
    </script>

@endsection
