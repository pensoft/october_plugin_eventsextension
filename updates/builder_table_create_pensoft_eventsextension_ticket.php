<?php namespace Pensoft\Eventsextension\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreatePensoftEventsextensionTicket extends Migration
{
    public function up()
    {
        Schema::create('pensoft_eventsextension_ticket', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->integer('event_id');
            $table->string('name', 255);
            $table->char('type', 1);
            $table->float('price')->nullable();
            $table->smallInteger('quantity')->nullable();
            $table->smallInteger('available_quantity')->nullable();
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->text('description')->nullable();
            $table->boolean('visible')->default(false);
            $table->smallInteger('quantity_min_per_order')->nullable();
            $table->smallInteger('quantity_max_per_order')->nullable();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('pensoft_eventsextension_ticket');
    }
}
