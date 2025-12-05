<?php require_once __DIR__ . '/../layout/header.php'; ?>

<!-- ヒーローセクション -->
<section class="relative h-[600px] overflow-hidden">
    <!-- 背景画像 -->
    <div class="absolute inset-0">
        <img src="/gin_ec/images/hero.png" 
             alt="コーヒーのある暮らし" 
             class="w-full h-full object-cover"
             onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
        <!-- 画像が読み込めない場合のフォールバック -->
        <div style="display:none;" class="w-full h-full hero-fallback"></div>
    </div>
    
    <!-- グラデーションオーバーレイ -->
    <div class="absolute inset-0 bg-gradient-to-r from-black/40 via-black/20 to-transparent"></div>
    
    <!-- コンテンツ -->
    <div class="relative h-full container mx-auto px-4 flex items-center">
        <div class="max-w-2xl text-white">
            <h1 class="text-5xl md:text-6xl font-bold mb-6 leading-tight" style="text-shadow: 2px 2px 4px rgba(0,0,0,0.5);">
                あなたの「好き」に<br>合う一杯を
            </h1>
            <p class="text-xl md:text-2xl mb-8 leading-relaxed" style="text-shadow: 1px 1px 3px rgba(0,0,0,0.5);">
                30秒の味覚診断で、好みに合うコーヒー止まずを選定します。
            </p>
            <div class="flex flex-col sm:flex-row gap-4">
                <a href="<?= url('/diagnosis') ?>" 
                   class="inline-block bg-amber-600 hover:bg-amber-700 text-white font-bold py-4 px-8 rounded-lg transition-all duration-300 shadow-lg hover:shadow-xl text-center">
                   🎯 味覚診断を始める
                </a>
                <a href="<?= url('/products') ?>" 
                   class="inline-block bg-white/90 hover:bg-white text-amber-800 font-bold py-4 px-8 rounded-lg transition-all duration-300 shadow-lg hover:shadow-xl text-center">
                   商品一覧を見る
                </a>
            </div>
        </div>
    </div>
</section>

<!-- カテゴリー・検索バー -->
<section class="bg-white shadow-md sticky top-16 z-40">
    <div class="container mx-auto px-4 py-4">
        <div class="flex flex-wrap gap-4 items-center justify-between">
            <!-- カテゴリータブ -->
            <div class="flex gap-2 overflow-x-auto pb-2 sm:pb-0">
                <a href="<?= url('/products') ?>" class="px-4 py-2 rounded-full border border-amber-600 text-amber-600 hover:bg-amber-600 hover:text-white transition whitespace-nowrap">
                    すべて
                </a>
                <a href="<?= url('/products?category=coffee') ?>" class="px-4 py-2 rounded-full border border-gray-300 hover:border-amber-600 hover:text-amber-600 transition whitespace-nowrap">
                    コーヒー
                </a>
                <a href="<?= url('/products?category=decaf') ?>" class="px-4 py-2 rounded-full border border-gray-300 hover:border-amber-600 hover:text-amber-600 transition whitespace-nowrap">
                    カフェインレス
                </a>
                <a href="<?= url('/products?category=capsule') ?>" class="px-4 py-2 rounded-full border border-gray-300 hover:border-amber-600 hover:text-amber-600 transition whitespace-nowrap">
                    カプセル
                </a>
                <a href="<?= url('/products?category=gift') ?>" class="px-4 py-2 rounded-full border border-gray-300 hover:border-amber-600 hover:text-amber-600 transition whitespace-nowrap">
                    ギフト
                </a>
                <a href="<?= url('/products?favorite=1') ?>" class="px-4 py-2 rounded-full border border-gray-300 hover:border-amber-600 hover:text-amber-600 transition whitespace-nowrap">
                    お気に入り
                </a>
                <a href="<?= url('/products?sort=popular') ?>" class="px-4 py-2 rounded-full border border-gray-300 hover:border-amber-600 hover:text-amber-600 transition whitespace-nowrap">
                    人気順
                </a>
            </div>
        </div>
    </div>
</section>

