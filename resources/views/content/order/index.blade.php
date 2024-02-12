@extends('front')

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.css') }}">
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
        @if(Auth::user()->role != '0')
            <a href="{{ route('order.create') }}" class="btn btn-danger mb-3"> <i class="fas fa-plus"></i> Add Order</a>
        @endif
        <a href="{{ route('order.print') }}" class="btn btn-primary mb-3" target="_blank"> <i class="fas fa-pdf"></i> Import PDF</a>

        @if(Session::get('success'))
            <p class="alert alert-success">{{ Session::get('success') }}</p>
        @endif
        <!-- Default box -->
        <div class="card">
            <div class="card-body">
                <div id="myTable" class="mt-2 position-relative">
                    {{-- <div id="loadingTable">
                        <p><span class="alert alert-warning">... Loading</span></p>
                    </div> --}}
                    <table id="table-item" class="table table-bordered nowrap display" style="width:100%">
                        <thead>
                            <tr>
                                <th></th>
                                <th>Client Name</th>
                                <th>Item Name</th>
                                <th>Price</th>
                                <th>Order Date</th>
                                @if(Auth::user()->role != '0')
                                <th style="background-color: #ffffff" class="notExport noBulk">Action</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- /.card -->

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
@endsection

@section('js')
<script src="{{ asset('assets/plugins/select2/js/select2.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
<script src="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js" integrity="sha512-GsLlZN/3F2ErC5ifS5QtgpiJtWd43JWSuIgh7mbzZ8zBps+dvLusV+eNQATqgA/HdeKFVgA5v3S/cIrLF7QnIg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script>
    var orderTable;

    function datatable_show(){
        @if(Auth::user()->role != '0')
            var cols = [
                {
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    searchable: false,
                    visible: false
                },
                {
                    data: 'nama_client',
                    name: 'nama_client',
                },
                {
                    data: 'nama_item',
                    name: 'nama_item',
                },
                {
                    data: 'harga_item',
                    name: 'harga_item',
                    render: $.fn.dataTable.render.number( ',', '.', 2, 'Rp ' )
                },
                {
                    data: 'tanggal_order',
                    name: 'tanggal_order'
                },
                {
                    data: 'action',
                    name: 'action',
                    searchable: false
                }
            ]
        @else
            var cols = [
                {
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    searchable: false,
                    visible: false
                },
                {
                    data: 'nama_client',
                    name: 'nama_client',
                },
                {
                    data: 'nama_item',
                    name: 'nama_item',
                },
                {
                    data: 'harga_item',
                    name: 'harga_item',
                    render: $.fn.dataTable.render.number( ',', '.', 2, 'Rp ' )
                },
                {
                    data: 'tanggal_order',
                    name: 'tanggal_order'
                }
            ]
        @endif
        orderTable = $('#table-item').DataTable({
            // dom: 'rt<"bottom"i><"dataTable-right"flp><"clear">',
            autoWidth: true,
            processing: true,
            serverSide: true,
            "ajax": {
                url: "{{ route('order.datatables') }}",
                method: 'POST',
                'beforeSend': function(request) {
                    request.setRequestHeader("X-CSRF-TOKEN", $('meta[name="csrf-token"]').attr('content'));
                    $("#loadingTable").show();
                },
                "error": function(jqXHR, textStatus, error) {
                    if(jqXHR.status == 200){
                        location.reload();
                    }
                }
            },
            buttons:  ["pdf"],
            columns: cols
        }).buttons().container().appendTo('#table-item_wrapper .col-md-6:eq(0)');
    }

    $(document).ready(function(){
        datatable_show();
    })

    function remove(id){

        let token   = $("meta[name='csrf-token']").attr("content");

        Swal.fire({
            title: 'Are You Sure?',
            text: "Remove this data!",
            icon: 'warning',
            showCancelButton: true,
            cancelButtonText: 'NO',
            confirmButtonText: 'YES, REMOVE IT!'
        }).then((result) => {
            if (result.isConfirmed) {

                //fetch to delete data
                $.ajax({

                    url: `/order/delete/${id}`,
                    type: "DELETE",
                    cache: false,
                    data: {
                        "_token": token
                    },
                    success:function(response){ 

                        //show success message
                        Swal.fire({
                            type: 'success',
                            icon: 'success',
                            title: `${response.message}`,
                            showConfirmButton: false,
                            timer: 3000
                        });

                        //remove post on table
                        orderTable.ajax.reload();
                        // $(`#index_${post_id}`).remove();
                    }
                });

                
            }
        });
    }

    var element = document.getElementById('element-to-print');
    html2pdf(element);

</script>
@endsection