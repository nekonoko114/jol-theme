<?php

/**
 * ランキングフィルター
 * Template Part: ranking-filters.php
 */
?>

<div class="ranking-filters">
    <div class="filters-container">
        <div class="filter-group">
            <label for="period-filter" class="filter-label">
                <i class="fas fa-calendar-alt"></i>
                期間
            </label>
            <select id="period-filter" class="filter-select">
                <option value="total">全期間</option>
                <option value="monthly">今月</option>
                <option value="weekly">今週</option>
                <option value="daily">今日</option>
            </select>
        </div>

        <div class="filter-group">
            <label for="category-filter" class="filter-label">
                <i class="fas fa-tags"></i>
                カテゴリー
            </label>
            <select id="category-filter" class="filter-select">
                <option value="">全カテゴリー</option>
                <?php
                $categories = get_terms([
                    'taxonomy' => 'liver_category',
                    'hide_empty' => false,
                ]);
                foreach ($categories as $category) {
                    echo '<option value="' . $category->slug . '">' . $category->name . '</option>';
                }
                ?>
            </select>
        </div>

        <div class="filter-group">
            <label for="sort-filter" class="filter-label">
                <i class="fas fa-sort"></i>
                並び順
            </label>
            <select id="sort-filter" class="filter-select">
                <option value="desc">ギフト数: 多い順</option>
                <option value="asc">ギフト数: 少ない順</option>
                <option value="name">名前順</option>
                <option value="new">新着順</option>
            </select>
        </div>

        <div class="filter-actions">
            <button id="apply-filters" class="btn-apply-filters">
                <i class="fas fa-search"></i>
                絞り込み
            </button>
            <button id="reset-filters" class="btn-reset-filters">
                <i class="fas fa-redo"></i>
                リセット
            </button>
        </div>
    </div>

    <!-- リアルタイム更新表示 -->
    <div class="live-update-indicator">
        <div class="live-dot"></div>
        <span class="live-text">リアルタイム更新中</span>
        <span class="last-update" id="last-update-time">
            最終更新: <?php echo current_time('H:i'); ?>
        </span>
    </div>
</div>