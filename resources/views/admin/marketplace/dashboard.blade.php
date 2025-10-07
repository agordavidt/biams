@extends('layouts.admin')

@section('content')
<!-- start page title -->
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
<!-- end page title -->

<!-- Statistics Cards -->
<div class="row">
    <div class="col-xl-3 col-md-6">
        <div class="card">
            <div class="card-body">
                <div class="d-flex">
                    <div class="flex-grow-1">
                        <p class="text-truncate font-size-14 mb-2">Total Listings</p>
                        <h4 class="mb-2">{{ number_format($stats['total_listings']) }}</h4>
                        <p class="text-muted mb-0">
                            <span class="text-success fw-bold font-size-12 me-2">
                                <i class="ri-arrow-right-up-line me-1 align-middle"></i>{{ $stats['active_listings'] }}
                            </span>
                            <span class="text-muted">Active</span>
                        </p>
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
                        <p class="text-truncate font-size-14 mb-2">Pending Review</p>
                        <h4 class="mb-2">{{ number_format($stats['pending_review']) }}</h4>
                        <p class="text-muted mb-0">
                            <span class="text-warning fw-bold font-size-12 me-2">
                                <i class="ri-time-line me-1 align-middle"></i>Awaiting
                            </span>
                            <span class="text-muted">Approval</span>
                        </p>
                    </div>
                    <div class="avatar-sm">
                        <span class="avatar-title bg-light text-warning rounded-3">
                            <i class="ri-file-list-line font-size-24"></i>
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
                        <p class="text-truncate font-size-14 mb-2">Active Subscriptions</p>
                        <h4 class="mb-2">{{ number_format($stats['active_subscriptions']) }}</h4>
                        <p class="text-muted mb-0">
                            <span class="text-danger fw-bold font-size-12 me-2">
                                <i class="ri-arrow-right-down-line me-1 align-middle"></i>{{ $stats['expiring_soon'] }}
                            </span>
                            <span class="text-muted">Expiring Soon</span>
                        </p>
                    </div>
                    <div class="avatar-sm">
                        <span class="avatar-title bg-light text-success rounded-3">
                            <i class="ri-user-star-line font-size-24"></i>
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
                        <p class="text-truncate font-size-14 mb-2">Total Revenue</p>
                        <h4 class="mb-2">₦{{ number_format($stats['revenue_total'], 2) }}</h4>
                        <p class="text-muted mb-0">
                            <span class="text-success fw-bold font-size-12 me-2">
                                ₦{{ number_format($stats['revenue_this_month'], 2) }}
                            </span>
                            <span class="text-muted">This Month</span>
                        </p>
                    </div>
                    <div class="avatar-sm">
                        <span class="avatar-title bg-light text-info rounded-3">
                            <i class="ri-money-dollar-circle-line font-size-24"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-xl-8">
        <!-- Monthly Trends Chart -->
        <div class="card">
            <div class="card-body">
                <div class="d-sm-flex flex-wrap">
                    <h4 class="card-title mb-4">Marketplace Trends</h4>
                </div>
                <div id="marketplace-trends-chart" class="apex-charts" style="min-height: 350px;"></div>
            </div>
        </div>
    </div>

    <div class="col-xl-4">
        <!-- Category Distribution -->
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">Listings by Category</h4>
                <div id="category-chart" class="apex-charts" style="min-height: 350px;"></div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-xl-6">
        <!-- Recent Listings -->
        <div class="card">
            <div class="card-body">
                <div class="d-sm-flex flex-wrap">
                    <h4 class="card-title mb-4">Recent Listings</h4>
                    <div class="ms-auto">
                        <a href="{{ route('admin.marketplace.listings') }}" class="btn btn-primary btn-sm">View All</a>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover table-centered table-nowrap mb-0">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Farmer</th>
                                <th>Price</th>
                                <th>Status</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentListings as $listing)
                            <tr>
                                <td>
                                    <h5 class="font-size-14 mb-1">{{ Str::limit($listing->title, 30) }}</h5>
                                    <p class="text-muted mb-0">{{ $listing->category->name }}</p>
                                </td>
                                <td>{{ $listing->user->name }}</td>
                                <td>₦{{ number_format($listing->price, 2) }}</td>
                                <td>
                                    @if($listing->status === 'active')
                                        <span class="badge bg-success">Active</span>
                                    @elseif($listing->status === 'pending_review')
                                        <span class="badge bg-warning">Pending</span>
                                    @elseif($listing->status === 'rejected')
                                        <span class="badge bg-danger">Rejected</span>
                                    @else
                                        <span class="badge bg-secondary">{{ ucfirst($listing->status) }}</span>
                                    @endif
                                </td>
                                <td>{{ $listing->created_at->format('d M, Y') }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-4">No recent listings</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-6">
        <!-- Recent Subscriptions -->
        <div class="card">
            <div class="card-body">
                <div class="d-sm-flex flex-wrap">
                    <h4 class="card-title mb-4">Recent Subscriptions</h4>
                    <div class="ms-auto">
                        <a href="{{ route('admin.marketplace.subscriptions') }}" class="btn btn-primary btn-sm">View All</a>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover table-centered table-nowrap mb-0">
                        <thead>
                            <tr>
                                <th>Farmer</th>
                                <th>Amount</th>
                                <th>Valid Until</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentSubscriptions as $subscription)
                            <tr>
                                <td>
                                    <h5 class="font-size-14 mb-1">{{ $subscription->user->name }}</h5>
                                    <p class="text-muted mb-0">{{ $subscription->user->email }}</p>
                                </td>
                                <td>₦{{ number_format($subscription->amount, 2) }}</td>
                                <td>{{ $subscription->end_date->format('d M, Y') }}</td>
                                <td>
                                    @if($subscription->is_active)
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-secondary">Expired</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center py-4">No recent subscriptions</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Top Locations -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">Top Locations by Listings</h4>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Location (LGA)</th>
                                <th>Active Listings</th>
                                <th>Performance</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($lgaStats as $index => $lga)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td><strong>{{ $lga->location }}</strong></td>
                                <td>{{ $lga->count }}</td>
                                <td>
                                    <div class="progress" style="height: 20px;">
                                        <div class="progress-bar bg-success" role="progressbar" 
                                            style="width: {{ ($lga->count / $lgaStats->max('count')) * 100 }}%">
                                            {{ $lga->count }}
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center py-4">No location data available</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Monthly Trends Chart
var trendsOptions = {
    series: [{
        name: 'Listings',
        data: @json($monthlyTrends->pluck('listings'))
    }, {
        name: 'Subscriptions',
        data: @json($monthlyTrends->pluck('subscriptions'))
    }, {
        name: 'Revenue (₦1000s)',
        data: @json($monthlyTrends->pluck('revenue')->map(fn($val) => $val / 1000))
    }],
    chart: {
        height: 350,
        type: 'line',
        toolbar: { show: false }
    },
    dataLabels: { enabled: false },
    stroke: { curve: 'smooth', width: 2 },
    xaxis: {
        categories: @json($monthlyTrends->pluck('month'))
    },
    colors: ['#556ee6', '#34c38f', '#f46a6a'],
    legend: { position: 'top' }
};
var trendsChart = new ApexCharts(document.querySelector("#marketplace-trends-chart"), trendsOptions);
trendsChart.render();

// Category Distribution Chart
var categoryOptions = {
    series: @json($categoryStats->pluck('listings_count')),
    chart: {
        type: 'donut',
        height: 350
    },
    labels: @json($categoryStats->pluck('name')),
    colors: ['#556ee6', '#34c38f', '#f46a6a', '#50a5f1', '#f1b44c', '#343a40'],
    legend: { position: 'bottom' },
    responsive: [{
        breakpoint: 480,
        options: {
            chart: { width: 200 },
            legend: { position: 'bottom' }
        }
    }]
};
var categoryChart = new ApexCharts(document.querySelector("#category-chart"), categoryOptions);
categoryChart.render();
</script>
@endpush
@endsection