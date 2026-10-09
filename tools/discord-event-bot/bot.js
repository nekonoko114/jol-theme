require('dotenv').config();
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

// 環境変数の確認
const DISCORD_BOT_TOKEN = process.env.DISCORD_BOT_TOKEN;
const GEMINI_API_KEY = process.env.GEMINI_API_KEY;
const DISCORD_CHANNEL_ID = process.env.DISCORD_CHANNEL_ID;
const WP_EVENT_API_URL = process.env.WP_EVENT_API_URL;
const WP_EVENT_API_KEY = process.env.WP_EVENT_API_KEY || 'jol_event_secret_token_2026';

if (!DISCORD_BOT_TOKEN || !GEMINI_API_KEY || !WP_EVENT_API_URL) {
  console.error('[設定エラー] .envファイルに必要な情報が不足しています。');
  console.error('DISCORD_BOT_TOKEN, GEMINI_API_KEY, WP_EVENT_API_URL を確認してください。');
  process.exit(1);
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

// 確認待ちの記事データを一時保存するマップ（key: customId用のユニークID）
const pendingEvents = new Map();

client.once('ready', () => {
  console.log(`[起動完了] Discord Botにログインしました: ${client.user.tag}`);
  console.log(`[監視中] チャンネルID: ${DISCORD_CHANNEL_ID ? DISCORD_CHANNEL_ID : 'すべてのチャンネル'}`);
  console.log(`[連携先] WordPress API: ${WP_EVENT_API_URL}`);
});

/**
 * Gemini APIを使ってメッセージをイベント告知用に整形・解析する
 */
async function processEventWithGemini(rawText) {
  const model = genAI.getGenerativeModel({
    model: 'gemini-1.5-flash',
    generationConfig: {
      responseMimeType: 'application/json',
    },
  });

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
  "summary": "100文字程度の簡単な要約テキスト（Discordプレビュー表示用）",
  "content_html": "<p>整形されたHTML本文...</p>"
}

【入力テキスト】
${rawText}
`;

  const result = await model.generateContent(prompt);
  const responseText = result.response.text();
  return JSON.parse(responseText);
}

/**
 * メッセージ受信イベント
 */
client.on('messageCreate', async (message) => {
  // Bot自身のメッセージは無視
  if (message.author.bot) return;

  // 特定チャンネル指定がある場合、それ以外のチャンネルは無視
  if (DISCORD_CHANNEL_ID && message.channel.id !== DISCORD_CHANNEL_ID) return;

  // テキストまたは添付画像があるか確認
  const content = message.content.trim();
  const attachment = message.attachments.find((att) =>
    att.contentType && att.contentType.startsWith('image/')
  );

  if (!content && !attachment) {
    return;
  }

  // テキストがない場合は案内
  if (!content) {
    await message.reply('画像を受け取りました。イベント告知の文章（LINEオプチャの案内文など）も一緒に送信してください。');
    return;
  }

  const statusMsg = await message.reply('AIがイベント告知の内容を解析・整形しています。少々お待ちください...');

  try {
    const parsedData = await processEventWithGemini(content);
    const imageUrl = attachment ? attachment.url : null;

    // 一時IDを生成してデータを保持（30分間有効）
    const eventId = `evt_${Date.now()}_${Math.random().toString(36).substring(2, 7)}`;
    pendingEvents.set(eventId, {
      title: parsedData.title,
      category: parsedData.category,
      content_html: parsedData.content_html,
      imageUrl: imageUrl,
      authorId: message.author.id,
      timestamp: Date.now(),
    });

    // 30分後に自動消去
    setTimeout(() => {
      pendingEvents.delete(eventId);
    }, 30 * 60 * 1000);

    // カテゴリーの日本語表記
    const categoryLabels = {
      'event-news': 'イベント (event-news)',
      'battle': 'ガチバトル (battle)',
      'liver-news': 'ライバーニュース (liver-news)',
    };

    // プレビュー用Embedを作成
    const previewEmbed = new EmbedBuilder()
      .setColor(0x5865F2)
      .setTitle(`【確認プレビュー】${parsedData.title}`)
      .setDescription(parsedData.summary || '（要約なし）')
      .addFields(
        { name: 'カテゴリー', value: categoryLabels[parsedData.category] || parsedData.category, inline: true },
        { name: 'アイキャッチ画像', value: imageUrl ? '添付画像あり' : 'なし', inline: true }
      );

    if (imageUrl) {
      previewEmbed.setImage(imageUrl);
    }

    previewEmbed.setFooter({
      text: '内容を確認し、下のボタンで「本番公開」または「下書き保存」を選択してください。',
    });

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
  } catch (err) {
    console.error('[Gemini解析エラー]', err);
    await statusMsg.edit(`解析中にエラーが発生しました: ${err.message}`);
  }
});

/**
 * ボタン操作（Interaction）イベント
 */
client.on('interactionCreate', async (interaction) => {
  if (!interaction.isButton()) return;

  const [action, eventId] = interaction.customId.split('_');
  if (!eventId || !pendingEvents.has(eventId)) {
    await interaction.reply({
      content: 'この操作は有効期限が切れているか、すでに処理されています。',
      ephemeral: true,
    });
    return;
  }

  const eventData = pendingEvents.get(eventId);

  if (action === 'cancel') {
    pendingEvents.delete(eventId);
    await interaction.update({
      content: 'イベント作成をキャンセルしました。',
      embeds: [],
      components: [],
    });
    return;
  }

  const status = action === 'publish' ? 'publish' : 'draft';
  const statusLabel = status === 'publish' ? '本番公開' : '下書き保存';

  await interaction.deferUpdate();

  try {
    // WordPress API に送信
    const response = await axios.post(
      WP_EVENT_API_URL,
      {
        title: eventData.title,
        content: eventData.content_html,
        category: eventData.category,
        status: status,
        image_url: eventData.imageUrl,
      },
      {
        headers: {
          'Content-Type': 'application/json',
          'X-JOL-API-KEY': WP_EVENT_API_KEY,
        },
        timeout: 60000, // 画像ダウンロードを考慮して60秒
      }
    );

    // 成功したらキャッシュから削除
    pendingEvents.delete(eventId);

    const result = response.data;
    const postUrl = result.url || WP_EVENT_API_URL.replace('/wp-json/jol/v1/event', '');

    const resultEmbed = new EmbedBuilder()
      .setColor(status === 'publish' ? 0x57F287 : 0xFEE75C)
      .setTitle(`[完了] イベントを${statusLabel}しました`)
      .addFields(
        { name: 'タイトル', value: result.title || eventData.title },
        { name: 'ステータス', value: statusLabel, inline: true },
        { name: 'アイキャッチ画像', value: result.image_attached ? '登録完了' : 'なし', inline: true },
        { name: '記事リンク', value: `[記事を表示する](${postUrl})` }
      );

    await interaction.editReply({
      content: `WordPressへの登録が完了しました。`,
      embeds: [resultEmbed],
      components: [],
    });
  } catch (err) {
    console.error('[WordPress投稿エラー]', err.response ? err.response.data : err.message);
    const errorDetail = err.response && err.response.data && err.response.data.message
      ? err.response.data.message
      : err.message;

    await interaction.followUp({
      content: `WordPressへの送信中にエラーが発生しました: ${errorDetail}`,
      ephemeral: true,
    });
  }
});

// Botログイン
client.login(DISCORD_BOT_TOKEN);
