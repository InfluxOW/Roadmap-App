<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserCategoricalDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_categorical_data', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->index()->constrained();
            /*
             * For Everyone
             * */
            $table->foreignId('company_id')->index()->constrained();
            $table->enum('sex', ['male', 'female'])->nullable();
            $table->tinyInteger('age')->nullable();
            /*
             * For Employees
             * */
            $table->string('position')->nullable();
            $table->foreignId('team_id')->nullable()->index()->constrained();
            $table->foreignId('development_direction_id')->nullable()->index()->constrained();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_categorical_data');
    }
}
