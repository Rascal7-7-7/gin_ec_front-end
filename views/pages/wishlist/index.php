<?php
/**
 * ウィッシュリスト（ほしいものリスト）ページ
 * Phase 16: UX改善機能
 */

require_once __DIR__ . '/../../layout/header.php';
?>

<div class="container mx-auto px-4 py-8">
    <!-- ページヘッダー -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2 flex items-center">
                    <svg class="w-8 h-8 mr-3 text-pink-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"/>
                    </svg>
                    ほしいものリスト
                </h1>
                <p class="text-gray-600">お気に入りの商品を保存して、後でゆっくりチェックできます</p>
            </div>
            <div class="text-right">
                <div class="text-sm text-gray-500">登録件数</div>
                <div class="text-3xl font-bold text-pink-500" id="wishlist-count">-</div>
            </div>
        </div>
    </div>

    <!-- ローディング表示 -->
    <div id="loading" class="text-center py-16">
        <div class="inline-block animate-spin rounded-full h-12 w-12 border-4 border-pink-500 border-t-transparent"></div>
        <p class="mt-4 text-gray-600">読み込み中...</p>
    </div>

    <!-- 空の状態 -->
    <div id="empty-state" class="hidden text-center py-16">
        <svg class="w-24 h-24 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
        </svg>
        <h3 class="text-xl font-semibold text-gray-700 mb-2">ほしいものリストは空です</h3>
        <p class="text-gray-500 mb-6">気になる商品をハートマークで追加しましょう</p>
        <a href="<?= url('/products') ?>" class="inline-flex items-center px-6 py-3 bg-pink-500 text-white rounded-lg hover:bg-pink-600 transition-colors">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            商品を探す
        </a>
    </div>

    <!-- ウィッシュリストグリッド -->
    <div id="wishlist-grid" class="hidden grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        <!-- 商品カードが動的に追加される -->
    </div>
</div>

<script>
window.wishlistPageConfig = {
    productDetailBaseUrl: '<?= rtrim(url('/products'), '/') ?>/',
    fallbackImageUrl: '<?= url('images/no-image.png') ?>',
    api: {
        list: '<?= url('/api/wishlist') ?>',
        remove: '<?= url('/api/wishlist/remove') ?>',
        cartAdd: '<?= url('/api/cart/add') ?>'
    },
    csrfToken: '<?= $_SESSION['csrf_token'] ?? '' ?>',
    categoryNames: {
        coffee: 'コーヒー',
        tea_black: '紅茶',
        tea_green: '緑茶',
        tea_oolong: '烏龍茶',
        tea_herbal: 'ハーブティー'
    }
};
</script>
<script src="<?= url('js/wishlist.js') ?>"></script>

<?php require_once __DIR__ . '/../../layout/footer.php'; ?>
