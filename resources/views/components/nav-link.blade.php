@props(['active'])

@php
$classes = ($active ?? false)
            ? 'inline-flex items-center px-3 py-2 border-b-2 border-[#E50914] text-sm font-semibold leading-5 text-white focus:outline-none transition duration-200'
            : 'inline-flex items-center px-3 py-2 border-b-2 border-transparent text-sm font-medium leading-5 text-white/50 hover:text-white hover:border-white/20 focus:outline-none transition duration-200';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
