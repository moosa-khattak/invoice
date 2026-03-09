@props([
'name',
'id' => null,
'label' => null,
'value' => null,
'placeholder' => '',
'rows' => 3,
])

<div class="flex flex-col">
    @if($label)
    <label for="{{ $id ?? $name }}" class="block text-gray-500 mb-2 font-medium">
        {{ $label }}
    </label>
    @endif

    <textarea
        id="{{ $id ?? $name }}"
        name="{{ $name }}"
        rows="{{ $rows }}"
        placeholder="{{ $placeholder }}"
        {{ $attributes->merge(['class' => 'rounded-lg border px-3 py-2 focus:outline-none focus:ring-2 transition']) }}
        @class([ 'border-gray-300 focus:ring-teal-500'=> !$errors->has($name),
            'border-red-500 focus:ring-red-500' => $errors->has($name),
        ])
    >{{ old($name, $value) }}</textarea>

    @error($name)
    <span class="text-red-500 text-sm mt-1">
        {{ $message }}
    </span>
    @enderror
</div>