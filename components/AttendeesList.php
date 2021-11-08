<?php namespace Pensoft\EventsExtension\Components;

use Cms\Classes\ComponentBase;
use GuzzleHttp\Psr7\Request;
use Pensoft\Calendar\Models\Entry;
use Pensoft\Eventsextension\Models\Email;

/**
 * AttendeesList Component
 */
class AttendeesList extends ComponentBase
{
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
			foreach ($attendees as $attendee){
				foreach ($attendee as $question){
					if (strpos($question['question'], 'mail') !== false) {
						$mails[$question['attendee_id']] = $question['attendee_answers'][0]['answer'];
					}
				}
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
}
