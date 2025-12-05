<?php 
// Set OG tags for social sharing
$ogTitle = $product['name'];
$ogDescription = mb_substr(strip_tags($product['description'] ?? ''), 0, 150) . '...';
$ogImage = !empty($product['image_url']) ? $product['image_url'] : url('images/og-default.png');
$ogUrl = url('/products/' . $product['id']);
?>
<?php require_once __DIR__ . '/../../layout/header.php'; ?>

<?php
$initialVariant = $initialVariant ?? null;
$initialVariantId = $initialVariantId ?? null;
// 商品名の重複を防ぐため、基本名のみを使用
$baseName = function_exists('getProductBaseName') ? getProductBaseName($product['name']) : $product['name'];
$initialDisplayName = !empty($initialVariant['type_display'])
    ? sprintf('%s（%s）', $baseName, $initialVariant['type_display'])
    : $baseName;
$hasProductDiscount = !empty($product['discount_price']) && $product['discount_price'] < $product['price'];
$initialPrice = $initialVariant['price'] ?? ($hasProductDiscount ? (int) $product['discount_price'] : (int) $product['price']);
$initialComparePrice = $hasProductDiscount ? (int) $product['price'] : null;
$initialDiscountRate = ($initialComparePrice && $initialComparePrice > 0 && $initialComparePrice > $initialPrice)
    ? (int) round((($initialComparePrice - $initialPrice) / $initialComparePrice) * 100)
    : null;
$initialStock = $initialVariant['stock'] ?? $product['stock'];

$imagePayload = array_map(static function (array $image): array {
    return [
        'image_path' => $image['image_path'],
        'thumbnail_path' => $image['thumbnail_path'] ?? $image['image_path'],
    ];
}, $productImages ?? []);

if (empty($imagePayload) && !empty($product['image_url'])) {
    $imagePayload[] = [
        'image_path' => $product['image_url'],
        'thumbnail_path' => $product['image_url'],
    ];
}

$initialMainImage = $initialVariant['image_url'] ?? ($imagePayload[0]['image_path'] ?? ($product['image_url'] ?? null));
$initialThumbImage = $imagePayload[0]['thumbnail_path'] ?? $initialMainImage;

$lightboxImages = [];
if (!empty($productImages) && count($productImages) > 0) {
    $lightboxImages = array_map(static function ($img) {
        return $img['image_path'];
    }, $productImages);
} elseif (!empty($product['image_url'])) {
    $lightboxImages = [$product['image_url']];
}

$variantComponentConfig = [
    'variants' => $variants,
    'productName' => $product['name'],
    'productPrice' => (int) $product['price'],
    'productDiscountPrice' => $hasProductDiscount ? (int) $product['discount_price'] : null,
    'productStock' => (int) $product['stock'],
    'productId' => (int) $product['id'],
    'initialVariantId' => $initialVariantId,
    'initialDisplayName' => $initialDisplayName,
    'initialMainImage' => $initialMainImage,
    'initialThumbImage' => $initialThumbImage,
    'images' => $imagePayload,
    'fallbackImage' => $product['image_url'] ?? null,
];

