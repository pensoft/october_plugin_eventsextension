<?php namespace Pensoft\Eventsextension\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreatePensoftEventsextensionAttendeeQuestion extends Migration
{
    public function up()
    {
        Schema::create('pensoft_eventsextension_attendee_question', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->integer('attendee_id');
            $table->integer('order_question_id');
            $table->integer('event_id');
            $table->text('ticket_id');
            $table->text('question');
            $table->char('type', 1);
            $table->boolean('required')->default(false);
            $table->smallInteger('order');
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('pensoft_eventsextension_attendee_question');
    }
}
