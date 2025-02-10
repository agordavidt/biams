<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Available Resources') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
                    {{ session('success') }}
                </div>
            @endif

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($resources as $resource)
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold mb-2">{{ $resource->name }}</h3>
                            <p class="text-gray-600 mb-4">{{ Str::limit($resource->description, 100) }}</p>
                            
                            @php
                                $application = $applications->where('resource_id', $resource->id)->first();
                            @endphp

                            @if($application)
                                <div class="mb-4">
                                    <span class="px-2 py-1 rounded text-sm 
                                        @if($application->status === 'approved') bg-green-100 text-green-800
                                        @elseif($application->status === 'rejected') bg-red-100 text-red-800
                                        @else bg-yellow-100 text-yellow-800
                                        @endif">
                                        Status: {{ ucfirst($application->status) }}
                                    </span>
                                </div>
                            @endif

                            <div class="flex justify-between items-center">
                                <span class="text-gray-700">
                                    @if($resource->requires_payment)
                                        Price: ₦{{ number_format($resource->price, 2) }}
                                    @else
                                        Free
                                    @endif
                                </span>
                                
                                @if(!$application)
                                    <a href="{{ route('user.resources.show', $resource) }}"
                                       class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                        View Details
                                    </a>
                                @else
                                    <a href="{{ route('user.resources.track') }}"
                                       class="text-blue-500 hover:text-blue-700">
                                        Track Application
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</x-app-layout>