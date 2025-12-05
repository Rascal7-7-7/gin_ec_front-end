<?php require_once __DIR__ . '/../../layout/header.php'; ?>

<div class="min-h-screen bg-gray-50 py-12">
    <div class="container mx-auto px-4 max-w-4xl">
        <!-- ページヘッダー -->
        <div class="mb-8">
            <h1 class="text-4xl font-bold text-gray-900 mb-2">返品・交換ポリシー</h1>
            <p class="text-gray-600">Return & Exchange Policy</p>
        </div>

        <div class="bg-white rounded-lg shadow-md p-8 space-y-8">
            <!-- 基本方針 -->
            <section>
                <h2 class="text-2xl font-bold text-gray-900 mb-4 border-b-2 border-amber-600 pb-2">基本方針</h2>
                <div class="space-y-3 text-gray-700 leading-relaxed">
                    <p>おうちかふぇでは、お客様に安心してお買い物をしていただけるよう、以下の返品・交換ポリシーを定めております。商品の品質には万全を期しておりますが、万が一不良品や配送中の破損がございましたら、迅速に対応させていただきます。</p>
                </div>
            </section>

            <!-- 返品・交換の条件 -->
            <section>
                <h2 class="text-2xl font-bold text-gray-900 mb-4 border-b-2 border-amber-600 pb-2">返品・交換の条件</h2>
                
                <!-- 不良品・配送中の破損 -->
                <div class="mb-6">
                    <h3 class="text-xl font-bold text-gray-900 mb-3 flex items-center">
                        <span class="bg-red-100 text-red-800 px-3 py-1 rounded-full text-sm mr-3">重要</span>
                        不良品・配送中の破損の場合
                    </h3>
                    <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded">
                        <p class="font-semibold text-gray-900 mb-2">当店の責任において全額返金または交換いたします</p>
                        <ul class="list-disc list-inside space-y-2 text-gray-700 ml-4">
                            <li><strong>期限:</strong> 商品到着後7日以内にご連絡ください</li>
                            <li><strong>送料:</strong> 返送料・再送料ともに当店負担</li>
                            <li><strong>条件:</strong> 商品は未使用の状態でご返送ください</li>
                            <li><strong>対応:</strong> 同一商品との交換、または全額返金</li>
                        </ul>
                        <div class="mt-4 p-3 bg-white rounded">
                            <p class="text-sm font-semibold text-gray-900">【不良品の例】</p>
                            <ul class="text-sm text-gray-700 list-disc list-inside ml-2 mt-1">
                                <li>賞味期限が切れている、または極端に短い</li>
                                <li>パッケージが破損している</li>
                                <li>商品説明と異なる内容物</li>
                                <li>異物混入や変色・異臭がある</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- お客様都合による返品 -->
                <div class="mb-6">
                    <h3 class="text-xl font-bold text-gray-900 mb-3 flex items-center">
                        <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm mr-3">通常</span>
                        お客様都合による返品の場合
                    </h3>
                    <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded">
                        <p class="font-semibold text-gray-900 mb-2">以下の条件を満たす場合に限り返品を承ります</p>
                        <ul class="list-disc list-inside space-y-2 text-gray-700 ml-4">
                            <li><strong>期限:</strong> 商品到着後7日以内にご連絡ください</li>
                            <li><strong>送料:</strong> 返送料はお客様負担</li>
                            <li><strong>条件:</strong> 未開封・未使用の商品に限ります</li>
                            <li><strong>手数料:</strong> 返金額から商品代金の10%を差し引きます</li>
                            <li><strong>返金方法:</strong> 銀行振込（振込手数料はお客様負担）</li>
                        </ul>
                        <div class="mt-4 p-3 bg-white rounded">
                            <p class="text-sm font-semibold text-gray-900">【ご注意】</p>
                            <p class="text-sm text-gray-700 mt-1">食品の性質上、開封後の返品はお受けできません。味や香りが期待と異なる場合でも、開封後の返品はご容赦ください。</p>
                        </div>
                    </div>
                </div>

                <!-- 返品・交換ができない商品 -->
                <div class="mb-6">
                    <h3 class="text-xl font-bold text-gray-900 mb-3 flex items-center">
                        <span class="bg-gray-100 text-gray-800 px-3 py-1 rounded-full text-sm mr-3">対象外</span>
                        返品・交換ができない商品
                    </h3>
                    <div class="bg-amber-50 border-l-4 border-amber-500 p-4 rounded">
                        <p class="font-semibold text-gray-900 mb-2">以下の商品は返品・交換をお受けできません</p>
                        <ul class="list-disc list-inside space-y-2 text-gray-700 ml-4">
                            <li>商品到着後8日以上経過した商品</li>
                            <li>開封済み・使用済みの商品（不良品を除く）</li>
                            <li>お客様の責任で傷や汚れが生じた商品</li>
                            <li>セール品・アウトレット品（商品ページに明記）</li>
                            <li>オーダーメイド商品・特注品</li>
                            <li>賞味期限間近で割引販売している商品</li>
                        </ul>
                    </div>
                </div>
            </section>

            <!-- 返品・交換の手順 -->
            <section>
                <h2 class="text-2xl font-bold text-gray-900 mb-4 border-b-2 border-amber-600 pb-2">返品・交換の手順</h2>
                <div class="space-y-4">
                    <!-- ステップ1 -->
                    <div class="flex items-start">
                        <div class="flex-shrink-0 w-12 h-12 bg-amber-600 text-white rounded-full flex items-center justify-center font-bold text-lg mr-4">
                            1
                        </div>
                        <div class="flex-1">
                            <h3 class="font-bold text-lg text-gray-900 mb-2">お問い合わせ</h3>
                            <p class="text-gray-700">商品到着後7日以内に、下記のいずれかの方法でご連絡ください。</p>
                            <ul class="list-disc list-inside ml-4 mt-2 text-gray-700">
                                <li>メール: <a href="mailto:return@ouchicafe.jp" class="text-amber-600 hover:text-amber-700">return@ouchicafe.jp</a></li>
                                <li>電話: 03-1234-5678（平日 10:00〜17:00）</li>
                                <li>お問い合わせフォーム（24時間受付）</li>
                            </ul>
                            <div class="mt-3 p-3 bg-gray-50 rounded">
                                <p class="text-sm font-semibold text-gray-900">【お伝えいただく内容】</p>
                                <ul class="text-sm text-gray-700 list-disc list-inside ml-2 mt-1">
                                    <li>ご注文番号</li>
                                    <li>商品名</li>
                                    <li>返品・交換の理由</li>
                                    <li>不良品の場合は状況の詳細（写真添付推奨）</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- ステップ2 -->
                    <div class="flex items-start">
                        <div class="flex-shrink-0 w-12 h-12 bg-amber-600 text-white rounded-full flex items-center justify-center font-bold text-lg mr-4">
                            2
                        </div>
                        <div class="flex-1">
                            <h3 class="font-bold text-lg text-gray-900 mb-2">返送先のご案内</h3>
                            <p class="text-gray-700">当店からメールまたは電話にて、返送先住所と返品承認番号（RMA番号）をご案内いたします。</p>
                            <div class="mt-3 p-3 bg-amber-50 rounded border border-amber-200">
                                <p class="text-sm font-semibold text-amber-900">⚠️ 重要</p>
                                <p class="text-sm text-amber-900 mt-1">必ず事前にご連絡の上、返品承認番号を取得してから商品をご返送ください。事前連絡なしでの返送は受け付けられない場合がございます。</p>
                            </div>
                        </div>
                    </div>

                    <!-- ステップ3 -->
                    <div class="flex items-start">
                        <div class="flex-shrink-0 w-12 h-12 bg-amber-600 text-white rounded-full flex items-center justify-center font-bold text-lg mr-4">
                            3
                        </div>
                        <div class="flex-1">
                            <h3 class="font-bold text-lg text-gray-900 mb-2">商品の返送</h3>
                            <p class="text-gray-700">商品を丁寧に梱包し、指定の住所へご返送ください。</p>
                            <ul class="list-disc list-inside ml-4 mt-2 text-gray-700">
                                <li>元の箱がある場合は、可能な限り元の箱を使用してください</li>
                                <li>破損を防ぐため、十分な緩衝材で保護してください</li>
                                <li>返品承認番号（RMA番号）を外箱に記載してください</li>
                                <li>配送伝票の控えは必ず保管してください</li>
                            </ul>
                        </div>
                    </div>

                    <!-- ステップ4 -->
                    <div class="flex items-start">
                        <div class="flex-shrink-0 w-12 h-12 bg-amber-600 text-white rounded-full flex items-center justify-center font-bold text-lg mr-4">
                            4
                        </div>
                        <div class="flex-1">
                            <h3 class="font-bold text-lg text-gray-900 mb-2">商品確認・対応</h3>
                            <p class="text-gray-700">商品到着後、3営業日以内に商品を確認し、以下の対応を行います。</p>
                            <ul class="list-disc list-inside ml-4 mt-2 text-gray-700">
                                <li><strong>交換の場合:</strong> 代替商品を速やかに発送いたします</li>
                                <li><strong>返金の場合:</strong> ご指定の口座へ返金いたします（5〜7営業日）</li>
                            </ul>
                            <p class="text-sm text-gray-600 mt-3">※クレジットカード決済の場合、カード会社を通じた返金となるため、お客様の口座への反映までに1〜2ヶ月かかる場合がございます。</p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- 返送先 -->
            <section>
                <h2 class="text-2xl font-bold text-gray-900 mb-4 border-b-2 border-amber-600 pb-2">返送先</h2>
                <div class="bg-gray-50 p-6 rounded-lg">
                    <p class="text-gray-700 mb-2">〒150-0001</p>
                    <p class="text-gray-700 mb-2">東京都渋谷区神宮前1丁目2-3 おうちかふぇビル4F</p>
                    <p class="text-gray-700 mb-2">おうちかふぇ 返品受付係</p>
                    <p class="text-gray-700">TEL: 03-1234-5678</p>
                    <div class="mt-4 p-3 bg-amber-50 rounded border border-amber-200">
                        <p class="text-sm font-semibold text-amber-900">⚠️ ご注意</p>
                        <p class="text-sm text-amber-900 mt-1">必ず事前にご連絡の上、返品承認番号（RMA番号）を取得してからご返送ください。</p>
                    </div>
                </div>
            </section>

            <!-- よくある質問 -->
            <section>
                <h2 class="text-2xl font-bold text-gray-900 mb-4 border-b-2 border-amber-600 pb-2">よくある質問</h2>
                <div class="space-y-4">
                    <div class="border-l-4 border-gray-300 pl-4">
                        <p class="font-semibold text-gray-900">Q1. 注文した商品と違うものが届きました。</p>
                        <p class="text-gray-700 mt-2">A. 大変申し訳ございません。商品到着後7日以内にご連絡ください。送料当店負担にて正しい商品と交換させていただきます。</p>
                    </div>
                    <div class="border-l-4 border-gray-300 pl-4">
                        <p class="font-semibold text-gray-900">Q2. 味が期待と違ったので返品したいのですが。</p>
                        <p class="text-gray-700 mt-2">A. 開封済みの商品は、食品の性質上返品をお受けすることができません。未開封の場合は、商品到着後7日以内であれば返品可能です（返送料・手数料はお客様負担）。</p>
                    </div>
                    <div class="border-l-4 border-gray-300 pl-4">
                        <p class="font-semibold text-gray-900">Q3. 返金までどのくらいかかりますか？</p>
                        <p class="text-gray-700 mt-2">A. 商品確認後、5〜7営業日以内にご指定の口座へ返金いたします。クレジットカード決済の場合は、カード会社を通じた返金となるため、1〜2ヶ月かかる場合がございます。</p>
                    </div>
                    <div class="border-l-4 border-gray-300 pl-4">
                        <p class="font-semibold text-gray-900">Q4. セール商品は返品できますか？</p>
                        <p class="text-gray-700 mt-2">A. セール品・アウトレット品は原則として返品不可となります。ただし、不良品や配送中の破損の場合は対応いたしますので、ご連絡ください。</p>
                    </div>
                    <div class="border-l-4 border-gray-300 pl-4">
                        <p class="font-semibold text-gray-900">Q5. 一部の商品だけ返品できますか？</p>
                        <p class="text-gray-700 mt-2">A. はい、可能です。返品希望の商品のみをご返送ください。返金額は返品された商品分となります。</p>
                    </div>
                </div>
            </section>

            <!-- お問い合わせ -->
            <section class="bg-amber-50 p-6 rounded-lg">
                <h2 class="text-xl font-bold text-gray-900 mb-4">返品・交換に関するお問い合わせ</h2>
                <div class="space-y-2 text-gray-700">
                    <p><strong>メール:</strong> <a href="mailto:return@ouchicafe.jp" class="text-amber-600 hover:text-amber-700">return@ouchicafe.jp</a></p>
                    <p><strong>電話:</strong> 03-1234-5678</p>
                    <p class="text-sm text-gray-600">受付時間: 平日 10:00〜17:00（土日祝日・年末年始を除く）</p>
                </div>
            </section>

            <!-- フッター -->
            <div class="pt-8 border-t text-center">
                <p class="text-gray-600">最終更新日: 2025年11月12日</p>
            </div>
        </div>

        <!-- 戻るリンク -->
        <div class="mt-8 text-center">
            <a href="<?= url('/') ?>" class="text-amber-600 hover:text-amber-700 font-semibold">
                ← トップページに戻る
            </a>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../../layout/footer.php'; ?>
</div>
