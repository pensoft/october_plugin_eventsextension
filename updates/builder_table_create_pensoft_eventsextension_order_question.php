<?php namespace Pensoft\Eventsextension\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreatePensoftEventsextensionOrderQuestion extends Migration
{
    public function up()
    {
        Schema::create('pensoft_eventsextension_order_question', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->integer('event_id');
            $table->integer('ticket_id');
            $table->text('question');
            $table->char('type', 1);
            $table->boolean('required')->default(false);
            $table->smallInteger('order');
            $table->boolean('active')->default(true);
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('pensoft_eventsextension_order_question');
    }
}
