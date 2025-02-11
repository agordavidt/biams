@extends('layouts.admin')

@section('content')
    <!-- Page-Title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Application Details</h4>
                <div class="page-title-right">
                    <a href="{{ route('admin.applications.index') }}" class="btn btn-secondary">
                        <i class="ri-arrow-left-line align-middle me-1"></i> Back to List
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <!-- User Information -->
                        <div class="col-md-6 mb-4">
                            <h5 class="card-title mb-3">User Information</h5>
                            <div class="table-responsive">
                                <table class="table table-borderless mb-0">
                                    <tbody>
                                        <tr>
                                            <th scope="row" width="200">Name</th>
                                            <td>{{ $application->user->name }}</td>
                                        </tr>
                                        <tr>
                                            <th scope="row">Email</th>
                                            <td>{{ $application->user->email }}</td>
                                        </tr>
                                        <tr>
                                            <th scope="row">Submitted Date</th>
                                            <td>{{ $application->created_at->format('M d, Y H:i') }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Resource Information -->
                        <div class="col-md-6 mb-4">
                            <h5 class="card-title mb-3">Resource Information</h5>
                            <div class="table-responsive">
                                <table class="table table-borderless mb-0">
                                    <tbody>
                                        <tr>
                                            <th scope="row" width="200">Resource Name</th>
                                            <td>{{ $application->resource->name }}</td>
                                        </tr>
                                        <tr>
                                            <th scope="row">Type</th>
                                            <td>{{ ucfirst($application->resource->target_practice) }}</td>
                                        </tr>
                                        @if($application->resource->requires_payment)
                                        <tr>
                                            <th scope="row">Payment Status</th>
                                            <td>{{ ucfirst($application->payment_status) }}</td>
                                        </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Application Details -->
                        <div class="col-12 mb-4">
                            <h5 class="card-title mb-3">Application Details</h5>
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <tbody>
                                        @foreach($application->form_data as $key => $value)
                                            <tr>
                                                <th scope="row" width="200">{{ ucfirst(str_replace('_', ' ', $key)) }}</th>
                                                <td>{{ is_array($value) ? implode(', ', $value) : $value }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Status Update Form -->
                        @if($application->canBeEdited())
                        <div class="col-12">
                            <div class="card border">
                                <div class="card-body">
                                    <h5 class="card-title mb-3">Update Application Status</h5>
                                    <form action="{{ route('admin.applications.update-status', $application) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Status</label>
                                                <select name="status" class="form-control">
                                                    @foreach(\App\Models\ResourceApplication::getStatusOptions() as $status)
                                                        @if($application->canTransitionTo($status))
                                                            <option value="{{ $status }}">{{ ucfirst($status) }}</option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Note (Optional)</label>
                                                <textarea name="note" rows="3" 
                                                    class="form-control"
                                                    placeholder="Add a note to the applicant..."></textarea>
                                            </div>
                                        </div>
                                        <div>
                                            <button type="submit" class="btn btn-primary">
                                                <i class="ri-check-line align-middle me-1"></i> Update Status
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection