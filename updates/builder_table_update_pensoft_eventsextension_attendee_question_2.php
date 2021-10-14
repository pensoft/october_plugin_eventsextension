<?php namespace Pensoft\Eventsextension\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdatePensoftEventsextensionAttendeeQuestion2 extends Migration
{
    public function up()
    {
        Schema::table('pensoft_eventsextension_attendee_question', function($table)
        {
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->string('type', 1)->change();
            $table->boolean('required')->default(false)->change();
        });
    }
    
    public function down()
    {
        Schema::table('pensoft_eventsextension_attendee_question', function($table)
        {
            $table->dropColumn('created_at');
            $table->dropColumn('updated_at');
            $table->string('type', 1)->change();
            $table->boolean('required')->default(null)->change();
        });
    }
}
