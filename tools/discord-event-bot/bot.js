require('dotenv').config();
const fs = require('fs');
const path = require('path');
const {
  Client,
  GatewayIntentBits,
  ActionRowBuilder,
  ButtonBuilder,
  ButtonStyle,
  EmbedBuilder,
} = require('discord.js');
const { GoogleGenerativeAI } = require('@google/generative-ai');
const axios = require('axios');

// 環境変数の確認（前後の余分な空白を除去）
const DISCORD_BOT_TOKEN = (process.env.DISCORD_BOT_TOKEN || '').trim();
const GEMINI_API_KEY = (process.env.GEMINI_API_KEY || '').trim();
const DISCORD_CHANNEL_ID = (process.env.DISCORD_CHANNEL_ID || '').trim();
const WP_EVENT_API_URL = (process.env.WP_EVENT_API_URL || '').trim();
const WP_EVENT_API_KEY = (process.env.WP_EVENT_API_KEY || 'jol_event_secret_token_2026').trim();

if (!DISCORD_BOT_TOKEN || !GEMINI_API_KEY || !WP_EVENT_API_URL) {
  console.error('[設定エラー] .envファイルに必要な情報が不足しています。');
  console.error('DISCORD_BOT_TOKEN, GEMINI_API_KEY, WP_EVENT_API_URL を確認してください。');
  process.exit(1);
}

// カテゴリー定義
const CATEGORY_LABELS = {
  'event-news': 'イベント (event-news)',
  'battle': 'ガチバトル (battle)',
  'liver-news': 'ライバーニュース (liver-news)',
};
const VALID_CATEGORIES = Object.keys(CATEGORY_LABELS);

// 再起動しても消えないよう一時データをファイルに永続化するパス
const CACHE_FILE_PATH = path.join(__dirname, '.pending-events.json');

// 処理中ロック管理（メモリ）
const processingLocks = new Set();

/**
 * 永続化キャッシュファイルから全データを読み込む
 */
function loadCacheMap() {
  try {
    if (!fs.existsSync(CACHE_FILE_PATH)) {
      return {};
    }
    const raw = fs.readFileSync(CACHE_FILE_PATH, 'utf8');
    const data = JSON.parse(raw);
    return data && typeof data === 'object' ? data : {};
  } catch (err) {
    console.warn('[キャッシュ読込警告]', err.message);
    return {};
  }
}

/**
 * 永続化キャッシュファイルへ保存する（24時間以上前の古いデータは自動整理）
 */
function saveCacheMap(cacheObj) {
  try {
    const now = Date.now();
    const ttl = 24 * 60 * 60 * 1000; // 24時間保持
    const cleaned = {};
    for (const [key, val] of Object.entries(cacheObj)) {
      if (val && typeof val === 'object' && (!val.timestamp || now - val.timestamp < ttl)) {
        cleaned[key] = val;
      }
    }
    fs.writeFileSync(CACHE_FILE_PATH, JSON.stringify(cleaned, null, 2), 'utf8');
  } catch (err) {
    console.warn('[キャッシュ保存警告]', err.message);
  }
}

function getPendingEvent(eventId) {
  const cache = loadCacheMap();
  return cache[eventId] || null;
}

function setPendingEvent(eventId, eventData) {
  const cache = loadCacheMap();
  cache[eventId] = eventData;
  saveCacheMap(cache);
}

function deletePendingEvent(eventId) {
  const cache = loadCacheMap();
  if (cache[eventId]) {
    delete cache[eventId];
    saveCacheMap(cache);
  }
}

// Discordクライアント初期化
const client = new Client({
  intents: [
    GatewayIntentBits.Guilds,
    GatewayIntentBits.GuildMessages,
    GatewayIntentBits.MessageContent,
  ],
});

// Geminiクライアント初期化
const genAI = new GoogleGenerativeAI(GEMINI_API_KEY);

/**
 * Discord Embed の文字数制限および空文字エラーを防ぐヘルパー
 */
function safeText(value, maxLength = 1024, fallback = '（未設定）') {
  if (value === null || value === undefined) return fallback;
  const trimmed = String(value).trim();
  if (!trimmed) return fallback;
  if (trimmed.length > maxLength) {
    return trimmed.substring(0, maxLength - 3) + '...';
  }
  return trimmed;
}

/**
 * 添付ファイルが画像かどうかを安全に判定するヘルパー
 */
