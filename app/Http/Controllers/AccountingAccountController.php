<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use App\AccountingAccount;
use App\AccountingAccountType;
use App\Company;
use View;
use Session;

class AccountingAccountController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $accounting_accounts=AccountingAccount::where('company_id', session()->get('company_workspace_id'))->get();
        $companies=Company::where('user_id', Auth::user()->id)->get();
        $accounting_account_types=AccountingAccountType::all();
        return view::make('accounting_accounts.index',['accounting_accounts' => $accounting_accounts, 'companies' => $companies, 'accounting_account_types' => $accounting_account_types]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'accounting_account_number' => 'required|max:255',
            'accounting_account_description' => 'required|max:255',
            'accounting_account_type_id' => 'required',
        ]);

        $accounting_account = New AccountingAccount;
        $accounting_account->accounting_account_number = $request->accounting_account_number;
        $accounting_account->accounting_account_description = $request->accounting_account_description;
        $accounting_account->company_id = Session::get('company_workspace_id');
        $accounting_account->accounting_account_type_id = $request->accounting_account_type_id;

        $accounting_account->save();

        return Redirect::to('accounting_accounts');
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
        $validatedData = $request->validate([
            'accounting_account_number' => 'required|max:255',
            'accounting_account_description' => 'required|max:255',
            'accounting_account_type_id' => 'required',
        ]);

        $accounting_account = AccountingAccount::find($id);
        $accounting_account->accounting_account_number = $request->accounting_account_number;
        $accounting_account->accounting_account_description = $request->accounting_account_description;
        $accounting_account->company_id = Session::get('company_workspace_id');
        $accounting_account->accounting_account_type_id = $request->accounting_account_type_id;

        $accounting_account->save();

        
        return Redirect::to('accounting_accounts');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $accounting_account=AccountingAccount::find($id);
        $accounting_account->delete();

        return Redirect::to('accounting_accounts');
    }
}
