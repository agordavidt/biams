@extends('layouts.admin')
@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Marketplace Dashboard</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Marketplace</li>
                </ol>
            </div>
        </div>
    </div>
</div>
<!-- Stats Cards -->
<div class="row">
    <div class="col-xl-3 col-md-6">
        <div class="card">
            <div class="card-body">
                <div class="d-flex">
                    <div class="flex-grow-1">
                        <p class="text-truncate font-size-14 mb-2">Total Listings</p>
                        <h4 class="mb-2">{{ $totalListings }}</h4>
                    </div>
                    <div class="avatar-sm">
                        <span class="avatar-title bg-light text-primary rounded-3">
                            <i class="ri-shopping-bag-line font-size-24"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card">
            <div class="card-body">
                <div class="d-flex">
                    <div class="flex-grow-1">
                        <p class="text-truncate font-size-14 mb-2">Active Listings</p>
                        <h4 class="mb-2">{{ $activeListings }}</h4>
                    </div>
                    <div class="avatar-sm">
                        <span class="avatar-title bg-light text-success rounded-3">
                            <i class="ri-check-line font-size-24"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card">
            <div class="card-body">
                <div class="d-flex">
                    <div class="flex-grow-1">
                        <p class="text-truncate font-size-14 mb-2">Sold Listings</p>
                        <h4 class="mb-2">{{ $soldListings }}</h4>
                    </div>
                    <div class="avatar-sm">
                        <span class="avatar-title bg-light text-warning rounded-3">
                            <i class="ri-money-dollar-circle-line font-size-24"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card">
            <div class="card-body">
                <div class="d-flex">
                    <div class="flex-grow-1">
                        <p class="text-truncate font-size-14 mb-2">Expired Listings</p>
                        <h4 class="mb-2">{{ $expiredListings }}</h4>
                    </div>
                    <div class="avatar-sm">
                        <span class="avatar-title bg-light text-danger rounded-3">
                            <i class="ri-time-line font-size-24"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Category Distribution -->
<div class="row">
    <div class="col-xl-6">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">Category Distribution</h4>
                <a href="{{ route('admin.marketplace.categories') }}">Add Category</a>
                <div id="category-chart" class="apex-charts" dir="ltr"></div>
            </div>
        </div>
    </div>
    <div class="col-xl-6">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">Categories</h4>
                <div class="table-responsive">
                    <table class="table table-hover table-centered table-nowrap mb-0">
                        <thead>
                            <tr>
                                <th scope="col">Category</th>
                                <th scope="col">Total Listings</th>
                                <th scope="col">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($categoryCounts as $category)
                            <tr>
                                <td>{{ $category->name }}</td>
                                <td>{{ $category->listings_count }}</td>
                                <td>
                                    <a href="{{ route('admin.marketplace.listings', ['category_id' => $category->id]) }}" class="btn btn-sm btn-primary">View Listings</a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    </div>
    <!-- Recent Listings -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 class="card-title">Recent Listings</h4>
                        <a href="{{ route('admin.marketplace.listings') }}" class="btn btn-primary">View All Listings</a>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover table-centered table-nowrap mb-0">
                            <thead>
                                <tr>
                                    <th scope="col">ID</th>
                                    <th scope="col">Title</th>
                                    <th scope="col">Price</th>
                                    <th scope="col">Category</th>
                                    <th scope="col">Seller</th>
                                    <th scope="col">Availability</th>
                                    <th scope="col">Created</th>
                                    <th scope="col">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentListings as $listing)
                                <tr>
                                    <td>{{ $listing->id }}</td>
                                    <td>
                                        <div class="text-truncate" style="max-width: 150px;">{{ $listing->title }}</div>
                                    </td>
                                    <td>{{ number_format($listing->price, 2) }}</td>
                                    <td>{{ $listing->category->name }}</td>
                                    <td>{{ $listing->user->name }}</td>
                                    <td>
                                        @if($listing->availability == 'available')
                                            <span class="badge bg-success">Available</span>
                                        @elseif($listing->availability == 'sold')
                                            <span class="badge bg-warning">Sold</span>
                                        @elseif($listing->availability == 'pending')
                                            <span class="badge bg-info">Pending</span>
                                        @else
                                            <span class="badge bg-danger">Inactive</span>
                                        @endif
                                    </td>
                                    <td>{{ $listing->created_at->format('d M Y') }}</td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <a href="{{ route('marketplace.show', $listing) }}" class="btn btn-sm btn-info" target="_blank">
                                                <i class="ri-eye-line"></i>
                                            </a>
                                            <form action="{{ route('admin.marketplace.listings.remove', $listing) }}" method="POST" class="delete-form">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger">
                                                    <i class="ri-delete-bin-line"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Category chart data
        const categoryLabels = {!! json_encode($categoryCounts->pluck('name')) !!};
        const categoryData = {!! json_encode($categoryCounts->pluck('listings_count')) !!};
        
        // Category chart
        const categoryChart = new ApexCharts(document.querySelector("#category-chart"), {
            series: categoryData,
            chart: {
                type: 'pie',
                height: 350
            },
            labels: categoryLabels,
            responsive: [{
                breakpoint: 480,
                options: {
                    chart: {
                        width: 200
                    },
                    legend: {
                        position: 'bottom'
                    }
                }
            }],
            colors: [
                '#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b', 
                '#5a5c69', '#6f42c1', '#fd7e14', '#20c997', '#17a2b8'
            ]
        });
        
        categoryChart.render();
        
        // Delete confirmation
        document.querySelectorAll('.delete-form').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                Swal.fire({
                    title: 'Are you sure?',
                    text: "This listing will be permanently removed!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        this.submit();
                    }
                });
            });
        });
    });
</script>
@endpush