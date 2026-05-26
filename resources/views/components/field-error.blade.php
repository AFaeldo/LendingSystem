@props(['field'])

@if($errors->has($field))
    <p class="mt-1 text-xs text-red-600 font-medium">
        <i class="ti ti-exclamation-circle inline mr-1"></i>
        {{ $errors->first($field) }}
    </p>
@endif