function isImageAttachment(attachment) {
  if (!attachment) return false;
  if (attachment.contentType && attachment.contentType.startsWith('image/')) {
    return true;
  }
  const nameOrUrl = (attachment.name || attachment.url || '').toLowerCase().split('?')[0];
  return /\.(jpe?g|png|webp|gif)$/i.test(nameOrUrl);
}

/**
 * Geminiのレスポンス文字列から安全にJSONオブジェクトを抽出・正規化する
 */
function parseGeminiJsonResponse(rawResponseText) {
  if (!rawResponseText || typeof rawResponseText !== 'string') {
    throw new Error('AIからの応答が空でした。');
  }

  // Markdownのコードブロック記法（```json ... ```）が含まれている場合は除去
  const cleaned = rawResponseText
    .replace(/^```(?:json)?\s*/i, '')
    .replace(/\s*```$/i, '')
    .trim();

  let parsed = JSON.parse(cleaned);

  // 配列で返ってきた場合は先頭要素を取得
  if (Array.isArray(parsed)) {
    parsed = parsed[0] || {};
  }

  const title = typeof parsed.title === 'string' && parsed.title.trim()
    ? parsed.title.trim()
    : 'イベント告知';

  const rawCategory = typeof parsed.category === 'string' ? parsed.category.trim() : '';
  const category = VALID_CATEGORIES.includes(rawCategory) ? rawCategory : 'event-news';

  const summary = typeof parsed.summary === 'string' && parsed.summary.trim()
    ? parsed.summary.trim()
    : `${title}のお知らせです。`;

  const contentHtml = typeof parsed.content_html === 'string' && parsed.content_html.trim()
    ? parsed.content_html.trim()
    : `<p>${summary}</p>`;

  return {
    title,
    category,
    summary,
    content_html: contentHtml,
  };
}

client.once('clientReady', () => {
  console.log(`[起動完了] Discord Botにログインしました: ${client.user.tag} (PID: ${process.pid})`);
  console.log(`[監視中] チャンネルID: ${DISCORD_CHANNEL_ID ? DISCORD_CHANNEL_ID : 'すべてのチャンネル'}`);
  console.log(`[連携先] WordPress API: ${WP_EVENT_API_URL}`);
});

/**
 * Gemini APIを使ってメッセージをイベント告知用に整形・解析する
 */
async function processEventWithGemini(rawText) {
  const prompt = `
あなたはライバー事務所「J.O.L」の広報アシスタントです。
マネージャーがLINEオープンチャット等で投稿した以下の案内文を解析し、WordPressのイベント告知記事用データ（JSON形式）を生成してください。

【厳格な指示（タイトルのルール）】
1. ユーザーが1行目に指定したタイトル（または「タイトル: ○○」）がある場合は、それを100%タイトルとして最優先採用してください。
2. 1行目にタイトル指定がない場合は、案内文の中に書かれているイベント名・大会名・企画名の「正式名称」を一言一句変えずにそのまま抽出してください。
3. 誇張したキャッチコピーや余計な修飾語（例: 「大注目！」「見逃せない！」等）を勝手に創作してタイトルに足すことは絶対に禁止します。

【本文整形の指示（content_html）】
1. LINEの雑多な長文から必要な情報を抽出し、Webサイトのイベント詳細として読みやすく整理してください。
2. 以下の構成で見出し（<h3>）や箇条書き（<ul><li>）を使い、綺麗なHTMLタグで出力してください。
   - 概要・告知メッセージ（<p>）
   - <h3>開催期間</h3> または <h3>開催日時</h3>
   - <h3>参加条件・対象</h3>
   - <h3>イベント詳細・ルール</h3>
   - <h3>注意事項</h3>（該当情報がある場合のみ）
3. 絵文字は一切含めず、自然な日本語テキストのみで構成してください。

【カテゴリー判定の指示（category）】
以下の3つのスラッグのいずれかを判定してください。
- "battle": ガチバトル、事務所内バトル、対決企画など
- "event-news": 一般的なイベント、オフ会、周年企画、コラボ企画など
- "liver-news": ライバーのニュース、お知らせ、デビュー告知など

【出力JSONフォーマット】
{
  "title": "正式なイベントタイトル",
  "category": "event-news または battle または liver-news",
  "summary": "100文字程度の簡単な要約テキスト（Discordプレビュー表示および一覧抜粋用）",
  "content_html": "<p>整形されたHTML本文...</p>"
}

【入力テキスト】
${rawText}
`;

  const modelCandidates = ['gemini-2.5-flash', 'gemini-2.0-flash', 'gemini-1.5-flash'];
  let lastError = null;

  for (const modelName of modelCandidates) {
    try {
      const model = genAI.getGenerativeModel({
        model: modelName,
        generationConfig: {
          responseMimeType: 'application/json',
        },
      });
      const result = await model.generateContent(prompt);
      const responseText = result.response.text();
      return parseGeminiJsonResponse(responseText);
    } catch (err) {
      lastError = err;
      // 404 (モデル廃止・未対応) の場合は次のモデル候補へフォールバック
      if (err.status === 404 || (err.message && err.message.includes('404'))) {
        continue;
      }
      throw err;
    }
  }

  throw lastError;
}

