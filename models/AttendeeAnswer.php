<?php namespace Pensoft\Eventsextension\Models;

use Model;

/**
 * Model
 */
class AttendeeAnswer extends Model
{
    use \October\Rain\Database\Traits\Validation;
	use \October\Rain\Database\Traits\Sortable;

	const SORT_ORDER = 'order';
    

    /**
     * @var string The database table used by the model.
     */
    public $table = 'pensoft_eventsextension_attendee_answer';

    /**
     * @var array Validation rules
     */
    public $rules = [
    ];

    public $fillable = ['answer'];

	public $belongsTo = [
		'attendee_question' => 'Pensoft\Eventsextension\Models\AttendeeQuestion',
//		'event' => 'Pensoft\Eventsextension\Models\AttendeeQuestion',
//		'attendee' => 'Pensoft\Eventsextension\Models\Attendee',
	];

	public function getAttendeeIdAttribute()
	{
		$answer = self::find($this->id);
		return $answer->attendee_question;
	}

	public function getEventTitleAttribute()
	{

		$answer = self::find($this->id);
		if(is_object($answer->attendee_question)){
			return $answer->attendee_question->event;
		}
		return '';
	}
}
