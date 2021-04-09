<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateImagequestionsBlock extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('questionsets', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->tinyInteger('quantity')->default(2);
            $table->tinyInteger('type')->default(1);
            $table->integer('client_id')->nullable();
            $table->timestamps();
        });

        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->integer('sort_no');
            $table->string('imageA');
            $table->string('imageB');
            $table->string('imageC')->nullable();
            $table->string('imageD')->nullable();
            $table->foreignId('questionset_id')->constrained('questionsets')->cascadeOnDelete();
            $table->timestamps();
        });

        Schema::create('test_questionset', function (Blueprint $table) {
            $table->id();
            // Не работает - несоответствие размерности и нельзя изменить на лету
//            $table->foreignId('test_id')->constrained('tests')->cascadeOnDelete();
            $table->unsignedInteger('test_id');
            $table->foreignId('questionset_id')->constrained('questionsets')->cascadeOnDelete();
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
        Schema::dropIfExists('test_questionset');
        Schema::dropIfExists('questions');
        Schema::dropIfExists('questionsets');
    }
}
