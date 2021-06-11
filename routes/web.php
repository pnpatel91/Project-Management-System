<?php

use Illuminate\Support\Facades\Route;

Auth::routes();

Route::get('/', 'HomeController@index')->name('home');

Route::group([
    'prefix' => 'admin',
    'as' => 'admin.',
    'namespace' => 'Admin',
    'middleware' => ['auth']
], function () {
    Route::view('/', 'admin.layouts.master');
    
    // Profile Routes
    Route::view('profile', 'admin.profile.index')->name('profile.index');;
    Route::view('profile/edit', 'admin.profile.edit')->name('profile.edit');
    Route::put('profile/edit', 'ProfileController@update')->name('profile.update');
    Route::put('profile/updateProfileImage', 'ProfileController@updateProfileImage')->name('profile.updateProfileImage');
    Route::view('profile/password', 'admin.profile.edit_password')->name('profile.edit.password');
    Route::post('profile/password', 'ProfileController@updatePassword')->name('profile.update.password');

    // User Routes
    Route::resource('/user', 'UserController');

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
    Route::resource('attendance', 'AttendanceController');
    Route::get('attendance/ajax/data', 'AttendanceController@datatables'); // For Datatables

});

