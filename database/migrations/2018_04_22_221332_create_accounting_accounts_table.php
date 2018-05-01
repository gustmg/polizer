<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAccountingAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('accounting_accounts', function (Blueprint $table) {
            $table->increments('accounting_account_id');
            $table->string('accounting_account_number');
            $table->string('accounting_account_description');
            $table->integer('company_id')->unsigned();
            $table->integer('accounting_account_type_id')->unsigned();
            $table->timestamps();
        });

        Schema::table('accounting_accounts', function (Blueprint $table) {
            $table->foreign('company_id')->references('company_id')->on('companies')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('accounting_account_type_id')->references('accounting_account_type_id')->on('accounting_account_types')->onDelete('restrict')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('accounting_accounts');
    }
}
