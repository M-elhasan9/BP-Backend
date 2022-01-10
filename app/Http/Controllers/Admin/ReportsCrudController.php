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
        CRUD::setEntityNameStrings('report', 'reports');}

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
        CRUD::addColumn(['name' => 'description', 'type' => 'text', 'label' => "Description"]);
        CRUD::addColumn(['name' => 'user.phone', 'type' => 'text', 'label' => "Reported User"]);
        $this->crud->enableExportButtons();


    }

    protected function setupShowOperation()
    {
        CRUD::addColumn(['name' => 'id', 'type' => 'text', 'label' => "Report ID"]);
        CRUD::addColumn(['name' => 'user.phone', 'type' => 'text', 'label' => "Reported User"]);
        CRUD::addColumn(['name' => 'created_at', 'type' => 'datetime', 'label' => "Reported at"]);
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
}
