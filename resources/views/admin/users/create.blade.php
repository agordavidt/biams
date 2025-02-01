@extends('layouts.table')

@section('content')



<!-- <div class="container">
    <h1>Add User</h1>
    <form action="{{ route('admin.users.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input type="text" class="form-control" id="name" name="name" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>
        <div class="mb-3">
            <label for="password_confirmation" class="form-label">Confirm Password</label>
            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
        </div>
        <div class="mb-3">
            <label for="role" class="form-label">Role</label>
            <select class="form-control" id="role" name="role" required>
                <option value="admin">Admin</option>
                <option value="user">User</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Create User</button>
    </form>
</div> -->

            <div class="row">
                <div class="col-xl-6 mx-auto">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Add User</h4>
                            <p class="card-title-desc"></p>

                           <form class="custom-validation" action="{{ route('admin.users.store') }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label>Name</label>
                                    <input type="text" class="form-control" name="name" required placeholder="Enter full name"/>
                                </div>

                                <div class="mb-3">
                                    <label>E-Mail</label>
                                    <div>
                                        <input type="email" class="form-control" name="email" required parsley-type="email" placeholder="Enter a valid e-mail"/>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label>Password</label>
                                    <div>
                                        <input type="password" id="pass2" class="form-control" name="password" required placeholder="Password"/>
                                    </div>
                                    <div class="mt-2">
                                        <input type="password" class="form-control" name="password_confirmation" required data-parsley-equalto="#pass2" placeholder="Confirm Password"/>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label>Role</label>
                                    <div>
                                        <select class="form-select" name="role" required>
                                            <option selected disabled>Select role</option>
                                            <option value="admin">Admin</option>
                                            <option value="user">User</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="mb-0">
                                    <div>
                                        <button type="submit" class="btn btn-primary waves-effect waves-light me-1">
                                            Create User
                                        </button>
                                        <button type="reset" class="btn btn-secondary waves-effect">
                                            Cancel
                                        </button>
                                    </div>
                                </div>
                            </form>


                        </div>
                    </div>
                </div> <!-- end col -->

            </div> <!-- end row -->

@endsection