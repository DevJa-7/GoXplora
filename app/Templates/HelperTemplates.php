<?php

namespace App\Templates;

use App\Helpers\EnumHelper;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

trait HelperTemplates
{
    private $uniqueID = 0;
    public function uniqueID($id = null)
    {
        return isset($id) ? $id : ++$this->uniqueID;
    }

    public function header($title)
    {
        CRUD::addField([
            'name' => 'content_separator' . $this->uniqueID(),
            'type' => 'custom_html',
            'value' => '<hr><h2>' . $title . '</h2>',
        ]);
    }

    public function separator()
    {
        $this->crud->addField([
            'name' => 'separator' . $this->uniqueID(),
            'type' => 'custom_html',
            'value' => '<hr />',
            'wrapperAttributes' => [
                'style' => 'margin:0',
            ],
        ]);
    }

    public function multi_audios($data = [])
    {
        $id = $data['id'] ?? null;
        $min = $data['min'] ?? 0;
        $max = $data['max'] ?? 5;
        $slug = $data['slug'] ?? '';
        $header = $data['header'] ?? null;
        $label = $data['label'] ?? '';

        // -----
        $this->header(__('Audios') . ($header ? ' ' . $header : ''));

        $data = [
            // 'fake' => true,
            'name' => 'audios' . $slug,
            'type' => 'browse_table',
            'label' => $label,
            'entity_singular' => 'video',
            'max' => $max,
            'min' => $min,

            'columns' => [
                'title' => [
                    'label' => __('Title'),
                    'type' => 'input',
                    'width' => '160',
                ],
                'url' => [
                    'label' => 'URL' . ' ' . __('Audio'),
                    'type' => 'browse',
                    'mimes' => 'audio',
                    'name' => 'url',
                ],
                'type' => [
                    'label' => __('Type'),
                    'type' => 'select',
                    'options' => EnumHelper::get('module.audio_type'),
                ],
            ],

            'store_in' => 'audios',
            'mimes' => 'audio',
            'disk' => 'public',
            'readonly' => false,
        ];

        $this->crud->addField($data);
    }

    public function multi_videos($data = [])
    {
        $min = $data['min'] ?? 0;
        $max = $data['max'] ?? 5;
        $slug = $data['slug'] ?? '';
        $header = $data['header'] ?? null;
        $label = $data['label'] ?? '';

        // -----
        $this->header(__('Videos') . ($header ? ' ' . $header : ''));

        $data = [
            // 'fake' => true,
            'name' => 'videos' . $slug,
            'type' => 'browse_table',
            'label' => $label,
            'entity_singular' => 'video',
            'max' => $max,
            'min' => $min,

            'columns' => [
                'title' => [
                    'label' => __('Title'),
                    'type' => 'input',
                    'width' => '160',
                ],
                'url' => [
                    'label' => 'URL' . ' ' . __('Video'),
                    'type' => 'browse',
                    'mimes' => 'video',
                    'name' => 'url',
                ],
                'url_thumb' => [
                    'label' => 'URL' . ' ' . __('Thumbnail'),
                    'type' => 'browse',
                    'mimes' => 'image',
                    'name' => 'url_thumb',
                ],
                'type' => [
                    'label' => __('Type'),
                    'type' => 'select',
                    'options' => EnumHelper::get('module.video_type'),
                ],

            ],

            'store_in' => 'videos',
            'disk' => 'public',
            'readonly' => false,
        ];

        $this->crud->addField($data);
    }

    public function multi_images($data = [])
    {
        $min = $data['min'] ?? 0;
        $max = $data['max'] ?? 5;
        $slug = $data['slug'] ?? '';
        $header = $data['header'] ?? null;
        $label = $data['label'] ?? '';

        // -----
        $this->header(__('Images') . ($header ? ' ' . $header : ''));

        $data = [
            // 'fake' => true,
            'name' => 'images' . $slug,
            'type' => 'browse_table',
            'label' => $label,
            'entity_singular' => 'image',
            'max' => $max,
            'min' => $min,

            'columns' => [
                'title' => [
                    'label' => __('Title'),
                    'type' => 'input',
                    'width' => '160',
                ],
                'url' => [
                    'label' => 'URL' . ' ' . __('Image'),
                    'type' => 'browse',
                    'mimes' => 'video',
                    'name' => 'url',
                ],
                'url_thumb' => [
                    'label' => 'URL' . ' ' . __('Thumbnail'),
                    'type' => 'browse',
                    'mimes' => 'image',
                    'name' => 'url_thumb',
                ],

            ],

            'store_in' => 'images',
            'disk' => 'public',
            'readonly' => false,
        ];

        $this->crud->addField($data);
    }

    public function multi_docs($data = [])
    {
        $min = $data['min'] ?? 0;
        $max = $data['max'] ?? 5;
        $slug = $data['slug'] ?? null;
        $header = $data['header'] ?? true;
        $label = $data['label'] ?? '';

        // -----
        if ($header) {
            $this->header(__('Documents'));
        }

        $data = [
            // 'fake' => true,
            'name' => ($slug ? $slug . '_' : '') . 'documents',
            'type' => 'browse_table',
            'label' => $label,
            'entity_singular' => 'document',
            'max' => $max,
            'min' => $min,

            'columns' => [
                'title' => [
                    'label' => __('Title'),
                    'type' => 'input',
                    'width' => '160',
                ],
                'url' => [
                    'label' => 'URL' . ' ' . __('Document'),
                    'type' => 'browse',
                    'mimes' => 'video',
                    'name' => 'url',
                ],
                'url_thumb' => [
                    'label' => 'URL' . ' ' . __('Thumbnail'),
                    'type' => 'browse',
                    'mimes' => 'image',
                    'name' => 'url_thumb',
                ],

            ],

            'store_in' => 'documents',
            'disk' => 'public',
            'readonly' => false,
        ];

        $this->crud->addField($data);
    }

    public function multi_3d($data = [])
    {
        $min = $data['min'] ?? 0;
        $max = $data['max'] ?? 5;
        $slug = $data['slug'] ?? '';
        $header = $data['header'] ?? null;
        $label = $data['label'] ?? '';

        // -----
        $this->header(__('3D Models') . ($header ? ' ' . $header : ''));

        $data = [
            // 'fake' => true,
            'name' => 'models_3d' . $slug,
            'type' => 'browse_table',
            'label' => $label,
            'entity_singular' => 'models',
            'max' => $max,
            'min' => $min,

            'columns' => [
                'title' => [
                    'label' => __('Title'),
                    'type' => 'input',
                    'width' => '160',
                ],
                'url' => [
                    'label' => __('Model'),
                    'type' => 'browse',
                    'name' => 'url',
                ],

            ],

            'store_in' => 'models_3d',
            'disk' => 'public',
            'readonly' => false,
        ];

        $this->crud->addField($data);
    }

    public function multi_popups()
    {
        $this->header('Popups');

        $popups = $this->crud->model::find($this->id)->popup->toArray();
        // Translate popups
        $lang = \App::getLocale();
        for ($i = 0; $i < sizeof($popups); $i++) {
            $popups[$i]['description'] = str_limit(strip_tags(isset($popups[$i]['description'][$lang]) ?? ''), 120) ?? '';
            $popups[$i]['title'] = $popups[$i]['title'][$lang] ?? '';
        }

        $this->crud->addField([
            'name' => 'popups',
            'label' => '',
            'type' => 'list',
            'entity' => 'Popup',
            'values' => $popups,
            'columns' => ['title', 'description'],
            'add_extra' => '?module=' . $this->id,
        ]);
    }
}
