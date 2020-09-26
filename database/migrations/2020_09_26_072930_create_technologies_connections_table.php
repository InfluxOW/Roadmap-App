<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTechnologiesConnectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('technologies_connections', function (Blueprint $table) {
            $table->foreignId('technology_for_development_direction_id')->index()
                ->constrained('technology_development_directions', 'technology_for_development_direction');
            $table->foreignId('related_technology_for_development_direction_id')->index()
                ->constrained('technology_development_directions', 'technology_for_development_direction');
            $table->unique(
                ['technology_for_development_direction_id', 'related_technology_for_development_direction_id'],
                'technologies_for_development_directions_unique'
            );
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('technologies_connections');
    }
}
