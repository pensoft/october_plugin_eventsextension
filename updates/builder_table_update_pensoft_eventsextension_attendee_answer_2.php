<?php namespace Pensoft\Eventsextension\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdatePensoftEventsextensionAttendeeAnswer2 extends Migration
{
    public function up()
    {
        Schema::table('pensoft_eventsextension_attendee_answer', function($table)
        {
            $table->text('answer')->nullable(false)->unsigned(false)->default(null)->change();
        });
    }
    
    public function down()
    {
        Schema::table('pensoft_eventsextension_attendee_answer', function($table)
        {
            $table->string('answer', 255)->nullable(false)->unsigned(false)->default(null)->change();
        });
    }
}
