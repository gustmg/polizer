<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exports\InvoicesExport;
use Excel;

class ExcelController extends Controller
{
    public function export() 
    {
    	$users=new InvoicesExport;
        return Excel::download($users, 'invoices.xlsx');
    }
}
