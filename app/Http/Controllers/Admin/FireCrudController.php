<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\FireRequest;
use App\Models\Camera;
use App\Models\Fire;
use App\Models\Report;
use App\Models\Subscribe;
use App\Models\User;
use App\Ntfs\FireNearUser;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Illuminate\Support\Facades\Notification;
use Prologue\Alerts\Facades\Alert;

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
        $this->crud->set('show.setFromDb', false);
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
        $this->crud->addButtonFromModelFunction("line", "Send Notification", "SendNotify", "beginning");

        CRUD::addColumn(['name' => 'id', 'type' => 'text', 'label' => "Fire ID"]);

        CRUD::addColumn(['name' => 'status',
            'label' => "Status", 'type' => 'closure', 'function' =>
                function ($entry) {
                    switch ($entry->status) {
                        case 1:
                            return "New";
                        case 2:
                            return "Confirmed";
                        case 3:
                            return "End";
                        default:
                            return "No Status";
                    }
                },]);


        CRUD::addColumn(['name' => 'den_degree',
            'label' => "Degree of Danger", 'type' => 'closure', 'function' =>
                function ($entry) {
                    switch ($entry->den_degree) {
                        case 1:
                            return "Fake";
                        case 2:
                            return "Low";
                        case 3:
                            return "Normal";
                        case 4:
                            return "High";
                        case 5:
                            return "dangerous";
                        default:
                            return "No Degree";
                    }
                },]);


        CRUD::addColumn(['name' => 'count',
            'label' => "Reports Count", 'type' => 'closure', 'function' =>
                function ($entry) {
                    return Report::query()->where('fire_id', '=', $entry->id)->count();
                },]);


        /**
         * Columns can be defined using the fluent syntax or array syntax:
         * - CRUD::column('price')->type('number');
         * CRUD::addColumn(['name' => 'price', 'type' => 'number']);
         */
    }

    /**
     * Define what happens when the Create operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupUpdateOperation()
    {
        CRUD::setValidation(FireRequest::class);

        CRUD::addField([   // select_and_order
            'name' => 'status',
            'label' => "Status",
            'type' => 'select2_from_array',
            'allows_null' => false,
            'options' => [
                1 => "New",
                2 => "Confirmed",
                3 => "End",
            ]
        ],);

        CRUD::addField([   // select_and_order
            'name' => 'den_degree',
            'label' => "Degree of Danger",
            'type' => 'select2_from_array',
            'allows_null' => false,
            'options' => [
                1 => "Fake",
                2 => "Low",
                3 => "Normal",
                4 => "High",
                5 => "dangerous",
            ]
        ],);


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
    protected function setupShowOperation()
    {
        $this->crud->addButtonFromModelFunction("line", "Send Notification", "sendNotify", "beginning");

        CRUD::addColumn(['name' => 'id', 'type' => 'text', 'label' => "Fire ID"]);
        CRUD::addColumn(['name' => 'status',
            'label' => "Status", 'type' => 'closure', 'function' =>
                function ($entry) {
                    switch ($entry->status) {
                        case 1:
                            return "New";
                        case 2:
                            return "Confirmed";
                        case 3:
                            return "End";
                        default:
                            return "No Status";
                    }
                },]);

        CRUD::addColumn(['name' => 'lat_lang', 'type' => 'latlng_map', "label" => "Location"]);

        CRUD::addColumn(['name' => 'count',
            'label' => "Reports Count", 'type' => 'closure', 'function' =>
                function ($entry) {
                    return Report::query()->where('fire_id', '=', $entry->id)->count();
                },]);

        $this->crud->addColumn([
            'name' => 'reports',
            'type' => 'datatable_view',
            'titles' => [
                'Created at', 'Description', 'Degree', 'Reporter Type'
            ],
            'columns' => [
                [
                    'name' => 'created_at',
                    'type' => 'link',
                    'link' => '/admin/reports/?/show',
                    'attribute' => 'created_at',
                    'key' => 'id'
                ],
                [
                    'name' => 'description',
                ],
                [
                    'name' => 'den_degree',
                ],
                [
                    'name' => 'reporter_type',
                    'type' => 'select',
                    'options' => [
                        User::class => 'User',
                        Camera::class => 'Camera'
                    ]
                ],
            ],
            'source' => backpack_url('reports/' . $this->crud->getCurrentEntryId()),
            'key' => 'reports',
            'tab' => "tab"]);


        CRUD::addColumn(['name' => 'den_degree',
            'label' => "Degree of Danger", 'type' => 'closure', 'function' =>
                function ($entry) {
                    switch ($entry->den_degree) {
                        case 1:
                            return "Fake";
                        case 2:
                            return "Low";
                        case 3:
                            return "Normal";
                        case 4:
                            return "High";
                        case 5:
                            return "dangerous";
                        default:
                            return "No Degree";
                    }
                },]);

    }

    public function getReport($id)
    {
        $data = Report::query()->where('fire_id', $id)->get();
        return datatables()->of($data)->toJson();
    }

    public function sendFireNotificationToNearbyUsers($fireId)
    {
        $fire = Fire::query()->findOrFail($fireId);
        $lat = $fire['lat_lang']['lat'];
        $lng = $fire['lat_lang']['lng'];

        $nearByUsersIds = Subscribe::query()
            ->whereRaw("ST_Distance_Sphere( point(JSON_EXTRACT(lat_lang, '$.lng'),JSON_EXTRACT(lat_lang, '$.lat')), point($lng,$lat) )<1000")
            ->pluck('user_id')
            ->toArray();


        $nearByUsers = User::query()->whereIn('id', $nearByUsersIds)->get();


        Notification::send($nearByUsers, new FireNearUser('fire_near_user', $fire->id));

        Alert::success("Alert Sent")->flash();
        return redirect($this->crud->route);
    }
}