<!-- おすすめ商品セクション -->
<?php if (!empty($recommendedProducts)): ?>
<section class="container mx-auto px-4 py-16">
    <div class="mb-12">
        <div class="flex items-center gap-3 mb-3">
            <div class="w-1 h-8 bg-amber-600"></div>
            <h2 class="text-3xl font-bold">
                <?= isLoggedIn() ? 'こんな時間に、こんな一杯を' : '探しやすい選択' ?>
            </h2>
        </div>
        <?php if (isLoggedIn()): ?>
            <p class="text-gray-600 ml-4">あなたの味覚診断結果に基づいたおすすめです</p>
        <?php endif; ?>
    </div>
    
    <!-- シーン別提案 -->
    <?php if (isLoggedIn()): ?>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-12">
        <a href="<?= url('/products?scene=morning') ?>" class="group">
            <div class="aspect-square rounded-2xl overflow-hidden mb-3 relative">
                <img src="<?= url('images/no-image.png') ?>" 
                     alt="モーニング" 
                     class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent flex items-end p-4">
                    <span class="text-white font-bold text-lg">モーニング</span>
                </div>
            </div>
        </a>
        <a href="<?= url('/products?scene=work') ?>" class="group">
            <div class="aspect-square rounded-2xl overflow-hidden mb-3 relative">
                <img src="<?= url('images/no-image.png') ?>" 
                     alt="仕事" 
                     class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent flex items-end p-4">
                    <span class="text-white font-bold text-lg">仕事</span>
                </div>
            </div>
        </a>
        <a href="<?= url('/products?scene=relax') ?>" class="group">
            <div class="aspect-square rounded-2xl overflow-hidden mb-3 relative">
                <img src="<?= url('images/no-image.png') ?>" 
                     alt="夜" 
                     class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent flex items-end p-4">
                    <span class="text-white font-bold text-lg">夜</span>
                </div>
            </div>
        </a>
        <a href="<?= url('/products') ?>" class="group flex items-center justify-center bg-gray-100 rounded-2xl hover:bg-gray-200 transition">
            <div class="text-center">
                <div class="text-4xl mb-2">📄</div>
                <span class="text-gray-700 font-bold">一覧を見る</span>
            </div>
        </a>
    </div>
    <?php endif; ?>
    
    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4 md:gap-6">
        <?php foreach ($recommendedProducts as $product): ?>
            <?php
            $imageUrl = $product['image_url'] ?? '/images/no-image.png';
            ?>
            <div class="group">
                <!-- 商品画像 -->
                <a href="<?= url('products/' . $product['id']) ?>">
                    <div class="aspect-square bg-gray-100 overflow-hidden rounded-2xl mb-3">
                        <img src="<?= htmlspecialchars($imageUrl) ?>" 
                             alt="<?= htmlspecialchars($product['name']) ?>"
                             class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                    </div>
                </a>
                
                <div>
                    <!-- 商品名 -->
                    <h3 class="font-bold text-sm md:text-base mb-1 line-clamp-2 min-h-[2.5rem]">
                        <a href="<?= url('products/' . $product['id']) ?>" class="hover:text-amber-600 transition">
                            <?= htmlspecialchars($product['name']) ?>
                        </a>
                    </h3>
                    
                    <!-- 価格 -->
                    <p class="text-amber-600 font-bold text-lg">
                        ¥<?= number_format($product['price']) ?>
                    </p>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</section>
<?php endif; ?>

