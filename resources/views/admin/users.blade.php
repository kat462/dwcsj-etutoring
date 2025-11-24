@extends('layouts.app')
@section('content')
<h4>All Users</h4>
<table class="table">
  <thead><tr><th>Student ID</th><th>Name</th><th>Role</th><th>Action</th></tr></thead>
  <tbody>
    @foreach($users as $u)
      <tr>
        <td>{{ $u->student_id }}</td>
        <td>{{ $u->name }}</td>
        <td>{{ $u->role }}</td>
        <td>
          <form method="POST" action="{{ route('admin.user.toggle',$u->id) }}" style="display:inline">@csrf
            <button class="btn btn-sm btn-warning">{{ $u->is_active ? 'Deactivate' : 'Activate' }}</button>
          </form>
          <form method="POST" action="{{ route('admin.user.delete',$u->id) }}" style="display:inline">@csrf @method('DELETE')
            <button class="btn btn-sm btn-danger">Delete</button>
          </form>
        </td>
      </tr>
    @endforeach
  </tbody>
</table>
@endsection
