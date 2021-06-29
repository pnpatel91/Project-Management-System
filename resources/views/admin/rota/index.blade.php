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
                    @can('create rota')
                    <a href="{{ route('admin.rota.create') }}" class="btn btn-danger btn-add-circle edit-add-modal-button js-ajax-ux-request reset-target-modal-form" id="popup-modal-button">
                        <span tooltip="create new rota." flow="right"><i class="fas fa-plus"></i></span>
                    </a>
                    @endcan
                    <!--ADD NEW BUTTON (link)-->
                </div>
            </div>
            <div class="col-lg-7 align-self-center list-pages-crumbs text-right" id="breadcrumbs">
                <h3 class="text-themecolor">Rota</h3>
                <!--crumbs-->
                <ol class="breadcrumb float-right">
                    <li class="breadcrumb-item">App</li>    
                    <li class="breadcrumb-item  active active-bread-crumb ">Rota</li>
                </ol>
                <!--crumbs-->
            </div>
            
        </div>

        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">All Rota List</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <div class="col-md-12">
                        <div class="table-responsive" id="ajax_table_data">

                        </div>

                    </div>
                    
                </div>
                <!-- /.card-body -->
            </div>
        </div>

    </div>
</div>


<script>
function datatables() {

    var table = $('#table').DataTable({
        'scrollX': 'true',
    });
}

function load_table_data() {
    $("#pageloader").fadeIn();
    $('#ajax_table_data').html('');
    $.ajax({
        url:  '{{ route('admin.rota.ajax.table') }}',
        dataType: 'html',
        data: {
            "_token": "{{ csrf_token() }}"
        },
        success: function(response) {
            $('#ajax_table_data').html(response);
            datatables();
            $("#pageloader").hide();
        },
        error: function (data){
                console.log(data);
        }
    });
}
load_table_data();

$(document).ready(function () {

    $('body').on('click', '#popup-modal-button-rota', function(event) {
        event.preventDefault();
        var url = $(this).attr('href');
        $.ajax({
            url: url,
            dataType: 'html',
            success: function(response) {
                $('#popup-modal-body').html(response);
            },
            error: function (data){
                    console.log(data);
            }
        });

        $('#popup-modal').modal('show');
    });


    $(document).on('submit','.delete-form-rota',function(e){
        e.preventDefault();
        var url = $(this).attr('action');
        var data = $(this).serialize();
        swal({
            title: "Delete?",
            text: "Are you sure want to delete it?",
            type: "warning",
            showCancelButton: !0,
            confirmButtonText: "Yes, delete it!",
            cancelButtonText: "No, cancel!",
            reverseButtons: !0
        }).then(function (r) {
            if (r.value === true) {
                $("#pageloader").fadeIn();
                $.ajax({
                    method: "POST",
                    url: url,
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    data: data,
                    success: function(message){
                        setTimeout(function() {   //calls click event after a certain time
                            load_table_data();
                            $("#pageloader").hide();
                            alert_message(message);
                        }, 1000);
                    },
                });
            } else {
                r.dismiss;
            }
        }, function (dismiss) {
            return false;
        })
    }); 
});
</script>
@endsection


