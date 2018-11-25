<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProvidersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('providers', function (Blueprint $table) {
            $table->increments('provider_id');
            $table->string('provider_name');
            $table->string('provider_rfc');
            $table->string('provider_accounting_account');
            $table->integer('company_id')->unsigned();
            $table->integer('counterpart_accounting_account_id')->unsigned();
            $table->timestamps();
        });

        Schema::table('providers', function (Blueprint $table) {
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
        Schema::dropIfExists('providers');
    }
}
