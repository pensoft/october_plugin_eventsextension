<?php namespace Pensoft\Eventsextension\Components;

use Cms\Classes\ComponentBase;
use Pensoft\Calendar\Models\Entry;

/**
 * EventsList Component
 */
class EventsList extends ComponentBase
{
    public function componentDetails()
    {
        return [
            'name' => 'EventsList Component',
            'description' => 'No description provided yet...'
        ];
    }

    public function defineProperties()
    {
        return [];
    }

	public function getEvents()
	{
		$events = Entry::orderBy('start', 'desc')->get();
		return $events;
	}
}
