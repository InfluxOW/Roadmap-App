<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeeCourseCompletionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employee_course_completions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->index()->constrained('users')->cascadeOnDelete();
            $table->foreignId('course_id')->index()->constrained()->cascadeOnDelete();
            $table->unique(['employee_id', 'course_id']);
            $table->enum('rating', range(0, 10))->nullable();
            $table->string('certificate')->nullable();
            $table->timestamp('completed_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('employee_course_completions');
    }
}
