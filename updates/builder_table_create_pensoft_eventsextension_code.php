<?php namespace Pensoft\Eventsextension\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreatePensoftEventsextensionCode extends Migration
{
    public function up()
    {
        Schema::create('pensoft_eventsextension_code', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->integer('event_id');
            $table->string('code', 100);
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->float('amount_off')->nullable();
            $table->float('precent_off ')->nullable();
            $table->integer('quantity')->nullable();
            $table->integer('quantity_available')->nullable();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('pensoft_eventsextension_code');
    }
}
