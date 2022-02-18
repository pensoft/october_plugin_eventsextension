<?php namespace Pensoft\Eventsextension\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreatePensoftEventsextensionEventsUsers extends Migration
{
    public function up()
    {
        Schema::create('pensoft_eventsextension_events_users', function($table)
        {
            $table->engine = 'InnoDB';
            $table->integer('entry_id');
            $table->integer('user_id');
            $table->primary(['entry_id','user_id']);
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('pensoft_eventsextension_events_users');
    }
}
