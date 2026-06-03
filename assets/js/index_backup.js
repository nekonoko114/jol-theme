// Swiper初期化
document.addEventListener("DOMContentLoa      // タブレット（768px以上で複数表示復活）
      768: {
        slidesPerView: "auto",
        spaceBetween: 25,
        centeredSlides: false,
      },
      // デスクトップ
      1024: {
        slidesPerView: "auto",
        spaceBetween: 30,
        centeredSlides: false,
      },
      // 大画面
      1400: {
        slidesPerView: "auto",
        spaceBetween: 30,
        centeredSlides: false,
      },{
  // Swiperの要素が存在するかチェック
  const swiperElement = document.querySelector(".swiper");
  if (!swiperElement) {
    return;
  }

  // 基本的なSwiperの設定
  const swiper = new Swiper(".swiper", {
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
    effect: "slide",

    // 速度
    speed: 800,

    // レスポンシブ設定
    breakpoints: {
      // スマホ（480px以下で完全中央寄せ）
      480: {
        slidesPerView: 1,
        spaceBetween: 0, // スペース0で完全中央
        centeredSlides: true,
        centerInsufficientSlides: true,
      },
      // タブレット
      768: {
        slidesPerView: 1,
        spaceBetween: 25,
        centeredSlides: false,
      },
      // デスクトップ
      1024: {
        slidesPerView: 2,
        spaceBetween: 30,
        centeredSlides: false,
      },
      // 大画面
      1400: {
        slidesPerView: 3,
        spaceBetween: 30,
        centeredSlides: false,
      },
    },
  });

  // ============================================================================
  // LIVER SLIDER - 完全に独立したSwiper設定
  // ============================================================================
  const liverSliderElement = document.querySelector(".liver-slider");
  if (liverSliderElement) {
    const liverSlider = new Swiper(".liver-slider .liver-swiper", {
      // 縦長カードの横スクロール設定
      slidesPerView: 3,
      spaceBetween: 40,
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

      // 独自のナビゲーション
      navigation: {
        nextEl: ".liver-slider .liver-button-next",
        prevEl: ".liver-slider .liver-button-prev",
      },

      // 自動再生（独自設定）
      autoplay: {
        delay: 5000,
        disableOnInteraction: false,
        pauseOnMouseEnter: true,
        reverseDirection: false,
      },

      // ループ設定
      loop: true,
      loopAdditionalSlides: 1,

      // エフェクト
      effect: "slide",

      // 速度設定
      speed: 1200,

      // タッチ・マウス操作
      touchRatio: 1,
      touchAngle: 45,
      grabCursor: true,

      // レスポンシブ設定
      breakpoints: {
        // モバイル（320px〜）
        320: {
          slidesPerView: 1,
          spaceBetween: 20,
          centeredSlides: true,
        },
        // スマートフォン（480px〜）
        480: {
          slidesPerView: 1,
          spaceBetween: 25,
          centeredSlides: true,
        },
        // タブレット（768px〜）
        768: {
          slidesPerView: 2,
          spaceBetween: 30,
          centeredSlides: false,
        },
        // デスクトップ（1024px〜）
        1024: {
          slidesPerView: 3,
          spaceBetween: 40,
          centeredSlides: false,
        },
        // 大画面（1400px〜）
        1400: {
          slidesPerView: 3,
          spaceBetween: 60,
          centeredSlides: false,
        },
      },

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
    });

    // Liver Slider専用のカスタム制御
    if (liverSlider) {
      // ホバー時の自動再生制御
      liverSliderElement.addEventListener("mouseenter", () => {
        liverSlider.autoplay.stop();
      });

      liverSliderElement.addEventListener("mouseleave", () => {
        liverSlider.autoplay.start();
      });
    }
  }
});
