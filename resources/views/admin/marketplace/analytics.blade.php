@extends('layouts.admin')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0">Marketplace Analytics</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.marketplace.dashboard') }}">Marketplace</a></li>
                    <li class="breadcrumb-item active">Analytics</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<!-- Date Range Filter -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Analytics Overview</h5>
                    <form method="GET" action="{{ route('admin.marketplace.analytics') }}" class="d-flex gap-2">
                        <select name="range" class="form-select form-select-sm" style="width: auto;" onchange="this.form.submit()">
                            <option value="7" {{ $dateRange == '7' ? 'selected' : '' }}>Last 7 Days</option>
                            <option value="30" {{ $dateRange == '30' ? 'selected' : '' }}>Last 30 Days</option>
                            <option value="90" {{ $dateRange == '90' ? 'selected' : '' }}>Last 90 Days</option>
                            <option value="365" {{ $dateRange == '365' ? 'selected' : '' }}>Last 12 Months</option>
                        </select>
                        <!-- <a href="{{ route('admin.marketplace.reports.export') }}" class="btn btn-primary btn-sm">
                            <i class="ri-download-line me-1"></i> Export Report
                        </a> -->
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Key Metrics -->
<div class="row">
    <!-- Listings Metrics -->
    <div class="col-xl-3 col-md-6">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <h5 class="text-muted fw-normal mt-0" title="Total Listings">Listings</h5>
                        <h3 class="my-2">{{ $analytics['listings']['total'] }}</h3>
                        <p class="mb-0 text-muted">
                            <span class="text-success me-2">{{ $analytics['listings']['approved'] }} approved</span>
                        </p>
                    </div>
                    <div class="flex-shrink-0">
                        <i class="ri-shopping-bag-line text-primary h2"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Inquiries Metrics -->
    <div class="col-xl-3 col-md-6">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <h5 class="text-muted fw-normal mt-0" title="Total Inquiries">Inquiries</h5>
                        <h3 class="my-2">{{ $analytics['inquiries']['total'] }}</h3>
                        <p class="mb-0 text-muted">
                            <span class="text-success me-2">{{ $analytics['inquiries']['conversion_rate'] }}% conversion</span>
                        </p>
                    </div>
                    <div class="flex-shrink-0">
                        <i class="ri-customer-service-2-line text-success h2"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Subscriptions Metrics -->
    <div class="col-xl-3 col-md-6">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <h5 class="text-muted fw-normal mt-0" title="New Subscriptions">Subscriptions</h5>
                        <h3 class="my-2">{{ $analytics['subscriptions']['new'] }}</h3>
                        <p class="mb-0 text-muted">
                            <span class="text-success me-2">₦{{ number_format($analytics['subscriptions']['revenue']) }}</span>
                        </p>
                    </div>
                    <div class="flex-shrink-0">
                        <i class="ri-vip-crown-line text-warning h2"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Top Farmers -->
    <div class="col-xl-3 col-md-6">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <h5 class="text-muted fw-normal mt-0" title="Active Farmers">Top Farmers</h5>
                        <h3 class="my-2">{{ $analytics['top_farmers']->count() }}</h3>
                        <p class="mb-0 text-muted">
                            <span class="text-success me-2">Leading performers</span>
                        </p>
                    </div>
                    <div class="flex-shrink-0">
                        <i class="ri-user-star-line text-info h2"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Charts and Detailed Analytics -->
<div class="row">
    <!-- Listings by Category -->
    <div class="col-xl-6">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-4">Listings by Category</h5>
                <div class="table-responsive">
                    <table class="table table-sm table-centered mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Category</th>
                                <th>Listings</th>
                                <th>Percentage</th>
                                <th style="width: 120px;">Distribution</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($analytics['listings']['by_category'] as $category)
                                @if($category->listings_count > 0)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <i class="ri-folder-line me-2 text-primary"></i>
                                            {{ $category->name }}
                                        </div>
                                    </td>
                                    <td>{{ $category->listings_count }}</td>
                                    <td>{{ number_format(($category->listings_count / max($analytics['listings']['total'], 1)) * 100, 1) }}%</td>
                                    <td>
                                        <div class="progress" style="height: 6px;">
                                            <div class="progress-bar" role="progressbar" 
                                                 style="width: {{ ($category->listings_count / max($analytics['listings']['total'], 1)) * 100 }}%" 
                                                 aria-valuenow="{{ ($category->listings_count / max($analytics['listings']['total'], 1)) * 100 }}" 
                                                 aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </td>
                                </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Listings by Status -->
    <div class="col-xl-6">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-4">Listings by Status</h5>
                <div id="listings-status-chart"></div>
            </div>
        </div>
    </div>
</div>

