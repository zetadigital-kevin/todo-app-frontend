<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NavigationController extends Controller
{
  public function showLoginPage(){
    $pageConfigs = [
      'blankPage' => true
    ];

    return view('/auth/login', [
      'pageConfigs' => $pageConfigs
    ]);
  }

  public function showDashboardPage(){
    $pageConfigs = [
      'pageHeader' => false
    ];

    return view('/pages/dashboard', [
      'pageConfigs' => $pageConfigs
    ]);
  }

  public function showEditUserPasswordPage(){
    if(!in_array('administrator:self:password:update', session('permissions'))) {
      return redirect('/error/error-403');
    }

    $breadcrumbs = [
      ['link'=>url('/dashboard'), 'name'=>"Home"], ['name'=>"Update Password"]
    ];

    return view('/pages/user/user-password-edit', [
      'breadcrumbs' => $breadcrumbs
    ]);
  }

  public function showUsersPage(){
    if(!in_array('administrator:list', session('permissions'))) {
      return redirect('/error/error-403');
    }

    $breadcrumbs = [
      ['link'=>url('/dashboard'), 'name'=>"Home"], ['link'=>url('/users'), 'name'=>"Users"]
    ];

    return view('/pages/user/user-list', [
      'breadcrumbs' => $breadcrumbs
    ]);
  }

  public function showNewUserPage(){
    if(!in_array('administrator:create', session('permissions'))) {
     return redirect('/error/error-403');
    }

    $breadcrumbs = [
      ['link'=>url('/dashboard'), 'name'=>"Home"], ['link'=>url('/users'), 'name'=>"Users"], ['name'=>"New User"]
    ];

    return view('/pages/user/user-new', [
      'breadcrumbs' => $breadcrumbs
    ]);
  }

  public function showViewUserPage(){
    if(!in_array('administrator:view', session('permissions'))) {
      return redirect('/error/error-403');
    }

    $breadcrumbs = [
      ['link'=>url('/dashboard'), 'name'=>"Home"], ['link'=>url('/users'), 'name'=>"Users"], ['name'=>"View User"]
    ];

    return view('/pages/user/user-view', [
      'breadcrumbs' => $breadcrumbs
    ]);
  }

  public function showEditUserPage(){
    if(!in_array('administrator:update', session('permissions'))) {
      return redirect('/error/error-403');
    }

    $breadcrumbs = [
      ['link'=>url('/dashboard'), 'name'=>"Home"], ['link'=>url('/users'), 'name'=>"Users"], ['name'=>"Edit User"]
    ];

    return view('/pages/user/user-edit', [
      'breadcrumbs' => $breadcrumbs
    ]);
  }

  public function showSelfEditUserPage(){
    if(!in_array('administrator:self:update', session('permissions'))) {
      return redirect('/error/error-403');
    }

    $breadcrumbs = [
      ['link'=>url('/dashboard'), 'name'=>"Home"], ['link'=>url('/users'), 'name'=>"Users"], ['name'=>"Edit Self User"]
    ];

    return view('/pages/user/user-self-edit', [
      'breadcrumbs' => $breadcrumbs
    ]);
  }

  public function showRolesPage(){
    if(!in_array('role:list', session('permissions'))) {
      return redirect('/error/error-403');
    }

    $breadcrumbs = [
      ['link'=>url('/dashboard'), 'name'=>"Home"], ['link'=>url('/roles'), 'name'=>"Roles"]
    ];

    return view('/pages/user/role-list', [
      'breadcrumbs' => $breadcrumbs
    ]);
  }

  public function showNewRolePage(){
    if(!in_array('role:create', session('permissions'))) {
      return redirect('/error/error-403');
    }

    $breadcrumbs = [
      ['link'=>url('/dashboard'), 'name'=>"Home"], ['link'=>url('/roles'), 'name'=>"Roles"], ['name'=>"New Role"]
    ];

    return view('/pages/user/role-new', [
      'breadcrumbs' => $breadcrumbs
    ]);
  }

  public function showEditRolePage(){
    if(!in_array('role:update', session('permissions'))) {
      return redirect('/error/error-403');
    }

    $breadcrumbs = [
      ['link'=>url('/dashboard'), 'name'=>"Home"], ['link'=>url('/roles'), 'name'=>"Roles"], ['name'=>"Edit Role"]
    ];

    return view('/pages/user/role-edit', [
      'breadcrumbs' => $breadcrumbs
    ]);
  }

  public function showEditRolePermissionsPage(){
    if(!in_array('role:permission:list', session('permissions'))) {
      return redirect('/error/error-403');
    }

    $breadcrumbs = [
      ['link'=>url('/dashboard'), 'name'=>"Home"], ['link'=>url('/roles'), 'name'=>"Roles"], ['name'=>"Edit Role Permission"]
    ];

    return view('/pages/user/role-permissions-edit', [
      'breadcrumbs' => $breadcrumbs
    ]);
  }

  public function showRoleUsersPage(){
    if(!in_array('role:administrator:list', session('permissions'))) {
      return redirect('/error/error-403');
    }

    $breadcrumbs = [
      ['link'=>url('/dashboard'), 'name'=>"Home"], ['link'=>url('/roles'), 'name'=>"Roles"], ['name'=>"Role Users"]
    ];

    return view('/pages/user/role-user-list', [
      'breadcrumbs' => $breadcrumbs
    ]);
  }
}
