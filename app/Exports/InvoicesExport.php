<?php
	namespace App\Exports;

	use Maatwebsite\Excel\Concerns\FromCollection;
	use App\User;

	class InvoicesExport implements FromCollection
	{
	    public function collection()
	    {	       
	    	$users= User::all(); 
	        return $users;
	    }
	}
?>