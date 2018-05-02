<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BankAccount extends Model
{
    protected $table = 'bank_accounts';
    protected $primaryKey = 'bank_account_id';

    public function bank(){
    	return $this->belongsTo('App\Bank','bank_id');
    }

    public function counterpart_account(){
        return $this->belongsTo('App\AccountingAccount','counterpart_accounting_account_id');
    }
}
