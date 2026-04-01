@props(['active'])

@php
$classes = ($active ?? false)
            ? 'mx-2 block w-auto rounded-xl border border-indigo-200/80 bg-white/70 py-2.5 pe-4 ps-4 text-start text-base font-semibold text-indigo-800 shadow-sm backdrop-blur-sm transition duration-150 ease-in-out'
            : 'mx-2 block w-auto rounded-xl border border-transparent py-2.5 pe-4 ps-4 text-start text-base font-medium text-gray-600 transition duration-150 ease-in-out hover:border-white/50 hover:bg-white/45 hover:text-gray-900';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
