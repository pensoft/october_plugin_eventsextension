<?php namespace Pensoft\Eventsextension\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class UpdateEntriesThankYou extends Migration
{

	public function up()
	{
		Schema::table('christophheich_calendar_entries', function($table)
		{
			$table->text('thank_you_message')->nullable();
		});
	}

	public function down()
	{
		Schema::table('christophheich_calendar_entries', function($table)
		{
			$table->dropColumn('thank_you_message');
		});
	}

}
