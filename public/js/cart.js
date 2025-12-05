(function () {
    const config = window.cartPageConfig || {};
    const applyUrl = config.applyUrl || '/coupon/apply';
    const cartTotal = Number(config.cartTotal || 0);

    function getCsrfToken() {
        return document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
    }

    // クーポン適用ハンドラ
    (function attachCouponHandlers() {
        const input = document.getElementById(config.inputId || 'cart-coupon-code');
        const messageEl = document.getElementById(config.messageId || 'cart-coupon-message');
        const applyBtn = document.querySelector('[data-cart-action="apply-coupon"]');

        if (!input || !messageEl || !applyBtn) {
            return;
        }

        function showMessage(text, type) {
            const colorClass = type === 'success' ? 'text-green-600' : 'text-red-600';
            messageEl.className = `text-sm ${colorClass}`;
            messageEl.textContent = text;
            messageEl.classList.remove('hidden');
        }

        async function applyCoupon() {
            const code = input.value.trim();
            if (!code) {
                showMessage('クーポンコードを入力してください', 'error');
                return;
            }

            try {
                const csrfToken = getCsrfToken();
                const response = await fetch(applyUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: new URLSearchParams({
                        csrf_token: csrfToken,
                        code,
                        cart_total: cartTotal
                    })
                });

                const data = await response.json();
                if (data.success) {
                    showMessage(data.message || 'クーポンを適用しました', 'success');
                    setTimeout(() => window.location.reload(), 1000);
                } else {
                    showMessage(data.message || 'クーポンの適用に失敗しました', 'error');
                }
            } catch (error) {
                console.error('Coupon error:', error);
                showMessage('エラーが発生しました', 'error');
            }
        }

        applyBtn.addEventListener('click', applyCoupon);
        input.addEventListener('keypress', (event) => {
            if (event.key === 'Enter') {
                event.preventDefault();
                applyCoupon();
            }
        });
    })();

    // カート削除確認ハンドラ
    (function attachRemoveHandlers() {
        const removeButtons = document.querySelectorAll('[data-cart-remove]');
        if (!removeButtons.length) return;

        removeButtons.forEach((button) => {
            button.addEventListener('click', (event) => {
                const message = button.dataset.confirmMessage || '削除してもよろしいですか？';
                if (!window.confirm(message)) {
                    event.preventDefault();
                }
            });
        });
    })();

    // フォーム送信前の確認ハンドラ（例: カート全削除）
    (function attachFormConfirmHandlers() {
        document.querySelectorAll('form[data-confirm-submit]').forEach((form) => {
            form.addEventListener('submit', (event) => {
                const message = form.dataset.confirmSubmit || '実行してもよろしいですか？';
                if (!window.confirm(message)) {
                    event.preventDefault();
                }
            });
        });
    })();
})();
