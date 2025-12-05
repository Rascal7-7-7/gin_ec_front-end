<?php
/**
 * WishlistController - お気に入り機能（Bolt用サンプル）
 */
class WishlistController {
    public function index() {
        // お気に入り一覧
        $wishlistItems = $_SESSION['wishlist'] ?? [];

        view('pages/wishlist/index', [
            'title' => 'お気に入り',
            'wishlistItems' => $wishlistItems,
            'jsFile' => 'wishlist.js'
        ]);
    }

    public function add() {
        // お気に入り追加
        $productId = $_POST['product_id'] ?? null;

        if (!isset($_SESSION['wishlist'])) {
            $_SESSION['wishlist'] = [];
        }

        if (!in_array($productId, $_SESSION['wishlist'])) {
            $_SESSION['wishlist'][] = $productId;
            $_SESSION['success'] = 'お気に入りに追加しました';
        }

        redirect('/wishlist');
    }

    public function remove() {
        // お気に入り削除
        $productId = $_POST['product_id'] ?? null;

        if (isset($_SESSION['wishlist'])) {
            $_SESSION['wishlist'] = array_filter($_SESSION['wishlist'], function($id) use ($productId) {
                return $id != $productId;
            });
            $_SESSION['success'] = 'お気に入りから削除しました';
        }

        redirect('/wishlist');
    }
}
