<?php namespace Pensoft\Eventsextension\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdatePensoftEventsextensionAttendee3 extends Migration
{
    public function up()
    {
        Schema::table('pensoft_eventsextension_attendee', function($table)
        {
            $table->string('status', 1)->change();
        });
    }
    
    public function down()
    {
        Schema::table('pensoft_eventsextension_attendee', function($table)
        {
            $table->string('status', 1)->change();
        });
    }
}
