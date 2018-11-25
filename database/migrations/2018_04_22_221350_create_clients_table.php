<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->increments('client_id');
            $table->string('client_name');
            $table->string('client_rfc');
            $table->string('client_accounting_account');
            $table->integer('company_id')->unsigned();
            $table->integer('counterpart_accounting_account_id')->unsigned();
            $table->timestamps();
        });

        Schema::table('clients', function (Blueprint $table) {
            $table->foreign('company_id')->references('company_id')->on('companies')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('counterpart_accounting_account_id')->references('accounting_account_id')->on('accounting_accounts')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('clients');
    }
}
