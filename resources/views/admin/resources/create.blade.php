@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">Create New Resource</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form id="resource-form" action="{{ route('admin.resources.store') }}" method="POST" x-data="resourceForm()" @submit.prevent="submitForm">
                        @csrf
                        
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label class="form-label">Resource Name *</label>
                                <input type="text" class="form-control" name="name" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Description *</label>
                            <textarea class="form-control" name="description" rows="3" required></textarea>
                        </div>

                        <div class="row mb-3 mt-4 border-top pt-3 align-items-start">
                            <div class="col-md-6">                                
                                <div class="mb-3">
                                    <label class="form-label">Partner Organization</label>
                                    <select class="form-select" name="partner_id">
                                        <option value="">Ministry of Agriculture</option>
                                        @foreach($partners as $partner)
                                            <option value="{{ $partner->id }}">{{ $partner->legal_name }}</option>
                                        @endforeach
                                    </select>
                                    <small class="text-muted">Select the organization that provides this resource</small>
                                </div>
                            </div>
                            <div class="col-md-6">                                
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Start Date</label>
                                        <input type="date" class="form-control" name="start_date">
                                        <small class="text-muted">Leave blank if available immediately</small>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">End Date</label>
                                        <input type="date" class="form-control" name="end_date">
                                        <small class="text-muted">Leave blank for no expiration</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-check form-switch mb-3">
                            <input type="checkbox" class="form-check-input" id="requires_payment"
                                   name="requires_payment" x-model="requiresPayment">
                            <label class="form-check-label" for="requires_payment">This resource requires payment</label>
                        </div>

                        <div x-show="requiresPayment" x-transition>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label">Price (₦) *</label>
                                    <input type="number" class="form-control" name="price"
                                           step="0.01" min="0" x-bind:required="requiresPayment">
                                    <small class="text-muted">All payments will be received by the Ministry of Agriculture</small>
                                </div>
                            </div>
                        </div>

                        <h5 class="mb-3 mt-4 border-top pt-3">Application Form Fields</h5>
                        <p class="text-muted">Define the fields farmers will complete when applying for this resource. All active farmers can apply for this resource.</p>

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

                        <div class="d-flex justify-content-end gap-2 mt-4 border-top pt-3">
                            <a href="{{ route('admin.resources.index') }}" class="btn btn-light">Cancel</a>
                            <button type="submit" class="btn btn-primary">Create Resource</button>
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
                fields: [],

                init() {
                    this.addField();
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

                    formData.delete('form_fields');

                    formData.append('form_fields', JSON.stringify(
                        this.fields.map(field => ({
                            label: field.label,
                            type: field.type,
                            required: field.required,
                            options: field.type === 'select' ? field.options : null
                        }))
                    ));

                    if (!this.requiresPayment) {
                        formData.set('price', '');
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