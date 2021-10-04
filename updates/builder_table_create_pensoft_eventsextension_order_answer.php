<?php namespace Pensoft\Eventsextension\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreatePensoftEventsextensionOrderAnswer extends Migration
{
    public function up()
    {
        Schema::create('pensoft_eventsextension_order_answer', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->integer('order_question_id');
            $table->string('answer', 255)->nullable();
            $table->boolean('selected')->default(false);
            $table->smallInteger('order');
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('pensoft_eventsextension_order_answer');
    }
}
