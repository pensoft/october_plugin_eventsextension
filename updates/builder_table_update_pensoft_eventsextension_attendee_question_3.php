<?php namespace Pensoft\Eventsextension\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdatePensoftEventsextensionAttendeeQuestion3 extends Migration
{
    public function up()
    {
        Schema::table('pensoft_eventsextension_attendee_question', function($table)
        {
            $table->integer('attendee_id')->nullable()->change();
            $table->string('type', 1)->change();
            $table->boolean('required')->default(false)->change();
        });
    }
    
    public function down()
    {
        Schema::table('pensoft_eventsextension_attendee_question', function($table)
        {
            $table->integer('attendee_id')->nullable(false)->change();
            $table->string('type', 1)->change();
            $table->boolean('required')->default(null)->change();
        });
    }
}
