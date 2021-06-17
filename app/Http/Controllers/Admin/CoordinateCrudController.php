<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\CoordinateRequest as StoreRequest;
// VALIDATION: change the requests to match your own file names if you need form validation
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class CoordinateCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class CoordinateCrudController extends CrudController
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
        CRUD::setModel('App\Models\Coordinate');
        CRUD::setRoute(config('backpack.base.route_prefix') . '/coordinate');
        CRUD::setEntityNameStrings(__('coordinate'), __('coordinates'));

    }

    protected function setupListOperation() 
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // Columns
        CRUD::addColumn([
            'name' => 'title',
            'label' => __('Title'),
            'type' => 'text',
        ]);

        CRUD::addColumn([
            'name' => 'latitude',
            'label' => __('Latitude'),
            'type' => 'text',
        ]);

        CRUD::addColumn([
            'name' => 'longitude',
            'label' => __('Longitude'),
            'type' => 'text',
        ]);

        CRUD::addFilter([
            'name' => 'distance',
            'type' => 'range',
            'label' => __('Order by distance'),
            'label_from' => __('Min value'),
            'label_to' => __('Max value'),
        ],
            true,
            function ($value) {
                $range = json_decode($value);

                if (is_numeric($range->from) && is_numeric($range->to)) {
                    $this->crud->query
                        ->selectRaw("ST_Distance(position, ST_GeomFromText('POINT({$range->to} {$range->from})')) as distance")
                        ->orderBy('distance');
                }

        });

    }

    protected function setupCreateOperation()
    {
        CRUD::setValidation(StoreRequest::class);
        
        // Fields
        CRUD::addField([
            'name' => 'title',
            'label' => __('Title'),
            'type' => 'text',
        ]);

        CRUD::addField([
            'name' => 'radius',
            'label' => ucfirst(__('radius')),
            'type' => 'number',
            'suffix' => __('meters'),
            'attributes' => [
                'step' => '1',
                'min' => '2',
                'max' => '100',
            ],
        ]);

        CRUD::addField([
            'label' => __('Location'),
            'type' => 'latlng',
            'name' => 'location',
            'map_style' => 'width:100%; height:320px;',
            'google_api_key' => env('GOOGLE_API_KEY'),
            'default' => [
                'zoom' => 14,
                'lat' => 40.7292346,
                'long' => -6.929525,
            ],
        ]);

    }

    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }
}
