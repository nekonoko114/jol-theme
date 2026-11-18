<section class="contact relative overflow-hidden border-t border-white/10 py-24 md:py-32">
    <!-- 背後の巨大なデコレーション文字 -->
    <div class="pointer-events-none select-none absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 text-[16vw] font-black text-white/[0.015] tracking-[0.15em] z-0" aria-hidden="true">CONTACT</div>
    <!-- 背景グロー効果（ぼんやりした光） -->
    <div class="pointer-events-none absolute z-0 w-[250px] h-[250px] md:w-[400px] md:h-[400px] rounded-full blur-[80px] md:blur-[120px] opacity-12 bg-jol-purple top-[10%] left-[10%]"></div>
    <div class="pointer-events-none absolute z-0 w-[250px] h-[250px] md:w-[400px] md:h-[400px] rounded-full blur-[80px] md:blur-[120px] opacity-12 bg-jol-red bottom-[10%] right-[10%]"></div>

    <div class="inner relative z-10">
        <div class="text-center mb-12 md:mb-20">
            <p class="text-sm md:text-base font-medium text-white/60 tracking-[0.25em] uppercase mb-3">お問い合わせ</p>
            <h2 class="text-4xl md:text-5xl font-extrabold tracking-[0.04em] bg-gradient-to-r from-jol-cyan to-jol-red bg-clip-text text-transparent">CONTACT</h2>
        </div>

        <div class="flex flex-col md:flex-row gap-8 md:gap-16 max-w-[1000px] mx-auto justify-center items-center md:items-start px-4 md:px-0">
            <!-- 代理店募集カード (BtoB) -->
            <a href="<?php echo esc_url(home_url('/contact-partner')); ?>" class="group relative block flex-1 w-full max-w-[420px] bg-white/[0.03] border border-white/[0.08] backdrop-blur-md rounded-lg p-8 md:p-10 text-center no-underline text-white overflow-hidden transition-all duration-300 ease-editorial hover:-translate-y-2 hover:bg-white/[0.05] hover:border-white/20 shadow-[0_15px_35px_rgba(0,0,0,0.4),0_0_20px_rgba(138,43,226,0.15)] hover:shadow-[0_25px_50px_rgba(0,0,0,0.5),0_0_30px_rgba(138,43,226,0.35)]">
                <!-- ホバー時の閃光エフェクト用ライン -->
                <span class="pointer-events-none absolute top-0 -left-[150%] w-[60%] h-full bg-gradient-to-r from-transparent via-white/12 to-transparent -skew-x-[25deg] z-[1] transition-all duration-700 group-hover:left-[200%]"></span>
                <div class="relative z-[2]">
                    <div class="w-16 h-16 md:w-20 md:h-20 mx-auto mb-6 flex items-center justify-center rounded-lg bg-white/[0.04] border border-white/10 text-white/80 transition-all duration-300 ease-editorial group-hover:bg-jol-purple group-hover:border-jol-purple group-hover:text-white">
                        <svg class="w-8 h-8 md:w-9 md:h-9" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2">
                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
                            <circle cx="9" cy="7" r="4" />
                            <path d="M23 21v-2a4 4 0 0 0-3-3.87" />
                            <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                        </svg>
                    </div>
                    <h3 class="text-2xl md:text-[1.75rem] font-bold text-white mb-4 tracking-wide transition-colors duration-300 group-hover:text-purple-200">代理店募集</h3>
                    <p class="text-sm md:text-base text-white/65 leading-6 mb-8 font-light">ビジネスパートナーとして<br>一緒に成長しませんか？</p>
                    <span class="inline-flex items-center justify-center w-12 h-12 bg-white/[0.04] border border-white/10 rounded text-white text-xl transition-all duration-300 ease-editorial group-hover:bg-white group-hover:text-black group-hover:border-white">
                        <span class="transition-transform duration-300 ease-editorial group-hover:translate-x-1">→</span>
                    </span>
                </div>
            </a>

            <!-- Contact/ライバー応募カード (BtoC) -->
            <a href="<?php echo esc_url(home_url('/contact-creatore')); ?>" class="group relative block flex-1 w-full max-w-[420px] md:mt-16 bg-white/[0.03] border border-white/[0.08] backdrop-blur-md rounded-lg p-8 md:p-10 text-center no-underline text-white overflow-hidden transition-all duration-300 ease-editorial hover:-translate-y-2 hover:bg-white/[0.05] hover:border-white/20 shadow-[0_15px_35px_rgba(0,0,0,0.4),0_0_20px_rgba(255,0,0,0.12)] hover:shadow-[0_25px_50px_rgba(0,0,0,0.5),0_0_30px_rgba(255,0,0,0.3)]">
                <!-- ホバー時の閃光エフェクト用ライン -->
                <span class="pointer-events-none absolute top-0 -left-[150%] w-[60%] h-full bg-gradient-to-r from-transparent via-white/12 to-transparent -skew-x-[25deg] z-[1] transition-all duration-700 group-hover:left-[200%]"></span>
                <div class="relative z-[2]">
                    <div class="w-16 h-16 md:w-20 md:h-20 mx-auto mb-6 flex items-center justify-center rounded-lg bg-white/[0.04] border border-white/10 text-white/80 transition-all duration-300 ease-editorial group-hover:bg-jol-red group-hover:border-jol-red group-hover:text-white">
                        <svg class="w-8 h-8 md:w-9 md:h-9" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2">
                            <path d="M12 2a3 3 0 0 0-3 3v7a3 3 0 0 0 6 0V5a3 3 0 0 0-3-3Z" />
                            <path d="M19 10v2a7 7 0 0 1-14 0v-2" />
                            <line x1="12" y1="19" x2="12" y2="22" />
                            <line x1="8" y1="22" x2="16" y2="22" />
                        </svg>
                    </div>
                    <h3 class="text-2xl md:text-[1.75rem] font-bold text-white mb-4 tracking-wide transition-colors duration-300 group-hover:text-red-200">Contact</h3>
                    <p class="text-sm md:text-base text-white/65 leading-6 mb-8 font-light">ライバーになりたい方や<br>お問い合わせはこちら</p>
                    <span class="inline-flex items-center justify-center w-12 h-12 bg-white/[0.04] border border-white/10 rounded text-white text-xl transition-all duration-300 ease-editorial group-hover:bg-white group-hover:text-black group-hover:border-white">
                        <span class="transition-transform duration-300 ease-editorial group-hover:translate-x-1">→</span>
                    </span>
                </div>
            </a>
        </div>
    </div>
</section>
