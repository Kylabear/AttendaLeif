<button {{ $attributes->merge(['type' => 'submit', 'class' => 'btn-glass-primary focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-400 focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-40']) }}>
    {{ $slot }}
</button>
