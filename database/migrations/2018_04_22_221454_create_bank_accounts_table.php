<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBankAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bank_accounts', function (Blueprint $table) {
            $table->increments('bank_account_id');
            $table->integer('bank_account_number');
            $table->integer('bank_id')->unsigned();
            $table->integer('counterpart_accounting_account_id')->nullable()->unsigned();
            $table->integer('company_id')->unsigned();
            $table->timestamps();
        });

        Schema::table('bank_accounts', function (Blueprint $table) {
            $table->foreign('bank_id')->references('bank_id')->on('banks')->onDelete('restrict')->onUpdate('cascade');
            $table->foreign('counterpart_accounting_account_id')->references('accounting_account_id')->on('accounting_accounts')->onDelete('set null')->onUpdate('cascade');
            $table->foreign('company_id')->references('company_id')->on('companies')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bank_accounts');
    }
}
