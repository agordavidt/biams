@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Livestock Registration Report</h4>
                <div>
                    <a href="{{ route('admin.abattoirs.analytics') }}" class="btn btn-info">Back to Analytics</a>
                    <a href="{{ route('admin.abattoirs.analytics.slaughter') }}" class="btn btn-info">Slaughter Report</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form method="GET" action="{{ route('admin.abattoirs.analytics.livestock') }}" class="row g-3">
                        <div class="col-md-2">
                            <label class="form-label">LGA</label>
                            <select name="lga" class="form-select">
                                <option value="">All LGAs</option>
                                @foreach($lgas as $lgaOption)
                                    <option value="{{ $lgaOption }}" {{ $lga == $lgaOption ? 'selected' : '' }}>{{ $lgaOption }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select">
                                <option value="">All Statuses</option>
                                @foreach($statuses as $statusOption)
                                    <option value="{{ $statusOption }}" {{ $status == $statusOption ? 'selected' : '' }}>{{ ucfirst($statusOption) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Species</label>
                            <select name="species" class="form-select">
                                <option value="">All Species</option>
                                @foreach($speciesList as $speciesOption)
                                    <option value="{{ $speciesOption }}" {{ $species == $speciesOption ? 'selected' : '' }}>{{ ucfirst($speciesOption) }}</option>
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
                        <div class="col-md-2 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary me-2">Apply Filters</button>
                            <a href="{{ route('admin.abattoirs.analytics.livestock') }}" class="btn btn-secondary">Reset</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Livestock Table -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-4">Livestock Registration Records</h4>
                    <div class="table-responsive">
                        <table class="table table-centered table-nowrap mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Registration Date</th>
                                    <th>Tag Number</th>
                                    <th>Species</th>
                                    <th>Breed</th>
                                    <th>Sex</th>
                                    <th>Age</th>
                                    <th>Origin LGA</th>
                                    <th>Owner</th>
                                    <th>Status</th>
                                    <th>Registered By</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($livestock as $animal)
                                <tr>
                                    <td>{{ $animal->id }}</td>
                                    <td>{{ $animal->registration_date->format('Y-m-d') }}</td>
                                    <td>{{ $animal->tag_number }}</td>
                                    <td>{{ ucfirst($animal->species) }}</td>
                                    <td>{{ $animal->breed }}</td>
                                    <td>{{ ucfirst($animal->sex) }}</td>
                                    <td>{{ $animal->age }} {{ Str::plural('month', $animal->age) }}</td>
                                    <td>{{ $animal->origin_lga }}</td>
                                    <td>{{ $animal->owner_name }}</td>
                                    <td>
                                        <span class="badge bg-{{ 
                                            $animal->status == 'registered' ? 'info' : 
                                            ($animal->status == 'inspected' ? 'warning' : 
                                            ($animal->status == 'approved' ? 'success' : 
                                            ($animal->status == 'slaughtered' ? 'dark' : 'danger'))) 
                                        }}">
                                            {{ ucfirst($animal->status) }}
                                        </span>
                                    </td>
                                    <td>{{ $animal->registeredBy->name ?? 'N/A' }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="11" class="text-center">No livestock records found.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $livestock->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection