<?php namespace Pensoft\Eventsextension\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreatePensoftEventsextensionAttendeeAnswer extends Migration
{
    public function up()
    {
        Schema::create('pensoft_eventsextension_attendee_answer', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->integer('attendee_question_id');
            $table->string('answer', 255);
            $table->smallInteger('order');
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('pensoft_eventsextension_attendee_answer');
    }
}