<!-- セール商品 -->
<?php if (!empty($saleProducts)): ?>
<section class="bg-gradient-to-r from-red-50 to-orange-50 py-16">
    <div class="container mx-auto px-4">
        <div class="mb-12 text-center">
            <div class="inline-flex items-center gap-3 mb-3">
                <span class="text-5xl">🔥</span>
                <h2 class="text-4xl font-bold text-red-600">期間限定セール</h2>
                <span class="text-5xl">🔥</span>
            </div>
            <p class="text-gray-700 text-lg">今だけのお得な価格でお届けします</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach ($saleProducts as $product): ?>
                <?php 
                $hasDiscount = !empty($product['discount_price']) && $product['discount_price'] < $product['price'];
                $discountRate = $hasDiscount ? round((($product['price'] - $product['discount_price']) / $product['price']) * 100) : 0;
                ?>
                <div class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 relative">
                    <!-- セールバッジ -->
                    <div class="absolute top-4 left-4 z-10">
                        <span class="bg-red-600 text-white font-bold px-4 py-2 rounded-full text-sm shadow-lg animate-pulse">
                            <?= $discountRate ?>% OFF
                        </span>
                    </div>
                    
                    <!-- 商品画像 -->
                    <div class="relative h-64 overflow-hidden bg-gray-100">
                        <a href="<?= url('/products/' . $product['id']) ?>">
                            <img src="<?= e($product['image_url'] ?? '/images/no-image.png') ?>" 
                                 alt="<?= e($product['name']) ?>"
                                 class="w-full h-full object-cover hover:scale-110 transition-transform duration-300">
                        </a>
                    </div>

                    <!-- 商品情報 -->
                    <div class="p-6">
                        <a href="<?= url('/products/' . $product['id']) ?>" 
                           class="block hover:text-amber-600 transition-colors">
                            <h3 class="text-xl font-bold mb-2"><?= e($product['name']) ?></h3>
                        </a>
                        
                        <!-- カテゴリー -->
                        <p class="text-sm text-gray-500 mb-3">
                            <?= $product['category'] === 'coffee' ? 'コーヒー' : ($product['category'] === 'goods' ? 'グッズ' : 'その他') ?>
                        </p>

                        <!-- 価格 -->
                        <div class="mb-4">
                            <div class="flex items-baseline gap-3">
                                <span class="text-3xl font-bold text-red-600">
                                    ¥<?= number_format($hasDiscount ? $product['discount_price'] : $product['price']) ?>
                                </span>
                                <?php if ($hasDiscount): ?>
                                <span class="text-lg text-gray-400 line-through">
                                    ¥<?= number_format($product['price']) ?>
                                </span>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- 在庫状況 -->
                        <?php if ($product['stock'] <= 0): ?>
                            <span class="inline-block px-3 py-1 bg-gray-100 text-gray-500 rounded-full text-sm">
                                在庫切れ
                            </span>
                        <?php elseif ($product['stock'] <= 10): ?>
                            <span class="inline-block px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-sm">
                                残り<?= $product['stock'] ?>個
                            </span>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="text-center mt-8">
            <a href="<?= url('/products?sale=1') ?>" 
               class="inline-block bg-red-600 text-white font-bold px-8 py-3 rounded-lg hover:bg-red-700 transition-colors shadow-lg">
                セール商品をもっと見る →
            </a>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- 人気商品 -->
<?php if (!empty($popularProducts)): ?>
<section class="container mx-auto px-4 py-16">
    <div class="mb-12">
        <div class="flex items-center gap-3 mb-3">
            <div class="w-1 h-8 bg-amber-600"></div>
            <h2 class="text-3xl font-bold">人気商品ランキング</h2>
        </div>
        <p class="text-gray-600 ml-4">みんなが選ぶ、人気のコーヒー豆TOP5</p>
    </div>
    
    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4 md:gap-6">
        <?php foreach ($popularProducts as $index => $product): ?>
            <?php
            $imageUrl = $product['image_url'] ?? '/images/no-image.png';
            ?>
            <div class="group relative">
                <!-- ランキングバッジ -->
                <div class="absolute -top-2 -left-2 z-10 w-12 h-12 bg-gradient-to-br from-amber-400 to-amber-600 rounded-full flex items-center justify-center shadow-lg">
                    <span class="text-white font-bold text-lg"><?= $index + 1 ?></span>
                </div>
                
                <!-- 商品画像 -->
                <a href="<?= url('products/' . $product['id']) ?>">
                    <div class="aspect-square bg-gray-100 overflow-hidden rounded-2xl mb-3">
                        <img src="<?= htmlspecialchars($imageUrl) ?>" 
                             alt="<?= htmlspecialchars($product['name']) ?>"
                             class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                    </div>
                </a>
                
                <div>
                    <!-- 商品名 -->
                    <h3 class="font-bold text-sm md:text-base mb-1 line-clamp-2 min-h-[2.5rem]">
                        <a href="<?= url('products/' . $product['id']) ?>" class="hover:text-amber-600 transition">
                            <?= htmlspecialchars($product['name']) ?>
                        </a>
                    </h3>
                    
                    <!-- 価格 -->
                    <p class="text-amber-600 font-bold text-lg">
                        ¥<?= number_format($product['price']) ?>
                    </p>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</section>
<?php endif; ?>

