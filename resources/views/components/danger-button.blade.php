<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-5 py-3 bg-[#E50914] border border-transparent rounded-full font-semibold text-sm text-white uppercase tracking-wider shadow-lg shadow-[#E50914]/30 hover:bg-[#ff1a25] active:bg-[#b8070f] focus:outline-none focus:ring-2 focus:ring-[#E50914] focus:ring-offset-2 focus:ring-offset-[#0a0a0a] transition-all duration-300']) }}>
    {{ $slot }}
</button>
