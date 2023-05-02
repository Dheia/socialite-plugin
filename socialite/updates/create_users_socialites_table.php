<?php namespace Dubk0ff\Socialite\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

/**
 * Class CreateUsersSocialitesTable
 * @package Dubk0ff\Socialite\Updates
 */
class CreateUsersSocialitesTable extends Migration
{
    public function up()
    {
        Schema::create('dubk0ff_socialite_users', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('provider')->index();
            $table->string('provider_id');
            $table->unsignedInteger('user_id');
            $table->timestamps();

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('dubk0ff_socialite_users');
    }
}
