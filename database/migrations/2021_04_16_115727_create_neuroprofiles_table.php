<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNeuroprofilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('neuroprofiles', function (Blueprint $table) {
            $table->id();
            $table->string('code', 10);
            $table->string('name');
            $table->foreignId('fmptype_id')->constrained();
            $table->boolean('cluster')->default(false);
            $table->timestamps();
        });

        Schema::table('blocks', function (Blueprint $table) {
            $table->string('code', 10);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('neuroprofiles');
        Schema::table('blocks', function (Blueprint $table) {
            $table->dropColumn('code');
        });
    }
}
