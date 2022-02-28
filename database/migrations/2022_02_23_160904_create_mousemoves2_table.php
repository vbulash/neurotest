<?php

use App\Models\HistoryStep;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMousemoves2Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mousemoves', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hs_id')->constrained('historysteps')->cascadeOnDelete();
            $table->float('X');
            $table->float('Y');
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
        Schema::dropIfExists('mousemoves');
    }
}
