# JOL WordPress Theme (Vite Workflow)

このプロジェクトは、Vite と Sharp を使用した WordPress テーマ開発ワークフローを提供します。
Sass コンパイル、ES Modules による JavaScript バンドル、画像最適化および WebP 変換を備えています。

---

## 特徴

- **Vite による高速ビルド**:
  - `src/assets/scss/style.scss` をコンパイル・Autoprefixer 適用・圧縮して `assets/css/style.css` に出力。
  - `src/assets/js/app.js` から `src/assets/js/modules/*.js` を ES Modules（`import / export`）で束ねて `assets/js/app.js` に出力。
  - ページ専用スクリプト（`ranking.js`, `particles-config.js`）も個別のエントリポイントとして最適化出力。
- **Sharp による画像最適化・WebP 変換**:
  - `scripts/optimize-images.mjs` により、`src/assets/images/` 内の画像を最適化しつつ `.webp` を自動生成して `assets/images/` に出力。

---

## ディレクトリ構造

```text
jol-themes/
 ├── src/
 │   └── assets/
 │       ├── scss/       # Sass ソースファイル
 │       ├── js/         # JavaScript ソースファイル (app.js, modules/, ranking.js, particles-config.js)
 │       └── images/     # 元画像ファイル (jpg, png, gif)
 ├── assets/             # ビルド出力先（WordPress が読み込むディレクトリ）
 │   ├── css/
 │   ├── js/
 │   └── images/
 ├── scripts/
 │   └── optimize-images.mjs # 画像圧縮・WebP変換スクリプト
 ├── vite.config.js      # Vite 設定ファイル
 └── package.json        # npm スクリプト・依存パッケージ定義
```

---

## 使用方法

### 1. 依存パッケージのインストール

```bash
npm install
```

### 2. 開発モード（ファイル監視・自動再ビルド）

```bash
npm run dev
```

### 3. 本番用ビルド（CSS/JS 圧縮 ＋ 画像最適化・WebP 変換）

```bash
npm run build
```

### 4. 画像最適化・WebP 変換のみ実行

```bash
npm run images
```
