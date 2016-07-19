<?php 
Route::get('service', 'HomeController@index');
Route::get('about-us', 'HomeController@index');
Route::get('contact-us', 'HomeController@index');

//Route::get('results', 'HomeController@index');
//Route::get('results/clinic/{id}','HomeController@getClinicDetail');
//Route::get('results/doctor/{id}','HomeController@getDoctorDetail');
Route::get('result', 'HomeController@index');
Route::get('result/clinic/{id}','HomeController@getClinicDetail');
Route::get('result/doctor/{id}','HomeController@getDoctorDetail');
Route::get('search/autocomplete','HomeController@autocomplete');
Route::get('search/location','HomeController@searchlocation');
Route::get('VerifyLocation','HomeController@VerifyLocation');
Route::get('results/filter','HomeController@filter');
Route::get('terms-conditions','HomeController@index');
?>