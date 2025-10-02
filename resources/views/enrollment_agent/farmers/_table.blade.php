<div class="table-responsive">
    <table class="table table-centered table-nowrap mb-0">
        <thead class="table-light">
            <tr>
                <th>Farmer Name</th>
                <th>LGA</th>
                <th>Submission Date</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($farmers as $farmer)
            <tr>
                <td><a href="{{ route('enrollment.farmers.show', $farmer) }}" class="text-primary fw-bold">{{ $farmer->full_name }}</a></td>
                <td>{{ $farmer->lga->name ?? 'N/A' }}</td>
                <td>{{ $farmer->created_at->format('M d, Y') }}</td>
                <td>
                    @php
                        $statusClass = [
                            'pending_lga_review' => 'status-pending',
                            'rejected' => 'status-unverified',
                            'pending_activation' => 'status-verified',
                            'active' => 'status-verified',
                        ][$farmer->status] ?? 'status-pending';
                    @endphp
                    <span class="status-badge {{ $statusClass }}">
                        {{ ucwords(str_replace('_', ' ', $farmer->status)) }}
                    </span>
                </td>
                <td>
                    <a href="{{ route('enrollment.farmers.show', $farmer) }}" class="btn btn-sm btn-info waves-effect waves-light" title="View Details"><i class="ri-eye-line"></i></a>
                    
                    @if(in_array($farmer->status, ['pending_lga_review', 'rejected']))
                        <a href="{{ route('enrollment.farmers.edit', $farmer) }}" class="btn btn-sm btn-warning waves-effect waves-light" title="Edit/Resubmit"><i class="ri-pencil-line"></i></a>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>