<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\EnumHelper;
use App\Http\Requests\ModuleRequest as StoreRequest;
// VALIDATION: change the requests to match your own file names if you need form validation
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

use App\Templates\HelperTemplates;
use App\Helpers\HandleDropzoneUploadHelper;
use App\Http\Controllers\Admin\Traits\Permissions;

class ModuleCrudController extends CrudController
{
    use Permissions;
    use HandleDropzoneUploadHelper;
    use HelperTemplates;

    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ReorderOperation;
    // use \Backpack\CRUD\app\Http\Controllers\Operations\InlineCreateOperation;

    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | BASIC CRUD INFORMATION
        |--------------------------------------------------------------------------
        */
        CRUD::setModel("App\Models\Module");
        CRUD::setRoute(config('backpack.base.route_prefix', 'admin') . '/module');
        CRUD::setEntityNameStrings(__('module'), __('modules'));

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
            'name' => 'reference',
            'label' => '#',
        ]);

        CRUD::addColumn([
            'name' => 'title',
            'label' => __('Title'),
            'type' => 'text',
        ]);

        CRUD::addColumn([
            'name' => 'status',
            'label' => __('Status'),
            'type' => 'trans',
        ]);

        CRUD::addColumn([
            'name' => 'category_id',
            'label' => __('Category'),
            'type' => 'select',
            'entity' => 'category',
            'attribute' => 'name',
            'model' => "App\Models\Category",
        ]);

        CRUD::addColumn([
            'name' => 'featured',
            'label' => __('Featured'),
            'type' => 'check',
        ]);

    }

    protected function setupCreateOperation()
    {
        CRUD::setValidation(StoreRequest::class);

        // ------ CRUD FIELDS
        CRUD::addField([
            'name' => 'reference',
            'label' => __('Reference'),
            'type' => 'text',
        ]);

        // CRUD::addField([
        //     'name' => 'title',
        //     'label' => __('Title'),
        //     'type' => 'text',
        //     'placeholder' => 'Your title here',
        // ]);

        CRUD::addField([
            'name' => 'title',
            'label' => __('Select Test'),
            'type' => 'test',
            'model' => "Backpack\LangFileManager\app\Models\Language",
            'placeholder' => 'Select test placeholder is here.',
            'pivot' => true,
        ]);

        CRUD::addField([
            'name' => 'type',
            'label' => __('Type'),
            'type' => 'select_from_array',
            'options' => EnumHelper::translate('module.type'),
            'allows_null' => false,
        ]);

        CRUD::addField([
            'name' => 'parent_id',
            'label' => __('Parent'),
            'type' => 'select2_from_ajax',
            'entity' => 'parent',
            'attribute' => 'detail',
            'model' => "App\Models\Module",
            'data_source' => url('admin/module/ajax/search/'),
            'placeholder' => __('Select a Module'),
            'minimum_input_length' => 2,
        ]);

        CRUD::addField([
            'name' => 'slug',
            'label' => 'Slug (URL)',
            'type' => 'text',
            'hint' => __('Will be automatically generated from your title, if left empty.'),
            // 'disabled' => 'disabled'
        ]);

        CRUD::addField([
            'name' => 'content',
            'label' => __('Content'),
            'type' => 'ckeditor',
            'placeholder' => 'Your textarea text here',
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
            'name' => 'image_title',
            'label' => __('Image title'),
            'type' => 'text',
        ]);

        // $this->multi_popups();

        $this->multi_images();

        $this->multi_images([
            'slug' => '360',
            'header' => '360',
        ]);

        $this->multi_videos();

        $this->multi_audios();

        $this->multi_docs();

        $this->multi_3d();

        $this->header(ucfirst(__('survey')));

        CRUD::addField([
            'name' => 'questions',
            'label' => ucfirst(__('survey questions')),
            'type' => 'select2_from_ajax_multiple',
            'entity' => 'questions',
            'attribute' => 'detail',
            'model' => "App\Models\SurveyQuestion",
            'data_source' => url('admin/question/ajax/search/'),
            'placeholder' => __('Select a Survey'),
            'minimum_input_length' => 2,
            'pivot' => true,
        ]);

        $this->header(__('Location'));

        CRUD::addField([
            'name' => 'coordinates',
            'label' => ucfirst(__('coordinates')),
            'type' => 'select2_from_ajax_multiple',
            'entity' => 'coordinates',
            'attribute' => 'detail',
            'model' => "App\Models\Coordinate",
            'data_source' => url('admin/coordinate/ajax/search/'),
            'placeholder' => __('Select a Coordinate'),
            'minimum_input_length' => 2,
            'pivot' => true,
        ]);

        CRUD::addField([
            'name' => 'beacons',
            'label' => __('Beacons'),
            'type' => 'select2_from_ajax_multiple',
            'entity' => 'beacons',
            'attribute' => 'detail',
            'model' => "App\Models\Beacon",
            'data_source' => url('admin/beacon/ajax/search/'),
            'placeholder' => __('Select a Beacon'),
            'minimum_input_length' => 2,
            'pivot' => true,
        ]);

        CRUD::addField([
            'name' => 'markers',
            'label' => ucfirst(__('markers')),
            'type' => 'select2_from_ajax_multiple',
            'entity' => 'markers',
            'attribute' => 'detail',
            'model' => "App\Models\Marker",
            'data_source' => url('admin/marker/ajax/search/'),
            'placeholder' => __('Select a Marker'),
            'minimum_input_length' => 2,
            'pivot' => true,
        ]);

        $this->header(ucfirst(__('routes')));

        CRUD::addField([
            'name' => 'routes',
            'label' => ucfirst(__('routes')),
            'type' => 'select2_from_ajax_multiple',
            'entity' => 'routes',
            'attribute' => 'detail',
            'model' => "App\Models\Route",
            'data_source' => url('admin/route/ajax/search/'),
            'placeholder' => __('Select a Route'),
            'minimum_input_length' => 2,
            'pivot' => true,
        ]);

        $this->header(__('Meta'));

        CRUD::addField([
            'name' => 'related',
            'label' => __('Related'),
            'type' => 'select2_from_ajax_multiple',
            'entity' => 'related',
            'attribute' => 'detail',
            'model' => "App\Models\Module",
            'data_source' => url('admin/module/ajax/search/'),
            'placeholder' => __('Select a Module'),
            'minimum_input_length' => 2,
            'pivot' => true,
        ]);

        CRUD::addField([
            'name' => 'children',
            'label' => __('Children modules'),
            'type' => 'select2_from_ajax_multiple',
            'entity' => 'children',
            'attribute' => 'detail',
            'model' => "App\Models\Module",
            'data_source' => url('admin/module/ajax/search/'),
            'placeholder' => __('Select a Module'),
            'minimum_input_length' => 2,
            'pivot' => true,
        ]);

        CRUD::addField([
            'name' => 'category_id',
            'label' => ucfirst(__('category')),
            'type' => 'select2',
            'entity' => 'category',
            'attribute' => 'name',
            'model' => "App\Models\Category",
        ]);

        CRUD::addField([
            'name' => 'tags',
            'label' => ucfirst(__('tags')),
            'type' => 'select2_multiple',
            'entity' => 'tags',
            'attribute' => 'name',
            'model' => "App\Models\Tag",
            'pivot' => true,
        ]);

        CRUD::addField([
            'name' => 'status',
            'label' => __('Status'),
            'type' => 'enum',
        ]);

        CRUD::addField([
            'name' => 'featured',
            'label' => __('Featured item'),
            'type' => 'checkbox',
        ]);

        CRUD::addField([
            'name' => 'ra_range',
            'label' => __('RA visible outside range'),
            'type' => 'checkbox',
        ]);

    }

    protected function setupReorderOperation()
    {
        CRUD::set('reorder.label', 'orderName');
        CRUD::set('reorder.max_level', 2);   
    }

    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }

    public function wantsJSON()
    {
        return $this->request && strpos($this->request->headers->get('accept'), 'application/json') === 0;
    }

    // --------------------
    // Private methods

    public function getEntryID()
    {
        preg_match('/\w+\/(\d+)/', $_SERVER['REQUEST_URI'], $matches);
        return $matches && sizeof($matches) > 1 ? intval($matches[1]) : null;
    }

}
