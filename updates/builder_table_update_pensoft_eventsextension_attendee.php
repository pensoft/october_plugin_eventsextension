<?php namespace Pensoft\Eventsextension\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdatePensoftEventsextensionAttendee extends Migration
{
    public function up()
    {
        Schema::table('pensoft_eventsextension_attendee', function($table)
        {
            $table->integer('attendee_profile_id')->nullable()->change();
            $table->string('status', 1)->nullable()->change();
        });
    }
    
    public function down()
    {
        Schema::table('pensoft_eventsextension_attendee', function($table)
        {
            $table->integer('attendee_profile_id')->nullable(false)->change();
            $table->string('status', 1)->nullable(false)->change();
        });
    }
}
