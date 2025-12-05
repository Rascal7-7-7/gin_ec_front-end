(function () {
    const config = window.wishlistPageConfig || {};
    const api = config.api || {};
    const productDetailBaseUrl = (config.productDetailBaseUrl || '/products/').replace(/\/*$/, '/') || '/products/';
    const fallbackImageUrl = config.fallbackImageUrl || '';
    const categoryNames = config.categoryNames || {};
    const csrfToken = config.csrfToken || document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

    document.addEventListener('DOMContentLoaded', () => {
        loadWishlist();
    });

    async function loadWishlist() {
        if (!api.list) return;
        try {
            const response = await fetchWithLoading(api.list, {
                method: 'GET',
                headers: { 'Content-Type': 'application/json' }
            });

            const result = await response.json();
            if (!result.success) {
                throw new Error(result.error || 'ウィッシュリストの読み込みに失敗しました');
            }

            document.getElementById('loading').classList.add('hidden');

            const responseData = result.data || {};
            const items = Array.isArray(responseData.items) ? responseData.items : (Array.isArray(responseData) ? responseData : []);
            const totalCount = responseData.pagination && typeof responseData.pagination.total_items !== 'undefined'
                ? responseData.pagination.total_items
                : items.length;
            document.getElementById('wishlist-count').textContent = totalCount;

            if (totalCount === 0) {
                document.getElementById('empty-state').classList.remove('hidden');
            } else {
                renderWishlist(items);
                document.getElementById('wishlist-grid').classList.remove('hidden');
            }
        } catch (error) {
            console.error('Wishlist load error:', error);
            document.getElementById('loading').innerHTML = `
                <div class="text-center text-red-600">
                    <p class="mb-2">エラーが発生しました</p>
                    <p class="text-sm">${error.message}</p>
                </div>
            `;
        }
    }

    function renderWishlist(items) {
        const grid = document.getElementById('wishlist-grid');
        grid.innerHTML = '';
        items.forEach(item => {
            const card = createProductCard(item);
            grid.appendChild(card);
        });
    }

    function createProductCard(item) {
        const productId = item.product_id ?? item.id;
        const detailUrl = `${productDetailBaseUrl}${productId}`;
        const imageUrl = item.image_url || fallbackImageUrl;
        const addedAt = item.added_at ?? item.created_at ?? null;
        const addedLabel = addedAt ? new Date(addedAt).toLocaleDateString('ja-JP') : '---';
        const rawDisplayPrice = Number(item.display_price ?? item.price ?? 0);
        const displayPrice = Number.isFinite(rawDisplayPrice) ? rawDisplayPrice : 0;
        const rawStock = Number(item.stock_quantity);
        const fallbackStock = Number(item.stock);
        const stockQuantity = Number.isFinite(rawStock) ? rawStock : (Number.isFinite(fallbackStock) ? fallbackStock : 0);
        const card = document.createElement('div');
        card.className = 'bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition-shadow relative group';
        card.dataset.productId = productId;

        const stockStatus = stockQuantity > 0
            ? '<span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded">在庫あり</span>'
            : '<span class="px-2 py-1 bg-red-100 text-red-800 text-xs rounded">在庫なし</span>';

        const categoryName = categoryNames[item.category] || item.category || '';

        card.innerHTML = `
            <div class="relative">
                <img src="${imageUrl}"
                     alt="${escapeHtml(item.name)}"
                     class="w-full h-48 object-cover"
                     onerror="this.src='${fallbackImageUrl}'">

                <button onclick="removeFromWishlist(${productId})"
                    class="absolute top-2 right-2 p-2 bg-white rounded-full shadow-lg hover:bg-red-50 transition-colors opacity-0 group-hover:opacity-100">
                    <svg class="w-5 h-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                    </svg>
                </button>

                <div class="absolute top-2 left-2">
                    <span class="px-2 py-1 bg-black bg-opacity-70 text-white text-xs rounded">
                        ${categoryName}
                    </span>
                </div>
            </div>

            <div class="p-4">
                <h3 class="font-semibold text-gray-900 mb-2 line-clamp-2">
                    <a href="${detailUrl}" class="hover:text-amber-600 transition" title="${escapeHtml(item.name)}">
                        ${escapeHtml(item.name)}
                    </a>
                </h3>

                <div class="flex items-center justify-between mb-3">
                    <span class="text-2xl font-bold text-pink-600">¥${displayPrice.toLocaleString()}</span>
                    ${stockStatus}
                </div>

                <div class="text-xs text-gray-500 mb-3">
                    登録日: ${addedLabel}
                </div>

                <div class="flex gap-2">
                    <a href="${detailUrl}"
                       class="flex-1 text-center px-4 py-2 bg-gray-100 text-gray-800 rounded hover:bg-gray-200 transition-colors text-sm font-medium">
                        詳細を見る
                    </a>
                    ${stockQuantity > 0 ? `
                    <button onclick="addToCart(${productId})"
                            class="flex-1 px-4 py-2 bg-pink-500 text-white rounded hover:bg-pink-600 transition-colors text-sm font-medium">
                        カートへ
                    </button>
                    ` : ''}
                </div>
            </div>
        `;

        return card;
    }

    async function removeFromWishlist(productId) {
        if (!api.remove) return;
        if (!confirm('この商品をほしいものリストから削除しますか？')) return;

        try {
            const response = await fetchWithLoading(api.remove, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({ product_id: productId })
            });

            const result = await response.json();
            if (result.success) {
                const card = document.querySelector(`[data-product-id="${productId}"]`);
                if (card) card.remove();

                const countElement = document.getElementById('wishlist-count');
                const currentCount = parseInt(countElement.textContent, 10) || 0;
                const serverCount = result.data?.total_count;
                const nextCount = Number.isFinite(serverCount) ? serverCount : Math.max(0, currentCount - 1);
                countElement.textContent = nextCount;

                if (nextCount === 0) {
                    document.getElementById('wishlist-grid').classList.add('hidden');
                    document.getElementById('empty-state').classList.remove('hidden');
                }

                showToast('ほしいものリストから削除しました', 'success');
                if (window.updateWishlistCount) {
                    window.updateWishlistCount();
                }
            } else {
                throw new Error(result.error || '削除に失敗しました');
            }
        } catch (error) {
            console.error('Remove error:', error);
            showToast(error.message, 'error');
        }
    }

    async function addToCart(productId) {
        if (!api.cartAdd) return;
        try {
            const response = await fetchWithLoading(api.cartAdd, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({ product_id: productId, quantity: 1 })
            });

            const result = await response.json();
            if (result.success) {
                showToast('カートに追加しました', 'success');
                if (window.Alpine && typeof Alpine.store === 'function' && Alpine.store('cart')) {
                    Alpine.store('cart').fetchCount();
                } else if (typeof window.updateCartCount === 'function') {
                    window.updateCartCount();
                }
            } else {
                throw new Error(result.error || 'カートへの追加に失敗しました');
            }
        } catch (error) {
            console.error('Add to cart error:', error);
            showToast(error.message, 'error');
        }
    }

    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text ?? '';
        return div.innerHTML;
    }

    function showToast(message, type = 'info') {
        const toast = document.createElement('div');
        toast.className = `fixed bottom-4 right-4 px-6 py-3 rounded-lg shadow-lg text-white z-50 animate-slide-up ${
            type === 'success' ? 'bg-green-500' :
            type === 'error' ? 'bg-red-500' :
            'bg-blue-500'
        }`;
        toast.textContent = message;
        document.body.appendChild(toast);
        setTimeout(() => toast.remove(), 3000);
    }

    window.removeFromWishlist = removeFromWishlist;
    window.addToCart = addToCart;
})();
