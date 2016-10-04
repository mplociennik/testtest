<?php

/*
  |--------------------------------------------------------------------------
  | Application Routes
  |--------------------------------------------------------------------------
  |
  | Here is where you can register all of the routes for an application.
  | It's a breeze. Simply tell Laravel the URIs it should respond to
  | and give it the controller to call when that URI is requested.
  |
 */


Route::group(['middleware' => ['adlogin']], function () {
    Route::get('/', "\App\Http\Controllers\CicCore\CicController@index");
    Route::get('/cic/user', "\App\Http\Controllers\CicCore\CicController@user");
    Route::get('/cic/user-permissions/{userid}', "\App\Http\Controllers\CicCore\PermissionController@getAllPermissionsByUserId");
    Route::get('/cic/user-roles/{userid}', "\App\Http\Controllers\CicCore\RoleController@getAllRolesByUserId");
    Route::get('/cic/roles', "\App\Http\Controllers\CicCore\RoleController@getAllRolesWithPermissionsChecked");
    Route::resource('/session', "\App\Http\Controllers\CicCore\SessionController", [
        'only' => ['index']
    ]);
    Route::resource('/user', "\App\Http\Controllers\CicCore\UserController", [
        'only' => ['index']
    ]);
    Route::resource('/permissions-groups', "\App\Http\Controllers\CicCore\GroupController", [
        'only' => ['index']
    ]);
    Route::post('/store-user-roles', "\App\Http\Controllers\CicCore\UserController@storeUserRoles");
    Route::post('/store-user-permissions', "\App\Http\Controllers\CicCore\UserController@storeUserPermissions");
    Route::post('/store-role-permissions', "\App\Http\Controllers\CicCore\RoleController@storeRolePermissions");
});
Route::get('/social/google/', "Auth\LoginController@googleRedirect");
Route::get('/social/google/callback', "Auth\LoginController@googleLogin");

Route::get('/billings/crone', 'Billings\BillingsCroneController@index');
