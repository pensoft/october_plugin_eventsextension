<?php namespace Pensoft\Eventsextension\Components;

use Cms\Classes\ComponentBase;
use Exception;
use Pensoft\Calendar\Models\Entry;
use Pensoft\Eventsextension\Models\Attendee;
use Pensoft\Eventsextension\Models\AttendeeAnswer;
use Pensoft\Eventsextension\Models\AttendeeQuestion;
use Pensoft\Eventsextension\Models\OrderAnswer;
use Pensoft\Eventsextension\Models\OrderQuestion;
use Pensoft\Eventsextension\QuestionFormGenerator;

/**
 * EventsRegisterForm Component
 */
class EventsRegisterForm extends ComponentBase
{
    public $thankYouMessage;
	public function componentDetails()
    {
        return [
            'name' => 'EventsRegisterForm Component',
            'description' => 'No description provided yet...'
        ];
    }

	public function getEventId(){
		try {
			return $this->param('event_id');
		} catch (\Exception $e) {
			return null;
		}
	}

    public function defineProperties()
    {
        $this->page['event'] = (new Entry())::where('id', $this->getEventId())->first();
        $this->page['message'] = \Session::get('message');
        $this->thankYouMessage = $this->page['event']['thank_you_message'];
    }

	public function getFormFields()
	{
		$questions = new OrderQuestion();
		$eventId = $this->getEventId();
		$questions = $questions::where('event_id', $eventId)
			->where('active', true)
			->orderBy('order', 'asc')
			->get()->toArray();
		$questionForm = new QuestionFormGenerator($questions);
		// Generate form
		$form_attributes = [
//			'method' => 'post',
//			'id' => 'event-register-form-'.$eventId,
//			'name' => 'event-register-form-'.$eventId,
//			'data-request' => 'onSubmit',
//			'data-request-success' => 'alert(\'Message Sent\')',
		];
		return $questionForm->render($form_attributes, $submit_button_text="Submit"); // return html
	}

	public function onSubmit()
	{
		$questions = new OrderQuestion();
		$eventId = $this->getEventId();
		$questions = $questions::where('event_id', $eventId);

		$allQuestions = $questions->get()->toArray();
		$requiredQuestions = $questions->where('required', true)->get()->toArray();

		$arrRequired = [];
		foreach($requiredQuestions as $k => $q){
			$arrRequired[$q['name']] = 'required';
			if($q['name'] == 'email'){
				$arrRequired[$q['name']] .= '|email';
			}
		}
		$validator = \Validator::make(
			$form = \Input::all(), $arrRequired
		);

		if($validator->fails()){
//			return \Redirect::back()->withErrors($validator);
			throw new \ValidationException($validator);
		}
		$attendee = new Attendee();
		$attendee->event_id = $eventId;
		$attendee->save();

		foreach($allQuestions as $key => $question){

			//copy the questions to attendee questions
			$attendeeQuestion = new AttendeeQuestion();
			$attendeeQuestion->order_question_id = $question['id'];
			$attendeeQuestion->attendee_id = $attendee->id;
			$attendeeQuestion->event_id = $eventId;
			$attendeeQuestion->question = $question['question'];
			$attendeeQuestion->type = $question['type'];
			$attendeeQuestion->required = $question['required'];
			$attendeeQuestion->order = $question['order'];
			$attendeeQuestion->save();

			//write the answers in attendee_answers
			$attendeeAnswer = new AttendeeAnswer();
			if($question['active']){
				$attendeeAnswer->answer = \Input::get($question['name']);
			}else{
				$defaultAnswer = (new OrderAnswer())->where('order_question_id', $question['id'])->first()->toArray();
				$attendeeAnswer->answer =  $defaultAnswer['answer'];
			}

			$attendeeAnswer->order = $attendeeQuestion->order;
			$attendeeAnswer->attendee_question = $attendeeQuestion->id;
			$attendeeAnswer->save();
		}

		return \Redirect::back()->with('message', $this->thankYouMessage);
//		return \Redirect::back()->with('success', 1);
	}
}
