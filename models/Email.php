<?php namespace Pensoft\Eventsextension\Models;

use Model;

/**
 * Model
 */
class Email extends Model
{
    use \October\Rain\Database\Traits\Validation;
    

    /**
     * @var string The database table used by the model.
     */
    public $table = 'pensoft_eventsextension_emails';

    /**
     * @var array Validation rules
     */
    public $rules = [
    ];
}
