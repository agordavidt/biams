@extends('layouts.super_admin')

@section('content')
<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Content Management</h4>
                </div>
            </div>
        </div>
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title mb-4">Site Content Settings</h4>
                        <form action="{{ route('super_admin.content.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3">
                                <label for="site_logo" class="form-label">Site Logo</label><br>
                                @if(!empty($settings['site_logo']))
                                    <img src="{{ asset('storage/' . $settings['site_logo']) }}" alt="Site Logo" style="max-height: 60px; display:block; margin-bottom:10px;">
                                @endif
                                <input type="file" class="form-control" id="site_logo" name="site_logo" accept="image/*">
                            </div>
                            <div class="mb-3">
                                <label for="banner" class="form-label">Banner Image</label><br>
                                @if(!empty($settings['banner']))
                                    <img src="{{ asset('storage/' . $settings['banner']) }}" alt="Banner" style="max-width: 100%; max-height: 120px; display:block; margin-bottom:10px;">
                                @endif
                                <input type="file" class="form-control" id="banner" name="banner" accept="image/*">
                            </div>
                            <div class="mb-3">
                                <label for="site_title" class="form-label">Site Title</label>
                                <input type="text" class="form-control" id="site_title" name="site_title" value="{{ $settings['site_title'] ?? '' }}" required>
                            </div>
                            <div class="mb-3">
                                <label for="contact_email" class="form-label">Contact Email</label>
                                <input type="email" class="form-control" id="contact_email" name="contact_email" value="{{ $settings['contact_email'] ?? '' }}" required>
                            </div>
                            <div class="mb-3">
                                <label for="contact_phone" class="form-label">Contact Phone</label>
                                <input type="text" class="form-control" id="contact_phone" name="contact_phone" value="{{ $settings['contact_phone'] ?? '' }}" required>
                            </div>
                            <div class="mb-3">
                                <label for="address" class="form-label">Address</label>
                                <input type="text" class="form-control" id="address" name="address" value="{{ $settings['address'] ?? '' }}" required>
                            </div>
                            <div class="mb-3">
                                <label for="region_name" class="form-label">Region Name</label>
                                <input type="text" class="form-control" id="region_name" name="region_name" value="{{ $settings['region_name'] ?? '' }}" required>
                            </div>
                            <div class="mb-3">
                                <label for="currency" class="form-label">Currency</label>
                                <input type="text" class="form-control" id="currency" name="currency" value="{{ $settings['currency'] ?? '' }}" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Save Changes</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
