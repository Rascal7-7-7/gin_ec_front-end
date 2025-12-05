<?php require_once __DIR__ . '/../../layout/header.php'; ?>

<?php
$paginationMeta = $pagination ?? [
    'current_page' => $page ?? 1,
    'total_pages' => $totalPages ?? 1,
    'has_prev' => ($page ?? 1) > 1,
    'has_next' => ($page ?? 1) < ($totalPages ?? 1),
];

/**
 * 商品一覧ページ
 *
 * 機能:
 * - 商品の一覧表示（グリッドレイアウト）
 * - カテゴリー／シーン／セール／価格帯フィルター
 * - 並び替え（新着順、人気順、価格順、商品名順）
 * - ページネーション
 * - カート追加、ウィッシュリスト、比較リスト
 */

$productPageFilters = [
    'category' => $filters['category'] ?? ($_GET['category'] ?? null),
    'scene' => $filters['scene'] ?? ($_GET['scene'] ?? null),
    'variant_type' => $filters['variant_type'] ?? ($_GET['variant_type'] ?? null),
    'sort' => $filters['sort'] ?? ($_GET['sort'] ?? 'new'),
    'min_price' => $filters['min_price'] ?? ($_GET['min_price'] ?? null),
    'max_price' => $filters['max_price'] ?? ($_GET['max_price'] ?? null),
    'sale' => $filters['sale'] ?? ($_GET['sale'] ?? null),
    'search' => $filters['search'] ?? ($_GET['search'] ?? null),
    'page' => $_GET['page'] ?? ($paginationMeta['current_page'] ?? 1),
];

if (!function_exists('productFilterUrl')) {
    function productFilterUrl(array $overrides = [], bool $resetPage = true, array $removeKeys = []): string
    {
        global $productPageFilters;
        $params = $productPageFilters ?? [];

        // フィルターのスナップショットが空の場合でも、現在のクエリパラメータを維持できるようにフォールバック
        if (empty($params)) {
            $allowedKeys = ['category','scene','variant_type','sort','min_price','max_price','sale','search','page'];
            foreach ($allowedKeys as $key) {
                if (isset($_GET[$key]) && $_GET[$key] !== '' && $_GET[$key] !== null) {
                    $params[$key] = $_GET[$key];
                }
            }
        }

        if ($resetPage) {
            unset($params['page']);
        }

        foreach ($removeKeys as $removeKey) {
            unset($params[$removeKey]);
        }

        foreach ($overrides as $key => $value) {
            if ($value === null || $value === '') {
                unset($params[$key]);
            } else {
                $params[$key] = $value;
            }
        }

        $query = http_build_query(array_filter($params, static fn($value) => $value !== '' && $value !== null));
        return url('/products' . ($query ? '?' . $query : ''));
    }
}

if (!function_exists('productPaginationUrl')) {
    function productPaginationUrl(int $page): string
    {
        // productFilterUrlを利用して現在のフィルター条件を維持したままページのみを差し替える
        return productFilterUrl(['page' => $page], false);
    }
}

$categoryLabels = [
    'coffee' => 'コーヒー',
    'tea' => '紅茶',
    'tea_all' => 'すべての茶',
    'japanese_tea' => '日本茶',
    'green_tea' => '日本茶',
    'matcha' => '抹茶',
    'black_tea' => '紅茶',
    'chinese_tea' => '中国茶',
    'oolong_tea' => '中国茶',
    'white_tea' => '白茶',
    'herb_health' => 'ハーブティー・健康茶',
    'herbal_tea' => 'ハーブティー',
    'rooibos' => 'ルイボス',
    'chai' => 'チャイ',
    'gift' => 'ギフト',
    'goods' => 'グッズ',
    'other' => 'その他',
];

$categoryFilterOptions = [
    ['value' => null, 'label' => 'すべて'],
    ['value' => 'coffee', 'label' => 'コーヒー'],
    ['value' => 'tea_all', 'label' => 'すべての茶'],
    ['value' => 'japanese_tea', 'label' => '日本茶'],
    ['value' => 'black_tea', 'label' => '紅茶'],
    ['value' => 'chinese_tea', 'label' => '中国茶'],
    ['value' => 'herb_health', 'label' => 'ハーブティー・健康茶'],
    ['value' => 'gift', 'label' => 'ギフト'],
    ['value' => 'other', 'label' => 'その他'],
];

