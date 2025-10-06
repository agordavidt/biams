@extends('layouts.farmer')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Apply for {{ $resource->name }}</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('farmer.resources.index') }}">Resources</a></li>
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
                    <div class="alert alert-danger alert-dismissible fade show">
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        <h6 class="alert-heading">Please correct the following errors:</h6>
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show">
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show">
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        {{ session('error') }}
                    </div>
                @endif

                @if(session('info'))
                    <div class="alert alert-info alert-dismissible fade show">
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        {{ session('info') }}
                    </div>
                @endif

                <div class="mb-4">
                    <h5 class="mb-3">Resource Details</h5>
                    <div class="alert alert-light bg-light border border-light">
                        <p class="mb-2">{{ $resource->description }}</p>
                        @if($resource->requires_payment)
                            <p class="mb-0 fw-bold text-success">Application Fee: ₦{{ number_format($resource->price, 2) }}</p>
                            @if(!$hasPaid)
                                <small class="text-muted">Payment is required before application submission</small>
                            @endif
                        @endif
                    </div>
                </div>

                @if($resource->requires_payment && !$hasPaid)
                    {{-- Payment Required: Show form that submits to payment initiation --}}
                    <form id="payment-form" method="POST" 
                          action="{{ route('farmer.resources.payment.initiate', $resource) }}"
                          enctype="multipart/form-data">
                        @csrf

                        <div class="alert alert-warning mb-4">
                            <i class="ri-information-line me-2"></i>
                            <strong>Payment Required:</strong> Complete the form below and proceed to payment. Your application will be submitted automatically after successful payment.
                        </div>

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
                                        <input type="text" name="{{ $fieldName }}" 
                                               class="form-control @error($fieldName) is-invalid @enderror" 
                                               value="{{ old($fieldName) }}"
                                               @if($field['required']) required @endif>
                                        @error($fieldName)
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        @break
                                        
                                    @case('textarea')
                                        <textarea name="{{ $fieldName }}" 
                                                  class="form-control @error($fieldName) is-invalid @enderror" 
                                                  @if($field['required']) required @endif 
                                                  rows="3">{{ old($fieldName) }}</textarea>
                                        @error($fieldName)
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        @break
                                        
                                    @case('number')
                                        <input type="number" name="{{ $fieldName }}" 
                                               class="form-control @error($fieldName) is-invalid @enderror"
                                               value="{{ old($fieldName) }}"
                                               @if($field['required']) required @endif>
                                        @error($fieldName)
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        @break
                                        
                                    @case('file')
                                        <input type="file" name="{{ $fieldName }}" 
                                               class="form-control @error($fieldName) is-invalid @enderror"
                                               @if($field['required']) required @endif>
                                        <small class="text-muted">Maximum file size: 2MB</small>
                                        @error($fieldName)
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        @break
                                        
                                    @case('select')
                                        <select name="{{ $fieldName }}" 
                                                class="form-select @error($fieldName) is-invalid @enderror"
                                                @if($field['required']) required @endif>
                                            <option value="">Select an option</option>
                                            @foreach(explode(',', $field['options']) as $option)
                                                <option value="{{ trim($option) }}" 
                                                        {{ old($fieldName) == trim($option) ? 'selected' : '' }}>
                                                    {{ trim($option) }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error($fieldName)
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        @break
                                @endswitch
                            </div>
                        @endforeach

                        <div class="form-footer mt-4 pt-3 border-top">
                            <button type="submit" class="btn btn-primary btn-lg w-100">
                                <i class="ri-secure-payment-line me-1"></i> 
                                Proceed to Payment (₦{{ number_format($resource->price, 2) }})
                            </button>
                            <p class="text-center text-muted mt-2 mb-0">
                                <small>You will be redirected to secure payment gateway</small>
                            </p>
                        </div>
                    </form>

                @else
                    {{-- No Payment Required OR Already Paid: Submit application directly --}}
                    <form id="application-form" method="POST" 
                          action="{{ route('farmer.resources.submit', $resource) }}"
                          enctype="multipart/form-data">
                        @csrf

                        @if($hasPaid)
                            <div class="alert alert-success mb-4">
                                <i class="ri-checkbox-circle-line me-2"></i>
                                <strong>Payment Verified!</strong> Please complete the application form below to finalize your submission.
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
                                        <input type="text" name="{{ $fieldName }}" 
                                               class="form-control @error($fieldName) is-invalid @enderror" 
                                               value="{{ old($fieldName) }}"
                                               @if($field['required']) required @endif>
                                        @error($fieldName)
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        @break
                                        
                                    @case('textarea')
                                        <textarea name="{{ $fieldName }}" 
                                                  class="form-control @error($fieldName) is-invalid @enderror" 
                                                  @if($field['required']) required @endif 
                                                  rows="3">{{ old($fieldName) }}</textarea>
                                        @error($fieldName)
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        @break
                                        
                                    @case('number')
                                        <input type="number" name="{{ $fieldName }}" 
                                               class="form-control @error($fieldName) is-invalid @enderror"
                                               value="{{ old($fieldName) }}"
                                               @if($field['required']) required @endif>
                                        @error($fieldName)
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        @break
                                        
                                    @case('file')
                                        <input type="file" name="{{ $fieldName }}" 
                                               class="form-control @error($fieldName) is-invalid @enderror"
                                               @if($field['required']) required @endif>
                                        <small class="text-muted">Maximum file size: 2MB</small>
                                        @error($fieldName)
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        @break
                                        
                                    @case('select')
                                        <select name="{{ $fieldName }}" 
                                                class="form-select @error($fieldName) is-invalid @enderror"
                                                @if($field['required']) required @endif>
                                            <option value="">Select an option</option>
                                            @foreach(explode(',', $field['options']) as $option)
                                                <option value="{{ trim($option) }}" 
                                                        {{ old($fieldName) == trim($option) ? 'selected' : '' }}>
                                                    {{ trim($option) }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error($fieldName)
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        @break
                                @endswitch
                            </div>
                        @endforeach

                        <div class="form-footer mt-4 pt-3 border-top">
                            <button type="submit" class="btn btn-success btn-lg w-100">
                                <i class="ri-send-plane-line me-1"></i> 
                                Submit Application
                            </button>
                        </div>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Optional: Add form validation feedback
    document.addEventListener('DOMContentLoaded', function() {
        const forms = document.querySelectorAll('form');
        forms.forEach(form => {
            form.addEventListener('submit', function(e) {
                const submitBtn = form.querySelector('button[type="submit"]');
                if (submitBtn) {
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<i class="ri-loader-4-line me-1 spinner-border spinner-border-sm"></i> Processing...';
                }
            });
        });
    });
</script>
@endpush
@endsection