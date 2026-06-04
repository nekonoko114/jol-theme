<section class="contact">
    <!-- 背後の巨大なデコレーション文字 -->
    <div class="contact-bg-text" aria-hidden="true">CONTACT</div>
    <!-- 背景グロー効果（ぼんやりした光） -->
    <div class="contact-glow contact-glow-purple"></div>
    <div class="contact-glow contact-glow-red"></div>

    <div class="contact-inner inner">
        <h2 class="contact-title">CONTACT</h2>
        <div class="contact-cards">
            <!-- 代理店募集カード (BtoB) -->
            <a href="<?php echo esc_url(home_url('/contact-partner')); ?>" class="contact-card contact-partner">
                <!-- ホバー時の閃光エフェクト用ライン -->
                <span class="contact-card-glint"></span>
                <div class="contact-card-content">
                    <div class="contact-card-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2">
                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
                            <circle cx="9" cy="7" r="4" />
                            <path d="M23 21v-2a4 4 0 0 0-3-3.87" />
                            <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                        </svg>
                    </div>
                    <h3 class="contact-card-title">代理店募集</h3>
                    <p class="contact-card-text">ビジネスパートナーとして<br>一緒に成長しませんか？</p>
                    <span class="contact-card-arrow">
                        <span class="arrow-text">→</span>
                    </span>
                </div>
            </a>
            
            <!-- Contact/ライバー応募カード (BtoC) -->
            <a href="<?php echo esc_url(home_url('/contact-creatore')); ?>" class="contact-card contact-contact">
                <!-- ホバー時の閃光エフェクト用ライン -->
                <span class="contact-card-glint"></span>
                <div class="contact-card-content">
                    <div class="contact-card-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2">
                            <path d="M12 2a3 3 0 0 0-3 3v7a3 3 0 0 0 6 0V5a3 3 0 0 0-3-3Z" />
                            <path d="M19 10v2a7 7 0 0 1-14 0v-2" />
                            <line x1="12" y1="19" x2="12" y2="22" />
                            <line x1="8" y1="22" x2="16" y2="22" />
                        </svg>
                    </div>
                    <h3 class="contact-card-title">Contact</h3>
                    <p class="contact-card-text">ライバーになりたい方や<br>お問い合わせはこちら</p>
                    <span class="contact-card-arrow">
                        <span class="arrow-text">→</span>
                    </span>
                </div>
            </a>
        </div>
    </div>
</section>