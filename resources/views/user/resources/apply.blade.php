@extends('layouts.new')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Apply for {{ $resource->name }}</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('user.resources.index') }}">Resources</a></li>
                    <li class="breadcrumb-item active">Apply</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-8 mx-auto">
        <div class="card">
            <div class="card-body">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="mb-4">
                    <h5 class="mb-3">Resource Details</h5>
                    <div class="alert alert-light bg-light border border-light">
                        <p class="mb-2">{{ $resource->description }}</p>
                        @if($resource->requires_payment)
                            <p class="mb-0 fw-bold">Fee: ₦{{ number_format($resource->price, 2) }}</p>
                        @endif
                    </div>
                </div>

                <form id="application-form" method="POST" 
                      action="{{ route('user.resources.submit', $resource) }}"
                      enctype="multipart/form-data">
                    @csrf

                    @if($pendingPayment)
                        <div class="alert alert-success mb-4">
                            <i class="ri-check-line me-2"></i>
                            Payment completed successfully! Please complete the application form below.
                        </div>
                    @endif

                    <h5 class="mb-3">Application Form</h5>
                    
                    @foreach($resource->form_fields as $field)
                        @php $fieldName = Str::slug($field['label']); @endphp
                        <div class="mb-3">
                            <label class="form-label">
                                {{ $field['label'] }} 
                                @if($field['required'])<span class="text-danger">*</span>@endif
                            </label>
                            
                            @switch($field['type'])
                                @case('text')
                                    <input type="text" name="{{ $fieldName }}" class="form-control" 
                                           @required($field['required'])>
                                    @break
                                    
                                @case('textarea')
                                    <textarea name="{{ $fieldName }}" class="form-control" 
                                              @required($field['required']) rows="3"></textarea>
                                    @break
                                    
                                @case('number')
                                    <input type="number" name="{{ $fieldName }}" class="form-control"
                                           @required($field['required'])>
                                    @break
                                    
                                @case('file')
                                    <input type="file" name="{{ $fieldName }}" class="form-control"
                                           @required($field['required'])>
                                    @break
                                    
                                @case('select')
                                    <select name="{{ $fieldName }}" class="form-select"
                                            @required($field['required'])>
                                        <option value="">Select an option</option>
                                        @foreach(explode(',', $field['options']) as $option)
                                            <option value="{{ trim($option) }}">{{ trim($option) }}</option>
                                        @endforeach
                                    </select>
                                    @break
                            @endswitch
                        </div>
                    @endforeach

                    <div class="form-footer mt-4 pt-3 border-top">
                        <button type="submit" id="submit-button" class="btn btn-success btn-lg w-100">
                            <i class="ri-send-plane-line me-1"></i> 
                            @if($resource->requires_payment && $pendingPayment)
                                Submit Application
                            @elseif($resource->requires_payment)
                                Proceed to Payment and Submit
                            @else
                                Submit Application
                            @endif
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection