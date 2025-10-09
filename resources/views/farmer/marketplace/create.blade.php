@extends('layouts.farmer')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Create New Listing</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('farmer.marketplace.my-listings') }}">My Listings</a></li>
                    <li class="breadcrumb-item active">Create</li>
                </ol>
            </div>
        </div>
    </div>
</div>

@if($errors->any())
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <strong>Please fix the following errors:</strong>
    <ul class="mb-0 mt-2">
        @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">Listing Information</h4>

                <form method="POST" action="{{ route('farmer.marketplace.store') }}" enctype="multipart/form-data">
                    @csrf

                    <div class="row">
                        <!-- Product Title -->
                        <div class="col-md-8">
                            <div class="mb-3">
                                <label class="form-label">Product Title <span class="text-danger">*</span></label>
                                <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" 
                                       value="{{ old('title') }}" required 
                                       placeholder="e.g., Fresh Organic Tomatoes">
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Category -->
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Category <span class="text-danger">*</span></label>
                                <select name="category_id" class="form-select @error('category_id') is-invalid @enderror" required>
                                    <option value="">Select Category</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="mb-3">
                        <label class="form-label">Product Description <span class="text-danger">*</span></label>
                        <textarea name="description" rows="5" class="form-control @error('description') is-invalid @enderror" 
                                  required placeholder="Describe your product in detail...">{{ old('description') }}</textarea>
                        <small class="text-muted">Provide details about quality, harvesting method, organic certification, etc.</small>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <!-- Price -->
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Price (₦) <span class="text-danger">*</span></label>
                                <input type="number" name="price" step="0.01" class="form-control @error('price') is-invalid @enderror" 
                                       value="{{ old('price') }}" required placeholder="0.00">
                                @error('price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Unit -->
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Unit <span class="text-danger">*</span></label>
                                <select name="unit" class="form-select @error('unit') is-invalid @enderror" required>
                                    <option value="">Select Unit</option>
                                    <option value="kg" {{ old('unit') == 'kg' ? 'selected' : '' }}>Kilogram (kg)</option>
                                    <option value="bag" {{ old('unit') == 'bag' ? 'selected' : '' }}>Bag</option>
                                    <option value="basket" {{ old('unit') == 'basket' ? 'selected' : '' }}>Basket</option>
                                    <option value="crate" {{ old('unit') == 'crate' ? 'selected' : '' }}>Crate</option>
                                    <option value="piece" {{ old('unit') == 'piece' ? 'selected' : '' }}>Piece</option>
                                    <option value="liter" {{ old('unit') == 'liter' ? 'selected' : '' }}>Liter</option>
                                    <option value="tonne" {{ old('unit') == 'tonne' ? 'selected' : '' }}>Tonne</option>
                                </select>
                                @error('unit')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Quantity -->
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Quantity Available</label>
                                <input type="number" name="quantity" class="form-control @error('quantity') is-invalid @enderror" 
                                       value="{{ old('quantity') }}" placeholder="e.g., 100">
                                <small class="text-muted">Optional: Specify available quantity</small>
                                @error('quantity')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Location -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Location (LGA) <span class="text-danger">*</span></label>
                                <select name="location" class="form-select @error('location') is-invalid @enderror" required>
                                    <option value="">Select LGA</option>
                                    @foreach($lgas as $lga)
                                        <option value="{{ $lga->name }}" {{ old('location') == $lga->name ? 'selected' : '' }}>
                                            {{ $lga->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <small class="text-muted">Specify the LGA where the product is located</small>
                                @error('location')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Contact Number -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Contact Phone Number <span class="text-danger">*</span></label>
                                <input type="tel" name="contact" class="form-control @error('contact') is-invalid @enderror" 
                                       value="{{ old('contact') }}" required placeholder="0801234567890">
                                <small class="text-muted">Buyers will use this to contact you</small>
                                @error('contact')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Expiry Duration -->
                    <div class="mb-3">
                        <label class="form-label">Listing Duration <span class="text-danger">*</span></label>
                        <select name="expires_in" class="form-select @error('expires_in') is-invalid @enderror" required>
                            <option value="">Select Duration</option>
                            <option value="7" {{ old('expires_in') == '7' ? 'selected' : '' }}>7 Days</option>
                            <option value="14" {{ old('expires_in') == '14' ? 'selected' : '' }}>14 Days</option>
                            <option value="30" {{ old('expires_in') == '30' ? 'selected' : '' }}>30 Days (Recommended)</option>
                            <option value="60" {{ old('expires_in') == '60' ? 'selected' : '' }}>60 Days</option>
                            <option value="90" {{ old('expires_in') == '90' ? 'selected' : '' }}>90 Days</option>
                        </select>
                        <small class="text-muted">How long should this listing remain active?</small>
                        @error('expires_in')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Image Upload -->
                    <div class="mb-4">
                        <label class="form-label">Product Images <span class="text-danger">*</span></label>
                        <input type="file" name="images[]" class="form-control @error('images') is-invalid @enderror @error('images.*') is-invalid @enderror" 
                               multiple accept="image/jpeg,image/jpg,image/png" required>
                        <small class="text-muted">
                            Upload 1-5 clear images of your product (Max 2MB each, JPEG/PNG only). 
                            <strong>First image will be the main display image.</strong>
                        </small>
                        @error('images')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        @error('images.*')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        
                        <!-- Image Preview -->
                        <div id="imagePreview" class="row mt-3"></div>
                    </div>

                    <hr>

                    <!-- Terms and Conditions -->
                    <div class="mb-3">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="terms" required>
                            <label class="form-check-label" for="terms">
                                I confirm that the information provided is accurate and I agree to the 
                                <a href="#" data-bs-toggle="modal" data-bs-target="#termsModal">Marketplace Terms and Conditions</a>
                            </label>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('farmer.marketplace.my-listings') }}" class="btn btn-secondary">
                            <i class="ri-arrow-left-line me-1"></i>Cancel
                        </a>
                        <button type="submit" class="btn btn-success">
                            <i class="ri-check-line me-1"></i>Submit for Review
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Terms Modal -->
<div class="modal fade" id="termsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Marketplace Terms and Conditions</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <h6>1. Listing Guidelines</h6>
                <ul>
                    <li>All products must be genuine agricultural products grown or produced in Benue State</li>
                    <li>Provide accurate descriptions, prices, and images</li>
                    <li>Listings must comply with all applicable laws and regulations</li>
                </ul>

                <h6>2. Pricing and Transactions</h6>
                <ul>
                    <li>You are responsible for setting fair and competitive prices</li>
                    <li>All transactions occur directly between you and the buyer</li>
                    <li>The platform is not responsible for payment disputes</li>
                </ul>

                <h6>3. Communication</h6>
                <ul>
                    <li>Respond promptly to buyer inquiries</li>
                    <li>Maintain professional communication at all times</li>
                    <li>Provide accurate contact information</li>
                </ul>

                <h6>4. Listing Approval</h6>
                <ul>
                    <li>All listings are subject to review and approval by State Administrators</li>
                    <li>Listings may be rejected if they violate guidelines</li>
                    <li>You will be notified of approval or rejection</li>
                </ul>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Image Preview
document.querySelector('input[name="images[]"]').addEventListener('change', function(e) {
    const preview = document.getElementById('imagePreview');
    preview.innerHTML = '';
    
    const files = Array.from(e.target.files);
    
    if (files.length > 5) {
        Swal.fire('Too Many Images', 'You can only upload up to 5 images.', 'warning');
        e.target.value = '';
        return;
    }
    
    files.forEach((file, index) => {
        if (file.size > 2048000) { // 2MB
            Swal.fire('File Too Large', `${file.name} is larger than 2MB.`, 'warning');
            return;
        }
        
        const reader = new FileReader();
        reader.onload = function(e) {
            const col = document.createElement('div');
            col.className = 'col-md-2 mb-2';
            col.innerHTML = `
                <div class="card">
                    <img src="${e.target.result}" class="card-img-top" alt="Preview">
                    <div class="card-body p-2">
                        <small class="text-muted">${index === 0 ? 'Main Image' : `Image ${index + 1}`}</small>
                    </div>
                </div>
            `;
            preview.appendChild(col);
        };
        reader.readAsDataURL(file);
    });
});
</script>
@endpush
@endsection