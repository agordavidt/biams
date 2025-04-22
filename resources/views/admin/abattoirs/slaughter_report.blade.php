@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Slaughter Operations Report</h4>
                <div>
                    <a href="{{ route('admin.abattoirs.analytics') }}" class="btn btn-info">Back to Analytics</a>
                    <a href="{{ route('admin.abattoirs.analytics.livestock') }}" class="btn btn-info">Livestock Report</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form method="GET" action="{{ route('admin.abattoirs.analytics.slaughter') }}" class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">Abattoir</label>
                            <select name="abattoir_id" class="form-select">
                                <option value="">All Abattoirs</option>
                                @foreach($abattoirs as $abattoir)
                                    <option value="{{ $abattoir->id }}" {{ $abattoirId == $abattoir->id ? 'selected' : '' }}>{{ $abattoir->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Meat Grade</label>
                            <select name="meat_grade" class="form-select">
                                <option value="">All Grades</option>
                                @foreach($meatGrades as $grade)
                                    <option value="{{ $grade }}" {{ $meatGrade == $grade ? 'selected' : '' }}>{{ ucfirst($grade) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Start Date</label>
                            <input type="date" name="start_date" class="form-control" value="{{ $startDate ? $startDate->format('Y-m-d') : '' }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">End Date</label>
                            <input type="date" name="end_date" class="form-control" value="{{ $endDate ? $endDate->format('Y-m-d') : '' }}">
                        </div>
                        <div class="col-md-3 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary me-2">Apply Filters</button>
                            <a href="{{ route('admin.abattoirs.analytics.slaughter') }}" class="btn btn-secondary">Reset</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Slaughter Operations Table -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-4">Slaughter Operation Records</h4>
                    <div class="table-responsive">
                        <table class="table table-centered table-nowrap mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Slaughter Date</th>
                                    <th>Abattoir</th>
                                    <th>Livestock Tag</th>
                                    <th>Species</th>
                                    <th>Carcass Weight (kg)</th>
                                    <th>Meat Grade</th>
                                    <th>Performed By</th>
                                    <th>Supervised By</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($operations as $operation)
                                <tr>
                                    <td>{{ $operation->id }}</td>
                                    <td>{{ $operation->slaughter_date->format('Y-m-d') }}</td>
                                    <td>{{ $operation->abattoir->name }}</td>
                                    <td>{{ $operation->livestock->tag_number }}</td>
                                    <td>{{ ucfirst($operation->livestock->species) }}</td>
                                    <td>{{ number_format($operation->carcass_weight_kg, 2) }} kg</td>
                                    <td>
                                        <span class="badge bg-{{ 
                                            $operation->meat_grade == 'premium' ? 'success' : 
                                            ($operation->meat_grade == 'standard' ? 'info' : 
                                            ($operation->meat_grade == 'economy' ? 'warning' : 'secondary')) 
                                        }}">
                                            {{ ucfirst($operation->meat_grade) }}
                                        </span>
                                    </td>
                                    <td>{{ $operation->slaughteredBy->name ?? 'N/A' }}</td>
                                    <td>{{ $operation->supervisedBy->name ?? 'N/A' }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="9" class="text-center">No slaughter operations found.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $operations->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection