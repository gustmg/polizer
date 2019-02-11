<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserProcessedXmlTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_processed_xml', function (Blueprint $table) {
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('company_id');
            $table->timestamp('processed_at');
            $table->integer('xml_amount');

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('company_id')->references('company_id')->on('companies');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_processed_xml');
    }
}
