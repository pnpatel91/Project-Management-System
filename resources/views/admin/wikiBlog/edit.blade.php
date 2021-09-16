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
                    <h3 class="card-title">Edit Wiki Blog</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <form action="{{ route('admin.wikiBlog.update', ['wikiBlog' => $wikiBlog->id]) }}" method="put"  id="popup-form" >
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label>Title</label>
                            <input type="text" name="title" id="title" value="{{$wikiBlog->title}}" class="form-control" required autocomplete="title" autofocus maxlength="60">
                        </div>

                        <div class="form-group">
                            <label>Description</label>
                            <textarea id="description" name="description" class="form-control" required maxlength="5" autofocus>{{$wikiBlog->description}}</textarea>  
                        </div>

                        <div class="form-group">
                            <label>Wiki Categories</label>
                            <select class="form-control select2" id="category_id" name="category_id" required autocomplete="category_id" onchange="get_blog_by_category(this.value)">
                                <option value=""></option>
                                @foreach ($wikiCategories as $wikiCategory)
                                    <option value="{{ $wikiCategory->id }}" @if($wikiBlog->category_id == $wikiCategory->id) selected @endif>{{ $wikiCategory->name }}</option>
                                @endforeach
                            </select>
                            <label id="select2-error" class="error" for="select2"></label>
                        </div>

                        <div class="form-group">
                            <label>Parent</label>
                            <select class="form-control select2" id="parent_id" name="parent_id" autocomplete="parent_id">
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
    //CKEDITOR for description
    CKEDITOR.replace('description');

    // jQuery Validation
    $(function(){
        $('#popup-form').validate(
        {
            rules:{
              
            }
        }); //valdate end
    }); //function end

    

    get_blog_by_category({{$wikiBlog->category_id}});

    function get_blog_by_category(category_id) {
        $("#pageloader").fadeIn();
        $("#parent_id option").remove();
        
        var id = category_id;
        
        $.ajax({
          url : '{{ route('admin.wikiBlog.ajax.get_blog_by_category') }}',
          data: {
            "_token": "{{ csrf_token() }}",
            "id": id
            },
          type: 'post',
          dataType: 'json',
          success: function( result )
          {
            $('#parent_id').append($('<option>', {value:'', text:'Parent Blog'}));
            $.each( result, function(k, v) {
                if(k!={{$wikiBlog->id}}){
                    if(k=={{$wikiBlog->parent_id?$wikiBlog->parent_id:0}}){
                        $('#parent_id').append('<option selected value='+k+'>'+v+'</option>');
                    }else{
                        $('#parent_id').append('<option value='+k+'>'+v+'</option>'); 
                    }
                    
                }
            });

            $("#pageloader").hide();
          }
        });
    }


    $("#category_id").select2({
      placeholder: "select a wiki category",
      allowClear: false
    });

    $("#parent_id").select2({
      placeholder: "select a parent blog",
      allowClear: false
    });
</script>
@endsection