/**
 * キャッシュにデータがない場合（再起動前に作られたボタン等）、
 * プレビューメッセージと返信元のユーザー投稿からイベントデータを自動復元する
 */
async function recoverEventDataFromInteraction(interaction) {
  const botMsg = interaction.message;
  const embed = botMsg && botMsg.embeds && botMsg.embeds[0] ? botMsg.embeds[0] : null;

  // 1. 返信元のユーザーメッセージを取得して再解析を試みる
  if (botMsg && botMsg.reference && botMsg.reference.messageId && interaction.channel) {
    try {
      const originalMsg = await interaction.channel.messages.fetch(botMsg.reference.messageId);
      if (originalMsg) {
        const rawContent = (originalMsg.content || '').trim();
        const imageAttachments = originalMsg.attachments.filter(isImageAttachment);
        const imageUrls = imageAttachments.map((att) => att.url);

        if (rawContent) {
          console.log('[自動復元] 返信元のメッセージからAI解析データを再生成しています...');
          const parsedData = await processEventWithGemini(rawContent);
          return {
            title: parsedData.title,
            category: parsedData.category,
            summary: parsedData.summary,
            content_html: parsedData.content_html,
            imageUrls: imageUrls,
            authorId: originalMsg.author ? originalMsg.author.id : interaction.user.id,
            timestamp: Date.now(),
          };
        }
      }
    } catch (fetchErr) {
      console.warn('[自動復元警告] 返信元メッセージの取得に失敗しました:', fetchErr.message);
    }
  }

  // 2. 返信元が取得できない場合はプレビューEmbedの表示内容から復元する
  if (embed) {
    console.log('[自動復元] プレビューEmbedからイベントデータを復元しています...');
    const title = (embed.title || '').replace(/^【確認プレビュー】\s*/, '').trim() || 'イベント告知';
    const summary = (embed.description || '').trim();

    let category = 'event-news';
    if (Array.isArray(embed.fields)) {
      const catField = embed.fields.find((f) => f.name === 'カテゴリー');
      if (catField && catField.value) {
        for (const slug of VALID_CATEGORIES) {
          if (catField.value.includes(slug)) {
            category = slug;
            break;
          }
        }
      }
    }

    const imageUrls = embed.image && embed.image.url ? [embed.image.url] : [];
    const escapedSummary = summary
      .split('\n')
      .map((line) => line.trim())
      .filter(Boolean)
      .map((line) => `<p>${line}</p>`)
      .join('\n');

    return {
      title,
      category,
      summary,
      content_html: escapedSummary || `<p>${title}</p>`,
      imageUrls,
      authorId: interaction.user.id,
      timestamp: Date.now(),
    };
  }

  return null;
}

/**
 * メッセージ受信イベント
 */
