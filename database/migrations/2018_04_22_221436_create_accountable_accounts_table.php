<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAccountableAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('accountable_accounts', function (Blueprint $table) {
            $table->increments('accountable_account_id');
            $table->string('accountable_account_number');
            $table->string('accountable_account_description');
            $table->integer('company_id');
            $table->integer('accountable_account_type_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('accountable_accounts');
    }
}
