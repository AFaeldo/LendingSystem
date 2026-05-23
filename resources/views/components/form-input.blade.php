@props(['label' => null, 'name' => null, 'type' => 'text'])
@if($label)
    <label class="form-label" for="{{ $name }}">{{ $label }}</label>
@endif
<input id="{{ $name }}" name="{{ $name }}" type="{{ $type }}" {{ $attributes->merge(['class' => 'form-input']) }} />
