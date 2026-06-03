class LoadingAnimation {
  constructor(options = {}) {
    this.config = {
      splashSelector: "#splash",
      logoSelector: "#splash-logo", 
      containerSelector: "#container",
      splashBgSelector: ".splashbg",
      bodyAppearClass: "appear",
      logoFadeDelay: 1300,
      splashFadeDelay: 1300,
      fadeSpeed: "fast",
      enabled: true,
      svgElementSelector: 'svg [class*="svg-elem"]',
      svgMaxWaitTime: 3000,
      svgCheckInterval: 500,
      ...options,
    };
    this.isInitialized = false;
    this.isCompleted = false;
  }

  init() {
    if (!this.config.enabled) {
      console.log("ローディングアニメーション: 無効化されています");
      this.forceComplete();
      return Promise.resolve(false);
    }

    if (!this.isFrontPage()) {
      console.log("フロントページ以外: 短時間ローディング実行");
      this.setupQuickLoading();
      return Promise.resolve(true);
    }

    return this.waitForjQuery()
      .then(() => {
        if (!this.checkRequiredElements()) {
          console.log("必要な要素が見つかりません");
          return false;
        }
        this.setupLoadingAnimation();
        this.isInitialized = true;
        return true;
      })
      .catch((error) => {
        console.error("初期化エラー:", error);
        return false;
      });
  }

  setupQuickLoading() {
    if (document.readyState === 'loading') {
      document.addEventListener('DOMContentLoaded', () => {
        this.executeQuickLoading();
      });
    } else {
      this.executeQuickLoading();
    }
  }

  executeQuickLoading() {
    setTimeout(() => {
      const splashElement = document.querySelector(this.config.splashSelector);
      if (splashElement) {
        splashElement.style.transition = 'opacity 0.3s ease-out';
        splashElement.style.opacity = '0';
        setTimeout(() => {
          splashElement.style.display = 'none';
          document.body.classList.add(this.config.bodyAppearClass);
          this.isCompleted = true;
          console.log("短時間ローディング完了");
        }, 300);
      } else {
        document.body.classList.add(this.config.bodyAppearClass);
        this.isCompleted = true;
        console.log("即座に完了");
      }
    }, 100);
  }

  waitForjQuery(maxAttempts = 10, interval = 100) {
    return new Promise((resolve, reject) => {
      let attempts = 0;
      const checkjQuery = () => {
        if (typeof $ !== "undefined" && typeof jQuery !== "undefined") {
          resolve();
          return;
        }
        attempts++;
        if (attempts >= maxAttempts) {
          reject(new Error("jQueryタイムアウト"));
          return;
        }
        setTimeout(checkjQuery, interval);
      };
      checkjQuery();
    });
  }

  isFrontPage() {
    return document.body.classList.contains("home") || 
           document.body.classList.contains("front-page");
  }

  checkRequiredElements() {
    const selectors = [this.config.splashSelector, this.config.logoSelector, this.config.containerSelector];
    return selectors.every(selector => $(selector).length > 0);
  }

  setupLoadingAnimation() {
    $(window).on("load", () => {
      this.triggerSVGAnimation();
      this.startAnimation();
    });
  }

  triggerSVGAnimation() {
    const svg = $(this.config.logoSelector + " svg");
    if (svg.length === 0) {
      console.log("SVG要素なし");
      return;
    }
    console.log("SVGアニメーション開始");
    svg.addClass("active");
  }

  startAnimation() {
    if (this.isCompleted) return;
    this.waitForSVGAnimation()
      .then(() => this.fadeOutAnimation())
      .catch(() => this.fadeOutAnimation());
  }

  waitForSVGAnimation() {
    return new Promise((resolve) => {
      setTimeout(resolve, 3000); // 3秒後に強制完了
    });
  }

  fadeOutAnimation() {
    $(this.config.logoSelector).delay(this.config.logoFadeDelay).fadeOut(this.config.fadeSpeed);
    $(this.config.splashSelector).delay(this.config.splashFadeDelay).fadeOut(this.config.fadeSpeed, () => {
      this.onSplashComplete();
    });
  }

  onSplashComplete() {
    $("body").addClass(this.config.bodyAppearClass);
    this.isCompleted = true;
    console.log("ローディング完了");
  }

  forceComplete() {
    if (this.isCompleted) return;
    const logo = document.querySelector(this.config.logoSelector);
    const splash = document.querySelector(this.config.splashSelector);
    if (logo) logo.style.display = 'none';
    if (splash) splash.style.display = 'none';
    document.body.classList.add(this.config.bodyAppearClass);
    this.isCompleted = true;
    console.log("強制完了");
  }

  reset() {
    const logo = document.querySelector(this.config.logoSelector);
    const splash = document.querySelector(this.config.splashSelector);
    if (logo) logo.style.display = '';
    if (splash) splash.style.display = '';
    document.body.classList.remove(this.config.bodyAppearClass);
    this.isCompleted = false;
  }

  getStatus() {
    return {
      isInitialized: this.isInitialized,
      isCompleted: this.isCompleted,
      hasSVGElements: false
    };
  }
}

window.LoadingAnimation = LoadingAnimation;
