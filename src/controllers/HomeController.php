<?php
/**
 * HomeController - トップページ（Bolt用サンプルデータ）
 */
class HomeController {
    public function index() {
        // サンプル商品データ（DB接続なし）
        $featuredProducts = [
            [
                'id' => 1,
                'name' => 'エチオピア イルガチェフェ',
                'description' => '華やかな香りとフルーティーな酸味が特徴',
                'price' => 1580,
                'sale_price' => null,
                'image_url' => url('images/products/coffee/sample-1.jpg'),
                'category' => 'コーヒー豆',
                'type' => 'シングルオリジン'
            ],
            [
                'id' => 2,
                'name' => 'ブラジル サントス',
                'description' => 'バランスの良い味わいで飲みやすい',
                'price' => 1280,
                'sale_price' => 980,
                'image_url' => url('images/products/coffee/sample-2.jpg'),
                'category' => 'コーヒー豆',
                'type' => 'シングルオリジン'
            ],
            [
                'id' => 3,
                'name' => '抹茶ラテの素',
                'description' => '本格的な抹茶の風味を手軽に楽しめる',
                'price' => 980,
                'sale_price' => null,
                'image_url' => url('images/products/tea/sample-1.jpg'),
                'category' => 'お茶',
                'type' => '抹茶'
            ],
            [
                'id' => 4,
                'name' => 'コーヒーギフトセット',
                'description' => '厳選した3種類のコーヒー豆セット',
                'price' => 3980,
                'sale_price' => null,
                'image_url' => url('images/products/gift/sample-1.jpg'),
                'category' => 'ギフト',
                'type' => 'セット'
            ],
        ];

        view('pages/home', [
            'title' => 'おうちかふぇ - こだわりのコーヒー・お茶',
            'featuredProducts' => $featuredProducts,
            'jsFile' => null
        ]);
    }
}
