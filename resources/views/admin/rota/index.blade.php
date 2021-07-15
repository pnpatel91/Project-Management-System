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
                        <div class="form-row pt-3" id="search"> 

                            <div class="form-group col-md-6">
                                <span tooltip="search by default all employees" flow="right">Search by users <i class="fas fa-info-circle"></i></span>
                                <select class="select2 form-control" id="user_id" name="user_id[]" required autocomplete="user_id" multiple>
                                    <option value="All">All</option>
                                    @foreach ($users as $key => $user)
                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group col-md-4">
                                <span>Search by rota date</span>
                                <input class="search-area form-control" type="text" name="datefilter" id="daterange" value="" />  
                            </div>

                            <div class="mt-4 form-group col-md-1">
                                <button type="button  form-control" id="search" class="btn btn-primary" onclick="load_table_data()">Search</button>
                            </div>
                            <div class="mt-4 form-group col-md-1 text-right">
                               <button type="button  form-control" id="search" class="btn btn-primary" onclick="date_range_change(0)"><</button>
                                <button type="button  form-control" id="search" class="btn btn-primary" onclick="date_range_change(1)">></button> 
                            </div>
                            

                        </div>
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
        dom: 'Rltipr',
        "bLengthChange": false,
    });
}

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
                            //$("#pageloader").hide();
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


 $('input[name="datefilter"]').daterangepicker({
        ranges: {
            'Today': [moment(), moment()],
            'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Last 7 Days': [moment().subtract(6, 'days'), moment()],
            'This Week': [moment().startOf('isoWeek'), moment()],
            'Last 30 Days': [moment().subtract(29, 'days'), moment()],
            'This Month': [moment().startOf('month'), moment().endOf('month')],
            'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        },
        "startDate": moment().format("MM/DD/YYYY"),
        "endDate": moment().add(6, 'days').format("MM/DD/YYYY")
    }, function (start, end, label) {   
});

function load_table_data() {
    var datefilter = $('#daterange').val();
    var date = datefilter.split(" - ");
    $("#pageloader").fadeIn();
    $('#ajax_table_data').html('');
    $.ajax({
        url:  '{{ route('admin.rota.ajax.table') }}',
        dataType: 'html',
        data: {
            "_token": "{{ csrf_token() }}",
            "startDate": date[0],
            "endDate": date[1],
            "employee": $('#user_id').val(),
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

function date_range_change(i) {
    var datefilter = $('#daterange').val();
    var date = datefilter.split(" - ");
    var start_date = date[0];
    var end_date = date[1];

    if(i==1){
        var new_date_range = end_date +' - '+ moment(end_date).add(6,'days').format("MM/DD/YYYY"); 
        $('#daterange').val(new_date_range);
    }else{
        var new_date_range = moment(start_date).add(-6,'days').format("MM/DD/YYYY") +' - '+ start_date; 
        $('#daterange').val(new_date_range);
    }

    load_table_data();
}

$("#user_id").select2({
  placeholder: "select multiple employee",
  allowClear: false
});

$(document).ready(function () {
    $(document).on('submit','#popup-form-rota',function(e){
        e.preventDefault();
        var url = $(this).attr('action');
        $("#pageloader").fadeIn();
        $.ajax({
            method: "POST",
            url: url,
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            data: $(this).serialize(),
            success: function(message){
                if(typeof(message.success) != "undefined" && message.success !== null) {
                    $("#popup-modal").modal('hide');
                    var messageHtml = '<div class="alert alert-success alert-dismissible fade show" role="alert"><strong>Success: </strong> '+ message.success +' <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';
                    $('#message').html(messageHtml);
                    setTimeout(function() {   //calls click event after a certain time
                        load_table_data();
                        Swal.fire({ icon: 'Success', title: 'Success!', text: message.success})
                        $("#pageloader").hide();
                    }, 1000);
                } else if(typeof(message.error) != "undefined" && message.error !== null){
                    var messageHtml = '<div class="alert alert-danger alert-dismissible fade show" role="alert"><strong>Error: </strong> '+message.error+' <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';
                    $('#error_message').html(messageHtml);
                    setTimeout(function() {   //calls click event after a certain time
                        load_table_data();
                        $("#pageloader").hide();
                    }, 1000);
                }
            },
            error: function(message){
                if(typeof(message.responseJSON.errors) != "undefined" && message.responseJSON.errors !== null){
                    var errors = message.responseJSON.errors;
                    $.each(errors, function (key, val) {
                        var messageHtml = '<div class="alert alert-danger alert-dismissible fade show" role="alert"><strong>Error: </strong> '+val[0]+' <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';
                        $('#error_message').append(messageHtml);
                    });
                    
                    setTimeout(function() {   //calls click event after a certain time
                        load_table_data();
                        $("#pageloader").hide();
                    }, 1000);
                }
            },
        });
    }); 
});
</script>
@endsection


