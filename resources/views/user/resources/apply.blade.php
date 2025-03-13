@extends('layouts.new')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Apply for {{ $resource->name }}</h4>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
            <form action="{{ route('user.resources.submit', $resource) }}" method="POST" enctype="multipart/form-data">
                @csrf

                @foreach($resource->form_fields as $field)
                    @php                        
                        $fieldName = Str::slug($field['label']);
                    @endphp
                    <div class="mb-3">
                        <label for="{{ $fieldName }}" class="form-label">
                            {{ ucfirst($field['label']) }}
                            @if(isset($field['required']) && $field['required'])
                                <span class="text-danger">*</span>
                            @endif
                        </label>

                        @switch($field['type'])
                            @case('text')
                                <input type="text" 
                                    name="{{ $fieldName }}" 
                                    id="{{ $fieldName }}"
                                    class="form-control @error($fieldName) is-invalid @enderror"
                                    @if(isset($field['required']) && $field['required']) required @endif>
                                @break

                            @case('textarea')
                                <textarea name="{{ $fieldName }}"
                                        id="{{ $fieldName }}"
                                        rows="4"
                                        class="form-control @error($fieldName) is-invalid @enderror"
                                        @if(isset($field['required']) && $field['required']) required @endif></textarea>
                                @break

                            @case('number')
                                <input type="number"
                                    name="{{ $fieldName }}"
                                    id="{{ $fieldName }}"
                                    class="form-control @error($fieldName) is-invalid @enderror"
                                    @if(isset($field['required']) && $field['required']) required @endif>
                                @break

                            @case('file')
                                <input type="file"
                                    name="{{ $fieldName }}"
                                    id="{{ $fieldName }}"
                                    class="form-control @error($fieldName) is-invalid @enderror"
                                    @if(isset($field['required']) && $field['required']) required @endif>
                                <div class="form-text">Maximum file size: 2MB</div>
                                @break

                            @case('select')
                                <select name="{{ $fieldName }}"
                                        id="{{ $fieldName }}"
                                        class="form-select @error($fieldName) is-invalid @enderror"
                                        @if(isset($field['required']) && $field['required']) required @endif>
                                    <option value="">Select an option</option>
                                    @if(isset($field['options']))
                                        @php
                                            $options = is_string($field['options']) 
                                                ? array_map('trim', explode(',', $field['options']))
                                                : (is_array($field['options']) ? $field['options'] : []);
                                        @endphp
                                        @foreach($options as $option)
                                            <option value="{{ $option }}">{{ ucfirst($option) }}</option>
                                        @endforeach
                                    @endif
                                </select>
                                @break
                        @endswitch

                        @error($fieldName)
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                @endforeach
    

                    @if($resource->requires_payment)
                        <div class="alert alert-info mb-3">
                            <h5 class="alert-heading">Payment Required</h5>
                            <p class="mb-0">Amount: ₦{{ number_format($resource->price, 2) }}</p>
                        </div>
                    @endif

                    <div class="text-end">
                        <button type="submit" class="btn btn-primary">
                            Submit Application
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection