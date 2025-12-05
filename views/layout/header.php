<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($title ?? 'おうちかふぇ') ?> | コーヒー専門ECサイト</title>
    
    <?php csrfMeta(); ?>
    <?php if (getCurrentUserId()): ?>
    <meta name="user-id" content="<?= getCurrentUserId() ?>">
    <?php endif; ?>
    <meta name="base-url" content="<?= rtrim(APP_URL, '/') ?>">
    
    <!-- OG Tags for Social Sharing -->
    <?php
    $ogTitle = e($title ?? 'おうちかふぇ');
    $ogDescription = e($ogDescription ?? 'こだわりのコーヒー豆を全国へお届けします。あなたの好みに合う一杯を30秒の味覚診断で見つけましょう。');
    $ogImage = $ogImage ?? url('images/og-default.png');
    $ogUrl = $ogUrl ?? url($_SERVER['REQUEST_URI'] ?? '/');
    ?>
    <meta property="og:title" content="<?= $ogTitle ?> | コーヒー専門ECサイト">
    <meta property="og:description" content="<?= $ogDescription ?>">
    <meta property="og:image" content="<?= $ogImage ?>">
    <meta property="og:url" content="<?= $ogUrl ?>">
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="おうちかふぇ">
    
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?= $ogTitle ?> | コーヒー専門ECサイト">
    <meta name="twitter:description" content="<?= $ogDescription ?>">
    <meta name="twitter:image" content="<?= $ogImage ?>">
    
    <meta name="description" content="<?= $ogDescription ?>">
    
    <!-- Tailwind CSS (CDN) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        // 開発環境でのTailwind CDN警告を非表示
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#8B4513',
                        secondary: '#D2691E',
                        accent: '#CD853F',
                    }
                }
            }
        }
    </script>

    <!-- Global Styles -->
    <link rel="stylesheet" href="<?= url('css/global.css') ?>">
    
    <!-- Alpine.js (CDN) -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.0/dist/cdn.min.js"></script>
    
    <!-- Swiper CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    
    <!-- Swiper JS -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    
    <!-- Chart.js (CDN) -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
    
    <!-- Custom Styles -->
    <link rel="stylesheet" href="<?= url('css/home.css') ?>">
    
    <!-- Social Share Script -->
    <script src="<?= url('js/share.js') ?>"></script>
    
    <!-- Loading Overlay (Phase 16) -->
    <script src="<?= url('js/loading.js') ?>"></script>
    <script src="<?= url('js/header-global.js') ?>?v=20251205"></script>
