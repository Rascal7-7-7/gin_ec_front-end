<?php
/**
 * ヘルパー関数
 * 
 * アプリケーション全体で使用する便利な関数群です。
 */

// AI API関数の読み込み
require_once __DIR__ . '/ai.php';
require_once __DIR__ . '/helpers/VariantHelper.php';

if (!defined('LEGACY_VARIANT_ID_OFFSET')) {
    define('LEGACY_VARIANT_ID_OFFSET', 900000000);
}

/**
 * リダイレクト
 * 
 * 指定されたURLにリダイレクトしてスクリプトを終了します。
 * 
 * @param string $url リダイレクト先URL
 * @param int $statusCode HTTPステータスコード（デフォルト: 302）
 * 
 * @example
 * redirect('/login');
 * redirect('/products', 301); // 恒久的リダイレクト
 */
function redirect(string $url, int $statusCode = 302): void
{
    // 相対パスの場合はurl()ヘルパーを使用
    if ($url[0] === '/') {
        $url = url($url);
    }
    header('Location: ' . $url, true, $statusCode);
    exit;
}

/**
 * HTMLエスケープ
 * 
 * XSS攻撃を防ぐため、HTMLの特殊文字をエスケープします。
 * 
 * @param string|null $string エスケープする文字列
 * @return string エスケープされた文字列
 * 
 * @example
 * echo h($userInput); // <script>alert('XSS')</script> → &lt;script&gt;alert('XSS')&lt;/script&gt;
 */
function h(?string $string): string
{
    if ($string === null) {
        return '';
    }
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}

/**
 * 前のページにリダイレクト
 * 
 * HTTP_REFERERを使って前のページに戻ります。
 * REFERERが無い場合はフォールバックURLにリダイレクトします。
 * 
 * @param string $fallback フォールバックURL（デフォルト: /）
 * 
 * @example
 * redirectBack('/products'); // 前のページ、無ければ商品一覧へ
 */
function redirectBack(string $fallback = '/'): void
{
    $referer = $_SERVER['HTTP_REFERER'] ?? $fallback;
    redirect($referer);
}

/**
 * XSS対策：HTML特殊文字をエスケープ
 * 
 * ユーザー入力をHTMLで表示する際に必ず使用します。
 * 
 * @param string|null $str エスケープする文字列
 * @return string エスケープされた文字列
 * 
 * @example
 * <h1><?= e($productName) ?></h1>
 * <input type="text" value="<?= e($searchQuery) ?>">
 */
