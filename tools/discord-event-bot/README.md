# J.O.L イベント告知 Discord Bot

スマホのDiscordから画像とLINEオープンチャットの長文を投稿するだけで、AI（Google Gemini）が自動で要約・タイトル抽出・HTML整形を行い、WordPressの「イベント」投稿へワンタップで公開・下書き登録できるBotです。

---

## 必要な環境
- Node.js (v18以上推奨)
- Discord Bot トークン
- Google Gemini API キー

---

## セットアップ手順

### 1. 依存ライブラリのインストール
ターミナルでこのディレクトリに移動して実行します。
```bash
cd tools/discord-event-bot
npm install
```

### 2. 環境変数の設定
`.env.example` をコピーして `.env` を作成します。
```bash
cp .env.example .env
```

`.env` をエディタで開き、以下を入力します：
```env
DISCORD_BOT_TOKEN=ここにDiscordのBotトークンを貼り付け
GEMINI_API_KEY=ここにGeminiのAPIキーを貼り付け
DISCORD_CHANNEL_ID=（任意: 監視したいチャンネルのID。空欄でも動作します）

# WordPressの接続先
WP_EVENT_API_URL=https://liver-jol.com/wp-json/jol/v1/event
WP_EVENT_API_KEY=jol_event_secret_token_2026
```

※ チャンネルIDの取得方法: Discordの設定で「高度な設定」→「開発者モード」をONにし、チャンネル名を右クリックして「チャンネルIDをコピー」します。

### 3. Botの起動
```bash
npm start
```
ターミナルに `[起動完了] Discord Botにログインしました` と表示されれば準備完了です。

---

## スマホからの使い方
1. スマホのDiscordアプリで `#イベント告知` チャンネルを開きます。
2. Canva等で作成した**告知画像**を添付し、LINEオープンチャットの**案内文**を貼り付けて送信します。
   - ※ タイトルを自作したい場合は、1行目にタイトルを書いておくだけで優先採用されます。
3. 数秒後にBotから確認プレビューと操作ボタンが返信されます。
4. **「本番公開する」** または **「下書き保存する」** をタップすると、WordPressにアイキャッチ画像付きで自動投稿され、記事のURLが届きます。
