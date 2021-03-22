
@extends('layouts/contentLayoutMaster')

@section('title', 'New User')

@section('page-style')
{{-- Page Css files --}}
<link rel="stylesheet" href="{{ asset(mix('vendors/css/extensions/toastr.css')) }}">
@endsection

@section('content')
<section>
   <form onsubmit="return createUser();">
      <div class="row">
         <div class="col-12">
            <div class="card">
               <div class="card-header">
                  <h4 class="card-title">Create New User</h4>
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
                           <label>*Password</label>
                           <div class="input-group mb-75">
                              <input type="password" id="password" class="form-control" required>
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
                                 <input type="tel" id="mobile" class="form-control"  placeholder="0412345678" pattern="[0]{1}[0-9]{9}">
                              </div>
                           </div>
                        </div>
                        <div class="row">
                           <div class="col-6">
                              <label>*Role</label>
                              <div class="input-group mb-75">
                                 <select id="role" class="form-control" required></select>
                              </div>
                           </div>
                        </div>
                        <div class="col-12 mt-3 text-right">
                           <button id="create-user-button" type="submit" class="btn btn-primary mb-1">Create</button>
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
    var superuserRoleIds = [1, 2];
    $.ajax({
        type: "GET",
        url: "{{env('TODO_LIST_API_' . env('TODO_LIST_API_MODE') . '_ENDPOINT')}}" + "/role/list",
        headers: {
            'Authorization': 'Bearer ' + JSON.parse(localStorage.getItem('user'))['access_token']
        }
    }).then((response) => {
        response.forEach(role => {
            if (!superuserRoleIds.includes(role.id)) {
                $('#role').append(`<option value="${role.id}">${role.display_name}</option>`);
            }
        })
    }).catch((error) => {
        toastr.error(error.responseJSON.message, 'Oops!', { positionClass: 'toast-top-center', containerId: 'toast-top-center' });
    });

    var createUserButton = $('#create-user-button');
    function createUser(){
        createUserButton.prop('disabled', true);
        var createUserData = {
            'username': $('#username').val().toLowerCase(),
            'password': $('#password').val(),
            'given_name': $('#given-name').val().charAt(0).toUpperCase() + $('#given-name').val().substring(1).toLowerCase(),
            'family_name': $('#family-name').val().charAt(0).toUpperCase() + $('#family-name').val().substring(1).toLowerCase(),
            'role': $('#role').val()
        };

        if($('#mobile').val()) {
            createUserData.mobile = $('#mobile').val();
        }
        if($('#email').val()) {
            createUserData.email = $('#email').val().toLowerCase();
        }

        $.ajax({
            async: false,
            type: "POST",
            url: "{{env('TODO_LIST_API_' . env('TODO_LIST_API_MODE') . '_ENDPOINT')}}" + "/administrator/create",
            data: createUserData,
            headers: {
                'Authorization': 'Bearer ' + JSON.parse(localStorage.getItem('user'))['access_token']
            }
        }).then((response) => {
            window.location.href = "{{url('/users')}}" ;
            createUserButton.prop('disabled', false);
        }).catch((error) => {
          if (error.status === 422) {
            for (let key in error.responseJSON.errors) {
                toastr.error(error.responseJSON.errors[key], 'Oops!', { positionClass: 'toast-top-center', containerId: 'toast-top-center' });
            }
          }
            else {
              toastr.error(error.responseJSON.message, 'Oops!', { positionClass: 'toast-top-center', containerId: 'toast-top-center' });
            }
            createUserButton.prop('disabled', false);
        });
        return false;
    }
</script>
@endsection
