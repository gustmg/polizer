<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use App\AccountingAccount;
use App\AccountingAccountType;
use App\BankAccount;
use App\Bank;
use App\Company;
use View;
use Session;

class BankAccountController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $bank_accounts=BankAccount::with('bank')->where('company_id', session()->get('company_workspace_id'))->get();
        $companies=Company::where('user_id', Auth::user()->id)->get();
        $accounting_account_types=AccountingAccountType::all();
        $banks=Bank::all();
        $accounting_accounts=AccountingAccount::where(function($query){
            $query->where('accounting_account_type_id', 1);
        })->where('company_id', session()->get('company_workspace_id'))->get();

        return view::make('bank_accounts.index',['bank_accounts'=>$bank_accounts,'companies'=>$companies,'accounting_accounts'=>$accounting_accounts,'accounting_account_types'=>$accounting_account_types,'banks'=>$banks]);
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
            'bank_account_number' => 'required|max:255',
            'bank_id' => 'required|max:255',
        ]);

        $bank_account = new BankAccount;
        $bank_account->bank_account_number=$request->bank_account_number;
        $bank_account->bank_id=$request->bank_id;
        $bank_account->counterpart_accounting_account_id=$request->counterpart_accounting_account_id;
        $bank_account->company_id=Session::get('company_workspace_id');

        $bank_account->save();

        return Redirect::to('bank_accounts');
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
            'bank_account_number' => 'required|max:255',
            'bank_id' => 'required|max:255',
            'counterpart_accounting_account_id' => 'required|max:255',
        ]);

        $bank_account = BankAccount::find($id);
        $bank_account->bank_account_number=$request->bank_account_number;
        $bank_account->bank_id=$request->bank_id;
        $bank_account->counterpart_accounting_account_id=$request->counterpart_accounting_account_id;
        $bank_account->company_id=Session::get('company_workspace_id');

        $bank_account->save();

        return Redirect::to('bank_accounts');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $bank_account=BankAccount::find($id);
        $bank_account->delete();
        return Redirect::to('bank_accounts');
    }
}
