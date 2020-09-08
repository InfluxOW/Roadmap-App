<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            /*
             * General Information
             * */
            $table->id();
            $table->string('name');
            $table->string('username')->unique();
            $table->string('email')->unique();
            $table->string('password');
            $table->string('type');
            $table->enum('sex', ['male', 'female'])->nullable();
            $table->tinyInteger('age')->nullable();
            $table->foreignId('company_id')->index()->constrained();
            $table->rememberToken();
            $table->timestamp('email_verified_at')->nullable();
            $table->timestamps();
            /*
             * For Employees
             * */
            $table->string('position');
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
        Schema::dropIfExists('users');
    }
}
