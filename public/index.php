<?php
/**
 * フロントコントローラー（エントリーポイント）
 * 
 * 全てのリクエストがこのファイルを通過します。
 * Apacheの.htaccessまたはNginxの設定で、全リクエストをこのファイルにリダイレクトします。
 * 
 * 【処理の流れ】
 * 1. 設定ファイル読み込み
 * 2. セッション開始
 * 3. CSRF保護（POSTリクエストの場合）
 * 4. ルーティング実行
 */

// ===== 1. 設定ファイル読み込み =====
require_once __DIR__ . '/../src/config.php';
require_once __DIR__ . '/../src/db.php';
require_once __DIR__ . '/../src/session.php';
require_once __DIR__ . '/../src/csrf.php';
require_once __DIR__ . '/../src/middleware.php';
require_once __DIR__ . '/../src/router.php';
require_once __DIR__ . '/../src/stripe.php';
require_once __DIR__ . '/../src/mail.php';
require_once __DIR__ . '/../src/fastapi-mock.php';
// helpers.phpはcomposer autoloadで読み込まれる

// ===== 2. セッション開始 =====
startSession();
attemptRememberedLogin();

// ===== 3. CSRF保護（POSTリクエストの場合） =====
// API エンドポイント（/api/で始まるパス）とWebhook（/webhook/で始まるパス）は除外
$uri = getRequestUri();
if ($_SERVER['REQUEST_METHOD'] === 'POST' && 
    strpos($uri, '/api/') !== 0 && 
    strpos($uri, '/webhook/') !== 0) {
    if (!verifyCsrfToken()) {
        http_response_code(403);
        if (isAjaxRequest()) {
            header('Content-Type: application/json');
            echo json_encode(['error' => 'CSRFトークンが無効です']);
        } else {
            die('不正なリクエストです。ページを再読み込みしてください。');
        }
        exit;
    }
}

// ===== 4. ルート定義を読み込み =====
loadRoutes(__DIR__ . '/../src/routes.php');

// ===== 5. ルーティング実行 =====
try {
    dispatch();
} catch (Exception $e) {
    // エラーハンドリング
    if (APP_DEBUG) {
        echo '<h1>エラーが発生しました</h1>';
        echo '<pre>';
        echo htmlspecialchars($e->getMessage()) . "\n";
        echo htmlspecialchars($e->getTraceAsString());
        echo '</pre>';
    } else {
        http_response_code(500);
        view('errors/500');
    }
    
    // エラーログに記録
    error_log('Application Error: ' . $e->getMessage());
}
