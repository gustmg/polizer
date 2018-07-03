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
use Route;

class ProviderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $providers=Provider::with('counterpart_account')->where('company_id', session()->get('company_workspace_id'))->paginate(10);
        $companies=Company::where('user_id', Auth::user()->id)->get();
        $accounting_account_types=AccountingAccountType::all();
        $accounting_accounts=AccountingAccount::where(function($query){
            $query->where('accounting_account_type_id', 2)
            ->orWhere('accounting_account_type_id', 5)
            ->orWhere('accounting_account_type_id', 6);
        })->where('company_id', session()->get('company_workspace_id'))->get();

        return view::make('providers.index',['providers'=>$providers,'companies'=>$companies,'accounting_accounts'=>$accounting_accounts,'accounting_account_types'=>$accounting_account_types]);
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
            'provider_name' => 'required|max:255',
            'provider_rfc' => 'required|max:255',
            'provider_accounting_account' => 'required|max:255',
        ]);

        if(!Provider::where([['provider_rfc','=',$request->provider_rfc],['company_id','=',Session::get('company_workspace_id')]])->exists()){
            $provider=new Provider;
            $provider->provider_name=$request->provider_name;
            $provider->provider_rfc=$request->provider_rfc;
            $provider->provider_accounting_account=$request->provider_accounting_account;
            $provider->company_id=Session::get('company_workspace_id');
            $provider->counterpart_accounting_account_id=$request->counterpart_accounting_account_id;
            $provider->save();
            return Redirect::to('providers');
        }
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
            'provider_name' => 'required|max:255',
            'provider_rfc' => 'required|max:255',
            'provider_accounting_account' => 'required|max:255',
        ]);

        $provider=Provider::find($id);
        $provider->provider_name=$request->provider_name;
        $provider->provider_rfc=$request->provider_rfc;
        $provider->provider_accounting_account=$request->provider_accounting_account;
        $provider->company_id=Session::get('company_workspace_id');
        $provider->counterpart_accounting_account_id=$request->counterpart_accounting_account_id;

        $provider->save();

        return Redirect::to('providers');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $provider=Provider::find($id);
        $provider->delete();
        return Redirect::to('providers');
    }
}