function e(?string $str): string
{
    if ($str === null) {
        return '';
    }
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

/**
 * 配列から指定したキーの値を安全に取得
 * 
 * @param array $array 配列
 * @param string|int $key キー
 * @param mixed $default デフォルト値
 * @return mixed 取得した値
 * 
 * @example
 * $name = arrayGet($_POST, 'name', '');
 * $page = arrayGet($_GET, 'page', 1);
 */
function arrayGet(array $array, $key, $default = null)
{
    return $array[$key] ?? $default;
}

/**
 * GETパラメータを取得
 * 
 * @param string $key パラメータ名
 * @param mixed $default デフォルト値
 * @return mixed 取得した値
 * 
 * @example
 * $page = getParam('page', 1);
 * $category = getParam('category');
 */
function getParam(string $key, $default = null)
{
    return $_GET[$key] ?? $default;
}

/**
 * POSTパラメータを取得
 * 
 * @param string $key パラメータ名
 * @param mixed $default デフォルト値
 * @return mixed 取得した値
 * 
 * @example
 * $email = postParam('email');
 * $quantity = postParam('quantity', 1);
 */
function postParam(string $key, $default = null)
{
    return $_POST[$key] ?? $default;
}

/**
 * JSONレスポンスを送信
 * 
 * Ajax APIのレスポンスとして使用します。
 * 
 * @param mixed $data レスポンスデータ
 * @param int $statusCode HTTPステータスコード
 * 
 * @example
 * jsonResponse(['success' => true, 'message' => 'カートに追加しました']);
 * jsonResponse(['error' => '商品が見つかりません'], 404);
 */
function jsonResponse($data, int $statusCode = 200): void
{
    http_response_code($statusCode);
    header('Content-Type: application/json; charset=UTF-8');
    echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    exit;
}

/**
 * ビューをレンダリング
 * 
 * テンプレートファイルを読み込んで表示します。
 * 
 * @param string $view ビューファイル名（views/からの相対パス、.php不要）
 * @param array $data ビューに渡すデータ（連想配列）
 * 
 * @example
 * view('pages/home', ['products' => $products, 'title' => 'ホーム']);
 * view('pages/products/show', ['product' => $product]);
 */
function view(string $view, array $data = []): void
{
    // UTF-8エンコーディングを明示的に設定
    if (!headers_sent()) {
        header('Content-Type: text/html; charset=UTF-8');
    }
    
    // 連想配列を展開して変数化
    extract($data);
    
    // .php拡張子を付ける
    $viewPath = VIEWS_PATH . '/' . $view . '.php';
    
    if (!file_exists($viewPath)) {
        die("ビューファイルが見つかりません: {$view}");
    }
    
    require_once $viewPath;
}

/**
 * 日付を日本語形式でフォーマット
 * 
 * @param string $date 日付文字列
 * @param string $format フォーマット（デフォルト: Y年n月j日）
 * @return string フォーマットされた日付
 * 
 * @example
 * echo formatDate('2025-11-11'); // 2025年11月11日
 * echo formatDate('2025-11-11 15:30:00', 'Y年n月j日 H:i'); // 2025年11月11日 15:30
 */
function formatDate(?string $date, string $format = 'Y年n月j日'): string
{
    if ($date === null) {
        return '';
    }
    
    $timestamp = strtotime($date);
    if ($timestamp === false) {
        return '';
    }
    
    return date($format, $timestamp);
}

/**
 * 支払い方法の表示名を取得
 */
function paymentMethodLabel(?string $method, string $context = 'default'): string
{
    if (!$method) {
        return '';
    }

    $labels = [
        'stripe_card' => ['default' => 'クレジットカード（Stripe決済）', 'short' => 'クレジットカード（Stripe）'],
        'credit_card' => ['default' => 'クレジットカード（サイト内決済）', 'short' => 'クレジットカード'],
        'card' => ['default' => 'クレジットカード', 'short' => 'クレジットカード'],
        'bank_transfer' => ['default' => '銀行振込', 'short' => '銀行振込'],
        'bank' => ['default' => '銀行振込', 'short' => '銀行振込'],
        'cod' => ['default' => '代金引換（手数料330円）', 'short' => '代金引換'],
        'test' => ['default' => 'テスト決済', 'short' => 'テスト決済'],
        'external' => ['default' => '外部決済', 'short' => '外部決済'],
    ];

    $entry = $labels[$method] ?? null;
    if (!$entry) {
        return $method;
    }

    if ($context === 'short' && isset($entry['short'])) {
        return $entry['short'];
    }

    return $entry['default'] ?? $method;
}
/**
 * 金額を日本円形式でフォーマット
 * 
 * @param int|float $amount 金額
 * @param bool $withSymbol 円記号を付けるか（デフォルト: true）
 * @return string フォーマットされた金額
 * 
 * @example
 * echo formatPrice(1280); // ¥1,280
 * echo formatPrice(1280, false); // 1,280
 */
function formatPrice($amount, bool $withSymbol = true): string
{
    $formatted = number_format($amount);
    return $withSymbol ? '¥' . $formatted : $formatted;
}

/**
 * URLを生成
 * 
 * アプリケーションのベースURLを考慮してURLを生成します。
 * 
 * @param string $path パス
 * @return string 完全なURL
 * 
 * @example
 * <a href="<?= url('/products') ?>">商品一覧</a>
 * <img src="<?= url('/assets/img/logo.png') ?>">
 */
function url(string $path = ''): string
{
    $baseUrl = rtrim(APP_URL, '/');
    $path = ltrim($path, '/');
    return $baseUrl . '/' . $path;
}

/**
 * アセットURLを生成
 * 
 * @param string $path アセットファイルのパス
 * @return string アセットの完全なURL
 * 
 * @example
 * <link rel="stylesheet" href="<?= asset('css/style.css') ?>">
 * <script src="<?= asset('js/app.js') ?>"></script>
 */
function asset(string $path): string
{
    return url('assets/' . ltrim($path, '/'));
}

/**
 * 画像パスを公開URLへ正規化
 */
function normalizeProductImageUrl(?string $path): ?string
{
    if ($path === null) {
        return null;
    }

    $normalized = trim($path);
    if ($normalized === '') {
        return null;
    }

    if (preg_match('#^https?://#i', $normalized)) {
        return $normalized;
    }

    $normalized = str_replace('\\', '/', $normalized);
    $normalized = preg_replace('#^\./#', '', $normalized);
    $normalized = ltrim($normalized, '/');

    $stripPrefixes = ['public/', 'storage/', 'gin_ec/', 'htdocs/'];
    foreach ($stripPrefixes as $prefix) {
        if (str_starts_with($normalized, $prefix)) {
            $normalized = substr($normalized, strlen($prefix));
        }
    }

    $imagesPos = strpos($normalized, 'images/');
    if ($imagesPos !== false) {
        $normalized = substr($normalized, $imagesPos);
    }

    if ($normalized === '') {
        return null;
    }

    return url($normalized);
}

/**
 * 商品画像URLを取得（在庫切れ時は専用画像を返す）
 * 
 * @param array|null $product 商品データ
 * @param string $size 画像サイズ（'small', 'medium', 'large'）
 * @return string 画像URL
 */
function productImageUrl(?array $product, string $size = 'medium'): string
{
    // 在庫切れチェック
    if ($product && isset($product['stock']) && $product['stock'] <= 0) {
        return url('images/sold-out.png');
    }
    
    // 商品画像があればそれを返す
    if ($product) {
        $normalizedUrl = normalizeProductImageUrl($product['image_url'] ?? $product['image'] ?? null);
        if ($normalizedUrl) {
            return $normalizedUrl;
        }
    }
    
    // デフォルト画像
    return url('images/no-image.png');
}

/**
 * 古い入力値を取得（フォーム再表示用）
 * 
 * バリデーションエラー時にフォームの入力値を保持するために使用します。
 * 
 * @param string $key フィールド名
 * @param mixed $default デフォルト値
 * @return mixed 古い入力値
 * 
 * @example
 * <input type="text" name="email" value="<?= e(old('email')) ?>">
 */
function old(string $key, $default = '')
{
    return getSession('old_input')[$key] ?? $default;
}

/**
 * 古い入力値を保存
 * 
 * @param array $input 入力データ
 */
function saveOldInput(array $input): void
{
    setSession('old_input', $input);
}

/**
 * 古い入力値をクリア
 */
function clearOldInput(): void
{
    unsetSession('old_input');
}

/**
 * バリデーションエラーを取得
 * 
 * @param string|null $key フィールド名（nullの場合は全て）
 * @return mixed エラーメッセージ
 * 
 * @example
 * <?php if ($error = getError('email')): ?>
 *     <p class="text-red-500"><?= e($error) ?></p>
 * <?php endif; ?>
 */
function getError(?string $key = null)
{
    $errors = getSession('errors', []);
    
    if ($key === null) {
        return $errors;
    }
    
    return $errors[$key] ?? null;
}

/**
 * バリデーションエラーを保存
 * 
 * @param array $errors エラーメッセージの配列
 */
function saveErrors(array $errors): void
{
    setSession('errors', $errors);
}

/**
 * バリデーションエラーをクリア
 */
function clearErrors(): void
{
    unsetSession('errors');
}

/**
 * デバッグ用：変数の内容を整形して表示
 * 
 * @param mixed $var 表示する変数
 * @param bool $die 表示後に終了するか
 */
function dd($var, bool $die = true): void
{
    echo '<pre>';
    var_dump($var);
    echo '</pre>';
    
    if ($die) {
        die();
    }
}

/**
 * ランダムな文字列を生成
 * 
 * @param int $length 文字列長（デフォルト: 32）
 * @return string ランダム文字列
 * 
 * @example
 * $token = randomString(64);
 */
function randomString(int $length = 32): string
{
    return bin2hex(random_bytes($length / 2));
}

/**
 * メールアドレスのバリデーション
 * 
 * @param string $email メールアドレス
 * @return bool 有効な場合true
 */
function isValidEmail(string $email): bool
{
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

/**
 * 電話番号関連のヘルパー
 */

/**
 * 数字のみの電話番号に正規化
 */
function normalizePhoneNumber(string $phone): string
{
    return preg_replace('/\D+/', '', $phone);
}

/**
 * 電話番号のバリデーション（日本の形式）
 *
 * ハイフンや空白を含む入力でも、数字だけを取り出して10〜11桁かを確認します。
 */
function isValidPhone(string $phone): bool
{
    $digits = normalizePhoneNumber($phone);
    $length = strlen($digits);

    if ($length < 10 || $length > 11) {
        return false;
    }

    return substr($digits, 0, 1) === '0';
}

/**
 * 日本の電話番号にハイフンを付与
 */
function formatPhoneNumber(string $phone): string
{
    $digits = normalizePhoneNumber($phone);
    $length = strlen($digits);

    if ($length === 11) {
        return substr($digits, 0, 3) . '-' . substr($digits, 3, 4) . '-' . substr($digits, 7);
    }

    if ($length === 10) {
        $areaCode = substr($digits, 0, 2);
        if (in_array($areaCode, ['03', '06'], true)) {
            return substr($digits, 0, 2) . '-' . substr($digits, 2, 4) . '-' . substr($digits, 6);
        }
        return substr($digits, 0, 3) . '-' . substr($digits, 3, 3) . '-' . substr($digits, 6);
    }

    return $phone;
}

/**
 * ユーザーがログインしているかチェック
 * 
 * @return bool ログインしている場合true
 */
function isAuthenticated(): bool
{
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

/**
 * ログイン中のユーザーIDを取得
 * 
 * @return int|null ユーザーID、未ログインの場合null
 */
function getCurrentUserId(): ?int
{
    return $_SESSION['user_id'] ?? null;
}

/**
 * ログイン中のユーザーIDを取得（エイリアス）
 * 
 * @return int|null ユーザーID、未ログインの場合null
 */
function currentUserId(): ?int
{
    return getCurrentUserId();
}

/**
 * ログイン中のユーザー情報を取得
 * 
 * @return array|null ユーザー情報、未ログインの場合null
 */
function getCurrentUser(): ?array
{
    if (!isAuthenticated()) {
        return null;
    }
    
    return dbSelectOne(
        "SELECT id, name, email, points_balance, created_at FROM users WHERE id = ?",
        [getCurrentUserId()]
    );
}

/**
 * ユーザーのポイント残高を取得
 * 
 * @param int $userId ユーザーID
 * @return int ポイント残高
 */
function getUserPoints(int $userId): int
{
    $user = dbSelectOne(
        "SELECT points_balance FROM users WHERE id = ?",
        [$userId]
    );
    
    return $user ? (int)$user['points_balance'] : 0;
}

/**
 * ポイントを付与
 * 
 * @param int $userId ユーザーID
 * @param int $points 付与ポイント数
 * @param string $description 説明
 * @param int|null $orderId 関連注文ID
 * @param string|null $expiresAt 有効期限（YYYY-MM-DD HH:MM:SS）
 * @return bool 成功/失敗
 */
function addUserPoints(
    int $userId, 
    int $points, 
    string $description = '', 
    ?int $orderId = null, 
    ?string $expiresAt = null
): bool {
    try {
        // Phase 17: 新しいストアドプロシージャを使用
        // sp_award_points(user_id, amount, order_id, description)
        // 有効期限はストアドプロシージャ内で自動計算
        $db = getDb();
        $stmt = $db->prepare("CALL sp_award_points(?, ?, ?, ?)");
        $stmt->execute([
            $userId,
            $points,
            $orderId,
            $description ?: 'ポイント付与'
        ]);
        
        return true;
    } catch (Exception $e) {
        error_log("Failed to add points: " . $e->getMessage());
        return false;
    }
}

/**
 * ポイントを使用
 * 
 * @param int $userId ユーザーID
 * @param int $points 使用ポイント数（正の値）
 * @param string $description 説明
 * @param int|null $orderId 関連注文ID
 * @return bool 成功/失敗
 */
function useUserPoints(
    int $userId, 
    int $points, 
    string $description = '', 
    ?int $orderId = null
): bool {
    try {
        // Phase 17: 新しいストアドプロシージャを使用
        // sp_use_points(user_id, amount, order_id, description, OUT success, OUT message)
        $db = getDb();
        $stmt = $db->prepare("CALL sp_use_points(?, ?, ?, ?, @success, @message)");
        $stmt->execute([
            $userId,
            $points,
            $orderId,
            $description ?: 'ポイント使用'
        ]);
        
        // OUTパラメータを取得
        $result = $db->query("SELECT @success as success, @message as message")->fetch(PDO::FETCH_ASSOC);
        
        if ($result && $result['success']) {
            return true;
        } else {
            error_log("Failed to use points: " . ($result['message'] ?? 'Unknown error'));
            return false;
        }
    } catch (PDOException $e) {
        error_log("Failed to use points: " . $e->getMessage());
        return false;
    }
}

/**
 * ユーザーのポイント履歴を取得
 * 
 * @param int $userId ユーザーID
 * @param int $limit 取得件数
 * @param int $offset オフセット
 * @return array ポイント履歴の配列
 */
function getUserPointHistory(int $userId, int $limit = 20, int $offset = 0): array
{
    // Phase 17: point_transactionsテーブルから取得
    return dbSelect(
        "SELECT pt.*, o.id as order_id_ref
         FROM point_transactions pt
         LEFT JOIN orders o ON pt.order_id = o.id
         WHERE pt.user_id = ?
         ORDER BY pt.created_at DESC
         LIMIT ? OFFSET ?",
        [$userId, $limit, $offset]
    );
}

/**
 * 購入金額に応じて付与するポイント数を計算
 * 会員ランク別に還元率を適用
 * 
 * @param int $amount 注文金額
 * @param int|null $userId ユーザーID（省略時は現在のログインユーザー）
 * @return int 付与ポイント数
 */
function calculateEarnPoints(int $amount, ?int $userId = null): int
{
    // デフォルト還元率1%（非会員またはブロンズ未満）
    $rate = 0.01;

    // ユーザーID指定がない場合は現在のログインユーザー
    if ($userId === null && isLoggedIn()) {
        $userId = getUserId();
    }

    // 会員ランク取得
    if ($userId) {
        $user = dbSelectOne(
            "SELECT member_rank FROM users WHERE id = ? LIMIT 1",
            [$userId]
        );

        if ($user && isset($user['member_rank'])) {
            require_once __DIR__ . '/helpers/MemberRankHelper.php';
            $rate = \App\Helpers\MemberRankHelper::getPointsRate($user['member_rank']) / 100;
        }
    }

    return (int)floor($amount * $rate);
}

/**
 * ===== カートセッションヘルパー =====
 *
 * カート操作ロジックを複数のコントローラーから共通利用できるように、
 * セッション読み書きや商品追加などの処理を関数化しています。
 */

function cartSessionEmpty(): array
{
    return [
        'items' => [],
        'subtotal' => 0,
        'shipping_fee' => 0,
        'discount' => 0,
        'total' => 0,
    ];
}

function cartSessionGet(): array
{
    $rawCart = getSession('cart');

    if (is_array($rawCart) && isset($rawCart['items'])) {
        $cart = [
            'items' => is_array($rawCart['items']) ? $rawCart['items'] : [],
            'subtotal' => $rawCart['subtotal'] ?? 0,
            'shipping_fee' => $rawCart['shipping_fee'] ?? 0,
            'discount' => $rawCart['discount'] ?? 0,
            'total' => $rawCart['total'] ?? 0,
        ];
    } elseif (is_array($rawCart) && !isset($rawCart['items'])) {
        // 旧形式（productId => quantity）の互換対応
        $cart = cartSessionEmpty();
        foreach ($rawCart as $productId => $quantity) {
            if (!is_numeric($productId)) {
                continue;
            }
            $product = cartSessionFindProduct((int)$productId);
            if (!$product) {
                continue;
            }
            $qty = max(1, (int)$quantity);
            $cart['items'][] = cartSessionBuildItem($product, $qty);
        }
    } else {
        $cart = cartSessionEmpty();
    }

    $cart = cartSessionRefreshItems($cart);
    $cart = cartSessionRecalculateTotals($cart);
    setSession('cart', $cart);

    return $cart;
}

function cartSessionSave(array $cart): void
{
    $cart = cartSessionRecalculateTotals($cart);
    setSession('cart', $cart);
}

function cartSessionRefreshItems(array $cart): array
{
    $updated = [];

    foreach ($cart['items'] as $item) {
        $productId = $item['product_id'] ?? ($item['product']['id'] ?? null);
        $quantity = $item['quantity'] ?? ($item['product']['quantity'] ?? null);

        if (!$productId || !$quantity) {
            continue;
        }

        $product = cartSessionFindProduct((int)$productId);
        if (!$product) {
            continue;
        }

        $quantity = min((int)$quantity, (int)$product['stock']);
        if ($quantity < 1) {
            continue;
        }

        $updated[] = cartSessionBuildItem($product, $quantity);
    }

    $cart['items'] = $updated;
    return $cart;
}

function cartSessionFindProduct(int $productId): ?array
{
    $product = dbSelectOne(
        "SELECT * FROM products WHERE id = ? AND status = 'active'",
        [$productId]
    );

    return $product ?: null;
}

function cartSessionBuildItem(array $product, int $quantity): array
{
    return [
        'product_id' => (int)$product['id'],
        'name' => $product['name'],
        'price' => (int)$product['price'],
        'quantity' => $quantity,
        'image_url' => $product['image_url'] ?? ($product['images_json'] ?? null),
        'stock' => (int)$product['stock'],
        'item_total' => (int)$product['price'] * $quantity,
    ];
}

function cartSessionFindItemIndex(array $items, int $productId): ?int
{
    foreach ($items as $index => $item) {
        if ((int)($item['product_id'] ?? 0) === $productId) {
            return $index;
        }
    }

    return null;
}

function cartSessionAddItem(array $cart, array $product, int $quantity): array
{
    $productId = (int)$product['id'];
    $index = cartSessionFindItemIndex($cart['items'], $productId);

    if ($index !== null) {
        $newQuantity = $cart['items'][$index]['quantity'] + $quantity;
        if ($newQuantity > $product['stock']) {
            $newQuantity = (int)$product['stock'];
        }
        $cart['items'][$index] = cartSessionBuildItem($product, $newQuantity);
    } else {
        $cart['items'][] = cartSessionBuildItem($product, $quantity);
    }

    return $cart;
}

function cartSessionMergeItems(array $cart, array $items): array
{
    foreach ($items as $item) {
        $productId = (int)($item['product_id'] ?? 0);
        $quantity = (int)($item['quantity'] ?? 0);
        if ($productId <= 0 || $quantity <= 0) {
            continue;
        }

        $product = cartSessionFindProduct($productId);
        if (!$product || (int)$product['stock'] <= 0) {
            continue;
        }

        $cart = cartSessionAddItem(
            $cart,
            $product,
            min($quantity, (int)$product['stock'])
        );
    }

    return $cart;
}

function cartSessionRecalculateTotals(array $cart): array
{
    $subtotal = 0;
    foreach ($cart['items'] as &$item) {
        $item['item_total'] = $item['price'] * $item['quantity'];
        $subtotal += $item['item_total'];
    }
    unset($item);

    $shippingFee = cartSessionCalculateShippingFee($subtotal);
    $discount = max(0, (int)($cart['discount'] ?? 0));
    $total = max(0, $subtotal + $shippingFee - $discount);

    $cart['items'] = array_values($cart['items']);
    $cart['subtotal'] = $subtotal;
    $cart['shipping_fee'] = $shippingFee;
    $cart['discount'] = $discount;
    $cart['total'] = $total;

    return $cart;
}

function cartSessionCalculateShippingFee(int $subtotal): int
{
    if ($subtotal === 0) {
        return 0;
    }

    $defaultThreshold = 5000;
    $shippingFee = 500;
    $threshold = $defaultThreshold;

    if (isLoggedIn()) {
        $user = dbSelectOne(
            "SELECT member_rank FROM users WHERE id = ? LIMIT 1",
            [getUserId()]
        );

        if ($user && isset($user['member_rank'])) {
            require_once __DIR__ . '/helpers/MemberRankHelper.php';
            $rank = $user['member_rank'];
            $rankThreshold = max(0, (int)\App\Helpers\MemberRankHelper::getFreeShippingThreshold($rank));

            if ($rankThreshold === 0) {
                return 0;
            }

            if ($rankThreshold > 0 && $rankThreshold < $threshold) {
                $threshold = $rankThreshold;
            }
        }
    }

    return $subtotal >= $threshold ? 0 : $shippingFee;
}

/**
 * 注文IDごとの商品サマリーを取得
 *
 * @param array $orderIds   対象の注文ID配列
 * @param int   $limitPerOrder 各注文ごとに保持する最大件数（0以下で制限なし）
 * @return array [order_id => [['name' => string, 'quantity' => int], ...]]
 */
function getOrderItemSummaries(array $orderIds, int $limitPerOrder = 3): array
{
    $orderIds = array_values(array_filter(array_map('intval', $orderIds)));
    if (empty($orderIds)) {
        return [];
    }

    $placeholders = implode(',', array_fill(0, count($orderIds), '?'));
    $rows = dbSelect(
        "SELECT oi.order_id, p.name, oi.quantity
         FROM order_items oi
         LEFT JOIN products p ON oi.product_id = p.id
         WHERE oi.order_id IN ($placeholders)
         ORDER BY oi.order_id, oi.id",
        $orderIds
    );

    $summaries = [];
    foreach ($rows as $row) {
        $orderId = (int)$row['order_id'];
        if (!isset($summaries[$orderId])) {
            $summaries[$orderId] = [];
        }

        if ($limitPerOrder > 0 && count($summaries[$orderId]) >= $limitPerOrder) {
            continue;
        }

        $summaries[$orderId][] = [
            'name' => $row['name'] ?? '商品名未設定',
            'quantity' => (int)($row['quantity'] ?? 0),
        ];
    }

    return $summaries;
}

/**
 * 注文ごとの全商品明細を取得
 */
function getOrderItemsByOrder(array $orderIds): array
{
    $orderIds = array_values(array_filter(array_map('intval', $orderIds)));
    if (empty($orderIds)) {
        return [];
    }

    $placeholders = implode(',', array_fill(0, count($orderIds), '?'));
    $rows = dbSelect(
        "SELECT 
            oi.order_id,
            oi.product_id,
            COALESCE(p.name, oi.product_name) AS product_name,
            oi.quantity,
            oi.unit_price,
            (oi.unit_price * oi.quantity) AS line_total,
            COALESCE(p.image_url, '') AS product_image
        FROM order_items oi
        LEFT JOIN products p ON oi.product_id = p.id
        WHERE oi.order_id IN ($placeholders)
        ORDER BY oi.order_id, oi.id",
        $orderIds
    );

    $grouped = [];
    foreach ($rows as $row) {
        $orderId = (int)$row['order_id'];
        if (!isset($grouped[$orderId])) {
            $grouped[$orderId] = [];
        }
        
        // 画像URLを適切なパスに変換
        $imageUrl = $row['product_image'] ?? null;
        if ($imageUrl && strpos($imageUrl, 'http') !== 0) {
            // 相対パスの場合は絶対パスに変換しない（url()をビュー側で使用）
            $imageUrl = $imageUrl;
        }
        
        $grouped[$orderId][] = [
            'product_id' => (int)($row['product_id'] ?? 0),
            'name' => $row['product_name'] ?? '商品名未設定',
            'quantity' => (int)($row['quantity'] ?? 0),
            'unit_price' => (int)($row['unit_price'] ?? 0),
            'line_total' => (int)($row['line_total'] ?? 0),
            'image_url' => $imageUrl,
        ];
    }

    return $grouped;
}

/**
 * 注文ごとの割引内訳を取得
 *
 * @param int $orderId
 * @return array{points:int, points_used:int, coupon:int}
 */
function fetchOrderDiscountBreakdown(int $orderId): array
{
    if ($orderId <= 0) {
        return [
            'points' => 0,
            'points_used' => 0,
            'coupon' => 0,
        ];
    }

    static $pointValueCache = null;

    if ($pointValueCache === null) {
        $pointValueCache = 1;
        if (class_exists('Point')) {
            try {
                $pointModel = new Point();
                $settings = $pointModel->getSettings();
                $pointValueCache = max(1, (int)($settings['point_value'] ?? 1));
            } catch (\Throwable $e) {
                error_log('Failed to load point settings: ' . $e->getMessage());
            }
        }
    }

    $pointValue = $pointValueCache ?? 1;

    $pointsRow = dbSelectOne(
        "SELECT COALESCE(SUM(ABS(amount)), 0) AS used_points
         FROM point_transactions
         WHERE order_id = ? AND type = 'used'",
        [$orderId]
    );
    $pointsUsed = (int)($pointsRow['used_points'] ?? 0);
    $pointsDiscount = $pointsUsed * $pointValue;

    $couponRow = dbSelectOne(
        "SELECT discount_amount FROM coupon_usages WHERE order_id = ? LIMIT 1",
        [$orderId]
    );
    $couponDiscount = (int)($couponRow['discount_amount'] ?? 0);

    return [
        'points' => $pointsDiscount,
        'points_used' => $pointsUsed,
        'coupon' => $couponDiscount,
    ];
}

/**
 * reCAPTCHAトークンを検証
 * 
 * @param string      $token          reCAPTCHAレスポンストークン
 * @param float       $minScore       v3スコアの閾値（0〜1）
 * @param string|null $expectedAction 期待するaction（未指定ならチェックしない）
 * @return bool 検証成功/失敗
 */
function verifyRecaptcha(string $token, float $minScore = 0.5, ?string $expectedAction = null): bool
{
    $secretKey = $_ENV['RECAPTCHA_SECRET_KEY'] ?? getenv('RECAPTCHA_SECRET_KEY');
    
    if (!$secretKey || $secretKey === 'your_recaptcha_secret_key_here') {
        // 開発環境でキーが未設定の場合は常にtrue
        return true;
    }
    
    $url = 'https://www.google.com/recaptcha/api/siteverify';
    $data = [
        'secret' => $secretKey,
        'response' => $token,
        'remoteip' => $_SERVER['REMOTE_ADDR'] ?? ''
    ];
    
    $options = [
        'http' => [
            'method' => 'POST',
            'header' => 'Content-Type: application/x-www-form-urlencoded',
            'content' => http_build_query($data),
            'timeout' => 10
        ]
    ];
    
    try {
        $context = stream_context_create($options);
        $result = @file_get_contents($url, false, $context);

        if ($result === false) {
            // file_get_contents失敗時はcURLで再試行
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);
            $result = curl_exec($ch);
            if ($result === false) {
                error_log("reCAPTCHA verification failed: Unable to connect to Google API (" . curl_error($ch) . ")");
                curl_close($ch);
                return false;
            }
            curl_close($ch);
        }
        
        $response = json_decode($result, true);
        
        if (!$response || !isset($response['success'])) {
            error_log("reCAPTCHA verification failed: Invalid response format: {$result}");
            return false;
        }
        
        if (!$response['success']) {
            error_log("reCAPTCHA verification failed: " . json_encode($response['error-codes'] ?? []));
            return false;
        }

        if (isset($response['score']) && $response['score'] < $minScore) {
            error_log("reCAPTCHA verification failed: score too low ({$response['score']})");
            return false;
        }

        if ($expectedAction !== null && isset($response['action']) && $response['action'] !== $expectedAction) {
            error_log("reCAPTCHA verification failed: unexpected action {$response['action']} (expected {$expectedAction})");
            return false;
        }
        
        return true;
    } catch (Exception $e) {
        error_log("reCAPTCHA verification error: " . $e->getMessage());
        return false;
    }
}

/**
 * 認証済みユーザー情報を取得
 * 
 * @return array|null ユーザー情報（未ログインの場合はnull）
 */
function getAuthUser(): ?array
{
    return getCurrentUser();
}

// ========================================
// 商品バリアント・分類関連関数
// ========================================

/**
 * 商品のバリアント一覧を取得
 *
 * @param string $productName 商品名
 * @param string $category カテゴリ
 * @param int $productId 商品ID
 * @param bool $includeRelatedProducts 関連商品を含めるか
 * @param array|null $productData 参照用の商品データ
 * @return array
 */
function getProductVariants(string $productName, string $category, int $productId, bool $includeRelatedProducts = false, ?array $productData = null): array
{
    $baseProduct = $productData;
    if ($baseProduct === null) {
        $baseProduct = dbSelectOne("SELECT * FROM products WHERE id = ?", [$productId]);
    }

    if (!$baseProduct) {
        return [];
    }

    if ((int)($baseProduct['requires_variants'] ?? 1) === 0) {
        return [buildSingleVariantFromProductRecord($baseProduct)];
    }

    $variants = hydrateVariantsForProducts([$baseProduct])[$productId] ?? [];
    if (empty($variants)) {
        return [buildSingleVariantFromProductRecord($baseProduct)];
    }

    return $variants;
}

function buildVariantsFromProductCatalog(PDO $pdo, array $baseProduct, bool $includeRelatedProducts = false): array
{
    $baseName = getProductBaseName($baseProduct['name'] ?? '');
    if ($baseName === '') {
        $baseName = $baseProduct['name'] ?? '';
    }

    if ($baseName === '') {
        return [];
    }

    $conditions = [
        'p.status = \'active\'',
        'p.id != :product_id',
        '(p.name = :base_name OR p.name LIKE :like_suffix OR p.name LIKE :like_fullwidth OR p.name LIKE :like_dash OR p.name LIKE :like_contains)'
    ];

    $params = [
        ':product_id' => $baseProduct['id'],
        ':base_name' => $baseName,
        ':like_suffix' => $baseName . '%',
        ':like_fullwidth' => $baseName . '（%',
        ':like_dash' => $baseName . ' -%',
        ':like_contains' => '%' . $baseName . '%',
    ];

    if (!$includeRelatedProducts) {
        $conditions[] = 'p.category = :category';
        $params[':category'] = $baseProduct['category'];
    }

    $sql = 'SELECT * FROM products p WHERE ' . implode(' AND ', $conditions) . ' ORDER BY p.price ASC, p.id ASC';
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $variants = [];
    $candidates = array_merge([$baseProduct], $rows);
    $seenIds = [];

    foreach ($candidates as $candidate) {
        if (!isset($candidate['id'])) {
            continue;
        }

        if (getProductBaseName($candidate['name'] ?? '') !== $baseName) {
            continue;
        }

        $candidateId = (int) $candidate['id'];
        if (isset($seenIds[$candidateId])) {
            continue;
        }
        $seenIds[$candidateId] = true;

        $variants[] = transformProductToVariant($candidate, (int) $baseProduct['id']);
    }

    $groupKey = classifyProductCategoryGroup($baseProduct['category'] ?? 'other', $baseProduct['name'] ?? '');
    $displayPlan = getVariantDisplayPlan($groupKey);
    $planOrder = array_flip($displayPlan);

    $normalizedVariants = [];
    foreach ($variants as $variant) {
        $displayName = $variant['type_display'] ?? detectVariantDisplayName($variant['name'] ?? '');
        $canonical = normalizeVariantDisplayForPlan($displayName, $displayPlan);
        if ($canonical === null) {
            continue;
        }

        $variant['type_display'] = $canonical;
        $variant['type_name'] = $variant['type_name'] ?: $canonical;
        $variant['type_name_ja'] = $variant['type_name_ja'] ?: $canonical;
        $variant['_plan_order'] = $planOrder[$canonical] ?? 999;
        $normalizedVariants[] = $variant;
    }

    if (empty($normalizedVariants)) {
        $fallback = transformProductToVariant($baseProduct, (int) $baseProduct['id']);
        $fallback['type_display'] = '標準';
        $fallback['type_name'] = '標準';
        $fallback['type_name_ja'] = '標準';
        return [$fallback];
    }

    usort($normalizedVariants, static function(array $a, array $b): int {
        $orderA = $a['_plan_order'] ?? 999;
        $orderB = $b['_plan_order'] ?? 999;
        if ($orderA !== $orderB) {
            return $orderA <=> $orderB;
        }

        $weightA = $a['weight_grams'] ?? null;
        $weightB = $b['weight_grams'] ?? null;
        if ($weightA !== null && $weightB !== null && $weightA !== $weightB) {
            return $weightA <=> $weightB;
        }

        return ($a['price'] ?? 0) <=> ($b['price'] ?? 0);
    });

    foreach ($normalizedVariants as &$variant) {
        unset($variant['_plan_order']);
    }
    unset($variant);

    return $normalizedVariants;
}

function normalizeDatabaseVariantsForDisplay(array $variants, array $baseProduct, array $displayPlan): array
{
    $baseImage = normalizeProductImageUrl($baseProduct['image_url'] ?? null) ?? ($baseProduct['image_url'] ?? null);
    $baseName = getProductBaseName($baseProduct['name'] ?? '');
    if ($baseName === '') {
        $baseName = $baseProduct['name'] ?? '';
    }
    $categoryGroup = classifyProductCategoryGroup($baseProduct['category'] ?? 'other', $baseProduct['name'] ?? '');

    foreach ($variants as &$variant) {
        // バリアント固有の画像があればそれを使用、なければベース商品の画像を使用
        $variantImage = normalizeProductImageUrl($variant['image_url'] ?? null);
        $variant['image_url'] = $variantImage ?: $baseImage;
        $usingFallbackImage = !$variantImage || $variant['image_url'] === $baseImage;
        $variant['type_name_en'] = $variant['type_name'] ?? null;

        $displayLabel = resolveVariantDisplayLabel($variant, $displayPlan, $baseName);
        $variant['type_display'] = $displayLabel;
        $variant['type_name'] = $displayLabel;
        $variant['type_name_ja'] = $displayLabel;

        if ($usingFallbackImage) {
            $derivedImage = deriveVariantImageByType($baseImage, $displayLabel, $categoryGroup);
            if ($derivedImage) {
                $variant['image_url'] = $derivedImage;
            }
        }

        // バリアント名の生成（基本名 + タイプ）
        if (empty($variant['variant_name'])) {
            $variant['variant_name'] = $baseName !== ''
                ? sprintf('%s（%s）', $baseName, $displayLabel)
                : $displayLabel;
        }

        if (empty($variant['name'])) {
            $variant['name'] = $variant['variant_name'];
        }

        $variant['price'] = isset($variant['price']) ? (int) $variant['price'] : 0;
        $variant['stock'] = isset($variant['stock']) ? (int) $variant['stock'] : 0;
        $variant['source'] = 'database';
    }
    unset($variant);

    return $variants;
}

function getVariantImagePrefixCollections(): array
{
    static $collections = null;
    if ($collections !== null) {
        return $collections;
    }

    $collections = [
        'default' => [
            '豆' => 'Whole-Bean',
            '粉' => 'Ground-Coffee',
            '粉末' => 'Ground-Coffee',
            'ドリップバッグ' => 'Drip-Bag-Coffee',
            '水出しパック' => 'Cold-Brew-Pack',
            'インスタント' => 'Instant-Coffee-Stick',
            '即溶' => 'Instant-Coffee-Stick',
            '生豆' => 'Green-Coffee-Bean',
        ],
        'coffee' => [
            '豆' => 'Whole-Bean',
            '粉' => 'Ground-Coffee',
            '粉末' => 'Ground-Coffee',
            'ドリップバッグ' => 'Drip-Bag-Coffee',
            '水出しパック' => 'Cold-Brew-Pack',
            'インスタント' => 'Instant-Coffee-Stick',
            '即溶' => 'Instant-Coffee-Stick',
            '生豆' => 'Green-Coffee-Bean',
        ],
        'tea' => [
            '茶葉' => 'Leaf',
            'ルーズリーフ' => 'Leaf',
            '粉末' => 'Powder',
            '粉' => 'Powder',
            'パウダー' => 'Powder',
            '抹茶パウダー' => 'Powder',
            'ティーバッグ' => 'TeaBag',
            'ティバッグ' => 'TeaBag',
            '水出しパック' => 'ColdBrewPack',
            'インスタント' => 'InstantStick',
            '即溶' => 'InstantStick',
            'ボトル' => 'PETBottle',
            'ペットボトル' => 'PETBottle',
            '缶入り' => 'Can',
            '茶筒' => 'Can',
            '小分け' => 'SmallPack',
            'スティック' => 'Stick',
            'ラテ用' => 'Latte',
        ],
    ];

    return $collections;
}

function mapVariantLabelToImagePrefix(string $label, ?string $categoryGroup = null): ?string
{
    $label = trim($label);
    if ($label === '') {
        return null;
    }

    $collections = getVariantImagePrefixCollections();
    $groupKey = match ($categoryGroup) {
        'coffee' => 'coffee',
        'tea_normal', 'tea_special', 'matcha_ceremonial', 'matcha_regular', 'matcha_latte' => 'tea',
        default => 'default',
    };

    if (isset($collections[$groupKey][$label])) {
        return $collections[$groupKey][$label];
    }

    foreach ($collections as $collection) {
        if (isset($collection[$label])) {
            return $collection[$label];
        }
    }

    return null;
}

function deriveVariantImageByType(?string $baseImageUrl, string $typeLabel, ?string $categoryGroup = null): ?string
{
    if (!$baseImageUrl || $typeLabel === '') {
        return null;
    }

    $targetPrefix = mapVariantLabelToImagePrefix($typeLabel, $categoryGroup);
    if ($targetPrefix === null) {
        return null;
    }

    $path = parse_url($baseImageUrl, PHP_URL_PATH);
    if (!$path) {
        return null;
    }

    $relativePath = ltrim($path, '/');
    $appPath = trim(parse_url(APP_URL, PHP_URL_PATH) ?? '', '/');
    if ($appPath !== '' && str_starts_with($relativePath, $appPath . '/')) {
        $relativePath = substr($relativePath, strlen($appPath) + 1);
    }

    if ($relativePath === '') {
        return null;
    }

    $directory = trim(dirname($relativePath), '/');
    $fileName = basename($relativePath);

    $collections = getVariantImagePrefixCollections();
    $prefixCandidates = [];
    foreach ($collections as $collection) {
        foreach ($collection as $prefix) {
            $prefixCandidates[$prefix] = true;
        }
    }
    $orderedPrefixes = array_keys($prefixCandidates);
    usort($orderedPrefixes, static fn(string $a, string $b): int => strlen($b) <=> strlen($a));

    $basePrefix = null;
    foreach ($orderedPrefixes as $prefix) {
        if (str_starts_with($fileName, $prefix . '-')) {
            $basePrefix = $prefix;
            break;
        }
    }

    if ($basePrefix === null) {
        return null;
    }

    $slugWithExt = substr($fileName, strlen($basePrefix) + 1);
    if ($slugWithExt === false || $slugWithExt === '') {
        return null;
    }

    $dotPos = strrpos($slugWithExt, '.');
    if ($dotPos === false) {
        return null;
    }

    $slug = substr($slugWithExt, 0, $dotPos);
    $extension = substr($slugWithExt, $dotPos + 1);
    if ($slug === '' || $extension === '') {
        return null;
    }

    $relativeDir = $directory !== '' ? $directory : '.';
    $candidateRelative = ($relativeDir === '.' ? '' : $relativeDir . '/') . $targetPrefix . '-' . $slug . '.' . $extension;
    $absolutePath = PUBLIC_PATH . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $candidateRelative);

    if (is_file($absolutePath)) {
        return url($candidateRelative);
    }

    return null;
}

function resolveVariantDisplayLabel(array $variant, array $displayPlan, string $fallbackName = ''): string
{
    $metadataCandidates = [];
    $detectedCandidates = [];

    if (!empty($variant['type_name_ja'])) {
        $metadataCandidates[] = $variant['type_name_ja'];
    }

    if (!empty($variant['type_name_en'])) {
        $mapped = mapProductTypeEnglishToJa($variant['type_name_en']);
        if ($mapped) {
            $metadataCandidates[] = $mapped;
        }
    }

    if (!empty($variant['type_name'])) {
        $mapped = mapProductTypeEnglishToJa($variant['type_name']);
        if ($mapped) {
            $metadataCandidates[] = $mapped;
        }
    }

    if (!empty($variant['variant_name'])) {
        $detectedCandidates[] = detectVariantDisplayName($variant['variant_name']);
    }

    if (!empty($variant['name'])) {
        $detectedCandidates[] = detectVariantDisplayName($variant['name']);
    }

    if ($fallbackName !== '') {
        $detectedCandidates[] = detectVariantDisplayName($fallbackName);
    }

    $firstMetadata = null;
    foreach ($metadataCandidates as $candidate) {
        if (!$candidate) {
            continue;
        }
        if ($firstMetadata === null) {
            $firstMetadata = $candidate;
        }
        $canonical = normalizeVariantDisplayForPlan($candidate, $displayPlan);
        if ($canonical !== null) {
            return $canonical;
        }
    }

    $firstDetected = null;
    foreach ($detectedCandidates as $candidate) {
        if (!$candidate) {
            continue;
        }
        if ($firstDetected === null) {
            $firstDetected = $candidate;
        }
        $canonical = normalizeVariantDisplayForPlan($candidate, $displayPlan);
        if ($canonical !== null) {
            if ($firstMetadata === null) {
                return $canonical;
            }
            continue;
        }
    }

    if ($firstMetadata !== null) {
        return $firstMetadata;
    }

    if ($firstDetected !== null) {
        return $firstDetected;
    }

    return $displayPlan[0] ?? '標準';
}

function mapProductTypeEnglishToJa(?string $typeName): ?string
{
    if ($typeName === null) {
        return null;
    }

    $map = [
        'Whole Bean' => '豆',
        'Ground' => '粉',
        'Drip Bag' => 'ドリップバッグ',
        'Cold Brew Pack' => '水出しパック',
        'Instant Coffee' => 'インスタント',
        'Green Coffee Bean' => '生豆',
        'Loose Leaf' => '茶葉',
        'Powder' => '粉末',
        'Tea Bag' => 'ティーバッグ',
        'Glass Bottle Iced Tea' => 'ボトル',
        'Cold Tea PET Sample' => 'ボトル',
        'Personalized Diagnosis Bottle' => 'ボトル',
        'Concentrated Cafe Base' => 'ボトル',
    ];

    return $map[$typeName] ?? $typeName;
}

function mergeVariantSourcesWithDisplayPlan(
    array $databaseVariants,
    array $baseProduct,
    array $displayPlan,
    PDO $pdo,
    bool $includeRelatedProducts
): array {
    $grouped = [];
    foreach ($displayPlan as $label) {
        $grouped[$label] = [];
    }

    foreach ($databaseVariants as $variant) {
        $label = $variant['type_display'] ?? $variant['type_name'] ?? null;
        if ($label === null) {
            continue;
        }
        if (!array_key_exists($label, $grouped)) {
            $grouped[$label] = [];
        }
        $grouped[$label][] = $variant;
    }

    $missing = array_filter($displayPlan, static function (string $label) use ($grouped): bool {
        return empty($grouped[$label]);
    });

    if (!empty($missing)) {
        $catalogVariants = buildVariantsFromProductCatalog($pdo, $baseProduct, $includeRelatedProducts);
        foreach ($catalogVariants as $catalogVariant) {
            $label = $catalogVariant['type_display'] ?? $catalogVariant['type_name'] ?? null;
            if ($label === null) {
                $label = detectVariantDisplayName($catalogVariant['name'] ?? '') ?: null;
            }
            $label = $label ? normalizeVariantDisplayForPlan($label, $displayPlan) : null;

            if ($label === null || !in_array($label, $missing, true)) {
                continue;
            }

            $catalogVariant['type_display'] = $label;
            $catalogVariant['type_name'] = $label;
            $catalogVariant['type_name_ja'] = $label;
            $catalogVariant['source'] = $catalogVariant['source'] ?? 'catalog';
            $catalogVariant['legacy_product_id'] = $catalogVariant['product_id'] ?? null;
            if (!empty($catalogVariant['product_id']) && defined('LEGACY_VARIANT_ID_OFFSET')) {
                $catalogVariant['id'] = LEGACY_VARIANT_ID_OFFSET + (int) $catalogVariant['product_id'];
            }
            $grouped[$label][] = $catalogVariant;

            $missing = array_values(array_diff($missing, [$label]));
            if (empty($missing)) {
                break;
            }
        }
    }

    $extraLabels = [];
    foreach ($grouped as $label => $items) {
        if (in_array($label, $displayPlan, true)) {
            continue;
        }
        if (!empty($items)) {
            $extraLabels[] = $label;
        }
    }

    $orderedLabels = array_merge($displayPlan, $extraLabels);

    $result = [];
    foreach ($orderedLabels as $label) {
        if (empty($grouped[$label])) {
            continue;
        }

        usort($grouped[$label], static function (array $a, array $b): int {
            $weightA = $a['weight_grams'] ?? null;
            $weightB = $b['weight_grams'] ?? null;
            if ($weightA !== null && $weightB !== null && $weightA !== $weightB) {
                return $weightA <=> $weightB;
            }

            $priceA = $a['price'] ?? 0;
            $priceB = $b['price'] ?? 0;
            if ($priceA !== $priceB) {
                return $priceA <=> $priceB;
            }

            return ($a['id'] ?? 0) <=> ($b['id'] ?? 0);
        });

        foreach ($grouped[$label] as $variant) {
            $result[] = $variant;
        }
    }

    return $result;
}

function transformProductToVariant(array $productRow, int $activeProductId): array
{
    $typeKey = getProductTypeKey($productRow['name'] ?? '');
    $typeOrder = getProductTypeOrder($typeKey);
    $typeName = extractVariantType($productRow['name'] ?? '');
    $typeNameJa = extractVariantTypeJa($productRow['name'] ?? '');
    $weightGrams = extractWeightGramsFromName($productRow['name'] ?? '');
    $hasDiscount = isset($productRow['discount_price'])
        && (int)$productRow['discount_price'] > 0
        && isset($productRow['price'])
        && (int)$productRow['discount_price'] < (int)$productRow['price'];
    $price = $hasDiscount
        ? (int) $productRow['discount_price']
        : (int) ($productRow['price'] ?? 0);
    $compareAt = $hasDiscount ? (int) $productRow['price'] : null;

    return [
        'id' => (int) $productRow['id'],
        'product_id' => (int) $productRow['id'],
        'variant_name' => $productRow['name'] ?? '',
        'name' => $productRow['name'] ?? '',
        'type_name' => $typeName,
        'type_name_ja' => $typeNameJa,
        'type_order' => $typeOrder,
        'type_display' => detectVariantDisplayName($productRow['name'] ?? ''),
        'weight_grams' => $weightGrams,
        'weight' => $weightGrams ? ($weightGrams . 'g') : null,
        'price' => $price,
        'compare_at_price' => $compareAt,
        'stock' => (int) ($productRow['stock'] ?? 0),
        'sku' => array_key_exists('sku', $productRow) ? $productRow['sku'] : null,
        'image_url' => normalizeProductImageUrl($productRow['image_url'] ?? null) ?? null,
        'status' => $productRow['status'] ?? 'active',
        'is_default' => ((int) $productRow['id'] === $activeProductId) ? 1 : 0,
        'source' => 'product',
    ];
}

function extractWeightGramsFromName(string $productName): ?int
{
    $normalized = str_replace('　', ' ', $productName);

    if (preg_match('/(\d+(?:\.\d+)?)\s*(kg|キロ)/iu', $normalized, $matches)) {
        return (int) round((float) $matches[1] * 1000);
    }

    if (preg_match('/(\d+(?:\.\d+)?)\s*(g|グラム)/iu', $normalized, $matches)) {
        return (int) round((float) $matches[1]);
    }

    if (preg_match('/(\d+)\s*(ml|mL)/u', $normalized, $matches)) {
        return (int) $matches[1];
    }

    if (preg_match('/(\d+)\s*(包|袋|本|枚)/u', $normalized, $matches)) {
        return (int) $matches[1];
    }

    return null;
}

/**
 * バリアント情報を表示用に整形
 * 
 * @param array $variant バリアント情報
 * @return array 整形されたバリアント情報
 */
function formatVariantForDisplay(array $variant): array
{
    $variant['type_key'] = getProductTypeKey($variant['name']);
    $variant['type_display'] = getProductTypeDisplayName($variant['type_key']);
    $variant['type_order'] = getProductTypeOrder($variant['type_key']);
    
    return $variant;
}

/**
 * バリアントを表示順にソート
 * 
 * @param array $variants バリアント配列
 * @return array ソート済みバリアント配列
 */
function sortVariantsForDisplay(array $variants): array
{
    usort($variants, function($a, $b) {
        // 1. type_orderで並び替え
        $typeCompare = ($a['type_order'] ?? 999) <=> ($b['type_order'] ?? 999);
        if ($typeCompare !== 0) return $typeCompare;
        
        // 2. 重量で並び替え（数値変換）
        $weightA = (int)preg_replace('/\D/', '', $a['weight'] ?? '0');
        $weightB = (int)preg_replace('/\D/', '', $b['weight'] ?? '0');
        $weightCompare = $weightA <=> $weightB;
        if ($weightCompare !== 0) return $weightCompare;
        
        // 3. 価格で並び替え
        return ($a['price'] ?? 0) <=> ($b['price'] ?? 0);
    });
    
    return $variants;
}

/**
 * 商品タイプのキーを取得
 * 
 * @param string $productName 商品名
 * @return string タイプキー
 */
function getProductTypeKey(string $productName): string
{
    // コーヒー系タイプ（茶葉より優先チェック）
    if (preg_match('/豆|ホールビーン|Whole.*Bean/iu', $productName)) return 'bean';
    if (preg_match('/粉末|グラインド|Ground/iu', $productName)) return 'ground';
    if (preg_match('/ドリップ|Drip.*Bag/iu', $productName)) return 'drip';
    if (preg_match('/水出し|コールドブリュー|Cold.*Brew/iu', $productName)) return 'cold_brew';
    if (preg_match('/インスタント|Instant/iu', $productName)) return 'instant';
    if (preg_match('/生豆|グリーン.*ビーン|Green.*Bean/iu', $productName)) return 'green_bean';
    
    // 茶葉系タイプ
    if (preg_match('/茶葉|リーフ|Leaf/iu', $productName)) return 'tea_leaf';
    if (preg_match('/粉末/u', $productName)) return 'powder';
    if (preg_match('/ティーバッグ|Tea.*Bag/iu', $productName)) return 'teabag';
    if (preg_match('/スティック|Stick/iu', $productName)) return 'stick';
    if (preg_match('/カプセル|Capsule/iu', $productName)) return 'capsule';
    
    return 'other';
}

/**
 * 商品タイプの表示名を取得
 * 
 * @param string $typeKey タイプキー
 * @return string 表示名
 */
function getProductTypeDisplayName(string $typeKey): string
{
    $types = [
        'bean' => '豆',
        'ground' => '粉末',
        'drip' => 'ドリップバッグ',
        'cold_brew' => '水出しパック',
        'instant' => 'インスタント',
        'green_bean' => '生豆',
        'tea_leaf' => '茶葉',
        'powder' => '粉末',
        'teabag' => 'ティーバッグ',
        'stick' => 'スティック',
        'capsule' => 'カプセル',
        'other' => 'その他'
    ];
    
    return $types[$typeKey] ?? 'その他';
}

/**
 * 商品タイプの表示順序を取得
 * 
 * @param string $typeKey タイプキー
 * @return int 表示順序
 */
function getProductTypeOrder(string $typeKey): int
{
    $order = [
        'bean' => 10,
        'ground' => 20,
        'drip' => 30,
        'cold_brew' => 40,
        'instant' => 50,
        'green_bean' => 60,
        'tea_leaf' => 10,
        'powder' => 20,
        'teabag' => 30,
        'stick' => 40,
        'capsule' => 50,
        'other' => 999
    ];
    
    return $order[$typeKey] ?? 999;
}

/**
 * 商品のカテゴリグループを分類
 * 
 * @param string $category カテゴリ
 * @param string $productName 商品名
 * @return string カテゴリグループ
 */
function classifyProductCategoryGroup(string $category, string $productName): string
{
    // 抹茶の3分類
    if ($category === 'matcha') {
        if (preg_match('/ceremonial|儀式|茶道|薄茶|濃茶/iu', $productName)) {
            return 'matcha_ceremonial';
        }
        if (preg_match('/latte|ラテ|milk|ミルク/iu', $productName)) {
            return 'matcha_latte';
        }
        return 'matcha_regular';
    }

    // コーヒー系
    $coffeeCategories = ['coffee', 'espresso', 'latte', 'cappuccino', 'mocha'];
    if (in_array($category, $coffeeCategories, true)) {
        return 'coffee';
    }

    // 通常茶系
    $teaNormal = ['green_tea', 'tea'];
    if (in_array($category, $teaNormal, true)) {
        return 'tea_normal';
    }

    // 特殊茶系
    $teaSpecial = ['black_tea', 'oolong_tea', 'white_tea', 'herbal_tea', 'rooibos'];
    if (in_array($category, $teaSpecial, true)) {
        return 'tea_special';
    }

    return 'other';
}

/**
 * カテゴリグループの表示順序を取得
 * 
 * @param string $groupKey グループキー
 * @return int 表示順序
 */
function getCategoryGroupOrder(string $groupKey): int
{
    $order = [
        'matcha_ceremonial' => 5,
        'matcha_regular' => 10,
        'matcha_latte' => 15,
        'tea_normal' => 20,
        'tea_special' => 30,
        'coffee' => 40,
        'other' => 999
    ];
    
    return $order[$groupKey] ?? 999;
}

/**
 * データベーステーブルに特定の列が存在するかチェック
 * 
 * @param string $tableName テーブル名
 * @param string $columnName 列名
 * @return bool 列が存在する場合true
 */
function databaseTableHasColumn(string $tableName, string $columnName): bool
{
    static $cache = [];
    $cacheKey = "{$tableName}.{$columnName}";
    
    if (isset($cache[$cacheKey])) {
        return $cache[$cacheKey];
    }
    
    try {
        $pdo = getDb();
        $stmt = $pdo->prepare("SHOW COLUMNS FROM `{$tableName}` LIKE ?");
        $stmt->execute([$columnName]);
        $exists = $stmt->rowCount() > 0;
        $cache[$cacheKey] = $exists;
        return $exists;
    } catch (PDOException $e) {
        error_log("Column check failed: {$tableName}.{$columnName} - " . $e->getMessage());
        return false;
    }
}

/**
 * 商品名から基本名を抽出（バリアント部分を除去）
 * 例: "エチオピア イルガチェフェ G1（豆）" → "エチオピア イルガチェフェ G1"
 * 
 * @param string $productName 商品名
 * @return string 基本名
 */
function getProductBaseName(string $productName): string
{
    $originalName = trim($productName);
    $baseName = $productName;

    // 全角スペースを半角へ統一
    $baseName = preg_replace('/　+/', ' ', $baseName);

    // 両タイプの括弧内（派生情報）を除去
    $baseName = preg_replace('/（[^）]*）/u', '', $baseName);
    $baseName = preg_replace('/\([^)]*\)/u', '', $baseName);

    // バリアントを示す語以降を除去
    $suffixPatterns = [
        '/\s*(ドリップバッグ.*)$/u',
        '/\s*(ティーバッグ.*)$/u',
        '/\s*(水出しパック.*)$/u',
        '/\s*(インスタント.*)$/u',
        '/\s*(生豆.*)$/u',
        '/\s*(ペットボトル.*)$/u',
        '/\s*(ボトル.*)$/u',
        '/\s*(スティック.*)$/u',
        '/\s*(ラテ用.*)$/u',
        '/\s*(缶入り.*)$/u',
        '/\s*(茶筒.*)$/u',
        '/\s*(小分け.*)$/u',
        '/\s*(小袋.*)$/u',
        '/\s*(即溶.*)$/u',
    ];

    foreach ($suffixPatterns as $pattern) {
        $baseName = preg_replace($pattern, '', $baseName);
    }

    // 数量表記（100g、12包、1kg など）を末尾から除去
    $baseName = preg_replace('/\s*(\d+(g|kg|包|本|ml|枚))$/iu', '', $baseName);

    // 連続スペースを1つに
    $baseName = preg_replace('/\s+/', ' ', $baseName);

    $baseName = trim($baseName);

    return $baseName !== '' ? $baseName : $originalName;
}

/**
 * 商品名からバリアントタイプ（英語）を抽出
 * 
 * @param string $productName 商品名
 * @return string バリアントタイプ
 */
function extractVariantType(string $productName): string
{
    if (preg_match('/[（(]豆[)）]/', $productName)) return 'Whole Bean';
    if (preg_match('/[（(]粉[)）]/', $productName)) return 'Ground';
    if (preg_match('/ドリップバッグ/', $productName)) return 'Drip Bag';
    if (preg_match('/水出しパック/', $productName) || preg_match('/水出し用/', $productName)) return 'Cold Brew Pack';
    if (preg_match('/インスタント/', $productName)) return 'Instant';
    if (preg_match('/生豆/', $productName)) return 'Green Coffee Bean';
    if (preg_match('/[（(]茶葉[)）]/', $productName)) return 'Loose Leaf';
    if (preg_match('/[（(]粉末[)）]/', $productName)) return 'Powder';
    if (preg_match('/ティーバッグ/', $productName)) return 'Tea Bag';
    if (preg_match('/[（(]ペットボトル[)）]/', $productName) || preg_match('/[（(]ボトル[)）]/', $productName)) return 'Bottle';
    if (preg_match('/[（(]スティック[)）]/', $productName)) return 'Stick';
    if (preg_match('/[（(]ラテ用[)）]/', $productName)) return 'Latte';
    if (preg_match('/[（(]缶入り[)）]/', $productName) || preg_match('/[（(]茶筒/', $productName)) return 'Can';
    if (preg_match('/[（(]小分け[)）]/', $productName) || preg_match('/[（(]小袋/', $productName)) return 'Sachet';
    if (preg_match('/[（(]即溶[)）]/', $productName)) return 'Instant';
    
    return 'Standard';
}

/**
 * 商品名からバリアントタイプ（日本語）を抽出
 * 
 * @param string $productName 商品名
 * @return string バリアントタイプ（日本語）
 */
function extractVariantTypeJa(string $productName): string
{
    if (preg_match('/[（(]豆[)）]/', $productName)) return '豆';
    if (preg_match('/[（(]粉[)）]/', $productName)) return '粉';
    if (preg_match('/ドリップバッグ/', $productName)) return 'ドリップバッグ';
    if (preg_match('/水出しパック/', $productName) || preg_match('/水出し用/', $productName)) return '水出しパック';
    if (preg_match('/インスタント/', $productName)) return 'インスタント';
    if (preg_match('/生豆/', $productName)) return '生豆';
    if (preg_match('/[（(]茶葉[)）]/', $productName)) return '茶葉';
    if (preg_match('/[（(]粉末[)）]/', $productName)) return '粉末';
    if (preg_match('/ティーバッグ/', $productName)) return 'ティーバッグ';
    if (preg_match('/[（(]ペットボトル[)）]/', $productName)) return 'ペットボトル';
    if (preg_match('/[（(]ボトル[)）]/', $productName)) return 'ボトル';
    if (preg_match('/[（(]スティック[)）]/', $productName)) return 'スティック';
    if (preg_match('/[（(]ラテ用[)）]/', $productName)) return 'ラテ用';
    if (preg_match('/[（(]缶入り[)）]/', $productName)) return '缶入り';
    if (preg_match('/[（(]茶筒/', $productName)) return '茶筒';
    if (preg_match('/[（(]小分け[)）]/', $productName)) return '小分け';
    if (preg_match('/[（(]小袋/', $productName)) return '小袋';
    if (preg_match('/[（(]即溶[)）]/', $productName)) return '即溶';
    
    return '標準';
}

/**
 * バリアント表示計画（表示順）を取得
 * 各カテゴリで正確に6タイプを定義
 */
function getVariantDisplayPlan(string $groupKey): array
{
    return match ($groupKey) {
        // コーヒー系: 6タイプ
        'coffee' => ['豆', '粉', 'ドリップバッグ', '水出しパック', 'インスタント', '生豆'],
        // 茶系(通常): 6タイプ (緑茶など)
        'tea_normal' => ['茶葉', '粉末', 'ティーバッグ', '水出しパック', 'インスタント', 'ボトル'],
        // 茶系(特殊): 6タイプ (紅茶、ウーロン茶、ハーブティーなど)
        'tea_special' => ['茶葉', '粉末', '水出しパック', 'スティック', 'インスタント', 'ボトル'],
        // 茶道用抹茶: 6タイプ
        'matcha_ceremonial' => ['粉末', 'インスタント', '即溶', '小分け', 'ボトル', '缶入り'],
        // 抹茶(通常): 6タイプ
        'matcha_regular' => ['粉末', 'インスタント', '即溶', 'ラテ用', 'ボトル', '缶入り'],
        // 抹茶ラテ用: 6タイプ (茶葉、粉末、ティーバッグ、水出しパック、即溶、ペットボトル)
        'matcha_latte' => ['茶葉', '粉末', 'ティーバッグ', '水出しパック', '即溶', 'ペットボトル'],
        'other' => ['標準'],
        default => ['標準'],
    };
}

function normalizeVariantDisplayForPlan(string $displayName, array $displayPlan): ?string
{
    // 完全一致があればそれを使用
    if (in_array($displayName, $displayPlan, true)) {
        return $displayName;
    }

    // 代替パターンのマッピング
    $alternatives = [
        '粉末' => ['粉', '粉末', 'パウダー'],
        '粉' => ['粉末', '粉', 'パウダー'],
        'ティーバッグ' => ['ティーバッグ', 'ティバッグ'],
        'ティバッグ' => ['ティーバッグ', 'ティバッグ'],
        '茶葉' => ['茶葉', 'リーフ'],
        'リーフ' => ['茶葉', 'リーフ'],
        '即溶' => ['即溶', 'インスタント'],
        'インスタント' => ['インスタント', '即溶'],
        'スティック' => ['スティック', 'インスタント'],
        'ペットボトル' => ['ペットボトル', 'ボトル'],
        'ボトル' => ['ボトル', 'ペットボトル'],
        '標準' => ['標準'],
        'その他' => ['標準', 'その他'],
    ];

    // 代替候補を確認
    if (isset($alternatives[$displayName])) {
        foreach ($alternatives[$displayName] as $candidate) {
            if (in_array($candidate, $displayPlan, true)) {
                return $candidate;
            }
        }
    }

    // 表示プランに「標準」が含まれる場合はそれを返す
    if (in_array('標準', $displayPlan, true)) {
        return '標準';
    }

    // マッチしなかった場合はnull
    return null;
}

function deduplicateVariantsByDisplayLabel(array $variants): array
{
    if (empty($variants)) {
        return [];
    }

    $normalized = [];

    foreach ($variants as $index => $variant) {
        $label = trim((string) ($variant['type_display'] ?? $variant['type_name_ja'] ?? $variant['type_name'] ?? $variant['variant_name'] ?? $variant['name'] ?? ''));
        if ($label === '') {
            $label = 'バリアント';
        }

        $key = function_exists('mb_strtolower')
            ? mb_strtolower($label, 'UTF-8')
            : strtolower($label);

        $score = 0;
        if (($variant['stock'] ?? 0) > 0) {
            $score += 100;
        }
        if (!empty($variant['is_default'])) {
            $score += 25;
        }
        if (($variant['source'] ?? '') === 'database') {
            $score += 5;
        }
        if (!empty($variant['image_url'])) {
            $score += 1;
        }

        if (!isset($normalized[$key])) {
            $variant['type_display'] = $label;
            $normalized[$key] = [
                'variant' => $variant,
                'score' => $score,
                'order' => $index,
            ];
            continue;
        }

        if ($score > $normalized[$key]['score']) {
            $variant['type_display'] = $label;
            $normalized[$key]['variant'] = $variant;
            $normalized[$key]['score'] = $score;
        }
    }

    uasort($normalized, static function (array $a, array $b): int {
        return $a['order'] <=> $b['order'];
    });

    return array_values(array_map(static function (array $entry): array {
        return $entry['variant'];
    }, $normalized));
}

/**
 * 商品名からプルダウン表示用のタイプ名を判定
 * 優先順位を考慮して最も適切なタイプを返す
 */
function detectVariantDisplayName(string $productName): string
{
    $name = $productName;

    // 優先順位の高いパターンから順に検査
    $patterns = [
        // コーヒー系タイプ
        '生豆' => '/[（(]?生豆[)）]?|Green.?Coffee.?Bean|グリーン.?コーヒー.?ビー?ン/iu',
        'ドリップバッグ' => '/[（(]?ドリップ.?バッグ[)）]?|Drip.?Bag/iu',
        '水出しパック' => '/[（(]?水出し.?パック[)）]?|[（(]?水出し[)）]?|Cold.?Brew.?Pack/iu',
        '即溶' => '/[（(]?即溶[)）]?|即溶性/iu',
        'インスタント' => '/[（(]?インスタント[)）]?|Instant/iu',
        '粉' => '/[（(]粉[)）]/u',
        '豆' => '/[（(]豆[)）]|Whole.?Bean|ホール.?ビー.?ン/iu',
        
        // 茶系タイプ
        'ティーバッグ' => '/[（(]?ティ[ーｨ]?.?バッグ[)）]?|Tea.?Bag/iu',
        '粉末' => '/[（(]?粉末[)）]?|パウダー|Powder/iu',
        '茶葉' => '/[（(]?茶葉[)）]?|Loose.?Leaf|リー?フ/iu',
        'スティック' => '/[（(]?スティック[)）]?|Stick/iu',
        'ラテ用' => '/[（(]?ラテ.?用[)）]?|[（(]?ラテ[)）]?|Latte/iu',
        '缶入り' => '/[（(]?缶入り[)）]?|[（(]?缶[)）]?|茶筒|Can/iu',
        '小分け' => '/[（(]?小分け[)）]?|[（(]?小袋[)）]?|サシェ|Sachet/iu',
        'ペットボトル' => '/[（(]?ペット.?ボトル[)）]?|PET.?ボトル/iu',
        'ボトル' => '/[（(]?ボトル[)）]?|Bottle/iu',
    ];

    foreach ($patterns as $label => $pattern) {
        if (preg_match($pattern, $name)) {
            return $label;
        }
    }

    return 'その他';
}
