<?php

    use App\Models\Test;
    use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class PrepareTestContract extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('contracts', function (Blueprint $table) {
            $table->dropColumn('anonymous');
            $table->dropColumn('options');
        });

        Schema::table('tests', function (Blueprint $table) {
            $table->dropColumn('code');
            $table->tinyInteger('type')->default(Test::TYPE_DRAFT);
            $table->integer('options')->default(Test::AUTH_GUEST);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('contracts', function (Blueprint $table) {
            $table->tinyInteger('anonymous')->default(0);
            $table->tinyInteger('options');
        });

        Schema::table('tests', function (Blueprint $table) {
            $table->dropColumn('type');
            $table->longText('code');
            $table->dropColumn('options');
        });
    }
}
