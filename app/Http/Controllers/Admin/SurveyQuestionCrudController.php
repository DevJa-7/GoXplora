<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\SurveyQuestionRequest as StoreRequest;
// VALIDATION: change the requests to match your own file names if you need form validation
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class SurveyQuestionCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class SurveyQuestionCrudController extends CrudController
{
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
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        CRUD::setModel('App\Models\SurveyQuestion');
        CRUD::setRoute(config('backpack.base.route_prefix') . '/survey');
        CRUD::setEntityNameStrings(__('survey question'), __('survey questions'));

    }

    protected function setupListOperation() 
    {
        CRUD::addColumn([
            'name' => 'title',
            'label' => __('Title'),
        ]);

        CRUD::addColumn([
            'name' => 'type',
            'label' => __('Type'),
            'type' => 'array_trans',
        ]);

        // CRUD::addColumn([
        //     'name' => 'module_id',
        //     'label' => ucfirst(__('module')),
        //     'type' => 'select',
        //     'entity' => 'module',
        //     'attribute' => 'title',
        //     'model' => "App\Models\Module",
        // ]);
    
        CRUD::orderBy('lft');

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
            'name' => 'type',
            'label' => __('Type'),
            'type' => 'enum',
        ]);

        CRUD::addField([
            'name' => 'options',
            'label' => __('Options'),
            'type' => 'table',
            'columns' => ['option' => __('Option')],
        ]);

        /*CRUD::addField([
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
        ]);*/

    }

    protected function setupReorderOperation()
    {
        CRUD::set('reorder.label', 'title');
        CRUD::set('reorder.max_level', 1);   
    }

    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }
}
