@extends('layouts.new')



<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            {{-- <h4 class="mb-sm-0">Dashboard</h4> --}}
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
                    <li class="breadcrumb-item active"><a href="{{ route('home') }}">Home</a></li>
                </ol>
            </div>
        </div>
    </div>
</div>







<div class="container mt-5">
    <!-- User Profile Card -->
    <div class="row">
        {{-- <div class="col-md-4">
        <div class="card">
          <img src="https://via.placeholder.com/150" class="card-img-top rounded-circle mx-auto mt-3" alt="User Avatar">
          <div class="card-body text-center">
            <h5 class="card-title">John Doe</h5>
            <p class="text-muted">Software Developer</p>
            <a href="#" class="btn btn-primary">Follow</a>
            <a href="#" class="btn btn-secondary">Message</a>
          </div>
        </div>
      </div> --}}

        <!-- Tabbed Content -->
        <div class="col-md-12">
            <!-- Nav tabs -->
            <ul class="nav nav-pills mb-3" id="profileTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <a class="nav-link active" id="profile-tab" data-bs-toggle="pill" href="#profile" role="tab"
                        aria-controls="profile" aria-selected="true">Profile</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="settings-tab" data-bs-toggle="pill" href="#settings" role="tab"
                        aria-controls="settings" aria-selected="false">Security</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="activity-tab" data-bs-toggle="pill" href="#activity" role="tab"
                        aria-controls="activity" aria-selected="false">Account</a>
                </li>
            </ul>

            <!-- Tab content -->
            <div class="tab-content" id="profileTabContent">
                <!-- Profile Tab -->
                <div class="tab-pane fade show active" id="profile" role="tabpanel" aria-labelledby="profile-tab">

                    <section>
                        <header>
                            <h2 class="text-lg font-medium text-gray-900">
                                {{ __('Profile Information') }}
                            </h2>

                            <p class="mt-1 text-sm text-gray-600">
                                {{ __("Update your account's profile information.") }}
                            </p>
                        </header>

                        <form id="send-verification" method="post" action="{{ route('verification.send') }}">
                            @csrf
                        </form>

                        <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
                            @csrf
                            @method('patch')

                            <!-- Grid Layout -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Name -->
                                <div>
                                    <label class="form-label" for="name">Name</label>
                                    <input type="text" class="form-control input-mask" name="name"
                                        value="{{ old('name', $user->name) }}" required autofocus autocomplete="name">
                                    @error('name')
                                        <p class="text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Email -->
                                <div>
                                    <label class="form-label" for="email">Email</label>
                                    <input type="email" class="form-control input-mask" name="email"
                                        value="{{ old('email', $user->email) }}" required autocomplete="username">
                                    @error('email')
                                        <p class="text-sm text-red-600">{{ $message }}</p>
                                    @enderror

                                    @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !$user->hasVerifiedEmail())
                                        <div>
                                            <p class="text-sm mt-2 text-gray-800">
                                                {{ __('Your email address is unverified.') }}

                                                <button form="send-verification"
                                                    class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                                    {{ __('Click here to re-send the verification email.') }}
                                                </button>
                                            </p>

                                            @if (session('status') === 'verification-link-sent')
                                                <p class="mt-2 font-medium text-sm text-green-600">
                                                    {{ __('A new verification link has been sent to your email address.') }}
                                                </p>
                                            @endif
                                        </div>
                                    @endif
                                </div>

                                <!-- Phone -->
                                <div>
                                    <label class="form-label" for="phone">Phone Number</label>
                                    <input type="tel" class="form-control input-mask" name="phone"
                                        value="{{ old('phone', $user->phone) }}" required>
                                    @error('phone')
                                        <p class="text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Date of Birth -->
                                <div>
                                    <label class="form-label" for="dob">Date of Birth</label>
                                    <input type="date" class="form-control input-mask" name="dob"
                                        value="{{ old('dob', $user->dob) }}" required>
                                    @error('dob')
                                        <p class="text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Gender -->
                                <div>
                                    <label class="form-label" for="gender">Gender</label>
                                    <select class="form-control input-mask" name="gender" required>
                                        <option value="">Select Gender</option>
                                        <option value="Male"
                                            {{ old('gender', $user->gender) === 'Male' ? 'selected' : '' }}>Male
                                        </option>
                                        <option value="Female"
                                            {{ old('gender', $user->gender) === 'Female' ? 'selected' : '' }}>Female
                                        </option>
                                    </select>
                                    @error('gender')
                                        <p class="text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Education -->
                                <div>
                                    <label class="form-label" for="education">Education Level</label>
                                    <select class="form-control input-mask" name="education" required>
                                        <option value="">Select Education Level</option>
                                        <option value="no_formal"
                                            {{ old('education', $user->education) === 'no_formal' ? 'selected' : '' }}>
                                            No Formal School
                                        </option>
                                        <option value="primary"
                                            {{ old('education', $user->education) === 'primary' ? 'selected' : '' }}>
                                            Primary School</option>
                                        <option value="secondary"
                                            {{ old('education', $user->education) === 'secondary' ? 'selected' : '' }}>
                                            Secondary School
                                        </option>
                                        <option value="undergraduate"
                                            {{ old('education', $user->education) === 'undergraduate' ? 'selected' : '' }}>
                                            Undergraduate
                                        </option>
                                        <option value="graduate"
                                            {{ old('education', $user->education) === 'graduate' ? 'selected' : '' }}>
                                            Graduate</option>
                                        <option value="postgraduate"
                                            {{ old('education', $user->education) === 'postgraduate' ? 'selected' : '' }}>
                                            Post Graduate
                                        </option>
                                    </select>
                                    @error('education')
                                        <p class="text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>


                                <!-- Household Size -->
                                <div>
                                    <label class="form-label" for="household_size">Household Size</label>
                                    <input type="number" class="form-control input-mask" name="household_size"
                                        value="{{ old('household_size', $user->household_size) }}" required>
                                    @error('household_size')
                                        <p class="text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Dependents -->
                                <div>
                                    <label class="form-label" for="dependents">Number of Dependents</label>
                                    <input type="number" class="form-control input-mask" name="dependents"
                                        value="{{ old('dependents', $user->dependents) }}" required>
                                    @error('dependents')
                                        <p class="text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Income Level -->
                                <div>
                                    <label class="form-label" for="income_level">Income Level</label>
                                    <select class="form-control input-mask" name="income_level" required>
                                        <option value="">Select Income Level</option>
                                        <option value="0-100000"
                                            {{ old('income_level', $user->income_level) === '0-100000' ? 'selected' : '' }}>
                                            Less than
                                            ₦100,000</option>
                                        <option value="100001-250000"
                                            {{ old('income_level', $user->income_level) === '100001-250000' ? 'selected' : '' }}>
                                            ₦100,001 -
                                            ₦250,000</option>
                                        <option value="250001-500000"
                                            {{ old('income_level', $user->income_level) === '250001-500000' ? 'selected' : '' }}>
                                            ₦250,001 -
                                            ₦500,000</option>
                                        <option value="500001-1000000"
                                            {{ old('income_level', $user->income_level) === '500001-1000000' ? 'selected' : '' }}>
                                            ₦500,001
                                            - ₦1,000,000</option>
                                        <option value="1000001+"
                                            {{ old('income_level', $user->income_level) === '1000001+' ? 'selected' : '' }}>
                                            Above
                                            ₦1,000,000</option>
                                    </select>
                                    @error('income_level')
                                        <p class="text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- LGA -->
                                <div>
                                    <label class="form-label" for="lga">Local Government Area</label>
                                    <select class="form-control input-mask" name="lga" required>
                                        <option value="">Select LGA</option>
                                        <option value="Ado"
                                            {{ old('lga', $user->lga) === 'Ado' ? 'selected' : '' }}>Ado</option>
                                        <option value="Agatu"
                                            {{ old('lga', $user->lga) === 'Agatu' ? 'selected' : '' }}>Agatu</option>
                                        <option value="Apa"
                                            {{ old('lga', $user->lga) === 'Apa' ? 'selected' : '' }}>Apa</option>
                                        <!-- Add other LGAs here -->
                                    </select>
                                    @error('lga')
                                        <p class="text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Save Button -->
                            <div class="flex items-center gap-4 mt-6">
                                <button type="submit" class="btn btn-primary waves-effect waves-light">Save</button>

                                @if (session('status') === 'profile-updated')
                                    <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                                        class="text-sm text-gray-600">{{ __('Saved.') }}</p>
                                @endif
                            </div>
                        </form>
                    </section>

                </div>

                <!-- Security Tab -->
                <div class="tab-pane fade" id="settings" role="tabpanel" aria-labelledby="settings-tab">
                    <section>
                        <header>
                            <h2 class="text-lg font-medium text-gray-900">
                                {{ __('Update Password') }}
                            </h2>

                            <p class="mt-1 text-sm text-gray-600">
                                {{ __('Ensure your account is using a long, random password to stay secure.') }}
                            </p>
                        </header>
                    </section>

                    {{-- PASSWORD UPDATE AND OTHER SECURITY SETTINGS COME HERE --}}

                </div>

                <!-- Account Tab -->
                <div class="tab-pane fade" id="activity" role="tabpanel" aria-labelledby="activity-tab">
                    <section>
                        <header>
                            <h2 class="text-lg font-medium text-gray-900">
                                {{ __('Delete Account') }}
                            </h2>

                            <p class="mt-1 text-sm text-gray-600">
                                {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.') }}
                            </p>
                        </header>
                    </section>

                    {{-- PASSWORD UPDATE AND OTHER SECURITY SETTINGS COME HERE --}}
                </div>
            </div>
        </div>
    </div>

</div>




