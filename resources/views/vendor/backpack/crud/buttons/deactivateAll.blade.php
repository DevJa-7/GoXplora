@if ($crud->hasAccess('update'))
	<div ajax-request="/<?=$crud->route;?>/deactivateAll" ajax-confirm="{{ __("Do you really want to deactivate all?") }}" ajax-refresh-on-success="true" class="btn btn-default ladda-button">
		<span class="ladda-label"><i class="nav-icon la la-square-o"></i> {{ __("Deactivate all") }}</span>
	</div>
@endif