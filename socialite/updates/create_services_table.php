<?php namespace Dubk0ff\Socialite\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

/**
 * Class CreateServicesTable
 * @package Dubk0ff\Socialite\Updates
 */
class CreateServicesTable extends Migration
{
    /** @var string */
    protected static $table = 'dubk0ff_socialite_services';

    public function up()
    {
        if (! Schema::hasTable(self::$table)) {
            Schema::create(self::$table, function (Blueprint $table) {
                $table->engine = 'InnoDB';
                $table->increments('id');
                $table->string('title');
                $table->string('provider');
                $table->string('client_id');
                $table->string('client_secret');
                $table->json('data');
                $table->timestamps();
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists(self::$table);
    }
}
