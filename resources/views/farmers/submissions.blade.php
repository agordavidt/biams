<!-- resources/views/farmers/submissions.blade.php -->

@extends('layouts.user')

@section('content')
   

            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Your registered agricultural practices</h4>
                            <p class="card-title-desc">                                           
                            </p>

                            <div class="table-responsive">
                                <table class="table mb-0">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Submission ID</th>
                                            <th>Application Date</th>
                                            <th>Type</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($submissions as $index => $submission)
                                        <tr>
                                            <td>{{ $index + 1}}</td>
                                            <td>{{ $submission->id }}</td>
                                            <td>{{ $submission->created_at->format('Y-m-d') }}</td>
                                            <td>{{ $submission->type }}</td>
                                            <td><span class="badge 
                                            @if($submission->status === 'approved') 
                                                text-success 
                                            @elseif($submission->status === 'pending') 
                                                text-warning 
                                            @endif
                                                "{{ ucfirst($submission->status) }}<span>
                                            
                                            <td>
                                                <a href="#" class="btn btn-sm btn-primary">View Details</a>
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
            <!-- end row -->
@endsection
