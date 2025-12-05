(function () {
    const config = window.productsPageConfig || {};
    const wishlistApi = config.wishlistApi || {};
    const compareAddUrl = config.compareAddUrl || '/compare/add';
    const comparePageUrl = config.comparePageUrl || '/compare';
    const loginUrl = config.loginUrl || '/login';
    const csrfToken = config.csrfToken || document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

    const fetchWithLoader = (typeof fetchWithLoading === 'function') ? fetchWithLoading : fetch;
    const swiperInstances = new Map();
    let wishlistProductIds = new Set();

    document.addEventListener('DOMContentLoaded', () => {
        initSwipers();
        attachSwiperListeners();
        initVariantSelectors();
        initWishlistState();
        bindWishlistButtons();
        bindCompareButtons();
        bindAutoSubmitSelects();
    });

    function bindAutoSubmitSelects() {
        document.querySelectorAll('select[data-auto-submit="true"]').forEach((selectEl) => {
            const form = selectEl.closest('form');
            if (!form) return;
            selectEl.addEventListener('change', () => form.submit());
        });
    }

    function initSwipers() {
        if (typeof Swiper === 'undefined') return;
        document.querySelectorAll('.product-card-swiper').forEach((swiperEl) => {
            const options = {
                loop: swiperEl.dataset.loop === 'true',
                slidesPerView: 1,
                speed: 600,
            };

            const paginationEl = swiperEl.querySelector('.swiper-pagination');
            if (paginationEl) {
                options.pagination = { el: paginationEl, clickable: true };
            }

            const nextEl = swiperEl.querySelector('.swiper-button-next');
            const prevEl = swiperEl.querySelector('.swiper-button-prev');
            if (nextEl && prevEl) {
                options.navigation = { nextEl, prevEl };
            }

            const instance = new Swiper(swiperEl, options);
            swiperInstances.set(swiperEl.id, instance);
        });
    }

    function initVariantSelectors() {
        document.querySelectorAll('.variant-selector').forEach((selectEl) => {
            selectEl.addEventListener('change', handleVariantChange);
            const cardEl = selectEl.closest('[data-product-card]');
            const initialVariantId = selectEl.value || cardEl?.dataset.defaultVariantId;
            if (cardEl && initialVariantId) {
                applyVariantState(cardEl, initialVariantId, { syncSelect: false, syncSwiper: true });
            }
        });
    }

    function handleVariantChange(event) {
        const selectEl = event.target;
        if (!selectEl) return;

        const cardEl = selectEl.closest('[data-product-card]');
        if (!cardEl) return;

        applyVariantState(cardEl, selectEl.value, { syncSelect: false, syncSwiper: true });
    }

    function attachSwiperListeners() {
        document.querySelectorAll('.product-card-swiper').forEach((swiperEl) => {
            if (swiperEl.dataset.variantListenerBound === 'true') return;

            const swiperInstance = swiperInstances.get(swiperEl.id);
            if (!swiperInstance) return;

            const cardEl = swiperEl.closest('[data-product-card]');
            if (!cardEl) return;

            const handleSync = () => {
                const variantId = resolveVariantIdFromSwiper(swiperInstance, swiperEl);
                if (!variantId) return;
                if (cardEl.dataset.activeVariantId === String(variantId)) return;
                applyVariantState(cardEl, variantId, { syncSelect: true, syncSwiper: false });
            };

            swiperInstance.on('slideChangeTransitionEnd', handleSync);
            swiperEl.dataset.variantListenerBound = 'true';
        });
    }

    function applyVariantState(cardEl, variantId, options = {}) {
        if (!cardEl || !variantId) return;
        const variantData = findVariantData(cardEl, variantId);
        if (!variantData) return;

        const normalizedId = String(variantId);
        if (cardEl.dataset.activeVariantId === normalizedId && options.skipIfSame !== false) {
            return;
        }

        if (options.syncSelect) {
            const selectEl = cardEl.querySelector('.variant-selector');
            if (selectEl && selectEl.value !== normalizedId) {
                selectEl.value = normalizedId;
            }
        }

        updateCardPrice(cardEl, variantData);
        updateCardImage(cardEl, variantId, variantData, { syncSwiper: options.syncSwiper !== false });
        cardEl.dataset.activeVariantId = normalizedId;
    }

    function findVariantData(cardEl, variantId) {
        let variants = [];
        try {
            variants = JSON.parse(cardEl.dataset.variants || '[]');
        } catch (error) {
            console.error('Variant JSON parse error', error);
            return null;
        }
        return variants.find(v => String(v.id) === String(variantId)) || null;
    }

    function updateCardPrice(cardEl, variant) {
        const priceEl = cardEl.querySelector('[data-price-text]');
        const compareEl = cardEl.querySelector('[data-compare-text]');

        if (priceEl) {
            priceEl.textContent = formatCurrency(variant.price);
        }

        if (!compareEl) return;

        if (variant.compare_price) {
            compareEl.textContent = formatCurrency(variant.compare_price);
            compareEl.style.display = '';
        } else {
            compareEl.textContent = '';
            compareEl.style.display = 'none';
        }
    }

    function updateCardImage(cardEl, variantId, variant, options = {}) {
        const shouldSyncSwiper = options.syncSwiper !== false;
        const swiperEl = cardEl.querySelector('.product-card-swiper');
        let didSyncSwiper = false;

        if (shouldSyncSwiper && swiperEl) {
            const swiperInstance = swiperInstances.get(swiperEl.id);
            if (swiperInstance) {
                const slides = Array.from(swiperEl.querySelectorAll('.swiper-slide'));
                const isLoopEnabled = !!swiperInstance.params?.loop;
                const slideSource = isLoopEnabled
                    ? slides.filter(slide => !slide.classList.contains('swiper-slide-duplicate'))
                    : slides;

                const targetIndex = slideSource.findIndex(slide => slide.dataset.variantId === String(variantId));
                if (targetIndex >= 0) {
                    if (isLoopEnabled && typeof swiperInstance.slideToLoop === 'function') {
                        swiperInstance.slideToLoop(targetIndex);
                    } else {
                        swiperInstance.slideTo(targetIndex);
                    }
                    didSyncSwiper = true;
                }
            }
        }

        const needsFallback = !swiperEl || (shouldSyncSwiper && !didSyncSwiper);
        if (needsFallback && variant.image_url) {
            const fallbackImg = cardEl.querySelector('.product-card-swiper img');
            if (fallbackImg) {
                fallbackImg.src = variant.image_url;
                fallbackImg.alt = variant.name || fallbackImg.alt;
            }
        }
    }

    function formatCurrency(value) {
        const numberValue = Number(value || 0);
        return '¥' + numberValue.toLocaleString('ja-JP');
    }

    function resolveVariantIdFromSwiper(swiperInstance, swiperEl) {
        if (!swiperInstance || !swiperEl) return null;

        const activeSlide = swiperEl.querySelector('.swiper-slide.swiper-slide-active');
        if (activeSlide?.dataset?.variantId) {
            return activeSlide.dataset.variantId;
        }

        const isLoopEnabled = !!swiperInstance.params?.loop;
        if (isLoopEnabled && typeof swiperInstance.realIndex === 'number') {
            const nonDuplicateSlides = Array.from(swiperEl.querySelectorAll('.swiper-slide'))
                .filter(slide => !slide.classList.contains('swiper-slide-duplicate'));
            return nonDuplicateSlides[swiperInstance.realIndex]?.dataset.variantId || null;
        }

        const fallbackSlide = swiperInstance.slides?.[swiperInstance.activeIndex];
        return fallbackSlide?.dataset?.variantId || null;
    }

    async function initWishlistState() {
        const isLoggedIn = document.querySelector('meta[name="user-id"]')?.getAttribute('content');
        if (!isLoggedIn || !wishlistApi.check) return;

        const productIds = Array.from(document.querySelectorAll('[data-product-id]'))
            .map(btn => parseInt(btn.dataset.productId, 10))
            .filter(id => Number.isFinite(id));

        if (productIds.length === 0) return;

        try {
            const response = await fetch(wishlistApi.check, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-Token': csrfToken
                },
                body: JSON.stringify({ product_ids: productIds })
            });

            const result = await response.json();
            if (result.success) {
                Object.entries(result.data || {}).forEach(([productId, inWishlist]) => {
                    const numericId = parseInt(productId, 10);
                    if (inWishlist && Number.isFinite(numericId)) {
                        wishlistProductIds.add(numericId);
                        updateWishlistButton(numericId, true);
                    }
                });
            }
        } catch (error) {
            console.error('ウィッシュリスト状態取得エラー:', error);
        }
    }

    async function toggleWishlistProduct(productId) {
        const isLoggedIn = document.querySelector('meta[name="user-id"]')?.getAttribute('content');
        if (!isLoggedIn) {
            alert('お気に入りに追加するにはログインが必要です');
            window.location.href = loginUrl;
            return;
        }

        const isInWishlist = wishlistProductIds.has(productId);
        const endpoint = isInWishlist ? wishlistApi.remove : wishlistApi.add;
        if (!endpoint) return;

        try {
            const response = await fetchWithLoader(endpoint, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-Token': csrfToken
                },
                body: JSON.stringify({ product_id: productId })
            });

            const result = await response.json();
            if (result.success) {
                if (isInWishlist) {
                    wishlistProductIds.delete(productId);
                    updateWishlistButton(productId, false);
                } else {
                    wishlistProductIds.add(productId);
                    updateWishlistButton(productId, true);
                }

                if (window.updateWishlistCount) {
                    window.updateWishlistCount();
                }

                const message = isInWishlist ? 'お気に入りから削除しました' : 'お気に入りに追加しました';
                if (window.showToast) {
                    window.showToast(message, 'success');
                } else {
                    alert(message);
                }
            } else {
                const msg = result.error || '操作に失敗しました';
                if (window.showToast) {
                    window.showToast(msg, 'error');
                } else {
                    alert(msg);
                }
            }
        } catch (error) {
            console.error('ウィッシュリスト操作エラー:', error);
            alert('エラーが発生しました');
        }
    }

    function updateWishlistButton(productId, inWishlist) {
        const button = document.querySelector(`[data-product-id="${productId}"]`);
        if (!button) return;
        const icon = button.querySelector('.wishlist-icon');
        button.dataset.wishlist = inWishlist.toString();
        if (icon) {
            icon.setAttribute('fill', inWishlist ? 'currentColor' : 'none');
        }
    }

    function addToCompare(productId) {
        const url = `${compareAddUrl}/${productId}`.replace(/\/+$/, '').replace(/([^:])\/\//g, '$1/');
        fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-Token': csrfToken
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                if (window.showToast) {
                    window.showToast(`${data.message} (${data.count}/3商品)`, 'success');
                } else {
                    alert(data.message);
                }
                if (data.count >= 2 && comparePageUrl) {
                    if (confirm(`比較リストに ${data.count} 件の商品があります。\n比較ページを開きますか？`)) {
                        window.location.href = comparePageUrl;
                    }
                }
            } else {
                const msg = data.message || data.error || '比較リストへの追加に失敗しました';
                if (window.showToast) {
                    window.showToast(msg, 'error');
                } else {
                    alert(msg);
                }
            }
        })
        .catch(error => {
            console.error('比較リスト追加エラー:', error);
            alert('エラーが発生しました');
        });
    }

    function bindWishlistButtons() {
        document.querySelectorAll('[data-wishlist-toggle]').forEach((button) => {
            if (button.dataset.wishlistListenerBound === 'true') return;
            const productId = parseInt(button.dataset.productId, 10);
            if (!Number.isFinite(productId)) return;
            button.addEventListener('click', (event) => {
                event.preventDefault();
                toggleWishlistProduct(productId);
            });
            button.dataset.wishlistListenerBound = 'true';
        });
    }

    function bindCompareButtons() {
        document.querySelectorAll('[data-compare-button]').forEach((button) => {
            if (button.dataset.compareListenerBound === 'true') return;
            const productId = parseInt(button.dataset.productId, 10);
            if (!Number.isFinite(productId)) return;
            button.addEventListener('click', (event) => {
                event.preventDefault();
                addToCompare(productId);
            });
            button.dataset.compareListenerBound = 'true';
        });
    }

    window.toggleWishlistProduct = toggleWishlistProduct;
    window.addToCompare = addToCompare;
})();
