<?php require_once __DIR__ . '/../../layout/header.php'; ?>

<div class="min-h-screen bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold text-gray-900 mb-4">
                🏆 <?= e($periodLabel) ?>ランキング
            </h1>
            <p class="text-gray-600">人気の商品をチェック！</p>
        </div>

        <!-- Filter Tabs -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-8">
            <div class="flex flex-col md:flex-row gap-4 items-center justify-between">
                <!-- Period Filter -->
                <div class="flex items-center gap-2 flex-wrap">
                    <span class="text-sm font-medium text-gray-700 mr-2">期間:</span>
                    <a href="<?= url('/products/ranking?period=week' . ($category ? '&category=' . $category : '')) ?>" 
                       class="px-4 py-2 rounded-full transition <?= $period === 'week' ? 'bg-amber-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' ?>">
                        週間
                    </a>
                    <a href="<?= url('/products/ranking?period=month' . ($category ? '&category=' . $category : '')) ?>" 
                       class="px-4 py-2 rounded-full transition <?= $period === 'month' ? 'bg-amber-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' ?>">
                        月間
                    </a>
                    <a href="<?= url('/products/ranking?period=all' . ($category ? '&category=' . $category : '')) ?>" 
                       class="px-4 py-2 rounded-full transition <?= $period === 'all' ? 'bg-amber-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' ?>">
                        全期間
                    </a>
                </div>

                <!-- Category Filter -->
                <div class="flex items-center gap-2 flex-wrap">
                    <span class="text-sm font-medium text-gray-700 mr-2">カテゴリ:</span>
                    <a href="<?= url('/products/ranking?period=' . $period) ?>" 
                       class="px-3 py-2 text-sm rounded-full transition <?= !$category ? 'bg-amber-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' ?>">
                        すべて
                    </a>
                    <a href="<?= url('/products/ranking?period=' . $period . '&category=coffee') ?>" 
                       class="px-3 py-2 text-sm rounded-full transition <?= $category === 'coffee' ? 'bg-amber-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' ?>">
                        コーヒー
                    </a>
                    <a href="<?= url('/products/ranking?period=' . $period . '&category=tea') ?>" 
                       class="px-3 py-2 text-sm rounded-full transition <?= $category === 'tea' ? 'bg-amber-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' ?>">
                        お茶
                    </a>
                    <a href="<?= url('/products/ranking?period=' . $period . '&category=other') ?>" 
                       class="px-3 py-2 text-sm rounded-full transition <?= $category === 'other' ? 'bg-amber-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' ?>">
                        その他
                    </a>
                </div>
            </div>
        </div>

        <!-- Ranking List -->
        <?php if (empty($products)): ?>
            <div class="bg-white rounded-lg shadow-md p-12 text-center">
                <svg class="w-24 h-24 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
                <h3 class="text-xl font-semibold text-gray-700 mb-2">該当する商品がありません</h3>
                <p class="text-gray-500 mb-6">条件を変更してお試しください</p>
                <a href="<?= url('/products/ranking') ?>" class="inline-block bg-amber-600 text-white px-6 py-2 rounded-lg hover:bg-amber-700 transition">
                    全ランキングを見る
                </a>
            </div>
        <?php else: ?>
            <div class="space-y-4">
                <?php foreach ($products as $index => $product): 
                    $rank = $offset + $index + 1;
                    $medalColors = [
                        1 => 'bg-gradient-to-br from-yellow-400 to-yellow-600',
                        2 => 'bg-gradient-to-br from-gray-300 to-gray-500',
                        3 => 'bg-gradient-to-br from-amber-600 to-amber-800'
                    ];
                    $medalColor = $medalColors[$rank] ?? 'bg-amber-100 text-amber-800';
                ?>
                    <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition overflow-hidden">
                        <div class="flex gap-6 p-6">
                            <!-- Rank Badge -->
                            <div class="flex-shrink-0 flex items-center">
                                <?php if ($rank <= 3): ?>
                                    <div class="w-16 h-16 <?= $medalColor ?> rounded-full flex items-center justify-center text-white font-bold text-2xl shadow-lg">
                                        <?= $rank ?>
                                    </div>
                                <?php else: ?>
                                    <div class="w-16 h-16 <?= $medalColor ?> rounded-full flex items-center justify-center font-bold text-xl">
                                        <?= $rank ?>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <!-- Product Image -->
                            <div class="flex-shrink-0">
                                <a href="<?= url('/products/' . $product['id']) ?>">
                                    <?php if (!empty($product['image_url'])): ?>
                                        <img src="<?= e($product['image_url']) ?>" 
                                             alt="<?= e($product['name']) ?>" 
                                             class="w-24 h-24 object-cover rounded-lg"
                                             onerror="this.src='<?= url('images/no-image.png') ?>'">
                                    <?php else: ?>
                                        <div class="w-24 h-24 bg-gray-200 rounded-lg flex items-center justify-center">
                                            <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                        </div>
                                    <?php endif; ?>
                                </a>
                            </div>

                            <!-- Product Info -->
                            <div class="flex-1 min-w-0">
                                <a href="<?= url('/products/' . $product['id']) ?>" class="block group">
                                    <h3 class="text-lg font-bold text-gray-900 mb-2 group-hover:text-amber-600 transition">
                                        <?= e($product['name']) ?>
                                    </h3>
                                    <p class="text-gray-600 text-sm mb-3 line-clamp-2">
                                        <?= e(mb_substr($product['description'] ?? '', 0, 100)) ?>...
                                    </p>
                                    <div class="flex items-center gap-4 text-sm">
                                        <span class="text-amber-600 font-bold text-xl">
                                            ¥<?= number_format($product['price']) ?>
                                        </span>
                                        <span class="text-gray-500">
                                            注文数: <?= number_format($product['order_count']) ?>件
                                        </span>
                                        <span class="text-gray-500">
                                            販売数: <?= number_format($product['total_quantity']) ?>個
                                        </span>
                                    </div>
                                </a>
                            </div>

                            <!-- Action Button -->
                            <div class="flex-shrink-0 flex items-center">
                                <a href="<?= url('/products/' . $product['id']) ?>" 
                                   class="px-6 py-3 bg-amber-600 hover:bg-amber-700 text-white font-semibold rounded-lg transition">
                                    詳細を見る
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Pagination -->
            <?php if ($totalPages > 1): ?>
                <div class="mt-8 flex justify-center">
                    <nav class="flex gap-2">
                        <?php if ($page > 1): ?>
                            <a href="<?= url('/products/ranking?period=' . $period . ($category ? '&category=' . $category : '') . '&page=' . ($page - 1)) ?>" 
                               class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                                前へ
                            </a>
                        <?php endif; ?>
                        
                        <?php for ($i = max(1, $page - 2); $i <= min($totalPages, $page + 2); $i++): ?>
                            <a href="<?= url('/products/ranking?period=' . $period . ($category ? '&category=' . $category : '') . '&page=' . $i) ?>" 
                               class="px-4 py-2 border rounded-lg transition <?= $i === $page ? 'bg-amber-600 text-white border-amber-600' : 'border-gray-300 hover:bg-gray-50' ?>">
                                <?= $i ?>
                            </a>
                        <?php endfor; ?>
                        
                        <?php if ($page < $totalPages): ?>
                            <a href="<?= url('/products/ranking?period=' . $period . ($category ? '&category=' . $category : '') . '&page=' . ($page + 1)) ?>" 
                               class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                                次へ
                            </a>
                        <?php endif; ?>
                    </nav>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</div>

<?php require_once __DIR__ . '/../../layout/footer.php'; ?>
