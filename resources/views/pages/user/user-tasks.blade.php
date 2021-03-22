@extends('layouts.contentLayoutMaster')

@section('title', 'User tasks')

@section('vendor-style')
  {{-- vendor css files --}}
  <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/datatables.min.css')) }}">
  <link rel="stylesheet" href="{{ asset(mix('vendors/css/forms/select/select2.min.css')) }}">
@endsection
@section('page-style')
  {{-- Page Css files --}}
  <link rel="stylesheet" href="{{ asset(mix('css/pages/app-user.css')) }}">
@endsection

@section('content')
  <!-- Tasks Table -->
  <section class="users-list-wrapper">
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-header">
            <h4 class="card-title">User tasks</h4>

          </div>
          <div class="card-content">
            <div class="card-body card-dashboard">
              <div class="text-center" id="loading-spinner">
                <div class="spinner-grow" style="width: 3rem; height: 3rem; text-align: center;" role="status">
                  <span class="sr-only">Loading...</span>
                </div>
              </div>
              <div class="table-responsive">
                <table id="tasks-table" class="table zero-configuration">
                  <thead>
                  <tr>
                    <th>STATUS</th>
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
    <!-- New Task Modal -->
    <div class="modal fade text-left" id="create-task-modal" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
      <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
          <div class="modal-header bg-primary white">
            <h5 class="modal-title">New Task</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="row">
              <div class="col-12">
                <label for="task-title">Task Title</label>
                <div class="input-group mb-75">
                  <input type="text" id="create-task-title" class="form-control" required>
                </div>
              </div>
              <div class="col-12">
                <label for="task-description">Description</label>
                <div class="input-group mb-75">
                  <input type="text" id="create-task-description" class="form-control" required>
                </div>
              </div>
              <div class="col-12">
                <label for="task-description">Parent Task</label>
                <div class="input-group mb-75">
                  <select type="text" id="create-task-parent" class="select2 form-control"><option></option></select>
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-dismiss="modal" onclick="createTask()">Create</button>
          </div>
        </div>
      </div>
    </div>

    <!-- Edit Task Modal -->
    <div class="modal fade text-left" id="edit-task-modal" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
      <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
          <div class="modal-header bg-primary white">
            <h5 class="modal-title">Edit Task</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="row">
              <div class="col-12">
                <label for="task-title">Task Title</label>
                <div class="input-group mb-75">
                  <input type="text" id="edit-task-title" class="form-control" required>
                </div>
              </div>
              <div class="col-12">
                <label for="task-description">Description</label>
                <div class="input-group mb-75">
                  <input type="text" id="edit-task-description" class="form-control" required>
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-dismiss="modal" onclick="editTask()">Update</button>
          </div>
        </div>
      </div>
    </div>

    <!-- Trash Task Modal -->
    <div class="modal fade text-left" id="trash-task-modal" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
      <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
          <div class="modal-header bg-primary white">
            <h5 class="modal-title">Change Status to Trash?</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            Please confirm that you'd like to move this task to trash?
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-dismiss="modal" onclick="trashTask()">Confirm</button>
          </div>
        </div>
      </div>
    </div>
  </section>

