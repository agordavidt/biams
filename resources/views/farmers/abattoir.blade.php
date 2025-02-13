@extends('layouts.new')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Abattoir Registration</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Abattoir Registration</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <form method="POST" action="{{ route('farmers.abattoir.store') }}">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-4">
                                <label class="form-label" for="facility_type">Facility Type</label>
                                <input type="text" class="form-control" name="facility_type" value="{{ old('facility_type') }}" required>
                            </div>
                            
                            <div class="mb-4">
                                <label class="form-label" for="facility_specs">Facility Specifications</label>
                                <textarea class="form-control" name="facility_specs" rows="4" required>{{ old('facility_specs') }}</textarea>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-4">
                                <label class="form-label" for="operational_capacity">Operational Capacity</label>
                                <input type="text" class="form-control" name="operational_capacity" value="{{ old('operational_capacity') }}" required>
                            </div>

                            <div class="mb-4">
                                <label class="form-label">Certifications</label>
                                <div class="form-check mb-2">
                                    <input type="checkbox" class="form-check-input" name="certifications[]" id="health_cert" value="Health Certificate" {{ in_array('Health Certificate', old('certifications', [])) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="health_cert">Health Certificate</label>
                                </div>
                                <div class="form-check mb-2">
                                    <input type="checkbox" class="form-check-input" name="certifications[]" id="env_cert" value="Environmental Compliance" {{ in_array('Environmental Compliance', old('certifications', [])) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="env_cert">Environmental Compliance</label>
                                </div>
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" name="certifications[]" id="safety_cert" value="Safety Compliance" {{ in_array('Safety Compliance', old('certifications', [])) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="safety_cert">Safety Compliance</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-12 text-end">
                            <a href="{{ route('home') }}" class="btn btn-secondary me-2">Cancel</a>
                            <button type="submit" class="btn btn-primary">Submit Registration</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection