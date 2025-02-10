<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manage Resources') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="mb-4">
                        <a href="{{ route('admin.resources.create') }}" 
                           class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Create New Resource
                        </a>
                    </div>

                    <table class="min-w-full">
                        <thead>
                            <tr>
                                <th class="px-6 py-3 border-b-2 border-gray-300 text-left">Name</th>
                                <th class="px-6 py-3 border-b-2 border-gray-300 text-left">Description</th>
                                <th class="px-6 py-3 border-b-2 border-gray-300 text-left">Price</th>
                                <th class="px-6 py-3 border-b-2 border-gray-300 text-left">Target Practice</th>
                                <th class="px-6 py-3 border-b-2 border-gray-300 text-left">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($resources as $resource)
                                <tr>
                                    <td class="px-6 py-4 border-b border-gray-300">{{ $resource->name }}</td>
                                    <td class="px-6 py-4 border-b border-gray-300">{{ Str::limit($resource->description, 50) }}</td>
                                    <td class="px-6 py-4 border-b border-gray-300">
                                        {{ $resource->requires_payment ? '₦' . number_format($resource->price, 2) : 'Free' }}
                                    </td>
                                    <td class="px-6 py-4 border-b border-gray-300">{{ $resource->target_practice }}</td>
                                    <td class="px-6 py-4 border-b border-gray-300">
                                        <a href="{{ route('admin.resources.edit', $resource) }}" 
                                           class="text-blue-600 hover:text-blue-900 mr-2">Edit</a>
                                        
                                        <form action="{{ route('admin.resources.destroy', $resource) }}" 
                                              method="POST" 
                                              class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="text-red-600 hover:text-red-900"
                                                    onclick="return confirm('Are you sure you want to delete this resource?')">
                                                Delete
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>