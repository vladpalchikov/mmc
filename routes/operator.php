<?php 

Route::get('/foreigners/oktmo', 'ForeignerController@getOktmo');
Route::get('/foreigners/inn', 'ForeignerController@getInn');
Route::get('/foreigners/findclient', 'ForeignerController@findclient');

Route::get('foreigners/my', 'ForeignerController@my');
Route::resource('foreigners', 'ForeignerController');
Route::resource('current', 'CurrentServicesController');
Route::resource('service', 'ServiceController');
Route::resource('group', 'GroupController');

Route::get('cashless', 'CashlessServicesController@index');
Route::get('cashless/pay', 'CashlessServicesController@pay');

Route::get('cash', 'CashServicesController@index');
Route::get('cash/pay', 'CashServicesController@pay');

Route::get('muservices/my', 'MUServiceController@my');
Route::get('muservices/info', 'MUServiceController@foreignerInfo');
Route::resource('muservices', 'MUServiceController');

Route::get('clients/find', 'ClientController@find');
Route::resource('clients', 'ClientController');
Route::get('clients/{client_id}/all', 'ClientController@allPayments');
Route::get('clients/{client_id}/transactions', 'ClientController@transactions');
Route::get('clients/{client_id}/transactions/delete', 'ClientController@transactionDelete');
Route::post('clients/{client_id}/addbalance', 'ClientController@addBalance');

Route::group(['prefix' => '/foreigners'], function() {
	Route::get('/{service_id}/servicepay', 'ForeignerController@servicePay');
	Route::get('/{service_id}/repayment', 'ForeignerController@repayment');
	Route::get('/{service_id}/repayment_print', 'ForeignerController@repaymentPrint');
	Route::get('/{service_id}/servicedelete', 'ForeignerController@serviceDelete');
	Route::get('/{id}/allservicespay', 'ForeignerController@allServicesPay');
	Route::get('/{id}/close', 'ForeignerController@close');
	Route::get('/{document}/print', 'ForeignerController@servicePrint');
	Route::get('/{id}/qrcode/{tax}/{foreignerQr?}', 'ForeignerController@qrcode');
	Route::get('/{foreigner_id}/qrprint/{tax}/{foreignerQr?}', 'ForeignerController@qrPrint');
	Route::get('/{foreigner_id}/qrreturn/{foreignerQr?}', 'ForeignerController@qrReturn');
	Route::get('/{foreigner_id}/qrreturn/{foreignerQr?}/print', 'ForeignerController@qrReturnPrint');
	Route::get('/{foreigner_id}/qrsave/{tax}', 'ForeignerController@qrSave');
	Route::post('/{foreigner_id}/storeservices', 'ForeignerController@storeServices');

	// Заявки у менеджера
	Route::group(['prefix' => '/{foreigner_id}', 'namespace' => 'Foreigners'], function() {
		Route::resource('patent', 'PatentController');
		Route::get('patent/{id}/registry', 'PatentController@registry');
		Route::resource('patentchange', 'PatentChangeController');
		Route::resource('patentrecertifying', 'PatentRecertifyingController');
		Route::get('patentrecertifying/{id}/registry', 'PatentRecertifyingController@registry');
		Route::resource('ig', 'IgController');
		Route::resource('dms', 'DmsController');
		Route::resource('blank', 'BlankController');
	});
});

Route::group(['prefix' => '/muservices'], function() {
	Route::get('/{service_id}/servicepay', 'MUServiceController@servicePay');
	Route::get('/{service_id}/repayment', 'MUServiceController@repayment');
	Route::get('/{id}/allservicespay', 'MUServiceController@allServicesPay');
	Route::get('/{id}/close', 'MUServiceController@close');
	Route::get('/{document}/print', 'MUServiceController@servicePrint');
});