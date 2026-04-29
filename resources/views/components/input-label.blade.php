@props(['value'])

<label {{ $attributes->merge(['class' => 'block font-semibold text-sm text-white/80']) }}>
    {{ $value ?? $slot }}
</label>
