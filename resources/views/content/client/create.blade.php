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
                <form action="{{ route('client.store') }}" method="POST" id="client_form" data-parsley-validate>
                    @csrf
                    <div class="form-group">
                        <label for="client_name">Client Name</label>
                        <input type="text" class="form-control" id="client_name" name="client_name" placeholder="Enter Client Name" required>
                    </div>
                    <div class="form-group">
                        <label for="client_address">Client Address</label>
                        <textarea name="client_address" id="client_address" class="form-control" required></textarea>
                    </div>
                    <div class="row mb-3">
                        <div class="col-lg-6">
                            <label for="start_contract">Start Contract</label>
                            <input type="date" name="start_contract" class="form-control" id="start_contract" placeholder="YYYY/MM/DD" required>
                        </div>
                        <div class="col-lg-6">
                            <label for="end_contract">End Contract</label>
                            <input type="date" name="end_contract" class="form-control" id="end_contract" placeholder="YYYY/MM/DD" required>
                        </div>
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