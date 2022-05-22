<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\ReportsRequest;
use App\Models\Fire;
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
        $this->crud->set('show.setFromDb',false);
        $this->crud->denyAccess('create');
        $this->crud->denyAccess('delete');
        $this->crud->denyAccess('update');


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
        CRUD::addColumn([
            'name' => 'nn_approval',
            'type' => 'boolean',
            'label'=>'NN Approval',
            'option' => [1 => 'Yes', 0 => 'No'],
            'wrapper' => [
                'element' => 'span',
                'class'   => static function ($crud, $column, $entry) {
                    return 'badge badge-'.($entry->{$column['name']} ? 'success' : 'default');
                },
            ],
        ],);
        CRUD::addColumn(['name' => 'den_degree', 'type' => 'text', 'label' => "Degree"]);
        CRUD::addColumn([
            "name" => "image",
            "type" => "image",
            'label' => "Image",
            'upload' => true,
            'crop' => true,
            'prefix' => "/storage/"
        ]);
        CRUD::addColumn(['name' => 'description', 'type' => 'text', 'label' => "Description"]);

        CRUD::addColumn(['name' => 'fire_id',
            'type' => 'select',
            'entity' => 'fire',
            'attribute' => 'id',
            'model' => Fire::class,
            'wrapper' => [
                'href' => function ($crud, $column, $entry, $related_key) {
                    return backpack_url('fire/'.$related_key.'/show');
                },
            ],
            'label' => "Fire ID"]);

//
//        CRUD::column('fire_id')
//            ->lable("Fire ID")
//            ->type('select')
//            ->entity('fire')
//            ->attribute('id')
//            ->model(Fire::class)
//            ->wrapper([
//                'href' => function ($crud, $column, $entry, $related_key) {
//                    return backpack_url('fire/'.$related_key.'/show');
//                },
//            ]);


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
        CRUD::addColumn(['name' => 'lat_lang', 'type' => 'latlng_map', "label" => "Location"]);
        CRUD::addColumn(['name' => 'created_at', 'type' => 'datetime', 'label' => "Reported at"]);
        CRUD::addColumn(['name' => 'description', 'type' => 'text', 'label' => "Description"]);
        CRUD::addColumn(['name' => 'den_degree', 'type' => 'text', 'label' => "Degree"]);

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



}
