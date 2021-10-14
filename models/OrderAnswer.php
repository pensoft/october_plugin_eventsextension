<?php namespace Pensoft\Eventsextension\Models;

use Model;
use RainLab\Location\Models\Country;

/**
 * Model
 */
class OrderAnswer extends Model
{
    use \October\Rain\Database\Traits\Validation;
	use \October\Rain\Database\Traits\Sortable;

	const SORT_ORDER = 'order';
    
    /*
     * Disable timestamps by default.
     * Remove this line if timestamps are defined in the database table.
     */
    public $timestamps = false;


    /**
     * @var string The database table used by the model.
     */
    public $table = 'pensoft_eventsextension_order_answer';

    /**
     * @var array Validation rules
     */
    public $rules = [
    ];

	public $belongsTo = [
		'order_question' => 'Pensoft\Eventsextension\Models\OrderQuestion',
	];
}
