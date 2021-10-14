<?php namespace Pensoft\Eventsextension\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class UpdateEntriesActiveNew extends Migration
{

	public function up()
	{
		Schema::table('christophheich_calendar_entries', function($table)
		{
			$table->boolean('active')->default(true);
		});
	}

	public function down()
	{
		Schema::table('christophheich_calendar_entries', function($table)
		{
			$table->dropColumn('active');
		});
	}

}
