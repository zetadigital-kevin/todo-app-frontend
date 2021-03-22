
@extends('layouts/contentLayoutMaster')

@section('title', 'Edit User')

@section('page-style')
{{-- Page Css files --}}
<link rel="stylesheet" href="{{ asset(mix('vendors/css/extensions/toastr.css')) }}">
@endsection

@section('content')
<section>
   <form onsubmit="return updateUser();">
      <div class="row">
         <div class="col-12">
            <div class="card">
               <div class="card-header">
                  <h4 class="card-title">Edit User Detail</h4>
               </div>
               <div class="card-content">
                  <div class="card-body">
                     <div class="row">
                        <div class="col-6">
                           <label>*Username</label>
                           <div class="input-group mb-75">
                              <input type="text" id="username" class="form-control" required>
                           </div>
                        </div>
                        <div class="col-6">
                            <label>*Role</label>
                            <div class="input-group mb-75">
                               <select id="role" class="form-control" required></select>
                            </div>
                        </div>
                     </div>
                     <div>
                        <div class="row">
                           <div class="col-6">
                              <label>*Given Name</label>
                              <div class="input-group mb-75">
                                 <input type="text" id="given-name" class="form-control" required>
                              </div>
                           </div>
                           <div class="col-6">
                              <label>*Family Name</label>
                              <div class="input-group mb-75">
                                 <input type="text" id="family-name" class="form-control" required>
                              </div>
                           </div>
                        </div>
                        <div class="row">
                           <div class="col-6">
                              <label>Email</label>
                              <div class="input-group mb-75">
                                 <div class="input-group-prepend">
                                    <span class="input-group-text feather icon-mail"></span>
                                 </div>
                                 <input type="email" id="email" class="form-control">
                              </div>
                           </div>
                           <div class="col-6">
                              <label>Mobile</label>
                              <div class="input-group mb-75">
                                 <div class="input-group-prepend">
                                    <span class="input-group-text feather icon-phone"></span>
                                 </div>
                                 <input type="tel" id="mobile" class="form-control" placeholder="0412345678" pattern="[0]{1}[0-9]{9}">
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
                           <button id="update-user-button" type="submit" class="btn btn-primary mb-1">Update</button>
                        </div>
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
    var superuserRoleIds = [1, 2];

    var userRole = $('#role');
    function addRoleSelection(role) {
        if($('#role option[value="' + role.id + '"]').length === 0) {
            userRole.append(`<option value="${role.id}">${role.display_name}</option>`);
        } else {
            userRole.val(role.id);
        }
    }

    $.ajax({
        async: false,
        type: "GET",
        url: "{{env('TODO_LIST_API_' . env('TODO_LIST_API_MODE') . '_ENDPOINT')}}" + "/role/list",
        headers: {
            'Authorization': 'Bearer ' + JSON.parse(localStorage.getItem('user'))['access_token']
        }
    }).then((response) => {
        response.forEach(role => {
            if (!superuserRoleIds.includes(role.id)) {
                addRoleSelection(role);
            }
        });
    }).catch((error) => {
        toastr.error(error.responseJSON.message, 'Oops!', { positionClass: 'toast-top-center', containerId: 'toast-top-center' });
    });

    $.ajax({
        async: false,
        type: "GET",
        url: "{{env('TODO_LIST_API_' . env('TODO_LIST_API_MODE') . '_ENDPOINT')}}" + "/administrator/get/" + (window.location.pathname.split("/").pop()),
        headers: {
            'Authorization': 'Bearer ' + JSON.parse(localStorage.getItem('user'))['access_token']
        }
    }).then((response) => {
        $('#username').val(response.username);
        $('#given-name').val(response.given_name);
        $('#family-name').val(response.family_name);
        $('#mobile').val(response.mobile);
        $('#email').val(response.email);
        $('#created-at').val(response.created_at);
        $('#updated-at').val(response.updated_at);
        addRoleSelection(response.roles[0]);
    }).catch((error) => {
        toastr.error(error.responseJSON.message, 'Oops!', { positionClass: 'toast-top-center', containerId: 'toast-top-center' });
    });

    var updateUserButton = $('#update-user-button');
    function updateUser(){
        updateUserButton.prop('disabled', true);
        var updateUserData = {
          'username': $('#username').val().toLowerCase(),
          'given_name': $('#given-name').val().charAt(0).toUpperCase() + $('#given-name').val().substring(1).toLowerCase(),
          'family_name': $('#family-name').val().charAt(0).toUpperCase() + $('#family-name').val().substring(1).toLowerCase(),
          'role': $('#role').val()
        };

        if($('#mobile').val()) {
            updateUserData.mobile = $('#mobile').val();
        }
        if($('#email').val()) {
            updateUserData.email = $('#email').val().toLowerCase();
        }

        $.ajax({
            async: false,
            type: "PUT",
            url: "{{env('TODO_LIST_API_' . env('TODO_LIST_API_MODE') . '_ENDPOINT')}}" + "/administrator/update/" + (window.location.pathname.split("/").pop()),
            data: updateUserData,
            headers: {
                'Authorization': 'Bearer ' + JSON.parse(localStorage.getItem('user'))['access_token']
            }
        }).then((response) => {
            toastr.success(response.message, 'Success!', { positionClass: 'toast-top-center', containerId: 'toast-top-center' });
            updateUserButton.prop('disabled', false);
        }).catch((error) => {
          if (error.status === 422) {
            for (let key in error.responseJSON.errors) {
                toastr.error(error.responseJSON.errors[key], 'Oops!', { positionClass: 'toast-top-center', containerId: 'toast-top-center' });
            }
          }
          else {
            toastr.error(error.responseJSON.message, 'Oops!', { positionClass: 'toast-top-center', containerId: 'toast-top-center' });
          }
          updateUserButton.prop('disabled', false);
        });
        return false;
    }
</script>
@endsection
