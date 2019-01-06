<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('register','ApiController@register');
Route::post('login','ApiController@login');
Route::post('fblogin','ApiController@fblogin');
Route::post('fbregister','ApiController@fbregister');
Route::post('slider', 'Agentapi@slider');
Route::post('toutorial1', 'Agentapi@toutorial1');
Route::get('facebook_lead','ApiController@facebook_lead');
Route::post('proposal_settings', 'Agentapi@proposal_settings');
Route::group(['middleware' => 'auth:api'], function () {
    Route::get('events','ApiController@events');
    Route::get('logout','ApiController@logout');
    Route::get('properties','ApiController@properties');
    Route::get('favorite','ApiController@favorite');
    Route::get('favorite_project','ApiController@favorite_project');
    Route::get('add_interested','ApiController@add_interested');
    Route::get('project','ApiController@project');
    Route::get('resale_unit','ApiController@resale_unit');
    Route::get('project_view','ApiController@project_view');
    Route::get('resale_view','ApiController@resale_view');
    Route::get('saved_project','ApiController@saved_project');
    Route::get('saved_resale','ApiController@saved_resale');
    Route::get('resales','ApiController@resales');
    Route::get('rentals','ApiController@rentals');
    Route::get('rental_unit','ApiController@rental_unit');
    Route::get('rental_view','ApiController@rental_view');
    Route::get('saved_rental','ApiController@saved_rental');
    Route::get('contact_us','ApiController@contact_us');
    Route::get('about_us','ApiController@about_us');
    Route::get('search','ApiSearch@search');
    Route::post('search_result','ApiSearch@search_result');
    Route::get('get_region','ApiSearch@get_region');
    Route::get('vacation','ApiController@vacation');
    Route::post('refresh_token','ApiController@refresh');
    Route::get('notification','ApiController@notification');
    
});
Route::post('proposal_setting', 'Agentapi@proposal_setting');
Route::any('ios_properties','ApiController@ios_properties');
Route::get('region_filter','ApiSearch@region_filter');
Route::post('forget','ApiController@restpassword');
// Route::get('test','ApiController@vacation');
