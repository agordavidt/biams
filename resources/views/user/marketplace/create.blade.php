@extends('layouts.new')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Create New Listing</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('marketplace.index') }}">Marketplace</a></li>
                    <li class="breadcrumb-item active">Create Listing</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">Listing Details</h4>

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('marketplace.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="title" class="form-label">Title <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="title" name="title" value="{{ old('title') }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="category_id" class="form-label">Category <span class="text-danger">*</span></label>
                                <select class="form-select" id="category_id" name="category_id" required>
                                    <option value="">Select a category</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="price" class="form-label">Price (₦) <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="price" name="price" value="{{ old('price') }}" min="0" step="0.01" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="unit" class="form-label">Unit (optional)</label>
                                <input type="text" class="form-control" id="unit" name="unit" value="{{ old('unit') }}" placeholder="e.g., kg, bag, piece">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="quantity" class="form-label">Quantity (optional)</label>
                                <input type="number" class="form-control" id="quantity" name="quantity" value="{{ old('quantity') }}" min="1">
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Description <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="description" name="description" rows="5" required>{{ old('description') }}</textarea>
                        <small class="text-muted">Provide detailed information about your product or service</small>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="location" class="form-label">Location <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="location" name="location" value="{{ old('location') }}" required>
                                <small class="text-muted">Specify where the item is located (e.g., Makurdi, North Bank)</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="expires_in" class="form-label">Listing Duration <span class="text-danger">*</span></label>
                                <select class="form-select" id="expires_in" name="expires_in" required>
                                    <option value="7" {{ old('expires_in') == '7' ? 'selected' : '' }}>7 days</option>
                                    <option value="14" {{ old('expires_in') == '14' ? 'selected' : '' }}>14 days</option>
                                    <option value="30" {{ old('expires_in') == '30' ? 'selected' : '' }}>30 days</option>
                                    <option value="60" {{ old('expires_in') == '60' ? 'selected' : '' }}>60 days</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="image" class="form-label">Upload Image (optional)</label>
                        <input type="file" class="form-control" id="image" name="image" accept="image/*">
                        <small class="text-muted">Max file size: 2MB. Adding a clear image can help sell your item faster.</small>
                    </div>

                    <div class="d-flex justify-content-between mt-4">
                        <a href="{{ route('marketplace.my-listings') }}" class="btn btn-light">Cancel</a>
                        <button type="submit" class="btn btn-primary">Create Listing</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // You can add form validation or other scripts here if needed
    });
</script>
@endsection