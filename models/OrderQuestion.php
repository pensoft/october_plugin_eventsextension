<?php namespace Pensoft\Eventsextension\Models;

use Model;
use Pensoft\Calendar\Models\Entry;
use RainLab\Location\Models\Country;

/**
 * Model
 */
class OrderQuestion extends Model
{
    use \October\Rain\Database\Traits\Validation;
	use \October\Rain\Database\Traits\Sortable;

	const SORT_ORDER = 'order';

	protected $with = ['answers'];
	protected $appends = ['answers'];
    
    /*
     * Disable timestamps by default.
     * Remove this line if timestamps are defined in the database table.
     */
    public $timestamps = false;


    /**
     * @var string The database table used by the model.
     */
    public $table = 'pensoft_eventsextension_order_question';

    /**
     * @var array Validation rules
     */
    public $rules = [
    ];

	public $belongsTo = [
		'event' => ['Pensoft\Calendar\Models\Entry', 'order' => 'id desc'],
		'ticket' => 'Pensoft\Eventsextension\Models\Ticket',
	];
	

	public $hasMany = [
		'answers' => 'Pensoft\Eventsextension\Models\OrderAnswer',
	];

	public function getAnswersAttribute()
	{
		if($this->name == 'country'){
			$this->unsetRelation('answers');
			$country = new Country();
			$countries = $country::orderBy('name', 'asc')->get();
			$countries = $countries->map(function ($item, $key) {
				$item->id = $item->id;
				$item->order_question_id = $this->id;
				$item->answer = $item->name;
				$item->selected = false;
				$item->order = $item->id;
				return $item;
			});
			return $countries;
		}
	}

	public function getEventNameAttribute(){
		if((int)$this->event_id){
			$eventData = (new Entry())::where('id', (int)$this->event_id)->first();
			return strip_tags($this->name).' - visible (' . ($this->active ? 'true' : 'false') . ') - ['.$eventData->title.']';
		}else{
			return strip_tags($this->name).' - visible (' . ($this->active ? 'true' : 'false') . ') - no event object!';
		}

	}


}
