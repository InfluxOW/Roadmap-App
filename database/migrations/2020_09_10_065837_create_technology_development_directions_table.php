<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTechnologyDevelopmentDirectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('technology_development_directions', function (Blueprint $table) {
            $table->id('technology_for_development_direction');
            $table->foreignId('technology_id')->index()->constrained()->cascadeOnDelete();
            $table->foreignId('development_direction_id')->index()->constrained()->cascadeOnDelete();
            $table->unique(['technology_id', 'development_direction_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('technology_development_directions');
    }
}
