<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Track Applications') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if($applications->isEmpty())
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-500 text-center">
                        You haven't submitted any applications yet.
                    </div>
                </div>
            @else
                <div class="space-y-6">
                    @foreach($applications as $application)
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <h3 class="text-lg font-semibold">{{ $application->resource->name }}</h3>
                                        <p class="text-gray-600">Submitted: {{ $application->created_at->format('M d, Y') }}</p>
                                    </div>
                                    <span class="px-3 py-1 rounded-full text-sm 
                                        @switch($application->status)
                                            @case('pending')
                                                bg-yellow-100 text-yellow-800
                                                @break
                                            @case('reviewing')
                                                bg-blue-100 text-blue-800
                                                @break
                                            @case('approved')
                                                bg-green-100 text-green-800
                                                @break
                                            @case('rejected')
                                                bg-red-100 text-red-800
                                                @break
                                            @case('processing')
                                                bg-purple-100 text-purple-800
                                                @break
                                            @case('delivered')
                                                bg-gray-100 text-gray-800
                                                @break
                                        @endswitch">
                                        {{ ucfirst($application->status) }}
                                    </span>
                                </div>

                                <div class="mt-4">
                                    <h4 class="font-medium mb-2">Application Details</h4>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        @foreach($application->form_data as $field => $value)
                                            <div>
                                                <span class="text-gray-600">{{ ucfirst($field) }}:</span>
                                                <span class="ml-2">{{ $value }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                @if($application->payment_status)
                                    <div class="mt-4 p-3 bg-gray-50 rounded">
                                        <h4 class="font-medium mb-2">Payment Information</h4>
                                        <div>
                                            <span class="text-gray-600">Status:</span>
                                            <span class="ml-2">{{ ucfirst($application->payment_status) }}</span>
                                        </div>
                                        @if($application->payment_reference)
                                            <div>
                                                <span class="text-gray-600">Reference:</span>
                                                <span class="ml-2">{{ $application->payment_reference }}</span>
                                            </div>
                                        @endif
                                    </div>
                                @endif

                                <div class="mt-4 text-sm text-gray-500">
                                    @switch($application->status)
                                        @case('pending')
                                            Your application is pending review.
                                            @break
                                        @case('reviewing')
                                            Your application is currently being reviewed.
                                            @break
                                        @case('approved')
                                            Your application has been approved!
                                            @if($application->resource->requires_payment && $application->payment_status !== 'paid')
                                                Please complete the payment to proceed.
                                            @endif
                                            @break
                                        @case('rejected')
                                            Unfortunately, your application was not approved.
                                            @break
                                        @case('processing')
                                            Your application is being processed.
                                            @break
                                        @case('delivered')
                                            Your resource has been delivered.
                                            @break
                                    @endswitch
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</x-app-layout>