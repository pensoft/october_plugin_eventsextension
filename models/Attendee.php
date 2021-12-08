<?php namespace Pensoft\Eventsextension\Models;

use Model;

/**
 * Model
 */
class Attendee extends Model
{
    use \October\Rain\Database\Traits\Validation;
    
    /*
     * Disable timestamps by default.
     * Remove this line if timestamps are defined in the database table.
     */
    public $timestamps = false;


    /**
     * @var string The database table used by the model.
     */
    public $table = 'pensoft_eventsextension_attendee';

    /**
     * @var array Validation rules
     */
    public $rules = [
    ];

    public $belongsTo = [
    	'attendee_profile' => 'Pensoft\Eventsextension\Models\Attendee',
		'order' => 'Pensoft\Eventsextension\Models\Order',
		'event' => ['Pensoft\Calendar\Models\Entry', 'scope' => 'notdeleted', 'order' => 'id desc'],
	];

    public $hasMany = [
    	'attendee_questions' => ['Pensoft\Eventsextension\Models\AttendeeQuestion', 'orderBy' => 'order'],
		'attendee_question_names' => ['Pensoft\Eventsextension\Models\AttendeeQuestion', 'scope' => 'byName'],
	];

    public $hasOne = [
		'attendee_question_email' => ['Pensoft\Eventsextension\Models\AttendeeQuestion', 'scope' => 'byEmail']
	];

}
