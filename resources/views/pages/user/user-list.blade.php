@extends('layouts/contentLayoutMaster')

@section('title', 'Users')

@section('vendor-style')
  {{-- Page Css files --}}
  <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/ag-grid/ag-grid.css')) }}">
  <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/ag-grid/ag-theme-material.css')) }}">
@endsection

@section('page-style')
  {{-- Page Css files --}}
  <link rel="stylesheet" href="{{ asset(mix('vendors/css/extensions/toastr.css')) }}">
  <link rel="stylesheet" href="{{ asset(mix('css/pages/app-user.css')) }}">
  <link rel="stylesheet" href="{{ asset(mix('css/pages/aggrid.css')) }}">
@endsection

@section('content')
  <!-- users list start -->
  <section class="users-list-wrapper">
    <!-- Ag Grid users list section start -->
    <div id="basic-examples">
      <div class="card">
        <div class="card-content">
          <div class="card-body">
            <div class="row">
              <div class="col-12">
                <div class="ag-grid-btns d-flex justify-content-between flex-wrap mb-1">
                  <div class="dropdown sort-dropdown mb-1 mb-sm-0">
                    <button class="btn btn-white filter-btn dropdown-toggle border text-dark" type="button"
                            id="dropdownMenuButton6" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      1 - 20 of 50
                    </button>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton6">
                      <a class="dropdown-item" href="#">20</a>
                      <a class="dropdown-item" href="#">50</a>
                    </div>
                  </div>
                  <div class="ag-btns d-flex">
                    <input type="text" class="ag-grid-filter form-control w-100 mr-1 mb-1 mb-sm-0" id="filter-text-box"
                           placeholder="Search...." />
                    @if(in_array('administrator:create',session('permissions')))
                    <div class="action-btns w-100">
                      <a href="{{url('/users/new')}}" class="btn btn-flat-primary border-primary text-primary mr-1  mb-1 waves-effect waves-light">
                        New User
                      </a>
                    </div>
                    @endif
                  </div>
                </div>
              </div>
            </div>
            <div id="users-table" class="aggrid ag-theme-material"></div>
          </div>
        </div>
      </div>
    </div>
    <!-- Ag Grid users list section end -->
    <!-- Change user activated status modal start -->
    <div class="modal fade text-left" id="change-user-activation-status-modal" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
      <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
          <div class="modal-header bg-primary white">
            <h5 class="modal-title">Activate / Deactivate User Account?</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="resetActivatedSwitch()">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            Please confirm that you'd like to activate or deactivate selected user account?
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-dismiss="modal" onclick="changeUserActivationStatus()">Confirm</button>
          </div>
        </div>
      </div>
    </div>
    <!-- Change user activated status modal end -->
    <!-- Delete user modal start -->
    <div class="modal fade text-left" id="delete-user-modal" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
      <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
          <div class="modal-header bg-primary white">
            <h5 class="modal-title">Delete User Account?</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            Please confirm that you'd like to delete selected user account?
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-dismiss="modal" onclick="deleteUser()">Confirm</button>
          </div>
        </div>
      </div>
    </div>
    <!-- Delete user modal end -->
  </section>
  <!-- users list ends -->
@endsection

@section('vendor-script')
  {{-- Vendor js files --}}
  <script src="{{ asset(mix('vendors/js/tables/ag-grid/ag-grid-community.min.noStyle.js')) }}"></script>
  <script src="{{ asset(mix('vendors/js/extensions/toastr.min.js')) }}"></script>
@endsection

