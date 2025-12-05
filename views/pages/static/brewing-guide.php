<div class="min-h-screen bg-amber-50/30 py-12 brewing-guide-hero">
    <div class="container mx-auto px-4 max-w-6xl">
        <div class="text-center mb-10">
            <p class="text-sm uppercase tracking-[0.4em] text-amber-500 mb-2">Brewing Guide</p>
            <h1 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4">淹れ方ガイド</h1>
            <p class="text-gray-600 leading-relaxed">
                抽出器具やシーンに合わせて、おすすめの淹れ方をまとめました。<br class="hidden md:block">
                難易度・所要時間・味わいの特徴を確認して、今日の一杯をもっと楽しく。
            </p>
        </div>

        <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
            <?php foreach (($methods ?? []) as $method): ?>
                <article class="group bg-white rounded-2xl shadow-sm hover:shadow-xl transition-shadow border border-amber-100/60 p-6 flex flex-col brewing-guide-card">
                    <div class="flex items-center justify-between mb-6">
                        <div class="text-4xl" aria-hidden="true"><?= htmlspecialchars($method['icon'], ENT_QUOTES, 'UTF-8') ?></div>
                        <span class="inline-flex items-center text-xs font-semibold px-3 py-1 rounded-full bg-amber-100 text-amber-700">
                            <?= htmlspecialchars($method['difficulty'], ENT_QUOTES, 'UTF-8') ?>
                        </span>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900 mb-2">
                        <?= htmlspecialchars($method['name'], ENT_QUOTES, 'UTF-8') ?>
                    </h2>
                    <p class="text-sm text-gray-500 mb-4 flex items-center">
                        <svg class="w-4 h-4 mr-2 text-amber-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        所要時間 <?= htmlspecialchars($method['time'], ENT_QUOTES, 'UTF-8') ?>
                    </p>
                    <p class="text-gray-600 flex-1 leading-relaxed">
                        <?= htmlspecialchars($method['description'], ENT_QUOTES, 'UTF-8') ?>
                    </p>
                    <div class="mt-6">
                        <a href="<?= url('/brewing-guide/' . urlencode($method['id'])) ?>"
                           class="inline-flex items-center justify-center w-full bg-amber-600 text-white font-semibold py-3 rounded-xl shadow hover:bg-amber-700 transition">
                            詳細を見る
                            <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>

        <section class="mt-16 bg-white border border-amber-100 rounded-2xl shadow-sm p-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">シーン別おすすめ</h2>
            <p class="text-gray-600 mb-6">気分や時間帯に合わせて、ぴったりの抽出方法をピックアップ。</p>
            <div class="grid md:grid-cols-3 gap-6">
                <?php
                    $sceneTips = [
                        ['title' => '朝のスタートに', 'body' => 'ドリップやフレンチプレスで香り高い一杯を。軽めの焙煎と好相性です。'],
                        ['title' => '集中したいとき', 'body' => 'エアロプレスやエスプレッソでキレのある味わいに。アイスアレンジにも◎。'],
                        ['title' => 'くつろぎ時間に', 'body' => 'サイフォンや水出しでゆったりと。ミルクアレンジにもおすすめ。'],
                    ];
                    foreach ($sceneTips as $tip): ?>
                    <div class="p-5 border border-gray-100 rounded-xl bg-amber-50/30">
                        <h3 class="font-semibold text-gray-900 mb-2"><?= htmlspecialchars($tip['title'], ENT_QUOTES, 'UTF-8') ?></h3>
                        <p class="text-sm text-gray-600 leading-relaxed"><?= htmlspecialchars($tip['body'], ENT_QUOTES, 'UTF-8') ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
    </div>
</div>
