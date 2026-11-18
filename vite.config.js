import { defineConfig } from "vite";
import tailwindcss from "@tailwindcss/vite";
import autoprefixer from "autoprefixer";
import path from "path";
import { fileURLToPath } from "url";

const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

export default defineConfig(({ mode }) => ({
  base: "./",
  publicDir: false,
  plugins: [tailwindcss()],
  css: {
    devSourcemap: true,
    postcss: {
      plugins: [autoprefixer()],
    },
  },
  build: {
    outDir: path.resolve(__dirname, "assets"),
    emptyOutDir: false,
    sourcemap: mode === "development",
    minify: mode === "production" ? "esbuild" : false,
    rollupOptions: {
      input: {
        app: path.resolve(__dirname, "src/assets/js/app.js"),
        ranking: path.resolve(__dirname, "src/assets/js/ranking.js"),
        "particles-config": path.resolve(
          __dirname,
          "src/assets/js/particles-config.js"
        ),
        tailwind: path.resolve(__dirname, "src/assets/css/tailwind.css"),
      },
      // CSS内の相対画像パス（../images/...）はassets/images/をそのまま参照させる
      external: [/^(\.\.\/)+images\//],
      output: {
        entryFileNames: "js/[name].js",
        chunkFileNames: "js/chunks/[name].js",
        assetFileNames: (assetInfo) => {
          if (assetInfo.name && assetInfo.name.endsWith(".css")) {
            return "css/[name][extname]";
          }
          if (
            assetInfo.name &&
            /\.(png|jpe?g|gif|webp|svg)$/i.test(assetInfo.name)
          ) {
            return "images/logo/[name][extname]";
          }
          return "[ext]/[name][extname]";
        },
      },
    },
  },
}));
