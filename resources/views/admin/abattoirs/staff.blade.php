@extends('layouts.admin')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Manage Staff for {{ $abattoir->name }}</h4>
                    <a href="{{ route('admin.abattoirs.index') }}" class="btn btn-secondary">Back to Abattoirs</a>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h5>Add Staff</h5>
                        <form method="POST" action="{{ route('admin.abattoirs.staff.assign', $abattoir) }}">
                            @csrf
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">User</label>
                                    <select name="user_id" class="form-control @error('user_id') is-invalid @enderror">
                                        <option value="">Select User</option>
                                        @foreach ($users as $user)
                                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('user_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Role</label>
                                    <select name="role" class="form-control @error('role') is-invalid @enderror">
                                        <option value="supervisor">Supervisor</option>
                                        <option value="meat_inspector">Meat Inspector</option>
                                        <option value="veterinary_officer">Veterinary Officer</option>
                                        <option value="cleaner">Cleaner</option>
                                        <option value="security">Security</option>
                                        <option value="other">Other</option>
                                    </select>
                                    @error('role') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Start Date</label>
                                    <input type="date" name="start_date" class="form-control @error('start_date') is-invalid @enderror" value="{{ old('start_date') }}">
                                    @error('start_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary">Assign</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h5>Current Staff</h5>
                        <table class="table datatable">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Role</th>
                                    <th>Start Date</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($staff as $member)
                                    <tr>
                                        <td>{{ $member->user->name }}</td>
                                        <td>{{ ucfirst(str_replace('_', ' ', $member->role)) }}</td>
                                        <td>{{ $member->start_date->format('Y-m-d') }}</td>
                                        <td>{{ $member->is_active ? 'Active' : 'Inactive' }}</td>
                                        <td>
                                            <form action="{{ route('admin.abattoirs.staff.remove', [$abattoir, $member]) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Remove this staff member?')">Remove</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('.datatable').DataTable({
                responsive: true,
            });
        });
    </script>
@endpush