@extends('layouts.lga_admin')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Member Management: {{ $cooperative->name }}</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('lga_admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('lga_admin.cooperatives.index') }}">Cooperatives</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('lga_admin.cooperatives.show', $cooperative) }}">{{ $cooperative->name }}</a></li>
                    <li class="breadcrumb-item active">Members</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-4">
        <!-- Add Member Form -->
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="card-title mb-0 text-white">
                    <i class="ri-user-add-line me-2"></i>Add New Member
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('lga_admin.cooperatives.members.add', $cooperative) }}" method="POST" id="addMemberForm">
                    @csrf

                    <div class="mb-3">
                        <label for="farmer_id" class="form-label">
                            Select Farmer <span class="text-danger">*</span>
                        </label>
                        <select class="form-select @error('farmer_id') is-invalid @enderror" 
                                id="farmer_id" 
                                name="farmer_id" 
                                required>
                            <option value="">-- Select Farmer --</option>
                            @foreach($availableFarmers as $farmer)
                            <option value="{{ $farmer->id }}" {{ old('farmer_id') == $farmer->id ? 'selected' : '' }}>
                                {{ $farmer->full_name }} - {{ $farmer->ward }}
                            </option>
                            @endforeach
                        </select>
                        <small class="text-muted">Only active farmers from your LGA</small>
                        @error('farmer_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="membership_number" class="form-label">
                            Membership Number <span class="text-danger">*</span>
                        </label>
                        <input type="text" 
                               class="form-control @error('membership_number') is-invalid @enderror" 
                               id="membership_number" 
                               name="membership_number" 
                               value="{{ old('membership_number') }}" 
                               placeholder="e.g., MEM/2025/001"
                               required>
                        @error('membership_number')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="joined_date" class="form-label">
                            Date Joined <span class="text-danger">*</span>
                        </label>
                        <input type="date" 
                               class="form-control @error('joined_date') is-invalid @enderror" 
                               id="joined_date" 
                               name="joined_date" 
                               value="{{ old('joined_date', date('Y-m-d')) }}" 
                               max="{{ date('Y-m-d') }}"
                               required>
                        @error('joined_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="position" class="form-label">Position (Optional)</label>
                        <select class="form-select" id="position" name="position">
                            <option value="">-- Select Position --</option>
                            <option value="Chairman" {{ old('position') == 'Chairman' ? 'selected' : '' }}>Chairman</option>
                            <option value="Vice Chairman" {{ old('position') == 'Vice Chairman' ? 'selected' : '' }}>Vice Chairman</option>
                            <option value="Secretary" {{ old('position') == 'Secretary' ? 'selected' : '' }}>Secretary</option>
                            <option value="Treasurer" {{ old('position') == 'Treasurer' ? 'selected' : '' }}>Treasurer</option>
                            <option value="Financial Secretary" {{ old('position') == 'Financial Secretary' ? 'selected' : '' }}>Financial Secretary</option>
                            <option value="PRO" {{ old('position') == 'PRO' ? 'selected' : '' }}>PRO</option>
                            <option value="Executive Member" {{ old('position') == 'Executive Member' ? 'selected' : '' }}>Executive Member</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="notes" class="form-label">Notes (Optional)</label>
                        <textarea class="form-control" 
                                  id="notes" 
                                  name="notes" 
                                  rows="3" 
                                  placeholder="Any additional information...">{{ old('notes') }}</textarea>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">
                        <i class="ri-user-add-line me-1"></i>Add Member
                    </button>
                </form>
            </div>
        </div>

        <!-- Cooperative Info Card -->
        <div class="card">
            <div class="card-body">
                <h6 class="card-title mb-3">Cooperative Information</h6>
                <div class="mb-2">
                    <small class="text-muted">Registration Number</small>
                    <p class="mb-0 fw-medium">{{ $cooperative->registration_number }}</p>
                </div>
                <div class="mb-2">
                    <small class="text-muted">Contact Person</small>
                    <p class="mb-0">{{ $cooperative->contact_person }}</p>
                </div>
                <div class="mb-2">
                    <small class="text-muted">Phone</small>
                    <p class="mb-0">{{ $cooperative->phone }}</p>
                </div>
                <hr>
                <a href="{{ route('lga_admin.cooperatives.show', $cooperative) }}" class="btn btn-light btn-sm w-100">
                    <i class="ri-arrow-left-line me-1"></i>Back to Details
                </a>
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <!-- Current Members -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    Current Members ({{ $cooperative->members->count() }})
                </h5>
            </div>
            <div class="card-body">
                @if($cooperative->members->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" id="membersTable">
                        <thead class="table-light">
                            <tr>
                                <th>Member Name</th>
                                <th>Phone</th>
                                <th>Membership No.</th>
                                <th>Position</th>
                                <th>Joined Date</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($cooperative->members as $member)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-xs me-3">
                                            <span class="avatar-title rounded-circle bg-soft-primary text-primary">
                                                {{ substr($member->full_name, 0, 1) }}
                                            </span>
                                        </div>
                                        <div>
                                            <a href="{{ route('lga_admin.farmers.show', $member) }}" class="text-body fw-medium">
                                                {{ $member->full_name }}
                                            </a>
                                            @if($member->pivot->notes)
                                            <br><small class="text-muted">{{ Str::limit($member->pivot->notes, 30) }}</small>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <i class="ri-phone-line text-muted"></i> {{ $member->phone_primary }}
                                </td>
                                <td>
                                    <span class="badge bg-soft-secondary text-secondary">
                                        {{ $member->pivot->membership_number }}
                                    </span>
                                </td>
                                <td>
                                    @if($member->pivot->position)
                                        <span class="badge bg-soft-info text-info">{{ $member->pivot->position }}</span>
                                    @else
                                        <span class="text-muted">Member</span>
                                    @endif
                                </td>
                                <td>{{ \Carbon\Carbon::parse($member->pivot->joined_date)->format('M d, Y') }}</td>
                                <td>
                                    @if($member->pivot->membership_status === 'active')
                                        <span class="badge bg-soft-success text-success">
                                            <i class="ri-checkbox-circle-line"></i> Active
                                        </span>
                                    @else
                                        <span class="badge bg-soft-warning text-warning">
                                            <i class="ri-close-circle-line"></i> Inactive
                                        </span>
                                        @if($member->pivot->exit_date)
                                        <br><small class="text-muted">Exit: {{ \Carbon\Carbon::parse($member->pivot->exit_date)->format('M d, Y') }}</small>
                                        @endif
                                    @endif
                                </td>
                                <td>
                                    @if($member->pivot->membership_status === 'active')
                                    <form action="{{ route('lga_admin.cooperatives.members.remove', [$cooperative, $member]) }}" 
                                          method="POST" 
                                          class="d-inline remove-member-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-sm btn-soft-danger remove-member-btn" title="Remove Member">
                                            <i class="ri-user-unfollow-line"></i> Remove
                                        </button>
                                    </form>
                                    @else
                                        <span class="text-muted small">Removed</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="text-center py-5">
                    <i class="ri-team-line font-size-48 text-muted d-block mb-3"></i>
                    <h5 class="text-muted">No Members Yet</h5>
                    <p class="text-muted">Add farmers from your LGA as members of this cooperative using the form on the left.</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // DataTable initialization
    @if($cooperative->members->count() > 0)
    $(document).ready(function() {
        $('#membersTable').DataTable({
            "pageLength": 25,
            "order": [[4, "desc"]], // Sort by joined date
            "language": {
                "search": "Search members:"
            }
        });
    });
    @endif

    // Remove member confirmation
    document.querySelectorAll('.remove-member-btn').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const form = this.closest('form');
            
            Swal.fire({
                title: 'Remove Member?',
                text: "This will mark the member as inactive. This action can be tracked but not easily reversed.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Yes, remove member',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
</script>
@endpush