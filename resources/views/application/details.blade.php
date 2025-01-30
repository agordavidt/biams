<div>
    <h2>{{ $registration->type }} Application Details</h2>
    <p><strong>Status:</strong> {{ ucfirst($registration->status) }}</p>
    <p><strong>Application Date:</strong> {{ $registration->created_at->format('Y-m-d') }}</p> 
    <p> Add more fields</p>
</div>