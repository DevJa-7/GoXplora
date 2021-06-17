{{-- @extends('crud::fields.select2_from_ajax_multiple') --}}

<div class="form-group col-sm-12">
    <a class="btn btn-default" href="{{ url('/admin/' . $field['model'] . '/reorder?route_id=' . $crud->entry->id) }}">{{ $field['label'] }}</a>
</div>
