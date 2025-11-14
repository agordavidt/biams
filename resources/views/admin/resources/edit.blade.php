@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">Edit Resource: {{ $resource->name }}</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form id="resource-form" action="{{ route('admin.resources.update', $resource) }}" method="POST" 
                          x-data="resourceForm()" @submit.prevent="submitForm">
                        @csrf
                        @method('PUT')

                        <!-- Basic Information Section -->
                        <div class="mb-4">
                            <h5 class="mb-3 text-primary">Basic Information</h5>
                            <div class="row">
                                <div class="col-md-8 mb-3">
                                    <label class="form-label">Resource Name *</label>
                                    <input type="text" class="form-control" name="name" 
                                           value="{{ old('name', $resource->name) }}" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Resource Type *</label>
                                    <select class="form-select" name="type" x-model="resourceType" required>
                                        <option value="">Select Type</option>
                                        <option value="seed" {{ $resource->type == 'seed' ? 'selected' : '' }}>Seeds</option>
                                        <option value="fertilizer" {{ $resource->type == 'fertilizer' ? 'selected' : '' }}>Fertilizer</option>
                                        <option value="equipment" {{ $resource->type == 'equipment' ? 'selected' : '' }}>Equipment</option>
                                        <option value="pesticide" {{ $resource->type == 'pesticide' ? 'selected' : '' }}>Pesticide</option>
                                        <option value="training" {{ $resource->type == 'training' ? 'selected' : '' }}>Training</option>
                                        <option value="service" {{ $resource->type == 'service' ? 'selected' : '' }}>Service</option>
                                        <option value="tractor_service" {{ $resource->type == 'tractor_service' ? 'selected' : '' }}>Tractor Service</option>
                                        <option value="other" {{ $resource->type == 'other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Description *</label>
                                <textarea class="form-control" name="description" rows="3" required>{{ old('description', $resource->description) }}</textarea>
                            </div>
                        </div>

                        <!-- Stock Management (Only for physical resources) -->
                        <div x-show="requiresQuantity" x-transition class="mb-4 border-top pt-3">
                            <h5 class="mb-3 text-primary">Stock Management</h5>
                            
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label class="form-label">Unit of Measurement *</label>
                                    <input type="text" class="form-control" name="unit" 
                                           value="{{ old('unit', $resource->unit) }}"
                                           placeholder="e.g., Kg, Bags, Pieces"
                                           :required="requiresQuantity">
                                    <small class="text-muted">How this resource is measured</small>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Total Stock *</label>
                                    <input type="number" class="form-control" name="total_stock" 
                                           value="{{ old('total_stock', $resource->total_stock) }}"
                                           min="1" :required="requiresQuantity">
                                    <small class="text-muted">Total quantity available</small>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Maximum Per Farmer *</label>
                                    <input type="number" class="form-control" name="max_per_farmer" 
                                           value="{{ old('max_per_farmer', $resource->max_per_farmer) }}"
                                           min="1" :required="requiresQuantity">
                                    <small class="text-muted">Max a farmer can request</small>
                                </div>
                            </div>
                        </div>

                        <!-- Resource Timeline Section -->
                        <div class="mb-4 border-top pt-3">
                            <h5 class="mb-3 text-primary">Resource Timeline</h5>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Start Date</label>
                                    <input type="date" class="form-control" name="start_date" 
                                           x-model="startDate"
                                           value="{{ old('start_date', $resource->start_date ? $resource->start_date->format('Y-m-d') : '') }}">
                                    <small class="text-muted">Leave blank if the resource is available immediately</small>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">End Date</label>
                                    <input type="date" class="form-control" name="end_date" 
                                           x-model="endDate"
                                           :min="startDate || ''"
                                           value="{{ old('end_date', $resource->end_date ? $resource->end_date->format('Y-m-d') : '') }}">
                                    <small class="text-muted">Leave blank if there is no expiration date</small>
                                </div>
                            </div>
                        </div>

                        <!-- Payment Configuration Section -->
                        <div class="mb-4 border-top pt-3">
                            <h5 class="mb-3 text-primary">Payment Configuration</h5>
                            <div class="form-check form-switch mb-3">
                                <input type="hidden" name="requires_payment" value="0">
                                <input type="checkbox" class="form-check-input" id="requires_payment"
                                       name="requires_payment" value="1" x-model="requiresPayment"
                                       {{ old('requires_payment', $resource->requires_payment) ? 'checked' : '' }}>
                                <label class="form-check-label" for="requires_payment">This resource requires payment</label>
                            </div>

                            <div x-show="requiresPayment" x-transition class="mt-3">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Price (₦) *</label>
                                        <input type="number" class="form-control" name="price" 
                                               value="{{ old('price', $resource->price) }}"
                                               step="0.01" min="0" :required="requiresPayment">
                                        <small class="text-muted">All payments will be received by the Ministry of Agriculture</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Application Form Fields Section -->
                        <div class="mb-4 border-top pt-3">
                            <h5 class="mb-3 text-primary">Application Form Fields</h5>
                            <p class="text-muted">Define the fields users will complete when applying for this resource</p>

                            <div class="form-fields-container">
                                <template x-for="(field, index) in fields" :key="index">
                                    <div class="card border mb-3">
                                        <div class="card-body">
                                            <div class="row align-items-end">
                                                <div class="col-md-4">
                                                    <label class="form-label">Field Label *</label>
                                                    <input type="text" class="form-control"
                                                           x-model="field.label" required>
                                                </div>
                                                <div class="col-md-3">
                                                    <label class="form-label">Field Type *</label>
                                                    <select class="form-select" x-model="field.type" required>
                                                        <option value="text">Text</option>
                                                        <option value="number">Number</option>
                                                        <option value="textarea">Long Text</option>
                                                        <option value="select">Dropdown</option>
                                                        <option value="file">File Upload</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-check mt-3">
                                                        <input type="checkbox" class="form-check-input"
                                                               x-model="field.required" :id="'required-' + index">
                                                        <label class="form-check-label" :for="'required-' + index">
                                                            Required Field
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <button type="button" class="btn btn-danger w-100"
                                                            @click="removeField(index)">
                                                        Remove
                                                    </button>
                                                </div>
                                            </div>

                                            <!-- Dropdown Options (shown only when type is 'select') -->
                                            <div class="row mt-3" x-show="field.type === 'select'">
                                                <div class="col-12">
                                                    <label class="form-label">Dropdown Options *</label>
                                                    <input type="text" class="form-control"
                                                           x-model="field.options" placeholder="Option 1, Option 2, Option 3">
                                                    <small class="text-muted">Separate options with commas</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </template>

                                <button type="button" class="btn btn-success" @click="addField">
                                    <i class="ri-add-line me-1"></i> Add Field
                                </button>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="d-flex justify-content-end gap-2 mt-4">
                            <a href="{{ route('admin.resources.index') }}" class="btn btn-light">Cancel</a>
                            <button type="submit" class="btn btn-primary">Update Resource</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<script>
    function resourceForm() {
        return {
            requiresPayment: {{ $resource->requires_payment ? 'true' : 'false' }},
            resourceType: '{{ $resource->type }}',
            startDate: '{{ $resource->start_date ? $resource->start_date->format('Y-m-d') : '' }}',
            endDate: '{{ $resource->end_date ? $resource->end_date->format('Y-m-d') : '' }}',
            fields: @json($resource->form_fields ?? []),

            get requiresQuantity() {
                return !['service', 'training'].includes(this.resourceType);
            },

            init() {
                if (this.fields.length === 0) {
                    this.addField();
                }
            },

            addField() {
                this.fields.push({
                    label: '',
                    type: 'text',
                    required: false,
                    options: ''
                });
            },

            removeField(index) {
                if (this.fields.length > 1) {
                    this.fields.splice(index, 1);
                } else {
                    toastr.error('At least one field is required');
                }
            },

            submitForm(e) {
                const form = e.target;
                const formData = new FormData(form);

                // Handle requires_payment checkbox properly
                const paymentCheckbox = document.getElementById('requires_payment');
                if (paymentCheckbox && paymentCheckbox.checked) {
                    formData.set('requires_payment', '1');
                } else {
                    formData.set('requires_payment', '0');
                }

                // Validate date logic
                if (this.startDate && this.endDate) {
                    if (new Date(this.endDate) < new Date(this.startDate)) {
                        toastr.error('End date must be after start date');
                        return;
                    }
                }

                // Validate payment price
                if (this.requiresPayment) {
                    const price = formData.get('price');
                    if (!price || parseFloat(price) <= 0) {
                        toastr.error('Please enter a valid price when payment is required');
                        return;
                    }
                } else {
                    // Set price to 0 when payment not required
                    formData.set('price', '0');
                }

                // Validate quantity fields for physical resources
                if (this.requiresQuantity) {
                    const unit = formData.get('unit');
                    const totalStock = formData.get('total_stock');
                    const maxPerFarmer = formData.get('max_per_farmer');

                    if (!unit || !totalStock || !maxPerFarmer) {
                        toastr.error('Please fill in all stock management fields');
                        return;
                    }
                } else {
                    // Remove quantity fields for services/training
                    formData.delete('unit');
                    formData.delete('total_stock');
                    formData.delete('max_per_farmer');
                }

                // Remove the original form_fields data
                formData.delete('form_fields');

                // Add the processed fields data
                formData.append('form_fields', JSON.stringify(
                    this.fields.map(field => ({
                        label: field.label,
                        type: field.type,
                        required: field.required,
                        options: field.type === 'select' ? field.options : null
                    }))
                ));

                // Submit via fetch API
                fetch(form.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        toastr.success(data.message || 'Resource updated successfully');
                        setTimeout(() => {
                            window.location.href = data.redirect;
                        }, 1000);
                    } else {
                        const errorMessages = data.errors ? 
                            Object.values(data.errors).flat().join('<br>') : 
                            (data.message || 'An unknown error occurred');
                        toastr.error(errorMessages);
                    }
                })
                .catch(error => {
                    toastr.error('An error occurred: ' + error.message);
                });
            }
        };
    }
</script>
@endpush