<?php namespace Pensoft\EventsExtension\Components;

use Cms\Classes\ComponentBase;
use Illuminate\Support\Collection;
use Pensoft\Calendar\Models\Entry;
use Pensoft\Eventsextension\AttendeesExport;
use Pensoft\Eventsextension\Models\Attendee;
use Pensoft\Eventsextension\Models\AttendeeAnswer;
use Pensoft\Eventsextension\Models\AttendeeQuestion;
use Pensoft\Eventsextension\Models\Email;
use Maatwebsite\Excel\Facades\Excel;
use Pensoft\Eventsextension\Models\OrderQuestion;
use Backend\Facades\BackendAuth;
use System\Models\MailSetting;

/**
 * AttendeesList Component
 */
class AttendeesList extends ComponentBase
{

	const FIELD_TYPES = [
		'p' => 'p', //input one
		't' => 'text', // input one
		'e' => 'email', // input one
		'r' => 'radio', // input many (answers)
		'd' => 'select', // select many (answers)
		'c' => 'checkbox', // input many (answers)
	];

    public function componentDetails()
    {
        return [
            'name' => 'AttendeesList Component',
            'description' => 'No description provided yet...'
        ];
    }

	public function onRun(){
		$loggedIn= !empty(BackendAuth::getUser()) ? true : false;
		if(!$loggedIn){
			return \Redirect::to('/');
		}
		$this->addJs('/plugins/pensoft/eventsextension/assets/js/custom.js');
		$this->addCss('/plugins/pensoft/eventsextension/assets/css/custom.css');
	}

    public function defineProperties()
    {
        return [];
    }

    public function getEventAttendeeAnswers($eventId, $attendeeId){
		$orderQuestions = OrderQuestion::where('event_id', $eventId)->get()->toArray();
		$attendee = Attendee::where('event_id', $eventId)->where('id', $attendeeId)->first();
//		$attendees = Attendee::where('event_id', $eventId)->get();
		$items = [];

		$items[0][0] = 'Attendee ID';
		foreach($orderQuestions as $orderQuestion){
			$items[0][$orderQuestion['id']] = strip_tags($orderQuestion['question']);
//			foreach ($attendees as $attendee){
				$attendeeQuestions = $attendee->attendee_questions()->get()->toArray();
				foreach ($attendeeQuestions as $attendeeQuestion){
					if($orderQuestion['id'] == $attendeeQuestion['order_question_id']){
						$items[$attendee['id']][0] = $attendee['id'];
						$answer = [];
						foreach ($attendeeQuestion['attendee_answers'] as $attendeeAnswer){
							$answer[$attendeeAnswer['id']] = $attendeeAnswer['answer'];
						}
						$items[$attendee['id']][$orderQuestion['id']] = $answer;
					}
				}
//			}
		}

		foreach ($items as $k => $subArray){

			if($k > 0){
				if($diff = array_diff_key($items[0], $items[$k])){
					$key = key($diff);
					$offset = array_search($key, array_keys($items[0]));
					$items[$k] = array_slice($items[$k], 0, $offset, true) +
						[$key => []] +
						array_slice($items[$k], $offset, count($items[$k]) - 1, true) ;
				}
			}
		}

		return $items[$attendeeId];


//    	return Entry::where('active', true)->get();
	}


    public function getEntry(){
    	$eventId = $this->param('event_id');
		if(!$eventId){
			return \Redirect::to('/');
		}
    	return Entry::where('id', $eventId)->get();
	}

    public function onLoadEmailForm(){
    	if(post('attendees')){
    		$attendees = post('attendees');
    		$mails = array();
			foreach ($attendees as $attendeeQuestion){
				$mails[$attendeeQuestion['attendee_id']] = $attendeeQuestion['attendee_answers'][0]['answer'];
			}
		}
		$this->page['mailsArr'] = json_encode($mails);
		$this->page['mailsStr'] = implode(', ', $mails);
	}


	public function onSendEmail(){
		$validator = \Validator::make(
			$form = \Input::all(), [
				'subject' => 'required',
				'message' => 'required',
				'recipients' => 'required',
			]
		);

		if($validator->fails()){
			throw new \ValidationException($validator);
		}

		$recipients = json_decode(\Input::get('recipients'), 1);
		$subject = \Input::get('subject');
		$messageBody = \Input::get('message');


		foreach($recipients as $attendeeId => $mail){
			$recipientEmail = trim($mail);
			$vars = [];
			\Mail::send(['raw' => '<div>'.$messageBody.'</div>'], $vars, function($message)  use ($recipientEmail, $subject) {
				$message->from(MailSetting::get('sender_email'), MailSetting::get('sender_name'));
				$message->replyTo(MailSetting::get('sender_email'), MailSetting::get('sender_name'));
				$message->to($recipientEmail);
				$message->subject($subject);

			});
			sleep(1); // TODO remove this line

			if (count(\Mail::failures()) > 0){
				\Flash::error('Mail not sent');
				return;
			}

			$email = new Email();
			$email->subject = $subject;
			$email->attendee_id = $attendeeId;
			$email->body = $messageBody;
			$email->email = $mail;
			$email->save();
		}

		\Flash::success('Mail(s) sent');
	}


