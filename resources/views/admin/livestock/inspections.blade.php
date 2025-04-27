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

        <!-- Status Alert -->
        @if($livestock->status === 'slaughtered')
            <div class="alert alert-warning">
                This animal has already been slaughtered and cannot undergo further inspections.
            </div>
        @endif

        <!-- Ante-Mortem Section -->
        @if(in_array($livestock->status, ['registered', 'inspected', 'approved']))
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h5>Ante-Mortem Inspection</h5>
                        @if(!$livestock->anteMortemInspections->count())
                        <form method="POST" action="{{ route('admin.livestock.ante-mortem.store', $livestock) }}">
                            @csrf              
                                    <div class="row">
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label">Abattoir*</label>
                                            <select name="abattoir_id" class="form-control" required>
                                                <option value="">Select Abattoir</option>
                                                @foreach ($abattoirs as $abattoir)
                                                    <option value="{{ $abattoir->id }}" @selected(old('abattoir_id') == $abattoir->id)>
                                                        {{ $abattoir->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label">Inspector*</label>
                                            <select name="inspector_id" class="form-control" required>
                                                <option value="">Select Inspector</option>
                                                @foreach ($inspectors as $inspector)
                                                    <option value="{{ $inspector->id }}" @selected(old('inspector_id') == $inspector->id)>
                                                        {{ $inspector->name }} ({{ ucfirst($inspector->role) }})
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label">Inspection Date*</label>
                                            <input type="datetime-local" name="inspection_date" class="form-control" 
                                                value="{{ old('inspection_date') }}" required>
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-3 mb-3">
                                            <label class="form-label">Temperature (°C)</label>
                                            <input type="number" step="0.1" name="temperature" class="form-control" 
                                                value="{{ old('temperature') }}">
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label class="form-label">Heart Rate (bpm)</label>
                                            <input type="number" name="heart_rate" class="form-control" 
                                                value="{{ old('heart_rate') }}">
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label class="form-label">Respiratory Rate</label>
                                            <input type="number" name="respiratory_rate" class="form-control" 
                                                value="{{ old('respiratory_rate') }}">
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label class="form-label">General Appearance*</label>
                                            <select name="general_appearance" class="form-control" required>
                                                <option value="normal" @selected(old('general_appearance') == 'normal')>Normal</option>
                                                <option value="abnormal" @selected(old('general_appearance') == 'abnormal')>Abnormal</option>
                                            </select>
                                        </div>
                                    </div>
                                    
                                    <div class="row mb-3">
                                        <div class="col-md-3">
                                            <div class="form-check">
                                                <input type="hidden" name="is_alert" value="0">
                                                <input type="checkbox" name="is_alert" class="form-check-input" value="1" 
                                                    @checked(old('is_alert', true))>
                                                <label class="form-check-label">Alert</label>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-check">
                                                <input type="hidden" name="has_lameness" value="0">
                                                <input type="checkbox" name="has_lameness" class="form-check-input" value="1" 
                                                    @checked(old('has_lameness'))>
                                                <label class="form-check-label">Lameness</label>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-check">
                                                <input type="hidden" name="has_visible_injuries" value="0">
                                                <input type="checkbox" name="has_visible_injuries" class="form-check-input" value="1" 
                                                    @checked(old('has_visible_injuries'))>
                                                <label class="form-check-label">Visible Injuries</label>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-check">
                                                <input type="hidden" name="has_abnormal_discharge" value="0">
                                                <input type="checkbox" name="has_abnormal_discharge" class="form-check-input" value="1" 
                                                    @checked(old('has_abnormal_discharge'))>
                                                <label class="form-check-label">Abnormal Discharge</label>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Decision*</label>
                                            <select name="decision" class="form-control" required>
                                                <option value="approved" @selected(old('decision') == 'approved')>Approved</option>
                                                <option value="rejected" @selected(old('decision') == 'rejected')>Rejected</option>
                                                <option value="conditional" @selected(old('decision') == 'conditional')>Conditional</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Rejection Reason</label>
                                            <textarea name="rejection_reason" class="form-control">{{ old('rejection_reason') }}</textarea>
                                        </div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label">Notes</label>
                                        <textarea name="notes" class="form-control">{{ old('notes') }}</textarea>
                                    </div>
                                    
                                    <button type="submit" class="btn btn-primary">Record Inspection</button>
                                
                        </form>
                        @else
                            <div class="alert alert-info">
                                Ante-mortem inspection already recorded for this animal.
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @endif

       
        <!-- Post-Mortem Section -->
        @if($livestock->status === 'slaughtered')
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h5>Post-Mortem Inspection</h5>
                        @if(!$livestock->postMortemInspections->count())
                        <form method="POST" action="{{ route('admin.livestock.post-mortem.store', $livestock) }}">
                            @csrf
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Abattoir*</label>
                                    <select name="abattoir_id" class="form-control" required>
                                        <option value="">Select Abattoir</option>
                                        @foreach ($abattoirs as $abattoir)
                                            <option value="{{ $abattoir->id }}" @selected(old('abattoir_id') == $abattoir->id)>
                                                {{ $abattoir->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Inspector*</label>
                                    <select name="inspector_id" class="form-control" required>
                                        <option value="">Select Inspector</option>
                                        @foreach ($inspectors as $inspector)
                                            <option value="{{ $inspector->id }}" @selected(old('inspector_id') == $inspector->id)>
                                                {{ $inspector->name }} ({{ ucfirst($inspector->role) }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Inspection Date*</label>
                                    <input type="datetime-local" name="inspection_date" class="form-control" 
                                           value="{{ old('inspection_date') }}" required>
                                </div>
                            </div>
                            
                            <div class="row mb-3">
                                <div class="col-md-3">
                                    <div class="form-check">
                                        <input type="hidden" name="carcass_normal" value="0">
                                        <input type="checkbox" name="carcass_normal" class="form-check-input" value="1" 
                                               @checked(old('carcass_normal', true))>
                                        <label class="form-check-label">Carcass Normal</label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-check">
                                        <input type="hidden" name="organs_normal" value="0">
                                        <input type="checkbox" name="organs_normal" class="form-check-input" value="1" 
                                               @checked(old('organs_normal', true))>
                                        <label class="form-check-label">Organs Normal</label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-check">
                                        <input type="hidden" name="lymph_nodes_normal" value="0">
                                        <input type="checkbox" name="lymph_nodes_normal" class="form-check-input" value="1" 
                                               @checked(old('lymph_nodes_normal', true))>
                                        <label class="form-check-label">Lymph Nodes Normal</label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-check">
                                        <input type="hidden" name="has_parasites" value="0">
                                        <input type="checkbox" name="has_parasites" class="form-check-input" value="1" 
                                               @checked(old('has_parasites'))>
                                        <label class="form-check-label">Parasites Present</label>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row mb-3">
                                <div class="col-md-3">
                                    <div class="form-check">
                                        <input type="hidden" name="has_disease_signs" value="0">
                                        <input type="checkbox" name="has_disease_signs" class="form-check-input" value="1" 
                                               @checked(old('has_disease_signs'))>
                                        <label class="form-check-label">Disease Signs</label>
                                    </div>
                                </div>
                                <div class="col-md-9">
                                    <label class="form-label">Abnormality Details</label>
                                    <textarea name="abnormality_details" class="form-control">{{ old('abnormality_details') }}</textarea>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Decision*</label>
                                    <select name="decision" class="form-control" required>
                                        <option value="fit_for_consumption" @selected(old('decision') == 'fit_for_consumption')>Fit for Consumption</option>
                                        <option value="unfit_for_consumption" @selected(old('decision') == 'unfit_for_consumption')>Unfit for Consumption</option>
                                        <option value="partially_fit" @selected(old('decision') == 'partially_fit')>Partially Fit</option>
                                    </select>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Rejection Reason</label>
                                    <textarea name="rejection_reason" class="form-control">{{ old('rejection_reason') }}</textarea>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Stamp Number</label>
                                    <input type="text" name="stamp_number" class="form-control" value="{{ old('stamp_number') }}">
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Notes</label>
                                <textarea name="notes" class="form-control">{{ old('notes') }}</textarea>
                            </div>
                            
                            <button type="submit" class="btn btn-primary">Record Inspection</button>
                        </form>
                        @else
                            <div class="alert alert-info">
                                Post-mortem inspection already recorded for this animal.
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Inspection History -->
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

        @if($livestock->status === 'slaughtered')
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
                                    <th>Stamp Number</th>
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
                                        <td>{{ $inspection->stamp_number ?? 'N/A' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        @endif
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

