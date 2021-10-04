<?php namespace Pensoft\Eventsextension\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreatePensoftEventsextensionEventTags extends Migration
{
    public function up()
    {
        Schema::create('pensoft_eventsextension_event_tags', function($table)
        {
            $table->engine = 'InnoDB';
            $table->integer('tag_id');
            $table->integer('event_id');
            $table->primary(['tag_id','event_id']);
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('pensoft_eventsextension_event_tags');
    }
}
