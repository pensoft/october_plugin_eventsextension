<?php namespace Pensoft\Eventsextension\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreatePensoftEventsextensionAttendeeEmailLogs extends Migration
{
    public function up()
    {
        Schema::create('pensoft_eventsextension_attendee_email_logs', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->integer('attendee_profile_id');
            $table->text('bcc');
            $table->string('email', 255);
            $table->string('subject', 255);
            $table->text('body');
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('pensoft_eventsextension_attendee_email_logs');
    }
}
