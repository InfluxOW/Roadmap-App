<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTeamManagersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('team_managers', function (Blueprint $table) {
            $table->foreignId('manager_id')->index()->constrained('users')->cascadeOnDelete();
            $table->foreignId('team_id')->index()->constrained()->cascadeOnDelete();
            $table->unique(['manager_id', 'team_id']);
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
        Schema::dropIfExists('team_managers');
    }
}
