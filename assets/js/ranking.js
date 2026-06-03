/**
 * ランキングページ機能管理クラス
 * ライバーギフト数ランキングのフィルター、Ajax更新、インタラクション機能
 */
class RankingManager {
  constructor() {
    this.config = {
      ajaxUrl: "/wp-admin/admin-ajax.php",
      refreshInterval: 30000, // 30秒間隔で自動更新
      animationDuration: 500,
      loadMoreLimit: 10,
    };

    this.state = {
      currentPeriod: "total",
      currentCategory: "",
      currentSort: "desc",
      currentPage: 1,
      isLoading: false,
      autoRefresh: true,
    };

    this.elements = {};
    this.refreshTimer = null;
  }

  /**
   * 初期化
   */
  init() {
    this.cacheElements();
    this.bindEvents();
    this.initializeRanking();
    this.startAutoRefresh();
    this.initializeParticles();

    console.log("ランキングマネージャー初期化完了");
  }

  /**
   * DOM要素をキャッシュ
   */
  cacheElements() {
    this.elements = {
      // フィルター要素
      periodFilter: document.getElementById("period-filter"),
      categoryFilter: document.getElementById("category-filter"),
      sortFilter: document.getElementById("sort-filter"),
      applyBtn: document.getElementById("apply-filters"),
      resetBtn: document.getElementById("reset-filters"),

      // ランキング表示要素
      topRanking: document.getElementById("top-ranking"),
      generalRanking: document.getElementById("general-ranking"),
      loadMoreBtn: document.getElementById("load-more-ranking"),

      // 更新表示要素
      lastUpdateTime: document.getElementById("last-update-time"),

      // その他
      rankingPage: document.querySelector(".ranking-page"),
    };
  }

  /**
   * イベントハンドラーを設定
   */
  bindEvents() {
    // フィルターボタン
    if (this.elements.applyBtn) {
      this.elements.applyBtn.addEventListener("click", () =>
        this.applyFilters()
      );
    }

    if (this.elements.resetBtn) {
      this.elements.resetBtn.addEventListener("click", () =>
        this.resetFilters()
      );
    }

    // もっと見るボタン
    if (this.elements.loadMoreBtn) {
      this.elements.loadMoreBtn.addEventListener("click", () =>
        this.loadMoreRanking()
      );
    }

    // フィルター変更時の即座反映
    [
      this.elements.periodFilter,
      this.elements.categoryFilter,
      this.elements.sortFilter,
    ].forEach((element) => {
      if (element) {
        element.addEventListener("change", () => this.applyFilters());
      }
    });

    // ライバーカードのインタラクション
    this.bindCardEvents();

    // ページ離脱時の処理
    window.addEventListener("beforeunload", () => this.cleanup());
  }

  /**
   * ランキングカードのイベントを設定
   */
  bindCardEvents() {
    // 応援ボタンは廃止されたためクリックハンドラを削除

    // カードホバー効果
    document.addEventListener("mouseover", (e) => {
      const card = e.target.closest(".ranking-card");
      if (card) {
        this.animateCard(card, "enter");
      }
    });

    document.addEventListener("mouseout", (e) => {
      const card = e.target.closest(".ranking-card");
      if (card && !card.contains(e.relatedTarget)) {
        this.animateCard(card, "leave");
      }
    });
  }

  /**
   * 初期ランキングデータを読み込み
   */
  async initializeRanking() {
    this.showLoading(true);

    try {
      await this.loadRankingData();
      this.animateRankingCards();
    } catch (error) {
      console.error("初期ランキング読み込みエラー:", error);
      this.showError("ランキングデータの読み込みに失敗しました");
    } finally {
      this.showLoading(false);
    }
  }

  /**
   * ランキングデータをAjaxで取得
   */
  async loadRankingData(isLoadMore = false) {
    const formData = new FormData();
    formData.append("action", "get_ranking_data");
    formData.append("period", this.state.currentPeriod);
    formData.append("category", this.state.currentCategory);
    formData.append("sort", this.state.currentSort);
    formData.append("limit", this.config.loadMoreLimit);
    formData.append(
      "offset",
      isLoadMore ? (this.state.currentPage - 1) * this.config.loadMoreLimit : 0
    );

    try {
      const response = await fetch(this.config.ajaxUrl, {
        method: "POST",
        body: formData,
      });

      const data = await response.json();

      if (data.success) {
        this.updateRankingDisplay(data.data, isLoadMore);
        this.updateLastUpdateTime();
      } else {
        throw new Error(data.data || "データ取得に失敗しました");
      }
    } catch (error) {
      console.error("Ajax通信エラー:", error);
      throw error;
    }
  }

