<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Apply for') }} {{ $resource->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form action="{{ route('user.resources.submit', $resource) }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        @foreach($resource->form_fields as $field)
                            <div class="mb-6">
                                <label for="{{ $field['label'] }}" class="block text-sm font-medium text-gray-700 mb-2">
                                    {{ ucfirst($field['label']) }}
                                    @if(isset($field['required']) && $field['required'])
                                        <span class="text-red-500">*</span>
                                    @endif
                                </label>

                                @switch($field['type'])
                                    @case('text')
                                        <input type="text" 
                                               name="{{ $field['label'] }}" 
                                               id="{{ $field['label'] }}"
                                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                                               @if(isset($field['required']) && $field['required']) required @endif>
                                        @break

                                    @case('textarea')
                                        <textarea name="{{ $field['label'] }}"
                                                  id="{{ $field['label'] }}"
                                                  rows="4"
                                                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                                                  @if(isset($field['required']) && $field['required']) required @endif></textarea>
                                        @break

                                    @case('number')
                                        <input type="number"
                                               name="{{ $field['label'] }}"
                                               id="{{ $field['label'] }}"
                                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                                               @if(isset($field['required']) && $field['required']) required @endif>
                                        @break

                                    @case('file')
                                        <input type="file"
                                               name="{{ $field['label'] }}"
                                               id="{{ $field['label'] }}"
                                               class="mt-1 block w-full"
                                               @if(isset($field['required']) && $field['required']) required @endif>
                                        <p class="mt-1 text-sm text-gray-500">Maximum file size: 2MB</p>
                                        @break

                                    @case('select')
                                        <select name="{{ $field['label'] }}"
                                                id="{{ $field['label'] }}"
                                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                                                @if(isset($field['required']) && $field['required']) required @endif>
                                            <option value="">Select an option</option>
                                            @if(isset($field['options']))
                                                @php
                                                    $options = is_string($field['options']) 
                                                        ? array_map('trim', explode(',', $field['options']))
                                                        : (is_array($field['options']) ? $field['options'] : []);
                                                @endphp
                                                @foreach($options as $option)
                                                    <option value="{{ $option }}">{{ ucfirst($option) }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                        @break
                                @endswitch

                                @error($field['label'])
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        @endforeach

                        @if($resource->requires_payment)
                            <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                                <h3 class="font-medium text-gray-900">Payment Required</h3>
                                <p class="text-gray-600">Amount: ₦{{ number_format($resource->price, 2) }}</p>
                            </div>
                        @endif

                        <div class="flex justify-end">
                            <button type="submit" 
                                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Submit Application
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>