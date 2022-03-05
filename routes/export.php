<?php 

Route::get('patent', 'PatentExportController@index');
Route::get('patent/export', 'PatentExportController@export');