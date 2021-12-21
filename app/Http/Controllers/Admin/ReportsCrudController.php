<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\ReportsRequest;
use App\Models\Reports;
use App\Models\User;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class ReportsCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class ReportsCrudController extends CrudController
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
        CRUD::setModel(\App\Models\Reports::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/reports');
        CRUD::setEntityNameStrings('reports', 'reports');
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
        CRUD::addColumn(['name' => 'id', 'type' => 'text', 'label' => "Report ID"]);
        CRUD::addColumn(['name' => 'created_at', 'type' => 'datetime', 'label' => "Reported at"]);
        CRUD::addColumn(['name' => 'users.phone', 'type' => 'text', 'label' => "Reported User"]);
        CRUD::addColumn(['name' => 'status', 'type' => 'text', 'label' => "Status"]);
        CRUD::addColumn(['name' => 'den_degree', 'type' => 'text', 'label' => "Degree of danger"]);
        /**
         * Columns can be defined using the fluent syntax or array syntax:
         * - CRUD::column('price')->type('number');
         * - CRUD::addColumn(['name' => 'price', 'type' => 'number']);
         */
    }

    protected function setupShowOperation()
    {
        CRUD::addColumn(['name' => 'id', 'type' => 'text', 'label' => "Report ID"]);
        CRUD::addColumn(['name' => 'users.phone', 'type' => 'text', 'label' => "Reported User"]);
        CRUD::addColumn(['name' => 'created_at', 'type' => 'datetime', 'label' => "Reported at"]);
        CRUD::addColumn(['name' => 'status', 'type' => 'text', 'label' => "Status"]);
        CRUD::addColumn(['name' => 'den_degree', 'type' => 'text', 'label' => "Degree of danger"]);
        CRUD::addColumn(['name' => 'lat_lang', 'type' => 'latlng_map', "label" => "Location"]);
        CRUD::addColumn(['name' => 'description', 'type' => 'text', 'label' => "Description"]);

        CRUD::addColumn([
            "name" => "image",
            "type" => "image",
            'label' => "Image",
            'upload' => true,
            'crop' => true,
            'prefix' => "/storage/"
        ]);
    }

    /**
     * Define what happens when the Create operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(ReportsRequest::class);
        CRUD::addField([
            'name'=>'id',
            'label'=>'Report ID',
            'type'=>'text',
            'attributes' => [
                'readonly' => 'readonly',
            ],
        ]);
        CRUD::addField(['name' => 'created_at', 'type' => 'datetime', 'label' => "Reported at"]);
        CRUD::addField(['name' => 'users_id',
            'type' => 'select2',
            'label' => "Reported User",
            'entity'    => 'users', // the method that defines the relationship in your Model
            'attribute' => 'phone',
        ]);

        CRUD::addField(['name' => 'den_degree', 'type' => 'select_from_array',
            'label' => "Danger degree",
            'options' => [
                'High' => 'High',
                'Medium' => 'Medium',
                'Low' => 'Low',
            ],
            'allows_null' => false,
            ]);
        CRUD::addField(['name' => 'status', 'type' => 'select_from_array', 'label' => "Status",
            'options' => [
                'New' => 'New',
                'Confirmed' => 'Confirmed',
                'End' => 'End',
            ],
            'allows_null' => false,
            ]);
        CRUD::addField([
            'name' => 'lat_lang',
            'label' => "location",
            'type' => 'latlng',
            'google_api_key' => config('services.google_places.key'),
            'map_style' => 'height: 300px; width:auto',
            'default_zoom' => 17,
            'geolocate_icon' => 'fa-crosshairs',
            "attr" => "address",
            'marker_icon' => null
        ]);
        CRUD::addField(['name' => 'description', 'type' => 'text', 'label' => "Description"]);

        CRUD::addField([
            "name" => "image",
            "type" => "image",
            'upload' => true,
            'crop' => true,
            'prefix' => "/storage/"
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
