<!-- select -->
@php
	$current_value = old(square_brackets_to_dots($field['name'])) ?? $field['value'] ?? $field['default'] ?? '';

    //if it's part of a relationship here we have the full related model, we want the key.
    if (is_object($current_value) && is_subclass_of(get_class($current_value), 'Illuminate\Database\Eloquent\Model') ) {
        $current_value = $current_value->getKey();
    }

    if (!isset($field['options'])) {
        $options = $field['model']::all();
    } else {
        $options = call_user_func($field['options'], $field['model']::query());
    }
@endphp

@include('crud::fields.inc.wrapper_start')

    <label>{!! $field['label'] !!}</label>

    <div class="mb-2 float-right">
        <!-- Single button -->
        <div class="btn-group">
            <button type="button" class="btn btn-sm btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            Language: English <span class="caret"></span>
            </button>
            <ul class="dropdown-menu" style="">
                @if (count($options))
                    @foreach ($options as $option)
                        <a class="dropdown-item" value="{{ $option->getKey() }}" href="javascript:void(0);">{{ $option->name }}</a>
                    @endforeach
                @endif
            </ul>
        </div>
    </div>

    @if (count($options))
        @foreach ($options as $option)
            <input
                type="text"
                name="{{ $field['name'] }}"
                value="{{ old(square_brackets_to_dots($field['name'])) ?? $field['value'] ?? $field['default'] ?? '' }}"
                @include('crud::fields.inc.attributes')
            >
        @endforeach
    @endif

    {{-- HINT --}}
    @if (isset($field['hint']))
        <p class="help-block">{!! $field['hint'] !!}</p>
    @endif

@include('crud::fields.inc.wrapper_end')
