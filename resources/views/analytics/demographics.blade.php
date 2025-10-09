@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-md-12">
            <h2 class="mb-0">Farmer Demographics Analytics</h2>
            <p class="text-muted">Demographic breakdown and distribution</p>
        </div>
    </div>

    @if(!empty($data))
        @foreach($data as $snapshot)
        <div class="row mb-4">
            {{-- Gender Distribution --}}
            <div class="col-lg-6 mb-4">
                <div class="card shadow">
                    <div class="card-header">
                        <h5 class="mb-0">Gender Distribution</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="genderChart" height="200"></canvas>
                        <div class="mt-3">
                            <div class="d-flex justify-content-between mb-2">
                                <span>Male:</span>
                                <strong>{{ number_format($snapshot->male_count) }}</strong>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Female:</span>
                                <strong>{{ number_format($snapshot->female_count) }}</strong>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span>Other:</span>
                                <strong>{{ number_format($snapshot->other_gender_count) }}</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Age Distribution --}}
            <div class="col-lg-6 mb-4">
                <div class="card shadow">
                    <div class="card-header">
                        <h5 class="mb-0">Age Distribution</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="ageChart" height="200"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-4">
            {{-- Education Level --}}
            <div class="col-lg-6 mb-4">
                <div class="card shadow">
                    <div class="card-header">
                        <h5 class="mb-0">Education Level</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="educationChart" height="200"></canvas>
                    </div>
                </div>
            </div>

            {{-- Marital Status --}}
            <div class="col-lg-6 mb-4">
                <div class="card shadow">
                    <div class="card-header">
                        <h5 class="mb-0">Marital Status</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="maritalChart" height="200"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            {{-- Occupation --}}
            <div class="col-lg-6 mb-4">
                <div class="card shadow">
                    <div class="card-header">
                        <h5 class="mb-0">Primary Occupation</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-2">
                                <span>Full-time Farmers:</span>
                                <strong>{{ number_format($snapshot->occupation_full_time) }}</strong>
                            </div>
                            <div class="progress mb-3" style="height: 25px;">
                                <div class="progress-bar bg-success" style="width: {{ ($snapshot->total_farmers > 0) ? ($snapshot->occupation_full_time / $snapshot->total_farmers * 100) : 0 }}%">
                                    {{ ($snapshot->total_farmers > 0) ? round($snapshot->occupation_full_time / $snapshot->total_farmers * 100, 1) : 0 }}%
                                </div>
                            </div>

                            <div class="d-flex justify-content-between mb-2">
                                <span>Part-time Farmers:</span>
                                <strong>{{ number_format($snapshot->occupation_part_time) }}</strong>
                            </div>
                            <div class="progress mb-3" style="height: 25px;">
                                <div class="progress-bar bg-warning" style="width: {{ ($snapshot->total_farmers > 0) ? ($snapshot->occupation_part_time / $snapshot->total_farmers * 100) : 0 }}%">
                                    {{ ($snapshot->total_farmers > 0) ? round($snapshot->occupation_part_time / $snapshot->total_farmers * 100, 1) : 0 }}%
                                </div>
                            </div>

                            <div class="d-flex justify-content-between mb-2">
                                <span>Other Occupations:</span>
                                <strong>{{ number_format($snapshot->occupation_other) }}</strong>
                            </div>
                            <div class="progress" style="height: 25px;">
                                <div class="progress-bar bg-info" style="width: {{ ($snapshot->total_farmers > 0) ? ($snapshot->occupation_other / $snapshot->total_farmers * 100) : 0 }}%">
                                    {{ ($snapshot->total_farmers > 0) ? round($snapshot->occupation_other / $snapshot->total_farmers * 100, 1) : 0 }}%
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Summary Stats --}}
            <div class="col-lg-6 mb-4">
                <div class="card shadow">
                    <div class="card-header">
                        <h5 class="mb-0">Summary Statistics</h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-borderless">
                            <tr>
                                <td><strong>Total Farmers:</strong></td>
                                <td class="text-end">{{ number_format($snapshot->total_farmers) }}</td>
                            </tr>
                            <tr>
                                <td><strong>Average Household Size:</strong></td>
                                <td class="text-end">{{ number_format($snapshot->avg_household_size, 2) }}</td>
                            </tr>
                            <tr>
                                <td><strong>Youth (18-35):</strong></td>
                                <td class="text-end">{{ number_format($snapshot->age_18_25 + $snapshot->age_26_35) }}</td>
                            </tr>
                            <tr>
                                <td><strong>Working Age (36-55):</strong></td>
                                <td class="text-end">{{ number_format($snapshot->age_36_45 + $snapshot->age_46_55) }}</td>
                            </tr>
                            <tr>
                                <td><strong>Senior (56+):</strong></td>
                                <td class="text-end">{{ number_format($snapshot->age_56_plus) }}</td>
                            </tr>
                            <tr>
                                <td><strong>Educated (Tertiary/Vocational):</strong></td>
                                <td class="text-end">{{ number_format($snapshot->edu_tertiary + $snapshot->edu_vocational) }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
        <script>
            // Gender Chart
            new Chart(document.getElementById('genderChart'), {
                type: 'doughnut',
                data: {
                    labels: ['Male', 'Female', 'Other'],
                    datasets: [{
                        data: [{{ $snapshot->male_count }}, {{ $snapshot->female_count }}, {{ $snapshot->other_gender_count }}],
                        backgroundColor: ['#4e73df', '#1cc88a', '#36b9cc']
                    }]
                },
                options: { maintainAspectRatio: false }
            });

            // Age Chart
            new Chart(document.getElementById('ageChart'), {
                type: 'bar',
                data: {
                    labels: ['18-25', '26-35', '36-45', '46-55', '56+'],
                    datasets: [{
                        label: 'Farmers',
                        data: [{{ $snapshot->age_18_25 }}, {{ $snapshot->age_26_35 }}, {{ $snapshot->age_36_45 }}, {{ $snapshot->age_46_55 }}, {{ $snapshot->age_56_plus }}],
                        backgroundColor: '#4e73df'
                    }]
                },
                options: { maintainAspectRatio: false, scales: { y: { beginAtZero: true } } }
            });

            // Education Chart
            new Chart(document.getElementById('educationChart'), {
                type: 'bar',
                data: {
                    labels: ['None', 'Primary', 'Secondary', 'Tertiary', 'Vocational'],
                    datasets: [{
                        label: 'Farmers',
                        data: [{{ $snapshot->edu_none }}, {{ $snapshot->edu_primary }}, {{ $snapshot->edu_secondary }}, {{ $snapshot->edu_tertiary }}, {{ $snapshot->edu_vocational }}],
                        backgroundColor: '#1cc88a'
                    }]
                },
                options: { maintainAspectRatio: false, scales: { y: { beginAtZero: true } } }
            });

            // Marital Chart
            new Chart(document.getElementById('maritalChart'), {
                type: 'pie',
                data: {
                    labels: ['Single', 'Married', 'Divorced', 'Widowed'],
                    datasets: [{
                        data: [{{ $snapshot->marital_single }}, {{ $snapshot->marital_married }}, {{ $snapshot->marital_divorced }}, {{ $snapshot->marital_widowed }}],
                        backgroundColor: ['#f6c23e', '#4e73df', '#36b9cc', '#e74a3b']
                    }]
                },
                options: { maintainAspectRatio: false }
            });
        </script>
        @endpush
        @endforeach
    @else
        <div class="alert alert-info">
            No demographic data available. Please run <code>php artisan analytics:generate</code>
        </div>
    @endif
</div>
@endsection
