
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
                  <h4 class="card-title">Basic Information</h4>
               </div>
               <div class="card-content">
                  <div class="card-body">
                     <div class="row">
                        <div class="col-6">
                           <label>Username</label>
                           <div class="input-group mb-75">
                              <input type="text" id="username" class="form-control" required>
                           </div>
                        </div>
                        <div class="col-6">
                              <label>Given Name</label>
                              <div class="input-group mb-75">
                                 <input type="text" id="given-name" class="form-control" required>
                              </div>
                           </div>
                     </div>
                     <div>
                        <div class="row">
                           <div class="col-6">
                              <label>Family Name</label>
                              <div class="input-group mb-75">
                                 <input type="text" id="family-name" class="form-control" required>
                              </div>
                           </div>
                           <div class="col-6">
                              <label>Email</label>
                              <div class="input-group mb-75">
                                 <div class="input-group-prepend">
                                    <span class="input-group-text feather icon-mail"></span>
                                 </div>
                                 <input type="email" id="email" class="form-control">
                              </div>
                           </div>
                        </div>
                        <div class="row">
                           <div class="col-6">
                              <label>Mobile</label>
                              <div class="input-group mb-75">
                                 <div class="input-group-prepend">
                                    <span class="input-group-text feather icon-phone"></span>
                                 </div>
                                 <input type="text" id="mobile" class="form-control">
                              </div>
                           </div>
                        </div>
                        <div class="col-12 mt-3 text-right">
                           <button id="update-self-user-button" type="submit" class="btn btn-primary mb-1">Update</button>
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

@section('page-script')
<script src="{{ asset(mix('vendors/js/extensions/toastr.min.js')) }}"></script>

<script>
      $.ajax({
        async: false,
        type: "GET",
        url: "{{env('TODO_LIST_API_' . env('TODO_LIST_API_MODE') . '_ENDPOINT')}}" + "/role/list",
        headers: {
            'Authorization': 'Bearer ' + JSON.parse(localStorage.getItem('user'))['access_token']
        }
    }).then((response) => {
        response.forEach(role => {
            $('#role').append(`<option value="${role.id}">${role.display_name}</option>`);
        })
    }).catch((error) => {
        toastr.error(error.responseJSON.message, 'Oops!', { positionClass: 'toast-top-center', containerId: 'toast-top-center' });
    });

    $.ajax({
        async: false,
        type: "GET",
        url: "{{env('TODO_LIST_API_' . env('TODO_LIST_API_MODE') . '_ENDPOINT')}}" + "/administrator/get",
        headers: {
            'Authorization': 'Bearer ' + JSON.parse(localStorage.getItem('user'))['access_token']
        }
    }).then((response) => {
        $('#username').val(response.username);
        $('#given-name').val(response.given_name);
        $('#family-name').val(response.family_name);
        $('#mobile').val(response.mobile);
        $('#email').val(response.email);
    }).catch((error) => {
        toastr.error(error.responseJSON.message, 'Oops!', { positionClass: 'toast-top-center', containerId: 'toast-top-center' });
    });

    var updateSelfUserButton = $('#update-self-user-button');
    function updateUser(){
        updateSelfUserButton.prop('disabled', true);
        var updateUserData = {
          'username': $('#username').val().toLowerCase(),
          'given_name': $('#given-name').val().charAt(0).toUpperCase() + $('#given-name').val().substring(1).toLowerCase(),
          'family_name': $('#family-name').val().charAt(0).toUpperCase() + $('#family-name').val().substring(1).toLowerCase()
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
            url: "{{env('TODO_LIST_API_' . env('TODO_LIST_API_MODE') . '_ENDPOINT')}}" + "/administrator/update",
            data: updateUserData,
            headers: {
                'Authorization': 'Bearer ' + JSON.parse(localStorage.getItem('user'))['access_token']
            }
        }).then((response) => {
            toastr.success(response.message, 'Success!', { positionClass: 'toast-top-center', containerId: 'toast-top-center' });
            updateSelfUserButton.prop('disabled', false);
        }).catch((error) => {
        if (error.status === 422) {
            for (let key in error.responseJSON.errors) {
                toastr.error(error.responseJSON.errors[key], 'Oops!', { positionClass: 'toast-top-center', containerId: 'toast-top-center' });
            }
          }
          else {
            toastr.error(error.responseJSON.message, 'Oops!', { positionClass: 'toast-top-center', containerId: 'toast-top-center' });
          }
          updateSelfUserButton.prop('disabled', false);
        });
        return false;
    }
</script>
@endsection
