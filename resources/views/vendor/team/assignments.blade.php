@extends('layouts.vendor')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <div>
                    <h4 class="page-title mb-1">Agent Resource Assignments</h4>
                    <p class="text-muted mb-0">Assign specific resources to distribution agents</p>
                </div>
                <a href="{{ route('vendor.team.index') }}" class="btn btn-light">
                    <i class="ri-arrow-left-line me-1"></i> Back to Team
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="alert alert-info">
                        <i class="ri-information-line me-2"></i>
                        <strong>Assignment Logic:</strong> If no resources are assigned to an agent, they can access ALL vendor resources. 
                        Once you assign specific resources, they can only access those resources.
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Agent Name</th>
                                    <th>Email</th>
                                    <th>Assigned Resources</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($agents as $agent)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-sm me-2">
                                                    <span class="avatar-title bg-primary rounded-circle">
                                                        {{ substr($agent->name, 0, 1) }}
                                                    </span>
                                                </div>
                                                <strong>{{ $agent->name }}</strong>
                                            </div>
                                        </td>
                                        <td>{{ $agent->email }}</td>
                                        <td>
                                            @if($agent->assignedResources->isEmpty())
                                                <span class="badge bg-secondary">All Resources (No Restrictions)</span>
                                            @else
                                                <div class="d-flex flex-wrap gap-1">
                                                    @foreach($agent->assignedResources as $resource)
                                                        <span class="badge bg-success">
                                                            {{ $resource->name }}
                                                            <button type="button" class="btn-close btn-close-white btn-sm ms-1"
                                                                    onclick="unassignResource({{ $agent->id }}, {{ $resource->id }})"
                                                                    style="font-size: 10px;"></button>
                                                        </span>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-primary"
                                                    onclick="showAssignModal({{ $agent->id }}, '{{ $agent->name }}')">
                                                <i class="ri-add-line me-1"></i> Assign Resources
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-4">
                                            <i class="ri-user-line display-4 d-block mb-2"></i>
                                            No distribution agents found
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Resources Overview -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Resources & Assigned Agents</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Resource Name</th>
                                    <th>Type</th>
                                    <th>Assigned Agents</th>
                                    <th>Available Stock</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($resources as $resource)
                                    <tr>
                                        <td><strong>{{ $resource->name }}</strong></td>
                                        <td><span class="badge bg-info">{{ ucfirst($resource->type) }}</span></td>
                                        <td>
                                            @if($resource->assignedAgents->isEmpty())
                                                <span class="text-muted">Available to all agents</span>
                                            @else
                                                <div class="d-flex flex-wrap gap-1">
                                                    @foreach($resource->assignedAgents as $agent)
                                                        <span class="badge bg-primary">{{ $agent->name }}</span>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </td>
                                        <td>
                                            @if($resource->requires_quantity)
                                                <strong>{{ $resource->available_stock }}</strong> {{ $resource->unit }}
                                            @else
                                                <span class="text-muted">N/A</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-4">
                                            No active resources found
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Assign Resources Modal -->
<div class="modal fade" id="assignModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="ri-add-line me-2"></i>Assign Resources to <span id="agentName"></span>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="selectedAgentId">
                
                <div class="alert alert-info">
                    <i class="ri-information-line me-2"></i>
                    Select resources to assign. The agent will ONLY be able to fulfill these specific resources.
                </div>

                <div class="row">
                    @foreach($resources as $resource)
                        <div class="col-md-6 mb-3">
                            <div class="form-check card p-3">
                                <input class="form-check-input resource-checkbox" 
                                       type="checkbox" 
                                       value="{{ $resource->id }}" 
                                       id="resource-{{ $resource->id }}">
                                <label class="form-check-label" for="resource-{{ $resource->id }}">
                                    <strong>{{ $resource->name }}</strong>
                                    <br>
                                    <small class="text-muted">
                                        {{ $resource->type }} 
                                        @if($resource->requires_quantity)
                                            | {{ $resource->available_stock }} {{ $resource->unit }} available
                                        @endif
                                    </small>
                                </label>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="bulkAssignResources()">
                    <i class="ri-check-line me-1"></i> Assign Selected Resources
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

<script>
function showAssignModal(agentId, agentName) {
    document.getElementById('selectedAgentId').value = agentId;
    document.getElementById('agentName').textContent = agentName;
    
    // Uncheck all checkboxes
    document.querySelectorAll('.resource-checkbox').forEach(cb => cb.checked = false);
    
    const modal = new bootstrap.Modal(document.getElementById('assignModal'));
    modal.show();
}

async function bulkAssignResources() {
    const agentId = document.getElementById('selectedAgentId').value;
    const selectedResources = Array.from(document.querySelectorAll('.resource-checkbox:checked'))
        .map(cb => parseInt(cb.value));

    if (selectedResources.length === 0) {
        toastr.warning('Please select at least one resource');
        return;
    }

    try {
        const response = await fetch('{{ route("vendor.team.assignments.bulk-assign") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                agent_id: agentId,
                resource_ids: selectedResources
            })
        });

        const data = await response.json();

        if (data.success) {
            toastr.success(data.message);
            setTimeout(() => window.location.reload(), 1500);
        } else {
            toastr.error(data.error || 'Assignment failed');
        }
    } catch (error) {
        toastr.error('An error occurred');
        console.error(error);
    }
}

async function unassignResource(agentId, resourceId) {
    if (!confirm('Remove this resource assignment?')) return;

    try {
        const response = await fetch('{{ route("vendor.team.assignments.unassign") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                agent_id: agentId,
                resource_id: resourceId
            })
        });

        const data = await response.json();

        if (data.success) {
            toastr.success(data.message);
            setTimeout(() => window.location.reload(), 1500);
        } else {
            toastr.error(data.error || 'Unassignment failed');
        }
    } catch (error) {
        toastr.error('An error occurred');
        console.error(error);
    }
}

toastr.options = {
    "closeButton": true,
    "progressBar": true,
    "positionClass": "toast-top-right",
    "timeOut": "3000"
};
</script>
@endpush
@endsection