<?php namespace Pensoft\Eventsextension\Models;

use Model;

/**
 * Model
 */
class AttendeeQuestion extends Model
{
    use \October\Rain\Database\Traits\Validation;
	use \October\Rain\Database\Traits\Sortable;

	const SORT_ORDER = 'order';
    /*
     * Disable timestamps by default.
     * Remove this line if timestamps are defined in the database table.
     */
    public $timestamps = true;

	protected $with = ['attendee_answers'];


    /**
     * @var string The database table used by the model.
     */
    public $table = 'pensoft_eventsextension_attendee_question';

    /**
     * @var array Validation rules
     */
    public $rules = [
    ];

	public $belongsTo = [
		'event' => 'Pensoft\Calendar\Models\Entry',
		'attendee' => 'Pensoft\Eventsextension\Models\Attendee',
		'order_question' => 'Pensoft\Eventsextension\Models\OrderQuestion',
		'ticket' => 'Pensoft\Eventsextension\Models\Ticket',
	];

	public $hasMany = [
		'attendee_answers' => 'Pensoft\Eventsextension\Models\AttendeeAnswer',
	];
	public $hasOne = [
		'attendee_answer' => 'Pensoft\Eventsextension\Models\AttendeeAnswer',
	];
}
