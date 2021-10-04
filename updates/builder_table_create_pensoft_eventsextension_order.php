<?php namespace Pensoft\Eventsextension\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreatePensoftEventsextensionOrder extends Migration
{
    public function up()
    {
        Schema::create('pensoft_eventsextension_order', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->string('name', 255)->nullable();
            $table->string('first_name', 255)->nullable();
            $table->string('last_name', 255)->nullable();
            $table->string('email', 255)->nullable();
            $table->string('status', 2);
            $table->timestamp('created_at');
            $table->timestamp('updated_at');
            $table->float('subtotal');
            $table->float('fees');
            $table->float('total');
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('pensoft_eventsextension_order');
    }
}
