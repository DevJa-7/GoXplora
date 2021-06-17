<?php

namespace App\Http\Controllers\API;

use App\Models\Action;
use App\Models\Beacon;
use App\Models\Coordinate;
use App\Models\Marker;
use App\Models\Module;
use App\Models\Route;
use App\Models\SurveyQuestion;
use Auth;
use Cache;
use Config;
use DB;
use Illuminate\Http\Request;

class AppAPIContoller extends APIController
{


    function StripTag(&$content){

         //$module['content']['pt'] = strip_tags($module['content']['pt']);                
          //$module['content']['pt'] = html_entity_decode($module['content']['pt']); 
        foreach ($content as $lang => $value) {
            $content[$lang] = strip_tags($content[$lang]);  
            $content[$lang] = html_entity_decode($content[$lang]);     
        } 
    }

     function PreventNullMedia( &$media, &$media_dump)
    {
        if($media == null)
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
            for ($i=0; $i < $ms; $i++) { 
                $ss = count($media[$lang][$i]);
                if($ss > 0){
                    $mo[]=$media[$lang][$i];
                    if(isset($media[$lang][$i]['url']))
                        $media_dump[] = $media[$lang][$i]['url'];
                    if(isset($media[$lang][$i]['url_thumb']))
                        $media_dump[] = $media[$lang][$i]['url_thumb'];
                }
                
            }

            $media[$lang] = $mo;
        }    

    }

    public function getModules(Request $request)
    {
        // Clear cache on dev
        if (Config::get('app.debug')) {
            Cache::clear();
        }

        list($modules, $media) = Cache::rememberForever('modules', function () {

            $modules = Module::select('id', 'parent_id', 'reference', 'type', 'category_id', 'title', 'image_title', 'slug', 'content', 'image', 'images', 'images360', 'videos', 'audios', 'documents', 'models_3d', 'status', 'featured', 'ra_range', \DB::raw('lft as sort'))

                ->with([
                    // Categories
                    'category' => function ($query) {
                        return $query->select('id', 'name');
                    },

                    // Tags
                    'tags' => function ($query) {
                        return $query->select('id', 'name', 'slug');
                    },

                    // Beacons
                    'beacons' => function ($query) {
                        return $query->select('id');
                    },

                    // Coordinates
                    'coordinates' => function ($query) {
                        return $query->select('id');
                    },

                    // Markers
                    'markers' => function ($query) {
                        return $query->select('id');
                    },

                    // Routes
                    'routes' => function ($query) {
                        return $query->select('*');
                    },

                    // Questions
                    'questions' => function ($query) {
                        return $query->select('id');
                    },

                    // Related related
                    'related' => function ($query) {
                        return $query->select('id');
                    },

                    // Children modules
                    'children' => function ($query) {
                        return $query->select('id');
                    },
                ])
                ->get();

            $media = [];
            foreach ($modules as &$module) {
                unset($module['category_id']);
                unset($module['detail']);

                // Clean beacons
                $beacons = $module->beacons->pluck('id')->toArray();
                unset($module->beacons);
                $module->beacons = $beacons;

                // Clean coordinates
                $coordinates = $module->coordinates->pluck('id')->toArray();
                unset($module->coordinates);
                $module->coordinates = $coordinates;

                // Clean markers
                $markers = $module->markers->pluck('id')->toArray();
                unset($module->markers);
                $module->markers = $markers;

                // Clean routes
                $routes = $module->routes->pluck('id')->toArray();
                unset($module->routes);
                $module->routes = $routes;

                // Clean questions
                $questions = $module->questions->pluck('id')->toArray();
                unset($module->questions);
                $module->questions = $questions;

                // Clean modules
                $related = $module->related->pluck('id')->toArray();
                unset($module->related);
                $module->related = $related;

                // Clean children
                $children = $module->children->pluck('id')->toArray();
                unset($module->children);
                $module->children = $children;

                if ($module->image) {
                    array_push($media, 'uploads/' . sized_image($module->image, 1280));
                    array_push($media, 'uploads/' . sized_image($module->image, 384));
                }

                if ($module->images) {
                    $images = json_decode($module->images);

                    foreach ($images as $data) {
                        if (isset($data->url)) {
                            $media[] = $data->url;
                        }

                        if (isset($data->url_thumb)) {
                            $media[] = $data->url_thumb;
                        }
                    }
                }

                if ($module->images360) {
                    $images360 = json_decode($module->images360);

                    foreach ($images360 as $data) {
                        if (isset($data->url)) {
                            $media[] = $data->url;
                        }

                        if (isset($data->url_thumb)) {
                            $media[] = $data->url_thumb;
                        }
                    }
                }

                if ($module->videos) {
                    $videos = json_decode($module->videos);

                    foreach ($videos as $data) {
                        if (isset($data->url)) {
                            $media[] = $data->url;
                        }

                        if (isset($data->url_thumb)) {
                            $media[] = $data->url_thumb;
                        }
                    }
                }

                if ($module->audios) {
                    $audios = json_decode($module->audios);

                    foreach ($audios as $data) {
                        if (isset($data->url)) {
                            $media[] = $data->url;
                        }
                    }
                }

                if ($module->documents) {
                    $documents = json_decode($module->documents);

                    foreach ($documents as $data) {
                        if (isset($data->url)) {
                            $media[] = $data->url;
                        }

                        if (isset($data->url_thumb)) {
                            $media[] = $data->url_thumb;
                        }
                    }
                }

                if ($module->models_3d) {
                    foreach ($module->models_3d as $data) {
                        if (isset($data->url)) {
                            $media[] = $data->url;
                        }
                    }
                }
            }
            
            // Special rules for modules
            $modules = $modules->toArray();
            foreach ($modules as &$module) {

                if (substr($module['image'], 0, 4) !== 'http') {
                    $url = 'uploads/' . sized_image($module['image'], 1280);
                    $url_thumb = 'uploads/' . sized_image($module['image'], 384);
                } else {
                    $url = $module['image'];
                    $url_thumb = $module['image'];
                }

                $module['image'] = null;

                if ($module['image_title']) {
                    foreach ($module['image_title'] as $lang => $title) {
                        $module['image'][$lang] = [
                            'title' => $title != null ? $title : "",
                            'url' => $url,
                            'url_thumb' => $url_thumb,
                        ];
                    } 
                }

                //unset($module['image_title']);

                // Clean HTML Text                        
               $this->StripTag($module['content']);
                
                /*
                
               
                $this->PreventNullMedia($module['documents']);
                $this->PreventNullMedia($module['models_3d']);
                   */
               $this->PreventNullMedia($module['videos'],$media );
                $this->PreventNullMedia($module['images360'],$media);
                $this->PreventNullMedia($module['audios'],$media);
                $this->PreventNullMedia($module['images'],$media);
                $this->PreventNullMedia($module['images360'],$media);
            }

            $media = array_unique($media);
            $media = array_filter($media, function ($entry) {return substr($entry, 0, 4) !== 'http';});

            $mediaSizes = [];
            foreach ($media as $entry) {
                $file = public_path($entry);

                if (file_exists($file) && !is_dir($file)) {
                    $mediaSizes[] = [
                        'file' => $entry,
                        'size' => filesize(public_path($entry)),
                    ];
                }
            }
            return [$modules, $mediaSizes];
        });

        $beacons = Cache::rememberForever('beacons', function () {
            $beacons = Beacon::with([
                'modules' => function ($query) {
                    return $query->select('id');
                },
            ])->get();

            return $beacons->map(function (&$beacon) {
                $result = collect_only($beacon, [
                    'id',
                    'reference',
                    'minor',
                    'range',
                    'title',
                    'description',
                    'battery',
                    'local',
                    'active',
                ]);

                $result['modules'] = $beacon->modules->pluck('id')->toArray();

                return $result;
            });
        });

        $coordinates = Cache::rememberForever('coordinates', function () {
            $coordinates = Coordinate::with([
                'modules' => function ($query) {
                    return $query->select('id');
                },
            ])->get();

            return $coordinates->map(function (&$coordinate) {
                $result = collect_only($coordinate, [
                    'id',
                    'title',
                    'radius',
                    'active',
                ]);

                $result['modules'] = $coordinate->modules->pluck('id')->toArray();
                $result['latitude'] = $coordinate->position->getLat();
                $result['longitude'] = $coordinate->position->getLng();

                return $result;
            });
        });

        $markers = Cache::rememberForever('markers', function () {
            $markers = Marker::with([
                'modules' => function ($query) {
                    return $query->select('id');
                },
            ])->get();

            return $markers->map(function (&$marker) {
                $result = collect_only($marker, [
                    'id',
                    'title',
                    'code',
                ]);

                $result['modules'] = $marker->modules->pluck('id')->toArray();

                return $result;
            });
        });

        $menus = Cache::rememberForever('menus', function () {
            $menus = Module::select('id', 'title')
                ->where('featured', 1)
                ->orderBy('lft')
                ->get()->toArray();

            foreach ($menus as &$menu) {
                unset($menu['detail']);
            }

            return $menus;
        });

        $routes = Cache::rememberForever('routes', function () {
            $routes = Route::with([
                'modules' => function ($query) {
                    return $query->select('*')->orderBy('module_route.lft');
                },
            ])->get();

            return $routes->map(function (&$marker) {
                $result = collect_only($marker, [
                    'id', 'title', 'content', 'image',
                ]);

                $result['image'] = 'uploads/' . $result['image'];

                $result['bounding'] = [
                    'top' => $marker['bounding_top'],
                    'right' => $marker['bounding_right'],
                    'bottom' => $marker['bounding_bottom'],
                    'left' => $marker['bounding_left'],
                ];

                $result['modules'] = $marker->modules->pluck('module_id')->toArray();

                return $result;
            });
        });

        $survey = Cache::rememberForever('survey', function () {
            $survey = SurveyQuestion::select('id', 'title', 'options', 'type', 'parent_id')
                ->orderBy('lft')
                ->get()->toArray();

            foreach ($survey as &$menu) {
                unset($menu['detail']);
            }

            return $survey;
        });

        return json_response([
            'media' => $media,
            'modules' => $modules,
            'beacons' => $beacons,
            'coordinates' => $coordinates,
            'markers' => $markers,
            'menus' => $menus,
            'routes' => $routes,
            'survey' => $survey,
        ]);
    }

    public function addAction(Request $request)
    {
        $data = $request->only('type', 'detail');

        $request->validate([
            'type' => 'required|min:2|max:8',
            'detail' => 'nullable|max:512',
        ]);

        $data['user_id'] = Auth::user()->id;

        $action = Action::create($data);

        return json_response($action);
    }

    public function getEvents(Request $request)
    {
        // 1 hour cache
        $events = Cache::remember('events', 3600, function () {
            $url = 'https://www.cm-almeida.pt/wp-json/tribe/events/v1/events';

            $result = $this->curl_request($url);
            $events = (object) json_decode($result);

            $result = [];
            foreach ($events->events as $event) {
                array_push($result, [
                    'id' => $event->id,
                    'url' => $event->url,
                    'title' => $event->title,
                    'description' => $event->description,
                    'category' => sizeof($event->categories) > 0 ? $event->categories[0]->name : '',
                    'image' => [
                        'url' => $event->image->url,
                        'thumb' => $event->image->sizes->thumbnail->url,
                    ],
                    'date' => [
                        'start' => $event->start_date,
                        'end' => $event->end_date,
                    ],
                    'venue' => [
                        'venue' => $event->venue ? $event->venue->venue : '',
                        'address' => $event->venue ? $event->venue->address : '',
                        'city' => $event->venue ? $event->venue->city : '',
                    ],
                ]);
            }

            return $result;
        });

        return json_response($events);
    }
}
