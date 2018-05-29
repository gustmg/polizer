<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\AccountingAccount;
use App\AccountingAccountType;
use App\Company;
use App\Client;
use View;
use Session;
use Excel;

class BillingPolicyController extends Controller
{
    public function index(){
    	$clients=Client::with('counterpart_account')->where('company_id', session()->get('company_workspace_id'))->get();
    	$companies=Company::where('user_id', Auth::user()->id)->get();
    	$accounting_account_types=AccountingAccountType::all();
    	$accounting_accounts=AccountingAccount::where(function($query){
    	    $query->where('accounting_account_type_id', 3);
    	})->where('company_id', session()->get('company_workspace_id'))->get();
    	
    	return view::make('billing_policy.index',['clients'=>$clients,'companies'=>$companies,'accounting_accounts'=>$accounting_accounts,'accounting_account_types'=>$accounting_account_types]);
    }
}
