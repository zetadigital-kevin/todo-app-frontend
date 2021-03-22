
@extends('layouts/contentLayoutMaster')

@section('title', 'Edit Role')

@section('page-style')
{{-- Page Css files --}}
<link rel="stylesheet" href="{{ asset(mix('vendors/css/extensions/toastr.css')) }}">
@endsection

@section('content')
<section>
  <form onsubmit="return updateRole();">
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-header">
            <h4 class="card-title">Edit Role Detail</h4>
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
              <div class="row">
                <div class="col-6">
                  <label>Created At</label>
                  <div class="input-group mb-75">
                    <input type="text" id="created-at" class="form-control" disabled>
                  </div>
                </div>
                <div class="col-6">
                  <label>Updated At</label>
                  <div class="input-group mb-75">
                    <input type="text" id="updated-at" class="form-control" disabled>
                  </div>
                </div>
              </div>
              <div class="col-12 mt-3 text-right">
                <button id="update-role-button" type="submit" class="btn btn-primary mb-1">Update</button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </form>
</section>
@endsection

@section('vendor-script')
  {{-- Vendor js files --}}
  <script src="{{ asset(mix('vendors/js/extensions/toastr.min.js')) }}"></script>
@endsection

@section('page-script')
<script>
    $.ajax({
        async: false,
        type: "GET",
        url: "{{env('TODO_LIST_API_' . env('TODO_LIST_API_MODE') . '_ENDPOINT')}}" + "/role/get/" + (window.location.pathname.split("/").pop()),
        headers: {
            'Authorization': 'Bearer ' + JSON.parse(localStorage.getItem('user'))['access_token']
        }
    }).then((response) => {
        $('#name').val(response.name);
        $('#display-name').val(response.display_name);
        $('#description').val(response.description);
        $('#created-at').val(response.created_at);
        $('#updated-at').val(response.updated_at);
    }).catch((error) => {
        toastr.error(error.responseJSON.message, 'Oops!', { positionClass: 'toast-top-center', containerId: 'toast-top-center' });
    });

    var updateRoleButton = $('#update-role-button');
    function updateRole(){
        updateRoleButton.prop('disabled', true);
        var updateRoleData = {
            'name': $('#name').val().toLowerCase(),
            'display_name': $('#display-name').val()
        };

        if($('#description').val()) {
            updateRoleData.description = $('#description').val();
        }

        $.ajax({
            async: false,
            type: "PUT",
            url: "{{env('TODO_LIST_API_' . env('TODO_LIST_API_MODE') . '_ENDPOINT')}}" + "/role/update/" + (window.location.pathname.split("/").pop()),
            data: updateRoleData,
            headers: {
                'Authorization': 'Bearer ' + JSON.parse(localStorage.getItem('user'))['access_token']
            }
        }).then((response) => {
            toastr.success(response.message, 'Success!', { positionClass: 'toast-top-center', containerId: 'toast-top-center' });
            updateRoleButton.prop('disabled', false);
        }).catch((error) => {
          if (error.status === 422) {
            for (let key in error.responseJSON.errors) {
                toastr.error(error.responseJSON.errors[key], 'Oops!', { positionClass: 'toast-top-center', containerId: 'toast-top-center' });
            }
          }
          else {
            toastr.error(error.responseJSON.message, 'Oops!', { positionClass: 'toast-top-center', containerId: 'toast-top-center' });
          }
          updateRoleButton.prop('disabled', false);
        });
        return false;
    }
</script>
@endsection
