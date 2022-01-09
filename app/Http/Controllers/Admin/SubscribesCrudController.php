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
        CRUD::setEntityNameStrings('subscribe', 'subscribes');

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

    }

}
