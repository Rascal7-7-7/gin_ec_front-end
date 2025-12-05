const CART_BASE_URL = (document.querySelector('meta[name="base-url"]')?.content || '').replace(/\/$/, '');

function cartNormalizeUrl(path) {
    if (!path) return CART_BASE_URL || '';
    if (/^https?:\/\//i.test(path)) return path;
    if (path.startsWith('/')) return `${CART_BASE_URL}${path}`;
    return `${CART_BASE_URL}/${path}`;
}

function getCartCsrf() {
    return document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
}

function updateCartCount(count) {
    if (typeof count !== 'number') return;
    if (window.Alpine && typeof Alpine.store === 'function') {
        try {
            const cartStore = Alpine.store('cart');
            if (cartStore?.fetchCount) {
                cartStore.fetchCount();
                return;
            }
            if (typeof cartStore?.setCount === 'function') {
                cartStore.setCount(count);
                return;
            }
        } catch (storeError) {
            console.warn('カート数更新に失敗しました', storeError);
        }
    }
    const badge = document.querySelector('[data-cart-count]') || document.querySelector('[x-text="$store.cart.count"]');
    if (badge) {
        badge.textContent = count;
        badge.classList.toggle('hidden', count <= 0);
    }
}

function showCartAddedModal(productName = '') {
    const modal = document.getElementById('cart-added-modal');
    if (!modal) return;
    const message = modal.querySelector('#cart-added-message');
    if (message) {
        message.textContent = productName
            ? `${productName} をカートに追加しました。`
            : '商品をカートに追加しました。';
    }
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    document.body.classList.add('overflow-hidden');
}

function hideCartAddedModal() {
    const modal = document.getElementById('cart-added-modal');
    if (!modal) return;
    modal.classList.add('hidden');
    modal.classList.remove('flex');
    document.body.classList.remove('overflow-hidden');
}

window.showCartAddedModal = showCartAddedModal;
window.updateCartCount = updateCartCount;

(function attachCartHandlers() {
    const CART_FORM_SELECTOR = '.cart-add-form';

    function bindForms() {
        document.querySelectorAll(CART_FORM_SELECTOR).forEach(form => {
            if (form.dataset.cartHandlerBound === 'true') return;
            form.dataset.cartHandlerBound = 'true';
            form.classList.add('no-loading');

            form.addEventListener('submit', async function(event) {
                event.preventDefault();

                const button = this.querySelector('button[type="submit"]');
                if (!button) return;

                const endpoint = this.dataset.apiEndpoint || this.getAttribute('action') || cartNormalizeUrl('/api/cart/add');
                const productName = this.dataset.productName || '';
                const formData = new FormData(this);
                const csrfToken = getCartCsrf();

                const originalContent = button.dataset.originalContent || button.innerHTML;
                button.dataset.originalContent = originalContent;
                button.disabled = true;
                button.classList.remove('bg-green-600', 'bg-red-600');
                button.innerText = '追加中...';

                const resetButton = (delay = 1500) => {
                    setTimeout(() => {
                        button.innerHTML = button.dataset.originalContent;
                        button.classList.remove('bg-green-600', 'bg-red-600');
                        button.disabled = false;
                    }, delay);
                };

                try {
                    const response = await fetch(endpoint, {
                        method: 'POST',
                        credentials: 'same-origin',
                        headers: csrfToken ? { 'X-CSRF-TOKEN': csrfToken } : {},
                        body: formData,
                    });

                    const data = await response.json().catch(() => ({}));
                    if (!response.ok || data.success === false) {
                        throw new Error(data.message || 'カートに追加できませんでした');
                    }

                    button.innerText = '✓ 追加しました';
                    button.classList.add('bg-green-600');

                    updateCartCount(data.count ?? 0);
                    resetButton();
                    showCartAddedModal(productName);
                } catch (error) {
                    console.error('カート追加エラー', error);
                    button.innerText = error.message || '✗ エラー';
                    button.classList.add('bg-red-600');
                    resetButton();
                }
            });
        });
    }

    document.addEventListener('DOMContentLoaded', () => {
        bindForms();
        const modal = document.getElementById('cart-added-modal');
        if (modal) {
            modal.addEventListener('click', (event) => {
                if (event.target === modal || event.target.closest('[data-cart-modal-close]')) {
                    hideCartAddedModal();
                }
            });
            modal.querySelectorAll('[data-continue-shopping]').forEach(button => {
                button.addEventListener('click', hideCartAddedModal);
            });
        }
    });

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') {
            hideCartAddedModal();
        }
    });

    if (window.MutationObserver) {
        const observer = new MutationObserver(() => bindForms());
        observer.observe(document.body, { childList: true, subtree: true });
    }
})();
