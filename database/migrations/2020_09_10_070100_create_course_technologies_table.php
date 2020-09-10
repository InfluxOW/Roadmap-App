<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCourseTechnologiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('course_technologies', function (Blueprint $table) {
            $table->foreignId('technology_id')->index()->constrained()->cascadeOnDelete();
            $table->foreignId('course_id')->index()->constrained()->cascadeOnDelete();
            $table->unique(['technology_id', 'course_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('course_technologies');
    }
}
