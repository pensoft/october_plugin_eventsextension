<?php namespace Pensoft\Eventsextension\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdatePensoftEventsextensionAttendeeAnswer extends Migration
{
    public function up()
    {
        Schema::table('pensoft_eventsextension_attendee_answer', function($table)
        {
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }
    
    public function down()
    {
        Schema::table('pensoft_eventsextension_attendee_answer', function($table)
        {
            $table->dropColumn('created_at');
            $table->dropColumn('updated_at');
        });
    }
}
