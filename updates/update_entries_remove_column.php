<?php namespace Pensoft\Eventsextension\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class UpdateEntriesRemoveColumn extends Migration
{

	public function up()
	{
		Schema::table('christophheich_calendar_entries', function($table)
		{
			$table->dropColumn('created');
			$table->dropColumn('utc');
		});
	}

	public function down()
	{
		Schema::table('christophheich_calendar_entries', function($table)
		{
			$table->smallInteger('created')->nullable();
			$table->dateTime('utc');
		});
	}

}
