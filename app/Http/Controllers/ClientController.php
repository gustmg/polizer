<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use App\AccountingAccount;
use App\AccountingAccountType;
use App\Company;
use App\Client;
use App\Bank;
use View;
use Session;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $clients=Client::with('counterpart_account')->with('bank')->where('company_id', session()->get('company_workspace_id'))->get();
        $banks=Bank::all();
        $companies=Company::where('user_id', Auth::user()->id)->get();
        $accounting_account_types=AccountingAccountType::all();
        $accounting_accounts=AccountingAccount::where(function($query){
            $query->where('accounting_account_type_id', 3);
        })->where('company_id', session()->get('company_workspace_id'))->get();

        return view::make('clients.index',['clients'=>$clients,'companies'=>$companies,'accounting_accounts'=>$accounting_accounts,'accounting_account_types'=>$accounting_account_types, 'banks'=>$banks]);
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
            'client_name' => 'required|max:255',
            'client_rfc' => 'required|max:255',
            'client_accounting_account' => 'required|max:255',
        ]);

        $client=new Client;
        $client->client_name=$request->client_name;
        $client->client_rfc=$request->client_rfc;
        $client->client_accounting_account=$request->client_accounting_account;
        $client->company_id=Session::get('company_workspace_id');
        $client->counterpart_accounting_account_id=$request->counterpart_accounting_account_id;
        $client->bank_id=$request->bank_id;
        $client->client_bank_account_number=$request->client_bank_account_number;
        $client->save();

        return Redirect::to('clients');
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
            'client_name' => 'required|max:255',
            'client_rfc' => 'required|max:255',
            'client_accounting_account' => 'required|max:255',
        ]);

        $client=Client::find($id);
        $client->client_name=$request->client_name;
        $client->client_rfc=$request->client_rfc;
        $client->client_accounting_account=$request->client_accounting_account;
        $client->company_id=Session::get('company_workspace_id');
        $client->counterpart_accounting_account_id=$request->counterpart_accounting_account_id;
        $client->bank_id=$request->bank_id;
        $client->client_bank_account_number=$request->client_bank_account_number;
        $client->save();

        return Redirect::to('clients');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $client=Client::find($id);
        $client->delete();
        return Redirect::to('clients');
    }
}
