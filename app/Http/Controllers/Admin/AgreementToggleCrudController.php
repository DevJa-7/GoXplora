<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\AgreementToggleRequest as StoreRequest;
// VALIDATION: change the requests to match your own file names if you need form validation
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class AgreementToggleCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class AgreementToggleCrudController extends CrudController
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
        CRUD::setModel('App\Models\AgreementToggle');
        CRUD::setRoute(config('backpack.base.route_prefix') . '/agreement-toggle');
        CRUD::setEntityNameStrings(__('agreement toggle'), __('agreement toggles'));

    }

    protected function setupListOperation() 
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        CRUD::addColumn([
            'name' => 'title',
            'label' => __('Title'),
        ]);

        CRUD::addColumn([
            'name' => 'content',
            'label' => __('Content'),
        ]);

        CRUD::addColumn([
            'name' => 'agreements_id',
            'label' => ucfirst(__('agreement')),
            'type' => 'select',
            'entity' => 'agreement',
            'attribute' => 'detail',
            'model' => "App\Models\Agreement",
        ]);

        CRUD::addColumn([
            'name' => 'required',
            'label' => __('Required'),
            'type' => 'check',
        ]);

    }

    protected function setupCreateOperation()
    {
        CRUD::setValidation(StoreRequest::class);

        CRUD::addField([
            'name' => 'title',
            'label' => __('Title'),
            'type' => 'text',
        ]);

        CRUD::addField([
            'name' => 'content',
            'label' => __('Content'),
            'type' => 'textarea',
        ]);

        CRUD::addField([
            'name' => 'agreements_id',
            'label' => ucfirst(__('agreement')),
            'type' => 'select2_from_ajax',
            'entity' => 'agreement',
            'attribute' => 'detail',
            'model' => "App\Models\Agreement",
            'data_source' => url('admin/agreement/ajax/search/'),
            'placeholder' => __('Select an agreement'),
            'minimum_input_length' => 2,
        ]);

        CRUD::addField([
            'name' => 'required',
            'label' => __('Required'),
            'type' => 'checkbox',
        ]);

    }
    
    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }
}
