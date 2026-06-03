/**
 * FVセクション用パーティクルエフェクト設定
 * リアルな渦巻き銀河エフェクト
 * 特徴: 中心が明るい、渦巻き状の腕、ゴールド〜ブルーのグラデーション、星雲効果
 */
function initParticles() {
  console.log("Initializing realistic spiral galaxy...");

  // particles.jsライブラリの読み込み確認
  if (typeof particlesJS === "undefined") {
    console.error("particles.js library not loaded");
    return;
  }

  // パーティクル用コンテナの存在確認
  const particlesContainer = document.getElementById("particles-js");
  if (!particlesContainer) {
    console.error("particles-js container not found");
    return;
  }

  console.log("Container found, creating spiral galaxy...");

  // particles.jsの設定と初期化
  particlesJS("particles-js", {
    particles: {
      number: {
        value: 250, // より密度の高い銀河
        density: {
          enable: true,
          value_area: 900,
        },
      },
      color: {
        // リアルな銀河カラー: 中心の黄金色、腕の白/青、外縁の青紫
        value: [
          "#fef3c7", // 中心部の明るい黄金
          "#fde68a", // ゴールド
          "#fbbf24", // アンバー
          "#f59e0b", // オレンジゴールド
          "#ffffff", // 明るい白色の星
          "#e0f2fe", // ライトブルー
          "#bae6fd", // スカイブルー
          "#7dd3fc", // ブライトブルー
          "#38bdf8", // シアン
          "#0ea5e9", // ブルー
          "#3b82f6", // インディゴブルー
          "#6366f1", // パープルブルー
        ],
      },
      shape: {
        type: "circle",
        stroke: {
          width: 0,
          color: "#000000",
        },
      },
      opacity: {
        value: 0.8,
        random: true,
        anim: {
          enable: true,
          speed: 0.2, // ゆっくりした瞬き
          opacity_min: 0.2,
          sync: false,
        },
      },
      size: {
        value: 3, // 様々なサイズの星
        random: true,
        anim: {
          enable: true,
          speed: 1,
          size_min: 0.5,
          sync: false,
        },
      },
      line_linked: {
        enable: true, // 銀河の腕の構造を表現
        distance: 100,
        color: "#fef3c7", // ゴールドがかった接続線
        opacity: 0.15, // 繊細な接続
        width: 0.8,
      },
      move: {
        enable: true,
        speed: 0.5, // 非常にゆっくりとした回転
        direction: "none",
        random: false,
        straight: false,
        out_mode: "out",
        bounce: false,
        attract: {
          enable: false, // 強い中心への引力で渦巻き効果
          rotateX: 800,
          rotateY: 1600,
        },
      },
    },
    interactivity: {
      detect_on: "canvas",
      events: {
        onhover: {
          enable: true,
          mode: ["grab", "bubble"], // 銀河の腕が反応＋星が明るくなる
        },
        //タップ時の反応
        ontouchstart: {
          enable: true,
          mode: "push", // タップで超新星が誕生
        },
        onclick: {
          enable: true,
          mode: "push", // クリックで超新星が誕生
        },
        resize: true,
      },
      modes: {
        grab: {
          distance: 200,
          line_linked: {
            opacity: 0.4, // ホバー時に銀河の腕が明確に
          },
        },
        bubble: {
          distance: 200,
          size: 8, // ホバー時に星が大きく明るく
          duration: 2,
          opacity: 1,
          speed: 2,
        },
        repulse: {
          distance: 150,
          duration: 0.4,
        },
        push: {
          particles_nb: 8, // 多くの星が誕生
        },
        remove: {
          particles_nb: 2,
        },
      },
    },
    retina_detect: true,
  });

  console.log(
    "Realistic spiral galaxy initialized - Golden core with blue arms"
  );
}

/**
 * Spiral layout + slow rotation helper
 * - particles.js の内部エンジンが `pJSDom` を使っている前提で操作
 * - 初期化後にパーティクルを螺旋状に再配置し、requestAnimationFrame で回転させる
 */
