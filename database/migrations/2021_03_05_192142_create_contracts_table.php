<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContractsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contracts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('number');
            $table->string('invoice');
            $table->date('start');
            $table->date('end');
            $table->string('mkey');
            $table->integer('license_count');
            $table->string('url')->nullable();
            $table->tinyInteger('status');
            $table->boolean('anonymous')->default(false);
            $table->tinyInteger('options');
            $table->integer('client_id');
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
        Schema::dropIfExists('contracts');
    }
}