$canPurchase = $product['stock'] > 0;
if (!$canPurchase && !empty($variants)) {
    foreach ($variants as $variantStockCheck) {
        if (($variantStockCheck['stock'] ?? 0) > 0) {
            $canPurchase = true;
            break;
        }
    }
}
?>

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
                <li>
                    <a href="<?= e($productListUrl ?? url('/products')) ?>" class="text-gray-700 hover:text-amber-600">
                        商品一覧
                    </a>
                </li>
                <li>
                    <span class="mx-2 text-gray-400">/</span>
                </li>
                <li class="text-gray-500"><?= e($product['name']) ?></li>
            </ol>
        </nav>

        <div 
            class="bg-white rounded-lg shadow-lg overflow-hidden"
            x-data="window.productPage.variantSelector(window.productPageConfig.variantSelector)"
        >
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 p-8">
                <!-- 商品画像ギャラリー -->
                <div class="space-y-4">
                    <?php if (!empty($productImages) && count($productImages) > 0): ?>
                        <!-- Swiperメインスライダー -->
                        <div class="swiper productMainSwiper h-[500px] bg-gray-200 rounded-lg overflow-hidden">
                            <div class="swiper-wrapper">
                                <?php foreach ($productImages as $index => $image): ?>
                                    <div class="swiper-slide flex items-center justify-center" data-lightbox-index="<?= $index ?>">
                                        <img 
                                            src="<?= e($image['image_path']) ?>" 
                                            alt="<?= e($product['name']) ?>"
                                            class="max-w-full max-h-full object-contain"
                                            <?php if ($index === 0): ?>
                                                x-bind:src="activeMainImage || '<?= e($image['image_path']) ?>'"
                                                x-bind:alt="activeDisplayName"
                                            <?php endif; ?>
                                        >
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <!-- ナビゲーションボタン -->
                            <div class="swiper-button-next"></div>
                            <div class="swiper-button-prev"></div>
                            <!-- ページネーション -->
                            <div class="swiper-pagination"></div>
                        </div>

                        <!-- サムネイルスライダー -->
                        <?php if (count($productImages) > 1): ?>
                            <div class="swiper productThumbSwiper">
                                <div class="swiper-wrapper">
                                    <?php foreach ($productImages as $index => $image): ?>
                                        <div class="swiper-slide cursor-pointer">
                                            <div class="aspect-w-1 aspect-h-1 bg-gray-200 rounded-lg overflow-hidden">
                                                <img 
                                                    src="<?= e($image['thumbnail_path'] ?? $image['image_path']) ?>" 
                                                    alt="<?= e($product['name']) ?>"
                                                    class="w-full h-full object-cover"
                                                    <?php if ($index === 0): ?>
                                                        x-bind:src="activeThumbImage || '<?= e($image['thumbnail_path'] ?? $image['image_path']) ?>'"
                                                        x-bind:alt="activeDisplayName"
                                                    <?php endif; ?>
                                                >
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    <?php elseif (!empty($product['image_url'])): ?>
                        <!-- 既存の単一画像 -->
                        <div class="h-[500px] bg-gray-200 rounded-lg overflow-hidden flex items-center justify-center" data-lightbox-index="0">
                            <img 
                                src="<?= e($product['image_url']) ?>" 
                                alt="<?= e($product['name']) ?>"
                                class="max-w-full max-h-full object-contain"
                                x-bind:src="activeMainImage || '<?= e($product['image_url']) ?>'"
                                x-bind:alt="activeDisplayName"
                            >
                        </div>
                    <?php else: ?>
                        <!-- 画像なし -->
                        <div class="h-[500px] bg-gray-200 rounded-lg overflow-hidden">
                            <div class="w-full h-full flex items-center justify-center text-gray-400">
                                <svg class="w-32 h-32" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- 商品情報 -->
                <div class="flex flex-col justify-between">
                    <div>
                        <!-- カテゴリー -->
                        <?php if (!empty($product['category'])): ?>
                            <span class="inline-block bg-amber-100 text-amber-800 text-xs px-3 py-1 rounded-full mb-4">
                                <?= e($product['category']) ?>
                            </span>
                        <?php endif; ?>

                        <!-- 商品名 -->
                        <h1 
                            class="text-3xl font-bold text-gray-900 mb-4"
                            x-text="activeDisplayName"
                        >
                            <?= e($initialDisplayName) ?>
                        </h1>

                        <!-- 価格 -->
                        <div class="mb-6">
                            <div 
                                class="mb-2"
                                x-show="activePricing.hasDiscount"
                                style="display: <?= $initialComparePrice ? 'block' : 'none' ?>;"
                            >
                                <span class="bg-red-600 text-white font-bold px-3 py-1 rounded-full text-sm"
                                    x-text="activePricing.badgeText"
                                >
                                    <?= $initialDiscountRate ? ($initialDiscountRate . '% OFF') : '' ?>
                                </span>
                            </div>
                            <div 
                                class="flex items-baseline gap-3"
                                x-show="activePricing.hasDiscount"
                                style="display: <?= $initialComparePrice ? 'flex' : 'none' ?>;"
                            >
                                <span class="text-4xl font-bold text-red-600" x-text="activePricing.priceFormatted">
                                    ¥<?= number_format($initialPrice) ?>
                                </span>
                                <span class="text-2xl text-gray-400 line-through" x-text="activePricing.compareFormatted">
                                    <?= $initialComparePrice ? '¥' . number_format($initialComparePrice) : '' ?>
                                </span>
                                <span class="text-gray-500 ml-2">(税込)</span>
                            </div>
                            <div 
                                x-show="!activePricing.hasDiscount"
                                style="display: <?= $initialComparePrice ? 'none' : 'block' ?>;"
                            >
                                <span class="text-4xl font-bold text-amber-600" x-text="activePricing.priceFormatted">
                                    ¥<?= number_format($initialPrice) ?>
                                </span>
                                <span class="text-gray-500 ml-2">(税込)</span>
                            </div>
                        </div>

                        <!-- 在庫状況 -->
                        <div class="mb-6">
                            <span 
                                class="inline-flex items-center text-green-600 font-semibold"
                                x-show="activeStock > 0"
                                style="display: <?= $initialStock > 0 ? 'inline-flex' : 'none' ?>;"
                            >
                                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                <span x-text="activeStockLabel">在庫あり（残り<?= $initialStock ?>個）</span>
                            </span>
                            <span 
                                class="inline-flex items-center text-red-600 font-semibold"
                                x-show="activeStock <= 0"
                                style="display: <?= $initialStock > 0 ? 'none' : 'inline-flex' ?>;"
                            >
                                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                </svg>
                                在庫切れ
                            </span>
                        </div>

                        <!-- 商品説明 -->
                        <div class="mb-8">
                            <h2 class="text-lg font-semibold text-gray-900 mb-3">商品説明</h2>
                            <p class="text-gray-700 leading-relaxed whitespace-pre-line">
                                <?= e($product['description'] ?? '商品説明がありません。') ?>
                            </p>
                        </div>

                        <!-- 保存方法・賞味期限 -->
                        <?php if (!empty($product['storage_method']) || !empty($product['expiration_period'])): ?>
                            <div class="mb-8 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg p-6 border border-blue-200">
                                <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                    <svg class="w-6 h-6 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    保存方法・賞味期限
                                </h2>
                                
                                <?php if (!empty($product['storage_method'])): ?>
                                    <div class="mb-4">
                                        <h3 class="text-sm font-semibold text-gray-700 mb-2 flex items-center">
                                            <svg class="w-5 h-5 mr-1 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                            </svg>
                                            保存方法
                                        </h3>
                                        <p class="text-gray-700 leading-relaxed pl-6">
                                            <?= nl2br(e($product['storage_method'])) ?>
                                        </p>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if (!empty($product['expiration_period'])): ?>
                                    <div>
                                        <h3 class="text-sm font-semibold text-gray-700 mb-2 flex items-center">
                                            <svg class="w-5 h-5 mr-1 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                            賞味期限
                                        </h3>
                                        <p class="text-gray-700 leading-relaxed pl-6">
                                            <?= nl2br(e($product['expiration_period'])) ?>
                                        </p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>

                        <!-- おすすめシーン -->
                        <?php if (!empty($product['recommended_scenes'])): ?>
                            <?php
                            $scenes = json_decode($product['recommended_scenes'], true);
                            $sceneLabels = [
                                'morning' => ['☀️', '朝食'],
                                'breakfast' => ['🍳', '朝食'],
                                'work' => ['💼', '仕事中'],
                                'study' => ['📚', '勉強中'],
                                'afternoon' => ['☕', '午後'],
                                'teatime' => ['🫖', 'ティータイム'],
                                'relax' => ['🌙', 'リラックス'],
                                'evening' => ['🌆', '夕方・夜'],
                                'dessert' => ['🍰', 'デザート'],
                                'bedtime' => ['😴', '就寝前'],
                                'japanese_meal' => ['🍱', '和食']
                            ];
                            ?>
                            <div class="mb-8">
                                <h2 class="text-lg font-semibold text-gray-900 mb-3 flex items-center">
                                    <svg class="w-6 h-6 mr-2 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    おすすめのシーン
                                </h2>
                                <div class="flex flex-wrap gap-2">
                                    <?php foreach ($scenes as $scene): ?>
                                        <?php if (isset($sceneLabels[$scene])): ?>
                                            <a 
                                                href="<?= url('/products?scene=' . urlencode($scene)) ?>"
                                                class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-amber-50 to-orange-50 border border-amber-200 text-amber-800 rounded-full hover:from-amber-100 hover:to-orange-100 transition-all shadow-sm hover:shadow-md"
                                            >
                                                <span class="text-xl"><?= $sceneLabels[$scene][0] ?></span>
                                                <span class="font-semibold"><?= $sceneLabels[$scene][1] ?></span>
                                            </a>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endif; ?>

                        <!-- SNSシェアボタン -->
                        <div class="mb-8 pb-8 border-b border-gray-200">
                            <h3 class="text-sm font-semibold text-gray-700 mb-3">この商品をシェア</h3>
                            <div class="flex flex-wrap gap-3">
                                <button 
                                    data-share-action="x"
                                    data-share-url="<?= e(url('/products/' . $product['id'])) ?>"
                                    data-share-text="<?= e($product['name'] . ' - おうちかふぇ') ?>"
                                    class="flex items-center gap-2 px-4 py-2 bg-black hover:bg-gray-800 text-white rounded-lg transition"
                                    title="Xでシェア">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
                                    </svg>
                                    X
                                </button>
                                
                                <button 
                                    data-share-action="facebook"
                                    data-share-url="<?= e(url('/products/' . $product['id'])) ?>"
                                    class="flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition"
                                    title="Facebookでシェア">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                                    </svg>
                                    Facebook
                                </button>
                                
                                <button 
                                    data-share-action="line"
                                    data-share-url="<?= e(url('/products/' . $product['id'])) ?>"
                                    data-share-text="<?= e($product['name']) ?>"
                                    class="flex items-center gap-2 px-4 py-2 bg-green-500 hover:bg-green-600 text-white rounded-lg transition"
                                    title="LINEでシェア">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M19.365 9.863c.349 0 .63.285.63.631 0 .345-.281.63-.63.63H17.61v1.125h1.755c.349 0 .63.283.63.63 0 .344-.281.629-.63.629h-2.386c-.345 0-.627-.285-.627-.629V8.108c0-.345.282-.63.63-.63h2.386c.346 0 .627.285.627.63 0 .349-.281.63-.63.63H17.61v1.125h1.755zm-3.855 3.016c0 .27-.174.51-.432.596-.064.021-.133.031-.199.031-.211 0-.391-.09-.51-.25l-2.443-3.317v2.94c0 .344-.279.629-.631.629-.346 0-.626-.285-.626-.629V8.108c0-.27.173-.51.43-.595.06-.023.136-.033.194-.033.195 0 .375.104.495.254l2.462 3.33V8.108c0-.345.282-.63.63-.63.345 0 .63.285.63.63v4.771zm-5.741 0c0 .344-.282.629-.631.629-.345 0-.627-.285-.627-.629V8.108c0-.345.282-.63.63-.63.346 0 .628.285.628.63v4.771zm-2.466.629H4.917c-.345 0-.63-.285-.63-.629V8.108c0-.345.285-.63.63-.63.348 0 .63.285.63.63v4.141h1.756c.348 0 .629.283.629.63 0 .344-.282.629-.629.629M24 10.314C24 4.943 18.615.572 12 .572S0 4.943 0 10.314c0 4.811 4.27 8.842 10.035 9.608.391.082.923.258 1.058.59.12.301.079.766.038 1.08l-.164 1.02c-.045.301-.24 1.186 1.049.645 1.291-.539 6.916-4.078 9.436-6.975C23.176 14.393 24 12.458 24 10.314"/>
                                    </svg>
                                    LINE
                                </button>
                                
                                <button 
                                    data-share-action="copy"
                                    data-share-url="<?= e(url('/products/' . $product['id'])) ?>"
                                    class="flex items-center gap-2 px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg transition"
                                    title="URLをコピー">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                    </svg>
                                    URLコピー
                                </button>
                            </div>
                        </div>

                        <!-- 商品詳細 -->
                        <div class="border-t border-gray-200 pt-6 mb-8">
                            <h2 class="text-lg font-semibold text-gray-900 mb-3">商品詳細</h2>
                            <dl class="grid grid-cols-2 gap-4">
                                <?php if (!empty($product['origin'])): ?>
                                    <div>
                                        <dt class="text-sm text-gray-500">産地</dt>
                                        <dd class="text-gray-900 font-medium"><?= e($product['origin']) ?></dd>
                                    </div>
                                <?php endif; ?>
                                <?php if (!empty($product['roast_level'])): ?>
                                    <div>
                                        <dt class="text-sm text-gray-500">焙煎度</dt>
                                        <dd class="text-gray-900 font-medium"><?= e($product['roast_level']) ?></dd>
                                    </div>
                                <?php endif; ?>
                                <div>
                                    <dt class="text-sm text-gray-500">商品コード</dt>
                                    <dd class="text-gray-900 font-medium"><?= e($product['sku'] ?? $product['id']) ?></dd>
                                </div>
                            </dl>
                        </div>
                    </div>

                    <!-- カート追加フォーム -->
                    <div class="border-t    border-gray-200 pt-6">
                        <?php if ($canPurchase): ?>
                            <form 
                                method="POST" 
                                action="<?= url('/cart/add') ?>"
                                class="cart-add-form no-loading"
                                data-api-endpoint="<?= url('/api/cart/add') ?>"
                                data-product-name="<?= e($product['name']) ?>"
                            >
                                <?= csrfField() ?>
                                <input type="hidden" name="product_id" value="<?= e($product['id']) ?>" x-bind:value="selectedProductId">
                                <input type="hidden" name="variant_id" value="<?= e($initialVariantId ?? '') ?>" x-model="selectedVariantId">
                                
                                <?php if (!empty($variants)): ?>
                                <!-- バリアント選択UI -->
                                <div class="mb-6 space-y-4">
                                    <!-- タイプ選択 -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">形状タイプ</label>
                                        <div class="flex flex-wrap gap-2">
                                            <?php foreach ($variantsByType as $typeName => $typeVariants): ?>
                                                <?php $displayName = $typeVariants[0]['type_name_ja'] ?? $typeName; ?>
                                                <button 
                                                    type="button"
                                                    @click="selectType('<?= e($typeName) ?>')"
                                                    :class="selectedType === '<?= e($typeName) ?>' ? 
                                                        'bg-amber-600 text-white border-amber-600' : 
                                                        'bg-white text-gray-700 border-gray-300 hover:border-amber-500'"
                                                    class="px-4 py-2 rounded-lg border-2 font-medium transition"
                                                >
                                                    <?= e($displayName) ?>
                                                </button>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                    
                                    <!-- 内容量選択 -->
                                    <div x-show="selectedType">
                                        <label class="block text-sm font-medium text-gray-700 mb-2">内容量</label>
                                        <div class="flex flex-wrap gap-2">
                                            <template x-for="weight in availableWeights" :key="weight">
                                                <button 
                                                    type="button"
                                                    @click="selectWeight(weight)"
                                                    :class="selectedWeight === weight ? 
                                                        'bg-amber-600 text-white border-amber-600' : 
                                                        'bg-white text-gray-700 border-gray-300 hover:border-amber-500'"
                                                    class="px-4 py-2 rounded-lg border-2 font-medium transition"
                                                    x-text="typeof weight === 'number' ? `${weight}g` : weight"
                                                ></button>
                                            </template>
                                        </div>
                                    </div>
                                    
                                    <!-- 選択中のバリアント情報 -->
                                    <div x-show="currentVariant" class="bg-amber-50 border border-amber-200 rounded-lg p-4">
                                        <div class="grid grid-cols-2 gap-3 text-sm">
                                            <div>
                                                <span class="text-gray-600">価格:</span>
                                                <span class="font-bold text-amber-600 ml-2" x-text="'¥' + (currentVariant?.price || 0).toLocaleString()"></span>
                                            </div>
                                            <div>
                                                <span class="text-gray-600">在庫:</span>
                                                <span class="font-bold ml-2" x-text="(currentVariant?.stock || 0) + '個'"></span>
                                            </div>
                                            <div class="col-span-2">
                                                <span class="text-gray-600">SKU:</span>
                                                <span class="font-mono text-xs ml-2" x-text="currentVariant?.sku || '-'"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <?php endif; ?>
                                
                                <!-- 数量選択 -->
                                <div class="mb-6">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">数量</label>
                                    <div class="flex items-center space-x-3">
                                        <button 
                                            type="button" 
                                            @click="quantity = Math.max(1, quantity - 1)"
                                            class="w-10 h-10 rounded-lg border border-gray-300 hover:bg-gray-50 transition"
                                        >
                                            <svg class="w-5 h-5 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                                            </svg>
                                        </button>
                                        
                                        <input 
                                            type="number" 
                                            name="quantity" 
                                            x-model="quantity"
                                            min="1" 
                                            :max="activeStock > 0 ? activeStock : 1"
                                            class="w-20 px-4 py-2 text-center border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500"
                                        >
                                        
                                        <button 
                                            type="button" 
                                            @click="quantity = Math.min(activeStock > 0 ? activeStock : 1, quantity + 1)"
                                            class="w-10 h-10 rounded-lg border border-gray-300 hover:bg-gray-50 transition"
                                        >
                                            <svg class="w-5 h-5 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </div>

                                <!-- カート追加ボタン -->
                                <button 
                                    type="submit" 
                                    class="w-full bg-gradient-to-r from-amber-600 to-orange-600 text-white font-bold py-4 px-6 rounded-lg hover:from-amber-700 hover:to-orange-700 transition transform hover:scale-105 shadow-lg"
                                    :disabled="activeStock <= 0"
                                    :class="activeStock <= 0 ? 'opacity-60 cursor-not-allowed hover:scale-100 hover:from-amber-600 hover:to-orange-600' : ''"
                                >
                                    <span class="flex items-center justify-center">
                                        <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                        </svg>
                                        カートに追加
                                    </span>
                                </button>
                            </form>
                        <?php else: ?>
                            <button 
                                disabled 
                                class="w-full bg-gray-300 text-gray-500 font-bold py-4 px-6 rounded-lg cursor-not-allowed"
                            >
                                在庫切れ
                            </button>
                        <?php endif; ?>

                        <!-- お気に入り・比較・共有ボタン -->
                        <div class="mt-4 flex gap-3" x-data="window.productPage.wishlistButtonApp(window.productPageConfig.productId)">
                            <button 
                                @click="toggleWishlist()"
                                :disabled="isLoading"
                                :class="isInWishlist ? 'bg-red-50 border-red-300 text-red-700' : 'border-gray-300 text-gray-700'"
                                class="flex-1 border font-semibold py-2 px-4 rounded-lg hover:bg-gray-50 transition"
                            >
                                <svg class="w-5 h-5 inline mr-2" :fill="isInWishlist ? 'currentColor' : 'none'" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                </svg>
                                <span x-text="isInWishlist ? 'お気に入り済み' : 'お気に入り'"></span>
                            </button>
                            
                            <!-- 商品比較ボタン -->
                            <button 
                                type="button"
                                data-add-to-compare
                                data-product-id="<?= $product['id'] ?>"
                                class="border border-gray-300 text-gray-700 font-semibold py-2 px-4 rounded-lg hover:bg-gray-50 transition"
                                title="商品比較リストに追加"
                            >
                                <svg class="w-5 h-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                </svg>
                                比較
                            </button>
                            
                            <button 
                                type="button"
                                data-share-button
                                class="border border-gray-300 text-gray-700 font-semibold py-2 px-4 rounded-lg hover:bg-gray-50 transition"
                                title="この商品をシェア"
                            >
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- その他の内容量・形態を見る（折りたたみ可能なバリアント一覧） -->
        <?php if (!empty($variants) && count($variants) > 1): ?>
            <div class="mt-16" x-data="{ variantListOpen: false }">
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <!-- ヘッダー（クリックで開閉） -->
                    <button 
                        @click="variantListOpen = !variantListOpen"
                        class="w-full px-6 py-4 flex items-center justify-between hover:bg-gray-50 transition"
                    >
                        <h2 class="text-xl font-bold text-gray-900">
                            その他の内容量・形態を見る (<?= count($variants) ?>種類)
                        </h2>
                        <svg 
                            class="w-6 h-6 text-gray-500 transition-transform duration-200"
                            :class="{ 'rotate-180': variantListOpen }"
                            fill="none" 
                            stroke="currentColor" 
                            viewBox="0 0 24 24"
                        >
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    
                    <!-- バリアント一覧（折りたたみ） -->
                    <div x-show="variantListOpen" x-collapse>
                        <div class="px-6 pb-6 border-t border-gray-200">
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mt-4">
                                <?php foreach ($variants as $variant): ?>
                                    <a 
                                        href="<?= url('/products/' . $variant['id']) ?>" 
                                        class="block border rounded-lg p-4 hover:shadow-lg hover:border-amber-500 transition <?= $variant['id'] == $product['id'] ? 'border-amber-500 bg-amber-50' : 'border-gray-200' ?>"
                                    >
                                        <div class="flex items-start justify-between mb-2">
                                            <div class="flex-1">
                                                <div class="flex items-center gap-2 mb-1">
                                                    <span class="inline-block px-2 py-1 text-xs font-semibold rounded bg-amber-100 text-amber-800">
                                                        <?= e($variant['type_display'] ?? 'その他') ?>
                                                    </span>
                                                    <?php if ($variant['id'] == $product['id']): ?>
                                                        <span class="inline-block px-2 py-1 text-xs font-semibold rounded bg-green-100 text-green-800">
                                                            現在表示中
                                                        </span>
                                                    <?php endif; ?>
                                                </div>
                                                <h3 class="font-medium text-gray-900 text-sm line-clamp-2">
                                                    <?= e($variant['name']) ?>
                                                </h3>
                                            </div>
                                        </div>
                                        
                                        <div class="mt-3 flex items-center justify-between">
                                            <div>
                                                <p class="text-lg font-bold text-amber-600">
                                                    ¥<?= number_format($variant['price']) ?>
                                                </p>
                                                <?php if (!empty($variant['weight'])): ?>
                                                    <p class="text-xs text-gray-500">
                                                        <?= e($variant['weight']) ?>
                                                    </p>
                                                <?php endif; ?>
                                            </div>
                                            
                                            <?php if (($variant['stock_quantity'] ?? 0) > 0): ?>
                                                <span class="text-xs text-green-600">在庫あり</span>
                                            <?php else: ?>
                                                <span class="text-xs text-red-600">在庫切れ</span>
                                            <?php endif; ?>
                                        </div>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <!-- この商品を買った人はこんな商品も買っています（Phase 14: 協調フィルタリング） -->
        <?php if (!empty($recommendedProducts)): ?>
            <div class="mt-16">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-2xl font-bold text-gray-900">
                        <span class="inline-block bg-gradient-to-r from-amber-500 to-orange-500 text-transparent bg-clip-text">
                            この商品を買った人はこんな商品も買っています
                        </span>
                    </h2>
                    <svg class="w-8 h-8 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                </div>
                
                <div class="bg-gradient-to-br from-amber-50 to-orange-50 rounded-xl p-6 shadow-lg">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-6">
                        <?php foreach ($recommendedProducts as $recommended): ?>
                            <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition-all transform hover:-translate-y-1">
                                <a href="<?= url('/products/' . $recommended['id']) ?>">
                                    <div class="relative h-48 bg-gray-200">
                                        <?php if (!empty($recommended['image_url'])): ?>
                                            <img 
                                                src="<?= e($recommended['image_url']) ?>" 
                                                alt="<?= e($recommended['name']) ?>"
                                                class="w-full h-full object-cover"
                                            >
                                        <?php else: ?>
                                            <div class="w-full h-full flex items-center justify-center text-gray-400">
                                                <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                </svg>
                                            </div>
                                        <?php endif; ?>
                                        
                                        <!-- 割引バッジ -->
                                        <?php if (!empty($recommended['discount_price']) && $recommended['discount_price'] < $recommended['price']): ?>
                                            <?php
                                            $discountRate = round(($recommended['price'] - $recommended['discount_price']) / $recommended['price'] * 100);
                                            ?>
                                            <div class="absolute top-2 right-2 bg-red-500 text-white text-xs font-bold px-2 py-1 rounded-full shadow">
                                                <?= $discountRate ?>% OFF
                                            </div>
                                        <?php endif; ?>
                                        
                                        <!-- 同時購入回数バッジ -->
                                        <?php if (isset($recommended['co_purchase_count']) && $recommended['co_purchase_count'] > 0): ?>
                                            <div class="absolute top-2 left-2 bg-amber-500 text-white text-xs font-bold px-2 py-1 rounded-full shadow flex items-center">
                                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                                </svg>
                                                人気
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="p-4">
                                        <h3 class="font-semibold text-gray-800 mb-2 hover:text-amber-600 transition line-clamp-2 h-12">
                                            <?= e($recommended['name']) ?>
                                        </h3>
                                        <div class="flex items-center justify-between">
                                            <?php if (!empty($recommended['discount_price']) && $recommended['discount_price'] < $recommended['price']): ?>
                                                <div>
                                                    <p class="text-sm text-gray-500 line-through">
                                                        ¥<?= number_format($recommended['price']) ?>
                                                    </p>
                                                    <p class="text-xl font-bold text-red-600">
                                                        ¥<?= number_format($recommended['discount_price']) ?>
                                                    </p>
                                                </div>
                                            <?php else: ?>
                                                <p class="text-xl font-bold text-amber-600">
                                                    ¥<?= number_format($recommended['price']) ?>
                                                </p>
                                            <?php endif; ?>
                                        </div>
                                        <?php if ($recommended['stock'] > 0): ?>
                                            <p class="text-xs text-green-600 mt-2">在庫あり</p>
                                        <?php else: ?>
                                            <p class="text-xs text-red-600 mt-2">在庫切れ</p>
                                        <?php endif; ?>
                                    </div>
                                </a>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    
                    <!-- 説明テキスト -->
                    <div class="mt-4 text-center">
                        <p class="text-sm text-gray-600">
                            <svg class="w-4 h-4 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                            </svg>
                            購入履歴データに基づくパーソナライズされたおすすめ商品です
                        </p>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <!-- 関連商品（同じカテゴリ） -->
        <?php if (!empty($relatedProducts)): ?>
            <div class="mt-16">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">同じカテゴリの商品</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-6">
                    <?php foreach ($relatedProducts as $related): ?>
                        <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition-shadow">
                            <a href="<?= url('/products/' . $related['id']) ?>">
                                <div class="h-48 bg-gray-200">
                                    <?php if (!empty($related['image_url'])): ?>
                                        <img 
                                            src="<?= e($related['image_url']) ?>" 
                                            alt="<?= e($related['name']) ?>"
                                            class="w-full h-full object-cover"
                                        >
                                    <?php endif; ?>
                                </div>
                                <div class="p-4">
                                    <h3 class="font-semibold text-gray-800 mb-2 hover:text-amber-600 transition">
                                        <?= e($related['name']) ?>
                                    </h3>
                                    <p class="text-xl font-bold text-amber-600">
                                        ¥<?= number_format($related['price']) ?>
                                    </p>
                                </div>
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>

        <!-- レビューセクション（Phase 15） -->
        <div class="mt-16" id="reviews-section" x-data="window.productPage.reviewsApp(window.productPageConfig.productId)">
            <div class="bg-white rounded-lg shadow-lg p-8">
                <div class="flex items-center justify-between mb-8">
                    <h2 class="text-3xl font-bold text-gray-900">カスタマーレビュー</h2>
                    <?php if (isAuthenticated()): ?>
                        <button 
                            @click="showReviewForm = !showReviewForm"
                            class="bg-amber-600 hover:bg-amber-700 text-white font-bold py-2 px-6 rounded-lg transition"
                        >
                            <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                            レビューを書く
                        </button>
                    <?php else: ?>
                        <a href="<?= url('/login') ?>" class="text-amber-600 hover:text-amber-700 font-semibold">
                            レビューを書くにはログインしてください →
                        </a>
                    <?php endif; ?>
                </div>

                <!-- 評価統計 -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-12" x-show="stats">
                    <!-- 平均評価 -->
                    <div class="text-center">
                        <div class="text-5xl font-bold text-amber-600 mb-2" x-text="stats?.average_rating?.toFixed(1) || '0.0'"></div>
                        <div class="flex items-center justify-center mb-2">
                            <template x-for="i in 5">
                                <svg 
                                    class="w-6 h-6" 
                                    :class="i <= Math.round(stats?.average_rating || 0) ? 'text-yellow-400' : 'text-gray-300'"
                                    fill="currentColor" 
                                    viewBox="0 0 20 20"
                                >
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                </svg>
                            </template>
                        </div>
                        <p class="text-gray-600" x-text="`${stats?.total_reviews || 0}件のレビュー`"></p>
                    </div>

                    <!-- 評価分布 -->
                    <div class="lg:col-span-2 space-y-2">
                        <template x-for="rating in [5, 4, 3, 2, 1]">
                            <div class="flex items-center gap-4">
                                <span class="text-sm font-medium text-gray-700 w-12" x-text="`★${rating}`"></span>
                                <div class="flex-1 bg-gray-200 rounded-full h-4">
                                    <div 
                                        class="bg-yellow-400 h-4 rounded-full transition-all"
                                        :style="`width: ${stats ? (stats['rating_' + rating] / stats.total_reviews * 100) : 0}%`"
                                    ></div>
                                </div>
                                <span class="text-sm text-gray-600 w-16 text-right" x-text="stats ? stats['rating_' + rating] : 0"></span>
                            </div>
                        </template>
                    </div>
                </div>

                <!-- レビュー投稿フォーム -->
                <div x-show="showReviewForm" class="mb-12 bg-amber-50 rounded-lg p-6 border-2 border-amber-200">
                    <h3 class="text-xl font-bold text-gray-900 mb-6">レビューを投稿</h3>
                    <form @submit.prevent="submitReview">
                        <?= csrfField() ?>
                        
                        <!-- 評価選択 -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">評価 <span class="text-red-500">*</span></label>
                            <div class="flex gap-2">
                                <template x-for="i in 5">
                                    <button 
                                        type="button"
                                        @click="formData.rating = i"
                                        class="focus:outline-none"
                                    >
                                        <svg 
                                            class="w-10 h-10 transition-colors"
                                            :class="i <= formData.rating ? 'text-yellow-400' : 'text-gray-300 hover:text-yellow-200'"
                                            fill="currentColor" 
                                            viewBox="0 0 20 20"
                                        >
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                        </svg>
                                    </button>
                                </template>
                            </div>
                        </div>

                        <!-- レビュー本文 -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">レビュー本文 <span class="text-red-500">*</span></label>
                            <textarea 
                                x-model="formData.body"
                                rows="6"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500"
                                placeholder="この商品についてのご意見・ご感想をお聞かせください（10文字以上）"
                            ></textarea>
                            <p class="text-sm text-gray-500 mt-1" x-text="`${formData.body.length} / 2000文字`"></p>
                        </div>

                        <!-- エラーメッセージ -->
                        <div x-show="errorMessage" class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg">
                            <p class="text-red-600 text-sm" x-html="errorMessage"></p>
                        </div>

                        <!-- 送信ボタン -->
                        <div class="flex gap-4">
                            <button 
                                type="submit"
                                :disabled="isSubmitting"
                                class="bg-amber-600 hover:bg-amber-700 disabled:bg-gray-400 text-white font-bold py-3 px-8 rounded-lg transition"
                            >
                                <span x-show="!isSubmitting">投稿する</span>
                                <span x-show="isSubmitting">投稿中...</span>
                            </button>
                            <button 
                                type="button"
                                @click="showReviewForm = false; errorMessage = ''"
                                class="border border-gray-300 text-gray-700 font-semibold py-3 px-8 rounded-lg hover:bg-gray-50 transition"
                            >
                                キャンセル
                            </button>
                        </div>
                    </form>
                </div>

                <!-- レビュー一覧 -->
                <div class="space-y-6">
                    <template x-if="reviews.length === 0 && !isLoading">
                        <div class="text-center py-12">
                            <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path>
                            </svg>
                            <p class="text-gray-500 text-lg">まだレビューはありません</p>
                            <p class="text-gray-400 text-sm mt-2">最初のレビューを投稿してみませんか？</p>
                        </div>
                    </template>

                    <template x-for="review in reviews" :key="review.id">
                        <div class="border-b border-gray-200 pb-6">
                            <div class="flex items-start justify-between mb-4">
                                <div>
                                    <div class="flex items-center gap-2 mb-2">
                                        <span class="font-semibold text-gray-900" x-text="review.user_name"></span>
                                        <span x-show="review.is_verified_purchase" class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded-full">
                                            ✓ 購入者
                                        </span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <div class="flex">
                                            <template x-for="i in 5">
                                                <svg 
                                                    class="w-5 h-5" 
                                                    :class="i <= review.rating ? 'text-yellow-400' : 'text-gray-300'"
                                                    fill="currentColor" 
                                                    viewBox="0 0 20 20"
                                                >
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                                </svg>
                                            </template>
                                        </div>
                                        <span class="text-sm text-gray-500" x-text="new Date(review.created_at).toLocaleDateString('ja-JP')"></span>
                                    </div>
                                </div>
                            </div>
                            <p class="text-gray-700 leading-relaxed whitespace-pre-line" x-text="review.body"></p>
                        </div>
                    </template>
                </div>

                <!-- ページネーション -->
                <div x-show="pagination && pagination.totalPages > 1" class="mt-8 flex justify-center">
                    <nav class="flex gap-2">
                        <button 
                            @click="loadReviews(pagination.page - 1)"
                            :disabled="pagination.page === 1"
                            class="px-4 py-2 border border-gray-300 rounded-lg disabled:opacity-50"
                        >
                            前へ
                        </button>
                        <template x-for="page in pagination.totalPages">
                            <button 
                                @click="loadReviews(page)"
                                :class="page === pagination.page ? 'bg-amber-600 text-white' : 'bg-white text-gray-700'"
                                class="px-4 py-2 border border-gray-300 rounded-lg"
                                x-text="page"
                            ></button>
                        </template>
                        <button 
                            @click="loadReviews(pagination.page + 1)"
                            :disabled="pagination.page === pagination.totalPages"
                            class="px-4 py-2 border border-gray-300 rounded-lg disabled:opacity-50"
                        >
                            次へ
                        </button>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
