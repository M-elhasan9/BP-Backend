<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;


class MapController  extends CrudController
{

    use ShowOperation;
    public function setup()
    {
        CRUD::setModel(\App\Models\Reports::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/map');
        CRUD::setEntityNameStrings('map', 'maps');
        $this->crud->setShowView('map');
    }

    protected function setupShowOperation()
    {
        $this->crud->setShowView('map');

    }

    public function map()
    {
       return view("map");
    }


}
