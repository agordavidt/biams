@extends('layouts.new')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Processing Facility Registration</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Processor Registration</li>
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
                    <form method="POST" action="{{ route('farmers.processor.store') }}">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-4">
                                    <label class="form-label" for="processing_capacity">Processing Capacity</label>
                                    <input type="number" step="0.1" class="form-control" name="processing_capacity"
                                        value="{{ old('processing_capacity') }}" required>
                                </div>

                                <div class="mb-4">
                                    <label class="form-label" for="equipment_specs">Equipment Specifications</label>
                                    <textarea class="form-control" name="equipment_specs" rows="4" required>{{ old('equipment_specs') }}</textarea>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-4">

                                    <label class="form-label" for="equipment_type">Equipment Type</label>
                                    <select class="form-select" name="equipment_type" required>
                                        <option value="">--- Select ---</option>
                                        <option value="Cleaning and Sorting Equipment"
                                            {{ old('equipment_type') === 'Cleaning and Sorting Equipment' ? 'selected' : '' }}>
                                            Cleaning and Sorting Equipment</option>
                                        <option value="Drying Equipment"
                                            {{ old('equipment_type') === 'Drying Equipment' ? 'selected' : '' }}>Drying
                                            Equipment
                                        </option>
                                        <option value="Milling and Grinding"
                                            {{ old('equipment_type') === 'Milling and Grinding' ? 'selected' : '' }}>
                                            Milling and Grinding</option>

                                            <option value="Pressing and Extraction"
                                                {{ old('equipment_type') === 'Pressing and Extraction' ? 'selected' : '' }}>Pressing and Extraction
                                            </option>
                                            <option value="Mixing and Fermentation"
                                                {{ old('equipment_type') === 'Mixing and Fermentation' ? 'selected' : '' }}>Mixing and Fermentation
                                            </option>
                                            <option value="Packaging Equipment"
                                                {{ old('equipment_type') === 'Packaging Equipment' ? 'selected' : '' }}>Packaging Equipment
                                            </option>
                                            <option value="Storage and Handling"
                                                {{ old('equipment_type') === 'Storage and Handling' ? 'selected' : '' }}>Storage and Handling
                                            </option>
                                        </select>

                                    {{-- <label class="form-label" for="equipment_type">Equipment Type</label>
                                <input type="text" class="form-control" name="equipment_type" value="{{ old('equipment_type') }}" required> --}}
                                </div>

                                <div class="mb-4">
                                    <label class="form-label" for="processed_items">Processed Items</label>
                                    <textarea class="form-control" name="processed_items" rows="4">{{ old('processed_items') }}</textarea>
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
