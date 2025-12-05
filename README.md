# おうちかふぇ - フロントエンド（Bolt改造用）

**Boltでフロントエンドデザインを改造するための最小構成リポジトリ**

## 📋 概要

このリポジトリは、本番用リポジトリ（[gin_ec](https://github.com/Rascal7-7-7/gin_ec)）からフロントエンド改造に必要な最小限のファイルを抽出したものです。Boltでデザインを改造した後、本番リポジトリにマージして使用します。

---

## 🎯 含まれるもの

### ✅ レイアウト・デザイン
- ✅ ヘッダー/フッターレイアウト（`views/layout/`）
- ✅ グローバルCSS（`public/css/global.css`）
- ✅ トップページ専用CSS（`public/css/home.css`）

### ✅ ページ
- ✅ トップページ（ヒーロー、おすすめ商品、商品カード）
- ✅ 商品一覧・詳細ページ
- ✅ カート・お気に入りページ
- ✅ 静的ページ（会社概要、利用規約、プライバシーポリシー）

### ✅ JavaScript
- ✅ ヘッダー制御（`header-global.js`）
- ✅ 商品表示（`products-index.js`, `product-show.js`）
- ✅ カート機能（`cart-add-handler.js`, `cart.js`）
- ✅ お気に入り（`wishlist.js`）
- ✅ 共通機能（`loading.js`, `share.js`）

### ✅ 画像
- ✅ ロゴ、アイコン、ヒーロー画像
- ✅ 商品画像サンプル（各カテゴリ3枚程度）

---

## ❌ 削除されているもの

- ❌ 管理画面（`views/pages/admin/`, `public/js/admin-*.js`）
- ❌ 味覚診断・フォーラム（`diagnosis/`, `forum/`, `fastapi/`）
- ❌ 定期購入・マイページ（`subscriptions/`, `mypage/`）
- ❌ 認証・チェックアウト（`auth/`, `checkout/`）
- ❌ データベース・バックエンドロジック（`database/`, `vendor/`, `src/models/`）
- ❌ 商品画像（大部分、本番リポジトリに保管）

---

## 🛠️ 技術スタック

### フロントエンド
| 技術 | バージョン | 用途 |
|------|-----------|------|
| **Tailwind CSS** | CDN (3.x) | ユーティリティファーストCSSフレームワーク |
| **Alpine.js** | 3.14.0 | 軽量リアクティブJavaScriptフレームワーム |
| **Swiper** | 11.x | スライダー・カルーセル |
| **Chart.js** | 4.4.3 | グラフ・統計可視化（将来使用） |

### バックエンド（最小構成）
| 技術 | バージョン | 用途 |
|------|-----------|------|
| **PHP** | 8.2+ | サーバーサイドレンダリング |
| **Apache** | 2.4+ | Webサーバー（XAMPP推奨） |

### デザインシステム

#### カラーパレット
```css
primary:       #8B4513  /* サドルブラウン - メインカラー */
primary-dark:  #654321  /* ダークブラウン - ホバー・強調 */
secondary:     #D2691E  /* チョコレート - サブカラー */
accent:        #CD853F  /* ペルー - アクセント */

success:  #10B981  /* 成功メッセージ */
error:    #EF4444  /* エラー・削除 */
warning:  #F59E0B  /* 警告 */
info:     #3B82F6  /* 情報 */
```

#### タイポグラフィ
```css
font-family: 'Noto Sans JP', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;

text-4xl:  2.25rem (36px)  /* ページタイトル */
text-3xl:  1.875rem (30px) /* セクションタイトル */
text-2xl:  1.5rem (24px)   /* カードタイトル */
text-xl:   1.25rem (20px)  /* 強調テキスト */
text-base: 1rem (16px)     /* 本文 */
text-sm:   0.875rem (14px) /* 補足情報 */
text-xs:   0.75rem (12px)  /* キャプション */
```

#### スペーシング
```css
基本単位: 0.25rem (4px)

p-4:  1rem (16px)    /* 標準的な内部余白 */
p-6:  1.5rem (24px)  /* カードやセクション */
p-8:  2rem (32px)    /* 大きなセクション */
```

---

## 📁 ディレクトリ構造

```
gin_ec_front-end/
├── public/                      # 公開ディレクトリ（ドキュメントルート）
│   ├── .htaccess               # URL書き換えルール
│   ├── index.php               # フロントコントローラー
│   │
│   ├── css/
│   │   ├── global.css          # グローバルスタイル（全ページ共通）
│   │   └── home.css            # トップページ専用
│   │
│   ├── js/
│   │   ├── header-global.js    # ヘッダー制御、カート数更新
│   │   ├── loading.js          # ローディングオーバーレイ
│   │   ├── share.js            # SNSシェアボタン
│   │   ├── smooth-scroll-anchors.js
│   │   ├── products-index.js   # 商品一覧（フィルター、ソート）
│   │   ├── product-show.js     # 商品詳細（バリアント選択）
│   │   ├── cart-add-handler.js # カート追加ボタン
│   │   ├── cart.js             # カート更新、クーポン
│   │   └── wishlist.js         # お気に入り追加/削除
│   │
│   └── images/
│       ├── logo.png            # ロゴ
│       ├── icon.png            # アイコン
│       ├── favicon.ico         # ファビコン
│       ├── hero.png            # ヒーロー画像
│       ├── hero-coffee.svg     # ヒーローSVG
│       ├── no-image.png        # 画像なしプレースホルダー
│       └── products/
│           ├── coffee/         # コーヒー画像サンプル（3枚）
│           ├── tea/            # お茶画像サンプル（3枚）
│           └── gift/           # ギフト画像サンプル（3枚）
│
├── views/                       # ビューテンプレート
│   ├── layout/
│   │   ├── header.php          # フロント用ヘッダー
│   │   └── footer.php          # フロント用フッター
│   │
│   └── pages/
│       ├── home.php            # トップページ
│       ├── products/
│       │   ├── index.php       # 商品一覧
│       │   ├── ranking.php     # ランキング
│       │   └── show.php        # 商品詳細
│       ├── cart/
│       │   └── index.php       # カート
│       ├── wishlist/
│       │   └── index.php       # お気に入り
│       └── static/
│           ├── about.php       # 会社概要
│           ├── terms.php       # 利用規約
│           └── privacy.php     # プライバシーポリシー
│
├── src/                         # バックエンドロジック（最小構成）
│   ├── helpers.php             # ヘルパー関数（url, e, view等）
│   ├── routes.php              # ルート定義（簡略版）
│   └── controllers/
│       ├── HomeController.php  # トップページ（サンプルデータ）
│       ├── ProductController.php # 商品表示（サンプルデータ）
│       ├── CartController.php  # カート（セッション利用）
│       └── WishlistController.php # お気に入り（セッション利用）
│
└── docs/
    └── FRONTEND_GUIDE.md       # フロントエンド構成ガイド
```

---

## 🚀 セットアップ

### 前提条件
- PHP 8.2以上
- Apache 2.4以上（XAMPP推奨）
- Git

### 手順

1. **リポジトリクローン**
   ```bash
   git clone https://github.com/Rascal7-7-7/gin_ec_front-end.git
   cd gin_ec_front-end
   ```

2. **Apacheドキュメントルート設定**
   - XAMPPの場合: `C:\xampp\htdocs\gin_ec_front-end\public`
   - または、バーチャルホスト設定:
     ```apache
     <VirtualHost *:80>
         DocumentRoot "C:/xampp/htdocs/gin_ec_front-end/public"
         ServerName gin-ec-front.local
         <Directory "C:/xampp/htdocs/gin_ec_front-end/public">
             AllowOverride All
             Require all granted
         </Directory>
     </VirtualHost>
     ```

3. **動作確認**
   - ブラウザで `http://localhost/` または `http://gin-ec-front.local/` にアクセス
   - トップページが表示されればOK

---

## 💻 開発

### ページ追加方法

1. **ルート追加**（`src/routes.php`）
   ```php
   get('/your-page', 'YourController@index');
   ```

2. **コントローラー作成**（`src/controllers/YourController.php`）
   ```php
   <?php
   class YourController {
       public function index() {
           view('pages/your-page', [
               'title' => 'Your Page Title'
           ]);
       }
   }
   ```

3. **ビュー作成**（`views/pages/your-page.php`）
   ```php
   <?php require_once __DIR__ . '/../layout/header.php'; ?>

   <div class="container mx-auto px-4 py-8">
       <h1 class="text-3xl font-bold"><?= e($title) ?></h1>
   </div>

   <?php require_once __DIR__ . '/../layout/footer.php'; ?>
   ```

### カラーテーマ変更

`views/layout/header.php` 内のTailwind Config:
```javascript
tailwind.config = {
    theme: {
        extend: {
            colors: {
                primary: '#YOUR_COLOR',
                'primary-dark': '#YOUR_DARK',
            }
        }
    }
}
```

---

## 📚 ドキュメント

- [フロントエンド構成ガイド](docs/FRONTEND_GUIDE.md) - 詳細な画面一覧、遷移図、コンポーネント設計

---

## 🔄 本番リポジトリへのマージ手順

Boltで改造完了後、本番リポジトリにマージ:

```bash
# 本番リポジトリをクローン
git clone https://github.com/Rascal7-7-7/gin_ec.git
cd gin_ec

# フロントエンド改造ファイルを上書き
cp -r ../gin_ec_front-end/views/layout/* views/layout/
cp -r ../gin_ec_front-end/public/css/* public/css/
cp -r ../gin_ec_front-end/views/pages/home.php views/pages/

# コミット・プッシュ
git add .
git commit -m "フロントエンドデザイン更新（Bolt改造）"
git push origin main
```

---

## 🐛 既知の制限事項

- **データベース接続なし** - サンプルデータのみ使用
- **認証機能なし** - ログイン・会員登録は本番リポジトリで実装
- **決済機能なし** - チェックアウトは本番リポジトリで実装
- **商品画像少数** - デザイン確認用サンプルのみ

---

## 📝 ライセンス

本番リポジトリと同様

---

## 👥 開発チーム

- **Rascal7-7-7** - リポジトリオーナー

---

**最終更新:** 2025年12月5日
