<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterFmptypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		Schema::table('fmptypes', function (Blueprint $table) {
			$table->boolean('active')->default(false)->comment('Статус типа описания');
			$table->tinyInteger('limit')->default(16)->comment('Необходимое количество нейропрофилей');
		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
		Schema::table('fmptypes', function (Blueprint $table) {
			$table->dropColumn('limit');
			$table->dropColumn('active');
		});
    }
}
