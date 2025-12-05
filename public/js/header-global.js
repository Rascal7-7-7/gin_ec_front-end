const HEADER_BASE_URL = (document.querySelector('meta[name="base-url"]')?.content || '').replace(/\/$/, '');

function getCsrfToken() {
    return document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
}

function normalizeUrl(path) {
    if (!path) return HEADER_BASE_URL || '';
    if (/^https?:\/\//i.test(path)) return path;
    if (path.startsWith('/')) return `${HEADER_BASE_URL}${path}`;
    return `${HEADER_BASE_URL}/${path}`;
}

// Alpine cart store
if (typeof document !== 'undefined') {
    document.addEventListener('alpine:init', () => {
        Alpine.store('cart', {
            count: 0,
            init() {
                this.fetchCount();
            },
            async fetchCount() {
                try {
                    const response = await fetch(normalizeUrl('/api/cart/count'));
                    const data = await response.json();
                    this.count = data.count || 0;
                } catch (error) {
                    console.error('カート数取得エラー:', error);
                }
            },
            async add(productId, quantity = 1) {
                try {
                    const response = await fetch(normalizeUrl('/api/cart/add'), {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': getCsrfToken()
                        },
                        body: JSON.stringify({ product_id: productId, quantity })
                    });
                    const data = await response.json();
                    if (data.success) {
                        this.fetchCount();
                        return true;
                    }
                    return false;
                } catch (error) {
                    console.error('カート追加エラー:', error);
                    return false;
                }
            }
        });
    });
}

// Wishlist badge updater
async function updateWishlistCount() {
    try {
        const response = await fetch(normalizeUrl('/api/wishlist'));
        const result = await response.json();
        const badge = document.getElementById('wishlist-count-badge');
        if (!badge) return;
        if (!result.success || !result.data) {
            badge.classList.add('hidden');
            return;
        }
        const pagination = result.data.pagination || {};
        const items = Array.isArray(result.data.items) ? result.data.items : [];
        const count = typeof pagination.total_items !== 'undefined'
            ? parseInt(pagination.total_items, 10)
            : items.length;
        if (count > 0) {
            badge.textContent = count;
            badge.classList.remove('hidden');
        } else {
            badge.classList.add('hidden');
        }
    } catch (error) {
        console.error('Wishlist count error:', error);
    }
}

// View history badge
function updateViewHistoryBadge() {
    if (typeof ViewHistory?.updateHeaderCount === 'function') {
        ViewHistory.updateHeaderCount();
    }
}

window.updateWishlistCount = updateWishlistCount;
window.updateViewHistoryBadge = updateViewHistoryBadge;

if (typeof document !== 'undefined') {
    document.addEventListener('DOMContentLoaded', () => {
        updateWishlistCount();
        updateViewHistoryBadge();
    });
}
