<?php
	namespace App\Exports;

	use Maatwebsite\Excel\Concerns\FromCollection;
	use App\Company;

	class InvoicesExport implements FromCollection
	{
	    public function collection()
	    {	       
	    	$users= Company::all(); 
	        return $users;
	    }
	}
?>