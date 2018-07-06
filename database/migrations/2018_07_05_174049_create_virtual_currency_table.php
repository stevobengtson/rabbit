<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVirtualCurrencyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('virtual_currencies', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('source_user_id')->nullable();
            $table->unsignedInteger('destination_user_id');
            $table->decimal('credit', 18,3)->nullable();
            $table->decimal('debit', 18, 3)->nullable();
            $table->timestamp('entry_date')->nullable(false)->useCurrent();            
            $table->timestamps();
            $table->foreign('source_user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('destination_user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('virtual_currencies', function (Blueprint $table) {
            $table->dropForeign('virtual_currencies_source_user_id_foreign');
            $table->dropForeign('virtual_currencies_destination_user_id_foreign');
        });
        Schema::dropIfExists('virtual_currencies');
    }
}
