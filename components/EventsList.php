<?php namespace Pensoft\Eventsextension\Components;

use Cms\Classes\ComponentBase;
use Illuminate\Support\Facades\DB;
use Pensoft\Calendar\Models\Entry;
use Pensoft\Eventsextension\Models\OrderAnswer;
use Pensoft\Eventsextension\Models\OrderQuestion;

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
		$events = Entry::where('active', true)->orderBy('start', 'desc')->get();
		return $events;
	}

	public function onDuplicateEvent(){
    	$eventId = post('event_id');

    	if(!$eventId){
    		return;
		}

		$originalEvent = Entry::where('id', $eventId)->first();

		$cloneEvent = $originalEvent->replicate();
		$cloneEvent->title = "COPY of ".$cloneEvent->title;
		$cloneEvent->slug = now()->timestamp."_".$cloneEvent->slug;
		$cloneEvent->active = false;
		$cloneEvent->id = Entry::withTrashed()->max('id') + 1;
		$cloneEvent->save();

		$originalOrderQuestions = $originalEvent->order_questions;

		foreach($originalOrderQuestions as $originalOrderQuestion){
			$cloneOrderQuestion = $originalOrderQuestion->replicate();
			$cloneOrderQuestion->event_id = $cloneEvent->id;
			$cloneOrderQuestion->id = OrderQuestion::max('id') + 1;
			$cloneOrderQuestion->save();

			$originalOrderAnswers = OrderAnswer::where('order_question_id', $originalOrderQuestion->id)->get();

			if($originalOrderAnswers){
				foreach($originalOrderAnswers as $orioginalOrderAnsswer){
					$cloneOrderAnswer = $orioginalOrderAnsswer->replicate();
					$cloneOrderAnswer->order_question_id = $cloneOrderQuestion->id;
					$cloneOrderAnswer->id = OrderAnswer::max('id') + 1;
					$cloneOrderAnswer->save();
				}
			}

		}

		DB::statement('SELECT setval(\'pensoft_eventsextension_order_question_id_seq\', (SELECT MAX(id)
 FROM pensoft_eventsextension_order_question))');

		DB::statement('SELECT setval(\'pensoft_eventsextension_order_answer_id_seq\', (SELECT MAX(id)
 FROM pensoft_eventsextension_order_answer))');

		\Flash::success('Event cloned');
		return \Redirect::to('/events/'.$cloneEvent->slug);
	}
}
