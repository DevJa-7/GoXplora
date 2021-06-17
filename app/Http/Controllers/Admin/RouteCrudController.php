<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\RouteRequest as StoreRequest;
// VALIDATION: change the requests to match your own file names if you need form validation
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class RouteCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class RouteCrudController extends CrudController
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
        CRUD::setModel('App\Models\Route');
        CRUD::setRoute(config('backpack.base.route_prefix') . '/route');
        CRUD::setEntityNameStrings(__('route'), __('routes'));
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
            'name' => 'bounding_top',
            'label' => __('Bounding Top'),
        ]);

        CRUD::addColumn([
            'name' => 'bounding_right',
            'label' => __('Bounding Right'),
        ]);

        CRUD::addColumn([
            'name' => 'bounding_bottom',
            'label' => __('Bounding Bottom'),
        ]);

        CRUD::addColumn([
            'name' => 'bounding_left',
            'label' => __('Bounding Left'),
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
            'name' => 'image',
            'label' => __('Image'),
            'type' => 'image',
            'upload' => true,
            'crop' => true,
            'disk' => 'uploads',
        ]);

        CRUD::addField([
            'name' => 'bounding_top',
            'label' => __('Bounding Top'),
            'type' => 'number',
            'attributes' => [
                'min' => 0,
                'max' => 180,
                'step' => 0.000001,
            ],
        ]);

        CRUD::addField([
            'name' => 'bounding_right',
            'label' => __('Bounding Right'),
            'type' => 'number',
            'attributes' => [
                'min' => 0,
                'max' => 180,
                'step' => 0.000001,
            ],
        ]);

        CRUD::addField([
            'name' => 'bounding_bottom',
            'label' => __('Bounding Bottom'),
            'type' => 'number',
            'attributes' => [
                'min' => 0,
                'max' => 180,
                'step' => 0.000001,
            ],
        ]);

        CRUD::addField([
            'name' => 'bounding_left',
            'label' => __('Bounding Left'),
            'type' => 'number',
            'attributes' => [
                'min' => 0,
                'max' => 180,
                'step' => 0.000001,
            ],
        ]);

        CRUD::addField([
            'name' => 'modules',
            'label' => ucfirst(__('modules')),
            'type' => 'select2_from_ajax_multiple',
            'entity' => 'modules',
            'attribute' => 'detail',
            'model' => "App\Models\Module",
            'data_source' => url('admin/module/ajax/search/'),
            'placeholder' => __('Select a Module'),
            'minimum_input_length' => 2,
            'pivot' => true,
        ]);

        // CRUD::addField([
        //     'name' => 'route_modules',
        //     'type' => 'select2_from_ajax_multiple_orderable',
        //     'label' => __('Reorder'),
        //     'model' => 'module-route',
        // ], 'update');

    }

    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }

}
