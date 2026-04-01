@props(['active'])

@php
$classes = ($active ?? false)
            ? 'inline-flex items-center rounded-lg border border-indigo-200/80 bg-white/70 px-3 py-1.5 text-sm font-semibold text-indigo-800 shadow-sm backdrop-blur-sm transition duration-150 ease-in-out ring-1 ring-indigo-100/80'
            : 'inline-flex items-center rounded-lg border border-transparent px-3 py-1.5 text-sm font-medium leading-5 text-gray-600 transition duration-150 ease-in-out hover:border-white/60 hover:bg-white/50 hover:text-gray-900 hover:shadow-sm hover:backdrop-blur-sm';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
