<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeeDevelopmentDirectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employee_development_directions', function (Blueprint $table) {
            $table->foreignId('employee_id')->index()->constrained('users')->cascadeOnDelete();
            $table->foreignId('development_direction_id')->index()->constrained()->cascadeOnDelete();
            $table->unique(['employee_id', 'development_direction_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('employee_development_directions');
    }
}
