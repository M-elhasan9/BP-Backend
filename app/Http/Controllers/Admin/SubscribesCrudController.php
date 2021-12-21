<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\SubscribesRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class SubscribesCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class SubscribesCrudController extends CrudController
{
    use ListOperation;
    use CreateOperation;
    use UpdateOperation;
    use DeleteOperation;
    use ShowOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     *
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\Subscribes::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/subscribes');
        CRUD::setEntityNameStrings('subscribes', 'subscribes');
        $this->crud->denyAccess('create');

    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        CRUD::addColumn([
            'name'=>'id',
            'type'=>'text',
            'label'=>'Subscribe ID',
        ]);
        CRUD::addColumn(['name' => 'created_at', 'type' => 'datetime', 'label' => "Subscribed at"]);
        CRUD::addColumn(['name' => 'users.phone', 'type' => 'text', 'label' => "Subscribed User"]);


        /**
         * Columns can be defined using the fluent syntax or array syntax:
         * - CRUD::column('price')->type('number');
         * - CRUD::addColumn(['name' => 'price', 'type' => 'number']);
         */
    }
    protected function setupShowOperation()
    {
        CRUD::addColumn([
            'name'=>'id',
            'type'=>'text',
            'label'=>'Subscribe ID',
        ]);
        CRUD::addColumn(['name' => 'created_at', 'type' => 'datetime', 'label' => "Subscribed at"]);
        CRUD::addColumn(['name' => 'users.phone', 'type' => 'text', 'label' => "Subscribed User"]);
        CRUD::addColumn(['name' => 'lat_lang', 'type' => 'latlng_map', 'label' => "Location"]);
        CRUD::addColumn(['name' => 'description', 'type' => 'text', 'label' => "Description"]);


        /**
         * Columns can be defined using the fluent syntax or array syntax:
         * - CRUD::column('price')->type('number');
         * - CRUD::addColumn(['name' => 'price', 'type' => 'number']);
         */
    }


    /**
     * Define what happens when the Create operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(SubscribesRequest::class);

        CRUD::addField([
            'name'=>'id',
            'type'=>'text',
            'label' => "Subscribe ID",
            'attributes' => [
                'readonly' => 'readonly',
            ],
        ]);
        CRUD::addField(['name' => 'created_at', 'type' => 'datetime', 'label' => "Subscribed at"]);
        CRUD::addField(['name' => 'users_id',
            'type' => 'select2',
            'label' => "Subscribed User",
            'entity'    => 'users', // the method that defines the relationship in your Model
            'attribute' => 'phone',
        ]);
        CRUD::addField([
            'name' => 'lat_lang',
            'label' => "Location",
            'type' => 'latlng',
            'google_api_key' => config('services.google_places.key'),
            'map_style' => 'height: 300px; width:auto',
            'default_zoom' => 17,
            'geolocate_icon' => 'fa-crosshairs',
            "attr" => "address",
            'marker_icon' => null
        ]);
        CRUD::addField(['name' => 'description', 'type' => 'text', 'label' => "Description"]);



        /**
         * Fields can be defined using the fluent syntax or array syntax:
         * - CRUD::field('price')->type('number');
         * - CRUD::addField(['name' => 'price', 'type' => 'number']));
         */
    }

    /**
     * Define what happens when the Update operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-update
     * @return void
     */
    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }
}
