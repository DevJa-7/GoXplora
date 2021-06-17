<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\SurveyQuestionRequest as StoreRequest;
// VALIDATION: change the requests to match your own file names if you need form validation
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class GameQuestionCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class GameQuestionCrudController extends CrudController
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
        CRUD::setModel('App\Models\GameQuestion');
        CRUD::setRoute(config('backpack.base.route_prefix') . '/game/question');
        CRUD::setEntityNameStrings(__('question'), __('questions'));

    }

    protected function setupListOperation() 
    {
        CRUD::addColumn([
            'name' => 'title',
            'label' => __('Title'),
        ]);

        CRUD::addColumn([
            'name' => 'module_id',
            'label' => ucfirst(__('module')),
            'type' => 'select',
            'entity' => 'module',
            'attribute' => 'title',
            'model' => "App\Models\Module",
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
            'name' => 'module_id',
            'label' => ucfirst(__('modules')),
            'type' => 'select2_from_ajax',
            'entity' => 'module',
            'attribute' => 'detail',
            'model' => "App\Models\Module",
            'data_source' => url('admin/module/ajax/search/'),
            'placeholder' => __('Select a Module'),
            'minimum_input_length' => 2,
        ]);

        CRUD::addField([
            'name' => 'option_a',
            'label' => __('Option') . ' A',
            'type' => 'text',
        ]);

        CRUD::addField([
            'name' => 'option_b',
            'label' => __('Option') . ' B',
            'type' => 'text',
        ]);

        CRUD::addField([
            'name' => 'option_c',
            'label' => __('Option') . ' C',
            'type' => 'text',
        ]);

        CRUD::addField([
            'name' => 'option_d',
            'label' => __('Option') . ' D',
            'type' => 'text',
        ]);

        CRUD::addField([
            'name' => 'correct',
            'label' => ucfirst(__('correct')),
            'options' => [
                0 => 'A',
                1 => 'B',
                2 => 'C',
                3 => 'D',
            ],
            'type' => 'select_from_array',
        ]);

        /*CRUD::addField([
            'name' => 'images',
            'label' => __('Images'),
            'type' => 'dropzone',
            'upload-url' => '/admin/dropzone/images/game',
            'thumb' => 340,
            'size' => 1280,
            'quality' => 85,
        ]);*/

    }

    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }
}
