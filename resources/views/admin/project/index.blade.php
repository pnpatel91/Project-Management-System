@extends('admin.layouts.master')

@section('content')

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12" id="message">
            @include('message.alert')
        </div>
        
        <div class="row col-sm-12 page-titles">
            <div class="col-lg-5 p-b-9 align-self-center text-left  " id="list-page-actions-container">
                <div id="list-page-actions">
                    <!--ADD NEW ITEM-->
                    @can('create Project')
                    <a href="{{ route('admin.projectCategory.create') }}" class="btn btn-danger btn-add-circle edit-add-modal-button js-ajax-ux-request reset-target-modal-form" id="popup-modal-button">
                        <span tooltip="Create new project category." flow="right"><i class="fas fa-plus"></i></span>
                    </a>
                    @endcan
                    <!--ADD NEW BUTTON (link)-->
                </div>
            </div>
            <div class="col-lg-7 align-self-center list-pages-crumbs text-right" id="breadcrumbs">
                <h3 class="text-themecolor">Project</h3>
                <!--crumbs-->
                <ol class="breadcrumb float-right">
                    <li class="breadcrumb-item">App</li>    
                    <li class="breadcrumb-item  active active-bread-crumb ">Project</li>
                </ol>
                <!--crumbs-->
            </div>
            
        </div>

        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">All Project List</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <div class="table-responsive list-table-wrapper">
                        <table class="table table-hover dataTable no-footer" id="table" width="100%">
                            <thead>
                            <tr>
                                <th>Name</th>
                                <th>Created By</th>
                                <th>Status</th>
                                @if (auth()->user()->can('edit Project') || auth()->user()->can('delete Project'))
                                <th class="noExport" style="width: 100px;">Action</th>
                                @endif
                            </tr>
                            </thead>
                            <tbody>
                                
                            </tbody>
                        </table>
                    </div>
                    
                </div>
                <!-- /.card-body -->
            </div>
        </div>

    </div>
</div>


<script>
function datatables() {
    $("#table").load(location.href + " #table");
    window.location.reload();
}

function datatables_firstcall() {
    var table = $('#table').DataTable({
        dom: 'RBfrtip',
        buttons: [],
        aaSorting     : [[1, 'asc']],
        "bDestroy": true
    });
}

datatables_firstcall();

/*For user status change*/
    function funChangeStatus(id,status) {
        $("#pageloader").fadeIn();
        $.ajax({
          url : '{{ route('admin.projectCategory.ajax.change_status') }}',
          data: {
            "_token": "{{ csrf_token() }}",
            "id": id,
            "status": status
            },
          type: 'get',
          dataType: 'json',
          success: function( result )
          {
            datatables();
            $("#pageloader").hide();
          }
        });
    }
</script>

@endsection
