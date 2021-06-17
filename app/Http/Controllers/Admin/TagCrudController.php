<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\TagRequest as StoreRequest;
// VALIDATION: change the requests to match your own file names if you need form validation
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

use App\Templates\HelperTemplates;
use App\Helpers\HandleDropzoneUploadHelper;
use App\Http\Controllers\Admin\Traits\Permissions;

class TagCrudController extends CrudController
{
    use Permissions;
    use HandleDropzoneUploadHelper;
    use HelperTemplates;

    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ReorderOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\InlineCreateOperation;

    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | BASIC CRUD INFORMATION
        |--------------------------------------------------------------------------
        */
        CRUD::setModel("App\Models\Tag");
        CRUD::setRoute(config('backpack.base.route_prefix', 'admin') . '/tag');
        CRUD::setEntityNameStrings(__('tag'), __('tags'));

    }

    protected function setupListOperation() 
    {
        /*
        |--------------------------------------------------------------------------
        | COLUMNS AND FIELDS
        |--------------------------------------------------------------------------
        */

        // ------ CRUD COLUMNS
        CRUD::addColumn([
            'name' => 'name',
            'label' => __('Name'),
        ]);
        CRUD::addColumn([
            'name' => 'slug',
            'label' => 'Slug',
        ]);

    }

    protected function setupCreateOperation()
    {
        CRUD::setValidation(StoreRequest::class);

        // ------ CRUD FIELDS
        CRUD::addField([
            'name' => 'name',
            'label' => __('Name'),
            'type' => 'text',
        ]);
        CRUD::addField([
            'name' => 'slug',
            'label' => 'Slug (URL)',
            'type' => 'text',
            'hint' => __('Will be automatically generated from your name, if left empty.'),
            // 'disabled' => 'disabled'
        ]);
    }

    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }
}
