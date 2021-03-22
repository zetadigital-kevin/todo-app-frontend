
@extends('layouts/contentLayoutMaster')

@section('title', 'View User')

@section('page-style')
{{-- Page Css files --}}
<link rel="stylesheet" href="{{ asset(mix('vendors/css/extensions/toastr.css')) }}">
@endsection

@section('content')
<section>
  <div class="row">
   <div class="col-12">
      <div class="card">
         <div class="card-header">
            <h4 class="card-title">View User Detail</h4>
         </div>
         <div class="card-content">
            <div class="card-body">
               <div class="row">
                  <div class="col-6">
                     <label>*Username</label>
                     <div class="input-group mb-75">
                        <input type="text" id="username" class="form-control" disabled>
                     </div>
                  </div>
                  <div class="col-6">
                      <label>*Role</label>
                      <div class="input-group mb-75">
                         <input type="text" id="role" class="form-control" disabled>
                      </div>
                  </div>
               </div>
               <div>
                  <div class="row">
                     <div class="col-6">
                        <label>*Given Name</label>
                        <div class="input-group mb-75">
                           <input type="text" id="given-name" class="form-control" disabled>
                        </div>
                     </div>
                     <div class="col-6">
                        <label>*Family Name</label>
                        <div class="input-group mb-75">
                           <input type="text" id="family-name" class="form-control" disabled>
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
                           <input type="email" id="email" class="form-control" disabled>
                        </div>
                     </div>
                     <div class="col-6">
                        <label>Mobile</label>
                        <div class="input-group mb-75">
                           <div class="input-group-prepend">
                              <span class="input-group-text feather icon-phone"></span>
                           </div>
                           <input type="tel" id="mobile" class="form-control" disabled>
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
               </div>
            </div>
         </div>
      </div>
   </div>
  </div>
</section>
<section>
      <div class="row">
          <div class="col-12">
              <div class="card">
                  <div class="card-header">
                      <h4 class="card-title">Activities</h4>
                  </div>
                  <div class="card-content">
                    <div class="card-body card-dashboard">
                        <div class="table-responsive">
                         <table id="activities-table" class="table zero-configuration">
                          <thead>
                              <tr>
                                  <th>DESCRIPTION</th>
                                  <th>CREATED AT</th>
                              </tr>
                          </thead>
                         </table>
                        </div>
                    </div>
                  </div>
              </div>
          </div>
      </div>
</section>

<section>
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-header">
          <h4 class="card-title">Tasks</h4>
        </div>
        <div class="card-content">
          <div class="card-body card-dashboard">
            <div class="table-responsive">
              <table id="tasks-table" class="table zero-configuration">
                <thead>
                <tr>
                  <th>TASK</th>
                  <th>DESCRIPTION</th>
                  <th>CREATED AT</th>
                  <th>UPDATED AT</th>
                  <th>ACTIONS</th>
                </tr>
                </thead>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection
@section('vendor-style')
        {{-- vendor css files --}}
        <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/datatables.min.css')) }}">
@endsection
@section('vendor-script')
  {{-- Vendor js files --}}
  <script src="{{ asset(mix('vendors/js/extensions/toastr.min.js')) }}"></script>
          <script src="{{ asset(mix('vendors/js/tables/datatable/pdfmake.min.js')) }}"></script>
        <script src="{{ asset(mix('vendors/js/tables/datatable/vfs_fonts.js')) }}"></script>
        <script src="{{ asset(mix('vendors/js/tables/datatable/datatables.min.js')) }}"></script>
        <script src="{{ asset(mix('vendors/js/tables/datatable/datatables.buttons.min.js')) }}"></script>
        <script src="{{ asset(mix('vendors/js/tables/datatable/buttons.html5.min.js')) }}"></script>
        <script src="{{ asset(mix('vendors/js/tables/datatable/buttons.print.min.js')) }}"></script>
        <script src="{{ asset(mix('vendors/js/tables/datatable/buttons.bootstrap.min.js')) }}"></script>
        <script src="{{ asset(mix('vendors/js/tables/datatable/datatables.bootstrap4.min.js')) }}"></script>
@endsection

@section('page-script')
<script src="{{ asset(mix('js/scripts/datatables/datatable.js')) }}"></script>
<script>
    var data;
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
        $('#role').val(response.roles[0].display_name);
        console.log(response.activities)

        $('#activities-table').DataTable({
            data: response.activities,
            columns: [
              { data: 'description' },
              { data: 'created_at' }
          ]
        });

   }).catch((error) => {
        toastr.error(error.responseJSON.message, 'Oops!', { positionClass: 'toast-top-center', containerId: 'toast-top-center' });
    });

</script>
<script>

  $.ajax({
    async: false,
    type: "GET",
    url: "{{env('TODO_LIST_API_' . env('TODO_LIST_API_MODE') . '_ENDPOINT')}}" + '/task/list/self',
    headers: {
      'Authorization': 'Bearer ' + JSON.parse(localStorage.getItem('user'))['access_token']
    },
    success: function (response) {
      tasks = response;
      response.forEach(function(task){
        newTaskParentSelect.append(`<option value="${task.id}">${task.title}</option>`);
      });
      $('#tasks-table').DataTable({
        data: response,
        columns: [
          {
            "mRender": function (data, type, row) {
              return `<fieldset>
                  <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" id="task-status-${row.id}"` + (row.status == 'Completed' ? "checked='checked'" : '') + `>
                    <label class="custom-control-label" for="task-status-${row.id}"></label>
                  </div>
                </fieldset>`;
            }
          },
          {
            "mData": 'title',
            "mRender": function(data,type,row) {
              return data;
            }
          },
          {data: 'description'},
          {
            "mData": "created_at",
            "mRender": function (data, type, row) {
              return dateStringToFormat(data);
            }
          },
          {
            "mData": "updated_at",
            "mRender": function (data, type, row) {
              return dateStringToFormat(data);
            }
          },
          {
            "mData": "id",
            "mRender": function (data, type, row) {
              return "<a id='edit-task-" + data + "' onclick='showEditTaskModal(" + data + ")'><i class='users-edit-icon feather icon-edit-1 mr-50'></i></a>" +
                "<a id='trash-task-" + data + "' onclick='showTrashTaskModal(" + data + ")'><i class='users-edit-icon feather icon-trash-2 mr-50'></i></a>";
            }
          },
        ],
        rowCallback: function(row, data, index) {
          if(data.status == 'Trash'){
            $(row).hide();
          }
        }
      });
      $('.table-responsive').css('display', 'block');
      $('#loading-spinner').css('display', 'none');
    },
    error: function (error) {
      alertError(error);
    }
  });
</script>
@endsection
