<?php
/**
 * CartController - カート機能（Bolt用サンプル）
 */
class CartController {
    public function index() {
        // セッションからカートデータ取得（サンプル）
        $cartItems = $_SESSION['cart'] ?? [];

        view('pages/cart/index', [
            'title' => 'カート',
            'cartItems' => $cartItems,
            'jsFile' => 'cart.js'
        ]);
    }

    public function add() {
        // カート追加処理（サンプル）
        $productId = $_POST['product_id'] ?? null;
        $variantId = $_POST['variant_id'] ?? null;
        $quantity = $_POST['quantity'] ?? 1;

        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        $_SESSION['cart'][] = [
            'product_id' => $productId,
            'variant_id' => $variantId,
            'quantity' => $quantity
        ];

        $_SESSION['success'] = 'カートに追加しました';
        redirect('/cart');
    }

    public function update() {
        // カート更新処理（サンプル）
        redirect('/cart');
    }

    public function remove() {
        // カート削除処理（サンプル）
        redirect('/cart');
    }
}
