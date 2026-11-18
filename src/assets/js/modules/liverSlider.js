/**
 * LiverSliderモジュール（Expo Slider 風パララックス＆グレースケール効果）
 * ライバー専用スライダー機能を管理
 */
export class LiverSlider {
  constructor() {
    this.swiperInstance = null;
    this.element = null;
    this.config = {
      slidesPerView: "auto",
      centeredSlides: true,
      spaceBetween: 24,
      watchSlidesProgress: true,
      rewind: true,
      grabCursor: true,
      speed: 700,

      pagination: {
        el: ".liver-slider .liver-pagination",
        clickable: true,
        bulletClass: "liver-bullet",
        bulletActiveClass: "liver-bullet-active",
        renderBullet: function (index, className) {
          return '<span class="' + className + ' liver-pagination-bullet"></span>';
        },
      },

      autoplay: {
        delay: 4000,
        disableOnInteraction: false,
        pauseOnMouseEnter: true,
      },

      breakpoints: {
        768: {
          spaceBetween: 32,
        },
      },

      on: {
        init: (swiper) => {
          swiper.slides.forEach((slide, index) => {
            slide.setAttribute("data-liver-index", index);
          });
          this.applyExpoEffect(swiper);
        },
        setTranslate: (swiper) => {
          this.applyExpoEffect(swiper);
        },
        setTransition: (swiper, duration) => {
          const easing = "cubic-bezier(0.16, 1, 0.3, 1)";
          swiper.slides.forEach((slide) => {
            slide.style.transitionDuration = `${duration}ms`;
            slide.style.transitionTimingFunction = easing;
            const img = slide.querySelector(".expo-slide-img");
            const content = slide.querySelector(".expo-slide-content");
            if (img) {
              img.style.transitionDuration = `${duration}ms`;
              img.style.transitionTimingFunction = easing;
            }
            if (content) {
              content.style.transitionDuration = `${duration}ms`;
              content.style.transitionTimingFunction = easing;
            }
          });
        },
        touchStart: (swiper) => {
          if (swiper.autoplay) swiper.autoplay.stop();
        },
        touchEnd: (swiper) => {
          if (swiper.autoplay) swiper.autoplay.start();
        },
      },
    };
  }

  /**
   * Expo Slider 風のパララックス・スケール・グレースケール制御
   */
  applyExpoEffect(swiper) {
    swiper.slides.forEach((slide) => {
      const progress = slide.progress || 0;
      const absProgress = Math.min(Math.abs(progress), 2);

      // 中央(0)は等倍＆フルカラー、左右は縮小＆グレースケール
      const scale = 1 - absProgress * 0.14;
      const grayscale = Math.min(absProgress * 0.9, 1);
      const opacity = Math.max(1 - absProgress * 0.32, 0.38);

      slide.style.transform = `scale(${scale})`;
      slide.style.filter = `grayscale(${grayscale})`;
      slide.style.opacity = opacity;

      // 画像のパララックス移動＋微細ズーム
      const img = slide.querySelector(".expo-slide-img");
      if (img) {
        const imgScale = 1.0 + Math.min(absProgress * 0.16, 0.24);
        const imgTranslateX = progress * -18;
        img.style.transform = `translateX(${imgTranslateX}%) scale(${imgScale})`;
      }

      // キャプションのフォーカスフェード
      const content = slide.querySelector(".expo-slide-content");
      if (content) {
        const contentOpacity = Math.max(1 - absProgress * 0.85, 0.2);
        content.style.opacity = contentOpacity;
      }
    });
  }

  /**
   * LiverSliderの初期化
   */
  init() {
    this.element = document.querySelector(".liver-slider");
    if (!this.element) {
      return false;
    }

    try {
      const slideCount = this.element.querySelectorAll(".swiper-slide").length;
      if (slideCount > 2) {
        this.config.initialSlide = Math.floor(slideCount / 2);
      }

      this.swiperInstance = new Swiper(
        ".liver-slider .liver-swiper",
        this.config
      );

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

  getInstance() {
    return this.swiperInstance;
  }

  startAutoplay() {
    if (this.swiperInstance && this.swiperInstance.autoplay) {
      this.swiperInstance.autoplay.start();
    }
  }

  stopAutoplay() {
    if (this.swiperInstance && this.swiperInstance.autoplay) {
      this.swiperInstance.autoplay.stop();
    }
  }

  slideTo(index, speed = 300) {
    if (this.swiperInstance) {
      this.swiperInstance.slideTo(index, speed);
    }
  }

  slideNext() {
    if (this.swiperInstance) {
      this.swiperInstance.slideNext();
    }
  }

  slidePrev() {
    if (this.swiperInstance) {
      this.swiperInstance.slidePrev();
    }
  }
}

window.LiverSlider = LiverSlider;
