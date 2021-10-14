<?php namespace Pensoft\Eventsextension\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdatePensoftEventsextensionOrderQuestion2 extends Migration
{
    public function up()
    {
        Schema::table('pensoft_eventsextension_order_question', function($table)
        {
            $table->string('name')->nullable();
        });
    }
    
    public function down()
    {
        Schema::table('pensoft_eventsextension_order_question', function($table)
        {
            $table->dropColumn('name');
        });
    }
}
