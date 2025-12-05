<?php
/**
 * ルート定義（Bolt用フロントエンド - 最小構成）
 */

// ホームページ
get('/', 'HomeController@index');

// 商品
get('/products', 'ProductController@index');
get('/products/ranking', 'ProductController@ranking');
get('/products/:id', 'ProductController@show');

// カート
get('/cart', 'CartController@index');
post('/cart/add', 'CartController@add');
post('/cart/update', 'CartController@update');
post('/cart/remove', 'CartController@remove');

// お気に入り
get('/wishlist', 'WishlistController@index');
post('/wishlist/add', 'WishlistController@add');
post('/wishlist/remove', 'WishlistController@remove');

// 静的ページ
get('/about', function() {
    view('pages/static/about', ['title' => '会社概要']);
});

get('/terms', function() {
    view('pages/static/terms', ['title' => '利用規約']);
});

get('/privacy', function() {
    view('pages/static/privacy', ['title' => 'プライバシーポリシー']);
});
