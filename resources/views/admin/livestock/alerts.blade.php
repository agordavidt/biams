@extends('layouts.admin')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Livestock Alerts</h4>
                    <a href="{{ route('admin.livestock.index') }}" class="btn btn-secondary">Back to Livestock</a>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h5>Alerts for Disease or Non-Compliance</h5>
                        <table class="table datatable">
                            <thead>
                                <tr>
                                    <th>Type</th>
                                    <th>Message</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($alerts as $alert)
                                    <tr>
                                        <td>{{ ucfirst(str_replace('_', ' ', $alert['type'])) }}</td>
                                        <td>{{ $alert['message'] }}</td>
                                        <td>{{ $alert['created_at']->format('Y-m-d H:i') }}</td>
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