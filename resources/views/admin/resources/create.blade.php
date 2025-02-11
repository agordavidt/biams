@extends('layouts.admin')

@section('content')


    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Create New Resource</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Create Resources</li>
                    </ol>
                </div>

            </div>
        </div>
    </div>
    <!-- end page title -->

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4"></h4>

                <form id="resource-form" 
                      action="{{ route('admin.resources.store') }}" 
                      method="POST"
                      x-data="resourceForm()"
                      @submit.prevent="submitForm">
                    @csrf

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Name</label>
                            <input type="text" 
                                   class="form-control" 
                                   name="name" 
                                   required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Target Practice</label>
                            <select class="form-select" name="target_practice" required>
                                <option value="all">All Practices</option>
                                <option value="crop-farmer">Crop Farming</option>
                                <option value="animal-farmer">Animal Farming</option>
                                <option value="abattoir-operator">Abattoir Operation</option>
                                <option value="processor">Processing</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea class="form-control" 
                                  name="description" 
                                  rows="4" 
                                  required></textarea>
                    </div>

                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input type="checkbox" 
                                   class="form-check-input" 
                                   id="requires_payment" 
                                   name="requires_payment"
                                   x-model="requiresPayment">
                            <label class="form-check-label" for="requires_payment">
                                Requires Payment
                            </label>
                        </div>
                    </div>

                    <div class="mb-3" x-show="requiresPayment">
                        <label class="form-label">Price (₦)</label>
                        <input type="number" 
                               class="form-control" 
                               name="price" 
                               step="0.01" 
                               min="0"
                               x-model="price">
                    </div>

                    <div class="mb-4">
                        <h5>Form Fields</h5>
                        <div x-data="{ fields: [] }">
                            <template x-for="(field, index) in fields" :key="index">
                                <div class="card border mb-3">
                                    <div class="card-body">
                                        <div class="row align-items-end">
                                            <div class="col-md-4">
                                                <label class="form-label">Field Label</label>
                                                <input type="text" 
                                                       class="form-control" 
                                                       x-model="field.label" 
                                                       placeholder="Enter field label">
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label">Field Type</label>
                                                <select class="form-select" x-model="field.type">
                                                    <option value="text">Text</option>
                                                    <option value="number">Number</option>
                                                    <option value="textarea">Text Area</option>
                                                    <option value="select">Select</option>
                                                    <option value="file">File Upload</option>
                                                </select>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-check mt-2">
                                                    <input type="checkbox" 
                                                           class="form-check-input" 
                                                           x-model="field.required">
                                                    <label class="form-check-label">Required</label>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <button type="button" 
                                                        class="btn btn-danger w-100"
                                                        @click="fields.splice(index, 1)">
                                                    Remove
                                                </button>
                                            </div>
                                        </div>

                                        <div class="row mt-3" x-show="field.type === 'select'">
                                            <div class="col-12">
                                                <input type="text" 
                                                       class="form-control" 
                                                       x-model="field.options" 
                                                       placeholder="Options (comma-separated)">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </template>

                            <button type="button" 
                                    class="btn btn-success"
                                    @click="fields.push({ 
                                        label: '', 
                                        type: 'text', 
                                        required: false, 
                                        options: '' 
                                    })">
                                <i class="ri-add-line align-middle me-1"></i> Add Field
                            </button>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('admin.resources.index') }}" class="btn btn-light">Cancel</a>
                        <button type="submit" class="btn btn-primary">Create Resource</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function resourceForm() {
    return {
        requiresPayment: false,
        price: 0,
        fields: [],

        submitForm(e) {
            const form = e.target;
            const formData = new FormData(form);
            
            // Add fields data
            formData.append('form_fields', JSON.stringify(this.fields));

            // Show loading
            Swal.fire({
                title: 'Saving...',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            // Submit form
            fetch(form.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: data.message,
                        timer: 1500,
                        showConfirmButton: false
                    }).then(() => {
                        window.location.href = data.redirect;
                    });
                } else {
                    throw new Error(data.message || 'Something went wrong');
                }
            })
            .catch(error => {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: error.message
                });
            });
        }
    };
}
</script>
@endpush