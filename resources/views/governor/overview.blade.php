@extends('layouts.governor')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">System Overview</h4>
            <div class="page-title-right">
                <a href="{{ route('governor.overview.export') }}" class="btn btn-primary">Download Report</a>
            </div>
        </div>
    </div>
</div>

<!-- Summary Statistics -->
<div class="row">
    <div class="col-xl-3 col-md-6">
        <div class="card">
            <div class="card-body">
                <p class="text-muted mb-2">Total Farmers</p>
                <h3 class="mb-0">{{ number_format($summary['total_farmers']) }}</h3>
                <p class="text-muted mb-0 mt-2 small">
                    Female: {{ number_format($summary['female_farmers']) }} | 
                    Male: {{ number_format($summary['male_farmers']) }}
                </p>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card">
            <div class="card-body">
                <p class="text-muted mb-2">Youth Farmers (18-35)</p>
                <h3 class="mb-0">{{ number_format($summary['youth_farmers']) }}</h3>
                <p class="text-muted mb-0 mt-2 small">
                    {{ $summary['total_farmers'] > 0 ? number_format(($summary['youth_farmers'] / $summary['total_farmers']) * 100, 1) : 0 }}% of total
                </p>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card">
            <div class="card-body">
                <p class="text-muted mb-2">Total Farms</p>
                <h3 class="mb-0">{{ number_format($summary['total_farms']) }}</h3>
                <p class="text-muted mb-0 mt-2 small">
                    {{ number_format($summary['total_hectares'], 2) }} hectares
                </p>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6">
        <div class="card">
            <div class="card-body">
                <p class="text-muted mb-2">Cooperatives</p>
                <h3 class="mb-0">{{ number_format($summary['total_cooperatives']) }}</h3>
                <p class="text-muted mb-0 mt-2 small">
                    {{ number_format($summary['coop_members']) }} members
                </p>
            </div>
        </div>
    </div>
</div>

<!-- Farm Types -->
<div class="row">
    <div class="col-xl-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Farm Types Distribution</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm mb-0">
                        <thead>
                            <tr>
                                <th>Type</th>
                                <th class="text-end">Count</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Crops</td>
                                <td class="text-end">{{ number_format($summary['crop_farms']) }}</td>
                            </tr>
                            <tr>
                                <td>Livestock</td>
                                <td class="text-end">{{ number_format($summary['livestock_farms']) }}</td>
                            </tr>
                            <tr>
                                <td>Fisheries</td>
                                <td class="text-end">{{ number_format($summary['fishery_farms']) }}</td>
                            </tr>
                            <tr>
                                <td>Orchards</td>
                                <td class="text-end">{{ number_format($summary['orchard_farms']) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Resources & Vendors</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm mb-0">
                        <thead>
                            <tr>
                                <th>Metric</th>
                                <th class="text-end">Value</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Active Resources</td>
                                <td class="text-end">{{ number_format($summary['active_resources']) }}</td>
                            </tr>
                            <tr>
                                <td>Total Applications</td>
                                <td class="text-end">{{ number_format($summary['total_applications']) }}</td>
                            </tr>
                            <tr>
                                <td>Approved Applications</td>
                                <td class="text-end">{{ number_format($summary['approved_applications']) }}</td>
                            </tr>
                            <tr>
                                <td>Active Vendors</td>
                                <td class="text-end">{{ number_format($summary['active_vendors']) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- LGA Summary -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Summary by LGA</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover" id="lgaTable">
                        <thead>
                            <tr>
                                <th>LGA</th>
                                <th class="text-end">Farmers</th>
                                <th class="text-end">Farms</th>
                                <th class="text-end">Hectares</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($lga_summary as $lga)
                            <tr>
                                <td>{{ $lga->name }}</td>
                                <td class="text-end">{{ number_format($lga->farmers) }}</td>
                                <td class="text-end">{{ number_format($lga->farms) }}</td>
                                <td class="text-end">{{ number_format($lga->hectares, 2) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Trends -->
<div class="row">
    <div class="col-xl-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Farmer Enrollment (Last 6 Months)</h5>
            </div>
            <div class="card-body">
                <div id="enrollmentChart"></div>
            </div>
        </div>
    </div>

    <div class="col-xl-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Resource Applications (Last 6 Months)</h5>
            </div>
            <div class="card-body">
                <div id="applicationsChart"></div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $('#lgaTable').DataTable({
        order: [[1, 'desc']],
        pageLength: 25
    });

    // Enrollment Chart
    var enrollmentOptions = {
        chart: { type: 'line', height: 300 },
        series: [{
            name: 'New Farmers',
            data: @json($recent_trends['enrollments']->pluck('count'))
        }],
        xaxis: {
            categories: @json($recent_trends['enrollments']->pluck('month'))
        }
    };
    var enrollmentChart = new ApexCharts(document.querySelector("#enrollmentChart"), enrollmentOptions);
    enrollmentChart.render();

    // Applications Chart
    var applicationsOptions = {
        chart: { type: 'bar', height: 300 },
        series: [{
            name: 'Total',
            data: @json($recent_trends['applications']->pluck('count'))
        }, {
            name: 'Approved',
            data: @json($recent_trends['applications']->pluck('granted'))
        }],
        xaxis: {
            categories: @json($recent_trends['applications']->pluck('month'))
        }
    };
    var applicationsChart = new ApexCharts(document.querySelector("#applicationsChart"), applicationsOptions);
    applicationsChart.render();
});
</script>
@endpush