$sceneName = $sceneName ?? null;
$sceneLabels = [
    'morning' => '朝食',
    'work' => '仕事・勉強中',
    'afternoon' => '午後のティータイム',
    'relax' => 'リラックスタイム',
    'dessert' => 'デザートと一緒に',
    'bedtime' => '就寝前',
    'night' => '夜',
];

$sortLabels = [
    'popular' => '人気順',
    'price_asc' => '価格が安い順',
    'price_desc' => '価格が高い順',
    'name' => '商品名順',
];

$activeFilterBadges = [];

if (!empty($productPageFilters['category'])) {
    $activeFilterBadges[] = 'カテゴリ: ' . ($categoryLabels[$productPageFilters['category']] ?? ucfirst($productPageFilters['category']));
}

$variantTypeLabelMap = [];
if (!empty($variantTypeFilters ?? [])) {
    foreach ($variantTypeFilters as $typeOption) {
        $key = (string) ($typeOption['id'] ?? '');
        if ($key === '') {
            continue;
        }
        $variantTypeLabelMap[$key] = $typeOption['name_ja'] ?? $typeOption['name'] ?? $typeOption['label'] ?? '';
    }
}

if (!empty($productPageFilters['variant_type'])) {
    $variantTypeId = (string) $productPageFilters['variant_type'];
    $badgeLabel = $variantTypeLabelMap[$variantTypeId] ?? null;
    if ($badgeLabel) {
        $activeFilterBadges[] = '商品タイプ: ' . $badgeLabel;
    }
}

if (!empty($productPageFilters['scene'])) {
    $activeFilterBadges[] = 'シーン: ' . ($sceneLabels[$productPageFilters['scene']] ?? $productPageFilters['scene']);
}

if (!empty($productPageFilters['sale'])) {
    $activeFilterBadges[] = 'セール対象';
}

$hasMin = isset($productPageFilters['min_price']) && $productPageFilters['min_price'] !== '' && $productPageFilters['min_price'] !== null;
$hasMax = isset($productPageFilters['max_price']) && $productPageFilters['max_price'] !== '' && $productPageFilters['max_price'] !== null;
if ($hasMin || $hasMax) {
    $range = $hasMin ? '¥' . number_format((int) $productPageFilters['min_price']) : '下限なし';
    $range .= ' 〜 ';
    $range .= $hasMax ? '¥' . number_format((int) $productPageFilters['max_price']) : '上限なし';
    $activeFilterBadges[] = '価格: ' . $range;
}

if (!empty($productPageFilters['sort']) && $productPageFilters['sort'] !== 'new') {
    $activeFilterBadges[] = '並び順: ' . ($sortLabels[$productPageFilters['sort']] ?? $productPageFilters['sort']);
}
?>

