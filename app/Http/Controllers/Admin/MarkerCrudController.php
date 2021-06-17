<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\MarkerRequest as StoreRequest;
// VALIDATION: change the requests to match your own file names if you need form validation
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class MarkerCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class MarkerCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\InlineCreateOperation;

    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        CRUD::setModel('App\Models\Marker');
        CRUD::setRoute(config('backpack.base.route_prefix') . '/marker');
        CRUD::setEntityNameStrings(__('marker'), __('markers'));
    }

    protected function setupListOperation() 
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // ------ CRUD COLUMNS
        CRUD::addColumn([
            'name' => 'title',
            'label' => __('Title'),
        ]);

        CRUD::addColumn([
            'name' => 'code',
            'label' => __('Code'),
        ]);

    }

    protected function setupCreateOperation()
    {
        CRUD::setValidation(StoreRequest::class);

        // ------ CRUD FIELDS
        CRUD::addField([
            'name' => 'title',
            'label' => __('Title'),
            'type' => 'text',
        ]);

        CRUD::addField([
            'name' => 'code',
            'label' => __('Code'),
            'type' => 'textarea',
        ]);
        
    }

    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }
    
}