client.on('messageCreate', async (message) => {
  try {
    // Bot自身のメッセージやシステムメッセージは無視
    if (message.author.bot || message.system) return;

    // 特定チャンネル指定がある場合、それ以外のチャンネルは無視
    if (DISCORD_CHANNEL_ID && message.channel.id !== DISCORD_CHANNEL_ID) return;

    // テキストまたは添付画像があるか確認
    const content = (message.content || '').trim();
    const imageAttachments = message.attachments.filter(isImageAttachment);
    const imageUrls = imageAttachments.map((att) => att.url);

    if (!content && imageUrls.length === 0) {
      return;
    }

    // テキストがない場合は案内
    if (!content) {
      await message.reply(
        `画像（${imageUrls.length}枚）を受け取りました。イベント告知の文章（LINEオープンチャットの案内文など）も同じメッセージに添えて送信してください。`
      );
      return;
    }

    console.log(`[メッセージ受信] 投稿者: ${message.author.tag}, 画像: ${imageUrls.length}枚`);
    const statusMsg = await message.reply('AIがイベント告知の内容を解析・整形しています。少々お待ちください...');

    try {
      const parsedData = await processEventWithGemini(content);

      // 一時IDを生成してファイルに永続化保存（再起動しても消えない）
      const eventId = `evt_${Date.now()}_${Math.random().toString(36).substring(2, 7)}`;
      setPendingEvent(eventId, {
        title: parsedData.title,
        category: parsedData.category,
        summary: parsedData.summary,
        content_html: parsedData.content_html,
        imageUrls: imageUrls,
        authorId: message.author.id,
        timestamp: Date.now(),
      });

      const imageInfoText = imageUrls.length > 0
        ? `${imageUrls.length}枚（1枚目を一覧アイキャッチ、全画像をページ上部ギャラリーに設定）`
        : 'なし';

      // プレビュー用Embedを作成
      const previewEmbed = new EmbedBuilder()
        .setColor(0x5865F2)
        .setTitle(safeText(`【確認プレビュー】${parsedData.title}`, 256, '【確認プレビュー】イベント告知'))
        .setDescription(safeText(parsedData.summary, 4096, '（要約なし）'))
        .addFields(
          {
            name: 'カテゴリー',
            value: safeText(CATEGORY_LABELS[parsedData.category] || parsedData.category, 1024),
            inline: true,
          },
          {
            name: '添付画像',
            value: safeText(imageInfoText, 1024),
            inline: true,
          }
        )
        .setFooter({
          text: '内容を確認し、下のボタンで「本番公開」または「下書き保存」を選択してください。',
        });

      if (imageUrls.length > 0) {
        previewEmbed.setImage(imageUrls[0]);
      }

      // 操作ボタンを作成
      const buttons = new ActionRowBuilder().addComponents(
        new ButtonBuilder()
          .setCustomId(`publish_${eventId}`)
          .setLabel('本番公開する')
          .setStyle(ButtonStyle.Success),
        new ButtonBuilder()
          .setCustomId(`draft_${eventId}`)
          .setLabel('下書き保存する')
          .setStyle(ButtonStyle.Primary),
        new ButtonBuilder()
          .setCustomId(`cancel_${eventId}`)
          .setLabel('キャンセル')
          .setStyle(ButtonStyle.Secondary)
      );

      await statusMsg.edit({
        content: 'イベント告知の生成が完了しました。以下の内容でよろしいですか？',
        embeds: [previewEmbed],
        components: [buttons],
      });
      console.log(`[プレビュー完了] eventId: ${eventId}, title: ${parsedData.title}`);
    } catch (err) {
      console.error('[Gemini解析エラー]', err);
      await statusMsg.edit(`解析中にエラーが発生しました: ${safeText(err.message, 500, '不明なエラー')}`);
    }
  } catch (outerErr) {
    console.error('[メッセージ処理エラー]', outerErr);
  }
});

/**
 * ボタン操作（Interaction）イベント
 */
