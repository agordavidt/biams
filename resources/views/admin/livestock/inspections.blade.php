@extends('layouts.admin')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Inspections for Livestock: {{ $livestock->tracking_id }}</h4>
                    <a href="{{ route('admin.livestock.index') }}" class="btn btn-secondary">Back to Livestock</a>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h5>Ante-Mortem Inspection</h5>
                        <form method="POST" action="{{ route('admin.livestock.ante-mortem.store', $livestock) }}">
                            @csrf
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Abattoir</label>
                                    <select name="abattoir_id" class="form-control @error('abattoir_id') is-invalid @enderror">
                                        <option value="">Select Abattoir</option>
                                        @foreach ($abattoirs as $abattoir)
                                            <option value="{{ $abattoir->id }}">{{ $abattoir->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('abattoir_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Inspector</label>
                                    <select name="inspector_id" class="form-control @error('inspector_id') is-invalid @enderror">
                                        <option value="">Select Inspector</option>
                                        @foreach ($inspectors as $inspector)
                                            <option value="{{ $inspector->id }}">{{ $inspector->name }} ({{ ucfirst($inspector->role) }})</option>
                                        @endforeach
                                    </select>
                                    @error('inspector_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Inspection Date</label>
                                    <input type="datetime-local" name="inspection_date" class="form-control @error('inspection_date') is-invalid @enderror" value="{{ old('inspection_date') }}">
                                    @error('inspection_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">Temperature (°C)</label>
                                    <input type="number" step="0.1" name="temperature" class="form-control @error('temperature') is-invalid @enderror" value="{{ old('temperature') }}">
                                    @error('temperature') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">Heart Rate (bpm)</label>
                                    <input type="number" name="heart_rate" class="form-control @error('heart_rate') is-invalid @enderror" value="{{ old('heart_rate') }}">
                                    @error('heart_rate') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">Respiratory Rate</label>
                                    <input type="number" name="respiratory_rate" class="form-control @error('respiratory_rate') is-invalid @enderror" value="{{ old('respiratory_rate') }}">
                                    @error('respiratory_rate') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">General Appearance</label>
                                    <select name="general_appearance" class="form-control @error('general_appearance') is-invalid @enderror">
                                        <option value="normal">Normal</option>
                                        <option value="abnormal">Abnormal</option>
                                    </select>
                                    @error('general_appearance') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3 mb-3">
                                    <label class="form-check-label">Alert</label>
                                    <input type="checkbox" name="is_alert" class="form-check-input" {{ old('is_alert') ? 'checked' : '' }}>
                                    @error('is_alert') <div class="text-danger">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="form-check-label">Lameness</label>
                                    <input type="checkbox" name="has_lameness" class="form-check-input" {{ old('has_lameness') ? 'checked' : '' }}>
                                    @error('has_lameness') <div class="text-danger">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="form-check-label">Visible Injuries</label>
                                    <input type="checkbox" name="has_visible_injuries" class="form-check-input" {{ old('has_visible_injuries') ? 'checked' : '' }}>
                                    @error('has_visible_injuries') <div class="text-danger">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="form-check-label">Abnormal Discharge</label>
                                    <input type="checkbox" name="has_abnormal_discharge" class="form-check-input" {{ old('has_abnormal_discharge') ? 'checked' : '' }}>
                                    @error('has_abnormal_discharge') <div class="text-danger">{{ $message }}</div> @enderror
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Decision</label>
                                    <select name="decision" class="form-control @error('decision') is-invalid @enderror">
                                        <option value="approved">Approved</option>
                                        <option value="rejected">Rejected</option>
                                        <option value="conditional">Conditional</option>
                                    </select>
                                    @error('decision') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Rejection Reason</label>
                                    <textarea name="rejection_reason" class="form-control @error('rejection_reason') is-invalid @enderror">{{ old('rejection_reason') }}</textarea>
                                    @error('rejection_reason') <div class="invalid-feedback">{{ $message }}</div> @enderror
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
                        <h5>Post-Mortem Inspection</h5>
                        <form method="POST" action="{{ route('admin.livestock.post-mortem.store', $livestock) }}">
                            @csrf
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Abattoir</label>
                                    <select name="abattoir_id" class="form-control @error('abattoir_id') is-invalid @enderror">
                                        <option value="">Select Abattoir</option>
                                        @foreach ($abattoirs as $abattoir)
                                            <option value="{{ $abattoir->id }}">{{ $abattoir->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('abattoir_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Inspector</label>
                                    <select name="inspector_id" class="form-control @error('inspector_id') is-invalid @enderror">
                                        <option value="">Select Inspector</option>
                                        @foreach ($inspectors as $inspector)
                                            <option value="{{ $inspector->id }}">{{ $inspector->name }} ({{ ucfirst($inspector->role) }})</option>
                                        @endforeach
                                    </select>
                                    @error('inspector_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Inspection Date</label>
                                    <input type="datetime-local" name="inspection_date" class="form-control @error('inspection_date') is-invalid @enderror" value="{{ old('inspection_date') }}">
                                    @error('inspection_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3 mb-3">
                                    <label class="form-check-label">Carcass Normal</label>
                                    <input type="checkbox" name="carcass_normal" class="form-check-input" {{ old('carcass_normal') ? 'checked' : '' }}>
                                    @error('carcass_normal') <div class="text-danger">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="form-check-label">Organs Normal</label>
                                    <input type="checkbox" name="organs_normal" class="form-check-input" {{ old('organs_normal') ? 'checked' : '' }}>
                                    @error('organs_normal') <div class="text-danger">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="form-check-label">Lymph Nodes Normal</label>
                                    <input type="checkbox" name="lymph_nodes_normal" class="form-check-input" {{ old('lymph_nodes_normal') ? 'checked' : '' }}>
                                    @error('lymph_nodes_normal') <div class="text-danger">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="form-check-label">Parasites</label>
                                    <input type="checkbox" name="has_parasites" class="form-check-input" {{ old('has_parasites') ? 'checked' : '' }}>
                                    @error('has_parasites') <div class="text-danger">{{ $message }}</div> @enderror
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3 mb-3">
                                    <label class="form-check-label">Disease Signs</label>
                                    <input type="checkbox" name="has_disease_signs" class="form-check-input" {{ old('has_disease_signs') ? 'checked' : '' }}>
                                    @error('has_disease_signs') <div class="text-danger">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-9 mb-3">
                                    <label class="form-label">Abnormality Details</label>
                                    <textarea name="abnormality_details" class="form-control @error('abnormality_details') is-invalid @enderror">{{ old('abnormality_details') }}</textarea>
                                    @error('abnormality_details') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Decision</label>
                                    <select TRACK name="decision" class="form-control @error('decision') is-invalid @enderror">
                                        <option value="fit_for_consumption">Fit for Consumption</option>
                                        <option value="unfit_for_consumption">Unfit for Consumption</option>
                                        <option value="partially_fit">Partially Fit</option>
                                    </select>
                                    @error('decision') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Rejection Reason</label>
                                    <textarea name="rejection_reason" class="form-control @error('rejection_reason') is-invalid @enderror">{{ old('rejection_reason') }}</textarea>
                                    @error('rejection_reason') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Stamp Number</label>
                                    <input type="text" name="stamp_number" class="form-control @error('stamp_number') is-invalid @enderror" value="{{ old('stamp_number') }}">
                                    @error('stamp_number') <div class="invalid-feedback">{{ $message }}</div> @enderror
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
                        <h5>Ante-Mortem Inspections</h5>
                        <table class="table datatable">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Abattoir</th>
                                    <th>Inspector</th>
                                    <th>Decision</th>
                                    <th>Issues</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($anteInspections as $inspection)
                                    <tr>
                                        <td>{{ $inspection->inspection_date->format('Y-m-d H:i') }}</td>
                                        <td>{{ $inspection->abattoir->name ?? 'N/A' }}</td>
                                        <td>{{ $inspection->inspector->name ?? 'N/A' }}</td>
                                        <td>{{ ucfirst($inspection->decision) }}</td>
                                        <td>
                                            @if ($inspection->has_lameness || $inspection->has_visible_injuries || $inspection->has_abnormal_discharge)
                                                {{ implode(', ', array_filter([
                                                    $inspection->has_lameness ? 'Lameness' : null,
                                                    $inspection->has_visible_injuries ? 'Injuries' : null,
                                                    $inspection->has_abnormal_discharge ? 'Discharge' : null,
                                                ])) }}
                                            @else
                                                None
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h5>Post-Mortem Inspections</h5>
                        <table class="table datatable">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Abattoir</th>
                                    <th>Inspector</th>
                                    <th>Decision</th>
                                    <th>Issues</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($postInspections as $inspection)
                                    <tr>
                                        <td>{{ $inspection->inspection_date->format('Y-m-d H:i') }}</td>
                                        <td>{{ $inspection->abattoir->name ?? 'N/A' }}</td>
                                        <td>{{ $inspection->inspector->name ?? 'N/A' }}</td>
                                        <td>{{ ucfirst(str_replace('_', ' ', $inspection->decision)) }}</td>
                                        <td>
                                            @if ($inspection->has_parasites || $inspection->has_disease_signs)
                                                {{ implode(', ', array_filter([
                                                    $inspection->has_parasites ? 'Parasites' : null,
                                                    $inspection->has_disease_signs ? 'Disease Signs' : null,
                                                ])) }}
                                            @else
                                                None
                                            @endif
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
        $(document).ready(function() {
            $('.datatable').DataTable({
                responsive: true,
            });
        });
    </script>
@endpush