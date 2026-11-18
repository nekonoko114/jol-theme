# DESIGN.md — J.O.L Architectural & Editorial UI System

## 1. Core Design Philosophy
このプロジェクトは、J.O.L らしい黒基調＋ネオンアクセントの世界観（ブランドカラー・アイデンティティ）を継承しつつ、レイアウト・余白・角丸・タイポグラフィに建築的かつエディトリアルな規律を取り入れるためのデザインシステムです。

- **J.O.L ブランドの継承**: 既存のベースカラー（`#000000`）およびブランドアクセント（シアン `#00FFFF` / レッド `#FF0000` / パープル `#8A2BE2`）などの世界観は維持します。
- **極端な余白のコントラスト**: `24px` や `32px` を一律に多用せず、「密なグルーピング（`4px–8px`）」と「十分なセクション間の呼吸（`96px–128px`）」を明確に使い分けます。
- **節度ある角丸（4px / 8px ルール）**: 過度な角丸（`12px`, `16px`, `24px` や `rounded-full` のボタン）を新規・改修パーツでは避け、`4px` と `8px` を基準としたシャープで引き締まった輪郭を採用します。
- **構造的な階層表現**: 過度なドロップシャドウに頼らず、`1px` の繊細なボーダー、背景のトーン差、文字サイズのジャンプ率で階層を表現します。

---

## 2. 4px Base Grid & Spacing Rhythm
すべての `margin`、`padding`、`gap`、コンポーネント寸法は **`4px` の倍数** を厳守します。

### Spacing Tokens
- `--space-1`: `4px`（マイクロギャップ：アイコンとラベル間、バッジ内余白）
- `--space-2`: `8px`（密な近接：見出しとサブテキスト、関連ラベルと入力欄）
- `--space-3`: `12px`（コンパクトな要素間ギャップ）
- `--space-4`: `16px`（標準の内部ギャップ、小コンテナのパディング）
- `--space-6`: `24px`（カード内パディング、グリッドのカラムギャップ）
- `--space-8`: `32px`（サブセクション間の区切り）
- `--space-16`: `64px`（モバイルでの主要ブロック間の余白）
- `--space-24`: `96px`（主要セクション間の余白）
- `--space-32`: `128px`（デスクトップでのヒーロー・主要セクションの余白）

### Proximity & Contrast Rules (Mandatory)
1. **Tight Grouping (`4px` – `8px`):** 1つの意味的まとまり（カテゴリータグ＋タイトル、タイトル＋リード文、ラベル＋入力欄）は `4px`〜`8px` 以内に配置します。
2. **Component Containment (`16px` – `24px`):** カード・パネル・モーダルの内側パディングは `16px` または `24px` とします。
3. **Section Separation (`96px` – `128px`):** 異なるセクション間の縦方向の間隔は最低 `96px`（デスクトップでは `128px`）を確保し、視線をリセットします。

---

## 3. Border-Radius Architecture (Strict 4px / 8px Rule)
新規作成・改修するコンポーネントの角丸は、原則として `4px` / `8px` の構造ルールに従います。

- **Small / Interactive Elements (`4px` / Tailwind `rounded`):**
  - ボタン、入力欄、セレクトボックス、タグ、バッジ、カード内のサムネイル画像、ツールチップ
- **Containers / Surfaces (`8px` / Tailwind `rounded-lg`):**
  - モーダル、ドロワー、独立したメディアフレーム、外側のカード、フローティングパネル
- **Concentric Radius Harmony:**
  - `8px`（`rounded-lg`）のコンテナ内に `4px`（`rounded`）の要素を入れ子にする場合、外側のパディングを `4px`（`p-1`）または `8px`（`p-2`）等に揃え、角のアールが調和するように配置します。
- **Exceptions:**
  - `0px`（`rounded-none`）：全幅セクション、テーブル行、区切り線
  - `9999px`（`rounded-full`）：小さなステータスドット（`8px x 8px`）および円形のユーザーアバター画像のみに限定

---

## 4. Typography & Scale
ディスプレイ見出しと機能的なメタデータの間に明確なコントラストを持たせます。

- **Font Stack:**
  - Display / Headings: `'Outfit'`, `'Playfair Display'`, `'Noto Sans JP'`, sans-serif
  - Decorative (Brand Accent): `'Condiment'`, cursive
  - Body / UI: `'Noto Sans JP'`, `'M PLUS Rounded 1c'`, sans-serif
  - Monospace (数値・順位・日時・メタデータ): `'JetBrains Mono'`, `'SFMono-Regular'`, monospace
- **Line-Height Alignment:** 行間（`line-height`）は `4px` の倍数に揃えます（例：本文 `16px / 24px`、大見出し `48px / 56px`）。
- **Jump Ratio:** 本文（`14px–16px`）とセクション見出し（`32px–48px+`）に明確なサイズ差をつけます。
- **Tracking:** 大見出しは字間をやや詰め（`-0.02em` 〜 `-0.03em`）、英字大文字のメタデータ・ラベルはやや広げます（`+0.06em` 〜 `+0.08em`）。

---

## 5. Color & Surface Discipline (J.O.L Palette)
J.O.L の既存カラーを継承しつつ、背景・サーフェス・ボーダーを体系化します。

- **Base & Surface:**
  - `--bg-primary`: `#000000`（ベース背景）
  - `--bg-surface`: `#111113`（カード・パネル表面）
  - `--border-subtle`: `rgba(255, 255, 255, 0.12)`（構造用 `1px` ボーダー）
- **Text:**
  - `--text-primary`: `#EAEAEA`
  - `--text-secondary`: `#A1A1AA`
- **J.O.L Brand Accents:**
  - `--jol-cyan`: `#00FFFF`（プライマリアクセント）
  - `--jol-red`: `#FF0000`（強調・バトル系アクセント）
  - `--jol-purple`: `#8A2BE2`（セカンダリアクセント）
  - `--jol-yellow`: `#FFFF00`
- **Usage Rule:** アクセントカラー（シアン・レッド・パープル）はプライマリ CTA やアクティブ状態など重要な箇所に絞り、画面全体が過度に散らからないよう制御します。

---

## 6. Layout & Motion
- **Asymmetry over Monotony:** 同じ高さの3カラムグリッドばかりを並べるのを避け、非対称レイアウトやフル幅の強調行を織り交ぜます。
- **Micro-Interactions:** トランジションはバウンドさせず、シャープで機械的な応答にします。ホバーやボーダーハイライトには `cubic-bezier(0.16, 1, 0.3, 1)`（`180ms–240ms`）を使用します。

---

## 7. CSS 移行ルール（SCSS → Tailwind CSS v4）
- 新規コンポーネント作成および既存コンポーネントの改修時は、原則として **Tailwind CSS v4 のユーティリティクラス** を使用し、新しい SCSS ファイルは増やしません。
- 移行期間中は既存の `assets/css/style.css`（SCSS 出力）と `assets/css/tailwind.css`（Tailwind v4 出力）を併用し、各パーツの移行が完了し次第、対応する `.scss` ファイルを順次削除します。
