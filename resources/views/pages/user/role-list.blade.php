@extends('layouts/contentLayoutMaster')

@section('title', 'Roles')

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
  <!-- roles list start -->
  <section class="users-list-wrapper">
    <!-- Ag Grid roles list section start -->
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
                  @if(in_array('role:create',session('permissions')))
                  <div class="action-btns w-100">
                      <a href="{{url('/roles/new')}}" class="btn btn-flat-primary border-primary text-primary mr-1 mb-1 waves-effect waves-light">
                        New Role
                      </a>
                    </div>
                  @endif
                  </div>
                </div>
              </div>
            </div>
            <div id="roles-table" class="aggrid ag-theme-material"></div>
          </div>
        </div>
      </div>
    </div>
    <!-- Delete role modal start -->
    <div class="modal fade text-left" id="delete-role-modal" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
      <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
          <div class="modal-header bg-primary white">
            <h5 class="modal-title">Delete Role Account?</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            Please confirm that you'd like to delete selected role?
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-dismiss="modal" onclick="deleteRole()">Confirm</button>
          </div>
        </div>
      </div>
    </div>
    <!-- Delete role modal end -->
  </section>
  <!-- roles list ends -->
@endsection

@section('vendor-script')
{{-- Vendor js files --}}
<script src="{{ asset(mix('vendors/js/tables/ag-grid/ag-grid-community.min.noStyle.js')) }}"></script>
<script src="{{ asset(mix('vendors/js/extensions/toastr.min.js')) }}"></script>
@endsection

@section('page-script')
  {{-- Page js files --}}
  <script>
      var superuserRoleIds = [1, 2];

      var deleteRoleModal = $('#delete-role-modal');
      function showDeleteRoleModal(roleId) {
          deleteRoleModal.modal('show');
          deleteRoleModal.data('role-id', roleId);
      }

      // Delete role
      function deleteRole() {
          let selectedRoleId = $('#delete-role-modal').data('role-id');
          $.ajax({
              type: "DELETE",
              url: "{{env('TODO_LIST_API_' . env('TODO_LIST_API_MODE') . '_ENDPOINT')}}" + "/role/delete/" + selectedRoleId,
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
          }

          // Renering Icons in Actions column
          var customActionsHTML = function (params) {
              var usersIcons = document.createElement("span");
              if(!superuserRoleIds.includes(params.data.id)) {
                  @if(in_array('role:update',session('permissions')))
                  var editIconHTML = "<a href='" + window.location.href + '/edit/' + params.data.id + "'><i class='users-edit-icon feather icon-edit-1 mr-50'></i></a>";
                  usersIcons.appendChild($.parseHTML(editIconHTML)[0]);
                  @endif

                  @if(in_array('role:permission:update',session('permissions')))
                  var editIconPermissionHTML = "<a href='" + window.location.href + '/permission/edit/' + params.data.id + "'><i class='users-edit-icon feather icon-lock mr-50'></i></a>";
                  usersIcons.appendChild($.parseHTML(editIconPermissionHTML)[0]);
                  @endif

                  @if(in_array('role:delete',session('permissions')))
                  var deleteIconHTML = "<a  onclick='showDeleteRoleModal(" + params.data.id + ")'><i class='users-edit-icon feather icon-trash-2 mr-50'></i></a>";
                  usersIcons.appendChild($.parseHTML(deleteIconHTML)[0]);
                @endif
              }

              @if(in_array('role:administrator:list',session('permissions')))
              var viewUsersIconHTML = "<a href='" + window.location.href + '/users/' + params.data.id + "'><i class='users-edit-icon feather icon-eye mr-50'></i></a>";
              usersIcons.appendChild($.parseHTML(viewUsersIconHTML)[0]);
              @endif

              return usersIcons
          }


          // ag-grid
          /*** COLUMN DEFINE ***/
          var columnDefs = [
              {
                  headerName: 'Role',
                  field: 'display_name',
                  filter: true,
                  width: 200,
              },
              {
                  headerName: 'Description',
                  field: 'description',
                  filter: true,
                  width: 400,
              },
              {
                  headerName: 'Created At',
                  field: 'created_at',
                  filter: true,
                  width: 240,
                  cellRenderer: customDateHTML,
              },
              {
                  headerName: 'Actions',
                  field: 'id',
                  width: 150,
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
          if (document.getElementById("roles-table")) {
              /*** DEFINED TABLE VARIABLE ***/
              var roleTable = document.getElementById("roles-table");
              /*** GET TABLE DATA FROM URL ***/
              $.ajax({
                  type: "GET",
                  url: "{{env('TODO_LIST_API_' . env('TODO_LIST_API_MODE') . '_ENDPOINT')}}" + "/role/list",
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
                      toastr.error('Unable to load roles data, please try again or contact Zeta support team!', 'Oops!', { positionClass: 'toast-top-center', containerId: 'toast-top-center' });
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
              new agGrid.Grid(roleTable, gridOptions);
          }
      });
  </script>
@endsection