  /**
   * ランキング表示を更新
   */
  updateRankingDisplay(rankingData, isLoadMore = false) {
    if (!rankingData || rankingData.length === 0) {
      if (!isLoadMore) {
        this.showNoData();
      }
      return;
    }

    // TOP3の更新
    if (!isLoadMore) {
      this.updateTopRanking(rankingData.slice(0, 3));
      this.updateGeneralRanking(rankingData.slice(3), false);
    } else {
      this.updateGeneralRanking(rankingData, true);
    }

    // もっと見るボタンの制御
    this.updateLoadMoreButton(rankingData.length >= this.config.loadMoreLimit);
  }

  /**
   * TOP3表彰台の更新
   */
  updateTopRanking(topThree) {
    if (!this.elements.topRanking) return;

    this.elements.topRanking.innerHTML = "";

    topThree.forEach((liver, index) => {
      const podiumRank = this.createPodiumRank(liver, index + 1);
      this.elements.topRanking.appendChild(podiumRank);
    });

    // 表彰台アニメーション
    this.animatePodium();
  }

  /**
   * 一般ランキングの更新
   */
  updateGeneralRanking(rankingData, append = false) {
    if (!this.elements.generalRanking) return;

    if (!append) {
      this.elements.generalRanking.innerHTML = "";
    }

    rankingData.forEach((liver) => {
      const rankingCard = this.createRankingCard(liver);
      this.elements.generalRanking.appendChild(rankingCard);
    });
  }

  /**
   * 表彰台要素を作成
   */
  createPodiumRank(liver, rank) {
    const podiumDiv = document.createElement("div");
    podiumDiv.className = `podium-rank rank-${rank}`;
    podiumDiv.dataset.rank = rank;

    const trendIcon = this.getTrendIcon(liver.trend);

    // TOP3 は画像で表示（assets にある clown-*.webp を利用）
    const podiumImgMap = {
      1: "/wp-content/themes/jol-themes/assets/images/ranking/clown-gold.webp",
      2: "/wp-content/themes/jol-themes/assets/images/ranking/clown-silver.webp",
      3: "/wp-content/themes/jol-themes/assets/images/ranking/clown-brons.webp",
    };

    const rankDisplay = podiumImgMap[rank]
      ? `<div class="rank-badge"><img src="${podiumImgMap[rank]}" alt="rank-${rank}" class="rank-icon"></div>`
      : `<div class="rank-badge">${rank}</div>`;

    podiumDiv.innerHTML = `
      ${rankDisplay}
      <div class="liver-avatar">
        <img src="${liver.avatar}" alt="${liver.name}">
      </div>
      <div class="liver-info">
        <h3 class="liver-name">${liver.name}</h3>
        <div class="gift-count">${liver.gift_count.toLocaleString()} ギフト</div>
        <div class="gift-trend">${trendIcon}</div>
      </div>
    `;

    // クリックイベント
    podiumDiv.addEventListener("click", () => {
      if (liver.url) {
        window.open(liver.url, "_blank");
      }
    });

    return podiumDiv;
  }

  /**
   * ランキングカード要素を作成
   */
  createRankingCard(liver) {
    const cardDiv = document.createElement("div");
    cardDiv.className = "ranking-card";
    cardDiv.dataset.rank = liver.rank;

    // ランク別クラスの追加
    if (liver.rank <= 3) {
      cardDiv.classList.add(`rank-top-${liver.rank}`);
    } else if (liver.rank <= 10) {
      cardDiv.classList.add("rank-top-ten");
    }

    const trendIcon = this.getTrendIcon(liver.trend);

    // assetsに配置された画像を使ってトップ3の表示を画像に置換
    const assetImgMap = {
      1: "/wp-content/themes/jol-themes/assets/images/ranking/clown-gold.webp",
      2: "/wp-content/themes/jol-themes/assets/images/ranking/clown-silver.webp",
      3: "/wp-content/themes/jol-themes/assets/images/ranking/clown-brons.webp",
    };

    const rankInner = assetImgMap[liver.rank]
      ? `<div class="rank-badge"><img src="${
          assetImgMap[liver.rank]
        }" alt="rank-${liver.rank}" class="rank-icon"></div>`
      : `<div class="rank-badge"><span class="rank-number">${liver.rank}</span></div>`;

    cardDiv.innerHTML = `
            <div class="ranking-card-inner">
                ${rankInner}
                
                <div class="liver-info">
                    <div class="liver-avatar">
                        <img src="${liver.avatar}" alt="${liver.name}">
                        ${
                          liver.rank <= 3
                            ? '<div class="crown-overlay"></div>'
                            : ""
                        }
                    </div>
                    
                    <div class="liver-details">
                        <h3 class="liver-name">
                            <a href="${liver.url}" target="_blank">${
      liver.name
    }</a>
                        </h3>
                        
                        <div class="gift-info">
                            <div class="gift-count">
                                <i class="fas fa-gift"></i>
                                <span class="count-number">${liver.gift_count.toLocaleString()}</span>
                                <span class="count-label">ギフト</span>
                            </div>
                            
                            <div class="gift-trend">
                                <span class="trend-icon">${trendIcon}</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="card-actions">
                    <a href="${
                      liver.url
                    }" class="btn-view-profile" target="_blank">
                        <i class="fas fa-user"></i>
                        プロフィール
                    </a>
          <!-- 応援ボタンは廃止 -->
                </div>
            </div>
            
            <div class="card-glow"></div>
        `;

    return cardDiv;
  }

