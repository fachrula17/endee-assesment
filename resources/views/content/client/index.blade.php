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
        <a href="{{ route('client.create') }}" class="btn btn-danger mb-3">Add Client</a>

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
                                <th>Client Address</th>
                                <th>Date Start Contract</th>
                                <th>Date End Contract</th>
                                <th style="background-color: #ffffff" class="notExport noBulk">Action</th>
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
<script>
    var clientTable;

    function datatable_show(){
        clientTable = $('#table-item').DataTable({
            // dom: 'rt<"bottom"i><"dataTable-right"flp><"clear">',
            autoWidth: true,
            preDrawCallback: function() {
                let el = $('div.dataTables_filter label');
                if (!el.parent('form').length) {
                    el.wrapAll('<form></form>').parent()
                        .attr('autocomplete', false)
                        .attr('autofill', false);
                }
            },
            processing: true,
            serverSide: true,
            "ajax": {
                url: "{{ route('client.datatables') }}",
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
            // buttons: buttonAction,
            columns: [
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
                    data: 'alamat_client',
                    name: 'alamat_client',
                },
                {
                    data: 'tgl_mulai_kontrak',
                    name: 'tgl_mulai_kontrak',
                },
                {
                    data: 'tgl_akhir_kontrak',
                    name: 'tgl_akhir_kontrak'
                },
                {
                    data: 'action',
                    name: 'action',
                    searchable: false
                }
            ],
        });
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

                    url: `/client/delete/${id}`,
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
                        clientTable.ajax.reload();
                        // $(`#index_${post_id}`).remove();
                    }
                });

                
            }
        });
    }
</script>
@endsection