client.on('interactionCreate', async (interaction) => {
  if (!interaction.isButton()) return;

  try {
    const firstUnderscoreIndex = interaction.customId.indexOf('_');
    if (firstUnderscoreIndex === -1) return;

    const action = interaction.customId.substring(0, firstUnderscoreIndex);
    const eventId = interaction.customId.substring(firstUnderscoreIndex + 1);

    console.log(`[ボタン操作受信] action: ${action}, eventId: ${eventId}`);

    if (action === 'cancel') {
      deletePendingEvent(eventId);
      processingLocks.delete(eventId);
      await interaction.update({
        content: 'イベント作成をキャンセルしました。',
        embeds: [],
        components: [],
      });
      return;
    }

    if (action !== 'publish' && action !== 'draft') {
      return;
    }

    // 二重クリック防止ロック確認
    if (processingLocks.has(eventId)) {
      await interaction.reply({
        content: '現在WordPressへの登録処理を実行中です。完了までそのままお待ちください。',
        ephemeral: true,
      });
      return;
    }

    const status = action === 'publish' ? 'publish' : 'draft';
    const statusLabel = status === 'publish' ? '本番公開' : '下書き保存';

    // ロックを有効化し、即座にDiscordへ応答してボタンを非表示化
    processingLocks.add(eventId);
    await interaction.update({
      content: `WordPressへ「${statusLabel}」として送信・画像登録しています。少々お待ちください...`,
      components: [],
    });

    // キャッシュからデータ取得。再起動前のボタン等でキャッシュにない場合はメッセージから自動復元
    let eventData = getPendingEvent(eventId);
    if (!eventData) {
      eventData = await recoverEventDataFromInteraction(interaction);
      if (eventData) {
        setPendingEvent(eventId, eventData);
      }
    }

    if (!eventData) {
      processingLocks.delete(eventId);
      await interaction.editReply({
        content: '元データの取得に失敗しました。お手数ですが、もう一度イベント告知文と画像を送信してください。',
        embeds: [],
        components: [],
      });
      return;
    }

    try {
      // WordPress API に送信
      const response = await axios.post(
        WP_EVENT_API_URL,
        {
          title: eventData.title,
          content: eventData.content_html,
          excerpt: eventData.summary,
          category: eventData.category,
          status: status,
          image_urls: eventData.imageUrls,
        },
        {
          headers: {
            'Content-Type': 'application/json',
            'X-JOL-API-KEY': WP_EVENT_API_KEY,
          },
          timeout: 90000, // 複数画像ダウンロードを考慮して90秒
        }
      );

      // 成功したらキャッシュとロックを削除
      deletePendingEvent(eventId);
      processingLocks.delete(eventId);

      const result = response.data || {};
      const postUrl = result.url || WP_EVENT_API_URL.replace('/wp-json/jol/v1/event', '');
      const imgCount = result.image_count !== undefined
        ? result.image_count
        : (eventData.imageUrls ? eventData.imageUrls.length : 0);

      const resultEmbed = new EmbedBuilder()
        .setColor(status === 'publish' ? 0x57F287 : 0xFEE75C)
        .setTitle(safeText(`[完了] イベントを${statusLabel}しました`, 256))
        .addFields(
          { name: 'タイトル', value: safeText(result.title || eventData.title, 1024) },
          { name: 'ステータス', value: safeText(statusLabel, 1024), inline: true },
          { name: 'カテゴリー', value: safeText(CATEGORY_LABELS[eventData.category] || eventData.category, 1024), inline: true },
          { name: '登録画像', value: safeText(`${imgCount}枚（上部ギャラリー＆アイキャッチ）`, 1024), inline: true },
          { name: '確認用リンク', value: safeText(`[記事ページを開く](${postUrl})`, 1024) }
        );

      await interaction.editReply({
        content: 'WordPressへのイベント登録が完了しました。',
        embeds: [resultEmbed],
        components: [],
      });
      console.log(`[WordPress登録完了] post_id: ${result.post_id}, url: ${postUrl}`);
    } catch (err) {
      // エラー時は再試行できるようロックを解除しボタンを復元
      processingLocks.delete(eventId);

      const statusCode = err.response ? err.response.status : null;
      const responseData = err.response ? err.response.data : null;
      console.error('[WordPress投稿エラー]', statusCode, responseData || err.message);

      let errorDetail = err.message;
      if (responseData && typeof responseData === 'object' && responseData.message) {
        errorDetail = `${responseData.message} (code: ${responseData.code || statusCode})`;
      } else if (statusCode === 404) {
        errorDetail = 'WordPress側にイベント登録API (/wp-json/jol/v1/event) が見つかりません。テーマファイルが本番サーバーにデプロイされているか確認してください。';
      } else if (statusCode === 401 || statusCode === 403) {
        errorDetail = 'WordPress APIの認証エラーまたはアクセス拒否が発生しました。APIキーやサーバーのWAF設定を確認してください。';
      }

      const retryButtons = new ActionRowBuilder().addComponents(
        new ButtonBuilder()
          .setCustomId(`publish_${eventId}`)
          .setLabel('本番公開する（再試行）')
          .setStyle(ButtonStyle.Success),
          new ButtonBuilder()
          .setCustomId(`draft_${eventId}`)
          .setLabel('下書き保存する（再試行）')
          .setStyle(ButtonStyle.Primary),
        new ButtonBuilder()
          .setCustomId(`cancel_${eventId}`)
          .setLabel('キャンセル')
          .setStyle(ButtonStyle.Secondary)
      );

      await interaction.editReply({
        content: `WordPressへの送信中にエラーが発生しました: ${safeText(errorDetail, 500)}\n下のボタンから再試行できます。`,
        components: [retryButtons],
      });
    }
  } catch (outerErr) {
    console.error('[インタラクション処理エラー]', outerErr);
  }
});

// 未処理エラーによるプロセス強制終了を防止
process.on('unhandledRejection', (reason) => {
  console.error('[Unhandled Rejection]', reason);
});

// Botログイン
client.login(DISCORD_BOT_TOKEN);
