@extends('layouts.super_admin')

@section('content')
<div class="page-content">
    <div class="container-fluid">
        <!-- Page Title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">System Settings</h4>
                </div>
            </div>
        </div>
        <!-- End Page Title -->

        <!-- Settings Form -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title mb-4">Global Settings</h4>
                        <form action="{{ route('super_admin.settings.update') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="site_name" class="form-label">Site Name</label>
                                <input type="text" class="form-control" id="site_name" name="site_name" value="{{ $settings['site_name'] ?? '' }}" required>
                            </div>
                            <div class="mb-3">
                                <label for="theme" class="form-label">Theme</label>
                                <select class="form-select" id="theme" name="theme" required>
                                    <option value="light" {{ ($settings['theme'] ?? 'light') === 'light' ? 'selected' : '' }}>Light</option>
                                    <option value="dark" {{ ($settings['theme'] ?? 'light') === 'dark' ? 'selected' : '' }}>Dark</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary">Save Changes</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Settings Form -->
    </div>
</div>
@endsection