<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\BeaconRequest as StoreRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Class BeaconCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class BeaconCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ReorderOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\InlineCreateOperation;

    public function setup()
    {
        CRUD::setModel("App\Models\Beacon");
        CRUD::setRoute('admin/beacon');
        CRUD::setEntityNameStrings('beacon', 'beacons');
    }

    protected function setupListOperation() 
    {
        CRUD::setColumns(['reference', 'title', 'minor']);

        // CRUD::enableReorder(['minor', 'title']);
        // CRUD::allowAccess('reorder');

        CRUD::addColumn([
            'name' => 'reference',
            'label' => '#',
        ]);

        CRUD::addColumn([
            'name' => 'battery',
            'label' => __('Battery'),
            'type' => 'battery',
        ]);

        CRUD::addColumn([
            'name' => 'range',
            'label' => __('Range'),
            'type' => 'range',
        ]);

        CRUD::addColumn([
            'name' => 'description',
            'label' => __('Description'),
        ]);

        // Filtrers
        CRUD::addFilter([
            'name' => 'active',
            'type' => 'select2',
            'label' => __('Active'),
        ], [
            0 => __('Inactive'),
            1 => __('Active'),
        ], function ($value) {
            CRUD::addClause('where', 'active', $value);
        });

        CRUD::addButtonFromModelFunction('line', 'toggleActive', 'toggleActive', 'end');

        // Activate All
        CRUD::addButton('top', 'activateAll', 'view', 'vendor/backpack/crud/buttons/activateAll', 'end');
        CRUD::addButton('top', 'deactivateAll', 'view', 'vendor/backpack/crud/buttons/deactivateAll', 'end');

    }

    protected function setupReorderOperation()
    {
        // model attribute to be shown on draggable items
        CRUD::set('reorder.attributes', ['minor', 'title']); 
    }

    protected function setupCreateOperation()
    {
        CRUD::setValidation(StoreRequest::class);

        CRUD::addField([
            'name' => 'reference',
            'label' => __('Reference'),
            'type' => 'text',
        ]);

        CRUD::addField([
            'name' => 'title',
            'label' => __('Title'),
            'type' => 'text',
        ]);

        CRUD::addField([
            'name' => 'minor',
            'label' => __('Minor'),
            'type' => 'number',
            'attributes' => [
                'min' => 1,
                'max' => 64,
            ],
        ]);

        CRUD::addField([
            'name' => 'range',
            'label' => __('Range'),
            'type' => 'range',
            'attributes' => [
                'min' => -150,
                'max' => 0,
                'step' => 1,
            ],
        ]);

        CRUD::addField([
            'name' => 'description',
            'label' => __('Description'),
            'type' => 'textarea',
        ]);

        CRUD::addField([
            'name' => 'local',
            'label' => __('Local'),
            'type' => 'enum',
        ]);

        CRUD::addField([
            'name' => 'active',
            'label' => __('Active'),
            'type' => 'checkbox',
        ]);

        /*CRUD::addField([
			'name' => 'battery',
			'label' => __('Battery'),
			'type' => 'range',
			'min' => 0,
			'max' => 100,
			'step' => 1,
		]);*/
        
    }

    public function toggleActive(Request $request, $id, $active)
    {
        $result = DB::table('beacons')
            ->where('id', $id)
            ->update(['active' => $active]);

        echo json_encode([
            'id' => $id,
            'active' => $result > 0 ? $active : !$active,
        ]);
    }

    public function activateAll(Request $request)
    {
        $result = DB::table('beacons')->update(['active' => 1]);
        return response(json_encode(['success' => 0]))->header('Content-Type', 'text/json');
    }

    public function deactivateAll(Request $request)
    {
        $result = DB::table('beacons')->update(['active' => 0]);
        return response(json_encode(['success' => 0]))->header('Content-Type', 'text/json');
    }

    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }
}
