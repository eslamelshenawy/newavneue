<?php
Route::get('language/{lang}', 'HomeController@lang');
Route::group(['middleware' => ['lang','lead']], function () {
	Route::get('profile','LeadController@website_profile');
	Route::post('profile_update','LeadController@website_profile_update');
	Route::post('change_password','LeadController@change_password');
	Route::get('my_properties','LeadController@lead_properties');
	Route::get('add_properties','LeadController@add_properties');
	Route::post('add_property','HomeController@add_property');
	Route::get('favourite_properties','LeadController@favorite');

});
Route::get('lead_login',function(){
	return view('website.login2');
})->middleware('lang');
Route::group(['middleware' => ['lang']],function (){
	Route::get('/', 'HomeController@home');
	Route::post('check_login','LeadController@website_login');
	Route::post('add_lead','LeadController@add_lead');
	Route::get('logout','LeadController@website_logout');
	Route::get('project/{slug}','ProjectController@website_show');
	Route::get('resale/{slug}','ResaleUnitController@website_show');
	Route::get('rental/{slug}','RentalUnitController@website_show');
	Route::get('phase/{slug}','PhaseController@website_show_phase');
	Route::get('developer/{slug}','DeveloperController@website_show');
	Route::get('about','HomeController@about_page');
	Route::get('new-home-properties','HomeController@new_homes_properties');
	Route::get('resale-properties','HomeController@resale_properties');
	Route::get('favorite','HomeController@favorite');
	Route::get('rental-properties','HomeController@rental_properties');
	Route::get('new-home-commercial','HomeController@new_homes_commercial');
	Route::get('resale-commercial','HomeController@resale_commercial');
	Route::get('rental-commercial','HomeController@rental_commercial');
	Route::get('rental-commercial','HomeController@rental_commercial');
	Route::get('locations','LocationController@website_show');
	Route::get('news','HomeController@news');
	Route::get('news/{slug}','HomeController@single_news');
	Route::get('contact','HomeController@contact');
	Route::post('interested_lead','LeadController@interested');
	Route::post('newsletter','NewsLetterController@store');
	Route::get('send_massage','MassageController@store');
	Route::get('search', 'HomeController@search');

});
Route::post('get_unit_types', 'AjaxController@get_unit_types');

