<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\FireRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class FireCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class FireCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     *
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\Fire::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/fire');
        CRUD::setEntityNameStrings('fire', 'fires');
        $this->crud->enableExportButtons();
        $this->crud->denyAccess('create');
        $this->crud->denyAccess('delete');
    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        CRUD::addColumn(['name' => 'id', 'type' => 'text', 'label' => "Fire ID"]);
        CRUD::addColumn([
            'name' => 'lat_lang',
            'label' => "Location",
            'type' => 'latlng_map',
            'google_api_key' => config('services.google_places.key'),
            'map_style' => 'height: 300px; width:auto',
            'default_zoom' => 17,
            'geolocate_icon' => 'fa-crosshairs',
            "attr" => "lat_lang",
            'marker_icon' => null
        ]);
        CRUD::addColumn([   // select_and_order
            'name'  => 'status',
            'label' => "Status",
            'type'  => 'select2_from_array',
            'allows_null' => false,
            'options' => [
                1 => "New",
                2 => "Confirmed",
                3 => "End",
            ]
        ],);
        CRUD::addColumn(['name' => 'den_degree', 'type' => 'text', 'label' => "Degree of Danger"]);


        /**
         * Columns can be defined using the fluent syntax or array syntax:
         * - CRUD::column('price')->type('number');
         CRUD::addColumn(['name' => 'price', 'type' => 'number']);
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
        CRUD::setValidation(FireRequest::class);

        CRUD::addField([   // select_and_order
            'name'  => 'status',
            'label' => "Status",
            'type'  => 'select2_from_array',
            'allows_null' => false,
            'options' => [
                1 => "New",
                2 => "Confirmed",
                3 => "End",
            ]
        ],);

        CRUD::addField(['name' => 'den_degree', 'type' => 'number', 'label' => "Degree of Danger"]);





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
    protected function setupShowOperation(){

        $this->setupListOperation();
    }
}