  /**
   * トレンドアイコンを取得
   */
  getTrendIcon(trend) {
    const icons = {
      up: "📈",
      down: "📉",
      stable: "📊",
    };
    return icons[trend] || "📊";
  }

  /**
   * フィルターを適用
   */
  async applyFilters() {
    if (this.state.isLoading) return;

    // 状態を更新
    this.state.currentPeriod = this.elements.periodFilter?.value || "total";
    this.state.currentCategory = this.elements.categoryFilter?.value || "";
    this.state.currentSort = this.elements.sortFilter?.value || "desc";
    this.state.currentPage = 1;

    this.showLoading(true);

    try {
      await this.loadRankingData();
      this.animateRankingCards();
    } catch (error) {
      this.showError("フィルター適用に失敗しました");
    } finally {
      this.showLoading(false);
    }
  }

  /**
   * フィルターをリセット
   */
  resetFilters() {
    if (this.elements.periodFilter) this.elements.periodFilter.value = "total";
    if (this.elements.categoryFilter) this.elements.categoryFilter.value = "";
    if (this.elements.sortFilter) this.elements.sortFilter.value = "desc";

    this.applyFilters();
  }

  /**
   * もっと見るボタンの処理
   */
  async loadMoreRanking() {
    if (this.state.isLoading) return;

    this.state.currentPage++;
    this.showLoading(true, "もっと読み込み中...");

    try {
      await this.loadRankingData(true);
    } catch (error) {
      this.state.currentPage--; // エラー時は元に戻す
      this.showError("追加データの読み込みに失敗しました");
    } finally {
      this.showLoading(false);
    }
  }

  /**
   * ギフト送信の処理（モック）
   */
  sendGift(liverId) {
    // sendGift は廃止（かつてはモックでギフト送信を行っていました）
  }

  /**
   * カードアニメーション
   */
  animateCard(card, type) {
    if (type === "enter") {
      card.style.transform = "translateY(-5px) scale(1.02)";
      card.style.boxShadow = "0 15px 35px rgba(0, 0, 0, 0.2)";
    } else {
      card.style.transform = "";
      card.style.boxShadow = "";
    }
  }

  /**
   * 表彰台アニメーション
   */
  animatePodium() {
    const podiumRanks =
      this.elements.topRanking?.querySelectorAll(".podium-rank");
    if (!podiumRanks) return;

    podiumRanks.forEach((rank, index) => {
      setTimeout(() => {
        rank.style.opacity = "0";
        rank.style.transform = "translateY(50px) scale(0.8)";

        requestAnimationFrame(() => {
          rank.style.transition = "all 0.6s cubic-bezier(0.34, 1.56, 0.64, 1)";
          rank.style.opacity = "1";
          rank.style.transform = "";
        });
      }, index * 200);
    });
  }

  /**
   * ランキングカードアニメーション
   */
  animateRankingCards() {
    const cards =
      this.elements.generalRanking?.querySelectorAll(".ranking-card");
    if (!cards) return;

    cards.forEach((card, index) => {
      setTimeout(() => {
        card.style.opacity = "0";
        card.style.transform = "translateX(-30px)";

        requestAnimationFrame(() => {
          card.style.transition = "all 0.4s ease";
          card.style.opacity = "1";
          card.style.transform = "";
        });
      }, index * 100);
    });
  }

  /**
   * ギフト送信アニメーション
   */
  animateGiftSend(button) {
    // ギフトアニメーションは廃止
  }

  /**
   * パーティクル効果の初期化
   */
  initializeParticles() {
    const particlesContainer = document.getElementById("ranking-particles");
    if (particlesContainer && typeof particlesJS !== "undefined") {
      // 既存のパーティクル設定を流用
      particlesJS("ranking-particles", {
        particles: {
          number: { value: 50 },
          color: { value: ["#ffffff", "#ffd700"] },
          shape: { type: "star" },
          opacity: { value: 0.6, anim: { enable: true } },
          size: { value: 2, anim: { enable: true } },
          move: { enable: true, speed: 1 },
        },
      });
    }
  }

