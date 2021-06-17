<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\GameAnswerRequest as StoreRequest;
// VALIDATION: change the requests to match your own file names if you need form validation
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class GameAnswerCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class GameAnswerCrudController extends CrudController
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
        CRUD::setModel('App\Models\GameAnswer');
        CRUD::setRoute(config('backpack.base.route_prefix') . '/game/answer');
        CRUD::setEntityNameStrings(__('answer'), __('answers'));

        CRUD::denyAccess(['update', 'create']);

    }

    protected function setupListOperation() 
    {
        CRUD::addColumn([
            'name' => 'question_id',
            'label' => __('Question'),
            'type' => 'select',
            'entity' => 'question',
            'attribute' => 'title',
            'model' => "App\Models\Question",
        ]);

        CRUD::addColumn([
            'name' => 'user_id',
            'label' => __('User'),
            'type' => 'select',
            'entity' => 'user',
            'attribute' => 'name',
            'model' => "App\User",
        ]);

        CRUD::addColumn([
            'name' => 'correct',
            'label' => ucfirst(__('correct')),
            'type' => 'check',
        ]);

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

    }

    protected function setupCreateOperation()
    {
        CRUD::setValidation(StoreRequest::class);

    }

}
