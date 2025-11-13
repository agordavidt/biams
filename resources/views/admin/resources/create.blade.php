@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">Create New Resource</h4>
                <p class="text-muted">Create a resource for the Ministry of Agriculture</p>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form id="resource-form" action="{{ route('admin.resources.store') }}" method="POST" 
                          x-data="resourceForm()" @submit.prevent="submitForm">
                        @csrf
                        
                        <!-- Basic Information -->
                        <h5 class="mb-3">Basic Information</h5>
                        
                        <div class="row mb-3">
                            <div class="col-md-8">
                                <label class="form-label">Resource Name *</label>
                                <input type="text" class="form-control" name="name" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Resource Type *</label>
                                <select class="form-select" name="type" x-model="resourceType" required>
                                    <option value="">Select Type</option>
                                    <option value="seed">Seeds</option>
                                    <option value="fertilizer">Fertilizer</option>
                                    <option value="equipment">Equipment</option>
                                    <option value="pesticide">Pesticide</option>
                                    <option value="training">Training</option>
                                    <option value="service">Service</option>
                                    <option value="tractor_service">Tractor Service</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Description *</label>
                            <textarea class="form-control" name="description" rows="3" required></textarea>
                        </div>

                        <!-- Stock Management (Only for physical resources) -->
                        <div x-show="requiresQuantity" x-transition>
                            <h5 class="mb-3 mt-4 border-top pt-3">Stock Management</h5>
                            
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label class="form-label">Unit of Measurement *</label>
                                    <input type="text" class="form-control" name="unit" 
                                           placeholder="e.g., Kg, Bags, Pieces"
                                           x-bind:required="requiresQuantity">
                                    <small class="text-muted">How this resource is measured</small>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Total Stock *</label>
                                    <input type="number" class="form-control" name="total_stock" 
                                           min="1" x-bind:required="requiresQuantity">
                                    <small class="text-muted">Total quantity available</small>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Maximum Per Farmer *</label>
                                    <input type="number" class="form-control" name="max_per_farmer" 
                                           min="1" x-bind:required="requiresQuantity">
                                    <small class="text-muted">Max a farmer can request</small>
                                </div>
                            </div>
                        </div>

                        <!-- Payment Settings -->
                        <h5 class="mb-3 mt-4 border-top pt-3">Payment Settings</h5>
                        
                        <div class="form-check form-switch mb-3">
                            <input type="checkbox" class="form-check-input" id="requires_payment"
                                   name="requires_payment" x-model="requiresPayment">
                            <label class="form-check-label" for="requires_payment">
                                This resource requires payment from farmers
                            </label>
                        </div>

                        <div x-show="requiresPayment" x-transition>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label">Price (₦) *</label>
                                    <input type="number" class="form-control" name="price"
                                           step="0.01" min="0" x-bind:required="requiresPayment">
                                    <small class="text-muted">Amount farmers will pay</small>
                                </div>
                            </div>
                            <div class="alert alert-info">
                                <i class="ri-information-line me-2"></i>
                                All payments will be received by the Ministry of Agriculture
                            </div>
                        </div>

                        <!-- Organization & Availability -->
                        <h5 class="mb-3 mt-4 border-top pt-3">Organization & Availability</h5>
                        
                        <div class="row mb-3">                            
                            <div class="col-md-4">
                                <label class="form-label">Start Date</label>
                                <input type="date" class="form-control" name="start_date">
                                <small class="text-muted">Leave blank for immediate</small>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">End Date</label>
                                <input type="date" class="form-control" name="end_date">
                                <small class="text-muted">Leave blank for no expiration</small>
                            </div>
                        </div>

                        <!-- Application Form Fields -->
                        <h5 class="mb-3 mt-4 border-top pt-3">Application Form Fields</h5>
                        <p class="text-muted">Define additional information farmers will provide when applying</p>

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
                                                    <option value="email">Email</option>
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
                                                <button type="button" class="btn btn-danger btn-sm w-100"
                                                        @click="removeField(index)">
                                                     Remove
                                                </button>
                                            </div>
                                        </div>

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

                            <button type="button" class="btn btn-success btn-sm" @click="addField">
                               Add Form Field
                            </button>
                            <small class="text-muted ms-2">Optional - Add custom fields if needed</small>
                        </div>

                        <!-- Form Actions -->
                        <div class="d-flex justify-content-end gap-2 mt-4 border-top pt-3">
                            <a href="{{ route('admin.resources.index') }}" class="btn btn-light">Cancel</a>
                            <button type="submit" class="btn btn-primary">
                                 Create Resource
                            </button>
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
                requiresPayment: false,
                resourceType: '',
                fields: [],

                get requiresQuantity() {
                    // Services and training don't require quantity
                    return !['service', 'training'].includes(this.resourceType);
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
                    this.fields.splice(index, 1);
                },

                submitForm(e) {
                    const form = e.target;
                    const formData = new FormData(form);

                    // Add form fields JSON
                    if (this.fields.length > 0) {
                        formData.delete('form_fields');
                        formData.append('form_fields', JSON.stringify(
                            this.fields.map(field => ({
                                label: field.label,
                                type: field.type,
                                required: field.required,
                                options: field.type === 'select' ? field.options : null
                            }))
                        ));
                    }

                    // Clear price if not required
                    if (!this.requiresPayment) {
                        formData.set('price', '0');
                    }

                    // Clear quantity fields for services/training
                    if (!this.requiresQuantity) {
                        formData.delete('unit');
                        formData.delete('total_stock');
                        formData.delete('max_per_farmer');
                    }

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
                            toastr.success(data.message || 'Resource created successfully');
                            setTimeout(() => {
                                window.location.href = data.redirect;
                            }, 1000);
                        } else {
                            const errorMessages = data.errors ? 
                                Object.values(data.errors).flat().join('<br>') : 
                                (data.message || 'An error occurred');
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