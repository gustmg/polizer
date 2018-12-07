<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $table = 'clients';
    protected $primaryKey = 'client_id';

    public function counterpart_account(){
        return $this->belongsTo('App\AccountingAccount','counterpart_accounting_account_id');
    }

    public function bank(){
        return $this->belongsTo('App\Bank','bank_id');
    }
}