  /**
   * 自動更新を開始
   */
  startAutoRefresh() {
    if (!this.state.autoRefresh) return;

    this.refreshTimer = setInterval(() => {
      if (!this.state.isLoading && document.visibilityState === "visible") {
        this.loadRankingData();
      }
    }, this.config.refreshInterval);
  }

  /**
   * 自動更新を停止
   */
  stopAutoRefresh() {
    if (this.refreshTimer) {
      clearInterval(this.refreshTimer);
      this.refreshTimer = null;
    }
  }

  /**
   * ローディング表示制御
   */
  showLoading(show, message = "ランキングを更新中...") {
    this.state.isLoading = show;

    if (show) {
      // ローディング表示
      const loader = document.createElement("div");
      loader.id = "ranking-loader";
      loader.innerHTML = `
                <div class="loader-content">
                    <div class="loader-spinner"></div>
                    <p>${message}</p>
                </div>
            `;
      loader.style.cssText = `
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0, 0, 0, 0.5);
                display: flex;
                align-items: center;
                justify-content: center;
                z-index: 10000;
            `;

      document.body.appendChild(loader);
    } else {
      // ローディング非表示
      const loader = document.getElementById("ranking-loader");
      if (loader) {
        loader.remove();
      }
    }
  }

  /**
   * エラー表示
   */
  showError(message) {
    this.showNotification(message, "error");
  }

  /**
   * データなし表示
   */
  showNoData() {
    if (this.elements.generalRanking) {
      this.elements.generalRanking.innerHTML = `
                <div class="no-data-message">
                    <i class="fas fa-search"></i>
                    <h3>ランキングデータがありません</h3>
                    <p>条件を変更して再度お試しください</p>
                </div>
            `;
    }
  }

  /**
   * 通知表示
   */
  showNotification(message, type = "info") {
    const notification = document.createElement("div");
    notification.className = `ranking-notification ${type}`;
    notification.innerHTML = `
            <div class="notification-content">
                <i class="fas fa-${
                  type === "success"
                    ? "check-circle"
                    : type === "error"
                    ? "exclamation-circle"
                    : "info-circle"
                }"></i>
                <span>${message}</span>
            </div>
        `;

    notification.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            background: ${
              type === "success"
                ? "#4caf50"
                : type === "error"
                ? "#f44336"
                : "#2196f3"
            };
            color: white;
            padding: 15px 20px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
            z-index: 10001;
            animation: slideInRight 0.3s ease;
        `;

    document.body.appendChild(notification);

    setTimeout(() => {
      notification.style.animation = "slideOutRight 0.3s ease forwards";
      setTimeout(() => notification.remove(), 300);
    }, 3000);
  }

  /**
   * 最終更新時刻を更新
   */
  updateLastUpdateTime() {
    if (this.elements.lastUpdateTime) {
      const now = new Date();
      const timeString = now.toLocaleTimeString("ja-JP", {
        hour: "2-digit",
        minute: "2-digit",
      });
      this.elements.lastUpdateTime.textContent = `最終更新: ${timeString}`;
    }
  }

  /**
   * もっと見るボタンの制御
   */
  updateLoadMoreButton(hasMore) {
    if (!this.elements.loadMoreBtn) return;

    this.elements.loadMoreBtn.disabled = !hasMore;
    this.elements.loadMoreBtn.textContent = hasMore
      ? "もっと見る"
      : "すべて表示済み";
  }

  /**
   * クリーンアップ
   */
  cleanup() {
    this.stopAutoRefresh();
    console.log("ランキングマネージャー クリーンアップ完了");
  }
}

// CSS アニメーション追加
const style = document.createElement("style");
style.textContent = `
    @keyframes heartFloat {
        0% { transform: translateY(0) scale(1); opacity: 1; }
        100% { transform: translateY(-100px) scale(0.5); opacity: 0; }
    }
    
    @keyframes slideInRight {
        from { transform: translateX(100%); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }
    
    @keyframes slideOutRight {
        from { transform: translateX(0); opacity: 1; }
        to { transform: translateX(100%); opacity: 0; }
    }
    
    .loader-spinner {
        width: 40px;
        height: 40px;
        border: 4px solid rgba(255, 255, 255, 0.3);
        border-top: 4px solid #ffffff;
        border-radius: 50%;
        animation: spin 1s linear infinite;
        margin: 0 auto 15px;
    }
    
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
    
    .loader-content {
        text-align: center;
        color: white;
        font-weight: 600;
    }
`;
document.head.appendChild(style);

// グローバルに公開
window.RankingManager = RankingManager;
