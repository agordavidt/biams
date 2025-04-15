@extends('layouts.admin')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Abattoirs</h4>
                    <a href="{{ route('admin.abattoirs.create') }}" class="btn btn-primary">Add Abattoir</a>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <form method="GET" class="mb-3">
                            <input type="text" name="search" class="form-control w-25 d-inline" placeholder="Search by name or LGA" value="{{ request('search') }}">
                            <button type="submit" class="btn btn-secondary">Search</button>
                        </form>

                        <table class="table datatable">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Registration #</th>
                                    <th>LGA</th>
                                    <th>Capacity</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($abattoirs as $abattoir)
                                    <tr>
                                        <td>{{ $abattoir->name }}</td>
                                        <td>{{ $abattoir->registration_number }}</td>
                                        <td>{{ $abattoir->lga }}</td>
                                        <td>{{ $abattoir->capacity }}</td>
                                        <td>{{ ucfirst($abattoir->status) }}</td>
                                        <td>
                                            <a href="{{ route('admin.abattoirs.edit', $abattoir) }}" class="btn btn-sm btn-warning">Edit</a>
                                            <a href="{{ route('admin.abattoirs.staff', $abattoir) }}" class="btn btn-sm btn-info">Staff</a>
                                            <a href="{{ route('admin.abattoirs.operations', $abattoir) }}" class="btn btn-sm btn-success">Operations</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        {{ $abattoirs->links() }}
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
                dom: 'Bfrtip',
                buttons: ['csv', 'excel', 'pdf', 'print'],
                responsive: true,
            });
        });
    </script>
@endpush