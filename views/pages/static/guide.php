<div class="min-h-screen bg-gray-50 py-12">
    <div class="container mx-auto px-4 max-w-6xl">
        <!-- ページヘッダー -->
        <div class="mb-8 text-center">
            <h1 class="text-4xl font-bold text-gray-900 mb-2">ご利用ガイド</h1>
            <p class="text-gray-600">おうちかふぇのご利用方法をご案内します</p>
        </div>

        <!-- ガイドナビゲーション -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-8">
            <h2 class="text-xl font-bold text-gray-900 mb-4 text-center">ご利用の流れ</h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <a href="#registration" class="text-center p-4 border border-gray-200 rounded-lg hover:border-amber-600 hover:bg-amber-50 transition">
                    <div class="w-16 h-16 bg-amber-100 text-amber-600 rounded-full flex items-center justify-center mx-auto mb-3 font-bold text-2xl">1</div>
                    <p class="font-semibold text-gray-900">会員登録</p>
                </a>
                <a href="#shopping" class="text-center p-4 border border-gray-200 rounded-lg hover:border-amber-600 hover:bg-amber-50 transition">
                    <div class="w-16 h-16 bg-amber-100 text-amber-600 rounded-full flex items-center justify-center mx-auto mb-3 font-bold text-2xl">2</div>
                    <p class="font-semibold text-gray-900">商品を選ぶ</p>
                </a>
                <a href="#payment" class="text-center p-4 border border-gray-200 rounded-lg hover:border-amber-600 hover:bg-amber-50 transition">
                    <div class="w-16 h-16 bg-amber-100 text-amber-600 rounded-full flex items-center justify-center mx-auto mb-3 font-bold text-2xl">3</div>
                    <p class="font-semibold text-gray-900">お支払い</p>
                </a>
                <a href="#delivery" class="text-center p-4 border border-gray-200 rounded-lg hover:border-amber-600 hover:bg-amber-50 transition">
                    <div class="w-16 h-16 bg-amber-100 text-amber-600 rounded-full flex items-center justify-center mx-auto mb-3 font-bold text-2xl">4</div>
                    <p class="font-semibold text-gray-900">お届け</p>
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- メインコンテンツ -->
            <div class="lg:col-span-2 space-y-8">
                <!-- 1. 会員登録 -->
                <section id="registration" class="bg-white rounded-lg shadow-md p-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6 border-b-2 border-amber-600 pb-2">
                        <span class="bg-amber-600 text-white rounded-full w-8 h-8 inline-flex items-center justify-center mr-3">1</span>
                        会員登録
                    </h2>
                    <div class="space-y-4">
                        <p class="text-gray-700">おうちかふぇで商品をご購入いただくには、まず会員登録が必要です。</p>
                        
                        <div class="bg-amber-50 border-l-4 border-amber-600 p-4 rounded">
                            <h3 class="font-bold text-gray-900 mb-2">会員登録のメリット</h3>
                            <ul class="list-disc list-inside text-gray-700 space-y-1 ml-4">
                                <li>ポイントが貯まる・使える</li>
                                <li>お気に入り商品の保存</li>
                                <li>注文履歴の確認</li>
                                <li>配送先の複数登録</li>
                                <li>お得なキャンペーン情報をいち早くお届け</li>
                            </ul>
                        </div>

                        <div>
                            <h3 class="font-bold text-gray-900 mb-3">登録手順</h3>
                            <ol class="space-y-3">
                                <li class="flex items-start">
                                    <span class="flex-shrink-0 w-6 h-6 bg-amber-600 text-white rounded-full flex items-center justify-center text-sm mr-3 mt-1">1</span>
                                    <div>
                                        <p class="font-semibold text-gray-900">「新規会員登録」をクリック</p>
                                        <p class="text-sm text-gray-600">ページ右上または商品購入時に表示される「新規会員登録」ボタンをクリックします。</p>
                                    </div>
                                </li>
                                <li class="flex items-start">
                                    <span class="flex-shrink-0 w-6 h-6 bg-amber-600 text-white rounded-full flex items-center justify-center text-sm mr-3 mt-1">2</span>
                                    <div>
                                        <p class="font-semibold text-gray-900">必要情報を入力</p>
                                        <p class="text-sm text-gray-600">メールアドレス、パスワード、お名前、住所などの基本情報を入力します。</p>
                                    </div>
                                </li>
                                <li class="flex items-start">
                                    <span class="flex-shrink-0 w-6 h-6 bg-amber-600 text-white rounded-full flex items-center justify-center text-sm mr-3 mt-1">3</span>
                                    <div>
                                        <p class="font-semibold text-gray-900">メール認証</p>
                                        <p class="text-sm text-gray-600">入力したメールアドレスに認証メールが届きます。メール内のリンクをクリックして認証を完了します。</p>
                                    </div>
                                </li>
                                <li class="flex items-start">
                                    <span class="flex-shrink-0 w-6 h-6 bg-amber-600 text-white rounded-full flex items-center justify-center text-sm mr-3 mt-1">4</span>
                                    <div>
                                        <p class="font-semibold text-gray-900">登録完了</p>
                                        <p class="text-sm text-gray-600">登録完了メールが届いたら、すぐにお買い物をお楽しみいただけます。</p>
                                    </div>
                                </li>
                            </ol>
                        </div>

                        <div class="text-center mt-6">
                            <a href="<?= url('/register') ?>" class="inline-block bg-amber-600 text-white px-8 py-3 rounded-lg font-semibold hover:bg-amber-700 transition">
                                今すぐ会員登録する
                            </a>
                        </div>
                    </div>
                </section>

                <!-- 2. 商品を選ぶ -->
                <section id="shopping" class="bg-white rounded-lg shadow-md p-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6 border-b-2 border-amber-600 pb-2">
                        <span class="bg-amber-600 text-white rounded-full w-8 h-8 inline-flex items-center justify-center mr-3">2</span>
                        商品を選ぶ
                    </h2>
                    <div class="space-y-4">
                        <p class="text-gray-700">豊富な商品ラインナップからお好みのコーヒーをお選びください。</p>

                        <div>
                            <h3 class="font-bold text-gray-900 mb-3">商品の探し方</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="border border-gray-200 rounded-lg p-4">
                                    <h4 class="font-semibold text-amber-600 mb-2 flex items-center">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                        </svg>
                                        検索機能
                                    </h4>
                                    <p class="text-sm text-gray-700">商品名やキーワードで検索できます。例: 「エチオピア」「深煎り」など</p>
                                </div>
                                <div class="border border-gray-200 rounded-lg p-4">
                                    <h4 class="font-semibold text-amber-600 mb-2 flex items-center">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
                                        </svg>
                                        カテゴリ
                                    </h4>
                                    <p class="text-sm text-gray-700">産地、焙煎度、挽き方などのカテゴリから絞り込めます。</p>
                                </div>
                                <div class="border border-gray-200 rounded-lg p-4">
                                    <h4 class="font-semibold text-amber-600 mb-2 flex items-center">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                        </svg>
                                        AI診断
                                    </h4>
                                    <p class="text-sm text-gray-700">簡単な質問に答えるだけで、あなたにぴったりのコーヒーを提案します。</p>
                                </div>
                                <div class="border border-gray-200 rounded-lg p-4">
                                    <h4 class="font-semibold text-amber-600 mb-2 flex items-center">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                        </svg>
                                        おすすめ商品
                                    </h4>
                                    <p class="text-sm text-gray-700">購入履歴や閲覧履歴から、あなたにおすすめの商品を表示します。</p>
                                </div>
                            </div>
                        </div>

                        <div>
                            <h3 class="font-bold text-gray-900 mb-3">カートに追加</h3>
                            <ol class="space-y-2 text-gray-700">
                                <li class="flex items-start">
                                    <span class="text-amber-600 mr-2">1.</span>
                                    商品ページで内容量・挽き方を選択
                                </li>
                                <li class="flex items-start">
                                    <span class="text-amber-600 mr-2">2.</span>
                                    数量を指定
                                </li>
                                <li class="flex items-start">
                                    <span class="text-amber-600 mr-2">3.</span>
                                    「カートに入れる」ボタンをクリック
                                </li>
                            </ol>
                        </div>

                        <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded">
                            <h4 class="font-semibold text-gray-900 mb-2">💡 便利な機能</h4>
                            <ul class="list-disc list-inside text-gray-700 space-y-1 ml-4">
                                <li><strong>お気に入り:</strong> 気になる商品は「♡」マークでお気に入り登録</li>
                                <li><strong>比較機能:</strong> 最大3商品まで味わいを比較できます</li>
                                <li><strong>閲覧履歴:</strong> 最近見た商品が自動で保存されます</li>
                            </ul>
                        </div>
                    </div>
                </section>

                <!-- 3. お支払い -->
                <section id="payment" class="bg-white rounded-lg shadow-md p-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6 border-b-2 border-amber-600 pb-2">
                        <span class="bg-amber-600 text-white rounded-full w-8 h-8 inline-flex items-center justify-center mr-3">3</span>
                        お支払い
                    </h2>
                    <div class="space-y-4">
                        <p class="text-gray-700">以下のお支払い方法からお選びいただけます。</p>

                        <div class="space-y-4">
                            <!-- クレジットカード -->
                            <div class="border border-gray-200 rounded-lg p-4">
                                <h3 class="font-bold text-gray-900 mb-2 flex items-center">
                                    <svg class="w-6 h-6 text-amber-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                                    </svg>
                                    クレジットカード決済
                                </h3>
                                <p class="text-sm text-gray-700 mb-2">VISA、MasterCard、JCB、American Express、Diners Clubがご利用いただけます。</p>
                                <ul class="text-sm text-gray-600 list-disc list-inside ml-4">
                                    <li>決済手数料: 無料</li>
                                    <li>ご注文確定時に決済されます</li>
                                    <li>カード情報は安全に暗号化されます</li>
                                </ul>
                            </div>

                            <!-- 代金引換 -->
                            <div class="border border-gray-200 rounded-lg p-4">
                                <h3 class="font-bold text-gray-900 mb-2 flex items-center">
                                    <svg class="w-6 h-6 text-amber-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    </svg>
                                    代金引換
                                </h3>
                                <p class="text-sm text-gray-700 mb-2">商品受け取り時に配送業者へ現金でお支払いください。</p>
                                <ul class="text-sm text-gray-600 list-disc list-inside ml-4">
                                    <li>代引手数料: 330円（税込）</li>
                                    <li>お釣りの無いようご準備ください</li>
                                </ul>
                            </div>

                            <!-- 銀行振込 -->
                            <div class="border border-gray-200 rounded-lg p-4">
                                <h3 class="font-bold text-gray-900 mb-2 flex items-center">
                                    <svg class="w-6 h-6 text-amber-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"></path>
                                    </svg>
                                    銀行振込（前払い）
                                </h3>
                                <p class="text-sm text-gray-700 mb-2">ご注文後、指定口座へお振込ください。</p>
                                <ul class="text-sm text-gray-600 list-disc list-inside ml-4">
                                    <li>振込手数料: お客様負担</li>
                                    <li>お支払い期限: ご注文後7日以内</li>
                                    <li>入金確認後、商品を発送いたします</li>
                                </ul>
                            </div>

                            <!-- コンビニ決済 -->
                            <div class="border border-gray-200 rounded-lg p-4">
                                <h3 class="font-bold text-gray-900 mb-2 flex items-center">
                                    <svg class="w-6 h-6 text-amber-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                    </svg>
                                    コンビニ決済（前払い）
                                </h3>
                                <p class="text-sm text-gray-700 mb-2">お近くのコンビニエンスストアでお支払いいただけます。</p>
                                <ul class="text-sm text-gray-600 list-disc list-inside ml-4">
                                    <li>決済手数料: 220円（税込）</li>
                                    <li>お支払い期限: ご注文後7日以内</li>
                                    <li>利用可能: セブン-イレブン、ファミリーマート、ローソンなど</li>
                                </ul>
                            </div>
                        </div>

                        <div class="bg-amber-50 border-l-4 border-amber-600 p-4 rounded">
                            <h4 class="font-semibold text-gray-900 mb-2">ポイントのご利用</h4>
                            <p class="text-sm text-gray-700">貯まったポイントは1ポイント=1円としてご利用いただけます。お支払い画面でポイント利用額を指定してください。</p>
                        </div>
                    </div>
                </section>

                <!-- 4. お届け -->
                <section id="delivery" class="bg-white rounded-lg shadow-md p-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6 border-b-2 border-amber-600 pb-2">
                        <span class="bg-amber-600 text-white rounded-full w-8 h-8 inline-flex items-center justify-center mr-3">4</span>
                        お届け
                    </h2>
                    <div class="space-y-4">
                        <div>
                            <h3 class="font-bold text-gray-900 mb-3">配送料</h3>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <div class="flex justify-between items-center mb-2">
                                    <span class="text-gray-700">全国一律</span>
                                    <span class="font-bold text-gray-900">550円（税込）</span>
                                </div>
                                <div class="flex justify-between items-center mb-2">
                                    <span class="text-gray-700">北海道・沖縄・離島</span>
                                    <span class="font-bold text-gray-900">1,100円（税込）</span>
                                </div>
                                <div class="flex justify-between items-center text-amber-600">
                                    <span class="font-semibold">5,000円以上のご購入</span>
                                    <span class="font-bold">送料無料</span>
                                </div>
                            </div>
                        </div>

                        <div>
                            <h3 class="font-bold text-gray-900 mb-3">お届け日数</h3>
                            <ul class="space-y-2 text-gray-700">
                                <li class="flex items-start">
                                    <span class="text-amber-600 mr-2">•</span>
                                    <div>
                                        <strong>通常配送:</strong> ご注文確定後、2〜5営業日以内に発送
                                    </div>
                                </li>
                                <li class="flex items-start">
                                    <span class="text-amber-600 mr-2">•</span>
                                    <div>
                                        <strong>お急ぎの場合:</strong> お届け希望日をご指定いただけます
                                    </div>
                                </li>
                            </ul>
                        </div>

                        <div>
                            <h3 class="font-bold text-gray-900 mb-3">配送時間帯指定</h3>
                            <div class="grid grid-cols-2 md:grid-cols-3 gap-2 text-sm">
                                <div class="border border-gray-200 rounded p-2 text-center">午前中</div>
                                <div class="border border-gray-200 rounded p-2 text-center">12:00〜14:00</div>
                                <div class="border border-gray-200 rounded p-2 text-center">14:00〜16:00</div>
                                <div class="border border-gray-200 rounded p-2 text-center">16:00〜18:00</div>
                                <div class="border border-gray-200 rounded p-2 text-center">18:00〜20:00</div>
                                <div class="border border-gray-200 rounded p-2 text-center">20:00〜21:00</div>
                            </div>
                        </div>

                        <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded">
                            <h4 class="font-semibold text-gray-900 mb-2">配送状況の確認</h4>
                            <p class="text-sm text-gray-700">商品発送後、お問い合わせ番号をメールでお知らせします。配送業者のサイトから配送状況をご確認いただけます。</p>
                        </div>
                    </div>
                </section>
            </div>

            <!-- サイドバー -->
            <div class="space-y-6">
                <!-- クイックリンク -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="font-bold text-gray-900 mb-4">クイックリンク</h3>
                    <ul class="space-y-3">
                        <li>
                            <a href="<?= url('/faq') ?>" class="text-amber-600 hover:text-amber-700 flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                                よくある質問
                            </a>
                        </li>
                        <li>
                            <a href="<?= url('/returns') ?>" class="text-amber-600 hover:text-amber-700 flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                                返品・交換について
                            </a>
                        </li>
                        <li>
                            <a href="<?= url('/tokusho') ?>" class="text-amber-600 hover:text-amber-700 flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                                特定商取引法
                            </a>
                        </li>
                        <li>
                            <a href="<?= url('/privacy') ?>" class="text-amber-600 hover:text-amber-700 flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                                プライバシーポリシー
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- お問い合わせ -->
                <div class="bg-amber-600 text-white rounded-lg shadow-md p-6">
                    <h3 class="font-bold mb-4">お困りですか？</h3>
                    <p class="text-sm mb-4 opacity-90">カスタマーサポートがお手伝いします</p>
                    <a href="mailto:info@ouchicafe.jp" class="block bg-white text-amber-600 text-center py-2 rounded font-semibold hover:bg-gray-100 transition">
                        お問い合わせ
                    </a>
                </div>

                <!-- おすすめコンテンツ -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="font-bold text-gray-900 mb-4">おすすめコンテンツ</h3>
                    <div class="space-y-3">
                        <a href="<?= url('/diagnosis') ?>" class="block p-3 border border-gray-200 rounded hover:border-amber-600 hover:bg-amber-50 transition">
                            <p class="font-semibold text-gray-900 text-sm">コーヒー診断</p>
                            <p class="text-xs text-gray-600">あなたにぴったりのコーヒーを診断</p>
                        </a>
                        <a href="<?= url('/guide/brewing') ?>" class="block p-3 border border-gray-200 rounded hover:border-amber-600 hover:bg-amber-50 transition">
                            <p class="font-semibold text-gray-900 text-sm">淹れ方ガイド</p>
                            <p class="text-xs text-gray-600">美味しいコーヒーの淹れ方</p>
                        </a>
                    </div>
                </div>
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

<script src="<?= url('js/smooth-scroll-anchors.js') ?>?v=20251205"></script>
