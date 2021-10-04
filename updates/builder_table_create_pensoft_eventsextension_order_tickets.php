<?php namespace Pensoft\Eventsextension\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreatePensoftEventsextensionOrderTickets extends Migration
{
    public function up()
    {
        Schema::create('pensoft_eventsextension_order_tickets', function($table)
        {
            $table->engine = 'InnoDB';
            $table->integer('order_id');
            $table->integer('ticket_id');
            $table->string('name', 255);
            $table->char('type', 1);
            $table->float('price')->nullable();
            $table->smallInteger('quantity')->default(1);
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->text('description');
            $table->primary(['order_id','ticket_id']);
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('pensoft_eventsextension_order_tickets');
    }
}
