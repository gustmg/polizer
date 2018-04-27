<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Company;

class WorkspaceController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->session()->put('company_workspace_id',$request->company_workspace_id);
        $company = Company::find($request->company_workspace_id);
        $request->session()->put('company_workspace',$company->company_name);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if($request->session()->has('company_workspace'))
            echo $request->session()->get('company_workspace');
        else
            echo 'No data in the session';
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $request->session()->forget('company_workspace');
    }
}