window.productPageConfig = {
    productId: <?= (int) $product['id'] ?>,
    productName: <?= json_encode($product['name']) ?>,
    productUrl: <?= json_encode(url('/products/' . $product['id'])) ?>,
    fallbackImage: <?= json_encode($product['image_url'] ?? '/images/no-image.png') ?>,
    variantSelector: <?= json_encode($variantComponentConfig, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?>,
    review: {
        apiBase: <?= json_encode(url('/api/reviews/')) ?>
    },
    wishlist: {
        checkUrl: <?= json_encode(url('/api/wishlist/check')) ?>,
        addUrl: <?= json_encode(url('/api/wishlist/add')) ?>,
        removeUrl: <?= json_encode(url('/api/wishlist/remove')) ?>,
        loginUrl: <?= json_encode(url('/login')) ?>
    },
    viewHistory: {
        productId: <?= (int) $product['id'] ?>,
        productName: <?= json_encode($product['name']) ?>,
        productPrice: <?= (int) $product['price'] ?>,
        productImage: <?= json_encode($product['image_url'] ?? '/images/no-image.png') ?>
    },
    lightboxImages: <?= json_encode($lightboxImages) ?>,
    swiper: {
        hasThumbs: <?= (!empty($productImages) && count($productImages) > 1) ? 'true' : 'false' ?>
    }
};
</script>
<script src="<?= url('js/product-show.js') ?>?v=20251205"></script>

<?php require_once __DIR__ . '/../../layout/footer.php'; ?>
