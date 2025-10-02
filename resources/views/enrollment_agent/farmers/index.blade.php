@extends('layouts.enrollment_agent')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">All Farmer Submissions</h4>
                <div class="page-title-right">
                    <a href="{{ route('enrollment.farmers.create') }}" class="btn btn-primary waves-effect waves-light"><i class="ri-add-line align-middle me-1"></i> Enroll New Farmer</a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-4">Farmer Enrollment List</h4>
                    
                    @if($farmers->isEmpty())
                        <div class="alert alert-info">No farmer profiles have been submitted yet. Start by enrolling a new farmer!</div>
                    @else
                        @include('enrollment.farmers._table', ['farmers' => $farmers])
                        <div class="mt-3">
                            {{ $farmers->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection