<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use App\AccountingAccount;
use App\AccountingAccountType;
use App\Company;
use App\Provider;
use View;
use Session;

class ProvisionPolicyController extends Controller
{
    public function index(){
    	$providers=Provider::with('counterpart_account')->where('company_id', session()->get('company_workspace_id'))->get();
    	$companies=Company::where('user_id', Auth::user()->id)->get();
    	$accounting_account_types=AccountingAccountType::all();
    	$accounting_accounts=AccountingAccount::where(function($query){
    	    $query->where('accounting_account_type_id', 2)
    	    ->orWhere('accounting_account_type_id', 5)
    	    ->orWhere('accounting_account_type_id', 6);
    	})->where('company_id', session()->get('company_workspace_id'))->get();
    	
    	return view::make('provision_policy.index',['providers'=>$providers,'companies'=>$companies,'accounting_accounts'=>$accounting_accounts,'accounting_account_types'=>$accounting_account_types]);
    }
}