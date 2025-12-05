<div class="min-h-screen bg-gray-50 py-12">
    <div class="container mx-auto px-4 max-w-4xl">
        <!-- ページヘッダー -->
        <div class="mb-8">
            <h1 class="text-4xl font-bold text-gray-900 mb-2">よくある質問</h1>
            <p class="text-gray-600">Frequently Asked Questions</p>
        </div>

        <!-- カテゴリナビゲーション -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-8">
            <h2 class="text-xl font-bold text-gray-900 mb-4">カテゴリから探す</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <a href="#order" class="flex items-center p-4 border border-gray-200 rounded-lg hover:border-amber-600 hover:bg-amber-50 transition">
                    <svg class="w-6 h-6 text-amber-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                    </svg>
                    <div>
                        <p class="font-semibold text-gray-900">注文について</p>
                        <p class="text-sm text-gray-600"><?php echo count($faq['order']); ?>件の質問</p>
                    </div>
                </a>
                <a href="#shipping" class="flex items-center p-4 border border-gray-200 rounded-lg hover:border-amber-600 hover:bg-amber-50 transition">
                    <svg class="w-6 h-6 text-amber-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path>
                    </svg>
                    <div>
                        <p class="font-semibold text-gray-900">配送について</p>
                        <p class="text-sm text-gray-600"><?php echo count($faq['shipping']); ?>件の質問</p>
                    </div>
                </a>
                <a href="#product" class="flex items-center p-4 border border-gray-200 rounded-lg hover:border-amber-600 hover:bg-amber-50 transition">
                    <svg class="w-6 h-6 text-amber-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                    <div>
                        <p class="font-semibold text-gray-900">商品について</p>
                        <p class="text-sm text-gray-600"><?php echo count($faq['product']); ?>件の質問</p>
                    </div>
                </a>
                <a href="#account" class="flex items-center p-4 border border-gray-200 rounded-lg hover:border-amber-600 hover:bg-amber-50 transition">
                    <svg class="w-6 h-6 text-amber-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    <div>
                        <p class="font-semibold text-gray-900">会員登録について</p>
                        <p class="text-sm text-gray-600"><?php echo count($faq['account']); ?>件の質問</p>
                    </div>
                </a>
            </div>
        </div>

        <!-- FAQ内容 -->
        <div class="space-y-8">
            <!-- 注文について -->
            <section id="order" class="bg-white rounded-lg shadow-md p-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-6 border-b-2 border-amber-600 pb-2">
                    <svg class="w-6 h-6 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                    </svg>
                    注文について
                </h2>
                <div class="space-y-6">
                    <?php foreach ($faq['order'] as $index => $item): ?>
                    <div class="border-l-4 border-amber-600 pl-6 py-2">
                        <p class="font-bold text-lg text-gray-900 mb-2">
                            <span class="text-amber-600 mr-2">Q<?php echo $index + 1; ?>.</span>
                            <?php echo $item['q']; ?>
                        </p>
                        <p class="text-gray-700 leading-relaxed ml-8">
                            <span class="text-amber-600 font-bold mr-2">A.</span>
                            <?php echo $item['a']; ?>
                        </p>
                    </div>
                    <?php endforeach; ?>
                </div>
            </section>

            <!-- 配送について -->
            <section id="shipping" class="bg-white rounded-lg shadow-md p-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-6 border-b-2 border-amber-600 pb-2">
                    <svg class="w-6 h-6 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path>
                    </svg>
                    配送について
                </h2>
                <div class="space-y-6">
                    <?php foreach ($faq['shipping'] as $index => $item): ?>
                    <div class="border-l-4 border-amber-600 pl-6 py-2">
                        <p class="font-bold text-lg text-gray-900 mb-2">
                            <span class="text-amber-600 mr-2">Q<?php echo $index + 1; ?>.</span>
                            <?php echo $item['q']; ?>
                        </p>
                        <p class="text-gray-700 leading-relaxed ml-8">
                            <span class="text-amber-600 font-bold mr-2">A.</span>
                            <?php echo $item['a']; ?>
                        </p>
                    </div>
                    <?php endforeach; ?>
                </div>
            </section>

            <!-- 商品について -->
            <section id="product" class="bg-white rounded-lg shadow-md p-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-6 border-b-2 border-amber-600 pb-2">
                    <svg class="w-6 h-6 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                    商品について
                </h2>
                <div class="space-y-6">
                    <?php foreach ($faq['product'] as $index => $item): ?>
                    <div class="border-l-4 border-amber-600 pl-6 py-2">
                        <p class="font-bold text-lg text-gray-900 mb-2">
                            <span class="text-amber-600 mr-2">Q<?php echo $index + 1; ?>.</span>
                            <?php echo $item['q']; ?>
                        </p>
                        <p class="text-gray-700 leading-relaxed ml-8">
                            <span class="text-amber-600 font-bold mr-2">A.</span>
                            <?php echo $item['a']; ?>
                        </p>
                    </div>
                    <?php endforeach; ?>
                </div>
            </section>

            <!-- 会員登録について -->
            <section id="account" class="bg-white rounded-lg shadow-md p-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-6 border-b-2 border-amber-600 pb-2">
                    <svg class="w-6 h-6 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    会員登録について
                </h2>
                <div class="space-y-6">
                    <?php foreach ($faq['account'] as $index => $item): ?>
                    <div class="border-l-4 border-amber-600 pl-6 py-2">
                        <p class="font-bold text-lg text-gray-900 mb-2">
                            <span class="text-amber-600 mr-2">Q<?php echo $index + 1; ?>.</span>
                            <?php echo $item['q']; ?>
                        </p>
                        <p class="text-gray-700 leading-relaxed ml-8">
                            <span class="text-amber-600 font-bold mr-2">A.</span>
                            <?php echo $item['a']; ?>
                        </p>
                    </div>
                    <?php endforeach; ?>
                </div>
            </section>
        </div>

        <!-- お問い合わせCTA -->
        <div class="bg-amber-600 text-white rounded-lg shadow-md p-8 mt-8 text-center">
            <h2 class="text-2xl font-bold mb-4">解決しない場合は、お気軽にお問い合わせください</h2>
            <p class="mb-6">カスタマーサポートが丁寧にお答えいたします</p>
            <div class="flex flex-col md:flex-row gap-4 justify-center">
                <a href="mailto:info@ouchicafe.jp" class="bg-white text-amber-600 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition">
                    <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                    メールでお問い合わせ
                </a>
                <a href="tel:03-1234-5678" class="bg-white text-amber-600 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition">
                    <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                    </svg>
                    電話でお問い合わせ
                </a>
            </div>
            <p class="text-sm mt-4 opacity-90">受付時間: 平日 10:00〜17:00（土日祝日・年末年始を除く）</p>
        </div>

        <!-- 戻るリンク -->
        <div class="mt-8 text-center">
            <a href="<?= url('/') ?>" class="text-amber-600 hover:text-amber-700 font-semibold">
                ← トップページに戻る
            </a>
        </div>
    </div>
</div>

<script src="<?= url('js/smooth-scroll-anchors.js') ?>?v=20251205"></script>
