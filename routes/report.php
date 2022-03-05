<?php

Route::get('tm', 'TMReportController@index');
Route::get('tm/print', 'TMReportController@print');

Route::get('mu', 'MUReportController@index');
Route::get('mu/print', 'MUReportController@print');
Route::get('mu/export', 'MUReportController@export');

Route::get('muanalytics', 'MUAnalyticsReportController@index');
Route::get('muanalytics/print', 'MUAnalyticsReportController@print');
Route::get('muanalytics/export', 'MUAnalyticsReportController@export');

Route::get('summary', 'SummaryReportController@index');
Route::get('summary/print', 'SummaryReportController@print');
Route::get('summary/export', 'SummaryReportController@export');

Route::get('cashbox', 'CashboxReportController@index');
Route::get('cashbox/print', 'CashboxReportController@print');

Route::get('debit', 'DebitReportController@index');
Route::get('debit/print', 'DebitReportController@print');

Route::get('tax', 'TaxReportController@index');
Route::get('tax/print', 'TaxReportController@print');
Route::get('tax/unrecognize', 'TaxReportController@unrecognize');
Route::get('tax/unrecognize/link', 'TaxReportController@link');
Route::get('tax/unrecognize/parse', 'TaxReportController@parse');
Route::get('tax/unrecognize/save', 'TaxReportController@saveForeigner');

Route::get('payment', 'PaymentReportController@index');
Route::get('payment/print', 'PaymentReportController@print');

Route::get('manager', 'ManagerReportController@index');
Route::get('manager/print', 'ManagerReportController@print');

Route::get('refund', 'RefundReportController@index');
Route::get('refund/print', 'RefundReportController@print');

Route::get('host', 'HostReportController@index');
Route::get('host/print', 'HostReportController@print');
Route::get('host/export', 'HostReportController@export');

Route::get('registration', 'RegistrationReportController@index');
Route::get('registration/print', 'RegistrationReportController@print');
Route::get('registration/export', 'RegistrationReportController@export');

Route::get('blank', 'BlankReportController@index');
Route::get('blank/print', 'BlankReportController@print');

Route::get('registry', 'RegistryReportController@index');
Route::get('registry/print', 'RegistryReportController@print');
Route::get('registry/approve/{type}/{id}', 'RegistryReportController@approve');
Route::get('registry/remove/{type}/{id}', 'RegistryReportController@remove');

Route::get('journal', 'JournalReportController@index');
Route::get('journal/print', 'JournalReportController@print');
Route::get('journal/export', 'JournalReportController@export');

Route::get('document', 'DocumentReportController@index');
Route::get('document/print', 'DocumentReportController@print');