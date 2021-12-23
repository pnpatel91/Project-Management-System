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
                    <h3 class="card-title">Edit Project</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <form action="{{ route('admin.project.update', ['project' => $project->id]) }}" method="put"  id="popup-form" >
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label>Name</label>
                            <input type="text" name="name" value="{{ $project->name }}" class="form-control" required autocomplete="name" autofocus maxlength="60">
                        </div>
                        <div class="form-group">
                            <label>Project Category &nbsp;</label><input type="checkbox" id="checkbox_user" > &nbsp;Select All
                            <select class="form-control select2" id="category_id" name="category_id" required autocomplete="category_id">
                                @foreach ($project_categories as $project_category)
                                    <option value="{{ $project_category->id }}" @if($project->category_id == $project_category->id) selected @endif>{{ $project_category->name }}</option>
                                @endforeach
                            </select>
                            <label id="select2-error" class="error" for="select2"></label>
                        </div>

                        <div class="form-group">
                            <label>Status</label>
                            <select class="form-control select2" id="status" name="status" required autocomplete="status">
                                <option value="Active" @if($user->status=='Active') selected="selected" @endif >Active</option>
                                <option value="Inactive" @if($user->status=='Inactive') selected="selected" @endif >Inactive</option>
                            </select>
                            <label id="select2-error" class="error" for="select2"></label>
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
        $('#popup-form').validate(
        {
            rules:{
              
            }
        }); //valdate end
    }); //function end

    $("#user_id").select2({
      placeholder: "Select users",
      allowClear: true
    });

     $("#checkbox_user").click(function(){
        if($("#checkbox_user").is(':checked') ){
            $('#user_id').select2('destroy').find('option').prop('selected', 'selected').end().select2({placeholder: "Select users",allowClear: true});
        }else{
            $('#user_id').select2('destroy').find('option').prop('selected', false).end().select2({placeholder: "Select users",allowClear: true});
        }
    });
     
    $("#status").select2({
      placeholder: "Select statu",
      allowClear: true
    });
</script>
@endsection