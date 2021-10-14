<?php namespace Pensoft\Eventsextension\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreatePensoftEventsextensionAttendeeAddress extends Migration
{
    public function up()
    {
        Schema::create('pensoft_eventsextension_attendee_address', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->integer('attendee_profile_id');
            $table->string('address_1', 255);
            $table->string('address_2', 255)->nullable();
            $table->char('city', 100)->nullable();
            $table->char('region', 100)->nullable();
            $table->char('postal_code', 100);
            $table->char('country', 100);
            $table->char('type', 1);
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('pensoft_eventsextension_attendee_address');
    }
}
