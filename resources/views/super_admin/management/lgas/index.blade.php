@extends('layouts.super_admin')
@section('content')
<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">LGA Management</h4>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="alert alert-info">
                            <i class="ri-information-line"></i> LGAs are predefined and managed at system level. Contact system administrator for modifications.
                        </div>
                        <div class="table-responsive">
                            <table id="lgasTable" class="table table-bordered dt-responsive nowrap w-100">
                                <thead class="table-light">
                                    <tr>
                                        <th>Name</th>
                                        <th>Code</th>
                                        <th>Users Count</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($lgas as $lga)
                                        <tr>
                                            <td>{{ $lga->name }}</td>
                                            <td>{{ $lga->code ?? 'N/A' }}</td>
                                            <td>
                                                <span class="badge bg-primary">{{ $lga->users_count }}</span>
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
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('#lgasTable').DataTable({
            responsive: true,
            order: [[0, 'asc']],
            pageLength: 25
        });
    });
</script>
@endpush