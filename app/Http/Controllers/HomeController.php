<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Company;
use App\UserProcessedXml;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $companies=Company::where('user_id', Auth::user()->id)->get();
        $company_processed_xml=UserProcessedXml::where('company_id', session()->get('company_workspace_id'))->sum('xml_amount');
        return view('home')->with(['companies'=>$companies, 'company_processed_xml'=>$company_processed_xml]);
    }
}
