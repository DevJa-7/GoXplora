<!-- This file is used to store sidebar items, starting with Backpack\Base 0.9.0 -->
<li class="nav-item"><a class="nav-link" href="{{ backpack_url('dashboard') }}"><i class="la la-home nav-icon"></i> {{ trans('backpack::base.dashboard') }}</a></li>
<li class="nav-title">Content</li>
<li class="nav-item nav-dropdown">
    <a class="nav-link nav-dropdown-toggle" href="#"><i class="nav-icon la la-newspaper-o"></i>{{ ucfirst(__("modules")) }}</a>
    <ul class="nav-dropdown-items">
		<li class="nav-item"><a class="nav-link" href="{{ backpack_url('module') }}"><i class="nav-icon la la-newspaper-o"></i> <span>{{ ucfirst(__("modules")) }}</span></a></li>
		<li class="nav-item"><a class="nav-link" href="{{ backpack_url('category') }}"><i class="nav-icon la la-list"></i> <span>{{ __("Categories") }}</span></a></li>
		<li class="nav-item"><a class="nav-link" href="{{ backpack_url('tag') }}"><i class="nav-icon la la-tag"></i> <span>{{ __("Tags") }}</span></a></li>
    </ul>
</li>

<li class='nav-item'><a class='nav-link' href="{{ url(config('backpack.base.route_prefix', 'admin').'/marker') }}"><i class="nav-icon la la-hashtag"></i> <span>{{ ucfirst(__('markers')) }}</span></a></li>
<li class='nav-item'><a class='nav-link' href="{{ url(config('backpack.base.route_prefix', 'admin') . '/route') }}"><i class='nav-icon la la-map'></i> <span>{{ ucfirst(__("routes")) }}</span></a></li>
<li class='nav-item'><a class='nav-link' href="{{ url(config('backpack.base.route_prefix', 'admin').'/coordinate') }}"><i class="nav-icon la la-map-marker"></i> <span>{{ ucfirst(__('coordinates')) }}</span></a></li>
<li class='nav-item'><a class='nav-link' href="{{ url(config('backpack.base.route_prefix', 'admin').'/beacon') }}"><i class="nav-icon la la-bluetooth"></i> <span>{{ __('Beacons') }}</span></a></li>

<li class="nav-item nav-dropdown">
    <a class="nav-link nav-dropdown-toggle" href="#"><i class="nav-icon la la-check-circle-o"></i>{{ ucfirst(__("agreements")) }}</a>
    <ul class="nav-dropdown-items">
		<li class="nav-item"><a class="nav-link" href="{{ backpack_url('agreement') }}"><i class="nav-icon la la-check-circle-o"></i> <span>{{ ucfirst(__("agreements")) }}</span></a></li>
		<li class="nav-item"><a class="nav-link" href="{{ backpack_url('agreement-toggle') }}"><i class="nav-icon la la-check-circle-o"></i> <span>{{ ucfirst(__("agreement toggle")) }}</span></a></li>
    </ul>
</li>

<li class="nav-item nav-dropdown">
    <a class="nav-link nav-dropdown-toggle" href="#"><i class="nav-icon la la-question"></i>{{ ucfirst(__("survey")) }}</a>
    <ul class="nav-dropdown-items">
		<li class="nav-item"><a class="nav-link" href="{{ backpack_url('survey') }}"><i class="nav-icon la la-question"></i> <span>{{ ucfirst(__("questions")) }}</span></a></li>
		<li class="nav-item"><a class="nav-link" href="{{ backpack_url('survey-answer') }}"><i class="nav-icon la la-question"></i> <span>{{ ucfirst(__("answers")) }}</span></a></li>
    </ul>
</li>

<li class="nav-item nav-dropdown">
    <a class="nav-link nav-dropdown-toggle" href="#"><i class="nav-icon la la-question"></i>{{ ucfirst(__("game")) }}</a>
    <ul class="nav-dropdown-items">
		<li class="nav-item"><a class="nav-link" href="{{ backpack_url('game/question') }}"><i class="nav-icon la la-question"></i> <span>{{ ucfirst(__("questions")) }}</span></a></li>
		<li class="nav-item"><a class="nav-link" href="{{ backpack_url('game/answer') }}"><i class="nav-icon la la-question"></i> <span>{{ ucfirst(__("answers")) }}</span></a></li>
		<li class="nav-item"><a class="nav-link" href="{{ backpack_url('game/ranking') }}"><i class="nav-icon la la-question"></i> <span>{{ ucfirst(__("rankings")) }}</span></a></li>
    </ul>
</li>
<li class='nav-item'><a class='nav-link' href='{{ backpack_url('setting') }}'><i class='nav-icon la la-cog'></i> <span>Settings</span></a></li>

<li class="nav-title">Admin</li>
<li class="nav-item"><a class="nav-link" href="{{ backpack_url('elfinder') }}"><i class="nav-icon la la-files-o"></i> <span>{{ trans('backpack::crud.file_manager') }}</span></a></li>
<li class='nav-item'><a class='nav-link' href='{{ backpack_url('backup') }}'><i class='nav-icon la la-hdd-o'></i> Backups</a></li>
<li class="nav-item nav-dropdown">
	<a class="nav-link nav-dropdown-toggle" href="#"><i class="nav-icon la la-globe"></i> Translations</a>
	<ul class="nav-dropdown-items">
		<li class="nav-item"><a class="nav-link" href="{{ backpack_url('language') }}"><i class="nav-icon la la-flag-checkered"></i> Languages</a></li>
		<li class="nav-item"><a class="nav-link" href="{{ backpack_url('language/texts') }}"><i class="nav-icon la la-language"></i> Site texts</a></li>
	</ul>
</li>
<li class='nav-item'><a class='nav-link' href='{{ backpack_url('log') }}'><i class='nav-icon la la-terminal'></i> Logs</a></li>
<li class='nav-item'><a class='nav-link' href='{{ backpack_url('page') }}'><i class='nav-icon la la-file-o'></i> <span>Pages</span></a></li>
<!-- Users, Roles, Permissions -->
<li class="nav-item nav-dropdown">
	<a class="nav-link nav-dropdown-toggle" href="#"><i class="nav-icon la la-group"></i>Users</a>
	<ul class="nav-dropdown-items">
		<li class="nav-item"><a class="nav-link" href="{{ backpack_url('user') }}"><i class="nav-icon fa fa-user"></i> <span>Users</span></a></li>
		<li class="nav-item"><a class="nav-link" href="{{ backpack_url('role') }}"><i class="nav-icon fa fa-group"></i> <span>Roles</span></a></li>
		<li class="nav-item"><a class="nav-link" href="{{ backpack_url('permission') }}"><i class="nav-icon fa fa-key"></i> <span>Permissions</span></a></li>
	</ul>
</li>