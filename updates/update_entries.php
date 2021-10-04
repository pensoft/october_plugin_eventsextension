<?php namespace Pensoft\Eventsextension\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class UpdateEntries extends Migration
{

	public function up()
	{
		Schema::table('christophheich_calendar_entries', function($table)
		{
			$table->text('summary');
			$table->char('currency', 3)->nullable();
			$table->dateTime('utc');
			$table->boolean('invite_only')->default(false);
			$table->boolean('show_remaining')->default(false);
			$table->smallInteger('capacity')->nullable();
			$table->char('password', 32)->nullable();
			$table->char('created', 50);
			$table->boolean('online_event')->default(false);
			$table->string('organizer', 255)->nullable();
			$table->string('cover', 255)->nullable();
			$table->char('status', 50);
		});
	}

	public function down()
	{
		Schema::table('christophheich_calendar_entries', function($table)
		{
			$table->dropColumn('summary');
			$table->dropColumn('currency');
			$table->dropColumn('utc');
			$table->dropColumn('invite_only');
			$table->dropColumn('show_remaining');
			$table->dropColumn('capacity');
			$table->dropColumn('password');
			$table->dropColumn('created');
			$table->dropColumn('online_event');
			$table->dropColumn('organizer');
			$table->dropColumn('cover');
			$table->dropColumn('status');
		});
	}

}
