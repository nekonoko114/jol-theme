<?php

/**
 * ランキングページ用ヘッダー
 * Template Part: ranking-header.php
 */
?>

<div class="ranking-header">
    <div class="ranking-hero">
        <div class="ranking-hero-bg">
            <!-- パーティクル背景（既存のものを流用） -->
            <div id="ranking-particles"></div>
        </div>

        <div class="ranking-hero-content">
            <h1 class="ranking-main-title">
                <i class="fas fa-trophy"></i>
                ライバー ギフト ランキング
            </h1>
            <p class="ranking-subtitle">
                頑張っているライバーたちのギフト数ランキング！<br>
                あなたの推しライバーは何位かな？
            </p>

            <!-- ライブ感を演出するアニメーション -->
            <div class="ranking-stats">
                <div class="stat-item">
                    <span class="stat-number" data-count="<?php echo wp_count_posts('liver')->publish; ?>">0</span>
                    <span class="stat-label">参加ライバー</span>
                </div>
                <div class="stat-item">
                    <span class="stat-number" data-count="15000">0</span>
                    <span class="stat-label">総ギフト数</span>
                </div>
                <div class="stat-item">
                    <span class="stat-number" data-count="24">0</span>
                    <span class="stat-label">時間更新</span>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // カウントアップアニメーション
    document.addEventListener('DOMContentLoaded', function() {
        const counters = document.querySelectorAll('.stat-number[data-count]');

        counters.forEach(counter => {
            const target = parseInt(counter.getAttribute('data-count'));
            const duration = 2000; // 2秒でカウント
            const increment = target / (duration / 16); // 60fps
            let current = 0;

            const timer = setInterval(() => {
                current += increment;
                if (current >= target) {
                    counter.textContent = target.toLocaleString();
                    clearInterval(timer);
                } else {
                    counter.textContent = Math.floor(current).toLocaleString();
                }
            }, 16);
        });
    });
</script>