@endsection

    @section('vendor-script')
      {{-- Vendor js files --}}
      <script src="{{ asset(mix('vendors/js/tables/datatable/datatables.min.js')) }}"></script>
      <script src="{{ asset(mix('vendors/js/tables/datatable/datatables.bootstrap4.min.js')) }}"></script>
      <script src="{{ asset(mix('vendors/js/forms/select/select2.full.min.js')) }}"></script>
    @endsection

    @section('page-script')
      <script src="{{ asset(mix('js/scripts/common/format.js')) }}"></script>
      <script src="{{ asset(mix('js/scripts/forms/select/form-select2.js')) }}"></script>
      {{-- Page js files --}}
      <script>
        var tasks = [];
        var newTaskParentSelect = $('#create-task-parent');
        $('#loading-spinner').css('display', 'block');
        $('.table-responsive').css('display', 'none');
        $.ajax({
          async: false,
          type: "GET",
          url: "{{env('TODO_LIST_API_' . env('TODO_LIST_API_MODE') . '_ENDPOINT')}}" + '/task/list',
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
                            <input type="checkbox" onclick="updateTaskStatus(${row.id})" class="custom-control-input" id="task-status-${row.id}"` + (row.status == 'Completed' ? "checked='checked'" : '') + `>
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
      <script>
        var createTaskModal = $('#create-task-modal');
        $('#create-task-btn').on('click', function(){
          $('#create-task-title').val('');
          $('#create-task-description ').val('');
          createTaskModal.modal('show');
        })

        function createTask() {
          var createTaskData = {
            'title': $('#create-task-title').val(),
            'description': $('#create-task-description').val()
          };
          if (newTaskParentSelect.val() != null && newTaskParentSelect.val() != ''){
            createTaskData['parent_task_id'] = newTaskParentSelect.val();
          }

          $.ajax({
            type: "POST",
            url: "{{env('TODO_LIST_API_' . env('TODO_LIST_API_MODE') . '_ENDPOINT')}}" + '/task/create',
            headers: {
              'Authorization': 'Bearer ' + JSON.parse(localStorage.getItem('user'))['access_token']
            },
            data: createTaskData,
            success: function (response) {
              toastr.success('Task has been created successfully', 'Success!', { positionClass: 'toast-top-center', containerId: 'toast-top-center' });
              $('#tasks-table').DataTable().row.add(response).draw();
            },
            error: function (error) {
              alertError(error);
            }
          });
        }
      </script>
      <script>
        var editTaskModal = $('#edit-task-modal');

        function showEditTaskModal(taskId) {
          editTaskModal.modal('show');
          editTaskModal.data('id', taskId);
          var foundTask = false;
          tasks.forEach(function(task){
            if (task.id == taskId){
              $('#edit-task-title').val(task.title);
              $('#edit-task-description').val(task.description);
              foundTask = true;
              return false;
            }
          });
          if (!foundTask){
            toastr.error('Unable to find task!', 'ERROR!', { positionClass: 'toast-top-center', containerId: 'toast-top-center' });
          }
        }

        // Edit Task
        function editTask() {
          let selectedTaskId = editTaskModal.data('id');

          $.ajax({
            type: "PUT",
            url: "{{env('TODO_LIST_API_' . env('TODO_LIST_API_MODE') . '_ENDPOINT')}}" + '/task/update/' + selectedTaskId,
            headers: {
              'Authorization': 'Bearer ' + JSON.parse(localStorage.getItem('user'))['access_token']
            },
            data: {
              'title': $('#edit-task-title').val(),
              'description': $('#edit-task-description').val()
            },
            success: function (response) {
              toastr.success('Task has been updated successfully', 'Success!', { positionClass: 'toast-top-center', containerId: 'toast-top-center' });
              $('#tasks-table').DataTable().row($('#edit-task-' + selectedTaskId).parents('tr')).data(response).draw();
            },
            error: function (error) {
              alertError(error);
            }
          });
        }
      </script>
      <script>
        function updateTaskStatus(taskId) {
          var taskIndex = null
          for (var i = 0; i < tasks.length; i++){
            if (tasks[i].id == taskId){
              taskIndex = i;
            }
          }

          var currentStatus = tasks[taskIndex].status;
          $.ajax({
            type: "PUT",
            url: "{{env('TODO_LIST_API_' . env('TODO_LIST_API_MODE') . '_ENDPOINT')}}" + '/task/update/' + taskId,
            headers: {
              'Authorization': 'Bearer ' + JSON.parse(localStorage.getItem('user'))['access_token']
            },
            data: {
              'status': (currentStatus == "Not Completed") ? "Completed" : "Not Completed"
            },
            success: function (response) {
              toastr.success('Task status has been updated successfully', 'Success!', { positionClass: 'toast-top-center', containerId: 'toast-top-center' });
              tasks[taskIndex].status = response.status;
            },
            error: function (error) {
              alertError(error);
            }
          });
        }
      </script>
      <script>
        var trashTaskModal = $('#trash-task-modal');

        function showTrashTaskModal(taskId) {
          trashTaskModal.modal('show');
          trashTaskModal.data('id', taskId);
        }

        // Trash Task
        function trashTask() {
          let selectedTaskId = trashTaskModal.data('id');

          $.ajax({
            type: "PUT",
            url: "{{env('TODO_LIST_API_' . env('TODO_LIST_API_MODE') . '_ENDPOINT')}}" + '/task/update/' + selectedTaskId,
            headers: {
              'Authorization': 'Bearer ' + JSON.parse(localStorage.getItem('user'))['access_token']
            },
            data: {
              'status': 'Trash'
            },
            success: function (response) {
              toastr.success(response.message, 'Success!', { positionClass: 'toast-top-center', containerId: 'toast-top-center' });
              $('#tasks-table').DataTable().row($('#trash-task-' + selectedTaskId).parents('tr')).remove().draw();
            },
            error: function (error) {
              alertError(error);
            }
          });
        }
      </script>
@endsection
