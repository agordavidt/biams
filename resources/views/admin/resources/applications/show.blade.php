<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            Application Details
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    {{-- Application Details --}}
                    <div class="grid grid-cols-1 gap-6 mb-6 md:grid-cols-2">
                        <div>
                            <h3 class="text-lg font-semibold mb-2">User Information</h3>
                            <p><strong>Name:</strong> {{ $application->user->name }}</p>
                            <p><strong>Email:</strong> {{ $application->user->email }}</p>
                            <p><strong>Submitted:</strong> {{ $application->submitted_date }}</p>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold mb-2">Resource Information</h3>
                            <p><strong>Name:</strong> {{ $application->resource->name }}</p>
                            <p><strong>Type:</strong> {{ ucfirst($application->resource->target_practice) }}</p>
                            @if($application->resource->requires_payment)
                                <p><strong>Payment Status:</strong> {{ ucfirst($application->payment_status) }}</p>
                            @endif
                        </div>
                    </div>

                    {{-- Form Data --}}
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold mb-2">Application Details</h3>
                        @foreach($application->form_data as $key => $value)
                            <div class="mb-2">
                                <strong>{{ ucfirst(str_replace('_', ' ', $key)) }}:</strong>
                                <span>{{ is_array($value) ? implode(', ', $value) : $value }}</span>
                            </div>
                        @endforeach
                    </div>

                    {{-- Status Update Form --}}
                    @if($application->canBeEdited())
                        <form action="{{ route('admin.applications.update-status', $application) }}" method="POST" class="mt-6">
                            @csrf
                            @method('PUT')
                            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Update Status</label>
                                    <select name="status" class="mt-1 block w-full rounded-md border-gray-300">
                                        @foreach(\App\Models\ResourceApplication::getStatusOptions() as $status)
                                            @if($application->canTransitionTo($status))
                                                <option value="{{ $status }}">{{ ucfirst($status) }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Note (Optional)</label>
                                    <textarea name="note" rows="3" 
                                        class="mt-1 block w-full rounded-md border-gray-300"
                                        placeholder="Add a note to the applicant..."></textarea>
                                </div>
                            </div>
                            <div class="mt-4">
                                <button type="submit" 
                                    class="px-4 py-2 text-white bg-blue-500 rounded-md hover:bg-blue-600">
                                    Update Status
                                </button>
                            </div>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>