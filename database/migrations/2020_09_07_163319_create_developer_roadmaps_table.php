<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeveloperRoadmapsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('developer_roadmaps', function (Blueprint $table) {
            $table->foreignId('developer_id')->index()->constrained('users')->cascadeOnDelete();
            $table->foreignId('preset_id')->index()->constrained()->cascadeOnDelete();
            $table->unique(['developer_id', 'preset_id']);
            $table->foreignId('assigned_by')->index()->constrained('users')->cascadeOnDelete();
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
        Schema::dropIfExists('developer_roadmaps');
    }
}
