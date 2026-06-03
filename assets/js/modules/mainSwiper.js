/**
 * メインSwiperモジュール
 * 基本的なスライダー機能を管理
 */
class MainSwiper {
  constructor() {
    this.swiperInstance = null;
    this.config = {
      // スライド表示設定（縦長横スクロール）
      slidesPerView: "auto",
      spaceBetween: 30,
      centeredSlides: false,

      // ページネーション
      pagination: {
        el: ".swiper-pagination",
        clickable: true,
        dynamicBullets: true,           
      },

      // ナビゲーション
      navigation: {
        nextEl: ".swiper-button-next",
        prevEl: ".swiper-button-prev",
      },

      // 自動再生（強化版）
      autoplay: {
        delay: 4000,
        disableOnInteraction: false,
        pauseOnMouseEnter: true,
      },

      // ループ
      loop: true,

      // エフェクト
      effect: "cards",

      // 速度
      speed: 800,

      // レスポンシブ設定
      breakpoints: {
        // モバイル
        480: {
          slidesPerView: 1,
          spaceBetween: 20,
          centeredSlides: true,
        },
        // タブレット
        768: {
          slidesPerView: 1,
          spaceBetween: 25,
          centeredSlides: true,
        },
        // デスクトップ
        1024: {
          slidesPerView: 1,
          spaceBetween: 30,
          centeredSlides: true,
        },
        // 大画面
        1400: {
          slidesPerView: 1,
          spaceBetween: 30,
          centeredSlides: true,
        },
      },
    };
  }

  /**
   * Swiperの初期化
   */
  init() {
    // Swiperの要素が存在するかチェック
    const swiperElement = document.querySelector(".swiper");
    if (!swiperElement) {
      console.log("メインSwiper要素が見つかりません");
      return false;
    }

    try {
      // スライド数をカウントしてループ設定を調整
      const swiperElement = document.querySelector(".swiper");
      let slideCount = 0;
      if (swiperElement) {
         slideCount = swiperElement.querySelectorAll('.swiper-slide').length;
      }
      
      // スライドが少ない場合（6枚未満）はループを無効化してエラーを防ぐ
      if (slideCount > 0 && slideCount < 6) {
        this.config.loop = false;
        this.config.centeredSlides = true;
      }

      // 基本的なSwiperの設定
      this.swiperInstance = new Swiper(".swiper", this.config);
      return true;
    } catch (error) {
      console.error("メインSwiperの初期化に失敗:", error);
      return false;
    }
  }

  /**
   * Swiperインスタンスを取得
   */
  getInstance() {
    return this.swiperInstance;
  }


  /**
   * 自動再生を開始
   */
  startAutoplay() {
    if (this.swiperInstance && this.swiperInstance.autoplay) {
      this.swiperInstance.autoplay.start();
    }
  }

  /**
   * 自動再生を停止
   */
  stopAutoplay() {
    if (this.swiperInstance && this.swiperInstance.autoplay) {
      this.swiperInstance.autoplay.stop();
    }
  }
}

// モジュールをグローバルに公開
window.MainSwiper = MainSwiper;
