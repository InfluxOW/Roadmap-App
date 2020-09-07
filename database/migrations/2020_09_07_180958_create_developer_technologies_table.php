<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeveloperTechnologiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('developer_technologies', function (Blueprint $table) {
            $table->foreignId('developer_id')->index()->constrained('users')->cascadeOnDelete();
            $table->foreignId('technology_id')->index()->constrained()->cascadeOnDelete();
            $table->unique(['developer_id', 'technology_id']);
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
        Schema::dropIfExists('developer_technologies');
    }
}
