<?php namespace Pensoft\Eventsextension\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreatePensoftEventsextensionAttendee extends Migration
{
    public function up()
    {
        Schema::create('pensoft_eventsextension_attendee', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->integer('attendee_profile_id');
            $table->integer('order_id');
            $table->integer('event_id');
            $table->char('status', 1);
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('pensoft_eventsextension_attendee');
    }
}
