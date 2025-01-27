<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>



<!-- a new change in profile edit -->
<!-- 
<form method="POST" action="{{ route('profile.update') }}">
    @csrf
    @method('PUT')

   
    <div>
        <label for="name">Name</label>
        <input id="name" type="text" name="name" value="{{ old('name', $user->name) }}" required>
    </div>

    <div>
        <label for="email">Email</label>
        <input id="email" type="email" name="email" value="{{ old('email', $user->email) }}" required>
    </div>

  
    <div>
        <label for="phone">Phone</label>
        <input id="phone" type="text" name="phone" value="{{ old('phone', $profile->phone ?? '') }}" required>
    </div>

    <div>
        <label for="dob">Date of Birth</label>
        <input id="dob" type="date" name="dob" value="{{ old('dob', $profile->dob ?? '') }}" required>
    </div>

    <div>
        <label for="gender">Gender</label>
        <select id="gender" name="gender" required>
            <option value="Male" {{ old('gender', $profile->gender ?? '') === 'Male' ? 'selected' : '' }}>Male</option>
            <option value="Female" {{ old('gender', $profile->gender ?? '') === 'Female' ? 'selected' : '' }}>Female</option>
            <option value="Other" {{ old('gender', $profile->gender ?? '') === 'Other' ? 'selected' : '' }}>Other</option>
        </select>
    </div>

    

    <button type="submit">Update Profile</button>
</form> -->
