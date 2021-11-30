<?php namespace Pensoft\Eventsextension\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class CreateTableEventsextensionEmails extends Migration
{
	public function up()
	{
		if (!Schema::hasTable('pensoft_eventsextension_emails')) {
			Schema::create('pensoft_eventsextension_emails', function($table)
			{
				$table->engine = 'InnoDB';
				$table->increments('id')->unsigned();
				$table->string('email', 255);
				$table->string('subject', 255);
				$table->text('body');
				$table->timestamp('created_at')->nullable();
				$table->timestamp('updated_at')->nullable();
			});
		}

	}

	public function down()
	{
		Schema::dropIfExists('pensoft_eventsextension_emails');
	}
}