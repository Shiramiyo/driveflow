<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center rounded-full bg-rose-500 px-6 py-3 text-sm font-bold text-white transition duration-200 hover:bg-rose-400']) }}>
    {{ $slot }}
</button>
