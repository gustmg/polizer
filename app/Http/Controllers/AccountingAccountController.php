<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use App\AccountingAccount;
use View;
use App\Company;

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
        $companies=Company::all();
        return view::make('accounting_accounts.index',['accounting_accounts' => $accounting_accounts, 'companies'=>$companies]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $validatedData = $request->validate([
            'accounting_account_number' => 'required|max:255',
            'accounting_account_description' => 'required|max:255',
            'accounting_account_type_id' => 'required',
        ]);

        $accounting_account = New AccountingAccount;
        $accounting_account->accounting_account_number = $request->accounting_account_number;
        $accounting_account->accounting_account_description = $request->accounting_account_description;
        $accounting_account->company_id = $request->accounting_account_number;
        $accounting_account->accounting_account_type_id = $request->accounting_account_type_id;
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
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
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
    }
}
