
@extends('layouts/contentLayoutMaster')

@section('title', 'Update Password')

@section('page-style')
{{-- Page Css files --}}
<link rel="stylesheet" href="{{ asset(mix('vendors/css/extensions/toastr.css')) }}">
@endsection

@section('content')
<section>
  <div class="card">
    <div class="card-content">
      <div class="card-body">
        <form onsubmit="return updatePassword();">
          <div class="row">
            <div class="col-12">
              <label>Old Password</label>
              <div class="input-group mb-75">
                <input type="password" id="old-password" class="form-control" placeholder="Please enter your old password" required>
              </div>
              <label>New Password</label>
              <div class="input-group mb-75">
                <input type="password" id="new-password" class="form-control" placeholder="Please enter your new password" minlength="6" required>
              </div>
              <label>Confirm New Password</label>
              <div class="input-group mb-75">
                <input type="password" id="confirm-password" class="form-control" placeholder="Please confirm your new password" minlength="6" required>
              </div>
            </div>
            <div class="col-12 mt-3 text-right">
              <button id="update-password-button" type="submit" class="btn btn-primary mb-1">Update</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</section>
@endsection

@section('page-script')
<script src="{{ asset(mix('vendors/js/extensions/toastr.min.js')) }}"></script>
<script>
    var updatePasswordButton = $('#update-password-button');
    function updatePassword(){
        if($('#confirm-password').val() !== $('#new-password').val()) {
            toastr.error('Please make sure you enter the same password', 'Password not the same!', { positionClass: 'toast-top-center', containerId: 'toast-top-center' });
            return false;
        }
        var updatePasswordData = {
            'password': $('#old-password').val(),
            'new_password': $('#new-password').val(),
            'new_password_confirmation': $('#confirm-password').val(),
        };

        $.ajax({
            type: "PUT",
            url: "{{env('TODO_LIST_API_' . env('TODO_LIST_API_MODE') . '_ENDPOINT')}}" + "/administrator/password/update",
            data: updatePasswordData,
            headers: {
                'Authorization': 'Bearer ' + JSON.parse(localStorage.getItem('user'))['access_token']
            }
        }).then((response) => {
            toastr.success(response.message, 'Success!', { positionClass: 'toast-top-center', containerId: 'toast-top-center' });
            updatePasswordButton.prop('disabled', true);
        }).catch((error) => {
            if (error.status === 422) {
              for (let key in error.responseJSON.errors) {
                  toastr.error(error.responseJSON.errors[key], 'Oops!', { positionClass: 'toast-top-center', containerId: 'toast-top-center' });
              }
          }
          else {
            toastr.error(error.responseJSON.message, 'Oops!', { positionClass: 'toast-top-center', containerId: 'toast-top-center' });
          }
          updatePasswordButton.prop('disabled', true);
        });
        return false;
    }
</script>
@endsection
