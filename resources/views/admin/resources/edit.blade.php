<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Resource') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form action="{{ route('admin.resources.update', $resource) }}" 
                          method="POST" 
                          x-data="resourceForm({{ json_encode($resource) }})" 
                          @submit.prevent="submitForm">
                        @csrf
                        @method('PUT')

                        <!-- Same form fields as create, but with :value="resource.fieldname" -->
                        <!-- ... -->
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function resourceForm(resource) {
            return {
                requiresPayment: resource.requires_payment,
                price: resource.price,
                fields: resource.form_fields || [],
                
                submitForm(e) {
                    // Similar to create form submission
                    // ...
                }
            }
        }
    </script>
    @endpush
</x-app-layout>