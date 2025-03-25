@extends('layouts.new')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Animal Farming Registration</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Animal Registration</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <form method="POST" action="#">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-4">
                                <label class="form-label" for="herd_size">Herd Size</label>
                                <input type="number" class="form-control" name="herd_size" id="herd_size" placeholder="Enter total number of animals" required>
                            </div>

                            <div class="mb-4">
                                <label class="form-label" for="facility_type">Facility Type</label>
                                <select class="form-select" name="facility_type" id="facility_type" required>
                                    <option value="">Select Facility Type</option>
                                    <option value="Open Grazing">Open Grazing</option>
                                    <option value="Fenced Pasture">Fenced Pasture</option>
                                    <option value="Zero Grazing">Zero Grazing</option>
                                    <option value="Indoor Housing">Indoor Housing</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>

                            <div class="mb-4">
                                <label class="form-label" for="breeding_program">Breeding Program</label>
                                <select class="form-select" name="breeding_program" id="breeding_program" required>
                                    <option value="">Select Breeding Program</option>
                                    <option value="Artificial Insemination">Artificial Insemination</option>
                                    <option value="Natural Mating">Natural Mating</option>
                                    <option value="Crossbreeding">Crossbreeding</option>
                                    <option value="Selective Breeding">Selective Breeding</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-4">
                                <label class="form-label" for="farm_location">Location</label>
                                <input type="text" class="form-control" name="farm_location" id="location" placeholder="Enter farm location" required>
                            </div>

                            <div class="mb-4">
                                <label class="form-label" for="livestock">Livestock Type</label>
                                <select class="form-select" name="livestock" id="livestock" onchange="handleOtherLivestock()" required>
                                    <option value="">Select Livestock</option>
                                    <option value="Cattle">Cattle</option>
                                    <option value="Goats">Goats</option>
                                    <option value="Sheep">Sheep</option>
                                    <option value="Poultry">Poultry</option>
                                    <option value="Pigs">Pigs</option>
                                    <option value="Fish">Fish</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>

                            <div class="mb-4" id="otherLivestockField" style="display: none;">
                                <label class="form-label" for="other_livestock">Specify the livestock type</label>
                                <input type="text" class="form-control" name="other_livestock" id="other_livestock">
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-12 text-end">
                            <a href="{{ route('home') }}" class="btn btn-secondary me-2">Cancel</a>
                            <button type="submit" class="btn btn-primary">Submit Registration</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript -->
<script>
    // Function to handle the "Other" livestock option
    function handleOtherLivestock() {
        const livestockSelect = document.getElementById('livestock');
        const otherLivestockField = document.getElementById('otherLivestockField');
        otherLivestockField.style.display = livestockSelect.value === 'Other' ? 'block' : 'none';
    }

    // Initialize the other livestock field on page load
    document.addEventListener('DOMContentLoaded', handleOtherLivestock);
</script>
@endsection