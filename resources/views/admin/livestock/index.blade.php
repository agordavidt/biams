@extends('layouts.admin')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Livestock</h4>
                    <a href="{{ route('admin.livestock.create') }}" class="btn btn-primary">Register Livestock</a>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <form method="GET" class="mb-3">
                            <input type="text" name="search" class="form-control w-25 d-inline" placeholder="Search by ID, species, or LGA" value="{{ request('search') }}">
                            <button type="submit" class="btn btn-secondary">Search</button>
                        </form>

                        <table class="table datatable">
                            <thead>
                                <tr>
                                    <th>Tracking ID</th>
                                    <th>Species</th>
                                    <th>Origin LGA</th>
                                    <th>Owner</th>
                                    <th>Status</th>
                                    <th>Registered By</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($livestock as $animal)
                                    <tr>
                                        <td>{{ $animal->tracking_id }}</td>
                                        <td>{{ ucfirst($animal->species) }}</td>
                                        <td>{{ $animal->origin_lga }}</td>
                                        <td>{{ $animal->owner_name }}</td>
                                        <td>{{ ucfirst($animal->status) }}</td>
                                        <td>{{ $animal->registeredBy->name ?? 'N/A' }}</td>
                                        <td>
                                            <a href="{{ route('admin.livestock.edit', $animal) }}" class="btn btn-sm btn-warning">Edit</a>
                                            <a href="{{ route('admin.livestock.inspections', $animal) }}" class="btn btn-sm btn-info">Inspections</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        {{ $livestock->links() }}
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