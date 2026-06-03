// Swiper初期化
document.addEventListener("DOMContentLoaded", function () {
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
        delay: 3000,
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

      // リンククリックを許可
      allowTouchMove: true,
      preventClicks: false,
      preventClicksPropagation: false,

      // 安定化オプション
      observer: true,
      observeParents: true,
      roundLengths: true,
      watchOverflow: true,
      slideToClickedSlide: true,

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
          slidesPerView: 4,
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
        slideChange: function () {
          console.log("Liver Slider - スライド変更:", this.activeIndex);
        },
        touchStart: function () {
          // タッチ開始時の処理
          this.autoplay.stop();
        },
        touchEnd: function () {
          // タッチ終了時の処理
          this.autoplay.start();
        },
        resize: function () {
          // リサイズ時に再計算して見切れを防ぐ
          this.update();
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

$(window).on("load", function () {
  $("#splash-logo").delay(1200).fadeOut("slow"); //ロゴを1.2秒でフェードアウトする記述

  //=====ここからローディングエリア（splashエリア）を1.5秒でフェードアウトした後に動かしたいJSをまとめる
  $("#splash")
    .delay(1500)
    .fadeOut("slow", function () {
      //ローディングエリア（splashエリア）を1.5秒でフェードアウトする記述

      $("body").addClass("appear"); //フェードアウト後bodyにappearクラス付与
    });
  //=====ここまでローディングエリア（splashエリア）を1.5秒でフェードアウトした後に動かしたいJSをまとめる

  //=====ここから背景が伸びた後に動かしたいJSをまとめたい場合は
  $(".splashbg").on("animationend", function () {
    //この中に動かしたいJSを記載
  });
  //=====ここまで背景が伸びた後に動かしたいJSをまとめる
});
