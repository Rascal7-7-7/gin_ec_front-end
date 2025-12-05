<?php
/**
 * ProductController - 商品表示（Bolt用サンプルデータ）
 */
class ProductController {
    public function index() {
        // サンプル商品一覧
        $products = $this->getSampleProducts();
        
        view('pages/products/index', [
            'title' => '商品一覧',
            'products' => $products,
            'categories' => ['コーヒー豆', 'お茶', 'ギフト'],
            'jsFile' => 'products-index.js'
        ]);
    }

    public function show($id) {
        // サンプル商品詳細
        $product = [
            'id' => $id,
            'name' => 'エチオピア イルガチェフェ',
            'description' => '華やかな香りとフルーティーな酸味が特徴のスペシャルティコーヒー。エチオピアのイルガチェフェ地方で丁寧に栽培された高品質な豆です。',
            'price' => 1580,
            'sale_price' => null,
            'image_url' => url('images/products/coffee/sample-1.jpg'),
            'category' => 'コーヒー豆',
            'type' => 'シングルオリジン',
            'stock' => 50,
            'variants' => [
                ['id' => 1, 'type' => '容量', 'value' => '100g', 'price' => 1580],
                ['id' => 2, 'type' => '容量', 'value' => '200g', 'price' => 2980],
                ['id' => 3, 'type' => '容量', 'value' => '500g', 'price' => 6800],
            ]
        ];

        view('pages/products/show', [
            'title' => $product['name'],
            'product' => $product,
            'relatedProducts' => array_slice($this->getSampleProducts(), 0, 4),
            'jsFile' => 'product-show.js'
        ]);
    }

    public function ranking() {
        $products = $this->getSampleProducts();
        
        view('pages/products/ranking', [
            'title' => '人気ランキング',
            'products' => $products,
            'jsFile' => 'products-index.js'
        ]);
    }

    private function getSampleProducts() {
        return [
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
    }
}
