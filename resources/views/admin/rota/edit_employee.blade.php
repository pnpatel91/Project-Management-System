@extends('admin.layouts.popup')

@section('content')

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12" id="error_message">
            @include('message.alert')
        </div>
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h3 class="card-title">Edit Rota</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <form action="{{ route('admin.rota.update_employee', ['rota' => $rota->id]) }}" method="put"  id="popup-form-rota" >
                        @csrf
                        @method('PUT')
                        <div class="form-group mt-4">
                            <!-- <label>Employee</label> -->
                            <div class="user-add-shedule-list">
                                <h2 class="table-avatar">
                                    <a href="" class="avatar" tooltip="{{$user->name}}" flow="right"><img alt="" src="{{$user->getImageUrlAttribute($user->id)}}"></a>
                                    <a href="">{{$user->name}} <span>{{$user->departments[0]->name}}</span></a>
                                    <input type="hidden" name="employee_id" id="employee_id" value="{{$user->id}}" >
                                </h2>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Company - Branch :</label> {{ $rota->branch->name}}
                        </div>

                        <div class="form-group">
                            <label>Start Date & Time :</label> {{Carbon\Carbon::parse($rota->start_date)->format('Y-m-d')}} {{Carbon\Carbon::parse($rota->start_time)->format('H:i')}}
                        </div>

                        <div class="form-group">
                            <label>End Date & Time :</label> {{Carbon\Carbon::parse($rota->end_date)->format('Y-m-d')}} {{Carbon\Carbon::parse($rota->end_time)->format('H:i')}}
                        </div>

                        <div class="form-group">
                            <label>Max Start At :</label> {{Carbon\Carbon::parse($rota->max_start_time)->format('H:i')}}
                        </div>

                        <div class="form-group">
                            <label>Break Time :</label> {{$rota->break_time}} minutes
                        </div>

                        <div class="form-group">
                            <label>Location :</label> @if($rota->remotely_work=='No')
                                                {{$rota->branch->name}} - {{$rota->branch->address}}, {{$rota->branch->city}}, {{$rota->branch->state}}, {{$rota->branch->postcode}}, {{$rota->branch->country}}
                                            @else
                                                Remotely Work
                                            @endif
                        </div>

                        <div class="form-group">
                            <label>Over Time :</label> {{ $rota->over_time }}
                        </div>

                        <div class="form-group">
                            <label>Employer or Admin Notes :</label> {!!$rota->notes!!}
                        </div>

                        <div class="form-group">
                            <label>Your Notes</label>
                            <textarea id="employee_notes" name="employee_notes" class="form-control" required maxlength="5" autofocus>{{$rota->employee_notes}}</textarea>  
                        </div>
                        <button type="submit" class="btn btn-primary">Update</button>
                        <a href="" class="btn btn-secondary"  data-dismiss="modal">Close</a>
                    </form>
                </div>
                <!-- /.card-body -->
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    // jQuery Validation
    $(function(){
        $('#popup-form-rota').validate(
        {
            rules:{
              
            }
        }); //valdate end
    }); //function end

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
                        $("#navbar_current_Status").load(location.href+" #navbar_current_Status>*","");// after new attendance create. reload navbar_current_Status div
                        setTimeout(function() {   //calls click event after a certain time
                            load_table_data();
                            getLocationNavbar(); // after new attendance create. reload navbar_current_Status div
                            $("#pageloader").hide();
                        }, 1000);
                    } else if(typeof(message.error) != "undefined" && message.error !== null){
                        var messageHtml = '<div class="alert alert-danger alert-dismissible fade show" role="alert"><strong>Error: </strong> '+message.error+' <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';
                        $('#error_message').html(messageHtml);
                        setTimeout(function() {   //calls click event after a certain time
                            datatables();
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
                            datatables();
                            $("#pageloader").hide();
                        }, 1000);
                    }
                },
            });
        }); 
    });

    //CKEDITOR for notes
    CKEDITOR.replace( 'employee_notes' );
</script>
@endsection