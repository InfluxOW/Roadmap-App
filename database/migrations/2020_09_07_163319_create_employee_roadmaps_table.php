<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeeRoadmapsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employee_roadmaps', function (Blueprint $table) {
            $table->foreignId('employee_id')->index()->constrained('users')->cascadeOnDelete();
            $table->foreignId('preset_id')->index()->constrained()->cascadeOnDelete();
            $table->unique(['employee_id', 'preset_id']);
            $table->foreignId('assigned_by_id')->index()->constrained('users')->cascadeOnDelete();
            $table->timestamp('assigned_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('employee_roadmaps');
    }
}
