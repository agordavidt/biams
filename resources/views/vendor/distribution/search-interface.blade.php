@extends('layouts.vendor')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <div>
                    <h4 class="page-title mb-1">Farmer Search & Distribution</h4>
                    <p class="text-muted mb-0">Search for farmers to fulfill resource applications</p>
                </div>
                <div>
                    <a href="{{ route('vendor.distribution.resources') }}" class="btn btn-info">
                        View Resources
                    </a>
                    <a href="{{ route('vendor.distribution.dashboard') }}" class="btn btn-light">
                        <i class="ri-dashboard-line me-1"></i> Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-10 mx-auto">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0">
                        <i class="ri-search-line me-2"></i>Search Farmer Applications
                    </h5>
                    <p class="mb-0 mt-1 opacity-75"><small>Search by name, phone, email, or NIN</small></p>
                </div>
                <div class="card-body" x-data="farmerSearch()">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Search Query</label>
                            <input type="text" class="form-control form-control-lg" x-model="searchQuery"
                                   placeholder="Enter name, phone, email, or NIN"
                                   @keyup.enter="searchFarmer()"
                                   @input="searched = false">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Filter by Resource (Optional)</label>
                            <select class="form-select form-select-lg" x-model="resourceId">
                                <option value="">All Resources</option>
                                @foreach($resources as $res)
                                    <option value="{{ $res->id }}">{{ $res->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <button class="btn btn-primary btn-lg w-100" @click="searchFarmer()"
                                    :disabled="searching || searchQuery.length < 3">
                                <span x-show="!searching">
                                     Search
                                </span>
                                <span x-show="searching">
                                    <span class="spinner-border spinner-border-sm me-1"></span> Searching...
                                </span>
                            </button>
                        </div>
                    </div>

                    <!-- Search Results -->
                    <div x-show="searched && results.length === 0" class="alert alert-warning">
                        <i class="ri-information-line me-2"></i>
                        <strong>No applications found</strong>
                        <p class="mb-0">No paid applications found matching your search criteria.</p>
                    </div>

                    <div x-show="results.length > 0" class="mt-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="mb-0">Search Results (<span x-text="results.length"></span> found)</h6>
                            <button type="button" class="btn btn-sm btn-outline-primary" @click="resetSearch()">
                                <i class="ri-close-line me-1"></i> Clear Results
                            </button>
                        </div>
                        
                        <template x-for="app in results" :key="app.id">
                            <div class="card border mb-3">
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col-md-4">
                                            <h5 class="mb-2" x-text="app.farmer_name"></h5>
                                            <p class="mb-1"><i class="ri-phone-line text-primary me-1"></i> <span x-text="app.phone"></span></p>
                                            <p class="mb-0 text-muted"><small x-text="app.email"></small></p>
                                        </div>
                                        <div class="col-md-4">
                                            <p class="mb-1 text-muted"><small>Resource</small></p>
                                            <p class="mb-2"><strong x-text="app.resource_name"></strong></p>
                                            <p class="mb-0">
                                                <span class="badge bg-success">
                                                    <span x-text="app.quantity_approved"></span> approved
                                                </span>
                                            </p>
                                        </div>
                                        <div class="col-md-4 text-end">
                                            <template x-if="!app.is_fulfilled">
                                                <a :href="`/vendor/distribution/fulfill/${app.id}`" class="btn btn-success">
                                                    <i class="ri-check-line me-1"></i> Fulfill
                                                </a>
                                            </template>
                                            <template x-if="app.is_fulfilled">
                                                <span class="badge bg-info fs-6">
                                                    <i class="ri-checkbox-circle-line me-1"></i> Fulfilled
                                                </span>
                                            </template>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>

                    <div x-show="error" class="alert alert-danger mt-3">
                        <i class="ri-error-warning-line me-2"></i>
                        <span x-text="error"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
<script>
function farmerSearch() {
    return {
        searchQuery: '',
        resourceId: '',
        results: [],
        searching: false,
        searched: false,
        error: '',

        async searchFarmer() {
            if (this.searchQuery.length < 3) {
                this.error = 'Please enter at least 3 characters';
                return;
            }

            this.searching = true;
            this.error = '';
            this.searched = false;

            try {
                const response = await fetch('{{ route("vendor.distribution.search-farmer") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ 
                        search: this.searchQuery,
                        resource_id: this.resourceId || null
                    })
                });

                const data = await response.json();

                if (data.success) {
                    this.results = data.applications;
                    this.searched = true;
                } else {
                    this.error = data.error || 'Search failed';
                }
            } catch (error) {
                this.error = 'An error occurred during search';
                console.error(error);
            } finally {
                this.searching = false;
            }
        },

        resetSearch() {
            this.searchQuery = '';
            this.resourceId = '';
            this.results = [];
            this.searched = false;
            this.error = '';
        }
    };
}
</script>
@endpush
@endsection


