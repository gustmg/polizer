<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->increments('company_id');
            $table->string('company_name');
            $table->string('company_rfc')->nullable();
            $table->string('pending_creditable_vat_account')->nullable();
            $table->string('paid_creditable_vat_account')->nullable();
            $table->string('transferred_vat_account')->nullable();
            $table->string('charged_transferred_vat_account')->nullable();
            $table->string('fees_retention_isr_account')->nullable();
            $table->string('fees_retention_vat_account')->nullable();
            $table->string('freight_retention_vat_account')->nullable();
            $table->integer('user_id')->unsigned();
            $table->timestamps();
        });

        Schema::table('companies', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('companies');
    }
}