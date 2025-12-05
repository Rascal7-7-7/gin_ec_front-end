<?php require_once __DIR__ . '/../../layout/header.php'; ?>

<div class="min-h-screen bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
        <!-- パンくずリスト -->
        <nav class="flex mb-8" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="<?= url('/') ?>" class="text-gray-700 hover:text-amber-600">
                        ホーム
                    </a>
                </li>
                <li>
                    <span class="mx-2 text-gray-400">/</span>
                </li>
                <li class="text-gray-500">ショッピングカート</li>
            </ol>
        </nav>

        <h1 class="text-3xl font-bold text-gray-900 mb-8">ショッピングカート</h1>

        <?php if (empty($cart['items'])): ?>
            <!-- 空のカート -->
            <div class="bg-white rounded-lg shadow-md p-12 text-center">
                <svg class="w-24 h-24 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
                <h3 class="text-xl font-semibold text-gray-700 mb-2">カートは空です</h3>
                <p class="text-gray-500 mb-6">まだ商品が追加されていません</p>
                <a 
                    href="<?= url('/products') ?>" 
                    class="inline-block bg-gradient-to-r from-amber-600 to-orange-600 text-white font-bold py-3 px-8 rounded-lg hover:from-amber-700 hover:to-orange-700 transition transform hover:scale-105"
                >
                    商品を探す
                </a>
            </div>
        <?php else: ?>
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- カート内容 -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-lg shadow-md overflow-hidden">
                        <!-- カートヘッダー -->
                        <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                            <div class="flex justify-between items-center">
                                <h2 class="text-lg font-semibold text-gray-800">
                                    商品（<?= count($cart['items']) ?>点）
                                </h2>
                                <form method="POST" action="<?= url('/cart/clear') ?>" data-confirm-submit="カートを空にしてもよろしいですか？">
                                    <?= csrfField() ?>
                                    <button type="submit" class="text-sm text-red-600 hover:text-red-700 transition">
                                        カートを空にする
                                    </button>
                                </form>
                            </div>
                        </div>

                        <!-- カート商品リスト -->
                        <div class="divide-y divide-gray-200">
                            <?php foreach ($cart['items'] as $item): ?>
                                <div class="p-6 hover:bg-gray-50 transition">
                                    <div class="flex gap-4">
                                        <!-- 商品画像 -->
                                        <a href="<?= url('/products/' . $item['product_id']) ?>" class="flex-shrink-0">
                                            <div class="w-24 h-24 bg-gray-200 rounded-lg overflow-hidden">
                                                <?php if (!empty($item['image_url'])): ?>
                                                    <img 
                                                        src="<?= e($item['image_url']) ?>" 
                                                        alt="<?= e($item['name']) ?>"
                                                        class="w-full h-full object-cover"
                                                    >
                                                <?php else: ?>
                                                    <div class="w-full h-full flex items-center justify-center text-gray-400">
                                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                        </svg>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </a>

                                        <!-- 商品情報 -->
                                        <div class="flex-1">
                                            <a href="<?= url('/products/' . $item['product_id']) ?>" class="hover:text-amber-600 transition">
                                                <h3 class="font-semibold text-gray-900 mb-1">
                                                    <?= e($item['name']) ?>
                                                </h3>
                                            </a>
                                            <p class="text-sm text-gray-600 mb-2">
                                                単価: ¥<?= number_format($item['price']) ?>
                                            </p>

                                            <!-- 数量調整 -->
                                            <div class="flex items-center gap-3" x-data="{ quantity: <?= $item['quantity'] ?> }">
                                                <form method="POST" action="<?= url('/cart/update/' . $item['product_id']) ?>" class="flex items-center gap-2">
                                                    <?= csrfField() ?>
                                                    
                                                    <button 
                                                        type="button"
                                                        @click="quantity = Math.max(1, quantity - 1); $nextTick(() => $el.closest('form').submit())"
                                                        class="w-8 h-8 rounded border border-gray-300 hover:bg-gray-50 transition"
                                                    >
                                                        <svg class="w-4 h-4 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                                                        </svg>
                                                    </button>

                                                    <input 
                                                        type="number" 
                                                        name="quantity"
                                                        x-model="quantity"
                                                        min="1"
                                                        max="<?= $item['stock'] ?? 99 ?>"
                                                        class="w-16 px-2 py-1 text-center border border-gray-300 rounded focus:ring-2 focus:ring-amber-500"
                                                        @change="$el.closest('form').submit()"
                                                    >

                                                    <button 
                                                        type="button"
                                                        @click="quantity = Math.min(<?= $item['stock'] ?? 99 ?>, quantity + 1); $nextTick(() => $el.closest('form').submit())"
                                                        class="w-8 h-8 rounded border border-gray-300 hover:bg-gray-50 transition"
                                                    >
                                                        <svg class="w-4 h-4 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                                        </svg>
                                                    </button>
                                                </form>

                                                <!-- 削除ボタン -->
                                                <form method="POST" action="<?= url('/cart/remove/' . $item['product_id']) ?>" class="ml-4">
                                                    <?= csrfField() ?>
                                                    <button 
                                                        type="submit" 
                                                        class="text-red-600 hover:text-red-700 transition text-sm"
                                                        data-cart-remove
                                                        data-confirm-message="この商品をカートから削除しますか？"
                                                    >
                                                        削除
                                                    </button>
                                                </form>
                                            </div>
                                        </div>

                                        <!-- 小計 -->
                                        <div class="text-right">
                                            <p class="text-lg font-bold text-gray-900">
                                                ¥<?= number_format($item['item_total']) ?>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- 買い物を続けるボタン -->
                    <div class="mt-6">
                        <a 
                            href="<?= url('/products') ?>" 
                            class="inline-flex items-center text-amber-600 hover:text-amber-700 transition font-semibold"
                        >
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            買い物を続ける
                        </a>
                    </div>
                </div>

                <!-- 注文サマリー -->
                <div class="lg:col-span-1">
                    <div 
                        class="bg-white rounded-lg shadow-md p-6 sticky top-24"
                        x-data="{ showLoginPrompt: false }"
                    >
                        <h2 class="text-lg font-semibold text-gray-800 mb-4">注文サマリー</h2>

                        <div class="space-y-3 mb-6">
                            <div class="flex justify-between text-gray-600">
                                <span>小計</span>
                                <span>¥<?= number_format($cart['subtotal']) ?></span>
                            </div>
                            <div class="flex justify-between text-gray-600">
                                <span>送料</span>
                                <span><?= $cart['shipping_fee'] > 0 ? '¥' . number_format($cart['shipping_fee']) : '無料' ?></span>
                            </div>
                            <?php if (isset($_SESSION['applied_coupon'])): ?>
                                <div class="flex justify-between text-green-600 font-semibold">
                                    <span>クーポン割引</span>
                                    <span>-¥<?= number_format($_SESSION['applied_coupon']['discount_amount']) ?></span>
                                </div>
                            <?php endif; ?>
                            <?php if (isset($cart['discount']) && $cart['discount'] > 0): ?>
                                <div class="flex justify-between text-green-600">
                                    <span>割引</span>
                                    <span>-¥<?= number_format($cart['discount']) ?></span>
                                </div>
                            <?php endif; ?>
                            <div class="border-t border-gray-200 pt-3">
                                <div class="flex justify-between text-lg font-bold text-gray-900">
                                    <span>合計</span>
                                    <span>¥<?= number_format($cart['total']) ?></span>
                                </div>
                            </div>
                        </div>

                        <!-- クーポンコード -->
                        <div class="mb-6">
                            <div class="space-y-2">
                                <input 
                                    type="text" 
                                    id="cart-coupon-code"
                                    placeholder="クーポンコード"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500"
                                >
                                <button 
                                    type="button"
                                    data-cart-action="apply-coupon"
                                    class="w-full border border-amber-600 text-amber-600 font-semibold py-2 rounded-lg hover:bg-amber-50 transition"
                                >
                                    適用
                                </button>
                                <div id="cart-coupon-message" class="hidden text-sm"></div>
                            </div>
                        </div>

                        <!-- レジに進むボタン -->
                        <?php if (isLoggedIn()): ?>
                            <a 
                                href="<?= url('/checkout') ?>" 
                                class="block w-full bg-gradient-to-r from-amber-600 to-orange-600 text-white font-bold py-4 px-6 rounded-lg hover:from-amber-700 hover:to-orange-700 transition transform hover:scale-105 text-center shadow-lg"
                            >
                                レジに進む
                            </a>
                        <?php else: ?>
                            <a 
                                href="<?= url('/checkout') ?>" 
                                @click.prevent="showLoginPrompt = true" 
                                class="block w-full bg-gradient-to-r from-amber-600 to-orange-600 text-white font-bold py-4 px-6 rounded-lg hover:from-amber-700 hover:to-orange-700 transition transform hover:scale-105 text-center shadow-lg"
                            >
                                レジに進む
                            </a>
                        <?php endif; ?>

                        <!-- 配送情報 -->
                        <div class="mt-6 p-4 bg-gray-50 rounded-lg">
                            <h3 class="font-semibold text-gray-800 mb-2 text-sm">配送について</h3>
                            <ul class="space-y-1 text-xs text-gray-600">
                                <li class="flex items-start">
                                    <svg class="w-4 h-4 text-green-500 mr-2 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                    </svg>
                                    5,000円以上で送料無料
                                </li>
                                <li class="flex items-start">
                                    <svg class="w-4 h-4 text-green-500 mr-2 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                    </svg>
                                    午前中注文で翌日配送
                                </li>
                                <li class="flex items-start">
                                    <svg class="w-4 h-4 text-green-500 mr-2 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                    </svg>
                                    時間指定可能
                                </li>
                            </ul>
                        </div>

                        <!-- ログイン選択モーダル -->
                        <div 
                            x-cloak
                            x-show="showLoginPrompt"
                            class="fixed inset-0 z-50 flex items-center justify-center px-4"
                            x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0"
                            x-transition:enter-end="opacity-100"
                            x-transition:leave="transition ease-in duration-150"
                            x-transition:leave-start="opacity-100"
                            x-transition:leave-end="opacity-0"
                        >
                            <div class="absolute inset-0 bg-gray-900/60" @click="showLoginPrompt = false"></div>
                            <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-md p-6 space-y-4 transform transition-all">
                                <div class="flex items-start justify-between">
                                    <div>
                                        <p class="text-sm text-amber-600 font-semibold mb-1">チェックアウト前に確認</p>
                                        <h3 class="text-xl font-bold text-gray-900">購入方法を選択してください</h3>
                                        <p class="text-sm text-gray-600 mt-1">ログインせずにゲスト購入もご利用いただけます。</p>
                                    </div>
                                    <button @click="showLoginPrompt = false" class="text-gray-400 hover:text-gray-600">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </button>
                                </div>
                                <div class="space-y-3">
                                    <a 
                                        href="<?= url('/checkout') ?>" 
                                        class="block w-full text-center bg-amber-600 hover:bg-amber-700 text-white font-semibold py-3 px-4 rounded-lg transition"
                                    >
                                        ゲスト購入で進む
                                    </a>
                                    <a 
                                        href="<?= url('/login?redirect=/checkout') ?>" 
                                        class="block w-full text-center border border-amber-600 text-amber-700 font-semibold py-3 px-4 rounded-lg hover:bg-amber-50 transition"
                                    >
                                        ログインして進む
                                    </a>
                                    <a 
                                        href="<?= url('/register?redirect=/checkout') ?>" 
                                        class="block w-full text-center border border-gray-300 text-gray-800 font-semibold py-3 px-4 rounded-lg hover:bg-gray-50 transition"
                                    >
                                        新規登録して進む
                                    </a>
                                </div>
                                <p class="text-xs text-gray-500 text-center">登録済みの方はログインすると配送先の入力がスムーズです。</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
window.cartPageConfig = {
    applyUrl: '<?= url('/coupon/apply') ?>',
    cartTotal: <?= (int)($cart['total'] ?? 0) ?>,
    inputId: 'cart-coupon-code',
    messageId: 'cart-coupon-message'
};
</script>
<script src="<?= url('js/cart.js') ?>?v=20251205"></script>

<?php require_once __DIR__ . '/../../layout/footer.php'; ?>
