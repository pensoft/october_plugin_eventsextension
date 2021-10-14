<?php namespace Pensoft\Eventsextension\Models;

use Model;

/**
 * Model
 */
class Ticket extends Model
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
    public $table = 'pensoft_eventsextension_ticket';

    /**
     * @var array Validation rules
     */
    public $rules = [
    ];

	public $belongsTo = [
		'event' => 'Pensoft\Calendar\Models\Entry',
	];
}
