@extends('layouts.new')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Apply for {{ $resource->name }}</h4>
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <form action="{{ route('user.resources.submit', $resource) }}" method="POST" enctype="multipart/form-data" id="application-form">
                        @csrf
                        @foreach ($resource->form_fields as $field)
                            @php $fieldName = Str::slug($field['label']); @endphp
                            <div class="mb-3">
                                <label class="form-label">{{ $field['label'] }} {{ $field['required'] ? '*' : '' }}</label>
                                @switch($field['type'])
                                    @case('text')
                                        <input type="text" name="{{ $fieldName }}" class="form-control" {{ $field['required'] ? 'required' : '' }}>
                                        @break
                                    @case('textarea')
                                        <textarea name="{{ $fieldName }}" class="form-control" {{ $field['required'] ? 'required' : '' }}></textarea>
                                        @break
                                    @case('number')
                                        <input type="number" name="{{ $fieldName }}" class="form-control" {{ $field['required'] ? 'required' : '' }}>
                                        @break
                                    @case('file')
                                        <input type="file" name="{{ $fieldName }}" class="form-control" {{ $field['required'] ? 'required' : '' }}>
                                        @break
                                    @case('select')
                                        <select name="{{ $fieldName }}" class="form-select" {{ $field['required'] ? 'required' : '' }}>
                                            <option value="">Select</option>
                                            @foreach (is_array($field['options']) ? $field['options'] : explode(',', $field['options']) as $option)
                                                <option value="{{ $option }}">{{ $option }}</option>
                                            @endforeach
                                        </select>
                                        @break
                                @endswitch
                            </div>
                        @endforeach

                        @if ($resource->requires_payment)
                            <div class="alert alert-info">
                                <h5>Payment Required: ₦{{ number_format($resource->price, 2) }}</h5>
                                <p>Payment Option: {{ ucfirst(str_replace('_', ' ', $resource->payment_option)) }}</p>
                                @switch($resource->payment_option)
                                    @case('bank_transfer')
                                        <p>Account Name: {{ $resource->bank_account_name }}</p>
                                        <p>Account Number: {{ $resource->bank_account_number }}</p>
                                        <p>Bank Name: {{ $resource->bank_name }}</p>
                                        <div class="mb-3">
                                            <label class="form-label">Upload Payment Receipt *</label>
                                            <input type="file" name="payment_receipt" class="form-control" required>
                                        </div>
                                        @break
                                    @case('entrasact')
                                        <p>{{ $resource->entrasact_instruction }}</p>
                                        <button type="button" class="btn btn-primary" onclick="initiateEntrasact()">Pay with Entrasact</button>
                                        @break
                                    @case('paystack')
                                        <p>{{ $resource->paystack_instruction }}</p>
                                        <button type="button" class="btn btn-primary" onclick="initiatePaystack()">Pay with Paystack</button>
                                        @break
                                @endswitch
                            </div>
                        @endif

                        <button type="submit" class="btn btn-primary" id="submit-btn">Submit Application</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.getElementById('application-form').addEventListener('submit', function(event) {
            event.preventDefault(); // Prevent default submission
            console.log('Form submission intercepted');

            const form = this;
            const formData = new FormData(form);
            console.log('Form data:');
            for (let [key, value] of formData.entries()) {
                console.log(`${key}: ${value}`);
            }

            fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest', // Ensure Laravel recognizes it as AJAX
                }
            })
            .then(response => {
                console.log('Response status:', response.status);
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.text(); // Use text() to see raw response for debugging
            })
            .then(data => {
                console.log('Response data:', data);
                // Check if redirect is needed (Laravel might return HTML)
                if (data.includes('Application submitted successfully')) {
                    window.location.href = '{{ route('user.resources.index') }}';
                } else {
                    document.body.innerHTML = data; // Display the response (e.g., validation errors)
                }
            })
            .catch(error => {
                console.error('Fetch error:', error);
                alert('An error occurred: ' + error.message);
            });
        });

        function initiateEntrasact() {
            alert('Entrasact payment initiation logic goes here.');
        }

        function initiatePaystack() {
            alert('Paystack payment initiation logic goes here.');
        }
    </script>
@endpush