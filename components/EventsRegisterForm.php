<?php namespace Pensoft\Eventsextension\Components;

use Cms\Classes\ComponentBase;
use Exception;
use Illuminate\Support\Facades\Mail;
use Multiwebinc\Recaptcha\Validators\RecaptchaValidator;
use October\Rain\Database\Model;
use October\Rain\Support\Facades\Flash;
use Pensoft\Calendar\Models\Entry;
use Pensoft\Eventsextension\Models\Attendee;
use Pensoft\Eventsextension\Models\AttendeeAnswer;
use Pensoft\Eventsextension\Models\AttendeeQuestion;
use Pensoft\Eventsextension\Models\OrderAnswer;
use Pensoft\Eventsextension\Models\OrderQuestion;
use Pensoft\Eventsextension\QuestionFormGenerator;
use System\Models\MailSetting;

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
        $this->thankYouMessage = 'Thank you!';
        if($this->page['event']){
            $this->thankYouMessage = $this->page['event']['thank_you_message'];
        }

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
		$arrRequired['g-recaptcha-response'] = [
			'required',
			new RecaptchaValidator(),
		];

		$validator = \Validator::make(
			$form = \Input::all(), $arrRequired
		);

		if($validator->fails()){
//			return \Redirect::back()->withErrors($validator);
			throw new \ValidationException($validator);
		}
        //TODO uncomment
		$attendee = new Attendee();
		$attendee->event_id = $eventId;
		$attendee->save();


		foreach($allQuestions as $key => $question){

            //TODO uncomment
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

            //TODO uncomment
			if($question['active']){
				$answers = \Input::get($question['name']);
				if($answers){
                    if(is_array($answers)){
                        foreach ($answers as $answer){
                            $attendeeAnswer = new AttendeeAnswer();
                            $attendeeAnswer->answer = $answer;
                            $attendeeAnswer->order = $attendeeQuestion->order;
                            $attendeeAnswer->attendee_question = $attendeeQuestion->id;
                            $attendeeAnswer->save();
                        }
                    }else{
                        $attendeeAnswer = new AttendeeAnswer();
                        $attendeeAnswer->answer = $answers;
                        $attendeeAnswer->order = $attendeeQuestion->order;
                        $attendeeAnswer->attendee_question = $attendeeQuestion->id;
                        $attendeeAnswer->save();
                    }
                }
			}else{
				$attendeeAnswer = new AttendeeAnswer();
				$defaultAnswer = (new OrderAnswer())->where('order_question_id', $question['id'])->first()->toArray();
				$attendeeAnswer->answer =  $defaultAnswer['answer'];
				$attendeeAnswer->order = $attendeeQuestion->order;
				$attendeeAnswer->attendee_question = $attendeeQuestion->id;
				$attendeeAnswer->save();
			}
		}



        $lAttendeeData = $this->getEventAttendeeData($eventId, $attendee->id);
        $recipientEmail = $this->getAttendeeEmail($lAttendeeData);

        if($recipientEmail){
            //SEND MAIL
            $formData = $this->formDataMailPreview($lAttendeeData);
            $lEventsData = Entry::where('id', $eventId)->first();
            $settings = MailSetting::instance();
            $vars = [
                'event_name' => $lEventsData->title,
                'event_date' => $lEventsData->event_date,
                'event_place' => $lEventsData->place,
                'formData' => $formData,
            ];
            //send mail with registration data to user
            Mail::send('pensoft.eventsextension::mail.finish_registration', $vars, function($message) use ($recipientEmail, $settings) {
                $message->to($recipientEmail);
                $message->from($settings->sender_email, $settings->sender_name);
                $message->replyTo($settings->sender_email, $settings->sender_name);
            });


            if (count(Mail::failures()) > 0){
                Flash::error('Mail not sent');
                return;
            }
        }


		return \Redirect::back()->with('message', $this->thankYouMessage);
//		return \Redirect::back()->with('success', 1);
	}

    private function getEventAttendeeData($eventId, $attendeeId){

        $orderQuestions = OrderQuestion::where('event_id', $eventId)->get()->toArray();
        $attendee = Attendee::where('event_id', $eventId)->where('id', $attendeeId)->first();
        $attendeeQuestions = $attendee->attendee_questions()->get()->toArray();
        $items = [];
        if(count($attendeeQuestions)){
            foreach($orderQuestions as $orderQuestion) {

                $orderQuestionId = $orderQuestion['id'];
                $items[0][$orderQuestionId] = strip_tags($orderQuestion['question']);

                foreach ($attendeeQuestions as $attendeeQuestion) {
                    if($orderQuestionId == $attendeeQuestion['order_question_id']){
//                        $isActive = $orderQuestion['active'];
                        $answer = [];
                        if(count($attendeeQuestion['attendee_answers'])){
                            foreach ($attendeeQuestion['attendee_answers'] as $attendeeAnswer) {
//                                if(!$isActive){
//                                    $attendeeAnswer['answer'] .= ' <a href="javascript:void(0);" onclick="onLoadEditFieldForm('. $attendeeAnswer['id'] .', \'' . $attendeeAnswer['answer'] . '\', '. $orderQuestionId .');" class="btn-danger">edit</a>';
//                                }
                                $answer[$attendeeAnswer['id']] = $attendeeAnswer['answer'];
                            }
                        }
                        $items[$attendee['id']][$orderQuestion['id']]['question'] = strip_tags($orderQuestion['question']);
                        $items[$attendee['id']][$orderQuestion['id']]['type'] = $orderQuestion['type'];
                        $items[$attendee['id']][$orderQuestion['id']]['name'] = $orderQuestion['name'];
                        $items[$attendee['id']][$orderQuestion['id']]['answer'] = $answer;
                    }
                }
            }
            return $items[$attendeeId];
        }

    }


    private function formDataMailPreview($data){

        $html = '';

        foreach ($data as $k => $item) {
            $html .= '<br><b>'.$item['question'].':</b> ' . implode(',', $item['answer']);
        }

        $html .= '<p>&nbsp;</p>';

        return $html;
    }

    private function getAttendeeEmail($data){
        foreach ($data as $item) {
            if($item['type'] == 'e' || strtolower($item['name']) == 'email'){
                return $item['answer'];
            }
        }
        return;
    }

}


