<?php

if (!function_exists('api')) {
    function api()
    {
        return app('App\Http\Controllers\Admin\APICrudController');
    }
}

if (!function_exists('collect_only')) {
    function collect_only($model, $attributes)
    {
        return collect($model->toArray())->only($attributes)->all();
    }
}

if (!function_exists('data_get_first')) {
    function data_get_first($target, $key, $attribute, $default = 0)
    {
        $value = data_get($target, $key);
        return count($value) ? $value[0]->{$attribute} : $default;
    }
}

if (!function_exists('hasRole')) {
    function hasRole($role)
    {
        return backpack_user() && backpack_user()->hasRole($role);
    }
}

if (!function_exists('hasPermission')) {
    function hasPermission($permissions)
    {
        if (backpack_user()) {
            if (!is_array($permissions)) {
                $permissions = [$permissions];
            }

            foreach ($permissions as $permission) {
                if (backpack_user()->checkPermissionTo($permission, backpack_guard_name())) {
                    return true;
                }
            }
        }
        return false;
    }
}

if (!function_exists('admin')) {
    function admin()
    {
        return backpack_user() && backpack_user()->hasRole('admin');
    }
}

if (!function_exists('restrictTo')) {
    function restrictTo($roles, $permissions = null)
    {
        $session_role = Session::get('role', null);
        $session_permissions = Session::get('permissions', null);

        // Default
        if (!$session_role && !$session_permissions) {
            return ($roles && hasRole($roles)) || ($permissions && hasPermission($permissions));
        }

        // View as
        if (is_string($roles)) {
            $roles = [$roles];
        }

        if (is_string($permissions)) {
            $permissions = [$permissions];
        }

        // View as Role only
        if ($session_role && (!$session_permissions || !$permissions)) {
            return in_array($session_role, $roles);
        }

        // View as Role and Permissions
        else if ($session_role && $session_permissions && $permissions) {
            return in_array($session_role, $roles) || sizeof(array_intersect($session_permissions, array_values($permissions)));
        }
    }
}

if (!function_exists('is')) {
    function is($roles, $permissions = null)
    {
        return restrictTo($roles, $permissions);
    }
}

if (!function_exists('aurl')) {
    function aurl($disk, $path)
    {
        return starts_with($path, 'http') ? $path : Storage::disk($disk)->url($path);
    }
}

if (!function_exists('map_contains')) {
    function map_contains($needle_map, $haystack, $default = -1)
    {
        foreach ($needle_map as $needle => $value) {
            if (strpos($haystack, $needle) !== false) {
                return $value;
            }
        }
        return $default;
    }
}

if (!function_exists('map_transform')) {
    function map_transform($value, $map)
    {
        if (in_array($value, $map)) {
            $value = $map[$value];
        }

        return $value;
    }
}

if (!function_exists('json_response')) {
    function json_response($data = null, $code = 0, $status = 200, $errors = null, $exception = null)
    {
        $response = [
            'code' => $code,
            'data' => $data,
            'errors' => $errors,
        ];

        if (Config::get('app.debug', false)) {
            $start_time = \App\Http\Controllers\API\APIController::$start_time;

            $response = array_merge($response, ['debug' => [
                'time' => $start_time ? (microtime(true) - $start_time) * 1000 : 0,
                'exception' => $exception,
                'queries' => DB::getQueryLog(),
                'post' => request()->request->all(),
            ]]);
        }

        $response = json_encode($response);
        return response($response, $status)
            ->header('Content-Type', 'text/json')
            ->header('Content-Length', strlen($response));
    }
}

if (!function_exists('json_error')) {
    function json_error($errors = null, $code = -1, $status = 400)
    {
        return json_response(null, $code, $status, $errors);
    }
}

if (!function_exists('json_success_or_fail')) {
    function json_success_or_fail($success)
    {
        return $success ? json_response() : json_error();
    }
}

if (!function_exists('sized_image')) {
    function sized_image($path, $size)
    {
        if (($pos = strrpos($path, '/')) !== false) {
            $path = substr_replace($path, "/$size/", $pos, 1);
        }
        return $path;
    }
}