<!-- Top Performers Section -->
<div class="row">
    <!-- Top Farmers -->
    <div class="col-xl-6">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-4">Top Performing Farmers</h5>
                <div class="table-responsive">
                    <table class="table table-sm table-centered mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Farmer</th>
                                <th>Listings</th>
                                <th>Views</th>
                                <th>Inquiries</th>
                                <th>Performance</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($analytics['top_farmers'] as $farmer)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0">
                                            <div class="avatar-xs">
                                                <div class="avatar-title bg-soft-primary text-primary rounded-circle">
                                                    {{ substr($farmer->user->name ?? 'Unknown', 0, 1) }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1 ms-2">
                                            {{ $farmer->user->name ?? 'Unknown User' }}
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $farmer->listings_count }}</td>
                                <td>{{ $farmer->total_views }}</td>
                                <td>{{ $farmer->total_inquiries }}</td>
                                <td>
                                    @php
                                        $performance = $farmer->total_inquiries > 0 ? 'High' : ($farmer->total_views > 0 ? 'Medium' : 'Low');
                                        $badgeClass = $performance == 'High' ? 'bg-success' : ($performance == 'Medium' ? 'bg-warning' : 'bg-secondary');
                                    @endphp
                                    <span class="badge {{ $badgeClass }}">{{ $performance }}</span>
                                </td>
                            </tr>
                            @endforeach
                            @if($analytics['top_farmers']->isEmpty())
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">
                                    <i class="ri-user-search-line h2"></i>
                                    <p class="mt-2 mb-0">No farmer data available</p>
                                </td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Top Listings by Inquiries -->
    <div class="col-xl-6">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-4">Most Inquired Listings</h5>
                <div class="table-responsive">
                    <table class="table table-sm table-centered mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Listing</th>
                                <th>Category</th>
                                <th>Inquiries</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($analytics['inquiries']['by_listing'] as $inquiryData)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0 me-2">
                                            <i class="ri-shopping-bag-line text-primary"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <span class="d-block fw-medium text-truncate" style="max-width: 150px;" 
                                                  title="{{ $inquiryData->listing->title ?? 'Deleted Listing' }}">
                                                {{ $inquiryData->listing->title ?? 'Deleted Listing' }}
                                            </span>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    @if($inquiryData->listing)
                                        <span class="badge bg-soft-primary text-primary">
                                            {{ $inquiryData->listing->category->name ?? 'N/A' }}
                                        </span>
                                    @else
                                        <span class="badge bg-soft-secondary text-secondary">N/A</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="fw-medium">{{ $inquiryData->count }}</span>
                                </td>
                                <td>
                                    @if($inquiryData->listing)
                                        @php
                                            $statusBadge = [
                                                'active' => 'bg-success',
                                                'pending_review' => 'bg-warning',
                                                'expired' => 'bg-secondary',
                                                'rejected' => 'bg-danger'
                                            ][$inquiryData->listing->status] ?? 'bg-secondary';
                                        @endphp
                                        <span class="badge {{ $statusBadge }}">
                                            {{ ucfirst(str_replace('_', ' ', $inquiryData->listing->status)) }}
                                        </span>
                                    @else
                                        <span class="badge bg-soft-secondary text-secondary">Deleted</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                            @if($analytics['inquiries']['by_listing']->isEmpty())
                            <tr>
                                <td colspan="4" class="text-center text-muted py-4">
                                    <i class="ri-inbox-line h2"></i>
                                    <p class="mt-2 mb-0">No inquiry data available</p>
                                </td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Revenue Analytics -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="card-title mb-0">Revenue Overview</h5>
                    <div class="text-end">
                        <h4 class="text-success mb-0">₦{{ number_format($analytics['subscriptions']['revenue']) }}</h4>
                        <p class="text-muted mb-0">Total Revenue</p>
                    </div>
                </div>
                <div class="row text-center">
                    <div class="col-md-3">
                        <div class="border-end">
                            <h4 class="text-primary mb-1">{{ $analytics['subscriptions']['new'] }}</h4>
                            <p class="text-muted mb-0">New Subscriptions</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="border-end">
                            <h4 class="text-success mb-1">₦{{ number_format($analytics['subscriptions']['revenue'] / max($analytics['subscriptions']['new'], 1), 2) }}</h4>
                            <p class="text-muted mb-0">Average Value</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="border-end">
                            <h4 class="text-info mb-1">{{ $analytics['listings']['total'] > 0 ? number_format(($analytics['subscriptions']['new'] / $analytics['listings']['total']) * 100, 1) : 0 }}%</h4>
                            <p class="text-muted mb-0">Conversion Rate</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div>
                            <h4 class="text-warning mb-1">{{ $analytics['inquiries']['conversion_rate'] }}%</h4>
                            <p class="text-muted mb-0">Inquiry Conversion</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Listings Status Chart
    var statusData = {!! json_encode($analytics['listings']['by_status']) !!};
    var statusChart = new ApexCharts(document.querySelector("#listings-status-chart"), {
        series: statusData.map(item => item.count),
        chart: {
            type: 'donut',
            height: 320
        },
        labels: statusData.map(item => {
            return item.status.charAt(0).toUpperCase() + item.status.slice(1).replace('_', ' ');
        }),
        colors: ['#10b981', '#f59e0b', '#6b7280', '#ef4444'],
        legend: {
            position: 'bottom'
        },
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
        }]
    });

    statusChart.render();
});
</script>
@endpush

@push('styles')
<style>
.progress {
    background-color: #e9ecef;
}
.progress-bar {
    background-color: #38761d;
}
.avatar-title {
    font-size: 0.75rem;
    font-weight: 600;
}
</style>
@endpush