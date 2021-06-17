<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\CategoryRequest as StoreRequest;
// VALIDATION: change the requests to match your own file names if you need form validation
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

use App\Templates\HelperTemplates;
use App\Helpers\HandleDropzoneUploadHelper;
use App\Http\Controllers\Admin\Traits\Permissions;

class CategoryCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ReorderOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\InlineCreateOperation;

    use Permissions;
    use HandleDropzoneUploadHelper;
    use HelperTemplates;
    
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | BASIC CRUD INFORMATION
        |--------------------------------------------------------------------------
        */
        CRUD::setModel("App\Models\Category");
        CRUD::setRoute(config('backpack.base.route_prefix', 'admin') . '/category');
        CRUD::setEntityNameStrings(__('category'), __('categories'));

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
            'type' => 'text',
        ]);
        CRUD::addColumn([
            'name' => 'slug',
            'label' => 'Slug',
            'type' => 'text',
        ]);
        CRUD::addColumn([
            'label' => __('Parent'),
            'type' => 'select',
            'name' => 'parent_id',
            'entity' => 'parent',
            'attribute' => 'name',
            'model' => "App\Models\Category",
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
        CRUD::addField([
            'label' => __('Parent'),
            'type' => 'select',
            'name' => 'parent_id',
            'entity' => 'parent',
            'attribute' => 'name',
            'model' => "App\Models\Category",
        ]);
    }

    protected function setupReorderOperation()
    {
        CRUD::set('reorder.label', 'name');
        CRUD::set('reorder.max_level', 2);   
    }

    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }
}