@section('page-script')
  {{-- Page js files --}}
  <script>
      // Change user activated status
      var superuserRoleIds = [1, 2];
      var changeUserActivationStatusModal = $('#change-user-activation-status-modal');
      function showChangeUserActivatedModal(id) {
          changeUserActivationStatusModal.modal('show');
          changeUserActivationStatusModal.data('user-id', id);
      }

      function changeUserActivationStatus() {
          let selectedUserId = $('#change-user-activation-status-modal').data('user-id');
          $.ajax({
              type: "PUT",
              url: "{{env('TODO_LIST_API_' . env('TODO_LIST_API_MODE') . '_ENDPOINT')}}" + "/administrator/activated/update/" + selectedUserId,
              headers: {
                  'Authorization': 'Bearer ' + JSON.parse(localStorage.getItem('user'))['access_token']
              }
          }).then((response) => {
              toastr.success(response.message, 'Success!', { positionClass: 'toast-top-center', containerId: 'toast-top-center' });
          }).catch((error) => {
              resetActivatedSwitch();
              toastr.error(error.responseJSON.message, 'Oops!', { positionClass: 'toast-top-center', containerId: 'toast-top-center' });
          });
      }

      function resetActivatedSwitch() {
          let selectedUserId = $('#change-user-activation-status-modal').data('user-id');
          let selectedUserActivated = $('#user-activated-' + selectedUserId);
          selectedUserActivated.prop('checked', !selectedUserActivated.is(":checked"));
      }

      // Delete user account
      var deleteUserModal = $('#delete-user-modal');
      function showDeleteUserModal(userId) {
          deleteUserModal.modal('show');
          deleteUserModal.data('user-id', userId);
      }

      function deleteUser() {
          let selectedUserId = $('#delete-user-modal').data('user-id');
          $.ajax({
              type: "DELETE",
              url: "{{env('TODO_LIST_API_' . env('TODO_LIST_API_MODE') . '_ENDPOINT')}}" + "/administrator/delete/" + selectedUserId,
              headers: {
                  'Authorization': 'Bearer ' + JSON.parse(localStorage.getItem('user'))['access_token']
              }
          }).then((response) => {
              toastr.success(response.message, 'Success!', { positionClass: 'toast-top-center', containerId: 'toast-top-center' });
              gridOptions.api.updateRowData({ remove: gridOptions.api.getSelectedRows() })
          }).catch((error) => {
              toastr.error(error.responseJSON.message, 'Oops!', { positionClass: 'toast-top-center', containerId: 'toast-top-center' });
          });
      }

      // Load users datatable
      var gridOptions = null;
      $(document).ready(function () {
          var isRtl;
          if ( $('html').attr('data-textdirection') == 'rtl' ) {
              isRtl = true;
          } else {
              isRtl = false;
          }

          //  Rendering date column
          var customDateHTML = function (params) {
              return params.value.replace('T', ' ').replace('+10:00', '').replace('+11:00', '');
          };

          //  Rendering name column
          var getAdministratorName = function (params) {
              return params.data.given_name + ' ' + params.data.family_name;
          };

          //  Rendering activated status column
          var getUserActivatedStatus = function (params) {
              if (params.data.activated == 0){
                  return 'Inactivated';
              }
              return 'Activated';
          };

          var changeUserActivatedPermissions = false;
          @if(in_array('administrator:activated:update',session('permissions')))
          changeUserActivatedPermissions = true;
          @endif

          var superuserRoleIds = [1, 2];
          var customActivatedHTML = function (params) {
              if (!superuserRoleIds.includes(params.data.roles[0].id) && changeUserActivatedPermissions){
                  return '<div class="custom-control custom-switch mt-1">' +
                         '    <input type="checkbox" class="custom-control-input change-user-activated" id="user-activated-' + params.data.id + '" '+ (params.value == "Activated" ? "checked" : "") +' onchange="showChangeUserActivatedModal(' + params.data.id + ')">' +
                         '    <label class="custom-control-label" for="user-activated-' + params.data.id + '">' +
                         '    </label>' +
                         '</div>';
              } else {
                  return "<div class='badge badge-pill badge-light-success' >Activated</div>";
              }
          };
          //  Rendering role column
          var customRoleHTML = function (params) {
              return params.data.roles[0].display_name;
          };

          // Renering Icons in Actions column
          var customActionsHTML = function (params) {
              if (!superuserRoleIds.includes(params.data.roles[0].id)) {
                  var usersIcons = document.createElement("span");

                  @if(in_array('administrator:update',session('permissions')))
                  var editIconHTML = "<a href='" + window.location.href + '/edit/' + params.data.id + "'><i class='users-edit-icon feather icon-edit-1 mr-50'></i></a>";
                  usersIcons.appendChild($.parseHTML(editIconHTML)[0]);
                  @endif

                  @if(in_array('administrator:delete',session('permissions')))
                  var deleteIconHTML = "<a  onclick='showDeleteUserModal(" + params.data.id + ")'><i class='users-edit-icon feather icon-trash-2 mr-50'></i></a>";
                  usersIcons.appendChild($.parseHTML(deleteIconHTML)[0]);
                  @endif

                  @if(in_array('administrator:view',session('permissions')))
                  var viewIconHTML = "<a href='" + window.location.href + '/view/' + params.data.id + "'><i class='users-edit-icon feather icon-eye mr-50'></i></a>";
                  usersIcons.appendChild($.parseHTML(viewIconHTML)[0]);
                  @endif
                  return usersIcons;
              }
          };

          // ag-grid
          /*** COLUMN DEFINE ***/
          var columnDefs = [
              {
                  headerName: 'Username',
                  field: 'username',
                  filter: true,
                  width: 200,
              },
              {
                  headerName: 'Name',
                  field: 'name',
                  valueGetter: getAdministratorName,
                  filter: true,
                  width: 225,
              },
              {
                  headerName: 'Mobile',
                  field: 'mobile',
                  filter: true,
                  width: 200,
              },
              {
                  headerName: 'Email',
                  field: 'email',
                  filter: true,
                  width: 280,
              },
              {
                  headerName: 'Role',
                  field: 'roles',
                  filter: true,
                  width: 200,
                  cellRenderer: customRoleHTML,
              },
              {
                  headerName: 'Created At',
                  field: 'created_at',
                  filter: true,
                  width: 240,
                  cellRenderer: customDateHTML,
              },
              {
                  headerName: 'Activated',
                  field: 'activated',
                  filter: true,
                  width: 180,
                  valueGetter: getUserActivatedStatus,
                  cellRenderer: customActivatedHTML,
              },
              {
                  headerName: 'Actions',
                  field: 'id',
                  width: 200,
                  cellRenderer: customActionsHTML,
              }
          ];

          /*** GRID OPTIONS ***/
          gridOptions = {
              defaultColDef: {
                  sortable: true
              },
              enableRtl: isRtl,
              columnDefs: columnDefs,
              rowSelection: "multiple",
              floatingFilter: true,
              filter: true,
              pagination: true,
              paginationPageSize: 20,
              pivotPanelShow: "always",
              colResizeDefault: "shift",
              animateRows: true,
              resizable: true
          };
          if (document.getElementById("users-table")) {
              /*** DEFINED TABLE VARIABLE ***/
              var userTable = document.getElementById("users-table");

              /*** GET TABLE DATA FROM URL ***/
              $.ajax({
                  type: "GET",
                  url: "{{env('TODO_LIST_API_' . env('TODO_LIST_API_MODE') . '_ENDPOINT')}}" + "/administrator/list",
                  headers: {
                      'Authorization': 'Bearer ' + JSON.parse(localStorage.getItem('user'))['access_token']
                  },
                  success: function(response)
                  {
                      gridOptions.api.setRowData(response);
                      gridOptions.api.sizeColumnsToFit();
                      gridOptions.api.setDomLayout('autoHeight');
                  },
                  error: function(){
                      toastr.error('Unable to load administrators data, please try again or contact Zeta support team!', 'Oops!', { positionClass: 'toast-top-center', containerId: 'toast-top-center' });
                  }
              });

              /*** FILTER TABLE ***/
              function updateSearchQuery(val) {
                  gridOptions.api.setQuickFilter(val);
              }

              $(".ag-grid-filter").on("keyup", function () {
                  updateSearchQuery($(this).val());
              });

              /*** CHANGE DATA PER PAGE ***/
              function changePageSize(value) {
                  gridOptions.api.paginationSetPageSize(Number(value));
              }

              $(".sort-dropdown .dropdown-item").on("click", function () {
                  var $this = $(this);
                  changePageSize($this.text());
                  $(".filter-btn").text("1 - " + $this.text() + " of 50");
              });

              /*** INIT TABLE ***/
              new agGrid.Grid(userTable, gridOptions);
          }
      });
  </script>
@endsection
