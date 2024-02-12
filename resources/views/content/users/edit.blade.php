@extends('front')

@section('css')
<link rel="stylesheet" href="{{ asset('assets/plugins/parsley/src/parsley.css') }}">
@endsection

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>{{ $title }}</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">{{ $title }}</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        @if(Session::get('failed'))
            <p class="alert alert-danger">{{ Session::get('failed') }}</p>
        @endif

        <!-- Default box -->
        <div class="card">
            <div class="card-body">
                <form action="{{ route('users.update', $users->id) }}" method="POST" id="client_form" data-parsley-validate>
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label for="user_name">User Name</label>
                        <input type="text" class="form-control" id="user_name" name="user_name" placeholder="Enter User Name" required value="{{ $users->name }}">
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" id="email" name="email" placeholder="Enter Email" required value="{{ $users->email }}">
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" class="form-control" id="password" name="password" placeholder="Enter Password">
                        <small class="text-danger">fill this field if want to change password</small>
                    </div>
                    <div class="form-group">
                        <label for="email">Role</label>
                        <select name="role" id="role" class="form-control" required>
                            <option value="">-- Select Role --</option>
                            <option value="0" {{ ($users->role == 0) ? 'selected' : '' }}>User</option>
                            <option value="1" {{ ($users->role == 1) ? 'selected' : '' }}>Admin</option>
                            <option value="2" {{ ($users->role == 2) ? 'selected' : '' }}>Super Admin</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <input type="submit" name="submit" value="Save" class="btn btn-primary">
                    </div>
                </form>
            </div>
        </div>
        <!-- /.card -->

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
@endsection

@section('js')
<script src="{{ asset('assets/plugins/parsley/dist/parsley.min.js') }}"></script>
<script>

</script>
@endsection