<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\CamerasRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class CamerasCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class CamerasCrudController extends CrudController
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
        CRUD::setModel(\App\Models\Cameras::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/cameras');
        CRUD::setEntityNameStrings('camera', 'cameras');
    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        CRUD::addColumn(['name' => 'id', 'type' => 'number', "label" => "Camera ID",]);
        CRUD::addColumn(['name' => 'lat_lang', 'type' => 'latlng_map', "label" => "Location",]);
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

    protected function setupShowOperation()
    {
        CRUD::addColumn(['name' => 'id', 'type' => 'text', "label" => "Camera ID",]);
        CRUD::addColumn([
            'name' => 'lat_lang',
            'label' => "Location",
            'type' => 'latlng_map',
            'google_api_key' => config('services.google_places.key'),
            'map_style' => 'height: 300px; width:auto',
            'default_zoom' => 17,
            'geolocate_icon' => 'fa-crosshairs',
            "attr" => "address",
            'marker_icon' => null
        ]);
    }

    protected function setupCreateOperation()
    {
        CRUD::setValidation(CamerasRequest::class);
        CRUD::addField([
            'name'=>'id',
            'type'=>'text',
            'attributes' => [
                'readonly' => 'readonly',
            ],
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
