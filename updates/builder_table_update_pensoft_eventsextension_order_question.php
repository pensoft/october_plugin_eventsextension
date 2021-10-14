<?php namespace Pensoft\Eventsextension\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdatePensoftEventsextensionOrderQuestion extends Migration
{
    public function up()
    {
        Schema::table('pensoft_eventsextension_order_question', function($table)
        {
            $table->integer('ticket_id')->nullable()->change();
            $table->string('type', 1)->change();
            $table->boolean('required')->default(false)->change();
        });
    }
    
    public function down()
    {
        Schema::table('pensoft_eventsextension_order_question', function($table)
        {
            $table->integer('ticket_id')->nullable(false)->change();
            $table->string('type', 1)->change();
            $table->boolean('required')->default(null)->change();
        });
    }
}
