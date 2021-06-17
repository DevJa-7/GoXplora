<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Traits\Permissions;
use Backpack\PageManager\app\Http\Requests\PageRequest as UpdateRequest;

class PageCrudController extends \Backpack\PageManager\app\Http\Controllers\Admin\PageCrudController
{
    use Permissions;

    public function setup($template_name = false)
    {
        parent::setup($template_name);

        if (!is('admin')) {
            $this->crud->denyAccess(['list', 'update']);
        }

        $this->crud->denyAccess(['create', 'delete']);
    }

    public function addDefaultPageFields($template = false)
    {
        $result = parent::addDefaultPageFields($template);
        // $this->crud->modifyField('template', ['readonly' => 'readonly', 'style' => 'pointer-events: none;']);
        // $this->crud->modifyField('slug', ['attributes' => ['readonly' => 'readonly']]);

        return $result;
    }

    public function update(UpdateRequest $request)
    {           
        \Cache::forget("page_{$request->slug}_{$request->locale}");        
        \Cache::forget('pages');

        return parent::update($request);
    }
}
