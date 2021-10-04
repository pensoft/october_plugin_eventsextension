<?php namespace Pensoft\Eventsextension\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreatePensoftEventsextensionAttendeeProfile extends Migration
{
    public function up()
    {
        Schema::create('pensoft_eventsextension_attendee_profile', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->string('name', 255);
            $table->string('first_name', 255);
            $table->string('last_name', 255);
            $table->char('autolog_hash', 32)->nullable();
            $table->char('gender', 100)->nullable();
            $table->string('phone', 255)->nullable();
            $table->date('birth_date')->nullable();
            $table->string('organization', 255)->nullable();
            $table->char('status', 1);
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('pensoft_eventsextension_attendee_profile');
    }
}
