/**
 * FVセクション用 幻想的3D奥行き＆大玉ボケ・ライズスパークルエンジン
 * - 遠景：極小（0.4〜1.3px）の繊細な星屑がゆっくりと瞬きながら上昇
 * - 中景：中サイズの柔らかい光粒
 * - 近景：大きな玉ボケ（24〜68px）が多段階ガウス風グラデーションでふんわり溶けながら通過
 */
function initAuroraBokehParticles() {
  const container = document.getElementById("particles-js");
  if (!container) return;

  container.innerHTML = "";

  const canvas = document.createElement("canvas");
  canvas.style.width = "100%";
  canvas.style.height = "100%";
  canvas.style.display = "block";
  container.appendChild(canvas);

  const ctx = canvas.getContext("2d");
  if (!ctx) return;

  const palette = [
    { r: 0, g: 255, b: 255 },   // J.O.L シアン
    { r: 103, g: 232, b: 249 }, // アクアブルー
    { r: 168, g: 85, b: 247 },  // オーロラパープル
    { r: 138, g: 43, b: 226 },  // ディープバイオレット
    { r: 232, g: 121, b: 249 }, // ファンタジーマゼンタ
    { r: 255, g: 255, b: 255 }, // スターホワイト
  ];

  let width = 0;
  let height = 0;
  let dpr = Math.min(window.devicePixelRatio || 1, 2);
  let particles = [];
  let mouse = { x: 0, y: 0, targetX: 0, targetY: 0 };

  function resize() {
    width = container.clientWidth || window.innerWidth;
    height = container.clientHeight || window.innerHeight;
    dpr = Math.min(window.devicePixelRatio || 1, 2);
    canvas.width = Math.floor(width * dpr);
    canvas.height = Math.floor(height * dpr);
    ctx.setTransform(dpr, 0, 0, dpr, 0, 0);
  }

  function createParticle(spawnAtBottom = false, customX = null, customY = null) {
    const layerRoll = Math.random();
    const isMobile = width < 768;
    let depth;
    let baseRadius;
    let baseAlpha;

    if (layerRoll < 0.45) {
      // 遠景：ごく小さく繊細な星屑（遠くの奥行き）
      depth = 0.06 + Math.random() * 0.22;
      baseRadius = 0.7 + Math.random() * 1.4;
      baseAlpha = 0.45 + Math.random() * 0.4;
    } else if (layerRoll < 0.84) {
      // 中景：はっきり視認できる中間サイズの光の粒子
      depth = 0.34 + Math.random() * 0.34;
      baseRadius = isMobile ? 4.5 + Math.random() * 7.5 : 6 + Math.random() * 11;
      baseAlpha = 0.36 + Math.random() * 0.32;
    } else {
      // 近景：手前を大きく横切る存在感のあるソフトフォーカス玉ボケ
      depth = 0.75 + Math.random() * 0.25;
      baseRadius = isMobile ? 34 + Math.random() * 44 : 52 + Math.random() * 68;
      baseAlpha = 0.24 + Math.random() * 0.18;
    }

    const color = palette[Math.floor(Math.random() * palette.length)];

    return {
      x: customX !== null ? customX : Math.random() * width,
      y: customY !== null ? customY : spawnAtBottom ? height + baseRadius * 2 : Math.random() * height,
      depth,
      radius: baseRadius,
      speedY: 0.1 + Math.pow(depth, 1.7) * 1.25, // 奥は極遅く、手前はしっかり流れる
      swayAmp: 0.08 + depth * 0.42,
      swayFreq: 0.006 + Math.random() * 0.01,
      phase: Math.random() * Math.PI * 2,
      twinkleSpeed: 0.01 + Math.random() * 0.02,
      baseAlpha,
      color,
    };
  }

  function initParticlesArray() {
    const count = width < 768 ? 130 : 240;
    particles = [];
    for (let i = 0; i < count; i++) {
      particles.push(createParticle(false));
    }
    // 奥から手前へ描画するため深度順にソート
    particles.sort((a, b) => a.depth - b.depth);
  }

  container.addEventListener("mousemove", (e) => {
    const rect = container.getBoundingClientRect();
    const nx = (e.clientX - rect.left) / rect.width - 0.5;
    const ny = (e.clientY - rect.top) / rect.height - 0.5;
    mouse.targetX = nx * 64;
    mouse.targetY = ny * 40;
  });

  container.addEventListener("mouseleave", () => {
    mouse.targetX = 0;
    mouse.targetY = 0;
  });

  container.addEventListener("click", (e) => {
    const rect = container.getBoundingClientRect();
    const cx = e.clientX - rect.left;
    const cy = e.clientY - rect.top;
    for (let i = 0; i < 4; i++) {
      particles.push(
        createParticle(
          false,
          cx + (Math.random() - 0.5) * 48,
          cy + (Math.random() - 0.5) * 48
        )
      );
    }
    if (particles.length > 280) {
      particles.splice(0, particles.length - 280);
    }
    particles.sort((a, b) => a.depth - b.depth);
  });

  function render() {
    ctx.clearRect(0, 0, width, height);

    mouse.x += (mouse.targetX - mouse.x) * 0.045;
    mouse.y += (mouse.targetY - mouse.y) * 0.045;

    ctx.save();
    ctx.globalCompositeOperation = "lighter";

    for (let i = 0; i < particles.length; i++) {
      const p = particles[i];

      p.y -= p.speedY;
      p.phase += p.swayFreq;
      p.x += Math.sin(p.phase) * p.swayAmp;

      if (p.y < -p.radius * 2 - 20) {
        p.y = height + p.radius * 2 + 10;
        p.x = Math.random() * width;
      }
      if (p.x < -p.radius * 2) p.x = width + p.radius;
      if (p.x > width + p.radius * 2) p.x = -p.radius;

      const drawX = p.x - mouse.x * p.depth;
      const drawY = p.y - mouse.y * p.depth;

      const twinkle = 0.68 + 0.32 * Math.sin(p.phase * 1.5 + p.twinkleSpeed * 100);
      const alpha = p.baseAlpha * twinkle;
      const { r, g, b } = p.color;

      if (p.depth < 0.3) {
        // 遠景：ごく小さな星屑
        ctx.fillStyle = `rgba(${r}, ${g}, ${b}, ${alpha})`;
        ctx.beginPath();
        ctx.arc(drawX, drawY, p.radius, 0, Math.PI * 2);
        ctx.fill();
      } else if (p.depth < 0.72) {
        // 中景：優しい光の粒子
        const grad = ctx.createRadialGradient(drawX, drawY, 0, drawX, drawY, p.radius);
        grad.addColorStop(0, `rgba(${r}, ${g}, ${b}, ${alpha * 0.9})`);
        grad.addColorStop(0.4, `rgba(${r}, ${g}, ${b}, ${alpha * 0.45})`);
        grad.addColorStop(0.75, `rgba(${r}, ${g}, ${b}, ${alpha * 0.12})`);
        grad.addColorStop(1, `rgba(${r}, ${g}, ${b}, 0)`);
        ctx.fillStyle = grad;
        ctx.beginPath();
        ctx.arc(drawX, drawY, p.radius, 0, Math.PI * 2);
        ctx.fill();
      } else {
        // 近景：多段階の滑らかなソフトフォーカス玉ボケ
        const grad = ctx.createRadialGradient(drawX, drawY, 0, drawX, drawY, p.radius);
        grad.addColorStop(0, `rgba(${r}, ${g}, ${b}, ${alpha * 0.82})`);
        grad.addColorStop(0.28, `rgba(${r}, ${g}, ${b}, ${alpha * 0.58})`);
        grad.addColorStop(0.58, `rgba(${r}, ${g}, ${b}, ${alpha * 0.28})`);
        grad.addColorStop(0.84, `rgba(${r}, ${g}, ${b}, ${alpha * 0.08})`);
        grad.addColorStop(1, `rgba(${r}, ${g}, ${b}, 0)`);
        ctx.fillStyle = grad;
        ctx.beginPath();
        ctx.arc(drawX, drawY, p.radius, 0, Math.PI * 2);
        ctx.fill();
      }
    }

    ctx.restore();
    requestAnimationFrame(render);
  }

  resize();
  initParticlesArray();
  window.addEventListener("resize", () => {
    resize();
    initParticlesArray();
  });

  requestAnimationFrame(render);
}

if (document.readyState === "loading") {
  document.addEventListener("DOMContentLoaded", initAuroraBokehParticles);
} else {
  initAuroraBokehParticles();
}
