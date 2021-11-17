<?php namespace Pensoft\EventsExtension\Components;

use Cms\Classes\ComponentBase;
use GuzzleHttp\Psr7\Request;
use Pensoft\Calendar\Models\Entry;
use Pensoft\Eventsextension\Models\AttendeeAnswer;
use Pensoft\Eventsextension\Models\Email;

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
		$this->addJs('/plugins/pensoft/eventsextension/assets/js/custom.js');
		$this->addCss('/plugins/pensoft/eventsextension/assets/css/custom.css');
	}

    public function defineProperties()
    {
        return [];
    }

    public function getEvents(){
    	return Entry::where('active', true)->get();
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
				$message->from('noreply@pansoft.net', 'Pensoft Events Manager');
				$message->replyTo('noreply@pansoft.net', 'Pensoft Events Manager');
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
    	$orderQuestionId = (int)post('order_question_id');
    	$fieldType = post('field_type');
		$questionData = json_decode(post('order_question_data'), true);
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
}
