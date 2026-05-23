@props(['type' => 'button'])
<button {{ $attributes->merge(['class' => 'btn']) }} type="{{ $type }}">
    {{ $slot }}
</button>
