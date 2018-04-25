<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use App\Company;
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
        $companies=Company::all();
        return view::make('companies.index')->with('companies', $companies);
    }

    protected function create(array $data)
    {
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
        return Redirect::to('companies');
    }
}
