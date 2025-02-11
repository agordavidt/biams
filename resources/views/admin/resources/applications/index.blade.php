<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            Resource Applications
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    {{-- Search and Filter Form --}}
                    <form action="{{ route('admin.applications.index') }}" method="GET" class="mb-6">
                        <div class="flex flex-wrap gap-4">
                            <div class="flex-1">
                                <input type="text" name="search" value="{{ request('search') }}" 
                                    class="w-full rounded-md border-gray-300" 
                                    placeholder="Search by user or resource...">
                            </div>
                            <div class="w-48">
                                <select name="status" class="w-full rounded-md border-gray-300">
                                    <option value="">All Statuses</option>
                                    @foreach(\App\Models\ResourceApplication::getStatusOptions() as $status)
                                        <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>
                                            {{ ucfirst($status) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <button type="submit" class="px-4 py-2 text-white bg-blue-500 rounded-md hover:bg-blue-600">
                                Filter
                            </button>
                        </div>
                    </form>

                    {{-- Applications Table --}}
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        User
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Resource
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Status
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Submitted
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($applications as $application)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            {{ $application->user->name }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            {{ $application->resource->name }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                {{ $application->status === 'approved' ? 'bg-green-100 text-green-800' : 
                                                   ($application->status === 'rejected' ? 'bg-red-100 text-red-800' : 
                                                   'bg-yellow-100 text-yellow-800') }}">
                                                {{ ucfirst($application->status) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            {{ $application->submitted_date }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <a href="{{ route('admin.applications.show', $application) }}" 
                                                class="text-blue-600 hover:text-blue-900">View</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination --}}
                    <div class="mt-4">
                        {{ $applications->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

