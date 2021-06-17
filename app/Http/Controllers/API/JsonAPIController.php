<?php

namespace App\Http\Controllers\API;

use App\Models\Agreement;
use App\Models\AgreementToggle;
use App\Models\Country;
use App\Models\Page;
use App\Models\SurveyQuestion;
use Backpack\LangFileManager\app\Models\Language;
use Cache;
use Config;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;

class JsonAPIController extends APIController
{

    public function getJsons(Request $request)
    {
        Cache::clear();

        $languages = Cache::rememberForever('language', function () {
            $languages = Language::select('id', 'name', 'flag', 'abbr', 'script', 'native', 'default')->where('active', 1)->get();

            return $languages;
        });

        $translations = Cache::rememberForever('translations', function () {
            $locales = Config::get('backpack.crud.locales');

            $result = [];
            foreach ($locales as $key => $value) {
                $result[$key] = Lang::get('app', [], $key);
            }

            return $result;
        });

        $countries = Cache::rememberForever('countries', function () {
            $countries = Country::select('id', 'iso_3166_2', 'name', 'capital', 'currency', 'calling_code', 'flag')->where('active', 1)->get();

            return $countries;
        });

        $pages = Cache::rememberForever('pages', function () {
            $slugs = [
                'intro',
                'agreement',
                'contacts',
                'history',
                'tutorial',
            ];
            $jsonKeys = [
                'contacts',
                'videos',
                'audios'
            ];

            $pages = Page::selectRaw('slug, extras_translatable as translatable, extras as texts')->whereIn('slug', $slugs)->get();

            $result = [];
            foreach ($pages as $page) {
                $key = str_replace('-', '_', $page->slug);

                $translatable = $page->translatable;
                $texts = $page->texts ?: [];

                // Invert translation key values
                foreach ($translatable as $lang => $entries) {
                    foreach ($entries as $k => $v) {
                        if($k == 'history_title')
                            $k = 'title';
                        if($k == 'content'){
                            $this->StripTag( $v);

                        }

                        $texts[$k][$lang] = $v;
                    }

                }
                
                // Parse jsons
                if ($texts) {
                    foreach ($texts as $k => $v) {
                        if (in_array($k, $jsonKeys)) {
                            if (is_array($texts[$k])) {
                                foreach ($texts[$k] as $lang => &$text) {
                                    $text = json_decode($text);
                                }
                            } else {
                                $texts[$k] = json_decode($texts[$k]);
                            }
                        }
                    }
                }

                // Special rule for image field
                if ($page->texts) {
                    foreach ($texts as $k => $v) {
                        if ($k == 'image' && substr($v, 0, 4) !== 'http') {
                            $texts[$k] = 'uploads/' . $v;
                        }
                    }
                }

                // Images
                if (isset($texts['image'])) {
                    foreach ($texts['image'] as $lang => $image_url) {
                        if (substr($image_url, 0, 4) !== 'http') {
                            $url = 'uploads/' . sized_image($image_url, 1280);
                            $url_thumb = 'uploads/' . sized_image($image_url, 384);
                        } else {
                            $url = $image_url;
                            $url_thumb = $image_url;
                        }

                        if (isset($texts['image_title']) && $image_url) {
                            foreach ($texts['image_title'] as $lang => $title) {
                                $texts['image'][$lang] = [
                                    'title' => $title,
                                    'url' => $url,
                                    'url_thumb' => $url_thumb,
                                ];
                            }
                        }

                        unset($texts['image_title']);
                    }

               
                   // $this->PreventNullMedia($texts['images']);

                }

                if (isset($texts['images'])) {
                    $imgs = 0;
                    foreach ($texts['images'] as $lang => $images) {
                        
                        /*$imagesArr = json_decode($images);
                        foreach ($imagesArr as $img => &$text) {
                            $text = json_decode($text);
                            $k = count($text);
                            if($k > 0){
                                for($i = 0; $i < $k; $i++){
                                    $imgs[$i] = [
                                        'title' => $k,
                                        'url' => $text[$i],
                                        'url_thumb' => "",
                                    ];
                                }
                            }
                        }*/
                        
                        
                        /*$k = count($images);
                        if($k > 0){
                            for($i = 0; $i < $k; $i++){
                                $imgs[$i] = [
                                    'title' => $k,
                                    'url' => $images[$i],
                                    'url_thumb' => "",
                                ];
                            }
                        }

                        
                        
                        */

                        $texts['images'][$lang] = json_decode($images);

                        /*foreach ($imagesjson as $data) {
                            if (isset($data->url)) {
                                $media[] = $data->url;
                            }

                            if (isset($data->url_thumb)) {
                                $media[] = $data->url_thumb;
                            }
                        }*/
                    }
                    $this->PreventNullMedia($texts['images']);
                    
                }

                   /*

                $this->StripTag($module['content']);
                
               
                $this->PreventNullMedia($module['images']);
                   */ 

                $result[$key] = $texts;

                // html_entity_decode
                // foreach ($result[$key] as &$entries) {
                //     foreach ($entries as $lang => &$entry) {
                //         if (is_string($entry)) {
                //             $entry = html_entity_decode($entry);
                //         }
                //     }
                // }
            }

            return $result;
        });

        $countries_translations = Cache::rememberForever('countries_translations', function () use ($countries) {
            $countryKeys = [];
            foreach ($countries as $country) {
                $countryKeys[$country->iso_3166_2] = $country->id;
            }

            $countries_translations = [];
            foreach (array_keys(Config::get('backpack.crud.locales')) as $locale) {

                $locale = map_transform($locale, [
                    'pt' => 'pt_PT',
                ]);

                foreach (include base_path() . "/vendor/umpirsky/country-list/data/$locale/country.php" as $key => $value) {
                    if (isset($countryKeys[$key])) {
                        $countries_translations[$locale][$countryKeys[$key]] = $value;
                    }

                }
            }

            return $countries_translations;
        });

        $settings = Cache::rememberForever('settings', function () {
            $settings = DB::table('settings')->select('key', 'value', 'field')->get();

            foreach ($settings as $setting) {
                $value = $setting->value;
                switch (json_decode($setting->field)->type) {
                    case 'checkbox':
                        unset($setting->value);
                        $setting->value = boolval($value);
                        break;
                    case 'number':
                        unset($setting->value);
                        $setting->value = intval($value);
                        break;
                }
                unset($setting->field);
            }

            return $settings->pluck('value', 'key');
        });

        $agreement = Cache::rememberForever('agreement', function () {
            $agreement = Agreement::select('id', 'title', 'content')->get();

            return $agreement;
        });

        $agreement_toggles = Cache::rememberForever('agreement_toggles', function () {
            $agreement_toggles = AgreementToggle::select('id', 'content', 'required', 'agreements_id')->get();

            return $agreement_toggles;
        });

        $survey = Cache::rememberForever('survey', function () {
            $survey = SurveyQuestion::select('id', 'title', 'options', 'type')->get();

            return $survey;
        });

        return json_response([
            'settings' => $settings,
            'pages' => $pages,
            'languages' => $languages,
            'translations' => $translations,
            'countries_translations' => $countries_translations,
            'countries' => $countries,
            'agreements' => $agreement,
            'agreements_toggles' => $agreement_toggles,
            'survey' => $survey,
        ]);
    }

    function StripTag(&$content){
        $content = strip_tags($content);  
        $content = html_entity_decode($content, ENT_QUOTES);
          
    }

     function PreventNullMedia( &$media)
    {
        if($media ==null)
            return;

        $lastValidImgs = null;
        foreach ($media as $lang => $value) {
            $lastValidImgs = $value != null ? $media[$lang] : $lastValidImgs;      
        }

        foreach ($media as $lang => $value) {
            $media[$lang] =  $value == null ?  $lastValidImgs :  $media[$lang];
            if($media[$lang] == null)
                continue;
            $mo = [];
            $ms = count($media[$lang]);
            for ($i = 0; $i < $ms; $i++) { 
                if(isset($media[$lang][$i])){
                $ss = count($media[$lang][$i]);
                if($ss > 0)
                    $mo[]=$media[$lang][$i];    
                }
            }

            $media[$lang] = $mo;

          
        }    

    }
}