<!-- 新着商品 -->
<?php if (!empty($newProducts)): ?>
<section class="bg-amber-50 py-16">
    <div class="container mx-auto px-4">
        <div class="flex items-center gap-3 mb-8">
            <div class="w-1 h-8 bg-amber-600"></div>
            <h2 class="text-3xl font-bold">新着商品</h2>
        </div>
        <p class="text-gray-600 mb-8 ml-4">新しく入荷した商品をチェック</p>
        
        <div class="bg-white rounded-2xl p-6 md:p-8 shadow-sm">
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4 md:gap-6">
                <?php foreach (array_slice($newProducts, 0, 5) as $product): ?>
                    <?php
                    $imageUrl = $product['image_url'] ?? '/images/no-image.png';
                    ?>
                    <div class="group">
                        <!-- 商品画像 -->
                        <a href="<?= url('products/' . $product['id']) ?>">
                            <div class="aspect-square bg-gray-100 overflow-hidden rounded-2xl mb-3 relative">
                                <img src="<?= htmlspecialchars($imageUrl) ?>" 
                                     alt="<?= htmlspecialchars($product['name']) ?>"
                                     class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                                <!-- NEWバッジ -->
                                <span class="absolute top-2 left-2 bg-red-500 text-white text-xs font-bold px-2 py-1 rounded">NEW</span>
                            </div>
                        </a>
                        
                        <div>
                            <!-- 商品名 -->
                            <h3 class="font-bold text-sm md:text-base mb-1 line-clamp-2 min-h-[2.5rem]">
                                <a href="<?= url('products/' . $product['id']) ?>" class="hover:text-amber-600 transition">
                                    <?= htmlspecialchars($product['name']) ?>
                                </a>
                            </h3>
                            
                            <!-- 価格 -->
                            <p class="text-amber-600 font-bold text-lg">
                                ¥<?= number_format($product['price']) ?>
                            </p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- ライフスタイル × コーヒー欄 -->
<section class="container mx-auto px-4 py-16">
    <div class="flex items-center gap-3 mb-8">
        <div class="w-1 h-8 bg-amber-600"></div>
        <h2 class="text-3xl font-bold">ライフスタイル × コーヒー欄</h2>
    </div>
    <p class="text-gray-600 mb-8 ml-4">今の気分は少女性方</p>
    
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- ブログ記事風カード -->
        <a href="#" class="group bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-md transition">
            <div class="aspect-video bg-gradient-to-br from-amber-100 to-orange-100 overflow-hidden flex items-center justify-center">
                <img src="https://placehold.co/600x400/fef3c7/92400e?text=Coffee+Guide" 
                     alt="コーヒーの楽しみ方" 
                     class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
            </div>
            <div class="p-4">
                <h3 class="font-bold text-lg mb-2 group-hover:text-amber-600 transition">
                    おうちカフェの楽しみ方
                </h3>
                <p class="text-gray-600 text-sm line-clamp-2">
                    自宅で本格的なコーヒーを楽しむためのヒントをご紹介します。
                </p>
            </div>
        </a>
        
        <a href="#" class="group bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-md transition">
            <div class="aspect-video bg-gradient-to-br from-orange-100 to-amber-100 overflow-hidden flex items-center justify-center">
                <img src="https://placehold.co/600x400/fed7aa/92400e?text=Coffee+Beans" 
                     alt="コーヒー豆の選び方" 
                     class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
            </div>
            <div class="p-4">
                <h3 class="font-bold text-lg mb-2 group-hover:text-amber-600 transition">
                    コーヒー豆の選び方講座
                </h3>
                <p class="text-gray-600 text-sm line-clamp-2">
                    産地や焙煎度で変わる味わいの違いを詳しく解説します。
                </p>
            </div>
        </a>
        
        <a href="#" class="group bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-md transition">
            <div class="aspect-video bg-gradient-to-br from-yellow-100 to-amber-100 overflow-hidden flex items-center justify-center">
                <img src="https://placehold.co/600x400/fde68a/92400e?text=Brewing+Tips" 
                     alt="淹れ方のコツ" 
                     class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
            </div>
            <div class="p-4">
                <h3 class="font-bold text-lg mb-2 group-hover:text-amber-600 transition">
                    プロが教える淹れ方のコツ
                </h3>
                <p class="text-gray-600 text-sm line-clamp-2">
                    ドリップからエスプレッソまで、美味しく淹れる秘訣をご紹介。
                </p>
            </div>
        </a>
    </div>
