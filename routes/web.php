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

Route::get('/', function() {
    return view('welcome');
});

Route::get('/excel', 'ExcelController@export');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/excel_create', 'ExcelController@export')->name('excel_create');
//Rutas para objetos de datos
Route::resource('companies', 'CompanyController')->middleware('auth');
Route::resource('workspace', 'WorkspaceController')->middleware('auth');
Route::resource('providers', 'ProviderController')->middleware('auth');
Route::resource('clients', 'ClientController')->middleware('auth');
Route::resource('bank_accounts', 'BankAccountController')->middleware('auth');
Route::resource('accounting_accounts', 'AccountingAccountController')->middleware('auth');
//Rutas para generación de pólizas
Route::get('provision_policy', 'ProvisionPolicyController@index')->middleware('auth')->name('provision_policy');
Route::post('ajaxProvision', 'ProvisionPolicyController@handler');
Route::get('billing_policy', 'BillingPolicyController@index')->middleware('auth')->name('billing_policy');
Route::post('ajaxBilling', 'BillingPolicyController@handler');
Route::get('provider_payment_policy', 'ProviderPaymentPolicyController@index')->middleware('auth')->name('provider_payment_policy');
Route::post('ajaxProviderPayment', 'ProviderPaymentPolicyController@handler');
Route::get('client_deposit_policy', 'ClientDepositPolicyController@index')->middleware('auth')->name('client_deposit_policy');
Route::post('ajaxClientDeposit', 'ClientDepositPolicyController@handler');