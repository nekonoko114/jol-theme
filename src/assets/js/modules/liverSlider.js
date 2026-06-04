/**
 * LiverSliderモジュール
 * ライバー専用スライダー機能を管理
 */
class LiverSlider {
  constructor() {
    this.swiperInstance = null;
    this.element = null;
    this.config = {
      // 縦長カードの横スクロール設定 (Coverflowエフェクト)
      slidesPerView: "auto",
      centeredSlides: true,
      freeMode: false,

      // 独自のページネーション
      pagination: {
        el: ".liver-slider .liver-pagination",
        clickable: true,
        dynamicBullets: true,
        bulletClass: "liver-bullet",
        bulletActiveClass: "liver-bullet-active",
        renderBullet: function (index, className) {
          return (
            '<span class="' + className + ' liver-pagination-bullet"></span>'
          );
        },
      },


      // 自動再生（独自設定）
      autoplay: {
        delay: 4000,
        disableOnInteraction: false,
        pauseOnMouseEnter: true,
        reverseDirection: false,
      },

      // ループ設定
      loop: true,
      loopAdditionalSlides: 2, 

      // エフェクト: Coverflow (Movies Carousel Sliderスタイル)
      effect: "coverflow",
      coverflowEffect: {
        rotate: 0, // 回転なし
        stretch: 0, // 間隔
        depth: 150, // 奥に下がる深さ
        modifier: 1.5, // 奥行きの強調度
        slideShadows: true, // 左右スライドのシャドウ
      },

      // 速度設定
      speed: 800, // スライドのスピード

      // タッチ・マウス操作
      touchRatio: 1.2, 
      touchAngle: 45,
      grabCursor: true,

      // ブレイクポイントは使用せず、CSSの width でコントロールする
      breakpoints: {},

      // イベントリスナー
      on: {
        init: function () {
          // カスタム初期化処理
          this.slides.forEach((slide, index) => {
            slide.setAttribute("data-liver-index", index);
          });
        },
        slideChange: function () {},
        touchStart: function () {
          // タッチ開始時の処理
          this.autoplay.stop();
        },
        touchEnd: function () {
          // タッチ終了時の処理
          this.autoplay.start();
        },
      },
    };
  }

  /**
   * LiverSliderの初期化
   */
  init() {
    // LiverSliderの要素が存在するかチェック
    this.element = document.querySelector(".liver-slider");
    if (!this.element) {
      console.log("LiverSlider要素が見つかりません");
      return false;
    }

    try {
      // スライド数をカウントしてループ設定を調整
      // スライド数をカウントしてループ設定を調整
      const slideCount = this.element.querySelectorAll('.swiper-slide').length;

      // スライドが少ない場合（6枚未満）はループを無効化してエラーを防ぐ
      if (slideCount > 0 && slideCount < 6) {
        this.config.loop = false;
        // スライドが少ない場合は中央寄せにする
        this.config.centeredSlides = true;
      }

      // LiverSliderの初期化
      this.swiperInstance = new Swiper(
        ".liver-slider .liver-swiper",
        this.config
      );

      // カスタム制御の設定
      this.setupCustomControls();

      return true;
    } catch (error) {
      console.error("LiverSliderの初期化に失敗:", error);
      return false;
    }
  }

  /**
   * カスタム制御の設定
   */
  setupCustomControls() {
    if (!this.swiperInstance || !this.element) return;

    // ホバー時の自動再生制御
    this.element.addEventListener("mouseenter", () => {
      if (this.swiperInstance && this.swiperInstance.autoplay) {
        this.swiperInstance.autoplay.stop();
      }
    });

    this.element.addEventListener("mouseleave", () => {
      if (this.swiperInstance && this.swiperInstance.autoplay) {
        this.swiperInstance.autoplay.start();
      }
    });
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

  /**
   * 指定したスライドに移動
   */
  slideTo(index, speed = 300) {
    if (this.swiperInstance) {
      this.swiperInstance.slideTo(index, speed);
    }
  }

  /**
   * 次のスライドに移動
   */
  slideNext() {
    if (this.swiperInstance) {
      this.swiperInstance.slideNext();
    }
  }

  /**
   * 前のスライドに移動
   */
  slidePrev() {
    if (this.swiperInstance) {
      this.swiperInstance.slidePrev();
    }
  }
}

// モジュールをグローバルに公開
window.LiverSlider = LiverSlider;
