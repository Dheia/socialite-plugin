<?php namespace Dubk0ff\Socialite\Updates;

use Artisan;
use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

/**
 * Class CreateServicesTable
 * @package Dubk0ff\Socialite\Updates
 */
class CreateServicesTable extends Migration
{
    public function up()
    {
        Schema::create('dubk0ff_socialite_services', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('title');
            $table->string('provider');
            $table->string('client_id');
            $table->string('client_secret');
            $table->json('data');
            $table->timestamps();
        });

        Artisan::call('socialite:install', ['--force' => true]);
    }

    public function down()
    {
        Schema::dropIfExists('dubk0ff_socialite_services');
    }
}