<div class="min-h-screen bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
        <!-- パンくずリスト: ページナビゲーション -->
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
                <li class="text-gray-500">商品一覧</li>
            </ol>
        </nav>

        <?php if (!empty($productPageFilters['search'])): ?>
            <div class="mb-6 flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between bg-white border border-amber-100 rounded-lg px-4 py-3 shadow-sm">
                <div>
                    <p class="text-sm md:text-base text-gray-800">
                        <span class="font-semibold text-amber-700">
                            「<?= e($productPageFilters['search']) ?>」
                        </span>
                        の検索結果
                        <?php if (isset($totalCount)): ?>
                            <span class="text-gray-500">（<?= number_format($totalCount) ?>件ヒット）</span>
                        <?php endif; ?>
                    </p>
                    <p class="text-xs text-gray-500 mt-1">キーワードを修正しても現在のフィルター条件は維持されます。</p>
                </div>
                <div class="flex flex-col gap-2 sm:flex-row sm:items-center">
                    <form action="<?= url('/products') ?>" method="GET" class="flex flex-wrap gap-2">
                        <?php foreach ($productPageFilters as $key => $value): ?>
                            <?php if (in_array($key, ['search', 'page'], true)) continue; ?>
                            <?php if ($value === null || $value === '') continue; ?>
                            <input type="hidden" name="<?= e($key) ?>" value="<?= e($value) ?>">
                        <?php endforeach; ?>
                        <input
                            type="text"
                            name="search"
                            value="<?= e($productPageFilters['search']) ?>"
                            class="flex-1 min-w-[200px] px-3 py-2 border border-amber-200 rounded-lg text-sm focus:ring-2 focus:ring-amber-500"
                            placeholder="キーワードを編集"
                        >
                        <button type="submit" class="px-4 py-2 text-sm font-semibold text-white bg-amber-600 rounded-lg hover:bg-amber-700">
                            再検索
                        </button>
                    </form>
                    <a href="<?= productFilterUrl([], true, ['search']) ?>" class="text-sm text-amber-700 hover:text-amber-800 text-center">
                        キーワードをクリア
                    </a>
                </div>
            </div>
        <?php endif; ?>

        <div class="flex flex-col lg:flex-row gap-8">
            <!-- ===== サイドバー（フィルター機能） ===== -->
            <aside class="lg:w-64 flex-shrink-0">
                <!-- sticky top-24: スクロール時も固定表示 -->
                <div class="bg-white rounded-lg shadow-md p-6 sticky top-24">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">絞り込み</h3>

                    <!-- ===== カテゴリーフィルター ===== -->
                    <!-- blend: ブレンドコーヒー / single: シングルオリジン / decaf: デカフェ -->
                    <div class="mb-6">
                        <h4 class="font-semibold text-gray-700 mb-3">カテゴリー</h4>
                        <div class="space-y-2">
                            <?php foreach ($categoryFilterOptions as $option): ?>
                                <?php
                                    $value = $option['value'];
                                    $isActive = $value === null
                                        ? empty($productPageFilters['category'])
                                        : (($productPageFilters['category'] ?? '') === $value);
                                    $link = $value === null
                                        ? productFilterUrl([], true, ['category'])
                                        : productFilterUrl(['category' => $value]);
                                ?>
                                <a href="<?= $link ?>" class="block text-sm <?= $isActive ? 'text-amber-600 font-semibold' : 'text-gray-600 hover:text-amber-600' ?>">
                                    <?= e($option['label']) ?>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <?php if (!empty($variantTypeFilters ?? [])): ?>
                        <div class="mb-6">
                            <h4 class="font-semibold text-gray-700 mb-3">商品タイプ</h4>
                            <div class="flex flex-wrap gap-2">
                                <?php
                                    $currentVariantType = (string)($productPageFilters['variant_type'] ?? '');
                                    $allTypesUrl = productFilterUrl([], true, ['variant_type']);
                                ?>
                                <a href="<?= $allTypesUrl ?>" class="px-3 py-1 text-xs rounded-full border <?= $currentVariantType === '' ? 'bg-amber-50 border-amber-500 text-amber-700 font-semibold' : 'border-gray-300 text-gray-600 hover:border-amber-400 hover:text-amber-600' ?>">
                                    すべて
                                </a>
                                <?php foreach ($variantTypeFilters as $typeOption): ?>
                                    <?php
                                        $typeId = (string)($typeOption['id'] ?? '');
                                        if ($typeId === '') {
                                            continue;
                                        }
                                        $typeLabel = $typeOption['name_ja'] ?? $typeOption['name'] ?? 'タイプ';
                                        $isActive = $currentVariantType === $typeId;
                                        $typeUrl = productFilterUrl(['variant_type' => $typeId]);
                                    ?>
                                    <a href="<?= $typeUrl ?>" class="px-3 py-1 text-xs rounded-full border <?= $isActive ? 'bg-amber-50 border-amber-500 text-amber-700 font-semibold' : 'border-gray-300 text-gray-600 hover:border-amber-400 hover:text-amber-600' ?>">
                                        <?= e($typeLabel) ?>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- ===== シーン別フィルター ===== -->
                    <div class="mb-6">
                        <h4 class="font-semibold text-gray-700 mb-3 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            シーンで探す
                        </h4>
                        <div class="space-y-2">
                            <a href="<?= productFilterUrl([], true, ['scene']) ?>" class="block text-sm <?= empty($productPageFilters['scene']) ? 'text-amber-600 font-semibold' : 'text-gray-600 hover:text-amber-600' ?>">
                                すべて
                            </a>
                            <a href="<?= productFilterUrl(['scene' => 'morning']) ?>" class="block text-sm <?= ($productPageFilters['scene'] ?? '') === 'morning' ? 'text-amber-600 font-semibold' : 'text-gray-600 hover:text-amber-600' ?>">
                                ☀️ 朝食
                            </a>
                            <a href="<?= productFilterUrl(['scene' => 'work']) ?>" class="block text-sm <?= ($productPageFilters['scene'] ?? '') === 'work' ? 'text-amber-600 font-semibold' : 'text-gray-600 hover:text-amber-600' ?>">
                                💼 仕事・勉強中
                            </a>
                            <a href="<?= productFilterUrl(['scene' => 'afternoon']) ?>" class="block text-sm <?= ($productPageFilters['scene'] ?? '') === 'afternoon' ? 'text-amber-600 font-semibold' : 'text-gray-600 hover:text-amber-600' ?>">
                                ☕ 午後のティータイム
                            </a>
                            <a href="<?= productFilterUrl(['scene' => 'relax']) ?>" class="block text-sm <?= ($productPageFilters['scene'] ?? '') === 'relax' ? 'text-amber-600 font-semibold' : 'text-gray-600 hover:text-amber-600' ?>">
                                🌙 リラックスタイム
                            </a>
                            <a href="<?= productFilterUrl(['scene' => 'dessert']) ?>" class="block text-sm <?= ($productPageFilters['scene'] ?? '') === 'dessert' ? 'text-amber-600 font-semibold' : 'text-gray-600 hover:text-amber-600' ?>">
                                🍰 デザートと一緒に
                            </a>
                            <a href="<?= productFilterUrl(['scene' => 'bedtime']) ?>" class="block text-sm <?= ($productPageFilters['scene'] ?? '') === 'bedtime' ? 'text-amber-600 font-semibold' : 'text-gray-600 hover:text-amber-600' ?>">
                                😴 就寝前
                            </a>
                        </div>
                    </div>

                    <!-- ===== 並び替えフィルター ===== -->
                    <!-- GETパラメータでソート条件を指定 -->
                    <div class="mb-6">
                        <h4 class="font-semibold text-gray-700 mb-3">並び替え</h4>
                        <form method="GET" class="space-y-2">
                            <?php foreach (['category','scene','variant_type','min_price','max_price','sale','search'] as $hiddenKey): ?>
                                <?php if (isset($productPageFilters[$hiddenKey]) && $productPageFilters[$hiddenKey] !== '' && $productPageFilters[$hiddenKey] !== null): ?>
                                    <input type="hidden" name="<?= e($hiddenKey) ?>" value="<?= e($productPageFilters[$hiddenKey]) ?>">
                                <?php endif; ?>
                            <?php endforeach; ?>
                            <select name="sort" data-auto-submit="true" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 text-sm">
                                <option value="new" <?= ($productPageFilters['sort'] ?? 'new') === 'new' ? 'selected' : '' ?>>新着順</option>
                                <option value="popular" <?= ($productPageFilters['sort'] ?? '') === 'popular' ? 'selected' : '' ?>>人気順</option>
                                <option value="price_asc" <?= ($productPageFilters['sort'] ?? '') === 'price_asc' ? 'selected' : '' ?>>価格が安い順</option>
                                <option value="price_desc" <?= ($productPageFilters['sort'] ?? '') === 'price_desc' ? 'selected' : '' ?>>価格が高い順</option>
                            </select>
                        </form>
                    </div>

                    <!-- ===== 価格帯フィルター ===== -->
                    <!-- 最小価格〜最大価格で絞り込み -->
                    <div>
                        <h4 class="font-semibold text-gray-700 mb-3">価格帯</h4>
                        <form method="GET" class="space-y-3">
                            <?php foreach (['category','scene','variant_type','sort','sale','search'] as $hiddenKey): ?>
                                <?php if (isset($productPageFilters[$hiddenKey]) && $productPageFilters[$hiddenKey] !== '' && $productPageFilters[$hiddenKey] !== null): ?>
                                    <input type="hidden" name="<?= e($hiddenKey) ?>" value="<?= e($productPageFilters[$hiddenKey]) ?>">
                                <?php endif; ?>
                            <?php endforeach; ?>
                            <div class="flex gap-2">
                                <input 
                                    type="number" 
                                    name="min_price" 
                                    placeholder="最小"
                                    value="<?= e($productPageFilters['min_price'] ?? '') ?>"
                                    class="w-1/2 px-3 py-2 border border-gray-300 rounded-lg text-sm"
                                >
                                <input 
                                    type="number" 
                                    name="max_price" 
                                    placeholder="最大"
                                    value="<?= e($productPageFilters['max_price'] ?? '') ?>"
                                    class="w-1/2 px-3 py-2 border border-gray-300 rounded-lg text-sm"
                                >
                            </div>
                            <button type="submit" class="w-full bg-amber-600 text-white py-2 rounded-lg hover:bg-amber-700 transition text-sm font-semibold">
                                適用
                            </button>
                        </form>
                    </div>

                    <div class="mt-6 pt-6 border-t border-gray-100">
                        <a href="<?= url('/products') ?>" class="w-full inline-flex items-center justify-center px-4 py-2 border border-gray-300 rounded-lg text-sm text-gray-700 hover:bg-gray-50 transition">
                            フィルターをクリア
                        </a>
                    </div>
                </div>
            </aside>

            <!-- ===== メインコンテンツ（商品リスト） ===== -->
            <main class="flex-1">
                <!-- ===== ヘッダー: タイトルと商品数 ===== -->
                <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between mb-6">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-800">
                            <?php if (!empty($productPageFilters['search'])): ?>
                                「<?= e($productPageFilters['search']) ?>」の検索結果
                            <?php elseif (!empty($sceneName)): ?>
                                <?= e($sceneName) ?> におすすめ
                            <?php elseif (!empty($productPageFilters['category'])): ?>
                                <?= e($categoryLabels[$productPageFilters['category']] ?? ucfirst($productPageFilters['category'])) ?>
                            <?php else: ?>
                                すべての商品
                            <?php endif; ?>
                        </h1>
                        <p class="text-gray-600 mt-1">
                            <?php if (!empty($productPageFilters['search'])): ?>
                                現在の条件に一致する商品が <?= number_format($totalCount ?? count($products)) ?> 件見つかりました。
                            <?php else: ?>
                                <?= number_format($totalCount ?? count($products)) ?>件の商品
                            <?php endif; ?>
                        </p>
                    </div>
                    <div class="flex flex-wrap items-center gap-3">
                        <a href="<?= url('/products/ranking?period=week') ?>" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg border border-amber-600 text-amber-700 font-semibold hover:bg-amber-50 transition">
                            <span>🏆</span>
                            <span>人気ランキングを見る</span>
                        </a>
                    </div>
                </div>

                <?php if (!empty($activeFilterBadges)): ?>
                    <div class="mb-6 flex flex-wrap items-center gap-2">
                        <?php foreach ($activeFilterBadges as $badge): ?>
                            <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-amber-50 text-amber-700 text-xs font-semibold border border-amber-100">
                                <?= e($badge) ?>
                            </span>
                        <?php endforeach; ?>
                        <a href="<?= url('/products') ?>" class="text-xs font-semibold text-amber-700 hover:text-amber-800">
                            条件をすべてリセット
                        </a>
                    </div>
                <?php endif; ?>

                <!-- ===== 商品グリッド表示 ===== -->
                <!-- 商品が見つからない場合のメッセージ -->
                <?php if (empty($products)): ?>
                    <div class="bg-white rounded-lg shadow-md p-12 text-center">
                        <svg class="w-24 h-24 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                        </svg>
                        <h3 class="text-xl font-semibold text-gray-700 mb-2">商品が見つかりませんでした</h3>
                        <p class="text-gray-500 mb-4">条件を変更して再度お試しください</p>
                        <a href="<?= url('/products') ?>" class="inline-block bg-amber-600 text-white px-6 py-2 rounded-lg hover:bg-amber-700 transition">
                            すべての商品を見る
                        </a>
                    </div>
                <?php else: ?>
                    <!-- レスポンシブグリッド: モバイル1列、タブレット2列、デスクトップ3列 -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        <?php foreach ($products as $product): ?>
                            <?php
                                $variantSlides = [];
                                $variantPayload = [];
                                $hasStock = false;
                                $variantsForDisplay = $product['variants'] ?? [];
                                if (!empty($variantsForDisplay)) {
                                    foreach ($variantsForDisplay as $variantData) {
                                        if (($variantData['stock'] ?? 0) > 0) {
                                            $hasStock = true;
                                        }
                                        $imageUrl = normalizeProductImageUrl($variantData['image_url'] ?? null) ?? ($product['image_url'] ?? url('images/no-image.png'));
                                        $variantIdValue = $variantData['id'] ?? $variantData['product_id'];
                                        $variantCompareValue = null;
                                        if (isset($variantData['compare_price'])) {
                                            $variantCompareValue = (int) $variantData['compare_price'];
                                        } elseif (isset($variantData['compare_at_price'])) {
                                            $variantCompareValue = (int) $variantData['compare_at_price'];
                                        }
                                        $variantSlides[] = [
                                            'image_url' => $imageUrl,
                                            'label' => $variantData['type_display'] ?? ($variantData['type_name_ja'] ?? 'バリアント'),
                                            'name' => $variantData['name'] ?? ($product['base_name'] ?? $product['name']),
                                            'variant_id' => $variantIdValue,
                                        ];
                                        $variantPayload[] = [
                                            'id' => (string) $variantIdValue,
                                            'price' => (int) ($variantData['price'] ?? 0),
                                            'compare_price' => $variantCompareValue,
                                            'image_url' => $imageUrl,
                                            'label' => $variantData['type_display'] ?? ($variantData['type_name_ja'] ?? 'バリアント'),
                                            'name' => $variantData['name'] ?? ($product['base_name'] ?? $product['name']),
                                            'stock' => (int) ($variantData['stock'] ?? 0),
                                        ];
                                    }
                                } else {
                                    $imageUrl = $product['image_url'] ?? url('images/no-image.png');
                                    $variantSlides[] = [
                                        'image_url' => $imageUrl,
                                        'label' => $product['base_name'] ?? $product['name'],
                                        'name' => $product['name'],
                                        'variant_id' => $product['id'],
                                    ];
                                    $variantPayload[] = [
                                        'id' => (string) $product['id'],
                                        'price' => (int) ($product['discount_price'] ?: $product['price']),
                                        'compare_price' => !empty($product['discount_price']) && $product['discount_price'] < $product['price'] ? (int) $product['price'] : null,
                                        'image_url' => $imageUrl,
                                        'label' => $product['base_name'] ?? $product['name'],
                                        'name' => $product['name'],
                                    ];
                                    $hasStock = ($product['stock'] ?? 0) > 0;
                                }
                                $shouldLoop = count($variantSlides) > 1;
                                $defaultVariantId = $product['default_variant']['id'] ?? ($variantsForDisplay[0]['id'] ?? $product['id']);
                                $initialPriceValue = (!empty($product['discount_price']) && $product['discount_price'] < $product['price'])
                                    ? (int) $product['discount_price']
                                    : (int) $product['price'];
                                $initialCompareValue = (!empty($product['discount_price']) && $product['discount_price'] < $product['price'])
                                    ? (int) $product['price']
                                    : null;
                            ?>
                            <!-- ===== 商品カード ===== -->
                            <div 
                                class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition-shadow duration-300 flex flex-col h-full"
                                data-product-card
                                data-default-variant-id="<?= e($defaultVariantId) ?>"
                                data-variants='<?= e(json_encode($variantPayload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)) ?>'
                            >
                                <a href="<?= url('/products/' . $product['id']) ?>" class="block flex-1">
                                    <!-- ===== 商品画像エリア ===== -->
                                    <div class="relative overflow-hidden bg-gray-100" style="height: 400px;">
                                        <div 
                                            class="swiper product-card-swiper h-full" 
                                            id="product-swiper-<?= $product['id'] ?>"
                                            data-loop="<?= $shouldLoop ? 'true' : 'false' ?>"
                                        >
                                            <div class="swiper-wrapper">
                                                <?php foreach ($variantSlides as $slide): ?>
                                                    <div class="swiper-slide flex items-center justify-center" data-variant-id="<?= e($slide['variant_id']) ?>">
                                                        <img 
                                                            src="<?= e($slide['image_url']) ?>" 
                                                            alt="<?= e($slide['name']) ?>"
                                                            class="w-full h-full object-cover"
                                                        >
                                                        <div class="absolute bottom-3 right-3 bg-white/90 text-gray-800 text-xs font-semibold px-3 py-1 rounded-full shadow">
                                                            <?= e($slide['label']) ?>
                                                        </div>
                                                    </div>
                                                <?php endforeach; ?>
                                            </div>
                                            <?php if ($shouldLoop): ?>
                                                <div class="swiper-pagination"></div>
                                                <div class="swiper-button-next"></div>
                                                <div class="swiper-button-prev"></div>
                                            <?php endif; ?>
                                        </div>
                                        
                                        <!-- 在庫切れの場合はバッジ表示 -->
                                        <?php if (!$hasStock): ?>
                                            <div class="absolute top-2 right-2 bg-red-500 text-white px-3 py-1 rounded-full text-sm font-semibold">
                                                在庫切れ
                                            </div>
                                        <?php endif; ?>
                                        
                                        <!-- セールバッジ -->
                                        <?php if (!empty($product['discount_price']) && $product['discount_price'] < $product['price'] && $product['stock'] > 0): ?>
                                            <?php $discountRate = round((($product['price'] - $product['discount_price']) / $product['price']) * 100); ?>
                                            <div class="absolute top-2 right-2 bg-red-600 text-white px-3 py-1 rounded-full text-sm font-bold shadow-lg">
                                                <?= $discountRate ?>% OFF
                                            </div>
                                        <?php endif; ?>

                                        <!-- ===== ウィッシュリスト（お気に入り）ボタン ===== -->
                                        <!-- event.preventDefault()で親要素のリンク遷移を防止 -->
                                        <button 
                                            type="button"
                                            data-wishlist-toggle
                                            data-product-id="<?= $product['id'] ?>"
                                            class="absolute top-2 left-2 z-20 bg-white/90 hover:bg-white p-2 rounded-full shadow-md transition group"
                                            data-wishlist="false"
                                        >
                                            <!-- ハートアイコン（wishlist状態によってfillが変わる） -->
                                            <svg class="w-5 h-5 text-red-600 wishlist-icon" fill="none" stroke="currentColor" viewBox="0 0 20 20">
                                                <path d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" />
                                            </svg>
                                        </button>
                                    </div>

                                    <!-- ===== 商品情報エリア ===== -->
                                    <div class="p-4 flex flex-col gap-3">
                                        <!-- 商品名（グルーピング時は基本名を表示） -->
                                        <h3 class="font-semibold text-lg text-gray-800 hover:text-amber-600 transition product-card-title">
                                            <?= e($product['base_name'] ?? $product['name']) ?>
                                        </h3>
                                        <!-- 商品説明 -->
                                        <p class="text-gray-600 text-sm line-clamp-2 product-card-description">
                                            <?= e($product['description'] ?? '') ?: '商品説明は準備中です。' ?>
                                        </p>
                                        <!-- 価格と在庫状態 -->
                                        <div class="flex justify-between items-center mt-auto">
                                            <div class="flex flex-col" data-price-wrapper>
                                                <span 
                                                    class="text-2xl font-bold text-amber-600" 
                                                    data-price-text
                                                >
                                                    ¥<?= number_format($initialPriceValue) ?>
                                                </span>
                                                <span 
                                                    class="text-sm text-gray-400 line-through" 
                                                    data-compare-text
                                                    style="<?= $initialCompareValue ? '' : 'display:none;' ?>"
                                                >
                                                    <?= $initialCompareValue ? '¥' . number_format($initialCompareValue) : '' ?>
                                                </span>
                                            </div>
                                            <?php if ($product['stock'] > 0): ?>
                                                <span class="text-sm text-green-600" data-stock-label>在庫あり</span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </a>

                                <!-- ===== カート追加ボタンエリア ===== -->
                                <div class="px-4 pb-4 space-y-2">
                                    <?php if ($hasStock): ?>
                                        <?php
                                            $hasVariantChoices = !empty($product['variants']) && count($product['variants']) > 1;
                                        ?>
                                        <!-- 在庫がある場合: カート追加フォーム -->
                                        <form
                                            method="POST"
                                            action="<?= url('/cart/add') ?>"
                                            class="cart-add-form no-loading"
                                            data-api-endpoint="<?= url('/api/cart/add') ?>"
                                            data-product-name="<?= e($product['base_name'] ?? $product['name']) ?>"
                                        >
                                            <?= csrfField() ?>
                                            <input type="hidden" name="quantity" value="1">

                                            <?php if ($hasVariantChoices): ?>
                                                <!-- バリアント選択プルダウン -->
                                                <div class="mb-2">
                                                    <label class="block text-xs font-semibold text-gray-600 mb-1">タイプを選択</label>
                                                    <select 
                                                        name="product_id"
                                                        class="variant-selector w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-amber-500"
                                                        required
                                                    >
                                                        <?php foreach ($product['variants'] as $variant): ?>
                                                            <?php
                                                                $variantId = $variant['id'] ?? $variant['product_id'];
                                                                $isOut = ($variant['stock'] ?? 0) <= 0;
                                                            ?>
                                                            <option 
                                                                value="<?= e($variantId) ?>"
                                                                <?= $variantId == $defaultVariantId ? 'selected' : '' ?>
                                                                <?= $isOut ? 'disabled' : '' ?>
                                                            >
                                                                <?= h($variant['type_display'] ?? ($variant['type_name_ja'] ?? 'バリアント')) ?> - ¥<?= number_format($variant['price']) ?>
                                                                <?= $isOut ? '（在庫切れ）' : '' ?>
                                                            </option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>
                                            <?php else: ?>
                                                <input type="hidden" name="product_id" value="<?= e($defaultVariantId) ?>">
                                            <?php endif; ?>
                                            
                                            <button 
                                                type="submit" 
                                                class="w-full bg-gradient-to-r from-amber-600 to-orange-600 text-white font-semibold py-2 px-4 rounded-lg hover:from-amber-700 hover:to-orange-700 transition transform hover:scale-105"
                                            >
                                                カートに追加
                                            </button>
                                        </form>
                                    <?php else: ?>
                                        <!-- 在庫切れの場合: ボタンを無効化 -->
                                        <button 
                                            disabled 
                                            class="w-full bg-gray-300 text-gray-500 font-semibold py-2 px-4 rounded-lg cursor-not-allowed"
                                        >
                                            在庫切れ
                                        </button>
                                    <?php endif; ?>
                                    
                                    <!-- ===== 商品比較ボタン ===== -->
                                    <!-- 複数商品を比較リストに追加して比較ページで並べて確認 -->
                                    <button 
                                        type="button"
                                        data-compare-button
                                        data-product-id="<?= $product['id'] ?>"
                                        class="w-full bg-gray-200 text-gray-700 font-semibold py-2 px-4 rounded-lg hover:bg-gray-300 transition"
                                    >
                                        📊 比較する
                                    </button>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
                <!-- ===== ページネーション ===== -->
                <?php if (($paginationMeta['total_pages'] ?? 1) > 1): ?>
                    <?php
                        $currentPage = $paginationMeta['current_page'] ?? 1;
                        $totalPages = $paginationMeta['total_pages'] ?? 1;
                        $window = 2;
                        $startPage = max(1, $currentPage - $window);
                        $endPage = min($totalPages, $currentPage + $window);
                    ?>
                    <div class="mt-12 flex justify-center">
                        <nav class="flex items-center gap-2" aria-label="Pagination">
                            <a href="<?= productPaginationUrl(1) ?>" class="px-3 py-2 border rounded-md text-sm <?= $currentPage === 1 ? 'text-gray-300 cursor-not-allowed' : 'text-gray-600 hover:bg-gray-50' ?>" aria-label="最初のページ" <?= $currentPage === 1 ? 'tabindex="-1" aria-disabled="true"' : '' ?>>
                                &laquo;
                            </a>
                            <a href="<?= productPaginationUrl(max(1, $currentPage - 1)) ?>" class="px-3 py-2 border rounded-md text-sm <?= $currentPage === 1 ? 'text-gray-300 cursor-not-allowed' : 'text-gray-600 hover:bg-gray-50' ?>" aria-label="前のページ" <?= $currentPage === 1 ? 'tabindex="-1" aria-disabled="true"' : '' ?>>
                                &lsaquo;
                            </a>
                            <?php for ($i = $startPage; $i <= $endPage; $i++): ?>
                                <a href="<?= productPaginationUrl($i) ?>" class="px-3 py-2 border rounded-md text-sm <?= $i === $currentPage ? 'bg-amber-600 text-white border-amber-600' : 'text-gray-600 hover:bg-gray-50' ?>">
                                    <?= $i ?>
                                </a>
                            <?php endfor; ?>
                            <a href="<?= productPaginationUrl(min($totalPages, $currentPage + 1)) ?>" class="px-3 py-2 border rounded-md text-sm <?= $currentPage === $totalPages ? 'text-gray-300 cursor-not-allowed' : 'text-gray-600 hover:bg-gray-50' ?>" aria-label="次のページ" <?= $currentPage === $totalPages ? 'tabindex="-1" aria-disabled="true"' : '' ?>>
                                &rsaquo;
                            </a>
                            <a href="<?= productPaginationUrl($totalPages) ?>" class="px-3 py-2 border rounded-md text-sm <?= $currentPage === $totalPages ? 'text-gray-300 cursor-not-allowed' : 'text-gray-600 hover:bg-gray-50' ?>" aria-label="最後のページ" <?= $currentPage === $totalPages ? 'tabindex="-1" aria-disabled="true"' : '' ?>>
                                &raquo;
                            </a>
                        </nav>
                    </div>
                <?php endif; ?>
            </main>
        </div>
    </div>
</div>

<script>
window.productsPageConfig = {
    wishlistApi: {
        check: '<?= url('/api/wishlist/check') ?>',
        add: '<?= url('/api/wishlist/add') ?>',
        remove: '<?= url('/api/wishlist/remove') ?>'
    },
    compareAddUrl: '<?= url('/compare/add') ?>',
    comparePageUrl: '<?= url('/compare') ?>',
    loginUrl: '<?= url('/login') ?>',
    csrfToken: document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
};
</script>
<script src="<?= url('js/products-index.js') ?>"></script>

<?php require_once __DIR__ . '/../../layout/footer.php'; ?>
