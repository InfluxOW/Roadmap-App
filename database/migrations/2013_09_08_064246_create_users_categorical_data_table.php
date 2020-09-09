<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersCategoricalDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users_categorical_data', function (Blueprint $table) {
            $table->id();
            /*
             * For Everyone
             * */
            $table->foreignId('company_id')->nullable()->index()->constrained();
            $table->enum('sex', ['male', 'female'])->nullable();
            $table->tinyInteger('age')->nullable();
            $table->string('position')->nullable();
            /*
             * For Employees
             * */
            $table->foreignId('team_id')->nullable()->index()->constrained();
            $table->foreignId('development_direction_id')->nullable()->index()->constrained();
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
        Schema::dropIfExists('users_categorical_data');
    }
}
