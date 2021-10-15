<?php namespace Pensoft\Eventsextension;

use Backend;
use Pensoft\Calendar\Controllers\Entries;
use Pensoft\Calendar\Models\Entry;
use System\Classes\PluginBase;

/**
 * Eventsextension Plugin Information File
 */
class Plugin extends PluginBase
{

	public $require = ['Pensoft.Calendar', 'Rainlab.Location'];
    /**
     * Returns information about this plugin.
     *
     * @return array
     */
    public function pluginDetails()
    {
        return [
            'name'        => 'Eventsextension',
            'description' => 'No description provided yet...',
            'author'      => 'Pensoft',
            'icon'        => 'icon-calendar'
        ];
    }

    /**
     * Register method, called when the plugin is first registered.
     *
     * @return void
     */
    public function register()
    {

    }

    /**
     * Boot method, called right before the request route.
     *
     * @return array
     */

	public function boot()
	{
		//Extending Entry Model and add status relation
		Entry::extend(function($model) {
			if (!$model instanceof \Pensoft\Calendar\Models\Entry) {
				return;
			}

			$model->belongsTo['status'] = [
				'Pensoft\Eventsextension\Models\Status'
			];
		});

		if(class_exists('\Pensoft\Calendar\Controllers\Entries')){
			Entries::extendFormFields(function($form, $model){
				if (!$model instanceof \Pensoft\Calendar\Models\Entry) {
					return;
				}

				$form->addFields([
					'summary' => [
						'label' => 'Summary',
						'span' => 'auto',
						'type' => 'richeditor',
						'size' => 'large',
						'required' => 1
					],
					'currency' => [
						'label' => 'Currency',
						'span' => 'auto',
						'type' => 'text',
						'size' => 'autogrow',
						'comment' => 'EUR, GBP, USD, BGN',
						'required' => 1,
						'default' => 'EUR'
					],
					'invite_only' => [
						'label' => 'Invite only',
						'span' => 'auto',
						'type' => 'checkbox',
						'default' => false,
					],
					'show_remaining' => [
						'label' => 'Show remaining',
						'span' => 'auto',
						'type' => 'checkbox',
						'default' => false,
					],
					'online_event' => [
						'label' => 'Online event',
						'span' => 'auto',
						'type' => 'checkbox',
						'default' => false,
					],
					'active' => [
						'label' => 'Active',
						'span' => 'auto',
						'type' => 'checkbox',
						'default' => true,
					],
					'capacity' => [
						'label' => 'Capacity',
						'span' => 'auto',
						'type' => 'text',
						'default' => '1',
						'required' => 1
					],
					'password' => [
						'label' => 'Password',
						'span' => 'auto',
						'type' => 'text',
					],
					'organizer' => [
						'label' => 'Organizer',
						'span' => 'auto',
						'type' => 'text',
					],
					'status' => [
						'label' => 'Status',
//						'emptyOption' => '-- choose --',
						'span'  => 'auto',
						'type'  => 'relation',
						'select'  => 'name',
						'options' => Models\Status::all()->lists('name', 'id'),
						'nameFrom' => 'name'
					],
					'thank_you_message' => [
						'label' => 'Thank you message',
						'span' => 'auto',
						'type' => 'richeditor',
						'size' => 'large',
						'required' => 0
					],

				]);

				$form->removeField('color');
				$form->removeField('index');
				$form->removeField('identifier');
				$form->removeField('all_day');

			});
		}
	}

    /**
     * Registers any front-end components implemented in this plugin.
     *
     * @return array
     */
    public function registerComponents()
    {

        return [
            'Pensoft\Eventsextension\Components\EventsList' => 'EventsList',
            'Pensoft\Eventsextension\Components\EventsRegisterForm' => 'EventsRegisterForm',
        ];
    }

    /**
     * Registers any back-end permissions used by this plugin.
     *
     * @return array
     */
    public function registerPermissions()
    {
        return []; // Remove this line to activate

        return [
            'pensoft.eventsextension.some_permission' => [
                'tab' => 'Eventsextension',
                'label' => 'Some permission'
            ],
        ];
    }

    /**
     * Registers back-end navigation items for this plugin.
     *
     * @return array
     */
    public function registerNavigation()
    {
        return []; // Remove this line to activate

        return [
            'eventsextension' => [
                'label'       => 'Eventsextension',
                'url'         => Backend::url('pensoft/eventsextension/mycontroller'),
                'icon'        => 'icon-leaf',
                'permissions' => ['pensoft.eventsextension.*'],
                'order'       => 500,
            ],
        ];
    }
}
