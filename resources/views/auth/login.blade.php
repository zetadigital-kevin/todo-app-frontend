@extends('layouts/fullLayoutMaster')

@section('title', 'Login')

@section('page-style')
{{-- Page Css files --}}
<link rel="stylesheet" href="{{ asset(mix('css/pages/authentication.css')) }}">
@endsection

@section('page-script')
<script>
  $('#error-msg').hide();
  $('#error-expired').hide();
  $('#error-invalid-identity').hide();

  @if(session('logout'))
  localStorage.removeItem('user');
  @endif

  @if(session('session-expired'))
  localStorage.removeItem('user');
  $('#error-msg').show();
  $('#error-expired').show();
  @endif

  authorizedUser = JSON.parse(window.localStorage.getItem('user'));
  if (authorizedUser != null){
    if (new Date() < new Date(authorizedUser['expires_at'])){
      window.location.href = "{{url('/')}}"
    }
    else {
      localStorage.removeItem('user');
      $('#error-msg').show();
      $('#error-expired').show();
    }
  }

  $("#login-form").submit(function(e) {
    e.preventDefault();
    $.ajax({
      type: 'POST',
      url: "{{env('TODO_LIST_API_' . env('TODO_LIST_API_MODE') . '_ENDPOINT')}}" + '/administrator/login',
      data: { username: $('#username').val(), password: $('#password').val() },
      success: function(response)
      {
        localStorage.setItem('user', JSON.stringify(response));
        $.ajax({
          type: "POST",
          url: "{{ url('login') }}",
          data: { _token: $('meta[name="csrf-token"]').attr('content'), access_token: response['access_token'], expires_at: response['expires_at'], permissions: response['permissions'] },
          success: function()
          {
            window.location.href = "{{url('/')}}"
          }
        });
      },
      error: function(response) {
        if (response.status == 401){
          $('#error-msg').show();
          $('#error-invalid-identity').show();
        }
      }
    });
  });
</script>
@endsection

@section('content')
<section class="row flexbox-container">
  <div class="col-xl-8 col-11 d-flex justify-content-center">
    <div class="card bg-authentication rounded-0 mb-0">
      <div class="row m-0">
        <div class="col-lg-6 d-lg-block d-none text-center px-0 py-0" style="overflow: hidden; background-size: cover; background-position: center; background-image: url({{ asset('images/pages/login-background.jpg') }});"></div>
        <div class="col-lg-6 col-12 p-0">
          <div class="card rounded-0 mb-0 px-2 h-100">
            <div class="card-header pb-1">
              <div class="card-title">
                <img src="{{ asset('images/logo/logo.png') }}" width="60%" alt="logo" class="mt-3 mb-2">
              </div>
            </div>
            <h4 class="px-2">Login</h4>
            <div class="card-content">
              <div class="card-body pt-1 mb-3">
                <div id="error-msg" class="alert alert-danger mb-2" role="alert">
                  <p class="mb-0" id="error-invalid-identity">Invalid username or password!</p>
                  <p class="mb-0" id="error-expired">Your login session has been expired, please login again!</p>
                </div>
                <form id="login-form">
                  @csrf
                  <fieldset class="form-label-group form-group position-relative has-icon-left">
                    <input id="username" class="form-control" name="username" placeholder="Username" required autofocus>
                    <div class="form-control-position">
                      <i class="feather icon-user"></i>
                    </div>
                    <label for="username">Username</label>
                  </fieldset>
                  <fieldset class="form-label-group position-relative has-icon-left">
                    <input id="password" type="password" class="form-control" name="password" placeholder="Password" required>
                    <div class="form-control-position">
                      <i class="feather icon-lock"></i>
                    </div>
                    <label for="password">Password</label>
                  </fieldset>
                  <button type="submit" class="btn btn-primary float-right btn-inline">Login</button>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection
