<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\ReportsRequest;
use App\Models\Report;
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
        CRUD::setModel(\App\Models\Report::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/reports');
        CRUD::setEntityNameStrings('report', 'reports');
        $this->crud->enableExportButtons();


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
        CRUD::addColumn(['name' => 'status', 'type' => 'text', 'label' => "Status"]);
        CRUD::addColumn(['name' => 'den_degree', 'type' => 'text', 'label' => "Degree of danger"]);
        CRUD::addColumn(['name' => 'description', 'type' => 'text', 'label' => "Description"]);
        CRUD::addColumn([
            'name'     => 'reporter_type',
            'label'    => 'Reported From',
            'type'     => 'closure',
            'function' => function($entry) {
                return (($entry->reporter_type)=="App\Models\User"?"User":"Camera");
            }
        ],);
        CRUD::addColumn(['name' => 'reporter_id', 'type' => 'text', "label" => "Reporter ID"]);

    }

    protected function setupShowOperation()
    {
        CRUD::addColumn(['name' => 'id', 'type' => 'text', 'label' => "Report ID"]);
        CRUD::addColumn(['name' => 'created_at', 'type' => 'datetime', 'label' => "Reported at"]);
        CRUD::addColumn(['name' => 'description', 'type' => 'text', 'label' => "Description"]);
        CRUD::addColumn(['name' => 'status', 'type' => 'text', 'label' => "Status"]);
        CRUD::addColumn(['name' => 'den_degree', 'type' => 'text', 'label' => "Degree of danger"]);
        CRUD::addColumn(['name' => 'lat_lang', 'type' => 'latlng_map', "label" => "Location"]);
        CRUD::addColumn(['name' => 'reporter_id', 'type' => 'text', "label" => "Reporter ID"]);
        CRUD::addColumn([
            'name'     => 'reporter_type',
            'label'    => 'Reported From',
            'type'     => 'closure',
            'function' => function($entry) {
                return (($entry->reporter_type)=="App\Models\User"?"User":"Camera");
            }
        ],);

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
    protected function setupUpdateOperation()
    {
        CRUD::setValidation(ReportsRequest::class);

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
        CRUD::addField(['name' => 'description', 'type' => 'text', 'label' => "Description"]);

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
        CRUD::addField([
            "name" => "image",
            "type" => "image",
            'label' => "Image",
            'upload' => true,
            'crop' => true,
            'prefix' => "/storage/"
        ]);
    }


}
