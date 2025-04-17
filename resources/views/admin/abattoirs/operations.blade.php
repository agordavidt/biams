@extends('layouts.admin')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Slaughter Operations for {{ $abattoir->name }}</h4>
                    <a href="{{ route('admin.abattoirs.index') }}" class="btn btn-secondary">Back to Abattoirs</a>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h5>Record Slaughter Operation</h5>
                        <form method="POST" action="{{ route('admin.abattoirs.operations.store', $abattoir) }}">
                            @csrf
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Livestock</label>
                                    <select name="livestock_id" class="form-control @error('livestock_id') is-invalid @enderror">
                                        <option value="">Select Livestock</option>
                                        @foreach ($livestock as $animal)
                                            <option value="{{ $animal->id }}">{{ $animal->tracking_id }} ({{ ucfirst($animal->species) }})</option>
                                        @endforeach
                                    </select>
                                    @error('livestock_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Slaughter Date</label>
                                    <input type="date" name="slaughter_date" class="form-control @error('slaughter_date') is-invalid @enderror" value="{{ old('slaughter_date') }}">
                                    @error('slaughter_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Slaughter Time</label>
                                    <input type="time" name="slaughter_time" class="form-control @error('slaughter_time') is-invalid @enderror" value="{{ old('slaughter_time') }}">
                                    @error('slaughter_time') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Slaughtered By</label>
                                    <select name="slaughtered_by" class="form-control @error('slaughtered_by') is-invalid @enderror">
                                        <option value="">Select Staff</option>
                                        @foreach ($staff as $member)
                                            <option value="{{ $member->id }}">{{ $member->name }} ({{ ucfirst(str_replace('_', ' ', $member->role)) }})</option>
                                        @endforeach
                                    </select>
                                    @error('slaughtered_by') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Supervised By</label>
                                    <select name="supervised_by" class="form-control @error('supervised_by') is-invalid @enderror">
                                        <option value="">Select Staff (Optional)</option>
                                        @foreach ($staff as $member)
                                            <option value="{{ $member->id }}">{{ $member->name }} ({{ ucfirst(str_replace('_', ' ', $member->role)) }})</option>
                                        @endforeach
                                    </select>
                                    @error('supervised_by') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Carcass Weight (kg)</label>
                                    <input type="number" step="0.1" name="carcass_weight_kg" class="form-control @error('carcass_weight_kg') is-invalid @enderror" value="{{ old('carcass_weight_kg') }}">
                                    @error('carcass_weight_kg') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Meat Grade</label>
                                    <select name="meat_grade" class="form-control @error('meat_grade') is-invalid @enderror">
                                        <option value="premium">Premium</option>
                                        <option value="standard">Standard</option>
                                        <option value="economy">Economy</option>
                                        <option value="ungraded">Ungraded</option>
                                    </select>
                                    @error('meat_grade') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-check-label">Halal</label>
                                    <input type="checkbox" name="is_halal" class="form-check-input" {{ old('is_halal') ? 'checked' : '' }}>
                                    @error('is_halal') <div class="text-danger">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-check-label">Kosher</label>
                                    <input type="checkbox" name="is_kosher" class="form-check-input" {{ old('is_kosher') ? 'checked' : '' }}>
                                    @error('is_kosher') <div class="text-danger">{{ $message }}</div> @enderror
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Notes</label>
                                <textarea name="notes" class="form-control @error('notes') is-invalid @enderror">{{ old('notes') }}</textarea>
                                @error('notes') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <button type="submit" class="btn btn-primary">Record</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h5>Slaughter Operations</h5>
                        <table class="table datatable">
                            <thead>
                                <tr>
                                    <th>Livestock</th>
                                    <th>Species</th>
                                    <th>Slaughter Date</th>
                                    <th>Slaughtered By</th>
                                    <th>Supervised By</th>
                                    <th>Meat Grade</th>
                                    <th>Carcass Weight (kg)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($operations as $op)
                                    <tr>
                                        <td>{{ $op->livestock->tracking_id ?? 'N/A' }}</td>
                                        <td>{{ ucfirst($op->livestock->species ?? 'N/A') }}</td>
                                        <td>{{ $op->slaughter_date->format('Y-m-d') }} {{ $op->slaughter_time }}</td>
                                        <td>{{ $op->slaughteredBy->name ?? 'N/A' }}</td>
                                        <td>{{ $op->supervisedBy->name ?? 'N/A' }}</td>
                                        <td>{{ ucfirst($op->meat_grade) }}</td>
                                        <td>{{ $op->carcass_weight_kg ?? 'N/A' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        {{ $operations->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('.datatable').DataTable({
                dom: 'Bfrtip',
                buttons: ['csv', 'excel', 'pdf', 'print'],
                responsive: true,
            });
        });
    </script>
@endpush