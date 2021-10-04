<?php namespace Pensoft\Eventsextension\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreatePensoftEventsextensionCodesHasTickets extends Migration
{
    public function up()
    {
        Schema::create('pensoft_eventsextension_codes_has_tickets', function($table)
        {
            $table->engine = 'InnoDB';
            $table->integer('code_id');
            $table->integer('ticket_id');
            $table->primary(['code_id','ticket_id']);
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('pensoft_eventsextension_codes_has_tickets');
    }
}
