<button {{ $attributes->merge(['type' => 'button', 'class' => 'btn-glass-secondary focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-300 focus-visible:ring-offset-2 disabled:opacity-25']) }}>
    {{ $slot }}
</button>
