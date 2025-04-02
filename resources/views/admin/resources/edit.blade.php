@extends('layouts.admin')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Edit Resource: {{ $resource->name }}</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.resources.index') }}">Resources</a></li>
                        <li class="breadcrumb-item active">Edit</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form id="resource-form" action="{{ route('admin.resources.update', $resource) }}" method="POST" x-data="resourceForm()" @submit.prevent="submitForm">
                        @csrf
                        @method('PUT')

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Resource Name</label>
                                <input type="text" class="form-control" name="name" value="{{ $resource->name }}" required placeholder="Enter resource name">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Target Practice Area</label>
                                <select class="form-select" name="target_practice" required>
                                    <option value="all" {{ $resource->target_practice === 'all' ? 'selected' : '' }}>All Practices</option>
                                    <option value="crop-farmer" {{ $resource->target_practice === 'crop-farmer' ? 'selected' : '' }}>Crop Farming</option>
                                    <option value="animal-farmer" {{ $resource->target_practice === 'animal-farmer' ? 'selected' : '' }}>Animal Farming</option>
                                    <option value="abattoir-operator" {{ $resource->target_practice === 'abattoir-operator' ? 'selected' : '' }}>Abattoir Operation</option>
                                    <option value="processor" {{ $resource->target_practice === 'processor' ? 'selected' : '' }}>Processing</option>
                                </select>
                                <div class="form-text">Select the primary practice area this resource targets.</div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Resource Description</label>
                            <textarea class="form-control" name="description" rows="4" required placeholder="Provide a brief description">{{ $resource->description }}</textarea>
                        </div>

                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input type="hidden" name="requires_payment" value="0">
                                <input type="checkbox" class="form-check-input" id="requires_payment" name="requires_payment" x-model="requiresPayment" value="1" {{ $resource->requires_payment ? 'checked' : '' }}>
                                <label class="form-check-label" for="requires_payment">Requires Payment from Users</label>
                            </div>
                        </div>

                        <div class="mb-3" x-show="requiresPayment">
                            <label class="form-label">Resource Fee (₦)</label>
                            <input type="number" class="form-control" name="price" step="0.01" min="0" x-model="price" value="{{ $resource->price }}" placeholder="Set the fee amount" required>
                            <div class="form-text">Enter the amount users will pay for this resource.</div>
                        </div>

                        <div class="mb-3" x-show="requiresPayment">
                            <label class="form-label">Payment Option for Users</label>
                            <select class="form-select" x-model="paymentOption" name="payment_option" x-bind:required="requiresPayment">
                                <option value="">Select a Payment Option</option>
                                <option value="bank_transfer" {{ $resource->payment_option === 'bank_transfer' ? 'selected' : '' }}>Bank Transfer / Deposit</option>
                                <option value="entrasact" {{ $resource->payment_option === 'entrasact' ? 'selected' : '' }}>Entrasact</option>
                                <option value="paystack" {{ $resource->payment_option === 'paystack' ? 'selected' : '' }}>Paystack</option>
                            </select>
                            <div class="form-text">Choose the payment method for users.</div>
                        </div>

                        <div x-show="requiresPayment && paymentOption === 'bank_transfer'">
                            <div class="mb-3">
                                <label class="form-label">Bank Account Name</label>
                                <input type="text" class="form-control" name="bank_account_name" placeholder="Enter account name" value="{{ $resource->bank_account_name }}" x-bind:required="paymentOption === 'bank_transfer'">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Bank Account Number</label>
                                <input type="text" class="form-control" name="bank_account_number" placeholder="Enter account number" value="{{ $resource->bank_account_number }}" x-bind:required="paymentOption === 'bank_transfer'">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Bank Name</label>
                                <input type="text" class="form-control" name="bank_name" placeholder="Enter bank name" value="{{ $resource->bank_name }}" x-bind:required="paymentOption === 'bank_transfer'">
                            </div>
                        </div>

                        <div x-show="requiresPayment && paymentOption === 'entrasact'">
                            <div class="mb-3">
                                <label class="form-label">Entrasact Payment Instructions</label>
                                <textarea class="form-control" name="entrasact_instruction" rows="3" placeholder="Provide guidance for Entrasact payments" x-bind:required="paymentOption === 'entrasact'">{{ $resource->entrasact_instruction }}</textarea>
                            </div>
                        </div>

                        <div x-show="requiresPayment && paymentOption === 'paystack'">
                            <div class="mb-3">
                                <label class="form-label">Paystack Payment Instructions</label>
                                <textarea class="form-control" name="paystack_instruction" rows="3" placeholder="Provide guidance for Paystack payments" x-bind:required="paymentOption === 'paystack'">{{ $resource->paystack_instruction }}</textarea>
                            </div>
                        </div>

                        <div class="mb-4">
                            <h5>Define Application Form Fields</h5>
                            <p class="text-muted">Edit fields that users will fill out when applying for this resource.</p>
                            <div>
                                <template x-for="(field, index) in fields" :key="index">
                                    <div class="card border mb-3">
                                        <div class="card-body">
                                            <div class="row align-items-end">
                                                <div class="col-md-4">
                                                    <label class="form-label">Field Label <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" x-model="field.label" placeholder="Enter field label" required>
                                                    <!-- <div class="form-text">Label displayed to the user.</div> -->
                                                </div>
                                                <div class="col-md-3">
                                                    <label class="form-label">Field Type <span class="text-danger">*</span></label>
                                                    <select class="form-select" x-model="field.type" required>
                                                        <option value="text">Single Line Text</option>
                                                        <option value="number">Number</option>
                                                        <option value="textarea">Paragraph Text</option>
                                                        <option value="select">Dropdown Select</option>
                                                        <option value="file">File Upload</option>
                                                    </select>
                                                    <div class="form-text">Type of input field.</div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-check mt-2">
                                                        <input type="checkbox" class="form-check-input" x-model="field.required">
                                                        <label class="form-check-label">Required</label>
                                                        <div class="form-text">Check if mandatory.</div>
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <button type="button" class="btn btn-danger w-100" @click="removeField(index)">Remove</button>
                                                </div>
                                            </div>
                                            <div class="row mt-3" x-show="field.type === 'select'">
                                                <div class="col-12">
                                                    <label class="form-label">Dropdown Options <span class="text-info">(comma-separated)</span></label>
                                                    <input type="text" class="form-control" x-model="field.options" placeholder="e.g., Option 1, Option 2">
                                                    <div class="form-text">Enter options separated by commas.</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </template>
                                <button type="button" class="btn btn-success" @click="addField">
                                    <i class="ri-add-line align-middle me-1"></i> Add New Form Field
                                </button>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('admin.resources.index') }}" class="btn btn-light">Cancel</a>
                            <button type="submit" class="btn btn-primary">Update Resource</button>
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
                requiresPayment: {{ $resource->requires_payment ? 'true' : 'false' }},
                price: {{ $resource->price }},
                paymentOption: '{{ $resource->payment_option ?? '' }}',
                fields: @json($resource->form_fields ?? []),

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
                    formData.delete('form_fields');
                    formData.append('form_fields', JSON.stringify(this.fields));

                    Swal.fire({
                        title: 'Saving...',
                        allowOutsideClick: false,
                        didOpen: () => Swal.showLoading()
                    });

                    fetch(form.action, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json',
                        }
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
                            }).then(() => window.location.href = data.redirect);
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Validation Error',
                                html: Object.values(data.errors || {}).flat().join('<br>')
                            });
                        }
                    })
                    .catch(error => {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Something went wrong: ' + error.message
                        });
                    });
                }
            };
        }
    </script>
@endpush