	public function onLoadEditFieldForm(){
    	$answerId = (int)post('answer_id');
    	$answerValue = post('answer_value');

		$questionData = json_decode(post('order_question_data'), true);
		$fieldType = $questionData['type'];
		$type = self::FIELD_TYPES[$fieldType];
		$name = $questionData['name'];
		$answers = $questionData['answers'];
		if($type == 'p'){//todo textarea
			$field = "<input name=\"${name}\" type=\"text\" />\n";
		}
		if($type == 'text' || $type == 'email'){
			$field = "<input name=\"${name}\" type=\"${type}\" value='${answerValue}' />\n";
		}
		if($type == 'radio' || $type == 'checkbox'){
			$fields = "";
			foreach($answers as $answer){
				$value = $answer['answer'];
				$selected_ = '';
				if($answer['answer'] == $answerValue){
					$selected_ = 'selected';
					if($type == 'checkbox'){
						$selected_ = 'checked';
					}
				}

				$fields .= "<label><input name=\"${name}[]\" type=\"${type}\" value=\"${value}\" ${selected_}/>${value}</label>\n";
			}
			$field = $fields;
		}
		if($type == 'select'){
			$fields = "<select name=\"${name}\" >\n";

			foreach($answers as $answer){
				$value = $answer['answer'];
				$selected_ = '';
				if($answer['answer'] == $answerValue){
					$selected_ = 'selected';
				}
				$fields .= "<option value=\"${value}\" ${selected_}>${value}</option>\n";
			}
			$fields .= "</select>\n";
			$field = $fields;
		}

		$this->page['field'] = $field;
		$this->page['field_name'] = $name;
		$this->page['attendee_answer_id'] = $answerId;
		$this->page['label'] = 'my test label';
	}


	public function onSaveAnswerField(){
    	$fieldName = \Input::get('field_name');
    	$attendeeAnswer = \Input::get('attendee_answer_id');
		$fieldValue = \Input::get($fieldName);

		$validator = \Validator::make(
			$form = \Input::all(), [
				$fieldName => 'required',
			]
		);

		if($validator->fails()){
			throw new \ValidationException($validator);
		}

		$attendeeAnswer = (new AttendeeAnswer())->where('id', $attendeeAnswer)->first();
		$attendeeAnswer->update(['answer' => $fieldValue]);
		return \Redirect::refresh();
	}


	public function onExportAttendees(){
    	$attendeeIdsArr = post('attendee_ids');
    	$eventId = post('event_id');
		$attendeeIds = implode(',', $attendeeIdsArr);
		$orderQuestions = OrderQuestion::where('event_id', $eventId)->orderBy('order')->get()->toArray();
		$attendees = Attendee::whereIn('id', $attendeeIdsArr)->get();
		$items = [];
		$items[0][0] = 'Attendee ID';
		foreach($orderQuestions as $k => $orderQuestion){
			$items[0][$orderQuestion['id']] = strip_tags($orderQuestion['question']);
			foreach ($attendees as $attendee){
				$attendeeQuestions = $attendee->attendee_questions()->get()->toArray();

				foreach ($attendeeQuestions as $key => $attendeeQuestion){
					if($orderQuestion['id'] == $attendeeQuestion['order_question_id']){
						$items[$attendee['id']][0] = $attendee['id'];
						$answer = [];
						foreach ($attendeeQuestion['attendee_answers'] as $attendeeAnswer){
							$answer[] = $attendeeAnswer['answer'];
						}
						$items[$attendee['id']][$attendeeQuestion['order_question_id']] = implode(', ', $answer);
					}
				}
			}
		}

		foreach ($items as $k => $subArray){

			if($k > 0){
				if($diff = array_diff_key($items[0], $items[$k])){
					$key = key($diff);
					$offset = array_search($key, array_keys($items[0]));
					$items[$k] = array_slice($items[$k], 0, $offset, true) +
						[$key => ''] +
						array_slice($items[$k], $offset, count($items[$k]) - 1, true) ;
				}
			}
		}

		$collection = new \October\Rain\Support\Collection($items);

		
		$r = Excel::raw(new AttendeesExport($collection), \Maatwebsite\Excel\Excel::XLSX);
		return response()->json(['data'=>base64_encode($r)]);
	}
}
