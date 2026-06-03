/**
 * メインアプリケーション初期化モジュール
 * 全てのモジュールを統括し、適切な順序で初期化を行う
 */
class App {
  constructor() {
    this.modules = {
      mainSwiper: null,
      liverSlider: null,
      loadingAnimation: null,
    };

    this.isInitialized = false;
    this.config = {
      enableLogging: true,
      autoInit: true,
    }
  }

  /**
   * アプリケーションの初期化
   */
  init() {
    if (this.isInitialized) {
      this.log("アプリケーションは既に初期化されています");
      return;
    }
    // DOM読み込み完了を待つ
    document.addEventListener("DOMContentLoaded", () => {
      this.initializeModules();
    });

    this.isInitialized = true;
  }

  /**
   * 各モジュールの初期化
   */
  async initializeModules() {
    try {
      // 1. ローディングアニメーションの初期化（最初に実行）
      await this.initLoadingAnimation();

      // 2. Swiperモジュールの初期化
      this.initSwiperModules();

      // 3. イベントリスナーの設定
      this.setupEventListeners();

    } catch (error) {
      console.error("モジュール初期化中にエラーが発生しました:", error);
    }
  }

  /**
   * ローディングアニメーションの初期化
   */
  async initLoadingAnimation() {
    if (typeof LoadingAnimation === "undefined") {
      this.log("LoadingAnimationモジュールが見つかりません");
      return;
    }

    this.modules.loadingAnimation = new LoadingAnimation();

    try {
      const success = await this.modules.loadingAnimation.init();
      if (success) {
      } else {
      }
    } catch (error) {
      this.log("ローディングアニメーションの初期化に失敗: " + error.message);
    }
  }

  /**
   * Swiperモジュールの初期化
   */
  initSwiperModules() {
    // メインSwiperの初期化
    this.initMainSwiper();

    // LiverSliderの初期化
    this.initLiverSlider();
  }

  /**
   * メインSwiperの初期化
   */
  initMainSwiper() {
    if (typeof MainSwiper === "undefined") {
      this.log("MainSwiperモジュールが見つかりません");
      return;
    }

    this.modules.mainSwiper = new MainSwiper();
    const success = this.modules.mainSwiper.init();

    if (success) {
    } else {
    }
  }

  /**
   * LiverSliderの初期化
   */
  initLiverSlider() {
    if (typeof LiverSlider === "undefined") {
      this.log("LiverSliderモジュールが見つかりません");
      return;
    }

    this.modules.liverSlider = new LiverSlider();
    const success = this.modules.liverSlider.init();

    if (success) {
    } else {
    }
  }

  /**
   * グローバルイベントリスナーの設定
   */
  setupEventListeners() {
    // ローディングアニメーション完了イベント
    window.addEventListener("loadingAnimationComplete", (event) => {
      this.log("ローディングアニメーションが完了しました");
      this.onLoadingComplete(event.detail);
    });

    // 背景アニメーション完了イベント
    window.addEventListener("backgroundAnimationComplete", (event) => {
      this.log("背景アニメーションが完了しました");
      this.onBackgroundAnimationComplete(event.detail);
    });

    // ページ離脱時のクリーンアップ
    // window.addEventListener("beforeunload", () => {
    //   this.destroy();
    // });
  }

  /**
   * ローディング完了時の処理
   */
  onLoadingComplete(detail) {
    // ローディング完了後に実行したい処理をここに追加
    this.log("ローディング完了後の処理を実行中...");

    // 例: Swiperの自動再生を開始
    if (this.modules.mainSwiper) {
      this.modules.mainSwiper.startAutoplay();
    }
    if (this.modules.liverSlider) {
      this.modules.liverSlider.startAutoplay();
    }
  }

  /**
   * 背景アニメーション完了時の処理
   */
  onBackgroundAnimationComplete(detail) {
    // 背景アニメーション完了後に実行したい処理をここに追加
    this.log("背景アニメーション完了後の処理を実行中...");
  }

  /**
   * モジュールの取得
   */
  getModule(moduleName) {
    return this.modules[moduleName] || null;
  }

  /**
   * 全モジュールの状態取得
   */
  getStatus() {
    const status = {
      isInitialized: this.isInitialized,
      modules: {},
    };

    Object.keys(this.modules).forEach((key) => {
      const module = this.modules[key];
      if (module && typeof module.getStatus === "function") {
        status.modules[key] = module.getStatus();
      } else {
        status.modules[key] = { exists: !!module };
      }
    });

    return status;
  }

  /**
   * アプリケーションの破棄
   */
  // destroy() {
  //   this.log("アプリケーションを破棄中...");

  //   Object.values(this.modules).forEach((module) => {
  //     if (module && typeof module.destroy === "function") {
  //       module.destroy();
  //     }
  //   });

  //   this.modules = {};
  //   this.isInitialized = false;
  //   this.log("アプリケーションの破棄完了");
  // }

  // /**
  //  * ログ出力
  //  */
  // log(message) {
  //   if (this.config.enableLogging) {
  //     console.log(`[App] ${message}`);
  //   }
  // }
}

/**
 * 
    hamburger.jsの読み込み
 * 
 * */
// Hamburger.js is already loaded by functions.php


// グローバルアプリケーションインスタンス
window.app = new App();

// 自動初期化
if (window.app.config.autoInit) {
  window.app.init();
}

// モジュールをグローバルに公開
window.App = App;

