# フロントエンド構成ガイド

**最終更新:** 2025年12月5日  
**プロジェクト:** おうちかふぇ - コーヒー・お茶専門ECサイト

---

## 📋 目次

1. [全体構成](#全体構成)
2. [ディレクトリ構造](#ディレクトリ構造)
3. [デザインシステム](#デザインシステム)
4. [画面一覧と遷移図](#画面一覧と遷移図)
5. [レイアウトシステム](#レイアウトシステム)
6. [JavaScriptアーキテクチャ](#javascriptアーキテクチャ)
7. [CSSアーキテクチャ](#cssアーキテクチャ)
8. [コンポーネント設計](#コンポーネント設計)
9. [カスタマイズガイド](#カスタマイズガイド)

---

## 🏗️ 全体構成

### 技術スタック

| 技術 | バージョン | 用途 |
|------|-----------|------|
| **Tailwind CSS** | CDN (latest) | ユーティリティファーストCSSフレームワーク |
| **Alpine.js** | 3.14.0 | 軽量リアクティブJavaScriptフレームワーク |
| **Swiper** | 11.x | スライダー・カルーセル |
| **Chart.js** | 4.4.3 | グラフ・統計可視化 |
| **PHP** | 8.2+ | サーバーサイドレンダリング |

### アーキテクチャパターン

```
MVC + コンポーネントベース設計
├── Model (PHP): データベース操作
├── View (PHP + HTML): テンプレート
├── Controller (PHP): ビジネスロジック
└── JavaScript: クライアントサイドインタラクション
```

### 特徴

- ✅ **完全外部化されたJavaScript** - インラインスクリプトなし
- ✅ **データ駆動型イベント処理** - `data-*` 属性によるイベント委譲
- ✅ **レスポンシブデザイン** - モバイルファースト設計
- ✅ **アクセシビリティ対応** - ARIA属性、セマンティックHTML
- ✅ **SEO最適化** - OGタグ、構造化データ対応

---

## 📁 ディレクトリ構造

```
gin_ec/
├── public/                      # 公開ディレクトリ（ドキュメントルート）
│   ├── css/                     # スタイルシート
│   │   ├── global.css          # グローバルスタイル（全ページ共通）
│   │   ├── home.css            # ホームページ専用
│   │   └── brewing-guide.css   # 淹れ方ガイド専用
│   │
│   ├── js/                      # JavaScriptモジュール
│   │   ├── admin-*.js          # 管理画面用（6ファイル）
│   │   ├── auth-*.js           # 認証用（2ファイル）
│   │   ├── cart*.js            # カート用（2ファイル）
│   │   ├── checkout*.js        # チェックアウト用（2ファイル）
│   │   ├── compare*.js         # 比較機能用（4ファイル）
│   │   ├── diagnosis*.js       # 味覚診断用（2ファイル）
│   │   ├── product*.js         # 商品関連（2ファイル）
│   │   ├── subscription*.js    # 定期購入用（3ファイル）
│   │   └── その他ユーティリティ（10ファイル）
│   │
│   ├── images/                  # 画像アセット
│   │   ├── products/           # 商品画像
│   │   │   ├── coffee/         # コーヒー（各バリアント画像）
│   │   │   ├── tea/            # お茶（各バリアント画像）
│   │   │   ├── gift/           # ギフトセット
│   │   │   ├── goods/          # グッズ
│   │   │   └── Season/         # 季節限定商品
│   │   └── [その他共通画像]
│   │
│   └── index.php               # フロントコントローラー
│
├── views/                       # ビューテンプレート
│   ├── layout/                 # レイアウト
│   │   ├── header.php          # フロント用ヘッダー
│   │   ├── footer.php          # フロント用フッター
│   │   ├── admin-header.php    # 管理画面用ヘッダー
│   │   └── admin-footer.php    # 管理画面用フッター
│   │
│   ├── pages/                  # ページテンプレート
│   │   ├── home.php            # トップページ
│   │   ├── auth/               # 認証（2ファイル）
│   │   ├── products/           # 商品（3ファイル）
│   │   ├── cart/               # カート（1ファイル）
│   │   ├── checkout/           # チェックアウト（3ファイル）
│   │   ├── orders/             # 注文履歴（4ファイル）
│   │   ├── mypage/             # マイページ（6ファイル）
│   │   ├── admin/              # 管理画面（多数）
│   │   ├── diagnosis/          # 味覚診断（2ファイル）
│   │   ├── subscriptions/      # 定期購入（3ファイル）
│   │   ├── wishlist/           # お気に入り（1ファイル）
│   │   ├── compare/            # 商品比較（1ファイル）
│   │   ├── forum/              # フォーラム（3ファイル）
│   │   ├── contact/            # お問い合わせ（1ファイル）
│   │   ├── coupon/             # クーポン（1ファイル）
│   │   └── static/             # 静的ページ（3ファイル）
│   │
│   └── errors/                 # エラーページ
│       ├── 404.php
│       └── 500.php
│
└── src/                         # バックエンドロジック
    ├── controllers/            # コントローラー
    ├── models/                 # モデル
    └── helpers.php             # ヘルパー関数
```

---

## 🎨 デザインシステム

### カラーパレット

#### プライマリカラー（メインテーマ）

```css
primary:       #8B4513  /* サドルブラウン - メインカラー */
primary-dark:  #654321  /* ダークブラウン - ホバー・強調 */
secondary:     #D2691E  /* チョコレート - サブカラー */
accent:        #CD853F  /* ペルー - アクセント */
```

#### セマンティックカラー

```css
success:  #10B981  /* 成功メッセージ */
error:    #EF4444  /* エラー・削除 */
warning:  #F59E0B  /* 警告 */
info:     #3B82F6  /* 情報 */
```

#### ニュートラルカラー

```css
bg-gray-50:   #F9FAFB  /* 背景（最も明るい） */
bg-gray-100:  #F3F4F6  /* カード背景 */
bg-white:     #FFFFFF  /* コンテンツ背景 */
text-gray-900: #111827 /* メインテキスト */
text-gray-700: #374151 /* サブテキスト */
text-gray-500: #6B7280 /* 補助テキスト */
```

### タイポグラフィ

#### フォントファミリー

```css
font-family: 
  'Noto Sans JP',      /* 日本語メイン */
  -apple-system,
  BlinkMacSystemFont,
  'Segoe UI',
  sans-serif;
```

#### フォントサイズ階層

| 用途 | クラス | サイズ | 用例 |
|------|--------|--------|------|
| ヘッドライン | `text-4xl` | 2.25rem (36px) | ページタイトル |
| サブヘッド | `text-3xl` | 1.875rem (30px) | セクションタイトル |
| タイトル | `text-2xl` | 1.5rem (24px) | カードタイトル |
| 大 | `text-xl` | 1.25rem (20px) | 強調テキスト |
| 標準 | `text-base` | 1rem (16px) | 本文 |
| 小 | `text-sm` | 0.875rem (14px) | 補足情報 |
| 極小 | `text-xs` | 0.75rem (12px) | キャプション |

### スペーシングシステム

```
基本単位: 0.25rem (4px)

p-1:  0.25rem (4px)
p-2:  0.5rem  (8px)
p-3:  0.75rem (12px)
p-4:  1rem    (16px)  ← 標準的な内部余白
p-6:  1.5rem  (24px)  ← カードやセクション
p-8:  2rem    (32px)
p-12: 3rem    (48px)
```

### シャドウ・エフェクト

```css
shadow-sm:  小さな影（カード軽め）
shadow-md:  標準的な影（通常のカード）  ← 最も使用
shadow-lg:  大きな影（モーダル、ドロップダウン）
shadow-xl:  特大の影（ポップアップ）
```

### ボーダー半径

```css
rounded:     0.25rem (4px)   /* 小要素 */
rounded-lg:  0.5rem  (8px)   /* カード、ボタン ← 標準 */
rounded-xl:  0.75rem (12px)  /* 大きなカード */
rounded-full: 9999px         /* 円形（アバター等） */
```

---

## 🗺️ 画面一覧と遷移図

### ユーザー向けページ（フロントエンド）

#### 🏠 トップ・商品関連

| URL | ページ名 | 説明 | 主要機能 |
|-----|---------|------|---------|
| `/` | トップページ | ヒーロー、おすすめ商品、カテゴリ別商品表示 | スライダー、カート追加 |
| `/products` | 商品一覧 | フィルター、ソート、ページネーション | カテゴリ/タイプ/シーン別絞り込み |
| `/products/ranking` | ランキング | 人気商品順に表示 | ランキングバッジ |
| `/products/:id` | 商品詳細 | 商品情報、レビュー、関連商品 | バリアント選択、カート追加、レビュー投稿 |
| `/search` | 検索結果 | キーワード検索結果 | 検索キーワードハイライト |

#### 🛒 カート・購入フロー

| URL | ページ名 | 説明 | 主要機能 |
|-----|---------|------|---------|
| `/cart` | カート | カート内商品一覧、小計表示 | 数量変更、削除、クーポン適用 |
| `/checkout` | チェックアウト | 配送先入力、支払い方法選択 | バリデーション、リアルタイム計算 |
| `/checkout/confirm` | 注文確認 | 最終確認画面 | 注文内容確認 |
| `/checkout/success` | 注文完了 | 注文番号表示、メール送信確認 | 注文詳細表示 |

#### 📦 注文・履歴

| URL | ページ名 | 説明 | 主要機能 |
|-----|---------|------|---------|
| `/orders` | 注文履歴 | 過去の注文一覧 | ステータスフィルター、再注文 |
| `/orders/:id` | 注文詳細 | 注文の詳細情報 | トラッキング、キャンセル |
| `/orders/:id/receipt` | 領収書 | 印刷可能な領収書 | PDF出力（将来） |
| `/orders/:id/tracking` | 配送追跡 | 配送状況確認 | タイムライン表示 |
| `/orders/:id/review` | レビュー投稿 | 購入商品のレビュー | 星評価、コメント |

#### 👤 マイページ

| URL | ページ名 | 説明 | 主要機能 |
|-----|---------|------|---------|
| `/mypage` | マイページTOP | ダッシュボード、最近の注文 | クイックアクセス |
| `/mypage/profile` | プロフィール編集 | 個人情報編集 | バリデーション |
| `/mypage/password` | パスワード変更 | パスワード更新 | 現在PW確認 |
| `/mypage/points` | ポイント履歴 | 獲得・使用履歴 | ポイント残高表示 |
| `/mypage/subscriptions` | 定期購入一覧 | 定期購入管理 | 一時停止、解約 |
| `/mypage/subscriptions/:id` | 定期購入詳細 | 詳細情報、配送履歴 | 次回配送日変更 |

#### 🔐 認証

| URL | ページ名 | 説明 | 主要機能 |
|-----|---------|------|---------|
| `/login` | ログイン | メール・パスワード認証 | Google OAuth対応 |
| `/register` | 新規登録 | アカウント作成 | バリデーション |
| `/forgot-password` | パスワード忘れ | リセットメール送信 | メール送信 |
| `/reset-password` | パスワードリセット | 新パスワード設定 | トークン検証 |

#### 🎯 特殊機能

| URL | ページ名 | 説明 | 主要機能 |
|-----|---------|------|---------|
| `/diagnosis` | 味覚診断（従来型） | 8次元ベクトル診断 | API連携、結果保存 |
| `/diagnosis/chat` | 対話式診断 | チャット形式診断 | リアルタイム対話 |
| `/diagnosis/result/:id` | 診断結果 | おすすめ商品表示 | レーダーチャート |
| `/compare` | 商品比較 | 最大3商品比較 | 比較表、グラフ |
| `/wishlist` | お気に入り | お気に入り商品一覧 | カート追加 |
| `/subscriptions` | 定期購入商品 | 定期購入可能商品一覧 | プラン選択 |
| `/subscriptions/select` | プラン選択 | 配送頻度選択 | カスタマイズ |
| `/subscriptions/checkout` | 定期購入申込 | 定期購入確定 | 初回割引 |

#### 📖 コンテンツページ

| URL | ページ名 | 説明 |
|-----|---------|------|
| `/brewing-guide` | 淹れ方ガイド一覧 | 淹れ方の種類一覧 |
| `/brewing-guide/:method` | 淹れ方詳細 | 手順、動画、ポイント |
| `/forum` | フォーラムTOP | 投稿一覧 |
| `/forum/create` | 投稿作成 | 新規投稿 |
| `/forum/:id` | 投稿詳細 | コメント表示 |
| `/contact` | お問い合わせ | フォーム送信 |
| `/guide` | ご利用ガイド | サイトの使い方 |
| `/faq` | よくある質問 | FAQ |
| `/terms` | 利用規約 | 規約全文 |
| `/privacy` | プライバシーポリシー | 個人情報保護 |

---

### 管理者向けページ（バックエンド）

| URL | ページ名 | 説明 | 主要機能 |
|-----|---------|------|---------|
| `/admin` | ダッシュボード | 売上統計、グラフ、最近の注文 | Chart.js、統計カード |
| `/admin/products` | 商品管理 | 商品CRUD | 検索、フィルター、画像管理 |
| `/admin/products/:id/edit` | 商品編集 | 詳細編集 | バリアント管理、画像並び替え |
| `/admin/orders` | 注文管理 | 注文一覧、ステータス変更 | 検索、CSV出力 |
| `/admin/users` | ユーザー管理 | ユーザー一覧、ロール変更 | 強制ログアウト |
| `/admin/coupons` | クーポン管理 | クーポンCRUD | 使用履歴、統計 |
| `/admin/subscriptions` | 定期購入管理 | 定期購入一覧 | ステータス変更 |
| `/admin/security/password-resets` | パスワードリセット管理 | トークン一覧、失効 | セキュリティ管理 |

---

### 画面遷移フロー図

#### 🛒 購入フロー

```
[商品一覧] → [商品詳細] → [カート] → [チェックアウト] → [注文確認] → [完了]
     ↓            ↓          ↓
[フィルター]  [レビュー]  [クーポン適用]
     ↓            ↓          ↓
  [検索]     [関連商品]  [数量変更/削除]
```

#### 👤 会員登録フロー

```
[トップ] → [新規登録] → [メール認証] → [登録完了] → [マイページ]
                ↓
          [バリデーション]
```

#### 📦 定期購入フロー

```
[定期購入商品一覧] → [プラン選択] → [定期購入申込] → [完了] → [マイページ]
                        ↓
                  [配送頻度選択]
                  [初回割引適用]
```

#### 🎯 味覚診断フロー

```
[診断開始] → [質問回答] → [結果表示] → [おすすめ商品] → [カート追加]
                 ↓            ↓
            [8次元評価]  [レーダーチャート]
```

---

## 🧩 レイアウトシステム

### ヘッダー構成（`views/layout/header.php`）

```php
<!DOCTYPE html>
<html lang="ja">
<head>
    <!-- メタタグ -->
    - CSRFトークン
    - ユーザーID（ログイン時）
    - ベースURL
    - OGタグ（SNSシェア用）
    
    <!-- CSS -->
    - Tailwind CSS（CDN）
    - global.css（共通スタイル）
    - home.css（ページ固有）
    
    <!-- JavaScript -->
    - Alpine.js（CDN）
    - Swiper（CDN）
    - Chart.js（CDN）
    - share.js（SNSシェア）
    - loading.js（ローディングオーバーレイ）
    - header-global.js（ヘッダー制御）
</head>
<body>
    <header x-data="{ mobileMenuOpen: false, searchOpen: false }">
        <!-- ナビゲーションバー -->
        <nav>
            <!-- ロゴ -->
            <a href="/">☕ おうちかふぇ</a>
            
            <!-- デスクトップメニュー -->
            <div class="hidden md:flex">
                - ホーム
                - 商品一覧
                - 定期購入
                - 味覚診断
                - セール
                - 淹れ方ガイド
                - お問い合わせ
            </div>
            
            <!-- ユーザーアクション -->
            <div>
                - 検索
                - ウィッシュリスト（ハートアイコン）
                - 比較（天秤アイコン）
                - カート（バッジ付き）
                - マイページ or ログイン
            </div>
            
            <!-- モバイルハンバーガーメニュー -->
            <button @click="mobileMenuOpen = !mobileMenuOpen">
        </nav>
        
        <!-- モバイルメニュー（Alpine.js制御） -->
        <div x-show="mobileMenuOpen">
            <!-- 全メニュー項目 -->
        </div>
    </header>
```

### フッター構成（`views/layout/footer.php`）

```php
<footer class="bg-gray-800 text-white">
    <div class="container mx-auto px-4 py-12">
        <!-- 4カラムレイアウト -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            <!-- 会社情報 -->
            <div>
                <h3>おうちかふぇ</h3>
                <p>こだわりのコーヒー・お茶を...</p>
            </div>
            
            <!-- リンク集 -->
            <div>
                - 商品カテゴリ
                - サービス
                - サポート
            </div>
            
            <!-- SNS -->
            <div>
                - Twitter
                - Instagram
                - Facebook
            </div>
            
            <!-- ニュースレター -->
            <div>
                <form>メール登録</form>
            </div>
        </div>
    </div>
    
    <!-- コピーライト -->
    <div class="border-t">
        <p>© 2024 おうちかふぇ. All rights reserved.</p>
    </div>
</footer>

<!-- ページ固有のJavaScript読み込み -->
<!-- Alpine.js初期化 -->
</body>
</html>
```

### 管理画面ヘッダー（`views/layout/admin-header.php`）

```php
<!DOCTYPE html>
<html lang="ja">
<head>
    <!-- 同様のメタタグ、CSS -->
</head>
<body class="bg-gray-100">
    <!-- サイドバー付きレイアウト -->
    <div class="flex h-screen">
        <!-- サイドバー -->
        <aside class="w-64 bg-gray-900 text-white">
            <div class="p-4">
                <h1>管理画面</h1>
            </div>
            <nav>
                - ダッシュボード
                - 商品管理
                - 注文管理
                - ユーザー管理
                - 定期購入管理
                - クーポン管理
                - セキュリティ管理
            </nav>
        </aside>
        
        <!-- メインコンテンツエリア -->
        <main class="flex-1 overflow-y-auto">
            <header class="bg-white shadow">
                <div class="flex justify-between items-center p-4">
                    <h2>管理ダッシュボード</h2>
                    <div>
                        <span>admin</span>
                        <a href="/admin/logout">ログアウト</a>
                    </div>
                </div>
            </header>
            
            <!-- ページコンテンツ -->
            <div class="p-6">
                <!-- 各ページの内容がここに入る -->
```

---

## ⚙️ JavaScriptアーキテクチャ

### 設計原則

1. **完全外部化** - インラインスクリプト禁止
2. **データ駆動** - `data-*` 属性でイベント設定
3. **モジュール化** - 機能ごとにファイル分割
4. **イベント委譲** - パフォーマンス最適化

### ファイル命名規則

```
[機能名]-[サブ機能].js

例:
- admin-common.js         # 管理画面共通機能
- product-show.js         # 商品詳細ページ
- checkout-confirm.js     # チェックアウト確認
```

### 主要JavaScriptモジュール

#### 📦 共通機能

| ファイル | 機能 | 使用ページ |
|---------|------|-----------|
| `header-global.js` | ヘッダー制御、カート数更新 | 全ページ |
| `loading.js` | ローディングオーバーレイ | 全ページ |
| `share.js` | SNSシェアボタン | 商品詳細、記事 |
| `smooth-scroll-anchors.js` | スムーズスクロール | アンカーリンク使用ページ |

#### 🛒 商品・カート

| ファイル | 機能 | データ属性 |
|---------|------|-----------|
| `products-index.js` | フィルター、ソート | `data-filter`, `data-sort` |
| `product-show.js` | バリアント選択、レビュー | `data-variant-type`, `data-toggle-review` |
| `cart-add-handler.js` | カート追加ボタン | `data-product-id`, `data-variant-id` |
| `cart.js` | カート更新、クーポン | `data-cart-item-id`, `data-confirm-submit` |

#### 💳 チェックアウト

| ファイル | 機能 | データ属性 |
|---------|------|-----------|
| `checkout.js` | 配送先入力、計算 | `data-payment-method` |
| `checkout-confirm.js` | 注文確認、送信 | `data-confirm-order` |

#### 👤 マイページ

| ファイル | 機能 | データ属性 |
|---------|------|-----------|
| `mypage.js` | ダッシュボード | - |
| `mypage-password.js` | パスワード変更 | `data-password-toggle` |
| `orders-index.js` | 注文履歴フィルター | `data-order-status` |

#### 🔧 管理画面

| ファイル | 機能 | データ属性 |
|---------|------|-----------|
| `admin-common.js` | 削除確認ダイアログ | `data-confirm-submit` |
| `admin-dashboard.js` | 統計グラフ描画 | `data-chart-type` |
| `admin-product-images.js` | 画像並び替え、削除 | `data-image-id`, `data-action` |

#### 🎯 特殊機能

| ファイル | 機能 |
|---------|------|
| `diagnosis-chat.js` | チャット式診断 |
| `diagnosis-result.js` | レーダーチャート表示 |
| `compare-page.js` | 商品比較表 |
| `wishlist.js` | お気に入り追加/削除 |
| `subscription-select.js` | 定期購入プラン選択 |

### データ属性パターン

#### 確認ダイアログ

```html
<form data-confirm-submit="本当に削除しますか？">
    <button type="submit">削除</button>
</form>
```

```javascript
// admin-common.js
document.querySelectorAll('[data-confirm-submit]').forEach(form => {
    form.addEventListener('submit', (e) => {
        if (!confirm(form.dataset.confirmSubmit)) {
            e.preventDefault();
        }
    });
});
```

#### カート追加

```html
<button 
    data-product-id="123" 
    data-variant-id="456"
    data-action="add-to-cart"
>
    カートに追加
</button>
```

```javascript
// cart-add-handler.js
document.addEventListener('click', (e) => {
    if (e.target.dataset.action === 'add-to-cart') {
        const productId = e.target.dataset.productId;
        const variantId = e.target.dataset.variantId;
        // カート追加処理
    }
});
```

---

## 🎨 CSSアーキテクチャ

### ファイル構成

#### `public/css/global.css`（全ページ共通）

```css
/* リセット・ベーススタイル */
*, *::before, *::after {
    box-sizing: border-box;
}

body {
    font-family: 'Noto Sans JP', sans-serif;
    line-height: 1.6;
    color: #111827;
}

/* Alpine.js cloaking */
[x-cloak] {
    display: none !important;
}

/* カスタムボタンスタイル */
.btn-primary {
    background-color: #8B4513;
    color: white;
    padding: 0.75rem 1.5rem;
    border-radius: 0.5rem;
    transition: all 0.2s;
}

.btn-primary:hover {
    background-color: #654321;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(139, 69, 19, 0.3);
}

/* カード共通スタイル */
.card {
    background: white;
    border-radius: 0.5rem;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    padding: 1.5rem;
}

/* バッジ */
.badge {
    display: inline-block;
    padding: 0.25rem 0.75rem;
    border-radius: 9999px;
    font-size: 0.75rem;
    font-weight: 600;
}

.badge-primary {
    background-color: #8B4513;
    color: white;
}

.badge-success {
    background-color: #10B981;
    color: white;
}

/* フォーム共通スタイル */
.form-input {
    width: 100%;
    padding: 0.75rem 1rem;
    border: 1px solid #D1D5DB;
    border-radius: 0.5rem;
    transition: border-color 0.2s;
}

.form-input:focus {
    outline: none;
    border-color: #8B4513;
    ring: 2px solid rgba(139, 69, 19, 0.2);
}
```

#### `public/css/home.css`（トップページ専用）

```css
/* ヒーローセクション */
.hero {
    background: linear-gradient(135deg, #8B4513 0%, #D2691E 100%);
    min-height: 60vh;
}

/* 商品カードホバーエフェクト */
.product-card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.product-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
}

/* Swiperカスタマイズ */
.swiper-button-next,
.swiper-button-prev {
    color: #8B4513 !important;
}

.swiper-pagination-bullet-active {
    background-color: #8B4513 !important;
}
```

#### `public/css/brewing-guide.css`（淹れ方ガイド専用）

```css
/* タイムライン */
.timeline-step {
    position: relative;
    padding-left: 3rem;
}

.timeline-step::before {
    content: '';
    position: absolute;
    left: 0.5rem;
    top: 0;
    width: 2px;
    height: 100%;
    background: #D1D5DB;
}

.timeline-number {
    position: absolute;
    left: 0;
    top: 0;
    width: 2rem;
    height: 2rem;
    background: #8B4513;
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
}

/* 動画プレーヤー */
.video-container {
    position: relative;
    padding-bottom: 56.25%; /* 16:9 */
    height: 0;
    overflow: hidden;
}

.video-container iframe {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
}
```

### Tailwindユーティリティクラス活用

#### レスポンシブデザイン

```html
<!-- モバイルファースト -->
<div class="
    grid 
    grid-cols-1 
    sm:grid-cols-2 
    md:grid-cols-3 
    lg:grid-cols-4 
    gap-4
">
    <!-- 商品カード -->
</div>
```

#### コンテナ

```html
<!-- 中央揃え、最大幅制限 -->
<div class="container mx-auto px-4 max-w-7xl">
    <!-- コンテンツ -->
</div>
```

#### Flexbox

```html
<!-- 横並び、中央揃え -->
<div class="flex items-center justify-between">
    <h1>タイトル</h1>
    <button>ボタン</button>
</div>
```

---

## 🧱 コンポーネント設計

### 再利用可能コンポーネント

#### 商品カード

```html
<!-- views/components/product-card.php -->
<div class="product-card bg-white rounded-lg shadow-md overflow-hidden">
    <!-- 商品画像 -->
    <div class="relative">
        <img 
            src="<?= e($product['image_url']) ?>" 
            alt="<?= e($product['name']) ?>"
            class="w-full h-64 object-cover"
        >
        <?php if ($product['sale_price']): ?>
        <span class="badge badge-error absolute top-2 right-2">
            SALE
        </span>
        <?php endif; ?>
    </div>
    
    <!-- 商品情報 -->
    <div class="p-4">
        <h3 class="text-lg font-semibold text-gray-900">
            <?= e($product['name']) ?>
        </h3>
        <p class="text-sm text-gray-600 mt-1">
            <?= e(mb_substr($product['description'], 0, 50)) ?>...
        </p>
        
        <!-- 価格 -->
        <div class="mt-3">
            <?php if ($product['sale_price']): ?>
                <span class="text-gray-400 line-through text-sm">
                    ¥<?= number_format($product['price']) ?>
                </span>
                <span class="text-2xl font-bold text-error ml-2">
                    ¥<?= number_format($product['sale_price']) ?>
                </span>
            <?php else: ?>
                <span class="text-2xl font-bold text-gray-900">
                    ¥<?= number_format($product['price']) ?>
                </span>
            <?php endif; ?>
        </div>
        
        <!-- アクションボタン -->
        <div class="mt-4 flex gap-2">
            <button 
                data-product-id="<?= $product['id'] ?>"
                data-action="add-to-cart"
                class="btn-primary flex-1"
            >
                カートに追加
            </button>
            <button 
                data-product-id="<?= $product['id'] ?>"
                data-action="toggle-wishlist"
                class="p-2 border border-gray-300 rounded-lg hover:bg-gray-50"
            >
                ♡
            </button>
        </div>
    </div>
</div>
```

#### ページネーション

```html
<!-- views/components/pagination.php -->
<div class="flex items-center justify-center gap-2 mt-8">
    <?php if ($page > 1): ?>
        <a href="?page=<?= $page - 1 ?>" 
           class="px-4 py-2 border rounded-lg hover:bg-gray-50">
            前へ
        </a>
    <?php endif; ?>
    
    <?php for ($i = max(1, $page - 2); $i <= min($totalPages, $page + 2); $i++): ?>
        <a href="?page=<?= $i ?>" 
           class="px-4 py-2 border rounded-lg <?= $i === $page ? 'bg-primary text-white' : 'hover:bg-gray-50' ?>">
            <?= $i ?>
        </a>
    <?php endfor; ?>
    
    <?php if ($page < $totalPages): ?>
        <a href="?page=<?= $page + 1 ?>" 
           class="px-4 py-2 border rounded-lg hover:bg-gray-50">
            次へ
        </a>
    <?php endif; ?>
</div>
```

#### フラッシュメッセージ

```html
<!-- views/components/flash-message.php -->
<?php if (isset($_SESSION['success'])): ?>
<div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg mb-6">
    <div class="flex items-center">
        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
            <path d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/>
        </svg>
        <?= e($_SESSION['success']) ?>
    </div>
</div>
<?php unset($_SESSION['success']); endif; ?>

<?php if (isset($_SESSION['error'])): ?>
<div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg mb-6">
    <div class="flex items-center">
        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
            <path d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"/>
        </svg>
        <?= e($_SESSION['error']) ?>
    </div>
</div>
<?php unset($_SESSION['error']); endif; ?>
```

#### ローディングスピナー

```html
<!-- views/components/loading-spinner.php -->
<div class="flex items-center justify-center p-8">
    <div class="animate-spin rounded-full h-12 w-12 border-4 border-primary border-t-transparent">
    </div>
</div>
```

---

## 🔧 カスタマイズガイド

### カラーテーマ変更

#### 1. Tailwind Config（`views/layout/header.php`）

```javascript
tailwind.config = {
    theme: {
        extend: {
            colors: {
                primary: '#YOUR_COLOR',      // メインカラー
                'primary-dark': '#YOUR_DARK', // ホバー時
                secondary: '#YOUR_SECONDARY',
                accent: '#YOUR_ACCENT',
            }
        }
    }
}
```

#### 2. CSS変数（`public/css/global.css`）

```css
:root {
    --color-primary: #8B4513;
    --color-primary-dark: #654321;
    --color-secondary: #D2691E;
}

.btn-primary {
    background-color: var(--color-primary);
}
```

### レイアウト調整

#### コンテナ幅変更

```html
<!-- デフォルト: max-w-7xl (80rem / 1280px) -->
<div class="container mx-auto max-w-7xl">

<!-- より狭く -->
<div class="container mx-auto max-w-5xl">

<!-- より広く -->
<div class="container mx-auto max-w-full">
```

#### グリッドカラム変更

```html
<!-- 商品一覧: 4カラム → 5カラム -->
<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5">
```

### フォント変更

#### Google Fonts追加

```html
<!-- views/layout/header.php -->
<link href="https://fonts.googleapis.com/css2?family=YOUR_FONT&display=swap" rel="stylesheet">

<style>
body {
    font-family: 'YOUR_FONT', 'Noto Sans JP', sans-serif;
}
</style>
```

### 新規ページ追加手順

#### 1. ルート定義（`src/routes.php`）

```php
get('/your-page', 'YourController@index');
```

#### 2. コントローラー作成（`src/controllers/YourController.php`）

```php
<?php
class YourController {
    public function index() {
        view('pages/your-page', [
            'title' => 'Your Page Title',
            'data' => []
        ]);
    }
}
```

#### 3. ビュー作成（`views/pages/your-page.php`）

```php
<?php require_once __DIR__ . '/../layout/header.php'; ?>

<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold"><?= e($title) ?></h1>
    <!-- コンテンツ -->
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
```

#### 4. JavaScript追加（必要な場合）

```javascript
// public/js/your-page.js
(function() {
    'use strict';
    
    console.log('Your page loaded');
    
    // イベントリスナー
    document.addEventListener('DOMContentLoaded', function() {
        // 処理
    });
})();
```

```php
<!-- views/layout/footer.php（ページ固有スクリプト読み込み部分） -->
<?php if (isset($jsFile)): ?>
    <script src="<?= url('js/' . $jsFile) ?>"></script>
<?php endif; ?>
```

---

## 📚 参考資料

- [Tailwind CSS Documentation](https://tailwindcss.com/docs)
- [Alpine.js Documentation](https://alpinejs.dev/)
- [Swiper Documentation](https://swiperjs.com/)
- [Chart.js Documentation](https://www.chartjs.org/)
- [プロジェクト技術スタック詳細](./TECH_STACK.md)
- [テスト仕様書](./TEST_SPECIFICATION.md)

---

**作成日:** 2025年12月5日  
**作成者:** 開発チーム  
**バージョン:** 1.0
