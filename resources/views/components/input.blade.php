@props([
'name',
'id' => null,
'label' => null,
'type' => 'text',
'value' => null,
'placeholder' => '',
'readonly' => false,
'noWrapper' => false,
])

@if(!$noWrapper)
<div class="flex flex-col">
    @endif

    @if($label)
    <label for="{{ $id ?? $name }}" class="block text-gray-500 mb-2 font-medium">
        {{ $label }}
    </label>
    @endif

    <input
        id="{{ $id ?? $name }}"
        name="{{ $name }}"
        type="{{ $type }}"
        value="{{ old($name, $value) }}"
        placeholder="{{ $placeholder }}"
        {{ $attributes->has('min') ? 'min=' . $attributes->get('min') : '' }}
        {{ $readonly ? 'readonly' : '' }}
        {{ $attributes->merge(['class' => 'rounded-lg border px-3 py-2 focus:outline-none focus:ring-2 transition']) }}

        @class([ 'border-gray-300 focus:ring-teal-500'=> !$errors->has($name),
    'border-red-500 focus:ring-red-500' => $errors->has($name),
    'mt-1 block w-full' => !$noWrapper && !str_contains($attributes->get('class'), 'w-'),
    ])
    >

    @error($name)
    <span class="text-red-500 text-sm mt-1">
        {{ $message }}
    </span>
    @enderror

    @if(!$noWrapper)
</div>
@endif