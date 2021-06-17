<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\SurveyAnswerRequest as StoreRequest;
// VALIDATION: change the requests to match your own file names if you need form validation
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class SurveyAnswerCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class SurveyAnswerCrudController extends CrudController
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
        CRUD::setModel('App\Models\SurveyAnswer');
        CRUD::setRoute(config('backpack.base.route_prefix') . '/survey-answer');
        CRUD::setEntityNameStrings(__('survey answer'), __('survey answers'));

    }

    protected function setupListOperation() 
    {
        CRUD::addColumn([
            'name' => 'question_id',
            'label' => ucfirst(__('question')),
            'type' => 'select',
            'entity' => 'question',
            'attribute' => 'title',
            'model' => "App\Models\SurveyQuestion",
        ]);

        CRUD::addColumn([
            'name' => 'user_id',
            'label' => ucfirst(__('user')),
            'type' => 'select',
            'entity' => 'user',
            'attribute' => 'name',
            'model' => "App\User",
        ]);

        CRUD::addColumn([
            'name' => 'rating',
            'label' => ucfirst(__('rating')),
        ]);

        CRUD::addColumn([
            'name' => 'answer',
            'label' => ucfirst(__('answer')),
        ]);

    }

    protected function setupCreateOperation()
    {
        CRUD::setValidation(StoreRequest::class);
      
        CRUD::addField([
            'name' => 'question_id',
            'label' => ucfirst(__('question')),
            'type' => 'select2_from_ajax',
            'entity' => 'question',
            'attribute' => 'detail',
            'model' => "App\Models\SurveyQuestion",
            'data_source' => url('admin/question/ajax/search/'),
            'placeholder' => __('Select a question'),
            'minimum_input_length' => 2,
        ]);

        CRUD::addField([
            'name' => 'rating',
            'type' => 'number',
            'label' => ucfirst(__('rating')),
        ]);

        CRUD::addField([
            'name' => 'answer',
            'label' => ucfirst(__('answer')),
        ]);

    }

    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }
}
