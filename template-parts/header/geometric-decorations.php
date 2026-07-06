<!-- 背景幾何学模様SVGデコレーション -->
<div class="bg-geometric-decorations" aria-hidden="true">
    <!-- SVG 1: ドットマトリクス（グリッド：固定） -->
    <div class="geometric-shape geo-grid"></div>
    
    <!-- SVG 2: テックサークル（右上：固定） -->
    <svg class="geometric-shape geo-ring" viewBox="0 0 100 100" fill="none" stroke="currentColor">
        <circle cx="50" cy="50" r="45" stroke-width="0.3" stroke-dasharray="2 2" />
        <circle cx="50" cy="50" r="35" stroke-width="0.8" />
        <circle cx="50" cy="50" r="20" stroke-width="0.3" stroke-dasharray="1 1" />
        <line x1="50" y1="5" x2="50" y2="95" stroke-width="0.2" stroke-dasharray="4 4" />
        <line x1="5" y1="50" x2="95" y2="50" stroke-width="0.2" stroke-dasharray="4 4" />
    </svg>
    <svg class="geometric-shape geo-ring-sub" viewBox="0 0 100 100" fill="none" stroke="currentColor">
        <circle cx="50" cy="50" r="40" stroke-width="0.4" />
        <circle cx="50" cy="50" r="30" stroke-width="0.2" stroke-dasharray="2 1" />
    </svg>
    
    <!-- SVG 3: テックヘキサゴン（左上：スクロール） -->
    <svg class="geometric-shape geo-hexagon" viewBox="0 0 100 100" fill="none" stroke="currentColor">
        <polygon points="50,5 93.3,30 93.3,80 50,95 6.7,80 6.7,30" stroke-width="0.5" />
        <polygon points="50,15 84.6,35 84.6,75 50,85 15.4,75 15.4,35" stroke-width="0.3" stroke-dasharray="4 2" />
        <line x1="50" y1="5" x2="50" y2="95" stroke-width="0.2" opacity="0.5" />
        <line x1="6.7" y1="30" x2="93.3" y2="80" stroke-width="0.2" opacity="0.5" />
        <line x1="6.7" y1="80" x2="93.3" y2="30" stroke-width="0.2" opacity="0.5" />
    </svg>
    
    <!-- SVG 4: 二重ポリゴン（左下：スクロール） -->
    <svg class="geometric-shape geo-polygon" viewBox="0 0 100 100" fill="none" stroke="currentColor">
        <polygon points="50,15 90,85 10,85" stroke-width="0.4" />
        <polygon points="50,28 80,80 20,80" stroke-width="0.8" stroke-dasharray="3 2" />
    </svg>

    <!-- SVG 5: 立体軌道円（左中央：スクロール） -->
    <svg class="geometric-shape geo-orbit" viewBox="0 0 120 120" fill="none" stroke="currentColor">
        <ellipse cx="60" cy="60" rx="50" ry="18" stroke-width="0.5" transform="rotate(-30 60 60)" />
        <ellipse cx="60" cy="60" rx="40" ry="12" stroke-width="0.8" stroke-dasharray="4 2" transform="rotate(-30 60 60)" />
        <ellipse cx="60" cy="60" rx="30" ry="8" stroke-width="0.3" transform="rotate(45 60 60)" />
        <circle cx="60" cy="60" r="10" stroke-width="0.5" />
    </svg>
    
    <!-- SVG 6: テックウェーブ（右中央：スクロール） -->
    <svg class="geometric-shape geo-wave" viewBox="0 0 100 40" fill="none" stroke="currentColor">
        <path d="M0,20 Q25,5 50,20 T100,20" stroke-width="0.6" />
        <path d="M0,25 Q25,10 50,25 T100,25" stroke-width="0.3" opacity="0.5" />
    </svg>
    <svg class="geometric-shape geo-wave-sub" viewBox="0 0 100 40" fill="none" stroke="currentColor">
        <path d="M0,15 Q25,30 50,15 T100,15" stroke-width="0.4" stroke-dasharray="2 2" />
    </svg>
    
    <!-- SVG 7: テックバーコード（画面サイド：固定/スクロール） -->
    <svg class="geometric-shape geo-barcode-left" viewBox="0 0 20 150" fill="none" stroke="currentColor">
        <line x1="5" y1="0" x2="5" y2="150" stroke-width="0.5" />
        <line x1="10" y1="10" x2="10" y2="140" stroke-width="1.5" stroke-dasharray="10 5 2 5 20 10" />
        <line x1="15" y1="20" x2="15" y2="130" stroke-width="0.5" stroke-dasharray="1 3" />
    </svg>
    <svg class="geometric-shape geo-barcode-right" viewBox="0 0 20 150" fill="none" stroke="currentColor">
        <line x1="15" y1="0" x2="15" y2="150" stroke-width="0.5" />
        <line x1="10" y1="10" x2="10" y2="140" stroke-width="1.5" stroke-dasharray="20 10 5 2 10 5" />
        <line x1="5" y1="20" x2="5" y2="130" stroke-width="0.5" stroke-dasharray="1 3" />
    </svg>
    
    <!-- SVG 8: 3Dワイヤー立方体（右下：スクロール） -->
    <svg class="geometric-shape geo-cube" viewBox="0 0 100 100" fill="none" stroke="currentColor">
        <rect x="20" y="35" width="40" height="40" stroke-width="0.6" />
        <rect x="40" y="15" width="40" height="40" stroke-width="0.4" stroke-dasharray="2 2" />
        <line x1="20" y1="35" x2="40" y2="15" stroke-width="0.5" />
        <line x1="60" y1="35" x2="80" y2="15" stroke-width="0.5" />
        <line x1="20" y1="75" x2="40" y2="55" stroke-width="0.5" />
        <line x1="60" y1="75" x2="80" y2="55" stroke-width="0.5" />
    </svg>

    <!-- SVG 9: 追加の二重ポリゴン（下部：スクロール） -->
    <svg class="geometric-shape geo-polygon-sub" viewBox="0 0 100 100" fill="none" stroke="currentColor">
        <polygon points="50,15 90,85 10,85" stroke-width="0.3" stroke-dasharray="5 3" />
        <circle cx="50" cy="55" r="15" stroke-width="0.5" />
    </svg>
    
    <!-- SVG 10: 十字パーティクル（個別配置） -->
    <svg class="geometric-shape geo-cross geo-cross-1" viewBox="0 0 10 10" fill="none" stroke="currentColor">
        <line x1="5" y1="0" x2="5" y2="10" stroke-width="1.5" />
        <line x1="0" y1="5" x2="10" y2="5" stroke-width="1.5" />
    </svg>
    <svg class="geometric-shape geo-cross geo-cross-2" viewBox="0 0 10 10" fill="none" stroke="currentColor">
        <line x1="5" y1="0" x2="5" y2="10" stroke-width="1.5" />
        <line x1="0" y1="5" x2="10" y2="5" stroke-width="1.5" />
    </svg>
    <svg class="geometric-shape geo-cross geo-cross-3" viewBox="0 0 10 10" fill="none" stroke="currentColor">
        <line x1="5" y1="0" x2="5" y2="10" stroke-width="1.5" />
        <line x1="0" y1="5" x2="10" y2="5" stroke-width="1.5" />
    </svg>
    <svg class="geometric-shape geo-cross geo-cross-4" viewBox="0 0 10 10" fill="none" stroke="currentColor">
        <line x1="5" y1="0" x2="5" y2="10" stroke-width="1.5" />
        <line x1="0" y1="5" x2="10" y2="5" stroke-width="1.5" />
    </svg>
    <svg class="geometric-shape geo-cross geo-cross-5" viewBox="0 0 10 10" fill="none" stroke="currentColor">
        <line x1="5" y1="0" x2="5" y2="10" stroke-width="1.5" />
        <line x1="0" y1="5" x2="10" y2="5" stroke-width="1.5" />
    </svg>
    <svg class="geometric-shape geo-cross geo-cross-6" viewBox="0 0 10 10" fill="none" stroke="currentColor">
        <line x1="5" y1="0" x2="5" y2="10" stroke-width="1.5" />
        <line x1="0" y1="5" x2="10" y2="5" stroke-width="1.5" />
    </svg>
    <svg class="geometric-shape geo-cross geo-cross-7" viewBox="0 0 10 10" fill="none" stroke="currentColor">
        <line x1="5" y1="0" x2="5" y2="10" stroke-width="1.5" />
        <line x1="0" y1="5" x2="10" y2="5" stroke-width="1.5" />
    </svg>
    <svg class="geometric-shape geo-cross geo-cross-8" viewBox="0 0 10 10" fill="none" stroke="currentColor">
        <line x1="5" y1="0" x2="5" y2="10" stroke-width="1.5" />
        <line x1="0" y1="5" x2="10" y2="5" stroke-width="1.5" />
    </svg>
</div>
