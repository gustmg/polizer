<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use App\Company;
use App\Provider;
use App\AccountingAccount;
use App\Client;
use App\BankAccount;

use View;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $companies=Company::where('user_id', Auth::user()->id)->get();
        $providers=Provider::all();
        $clients=Client::all();
        $accounting_accounts=AccountingAccount::all();
        $bank_accounts=BankAccount::all();
        return view::make('companies.index', ['companies'=>$companies, 'providers'=>$providers, 'clients'=>$clients, 'accounting_accounts'=>$accounting_accounts, 'bank_accounts'=>$bank_accounts]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $validatedData = $request->validate([
            'company_name' => 'required|max:255',
        ]);

        $company = new Company;
        $company->company_name=$request->company_name;
        $company->company_rfc=$request->company_rfc;
        $company->pending_creditable_vat_account=$request->pending_creditable_vat_account;
        $company->paid_creditable_vat_account=$request->paid_creditable_vat_account;
        $company->transferred_vat_account=$request->transferred_vat_account;
        $company->charged_transferred_vat_account=$request->charged_transferred_vat_account;
        $company->fees_retention_isr_account=$request->fees_retention_isr_account;
        $company->fees_retention_vat_account=$request->fees_retention_vat_account;
        $company->freight_retention_vat_account=$request->freight_retention_vat_account;
        $company->user_id=Auth::user()->id;

        $company->save();

        return Redirect::to('companies');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $validatedData = $request->validate([
            'company_name' => 'required|max:255',
        ]);

        $company = Company::find($id);
        $company->company_name=$request->company_name;
        $company->company_rfc=$request->company_rfc;
        $company->pending_creditable_vat_account=$request->pending_creditable_vat_account;
        $company->paid_creditable_vat_account=$request->paid_creditable_vat_account;
        $company->transferred_vat_account=$request->transferred_vat_account;
        $company->charged_transferred_vat_account=$request->charged_transferred_vat_account;
        $company->fees_retention_isr_account=$request->fees_retention_isr_account;
        $company->fees_retention_vat_account=$request->fees_retention_vat_account;
        $company->freight_retention_vat_account=$request->freight_retention_vat_account;
        $company->user_id=Auth::user()->id;

        $company->save();

        if($request->session()->get('company_workspace_id')==$id){
            $request->session()->put('company_workspace',$request->company_name);
        }

        return Redirect::to('companies');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $company=Company::find($id);
        $company->delete();

        if(session()->get('company_workspace_id')==$id){
            session()->forget('company_workspace');
            session()->forget('company_workspace_id');
        }
        return Redirect::to('companies');
    }
}
