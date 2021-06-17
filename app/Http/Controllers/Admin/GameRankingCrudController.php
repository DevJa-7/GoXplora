<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\GameRankingRequest as StoreRequest;
// VALIDATION: change the requests to match your own file names if you need form validation
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;


/**
 * Class GameRankingCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class GameRankingCrudController extends CrudController
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
        CRUD::setModel('App\Models\GameRanking');
        CRUD::setRoute(config('backpack.base.route_prefix') . '/game/ranking');
        CRUD::setEntityNameStrings(__('ranking'), __('rankings'));

        CRUD::denyAccess(['update', 'create']);
    }

    protected function setupListOperation() 
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        CRUD::addColumn([
            'name' => 'user_id',
            'label' => __('User'),
            'type' => 'select',
            'entity' => 'user',
            'attribute' => 'name',
            'model' => "App\User",
        ]);

        CRUD::addColumn([
            'name' => 'score',
            'label' => ucfirst(__('score')),
        ]);

        CRUD::addColumn([
            'name' => 'credits',
            'label' => ucfirst(__('credits')),
        ]);

        CRUD::addColumn([
            'name' => 'total_answers',
            'label' => ucfirst(__('total answers')),
        ]);

        CRUD::addColumn([
            'name' => 'total_correct',
            'label' => ucfirst(__('total correct')),
        ]);

        CRUD::addColumn([
            'name' => 'updated_at',
            'type' => 'datetime',
            'label' => ucfirst(__('updated at')),
        ]);

        
    }

    protected function setupCreateOperation()
    {
        CRUD::setValidation(StoreRequest::class);

        CRUD::orderBy('score', 'desc');
    }

    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }
}
