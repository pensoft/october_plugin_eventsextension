<?php namespace Pensoft\Eventsextension\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreatePensoftEventsextensionOrderCodes extends Migration
{
    public function up()
    {
        Schema::create('pensoft_eventsextension_order_codes', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->integer('code_id');
            $table->integer('order_id');
            $table->char('code', 100);
            $table->float('amount_off')->nullable();
            $table->integer('precent_off')->nullable();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('pensoft_eventsextension_order_codes');
    }
}
