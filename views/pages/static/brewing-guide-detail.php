<div class="min-h-screen bg-gradient-to-b from-white via-amber-50/40 to-white py-12 brewing-guide-hero">
    <div class="container mx-auto px-4 max-w-4xl">
        <a href="<?= url('/brewing-guide') ?>" class="inline-flex items-center text-sm font-semibold text-amber-600 hover:text-amber-700 mb-6">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
            </svg>
            淹れ方ガイド一覧に戻る
        </a>

        <div class="bg-white rounded-3xl shadow-xl border border-amber-100 overflow-hidden brewing-guide-card">
            <div class="bg-amber-600/90 text-white px-8 py-10">
                <p class="text-sm uppercase tracking-[0.4em] text-amber-100 mb-2">Brewing Method</p>
                <h1 class="text-4xl md:text-5xl font-bold mb-4">
                    <?= htmlspecialchars($guide['name'] ?? '淹れ方ガイド', ENT_QUOTES, 'UTF-8') ?>
                </h1>
                <div class="flex flex-wrap gap-4 text-sm">
                    <span class="inline-flex items-center bg-white/20 rounded-full px-4 py-1.5">
                        難易度: <?= htmlspecialchars($guide['difficulty'] ?? '-', ENT_QUOTES, 'UTF-8') ?>
                    </span>
                    <span class="inline-flex items-center bg-white/20 rounded-full px-4 py-1.5">
                        所要時間: <?= htmlspecialchars($guide['time'] ?? '-', ENT_QUOTES, 'UTF-8') ?>
                    </span>
                    <?php if (!empty($guide['servings'])): ?>
                        <span class="inline-flex items-center bg-white/20 rounded-full px-4 py-1.5">
                            目安杯数: <?= htmlspecialchars($guide['servings'], ENT_QUOTES, 'UTF-8') ?>
                        </span>
                    <?php endif; ?>
                </div>
            </div>

            <div class="p-8 space-y-10">
                <section class="grid gap-6 md:grid-cols-2">
                    <div class="border border-gray-100 rounded-2xl p-6 bg-gray-50/60">
                        <h2 class="text-lg font-bold text-gray-900 mb-4">必要な道具</h2>
                        <ul class="space-y-2 text-gray-700">
                            <?php foreach (($guide['tools'] ?? []) as $tool): ?>
                                <li class="flex items-start">
                                    <span class="text-amber-500 mr-2">•</span>
                                    <span><?= htmlspecialchars($tool, ENT_QUOTES, 'UTF-8') ?></span>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                    <div class="border border-gray-100 rounded-2xl p-6 bg-gray-50/60">
                        <h2 class="text-lg font-bold text-gray-900 mb-4">材料</h2>
                        <ul class="space-y-2 text-gray-700">
                            <?php foreach (($guide['ingredients'] ?? []) as $ingredient): ?>
                                <li class="flex items-start">
                                    <span class="text-amber-500 mr-2">•</span>
                                    <span><?= htmlspecialchars($ingredient, ENT_QUOTES, 'UTF-8') ?></span>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </section>

                <section class="border border-amber-100 rounded-3xl p-6 bg-white shadow-inner">
                    <h2 class="text-lg font-bold text-gray-900 mb-4">抽出手順</h2>
                    <ol class="space-y-4">
                        <?php foreach (($guide['steps'] ?? []) as $index => $step): ?>
                            <li class="flex items-start">
                                <div class="flex-shrink-0 w-8 h-8 rounded-full bg-amber-500 text-white flex items-center justify-center font-semibold mr-4">
                                    <?= $index + 1 ?>
                                </div>
                                <p class="text-gray-700 leading-relaxed">
                                    <?= htmlspecialchars($step, ENT_QUOTES, 'UTF-8') ?>
                                </p>
                            </li>
                        <?php endforeach; ?>
                    </ol>
                </section>

                <?php if (!empty($guide['tips'])): ?>
                    <section class="bg-amber-50 border border-amber-100 rounded-3xl p-6">
                        <h2 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                            <svg class="w-5 h-5 text-amber-500 mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            おいしく淹れるコツ
                        </h2>
                        <ul class="space-y-2 text-gray-700">
                            <?php foreach ($guide['tips'] as $tip): ?>
                                <li class="flex items-start">
                                    <span class="text-amber-500 mr-2">✦</span>
                                    <span><?= htmlspecialchars($tip, ENT_QUOTES, 'UTF-8') ?></span>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </section>
                <?php endif; ?>

                <div class="text-center pt-4">
                    <a href="<?= url('/products?scene=morning') ?>" class="inline-flex items-center px-6 py-3 rounded-2xl font-semibold bg-gray-900 text-white hover:bg-black transition">
                        抽出に合う豆を探す
                        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
