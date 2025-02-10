<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $resource->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold mb-2">Description</h3>
                        <p class="text-gray-600">{{ $resource->description }}</p>
                    </div>

                    <div class="mb-6">
                        <h3 class="text-lg font-semibold mb-2">Details</h3>
                        <ul class="list-disc list-inside">
                            <li>Type: {{ ucfirst($resource->target_practice) }}</li>
                            <li>Cost: 
                                @if($resource->requires_payment)
                                    ₦{{ number_format($resource->price, 2) }}
                                @else
                                    Free
                                @endif
                            </li>
                        </ul>
                    </div>

                    @if($existingApplication)
                        <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 mb-6">
                            <p>You have already applied for this resource. 
                               <a href="{{ route('user.resources.track') }}" class="underline">Track your application</a>
                            </p>
                        </div>
                    @else
                        <div class="mt-6">
                            <a href="{{ route('user.resources.apply', $resource) }}"
                               class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Apply Now
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>