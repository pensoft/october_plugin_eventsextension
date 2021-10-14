<?php namespace Pensoft\Eventsextension\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class UpdateEntriesNew extends Migration
{

	public function up()
	{
		Schema::table('christophheich_calendar_entries', function($table)
		{
			$table->integer('status_id');
			$table->dropColumn('status');
		});
	}

	public function down()
	{
		Schema::table('christophheich_calendar_entries', function($table)
		{
			$table->dropColumn('status_id');
			$table->string('status', 1);
		});
	}

}
