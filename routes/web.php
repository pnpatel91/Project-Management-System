<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NotificationController;

Auth::routes();

Route::get('/', 'HomeController@index')->name('home');

Route::group([
    'prefix' => 'admin',
    'as' => 'admin.',
    'namespace' => 'Admin',
    'middleware' => ['auth']
], function () {
    Route::get('/', 'DashboardController@index');
    //Notifications
    Route::view('notifications-dropdown-menu', 'admin.layouts.notifications')->name('notifications-dropdown-menu');
    Route::get('/notificationMarkAsRead/{id}', 'DashboardController@notificationMarkAsRead');
    Route::get('/notificationMarkAllAsRead/{id}', 'DashboardController@notificationMarkAllAsRead');

    // Profile Routes
    Route::view('profile', 'admin.profile.index')->name('profile.index');
    Route::view('profile/edit', 'admin.profile.edit')->name('profile.edit');
    Route::put('profile/edit', 'ProfileController@update')->name('profile.update');
    Route::put('profile/updateProfileImage', 'ProfileController@updateProfileImage')->name('profile.updateProfileImage');
    Route::view('profile/password', 'admin.profile.edit_password')->name('profile.edit.password');
    Route::post('profile/password', 'ProfileController@updatePassword')->name('profile.update.password');

    // User Routes
    Route::resource('/user', 'UserController');
    Route::post( 'user/ajax/users', 'UserController@get_users_by_branch' )->name('user.ajax.users'); // Get user option by branch in ajax

    // Role Routes
    Route::put('role/{id}/update', 'RoleController@update');
    Route::resource('role', 'RoleController');

    // Company Routes
    Route::resource('company', 'CompanyController');
    Route::get('company/ajax/data', 'CompanyController@datatables'); // For Datatables

    // Branch Routes
    Route::resource('branch', 'BranchController');
    Route::get('branch/ajax/data', 'BranchController@datatables'); // For Datatables

    // Attendance Routes
    Route::get('attendance/employee', 'AttendanceController@index_employee')->name('attendance.employee');
    Route::get('attendance/ajax/datatables_employee', 'AttendanceController@datatables_employee')->name('attendance.ajax.datatables_employee');
    Route::resource('attendance', 'AttendanceController');
    Route::post('attendance/store_admin', 'AttendanceController@store_admin')->name('attendance.store_admin'); // For Admin 
    Route::get('attendance/ajax/data', 'AttendanceController@datatables'); // For Datatables
    Route::post( 'attendance/ajax/status', 'AttendanceController@status' )->name('attendance.ajax.status'); // Get status option by branch in ajax

    

    // Department Routes
    Route::resource('department', 'DepartmentController');
    Route::get('department/ajax/data', 'DepartmentController@datatables'); // For Datatables

    // Department Routes
    Route::resource('holiday', 'HolidayController');

    // Leave - Admin Routes
    Route::resource('leave', 'LeaveAdminController');
    Route::get('leave/ajax/data', 'LeaveAdminController@datatables'); // For Datatables
    Route::get('leave/ajax/change_status', 'LeaveAdminController@change_status')->name('leave.ajax.change_status'); // For change status

    // Leave - Employee Routes
    Route::resource('leave-employee', 'LeaveEmployeeController');
    Route::get('leave-employee/ajax/data', 'LeaveEmployeeController@datatables'); // For Datatables

    // Rota Template Routes
    Route::get('rota_template/ajax/data', 'RotaTemplateController@datatables'); // For Datatables
    Route::get('rota_template/ajax/get_rota_template', 'RotaTemplateController@get_rota_template')->name('rota_template.ajax.get_rota_template');
    Route::get('rota_template/replicate/{rota_template}', 'RotaTemplateController@replicate')->name('rota_template.replicate');
    Route::resource('rota_template', 'RotaTemplateController');

    Route::get('rota/create_bulk', 'RotaController@create_bulk')->name('rota.create_bulk');
    Route::put('rota/store_bulk', 'RotaController@store_bulk')->name('rota.store_bulk');
    Route::get('rota/ajax/table', 'RotaController@table')->name('rota.ajax.table');
    Route::get('rota/create_single_rota/{user_id}/{date}', 'RotaController@create_single_rota')->name('rota.create_single_rota');
    Route::put('rota/store_single_rota', 'RotaController@store_single_rota')->name('rota.store_single_rota');
    Route::get('rota/employee', 'RotaController@index_employee')->name('rota.employee');
    Route::get('rota/ajax/table_employee', 'RotaController@table_employee')->name('rota.ajax.table_employee');
    Route::get('rota/edit_employee', 'RotaController@edit_employee')->name('rota.edit_employee');
    Route::put('rota/update_employee', 'RotaController@update_employee')->name('rota.update_employee');
    
    Route::get('rota/ajax/calendarRota', 'RotaController@calendarRota')->name('rota.ajax.calendarRota');
    Route::resource('rota', 'RotaController')->parameters(['rota' => 'rota']);



});

