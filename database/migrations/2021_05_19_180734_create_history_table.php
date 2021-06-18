<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('test_id')->constrained()->cascadeOnDelete();
            $table->foreignId('license_id')->constrained()->cascadeOnDelete();
            $table->longText('card')->nullable();
            $table->timestamp('done')->nullable();
            $table->timestamps();
        });

        Schema::create('historysteps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('history_id')->constrained('history')->cascadeOnDelete();
            $table->foreignId('question_id')->constrained()->cascadeOnDelete();
            $table->boolean('channelA')->default(false);
            $table->boolean('channelB')->default(false);
            $table->boolean('channelC')->default(false);
            $table->boolean('channelD')->default(false);
            $table->timestamp('done')->nullable();
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
        Schema::dropIfExists('historysteps');
        Schema::dropIfExists('history');
    }
}
