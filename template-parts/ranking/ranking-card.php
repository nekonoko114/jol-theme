<?php

/**
 * ランキングカード
 * Template Part: ranking-card.php
 */

// パラメータを取得（既存ACFフィールド対応）
$rank = $args['rank'] ?? 0;
$liver_name = $args['liver_name'] ?? '';
$creator_account = $args['creator_account'] ?? '';
$gift_count = $args['gift_count'] ?? 0;
$feature = $args['feature'] ?? '';
$delivery = $args['delivery'] ?? '';
$delivery_time = $args['delivery_time'] ?? '';
$account_url = $args['account_url'] ?? '';
$avatar_url = $args['avatar_url'] ?? '';
$liver_url = $args['liver_url'] ?? '#';

// ランク別のバッジクラスと王冠の色を設定
$rank_class = '';
$crown_color = '';
if ($rank <= 3) {
    $rank_class = 'rank-top-' . $rank;
    switch ($rank) {
        case 1:
            $crown_color = 'gold';
            break;
        case 2:
            $crown_color = 'silver';
            break;
        case 3:
            $crown_color = '#cd7f32'; // 銅色
            break;
    }
} elseif ($rank <= 10) {
    $rank_class = 'rank-top-ten';
}
?>

<div class="ranking-card <?php echo $rank_class; ?>" data-rank="<?php echo $rank; ?>">
    <div class="ranking-card-inner">
        <!-- ランクバッジ -->
        <div class="rank-badge" style="border-color: <?php echo $crown_color; ?>;">
            <?php if ($rank <= 3): ?>
                <?php
                $img_map = [
                    1 => get_template_directory_uri() . '/assets/images/ranking/clown-gold.webp',
                    2 => get_template_directory_uri() . '/assets/images/ranking/clown-silver.webp',
                    3 => get_template_directory_uri() . '/assets/images/ranking/clown-brons.webp',
                ];
                $rank_img = $img_map[$rank] ?? '';
                ?>
                <?php if ($rank_img): ?>
                    <img src="<?php echo esc_url($rank_img); ?>" alt="rank-<?php echo $rank; ?>" class="rank-icon">
                <?php else: ?>
                    <span class="rank-number"><?php echo $rank; ?></span>
                <?php endif; ?>
            <?php else: ?>
                <span class="rank-number"><?php echo $rank; ?></span>
            <?php endif; ?>
        </div>

        <!-- ライバー情報 -->
        <div class="liver-info">
            <div class="liver-avatar">
                <img src="<?php echo esc_url($avatar_url); ?>" alt="<?php echo esc_attr($liver_name); ?>">
                <?php if ($rank <= 3): ?>
                    <div class="crown-overlay" style="background-color: <?php echo $crown_color; ?>;"></div>
                <?php endif; ?>
            </div>

            <div class="liver-details">
                <h3 class="liver-name">
                    <a href="<?php echo esc_url($liver_url); ?>">
                        <?php echo esc_html($liver_name); ?>
                    </a>
                </h3>

                <?php if ($creator_account): ?>
                    <div class="creator-account">@<?php echo esc_html($creator_account); ?></div>
                <?php endif; ?>

                <div class="gift-info">
                    <div class="gift-count">
                        <i class="fas fa-gift"></i>
                        <span class="count-number"><?php echo number_format($gift_count); ?></span>
                        <span class="count-label">ギフト</span>
                    </div>
                </div>

                <?php if ($feature): ?>
                    <div class="feature-info">
                        <i class="fas fa-star"></i>
                        <span class="feature-text"><?php echo esc_html($feature); ?></span>
                    </div>
                <?php endif; ?>

                <?php if ($delivery || $delivery_time): ?>
                    <div class="delivery-info">
                        <i class="fas fa-clock"></i>
                        <?php if ($delivery): ?>
                            <span class="delivery-text"><?php echo esc_html($delivery); ?></span>
                        <?php endif; ?>
                        <?php if ($delivery_time): ?>
                            <span class="delivery-time">(<?php echo esc_html($delivery_time); ?>)</span>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- アクションボタン -->
        <div class="card-actions">
            <a href="<?php echo esc_url($liver_url); ?>" class="btn-view-profile">
                <i class="fas fa-user"></i>
                プロフィール
            </a>

            <?php if ($account_url): ?>
                <a href="<?php echo esc_url($account_url); ?>" class="btn-visit-account" target="_blank">
                    <i class="fas fa-external-link-alt"></i>
                    アカウント
                </a>
            <?php endif; ?>

            <!-- 応援ボタンは廃止 -->
        </div>

        <!-- ランクアップ/ダウンインジケーター -->
        <?php if (isset($args['rank_change'])): ?>
            <div class="rank-change <?php echo $args['rank_change'] > 0 ? 'rank-up' : 'rank-down'; ?>">
                <i class="fas fa-arrow-<?php echo $args['rank_change'] > 0 ? 'up' : 'down'; ?>"></i>
                <span><?php echo abs($args['rank_change']); ?></span>
            </div>
        <?php endif; ?>
    </div>

    <!-- ホバー時のエフェクト -->
    <div class="card-glow"></div>
</div>