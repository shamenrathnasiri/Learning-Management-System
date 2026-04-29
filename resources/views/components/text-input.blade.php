@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border-white/10 bg-white/5 text-white placeholder:text-white/30 focus:border-[#E50914] focus:ring-[#E50914]/30 rounded-2xl shadow-sm transition-all duration-300']) }}>
