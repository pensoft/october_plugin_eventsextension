<?php namespace Pensoft\Eventsextension\Models;

use Model;

/**
 * Model
 */
class Order extends Model
{
    use \October\Rain\Database\Traits\Validation;
    
    /*
     * Disable timestamps by default.
     * Remove this line if timestamps are defined in the database table.
     */
    public $timestamps = true;


    /**
     * @var string The database table used by the model.
     */
    public $table = 'pensoft_eventsextension_order';

    /**
     * @var array Validation rules
     */
    public $rules = [
    ];
}
