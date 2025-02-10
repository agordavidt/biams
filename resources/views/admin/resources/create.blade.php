<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create Resource') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form action="{{ route('admin.resources.store') }}" method="POST"
                          x-data="resourceForm()"
                          @submit.prevent="submitForm">
                        @csrf

                        <div class="mb-6">
                            <label class="block mb-2">Name</label>
                            <input type="text" name="name"
                                   class="w-full border-gray-300 rounded-md shadow-sm"
                                   required>
                        </div>

                        <div class="mb-6">
                            <label class="block mb-2">Description</label>
                            <textarea name="description"
                                      class="w-full border-gray-300 rounded-md shadow-sm"
                                      rows="4" required></textarea>
                        </div>

                        <div class="mb-6">
                            <label class="block mb-2">Target Practice</label>
                            <select name="target_practice"
                                    class="w-full border-gray-300 rounded-md shadow-sm"
                                    required>
                                <option value="all">All Practices</option>
                                <option value="crop-farmer">Crop Farming</option>
                                <option value="animal-farmer">Animal Farming</option>
                                <option value="abattoir-operator">Abattoir Operation</option>
                                <option value="processor">Processing</option>
                            </select>
                        </div>

                        <div class="mb-6">
                            <label class="flex items-center">
                                <input type="checkbox" name="requires_payment"
                                       x-model="requiresPayment"
                                       class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <span class="ml-2">Requires Payment</span>
                            </label>
                        </div>

                        <div class="mb-6" x-show="requiresPayment">
                            <label class="block mb-2">Price (₦)</label>
                            <input type="number" name="price" step="0.01" min="0"
                                   x-model="price"
                                   class="w-full border-gray-300 rounded-md shadow-sm">
                        </div>

                        <div class="mb-6">
                            <h3 class="text-lg font-medium mb-4">Form Fields</h3>
                            <div x-data="{ fields: [] }">
                                <template x-for="(field, index) in fields" :key="index">
                                    <div class="mb-4 p-4 border rounded-md">
                                        <div class="flex justify-between mb-2">
                                            <input type="text" x-model="field.label"
                                                   placeholder="Field Label"
                                                   class="w-1/3 border-gray-300 rounded-md shadow-sm mr-2">

                                            <select x-model="field.type"
                                                    class="w-1/3 border-gray-300 rounded-md shadow-sm mr-2">
                                                <option value="text">Text</option>
                                                <option value="number">Number</option>
                                                <option value="textarea">Text Area</option>
                                                <option value="select">Select</option>
                                                <option value="file">File Upload</option>
                                            </select>

                                            <button type="button"
                                                    @click="fields.splice(index, 1)"
                                                    class="bg-red-500 text-white px-3 py-1 rounded-md">
                                                Remove
                                            </button>
                                        </div>

                                        <div x-show="field.type === 'select'" class="mt-2">
                                            <input type="text" x-model="field.options"
                                                   placeholder="Options (comma-separated)"
                                                   class="w-full border-gray-300 rounded-md shadow-sm">
                                        </div>

                                        <div class="mt-2">
                                            <label class="flex items-center">
                                                <input type="checkbox" x-model="field.required"
                                                       class="rounded border-gray-300">
                                                <span class="ml-2">Required field</span>
                                            </label>
                                        </div>
                                    </div>
                                </template>

                                <button type="button"
                                        @click="fields.push({ label: '', type: 'text', required: false, options: '' })"
                                        class="bg-green-500 text-white px-4 py-2 rounded-md">
                                    Add Field
                                </button>
                            </div>
                        </div>

                        <div class="mt-6">
                            <button type="submit"
                                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Create Resource
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            function resourceForm(resource = null) {
                return {
                    requiresPayment: resource ? resource.requires_payment : false,
                    price: resource ? resource.price : 0,
                    fields: resource ? resource.form_fields : [],
                    errors: {},

                    submitForm(e) {
                        const form = e.target;
                        const formData = new FormData(form);

                        this.errors = {};

                        const fieldsJson = JSON.stringify(this.fields);
                        formData.append('form_fields', fieldsJson);

                        fetch(form.action, {
                            method: form.method === 'POST' ? 'POST' : 'PUT',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            },
                            body: formData
                        })
                            .then(response => {
                                if (!response.ok) {
                                    return response.json().then(err => {throw new Error(err.message)});
                                }
                                return response.json();
                            })
                            .then(data => {
                                if (data.success) {
                                    window.location.href = data.redirect;
                                } else {
                                    this.errors = data.errors || {};
                                    if (data.message) {
                                        alert(data.message);
                                    }
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                alert(error.message || 'An error occurred while submitting the form.');
                            });
                    }
                };
            }
        </script>
    @endpush
</x-app-layout>



