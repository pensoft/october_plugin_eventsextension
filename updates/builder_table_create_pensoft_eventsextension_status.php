<?php namespace Pensoft\Eventsextension\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreatePensoftEventsextensionStatus extends Migration
{
    public function up()
    {
        Schema::create('pensoft_eventsextension_status', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->string('name', 50);
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('pensoft_eventsextension_status');
    }
}
