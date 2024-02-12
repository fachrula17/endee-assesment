@extends('front')

@section('css')
<link rel="stylesheet" href="{{ asset('assets/plugins/parsley/src/parsley.css') }}">
<link rel="stylesheet" href="{{ asset('assets/plugins/select2/css/select2.min.css') }}">
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
                <form action="{{ route('order.update', $order->id) }}" method="POST" id="client_form" data-parsley-validate>
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label for="client_id">Client Name</label>
                        <select name="client_id" id="client_id" class="form-control js-example-basic-single" required>
                            <option value="">-- Choose Client --</option>
                            @foreach($client as $c)
                            <option value="{{ $c->id }}">{{ $c->nama_client }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="item_name">Item Name</label>
                        <input type="text" name="item_name" id="item_name" class="form-control" required value="{{ $order->nama_item }}">
                    </div>
                    <div class="form-group">
                        <label for="item_price">Price</label>
                        <input type="number" min="0" name="item_price" id="item_price" class="form-control" required value="{{ $order->harga_item }}">
                    </div>
                    <div class="form-group">
                        <label for="order_date">Order Date</label>
                        <input type="date" name="order_date" class="form-control" id="order_date" placeholder="YYYY/MM/DD" required value="{{ $order->tanggal_order }}">
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
<script src="{{ asset('assets/plugins/select2/js/select2.min.js') }}"></script>
<script>
// In your Javascript (external .js resource or <script> tag)
$(document).ready(function() {
    $('.js-example-basic-single').select2();
    $('.js-example-basic-single').val("{{ $order->client_id }}").trigger('change');
});
</script>
@endsection