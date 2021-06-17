@if ($crud->hasAccess('update'))
	<div ajax-request="/<?=$crud->route;?>/activateAll" ajax-confirm="{{ __("Do you really want to activate all?") }}" ajax-refresh-on-success="true" class="btn btn-default ladda-button">
		<span class="ladda-label"><i class="nav-icon la la-check-square-o"></i> {{ __("Activate all") }}</span>
	</div>
@endif