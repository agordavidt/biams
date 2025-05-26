@extends('layouts.governor')

@section('content')
<div class="page-content">
    <div class="container-fluid">
        <!-- Page Title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">System Summary</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('governor.dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active">System Summary</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Page Title -->

        <!-- System Health -->
        <div class="row">
            @foreach($systemHealth as $key => $value)
            <div class="col-xl-3 col-md-6">
                <div class="card card-h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <span class="text-muted mb-3 lh-1 d-block text-truncate">{{ str_replace('_', ' ', ucwords($key)) }}</span>
                                <h4 class="mb-3">
                                    <span class="counter-value" data-target="{{ $value }}">{{ $value }}</span>
                                </h4>
                            </div>
                            <div class="avatar-sm flex-shrink-0">
                                <span class="avatar-title bg-primary-subtle rounded fs-3">
                                    <i class="ri-information-fill text-primary"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        <!-- End System Health -->

        <!-- Geographic Coverage -->
        <div class="row">
            <div class="col-xl-6">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title mb-0">Geographic Coverage</h4>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-info border-0 rounded-3 mb-3" role="alert">
                            <div class="d-flex">
                                <div class="avatar-xs me-3 align-self-center">
                                    <span class="avatar-title rounded-circle bg-info text-white">
                                        <i class="ri-map-pin-line"></i>
                                    </span>
                                </div>
                                <div class="flex-grow-1 text-truncate">
                                    <h5 class="text-info">{{ $geographicCoverage['total_lgas_covered'] }} LGAs Covered</h5>
                                    <p class="mb-0">Total Local Government Areas with registered practitioners</p>
                                </div>
                            </div>
                        </div>
                        <div id="lga-coverage-chart" class="apex-charts" dir="ltr"></div>
                    </div>
                </div>
            </div>

            <!-- Practice Type Summary -->
            <div class="col-xl-6">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title mb-0">Practice Type Summary</h4>
                    </div>
                    <div class="card-body">
                        <div id="practice-type-chart" class="apex-charts" dir="ltr"></div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Geographic Coverage -->

        <!-- Detailed Summary -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex">
                            <div class="flex-grow-1">
                                <h4 class="card-title mb-0">Detailed System Summary</h4>
                            </div>
                            <div class="flex-shrink-0">
                                <button onclick="exportReport()" class="btn btn-primary btn-sm">
                                    <i class="ri-download-cloud-line me-1"></i> Export Summary
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-borderless table-centered align-middle table-nowrap mb-0">
                                <thead class="text-muted table-light">
                                    <tr>
                                        <th scope="col">Category</th>
                                        <th scope="col">Detail</th>
                                        <th scope="col">Count</th>
                                        <th scope="col">Top Metrics</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($practiceTypeSummary as $practice => $data)
                                    <tr>
                                        <td>{{ str_replace('_', ' ', ucwords($practice)) }}</td>
                                        <td>
                                            Total: {{ $data['total'] }}<br>
                                            Active: {{ $data['active'] }}
                                        </td>
                                        <td>{{ number_format($data['active']) }}</td>
                                        <td>
                                            @if(isset($data['top_crops']))
                                                @foreach($data['top_crops'] as $crop)
                                                    {{ $crop->crop }}: {{ $crop->count }}<br>
                                                @endforeach
                                            @elseif(isset($data['top_livestock']))
                                                @foreach($data['top_livestock'] as $livestock)
                                                    {{ $livestock->livestock }}: {{ $livestock->count }}<br>
                                                @endforeach
                                            @elseif(isset($data['facility_types']))
                                                @foreach($data['facility_types'] as $facility)
                                                    {{ $facility->facility_type }}: {{ $facility->count }}<br>
                                                @endforeach
                                            @else
                                                N/A
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                    @foreach($demographicsSummary as $category => $data)
                                    <tr>
                                        <td>{{ str_replace('_', ' ', ucwords($category)) }}</td>
                                        <td colspan="2">
                                            @foreach($data as $item)
                                                {{ $item->hasAttribute('gender') ? $item->gender : ($item->hasAttribute('age_category') ? $item->age_category : $item->income_level) }}: {{ $item->count }}<br>
                                            @endforeach
                                        </td>
                                        <td>N/A</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // LGA Coverage Chart
    var lgaCoverageOptions = {
        chart: { 
            type: 'bar', 
            height: 350,
            toolbar: { show: true }
        },
        series: [{ 
            name: 'Practitioners', 
            data: @json($geographicCoverage['lga_distribution']->pluck('count')) 
        }],
        xaxis: { 
            categories: @json($geographicCoverage['lga_distribution']->pluck('lga')),
            labels: {
                style: {
                    colors: '#8c9097',
                    fontSize: '11px'
                }
            }
        },
        yaxis: {
            labels: {
                style: {
                    colors: '#8c9097',
                    fontSize: '11px'
                }
            }
        },
        colors: ['#0ab39c'],
        plotOptions: {
            bar: {
                borderRadius: 4,
                horizontal: false,
            }
        },
        grid: {
            borderColor: '#f1f1f1'
        },
        dataLabels: {
            enabled: false
        }
    };
    var lgaCoverageChart = new ApexCharts(document.querySelector("#lga-coverage-chart"), lgaCoverageOptions);
    lgaCoverageChart.render();

    // Practice Type Chart
    var practiceTypeOptions = {
        chart: { 
            type: 'pie', 
            height: 350 
        },
        series: [
            @foreach($practiceTypeSummary as $practice => $data)
                {{ $data['active'] }},
            @endforeach
        ],
        labels: [
            @foreach($practiceTypeSummary as $practice => $data)
                "{{ str_replace('_', ' ', ucwords($practice)) }}",
            @endforeach
        ],
        colors: ['#405189', '#0ab39c', '#f06548', '#f7b84b'],
        legend: {
            position: 'bottom'
        },
        dataLabels: {
            enabled: true
        }
    };
    var practiceTypeChart = new ApexCharts(document.querySelector("#practice-type-chart"), practiceTypeOptions);
    practiceTypeChart.render();

    // Counter Animation
    document.addEventListener('DOMContentLoaded', function() {
        const counters = document.querySelectorAll('.counter-value');
        counters.forEach(counter => {
            const target = parseInt(counter.getAttribute('data-target'));
            const count = +counter.innerText;
            const increment = target / 200;
            
            if (count < target) {
                counter.innerText = Math.ceil(count + increment);
                setTimeout(() => {
                    counter.click();
                }, 1);
            } else {
                counter.innerText = target;
            }
        });
    });
</script>
@endsection