function applySpiralLayout() {
  // pJSDom にアクセスできるか確認
  if (typeof window.pJSDom === "undefined" || !window.pJSDom.length) {
    console.warn("pJSDom not available - spiral layout skipped");
    return;
  }

  const pJS = window.pJSDom[0].pJS;
  if (!pJS || !pJS.particles || !pJS.particles.array) {
    console.warn("particles array not found - spiral layout skipped");
    return;
  }

  const particles = pJS.particles.array;
  const canvas = pJS.canvas.el;
  const ctx = pJS.canvas.ctx;
  const w = canvas.width;
  const h = canvas.height;
  const cx = w / 2;
  const cy = h / 2;

  // 螺旋パラメータ
  const arms = 20; // 銀河の腕の数（調整可能）
  const turns = 2.2; // 中心から外側への巻き数
  const maxR = Math.min(w, h) * 0.48;

  // 初期配置を螺旋に割り当てる
  particles.forEach((p, i) => {
    const t = i / particles.length; // 0..1
    // 各腕に割り振る
    const arm = i % arms;
    const armOffset = (arm / arms) * Math.PI * 2;

    // radius をランダムで広げて星雲感を出す
    const r = Math.pow(t, 0.6) * maxR * (0.8 + Math.random() * 0.6);
    const angle =
      armOffset + t * turns * Math.PI * 2 + (Math.random() - 0.5) * 0.6;

    // canvas の座標に変換
    const x = cx + Math.cos(angle) * r;
    const y = cy + Math.sin(angle) * r;

    // particles.js の座標に反映
    p.x = x;
    p.y = y;
    // 中心に近いほど明るく（opacity）し、サイズをやや大きくする
    p.opacity = { value: Math.min(1, 0.4 + (1 - t) * 1.2) };
    p.radius = Math.max(0.6, (1 - t) * 4 + Math.random() * 1.5);
    // size プロパティも更新（pJS は radius を使って描画する）
    if (pJS.particles && pJS.particles.size) {
      pJS.particles.size.value = pJS.particles.size.value; // no-op to keep structure
    }
  });

  // 輝き強化パラメータ
  const coreGlow = {
    radius: Math.max(80, Math.min(w, h) * 0.38),
    intensity: 0.9, // 0..1
    color: "rgba(255,244,200,", // gold-ish prefix; will append alpha
  };

  // フレア（超新星的な瞬間光）
  const flares = [];
  const flareChancePerFrame = 0.05; // フレア発生確率（各フレーム）

  function drawCoreGlow() {
    try {
      ctx.save();
      ctx.globalCompositeOperation = "lighter";
      const g = ctx.createRadialGradient(cx, cy, 0, cx, cy, coreGlow.radius);
      g.addColorStop(0, coreGlow.color + 0.9 * coreGlow.intensity + ")");
      g.addColorStop(0.35, coreGlow.color + 0.45 * coreGlow.intensity + ")");
      g.addColorStop(1, coreGlow.color + "0)");
      ctx.fillStyle = g;
      ctx.beginPath();
      ctx.arc(cx, cy, coreGlow.radius, 0, Math.PI * 2);
      ctx.fill();
      ctx.restore();
    } catch (e) {
      // 描画失敗しても継続
      console.warn("core glow draw failed", e);
    }
  }

  function spawnFlare() {
    // コア近傍にランダムに生成
    const angle = Math.random() * Math.PI * 2;
    const r = Math.random() * (coreGlow.radius * 0.9);
    const x = cx + Math.cos(angle) * r;
    const y = cy + Math.sin(angle) * r;
    const size = 4 + Math.random() * 6;
    flares.push({ x, y, size, life: 1.0, decay: 0.01 + Math.random() * 0.02 });
  }

  function drawFlares() {
    if (!flares.length) return;
    ctx.save();
    ctx.globalCompositeOperation = "lighter";
    flares.forEach((f, idx) => {
      const alpha = Math.max(0, f.life);
      const grd = ctx.createRadialGradient(f.x, f.y, 0, f.x, f.y, f.size * 4);
      grd.addColorStop(0, "rgba(255,250,230," + 0.9 * alpha + ")");
      grd.addColorStop(0.3, "rgba(255,200,120," + 0.6 * alpha + ")");
      grd.addColorStop(1, "rgba(255,200,120,0)");
      ctx.fillStyle = grd;
      ctx.beginPath();
      ctx.arc(f.x, f.y, f.size * 3, 0, Math.PI * 2);
      ctx.fill();
      // life を減衰
      f.life -= f.decay;
    });
    // 配列を掃除
    for (let i = flares.length - 1; i >= 0; i--) {
      if (flares[i].life <= 0) flares.splice(i, 1);
    }
    ctx.restore();
  }

  // 緩やかな回転アニメーション（パーティクル回転＋グロー/フレア描画）
  let rotation = 0;
  const rotationSpeed = 0.000005; // 回転速度（調整可）

  function rotateStep() {
    rotation += rotationSpeed;
    // 各パーティクルを回転させる
    particles.forEach((p) => {
      const dx = p.x - cx;
      const dy = p.y - cy;
      const r = Math.sqrt(dx * dx + dy * dy);
      const theta = Math.atan2(dy, dx) + rotation;
      p.x = cx + Math.cos(theta) * r;
      p.y = cy + Math.sin(theta) * r;
    });

    // pJS の描画
    if (pJS.fn && pJS.fn.particlesDraw) pJS.fn.particlesDraw();

    // コアのグローを描画（パーティクルの上に加算合成で乗せる）
    drawCoreGlow();

    // ランダムにフレアを発生させる
    if (Math.random() < flareChancePerFrame) spawnFlare();
    drawFlares();

    requestAnimationFrame(rotateStep);
  }

  // 少し遅延してアニメーション開始（canvas サイズ確定後）
  setTimeout(() => {
    requestAnimationFrame(rotateStep);
  }, 220);
}

// particles 初期化後にスパイラル配置を適用
document.addEventListener("DOMContentLoaded", () => {
  setTimeout(() => {
    applySpiralLayout();
  }, 350);
});

/**
 * DOMContentLoaded後にパーティクルを初期化
 * 100ms遅延でライブラリの読み込み完了を確実にする
 */
document.addEventListener("DOMContentLoaded", () => {
  setTimeout(initParticles, 100);
});
