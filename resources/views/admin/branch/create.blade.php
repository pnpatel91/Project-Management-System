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
                    <h3 class="card-title">Create Branch</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <form action="{{ route('admin.branch.store') }}" method="post" id="popup-form" >
                        @csrf
                        <div class="form-group">
                            <label>Name</label>
                            <input type="text" name="name" class="form-control" required autocomplete="name" autofocus maxlength="60">
                        </div>
                        <div class="form-group">
                            <label>Address</label>
                            <input type="text" name="address" class="form-control" required autocomplete="address" maxlength="60">
                        </div>
                        <div class="form-group">
                            <label>State</label>
                            <input type="text" name="state" class="form-control" required autocomplete="state" maxlength="60">
                        </div>
                        <div class="form-group">
                            <label>City</label>
                            <input type="text" name="city" class="form-control" required autocomplete="city" maxlength="60">
                        </div>
                        <div class="form-group">
                            <label>Postcode</label>
                            <input type="text" name="postcode" class="form-control" required autocomplete="postcode" maxlength="60">
                        </div>
                        <div class="form-group">
                            <label>Country</label>
                            <input type="text" name="country" class="form-control" required autocomplete="country" maxlength="60">
                        </div>
                        <div class="form-group">
                            <label>Latitude <span class="tooltipfontsize" tooltip="Default: your current location latitude" flow="right"><i class="fas fa-info-circle"></i></span></label>
                            <input type="text" name="latitude" id="latitude" class="form-control" required autocomplete="latitude" maxlength="60" pattern="(\+|-)?(?:90(?:(?:\.0{1,6})?)|(?:[0-9]|[1-8][0-9])(?:(?:\.[0-9]{1,6})?))">
                        </div>
                        <div class="form-group">
                            <label>Longitude <span class="tooltipfontsize" tooltip="Default: your current location longitude" flow="right"><i class="fas fa-info-circle"></i></span></label>
                            <input type="text" name="longitude" id="longitude" class="form-control" required autocomplete="longitude" maxlength="60" pattern="^(\+|-)?(?:180(?:(?:\.0{1,6})?)|(?:[0-9]|[1-9][0-9]|1[0-7][0-9])(?:(?:\.[0-9]{1,6})?))$">
                        </div>
                        <div class="form-group">
                            <label>Radius <span class="tooltipfontsize" tooltip="Radius in metres" flow="right"><i class="fas fa-info-circle"></i></span></label>
                            <input type="number" step="any" name="radius" class="form-control" required autocomplete="radius">
                        </div>
                        <div class="form-group">
                            <label>Company</label>
                            <select class="form-control select2" id="company_id" name="company_id" required autocomplete="company_id">
                                @foreach ($company as $value)
                                    <option value="{{ $value->id }}">{{ $value->name }}</option>
                                @endforeach
                            </select>
                            <label id="select2-error" class="error" for="select2"></label>
                        </div>
                        <div class="form-group">
                            <label>Users</label>
                            <select class="form-control select2" id="user_id" name="user_id[]" required autocomplete="user_id" multiple>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                            <label id="select2-error" class="error" for="select2"></label>
                        </div>
                        <button type="submit" class="btn btn-primary">Create</button>
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

    $("#company_id").select2({
      placeholder: "Select a company",
      allowClear: true
    });

    $("#user_id").select2({
      placeholder: "Select users",
      allowClear: true
    });

    function getLocation() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                // Success function
                showPosition, 
                // Error function
                null, 
                // Options. See MDN for details.
                {
                   enableHighAccuracy: true,
                   timeout: 5000,
                   maximumAge: 0
                });
        } else { 
            x.innerHTML = "Geolocation is not supported by this browser.";
        }
    }

    function showPosition(position) {
        document.getElementById("latitude").value= position.coords.latitude;
        document.getElementById("longitude").value= position.coords.longitude;
    }

    getLocation();
</script>

@endsection