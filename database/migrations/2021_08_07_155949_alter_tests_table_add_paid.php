<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTestsTableAddPaid extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tests', function (Blueprint $table) {
            $table->boolean('paid')->default(false);
        });

        Schema::table('blocks', function (Blueprint $table) {
            $table->boolean('paid')->default(false);
            $table->text('free')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tests', function (Blueprint $table) {
            $table->dropColumn('paid');
        });

        Schema::table('blocks', function (Blueprint $table) {
            $table->dropColumn('paid');
            $table->dropColumn('free');
        });
    }
}
