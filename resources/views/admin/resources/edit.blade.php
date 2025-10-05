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
                          x-data="resourceForm({{ json_encode([
                            'requiresPayment' => $resource->requires_payment,
                            'fields' => $resource->form_fields,
                            'startDate' => $resource->start_date ? $resource->start_date->format('Y-m-d') : null,
                            'endDate' => $resource->end_date ? $resource->end_date->format('Y-m-d') : null
                        ]) }})" 
                          @submit.prevent="submitForm">
                        @csrf
                        @method('PUT')

                        <!-- Basic Information Section -->
                        <div class="mb-4">
                            <h5 class="mb-3 text-primary">Basic Information</h5>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Resource Name *</label>
                                    <input type="text" class="form-control" name="name" value="{{ $resource->name }}" required>
                                </div>                                
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Description *</label>
                                <textarea class="form-control" name="description" rows="3" required>{{ $resource->description }}</textarea>
                            </div>
                        </div>

                        <!-- Partner Organization Section -->
                        <div class="mb-4 border-top pt-3">
                            <h5 class="mb-3 text-primary">Partner Organization</h5>
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label class="form-label">Partner Organization</label>
                                    <select class="form-select" name="partner_id">
                                        <option value="">Ministry of Agriculture</option>
                                        @foreach($partners as $partner)
                                            <option value="{{ $partner->id }}" {{ $resource->partner_id == $partner->id ? 'selected' : '' }}>
                                                {{ $partner->legal_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <small class="text-muted">Select the organization that provides this resource</small>
                                </div>
                            </div>
                        </div>

                        <!-- Resource Timeline Section -->
                        <div class="mb-4 border-top pt-3">
                            <h5 class="mb-3 text-primary">Resource Timeline</h5>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Start Date</label>
                                    <input type="date" class="form-control" name="start_date" x-model="startDate">
                                    <small class="text-muted">Leave blank if the resource is available immediately</small>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">End Date</label>
                                    <input type="date" class="form-control" name="end_date" x-model="endDate">
                                    <small class="text-muted">Leave blank if there is no expiration date</small>
                                </div>
                            </div>
                        </div>

                        <!-- Payment Configuration Section -->
                        <div class="mb-4 border-top pt-3">
                            <h5 class="mb-3 text-primary">Payment Configuration</h5>
                            <div class="form-check form-switch mb-3">
                                <input type="checkbox" class="form-check-input" id="requires_payment"
                                       name="requires_payment" x-model="requiresPayment">
                                <label class="form-check-label" for="requires_payment">This resource requires payment</label>
                            </div>

                            <div x-show="requiresPayment" x-transition class="mt-3">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Price (₦) *</label>
                                        <input type="number" class="form-control" name="price" 
                                               value="{{ $resource->price }}"
                                               step="0.01" min="0" x-bind:required="requiresPayment">
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
        function resourceForm(data = {}) {
            return {
                requiresPayment: data.requiresPayment || false,
                fields: data.fields || [],
                startDate: data.startDate || null,
                endDate: data.endDate || null,

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

                    // Remove the original fields data
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

                    // Handle payment fields based on requiresPayment toggle
                    if (!this.requiresPayment) {
                        // If payment is not required, we'll still send the field but with empty value
                        formData.set('price', '');
                    }

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
                            const errorMessages = data.errors ? Object.values(data.errors).flat().join('<br>') : (data.message || 'An unknown error occurred');
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