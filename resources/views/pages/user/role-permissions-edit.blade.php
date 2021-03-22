
@extends('layouts/contentLayoutMaster')

@section('title', 'Edit Role')

@section('page-style')
{{-- Page Css files --}}
<link rel="stylesheet" href="{{ asset(mix('vendors/css/extensions/toastr.css')) }}">
@endsection

@section('content')
<section>
  <form onsubmit="return updateRolePermissions();">
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-header">
            <h4 class="card-title">Edit Role Permissions</h4>
          </div>
          <div class="card-content">
            <div class="card-body">
              <div class="default-collapse collapse-bordered collapse-margin" id="permission-list">
              </div>
              <div class="col-12 mt-3 text-right">
                <button id="update-role-permissions-button" type="submit" class="btn btn-primary mb-1">Update</button>
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
    let selectedPermissions = [];
    $.ajax({
        async: false,
        type: "GET",
        url: "{{env('TODO_LIST_API_' . env('TODO_LIST_API_MODE') . '_ENDPOINT')}}" + "/role/permissions/list/" + (window.location.pathname.split("/").pop()),
        headers: {
            'Authorization': 'Bearer ' + JSON.parse(localStorage.getItem('user'))['access_token']
        }
    }).then((response) => {
        response.forEach(permission => {
            selectedPermissions.push(permission);
        })
    }).catch((error) => {
        toastr.error(error.responseJSON.message, 'Oops!', { positionClass: 'toast-top-center', containerId: 'toast-top-center' });
    });

    $.ajax({
        async: false,
        type: "GET",
        url: "{{env('TODO_LIST_API_' . env('TODO_LIST_API_MODE') . '_ENDPOINT')}}" + "/permission/list",
        headers: {
            'Authorization': 'Bearer ' + JSON.parse(localStorage.getItem('user'))['access_token']
        }
    }).then((response) => {
        let permissions =  {};
        response.forEach(permission => {
            let collectionName = permission.collection.split('&').join('and').split(' ').join('_');
            if(! (collectionName in permissions)) {
                permissions[collectionName] = [];
            }
            permissions[collectionName].push({
                id: permission.id,
                name: permission.name,
                display_name: permission.display_name,
                description: permission.description
            });
        });

        for (var collection in permissions) {
            $('#permission-list').append(
                '<div class="card collapse-header">' +
                '  <div id="collection-label-' + collection + '" class="card-header" data-toggle="collapse" role="button" data-target="#collection-control-' + collection + '"' +
                '       aria-expanded="false" aria-controls="collection-control-' + collection + '">' +
                '  <span class="lead collapse-title">' +
                collection.split('_').join(' ') +
                '  </span>' +
                '  </div>' +
                '  <div id="collection-control-' + collection + '" role="tabpanel" aria-labelledby="collection-label-' + collection + '" class="collapse">' +
                '    <div class="card-content">' +
                '      <div class="card-body" id="collection-body-' + collection + '">' +
                '      </div>' +
                '    </div>' +
                '  </div>' +
                '</div>'
            )
            permissions[collection].forEach(permission => {
                $('#collection-body-' + collection).append(
                    '<li class="d-block mr-2">' +
                    '  <fieldset>' +
                    '    <div class="vs-checkbox-con vs-checkbox-primary">' +
                    '      <input type="checkbox" name="permissions" ' + (selectedPermissions.includes(permission.name) ? " checked " : "") + 'value="' + permission.id + '">' +
                    '      <span class="vs-checkbox">' +
                    '        <span class="vs-checkbox--check">' +
                    '          <i class="vs-icon feather icon-check"></i>' +
                    '        </span>' +
                    '      </span>' +
                    '      <span class="">' + permission.display_name + '</span>' +
                    '    </div>' +
                    '  </fieldset>' +
                    '</li>'
                )
            })
        }
    }).catch((error) => {
        toastr.error(error.responseJSON.message, 'Oops!', { positionClass: 'toast-top-center', containerId: 'toast-top-center' });
    });

    var updateRolePermissionsButton = $('#update-role-permissions-button');
    function updateRolePermissions(){
        updateRolePermissionsButton.prop('disabled', true);
        let selectedPermissions = [];
        let selectedPermissionInput = $('input[name=permissions]:checked');
        for( var i = 0; i < selectedPermissionInput.length; i++) {
            selectedPermissions.push(parseInt(selectedPermissionInput[i].value));
        }
        var updateRoleData = {
            'permissions': selectedPermissions.join(',')
        };

        $.ajax({
            async: false,
            type: "PUT",
            url: "{{env('TODO_LIST_API_' . env('TODO_LIST_API_MODE') . '_ENDPOINT')}}" + "/role/permissions/update/" + (window.location.pathname.split("/").pop()),
            data: updateRoleData,
            headers: {
                'Authorization': 'Bearer ' + JSON.parse(localStorage.getItem('user'))['access_token']
            }
        }).then((response) => {
            toastr.success(response.message, 'Success!', { positionClass: 'toast-top-center', containerId: 'toast-top-center' });
            updateRolePermissionsButton.prop('disabled', false);
        }).catch((error) => {
            if (error.status === 422) {
            for (let key in error.responseJSON.errors) {
                toastr.error(error.responseJSON.errors[key], 'Oops!', { positionClass: 'toast-top-center', containerId: 'toast-top-center' });
            }
          }
          else {
                 toastr.error(error.responseJSON.message, 'Oops!', { positionClass: 'toast-top-center', containerId: 'toast-top-center' });
          }
          updateRolePermissionsButton.prop('disabled', false);
        });
        return false;
    }
</script>
@endsection