</head>
<body class="bg-gray-50">
    <!-- ヘッダー -->
    <header class="bg-white shadow-md sticky top-0 z-50" x-data="{ mobileMenuOpen: false, searchOpen: false }">
        <div class="container mx-auto px-4">
            <!-- ナビゲーションバー -->
            <nav class="flex items-center justify-between py-4">
                <!-- ロゴ -->
                <div class="flex items-center">
                    <a href="<?= url('/') ?>" class="text-2xl font-bold text-primary">
                        ☕ おうちかふぇ
                    </a>
                </div>
                
                <!-- デスクトップメニュー -->
                <div class="hidden md:flex items-center space-x-6">
                    <a href="<?= url('/') ?>" class="text-gray-700 hover:text-primary transition">ホーム</a>
                    <a href="<?= url('/products') ?>" class="text-gray-700 hover:text-primary transition">商品一覧</a>
                    <a href="<?= url('/subscriptions') ?>" class="text-gray-700 hover:text-primary transition">定期購入</a>
                    <a href="<?= url('/diagnosis') ?>" class="text-gray-700 hover:text-primary transition">味覚診断</a>
                    <a href="<?= url('/sale') ?>" class="text-gray-700 hover:text-primary transition">セール</a>
                    <a href="<?= url('/brew') ?>" class="text-gray-700 hover:text-primary transition">鉢淹ガイド</a>
                    <a href="<?= url('/contact') ?>" class="text-gray-700 hover:text-primary transition">お問い合わせ</a>
                </div>
                
                <!-- ユーザーメニュー -->
                <div class="hidden md:flex items-center space-x-4">
                    <!-- 検索 -->
                    <div class="relative">
                        <button @click="searchOpen = !searchOpen" class="text-gray-700 hover:text-primary transition">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </button>
                        <div x-show="searchOpen" 
                             @click.away="searchOpen = false"
                             x-transition
                             class="absolute right-0 mt-2 w-80 bg-white rounded-lg shadow-lg p-4 z-50">
                            <form action="<?= url('/products') ?>" method="GET" class="flex gap-2">
                                <input type="text" 
                                       name="search" 
                                       placeholder="商品を検索..." 
                                       class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent"
                                       value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
                                <button type="submit" class="bg-amber-600 hover:bg-amber-700 text-white px-4 py-2 rounded-lg transition">
                                    検索
                                </button>
                            </form>
                        </div>
                    </div>
                    
                    <!-- カート -->
                    <a href="<?= url('/cart') ?>" class="relative text-gray-700 hover:text-primary transition" title="カート">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        <span class="absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center leading-none" x-text="$store.cart.count" data-cart-count>0</span>
                    </a>
                    
                    <!-- ウィッシュリスト（Phase 16） -->
                    <?php if (isLoggedIn()): ?>
                    <a href="<?= url('/wishlist') ?>" class="relative text-gray-700 hover:text-pink-500 transition" title="ほしいものリスト">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 20 20">
                            <path d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/>
                        </svg>
                        <span id="wishlist-count-badge" class="absolute -top-2 -right-2 bg-pink-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center leading-none hidden">0</span>
                    </a>
                    <?php endif; ?>
                    
                    <!-- 比較リスト -->
                    <a href="<?= url('/compare') ?>" class="relative text-gray-700 hover:text-primary transition" title="商品比較">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                        <?php 
                        $compareCount = isset($_SESSION['compare_list']) ? count($_SESSION['compare_list']) : 0;
                        if ($compareCount > 0): 
                        ?>
                        <span class="absolute -top-2 -right-2 bg-amber-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center leading-none"><?= $compareCount ?></span>
                        <?php endif; ?>
                    </a>
                    
                    <!-- 閲覧履歴 -->
                    <a href="<?= url('/view-history') ?>" class="relative text-gray-700 hover:text-primary transition" title="閲覧履歴">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span id="view-history-count" class="absolute -top-2 -right-2 bg-purple-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center leading-none hidden">0</span>
                    </a>
                    
                    <?php if (isLoggedIn()): ?>
                        <!-- ログイン中 -->
                        <a href="<?= url('/mypage') ?>" class="text-gray-700 hover:text-primary transition">マイページ</a>
                        <form method="POST" action="<?= url('/logout') ?>" class="inline">
                            <?php csrfField(); ?>
                            <button type="submit" class="btn-outline text-sm">ログアウト</button>
                        </form>
                    <?php else: ?>
                        <!-- 未ログイン -->
                        <a href="<?= url('/login') ?>" class="btn-outline text-sm">ログイン</a>
                        <a href="<?= url('/register') ?>" class="btn-primary text-sm">新規登録</a>
                    <?php endif; ?>
                </div>
                
                <!-- モバイルメニューボタン -->
                <button @click="mobileMenuOpen = !mobileMenuOpen" class="md:hidden text-gray-700">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
            </nav>
            
            <!-- モバイルメニュー -->
            <div x-show="mobileMenuOpen" 
                 x-cloak
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 -translate-y-1"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 translate-y-0"
                 x-transition:leave-end="opacity-0 -translate-y-1"
                 @click.away="mobileMenuOpen = false"
                 class="md:hidden pb-4 bg-white border-t shadow-lg">
                <!-- モバイル検索 -->
                <form action="<?= url('/products') ?>" method="GET" class="mb-4 px-4 pt-4">
                    <div class="flex gap-2">
                        <input type="text" 
                               name="search" 
                               placeholder="商品を検索..." 
                               class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500"
                               value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
                        <button type="submit" class="bg-amber-600 hover:bg-amber-700 text-white px-4 py-2 rounded-lg">
                            検索
                        </button>
                    </div>
                </form>
                
                <a href="<?= url('/') ?>" class="block py-2 text-gray-700 hover:text-primary">ホーム</a>
                <a href="<?= url('/products') ?>" class="block py-2 text-gray-700 hover:text-primary">商品一覧</a>
                <a href="<?= url('/subscriptions') ?>" class="block py-2 text-gray-700 hover:text-primary">定期購入</a>
                <a href="<?= url('/diagnosis') ?>" class="block py-2 text-gray-700 hover:text-primary">味覚診断</a>
                <a href="<?= url('/sale') ?>" class="block py-2 text-gray-700 hover:text-primary">セール</a>
                <a href="<?= url('/brew') ?>" class="block py-2 text-gray-700 hover:text-primary">醸造ガイド</a>
                <a href="<?= url('/contact') ?>" class="block py-2 text-gray-700 hover:text-primary">お問い合わせ</a>
                <a href="<?= url('/cart') ?>" class="block py-2 text-gray-700 hover:text-primary">カート</a>
                <a href="<?= url('/compare') ?>" class="block py-2 text-gray-700 hover:text-primary">
                    商品比較
                    <?php if ($compareCount > 0): ?>
                        <span class="ml-2 bg-amber-500 text-white text-xs px-2 py-1 rounded-full"><?= $compareCount ?></span>
                    <?php endif; ?>
                </a>
                <a href="<?= url('/view-history') ?>" class="block py-2 text-gray-700 hover:text-primary">
                    閲覧履歴
                </a>
                
                <?php if (isLoggedIn()): ?>
                    <a href="<?= url('/mypage') ?>" class="block py-2 text-gray-700 hover:text-primary">マイページ</a>
                    <form method="POST" action="<?= url('/logout') ?>" class="mt-2">
                        <?php csrfField(); ?>
                        <button type="submit" class="btn-outline w-full">ログアウト</button>
                    </form>
                <?php else: ?>
                    <a href="<?= url('/login') ?>" class="block mt-2 btn-outline text-center">ログイン</a>
                    <a href="<?= url('/register') ?>" class="block mt-2 btn-primary text-center">新規登録</a>
                <?php endif; ?>
            </div>
        </div>
    </header>
    
    <!-- フラッシュメッセージ -->
    <?php
    $successMsg = getFlashMessage('success');
    $errorMsg = getFlashMessage('error');
    $infoMsg = getFlashMessage('info');
    $warningMsg = getFlashMessage('warning');
    ?>
    
    <?php if ($successMsg): ?>
    <div class="container mx-auto px-4 mt-4">
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline"><?= e($successMsg) ?></span>
        </div>
    </div>
    <?php endif; ?>
    
    <?php if ($errorMsg): ?>
    <div class="container mx-auto px-4 mt-4">
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline"><?= e($errorMsg) ?></span>
        </div>
    </div>
    <?php endif; ?>
    
    <?php if ($infoMsg): ?>
    <div class="container mx-auto px-4 mt-4">
        <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline"><?= e($infoMsg) ?></span>
        </div>
    </div>
    <?php endif; ?>
    
    <?php if ($warningMsg): ?>
    <div class="container mx-auto px-4 mt-4">
        <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline"><?= e($warningMsg) ?></span>
        </div>
    </div>
    <?php endif; ?>
    
    <!-- 閲覧履歴スクリプト -->
    <script src="<?= url('js/view-history.js') ?>?v=20251118"></script>
    <!-- 商品比較スクリプト -->
    <script src="<?= url('js/compare.js') ?>?v=20251118"></script>
    
    <!-- メインコンテンツ開始 -->
    <main class="min-h-screen">
