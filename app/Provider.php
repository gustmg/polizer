<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Provider extends Model
{
    protected $table = 'providers';
    protected $primaryKey = 'provider_id';

    public function counterpart_account(){
        return $this->belongsTo('App\AccountingAccount','counterpart_accounting_account_id');
    }
}
