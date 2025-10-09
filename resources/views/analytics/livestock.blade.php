{{-- ============================================ --}}
{{-- FILE 4: resources/views/analytics/livestock.blade.php --}}
{{-- ============================================ --}}

@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-md-12">
            <h2 class="mb-0">Livestock Production Analytics</h2>
            <p class="text-muted">Animal types, herd sizes, and breeding practices</p>
        </div>
    </div>

    @if(!empty($data))
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="card shadow">
                    <div class="card-header">
                        <h5 class="mb-0">Livestock Overview</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Animal Type</th>
                                        <th>Farmers</th>
                                        <th>Farms</th>
                                        <th>Total Animals</th>
                                        <th>Avg Herd Size</th>
                                        <th>Open Grazing</th>
                                        <th>Ranching</th>
                                        <th>Intensive</th>
                                        <th>Semi-Intensive</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($data as $livestock)
                                    <tr>
                                        <td><strong>{{ ucwords(str_replace('_', ' ', $livestock->animal_type)) }}</strong></td>
                                        <td>{{ number_format($livestock->farmer_count) }}</td>
                                        <td>{{ number_format($livestock->farm_count) }}</td>
                                        <td>{{ number_format($livestock->total_herd_size) }}</td>
                                        <td>{{ number_format($livestock->avg_herd_size, 2) }}</td>
                                        <td>{{ number_format($livestock->practice_open_grazing) }}</td>
                                        <td>{{ number_format($livestock->practice_ranching) }}</td>
                                        <td>{{ number_format($livestock->practice_intensive) }}</td>
                                        <td>{{ number_format($livestock->practice_semi_intensive) }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-6 mb-4">
                <div class="card shadow">
                    <div class="card-header">
                        <h5 class="mb-0">Total Animals by Type</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="animalTypeChart" height="300"></canvas>
                    </div>
                </div>
            </div>

            <div class="col-lg-6 mb-4">
                <div class="card shadow">
                    <div class="card-header">
                        <h5 class="mb-0">Breeding Practices Distribution</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="breedingChart" height="300"></canvas>
                    </div>
                </div>
            </div>
        </div>

        @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js