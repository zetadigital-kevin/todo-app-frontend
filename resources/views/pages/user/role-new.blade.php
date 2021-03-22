
@extends('layouts/contentLayoutMaster')

@section('title', 'New Role')

@section('page-style')
{{-- Page Css files --}}
<link rel="stylesheet" href="{{ asset(mix('vendors/css/extensions/toastr.css')) }}">
@endsection

@section('content')
<section>
  <form onsubmit="return createRole();">
    <div class="row">
      <div class="col-md-12 col-12">
        <div class="card">
          <div class="card-header">
            <h4 class="card-title">Create New Role</h4>
          </div>
          <div class="card-content">
            <div class="card-body">
              <div class="row">
                <div class="col-12">
                  <label>Name</label>
                  <div class="input-group mb-75">
                    <input type="text" id="name" class="form-control" required>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-12">
                  <label>Description</label>
                  <div class="input-group mb-75">
                    <textarea type="text" id="description" class="form-control"></textarea>
                  </div>
                </div>
              </div>
              <div class="col-12 mt-3 text-right">
                <button id="create-role-button" type="submit" class="btn btn-primary mb-1">Create</button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </form>
</section>
@endsection

@section('page-script')
<script src="{{ asset(mix('vendors/js/extensions/toastr.min.js')) }}"></script>
<script>
    var createRoleButton = $('#create-role-button');
    function createRole(){
        createRoleButton.prop('disabled', true);
        var createRoleData = {
            'name': $('#name').val().toLowerCase(),
            'display_name': $('#display-name').val()
        };

        if($('#description').val()) {
            createRoleData.description = $('#description').val();
        }

        $.ajax({
            async: false,
            type: "POST",
            url: "{{env('TODO_LIST_API_' . env('TODO_LIST_API_MODE') . '_ENDPOINT')}}" + "/role/create",
            data: createRoleData,
            headers: {
                'Authorization': 'Bearer ' + JSON.parse(localStorage.getItem('user'))['access_token']
            }
        }).then((response) => {
            window.location.href = "{{url('/roles')}}" ;
            createRoleButton.prop('disabled', false);
        }).catch((error) => {
            if (error.status === 422) {
            for (let key in error.responseJSON.errors) {
                toastr.error(error.responseJSON.errors[key], 'Oops!', { positionClass: 'toast-top-center', containerId: 'toast-top-center' });
            }
          }
            else {
              toastr.error(error.responseJSON.message, 'Oops!', {positionClass: 'toast-top-center', containerId: 'toast-top-center' });
            }
            createRoleButton.prop('disabled', false);
        });
        return false;
    }
</script>
@endsection
