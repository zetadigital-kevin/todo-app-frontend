<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
use Illuminate\Http\Request;

Route::get('/login', 'NavigationController@showLoginPage');
Route::post('/login', function (Request $request){
    session(['access_token' => $request->get('access_token'), 'expires_at' => $request->get('expires_at'), 'permissions' => $request->get('permissions')]);
});
Route::get('/error/error-403', function () {
        $pageConfigs = [
          'bodyClass' => "bg-full-screen-image",
          'blankPage' => true
      ];

      return view('/pages/error/error-403', [
          'pageConfigs' => $pageConfigs
      ]);
});
Route::group(['middleware' => ['web','login']], function () {
  // Route Dashboard
  Route::get('/', function (){
      return redirect('/dashboard');
  });
  Route::get('/dashboard', 'NavigationController@showDashboardPage');

  // Administrator Pages
  Route::get('/users', 'NavigationController@showUsersPage');
  Route::get('/users/new', 'NavigationController@showNewUserPage');
  Route::get('/users/edit/{administratorId}', 'NavigationController@showEditUserPage');
  Route::get('/users/view/{administratorId}', 'NavigationController@showViewUserPage');
  Route::get('/users/edit', 'NavigationController@showSelfEditUserPage');
  Route::get('/password/update', 'NavigationController@showEditUserPasswordPage');
  Route::get('/users/tasks/list','NavigationController@showUserTasksPage');
  Route::get('/logout', function (Request $request){
      $request->session()->flush();
      return redirect('/login')->with('logout', true);
  });

  // Role Pages
  Route::get('/roles', 'NavigationController@showRolesPage');
  Route::get('/roles/new', 'NavigationController@showNewRolePage');
  Route::get('/roles/edit/{roleId}', 'NavigationController@showEditRolePage');
  Route::get('/roles/permission/edit/{roleId}', 'NavigationController@showEditRolePermissionsPage');
  Route::get('/roles/users/{roleId}', 'NavigationController@showRoleUsersPage');
});
