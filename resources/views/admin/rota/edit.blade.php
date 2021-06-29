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
                    <form action="{{ route('admin.rota.update', ['rota' => $rota->id]) }}" method="put"  id="popup-form-rota" >
                        @csrf
                        @method('PUT')
                        <div class="form-group mt-4">
                            <label>Employee</label>
                            <div class="user-add-shedule-list">
                                <h2 class="table-avatar">
                                    <a href="" class="avatar" tooltip="{{$user->name}}" flow="right"><img alt="" src="{{$user->getImageUrlAttribute($user->id)}}"></a>
                                    <a href="">{{$user->name}} <span>{{$user->departments[0]->name}}</span></a>
                                    <input type="hidden" name="employee_id" id="employee_id" value="{{$user->id}}" >
                                </h2>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Company - Branch</label>
                            <select class="form-control select2" id="branch_id" name="branch_id" required autocomplete="branch_id">
                                <option></option>
                                @foreach ($branches as $branch)
                                    <option value="{{ $branch->id }}" {{ $rota->branch_id ? 'selected="selected"' : '' }}>{{ $branch->company->name .' - '. $branch->name}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Start Date</label>
                            <input type='text' class="form-control datepicker" id='start_date' name="start_date" value="{{Carbon\Carbon::parse($rota->date)->format('Y-m-d')}}" required min={{date('Y-m-d')}} readonly />
                        </div>

                        <div class="form-group">
                            <label>Start At</label>
                            <input type='time' class="form-control" id='start_at' name="start_at" value="{{$rota->start_time}}" required />
                        </div>

                        <div class="form-group">
                            <label>Max Start At</label>
                            <input type='time' class="form-control" id='max_start_at' name="max_start_at" value="{{$rota->max_start_time}}" required />
                        </div>
                        <div class="form-group">
                            <label>End Date</label>
                            <input type='text' class="form-control datepicker" id='end_date' name="end_date" value="{{Carbon\Carbon::parse($rota->end_date)->format('Y-m-d')}}" required min={{date('Y-m-d')}} readonly />
                        </div>
                        <div class="form-group">
                            <label>End At</label>
                            <input type='time' class="form-control" id='end_at' name="end_at" value="{{$rota->end_time}}" required />
                        </div>

                        <div class="form-group">
                            <label>Break Time <span class="tooltipfontsize" tooltip="break time in minutes" flow="right"><i class="fas fa-info-circle"></i></span></label>
                            <input type='number' class="form-control" id='break_time' name="break_time" value="{{$rota->break_time}}" min="0" max="120" oninput="this.value = !!this.value && Math.abs(this.value) >= 0 ? Math.abs(this.value) : null" required />
                        </div>

                        <div class="form-group">
                            <label>Remotely Work</label>
                            <select class="form-control select2" id="remotely_work" name="remotely_work" required autocomplete="remotely_work">
                                <option></option>
                                @foreach ($remotely_work as $remotely_work)
                                    <option value="{{ $remotely_work }}" {{ $rota->remotely_work ? 'selected="selected"' : '' }}>{{ $remotely_work }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Over Time</label>
                            <select class="form-control select2" id="over_time" name="over_time" required autocomplete="over_time">
                                <option></option>
                                @foreach ($over_time as $over_time)
                                    <option value="{{ $over_time }}" {{ $rota->over_time ? 'selected="selected"' : '' }}>{{ $over_time }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Notes</label>
                            <textarea id="notes" name="notes" class="form-control" required maxlength="5" autofocus>{{$rota->notes}}</textarea>  
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
    CKEDITOR.replace( 'notes' );
</script>
@endsection