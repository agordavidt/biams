@extends('layouts.new')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Agricultural Practices</h4>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Your registered agricultural practices</h4>
                <p class="card-title-desc"></p>

                <div class="table-responsive">
                    <table class="table table-bordered table-striped dt-responsive nowrap" style="width: 100%">
                        <thead class="table-light">
                            <tr>
                                <th width="5%">#</th>
                                <th>Submission ID</th>
                                <th>Application Date</th>
                                <th>Type</th>
                                <th>Status</th>
                                <th width="10%">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($submissions as $index => $submission)
                                <tr>
                                    <td>{{ $index + 1}}</td>
                                    <td>{{ $submission->id }}</td>
                                    <td>{{ $submission->created_at->format('M d, Y') }}</td>
                                    <td>{{ $submission->type }}</td>
                                    <td>
                                        <span class="badge @switch($submission->status)
                                            @case('approved')
                                                bg-success
                                                @break
                                            @case('pending')
                                                bg-warning
                                                @break
                                            @case('rejected')
                                                bg-danger
                                                @break
                                            @default
                                                bg-secondary
                                        @endswitch">
                                            {{ ucfirst($submission->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="#" class="btn btn-primary btn-sm waves-effect waves-light">
                                            <i class="ri-eye-line align-middle me-1"></i> View
                                        </a>
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

@section('scripts')
<script>
    $(document).ready(function() {
        $('.table').DataTable({
            responsive: true,
            lengthChange: false,
            pageLength: 10,
            ordering: true,
            info: true
        });
    });
</script>
@endsection