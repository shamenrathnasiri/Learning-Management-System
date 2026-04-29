<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center px-5 py-3 bg-white/5 border border-white/15 rounded-full font-semibold text-sm text-white/80 uppercase tracking-wider shadow-sm hover:bg-white/10 hover:border-[#E50914]/40 hover:text-white focus:outline-none focus:ring-2 focus:ring-[#E50914]/30 focus:ring-offset-2 focus:ring-offset-[#0a0a0a] disabled:opacity-25 transition-all duration-300']) }}>
    {{ $slot }}
</button>