</section>

<!-- SNS投稿エリア -->
<section class="bg-gray-50 py-16">
    <div class="container mx-auto px-4">
        <div class="flex items-center gap-3 mb-4">
            <div class="w-1 h-8 bg-amber-600"></div>
            <h2 class="text-3xl font-bold">SNS投稿アカ</h2>
        </div>
        <p class="text-gray-600 mb-8 ml-4">#おうちかふぇ で実施の名言酔を歩子敷います</p>
        
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
            <?php 
            $snsImages = [
                'https://placehold.co/400x400/fef3c7/92400e?text=Coffee+1',
                'https://placehold.co/400x400/fed7aa/92400e?text=Coffee+2',
                'https://placehold.co/400x400/fde68a/92400e?text=Coffee+3',
                'https://placehold.co/400x400/fdba74/92400e?text=Coffee+4',
                'https://placehold.co/400x400/fb923c/92400e?text=Coffee+5',
                'https://placehold.co/400x400/f97316/92400e?text=Coffee+6'
            ];
            for ($i = 0; $i < 6; $i++): 
            ?>
                <a href="#" class="group aspect-square bg-gradient-to-br from-amber-100 to-orange-100 rounded-2xl overflow-hidden shadow-sm hover:shadow-md transition">
                    <img src="<?= $snsImages[$i] ?>" 
                         alt="SNS投稿<?= $i + 1 ?>" 
                         class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                </a>
            <?php endfor; ?>
        </div>
    </div>
</section>

<!-- 味覚診断CTAセクション -->
<section class="container mx-auto px-4 py-16">
    <div class="relative bg-gradient-to-br from-amber-100 via-orange-50 to-amber-50 rounded-3xl overflow-hidden">
        <!-- 背景装飾 -->
        <div class="absolute top-0 right-0 w-64 h-64 bg-amber-200/30 rounded-full -translate-y-32 translate-x-32"></div>
        <div class="absolute bottom-0 left-0 w-96 h-96 bg-orange-200/20 rounded-full translate-y-48 -translate-x-48"></div>
        
        <div class="relative p-8 md:p-12 text-center">
            <div class="max-w-2xl mx-auto">
                <div class="text-6xl mb-6">📄</div>
                <h2 class="text-3xl md:text-4xl font-bold mb-4 text-gray-800">
                    口の交流資料をコーヒー一杯
                </h2>
                <p class="text-lg md:text-xl text-gray-700 mb-8 leading-relaxed">
                    あなたの「好き」に合う一杯を<br>
                    30秒の味覚診断でお選びします
                </p>
                <a href="<?= url('/diagnosis') ?>" 
                   class="inline-block bg-amber-600 hover:bg-amber-700 text-white font-bold py-4 px-10 rounded-full transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                    🎯 味覚診断を始める →
                </a>
            </div>
        </div>
    </div>
</section>

<!-- 特徴セクション -->
<section class="bg-white py-16">
    <div class="container mx-auto px-4">
        <h2 class="text-3xl font-bold text-center mb-12">
            おうちかふぇが選ばれる理由
        </h2>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- 特徴1 -->
            <div class="text-center p-6">
                <div class="w-20 h-20 bg-amber-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <span class="text-4xl">🎯</span>
                </div>
                <h3 class="text-xl font-bold mb-3">味覚診断</h3>
                <p class="text-gray-600 leading-relaxed">
                    あなたの好みに合わせて<br>
                    最適な商品を提案します
                </p>
            </div>
            
            <!-- 特徴2 -->
            <div class="text-center p-6">
                <div class="w-20 h-20 bg-amber-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <span class="text-4xl">📦</span>
                </div>
                <h3 class="text-xl font-bold mb-3">迅速配送</h3>
                <p class="text-gray-600 leading-relaxed">
                    ご注文から最短翌日<br>
                    新鮮な状態でお届け
                </p>
            </div>
            
            <!-- 特徴3 -->
            <div class="text-center p-6">
                <div class="w-20 h-20 bg-amber-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <span class="text-4xl">🌟</span>
                </div>
                <h3 class="text-xl font-bold mb-3">厳選豆</h3>
                <p class="text-gray-600 leading-relaxed">
                    世界中から厳選した<br>
                    高品質なコーヒー豆
                </p>
            </div>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
