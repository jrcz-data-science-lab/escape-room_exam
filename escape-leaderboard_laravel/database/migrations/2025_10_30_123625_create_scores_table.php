<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateScoresTable extends Migration
{
    public function up()
    {
        Schema::create('scores', function (Blueprint $table) {
            $table->id();
            $table->string('player_name', 100);
            // We slaan 'time_seconds' op als integer (aantal seconden) - of als 'score' als lagere is beter
            $table->integer('time_seconds')->nullable();
            $table->integer('score')->nullable();
            $table->string('game_id')->nullable(); // optioneel: om meerdere kamers/levels te onderscheiden
            $table->string('submitted_from_ip')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('scores');
    }
}
