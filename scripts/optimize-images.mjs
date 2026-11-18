import fs from "fs/promises";
import path from "path";
import { fileURLToPath } from "url";
import sharp from "sharp";

const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

const SRC_DIR = path.resolve(__dirname, "../src/assets/images");
const DIST_DIR = path.resolve(__dirname, "../assets/images");
const IMAGE_EXTENSIONS = new Set([".jpg", ".jpeg", ".png", ".gif"]);

async function getFilesRecursively(dir) {
  const entries = await fs.readdir(dir, { withFileTypes: true });
  const files = [];
  for (const entry of entries) {
    if (entry.name.startsWith(".")) continue;
    const fullPath = path.join(dir, entry.name);
    if (entry.isDirectory()) {
      files.push(...(await getFilesRecursively(fullPath)));
    } else if (entry.isFile()) {
      files.push(fullPath);
    }
  }
  return files;
}

async function isNewer(srcFile, destFile) {
  try {
    const [srcStat, destStat] = await Promise.all([
      fs.stat(srcFile),
      fs.stat(destFile),
    ]);
    return srcStat.mtimeMs > destStat.mtimeMs;
  } catch {
    return true;
  }
}

async function processImages() {
  try {
    await fs.access(SRC_DIR);
  } catch {
    console.log("画像ソースディレクトリが存在しないためスキップします:", SRC_DIR);
    return;
  }

  const files = await getFilesRecursively(SRC_DIR);
  let processedCount = 0;

  for (const srcPath of files) {
    const ext = path.extname(srcPath).toLowerCase();
    if (!IMAGE_EXTENSIONS.has(ext)) continue;

    const relPath = path.relative(SRC_DIR, srcPath);
    const destPath = path.join(DIST_DIR, relPath);
    const destDir = path.dirname(destPath);
    const webpPath = destPath.replace(/\.(jpe?g|png|gif)$/i, ".webp");

    await fs.mkdir(destDir, { recursive: true });

    // 元画像の最適化出力
    if (await isNewer(srcPath, destPath)) {
      if (ext === ".jpg" || ext === ".jpeg") {
        await sharp(srcPath).jpeg({ quality: 90, progressive: true }).toFile(destPath);
      } else if (ext === ".png") {
        await sharp(srcPath).png({ compressionLevel: 8 }).toFile(destPath);
      } else {
        await fs.copyFile(srcPath, destPath);
      }
      processedCount++;
    }

    // WebP変換出力
    if (await isNewer(srcPath, webpPath)) {
      await sharp(srcPath).webp({ quality: 90 }).toFile(webpPath);
      processedCount++;
    }
  }

  console.log(`画像最適化・WebP変換完了（更新ファイル数: ${processedCount}）`);
}

processImages().catch((err) => {
  console.error("画像処理中にエラーが発生しました:", err);
  process.exit(1);
});
