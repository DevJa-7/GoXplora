<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\AgreementRequest as StoreRequest;
// VALIDATION: change the requests to match your own file names if you need form validation
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class AgreementCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class AgreementCrudController extends CrudController
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
        CRUD::setModel('App\Models\Agreement');
        CRUD::setRoute(config('backpack.base.route_prefix') . '/agreement');
        CRUD::setEntityNameStrings(__('agreement'), __('agreements'));

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

    }

    protected function setupCreateOperation()
    {
        CRUD::setValidation(StoreRequest::class);

        CRUD::addField([
            'name' => 'title',
            'label' => __('Title'),
            'type' => 'text',
            'attributes' => [
                'min' => 2,
                'max' => 255,
            ],
        ]);

        CRUD::addField([
            'name' => 'content',
            'label' => __('Content'),
            'type' => 'textarea',
            'attributes' => [
                'min' => 2,
            ],
        ]);
    }

    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }
}
