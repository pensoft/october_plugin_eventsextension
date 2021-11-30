<?php namespace Pensoft\Eventsextension\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class UpdateTableEventsextensionEmails extends Migration
{
	public function up()
	{
		if (!Schema::hasColumn('pensoft_eventsextension_emails', 'attendee_id')) {
			Schema::table('pensoft_eventsextension_emails', function($table)
			{
				$table->integer('attendee_id');
			});
		}

	}

	public function down()
	{
		if (Schema::hasColumn('pensoft_eventsextension_emails', 'attendee_id')) {
			Schema::table('pensoft_eventsextension_emails', function ($table) {
				$table->dropColumn('attendee_id');
			});
		}
	}
}
