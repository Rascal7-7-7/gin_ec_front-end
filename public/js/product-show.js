(function () {
    const pageConfig = window.productPageConfig || {};
    const variantDefaults = pageConfig.variantSelector || {};
    const reviewConfig = pageConfig.review || {};
    const wishlistConfig = pageConfig.wishlist || {};
    const viewHistoryConfig = pageConfig.viewHistory || {};
    const lightboxImages = Array.isArray(pageConfig.lightboxImages) ? pageConfig.lightboxImages : [];

    const lightboxState = {
        images: lightboxImages,
        currentIndex: 0
    };

    function getCsrfToken() {
        return document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || wishlistConfig.csrfToken || '';
    }

    function reviewsApp(productId) {
        const apiBase = reviewConfig.apiBase || '';
        return {
            productId,
            reviews: [],
            stats: null,
            pagination: {
                page: 1,
                totalPages: 1,
                perPage: 10,
                total: 0
            },
            isLoading: false,
            showReviewForm: false,
            isSubmitting: false,
            errorMessage: '',
            formData: {
                rating: 5,
                body: '',
                taste_tags: []
            },

            init() {
                this.loadReviews(1);
            },

            async loadReviews(page = 1) {
                this.isLoading = true;
                try {
                    const response = await fetch(`${apiBase}${this.productId}?page=${page}`);
                    const contentType = response.headers.get('content-type');
                    if (!contentType || !contentType.includes('application/json')) {
                        console.warn('レビューAPI: JSON以外のレスポンス');
                        return;
                    }
                    const data = await response.json();
                    if (data.success) {
                        this.reviews = data.reviews || [];
                        this.stats = data.stats || null;
                        this.pagination = data.pagination || this.pagination;
                    } else {
                        console.warn('レビュー読み込み失敗:', data.error);
                    }
                } catch (error) {
                    console.error('レビュー読み込みエラー:', error);
                } finally {
                    this.isLoading = false;
                }
            },

            async submitReview() {
                this.isSubmitting = true;
                this.errorMessage = '';
                try {
                    const formData = new FormData();
                    formData.append('rating', this.formData.rating);
                    formData.append('body', this.formData.body);
                    const csrfInput = document.querySelector('input[name="csrf_token"]')?.value || reviewConfig.csrfToken || '';
                    if (csrfInput) {
                        formData.append('csrf_token', csrfInput);
                    }

                    const response = await fetch(`${apiBase}${this.productId}/store`, {
                        method: 'POST',
                        body: formData
                    });

                    const data = await response.json();

                    if (data.success) {
                        this.formData = { rating: 5, body: '', taste_tags: [] };
                        this.showReviewForm = false;
                        this.loadReviews(1);
                        if (window.showToast) {
                            window.showToast(data.message, 'success');
                        } else {
                            alert(data.message);
                        }
                    } else {
                        this.errorMessage = data.error || 'エラーが発生しました';
                    }
                } catch (error) {
                    console.error('レビュー投稿エラー:', error);
                    this.errorMessage = 'レビューの投稿に失敗しました';
                } finally {
                    this.isSubmitting = false;
                }
            }
        };
    }

    function wishlistButtonApp(productId) {
        const checkUrl = wishlistConfig.checkUrl || '';
        const addUrl = wishlistConfig.addUrl || '';
        const removeUrl = wishlistConfig.removeUrl || '';
        const loginUrl = wishlistConfig.loginUrl || '/login';
        return {
            productId,
            isInWishlist: false,
            isLoading: false,

            async init() {
                await this.checkWishlistStatus();
            },

            async checkWishlistStatus() {
                const isLoggedIn = document.querySelector('meta[name="user-id"]')?.getAttribute('content');
                if (!isLoggedIn || !checkUrl) {
                    return;
                }

                try {
                    const response = await fetch(checkUrl, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-Token': getCsrfToken()
                        },
                        body: JSON.stringify({ product_ids: [this.productId] })
                    });

                    const contentType = response.headers.get('content-type');
                    if (!contentType || !contentType.includes('application/json')) {
                        console.warn('ウィッシュリストAPI: JSON以外のレスポンス');
                        return;
                    }

                    const result = await response.json();
                    if (result.success && result.data) {
                        this.isInWishlist = result.data[this.productId] || false;
                    }
                } catch (error) {
                    console.error('ウィッシュリスト状態確認エラー:', error);
                }
            },

            async toggleWishlist() {
                const isLoggedIn = document.querySelector('meta[name="user-id"]')?.getAttribute('content');
                if (!isLoggedIn) {
                    alert('お気に入りに追加するにはログインが必要です');
                    window.location.href = loginUrl;
                    return;
                }

                if (this.isLoading) return;
                this.isLoading = true;

                try {
                    const endpoint = this.isInWishlist ? removeUrl : addUrl;
                    const response = await fetch(endpoint, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-Token': getCsrfToken()
                        },
                        body: JSON.stringify({ product_id: this.productId })
                    });

                    const result = await response.json();

                    if (result.success) {
                        this.isInWishlist = !this.isInWishlist;
                        const message = this.isInWishlist ? 'お気に入りに追加しました' : 'お気に入りから削除しました';
                        if (window.showToast) {
                            window.showToast(message, 'success');
                        } else {
                            alert(message);
                        }
                    } else {
                        const message = result.error || '操作に失敗しました';
                        if (window.showToast) {
                            window.showToast(message, 'error');
                        } else {
                            alert(message);
                        }
                    }
                } catch (error) {
                    console.error('ウィッシュリスト操作エラー:', error);
                    if (window.showToast) {
                        window.showToast('操作に失敗しました', 'error');
                    } else {
                        alert('操作に失敗しました');
                    }
                } finally {
                    this.isLoading = false;
                }
            }
        };
    }

    function variantSelector(config = {}) {
        const merged = Object.assign({}, variantDefaults, config);
        const normalizedImages = Array.isArray(merged.images) ? merged.images : [];
        const defaultMain = merged.initialMainImage || normalizedImages[0]?.image_path || merged.fallbackImage || null;
        const defaultThumb = merged.initialThumbImage || normalizedImages[0]?.thumbnail_path || merged.fallbackImage || defaultMain;

        return {
            quantity: 1,
            selectedType: null,
            selectedWeight: null,
            selectedVariantId: merged.initialVariantId || null,
            selectedProductId: merged.productId || null,
            initialProductId: merged.productId || null,
            currentVariant: null,
            availableWeights: [],
            variants: Array.isArray(merged.variants) ? merged.variants : [],
            productName: merged.productName || '',
            productPrice: Number(merged.productPrice || 0),
            productDiscountPrice: merged.productDiscountPrice !== null && merged.productDiscountPrice !== undefined
                ? Number(merged.productDiscountPrice)
                : null,
            productStock: Number(merged.productStock || 0),
            activeDisplayName: merged.initialDisplayName || merged.productName || '',
            activePricing: {
                price: 0,
                compareAt: null,
                hasDiscount: false,
                priceFormatted: '',
                compareFormatted: '',
                discountRate: 0,
                badgeText: ''
            },
            activeStock: Number(merged.productStock || 0),
            activeStockLabel: '',
            defaultMainImage: defaultMain,
            defaultThumbImage: defaultThumb,
            activeMainImage: defaultMain,
            activeThumbImage: defaultThumb,
            fallbackImage: merged.fallbackImage || null,

            init() {
                if (this.variants.length > 0) {
                    const initialVariant = this.resolveInitialVariant();
                    if (initialVariant) {
                        this.selectedType = initialVariant.type_name;
                        this.selectedWeight = initialVariant.weight_grams;
                        this.applyVariant(initialVariant, { skipUrl: true });
                    } else {
                        this.resetToProductDefaults();
                    }
                    this.updateAvailableWeights();
                } else {
                    this.resetToProductDefaults();
                }
                this.updateStockLabel();
            },

            resolveInitialVariant() {
                if (!this.variants.length) {
                    return null;
                }

                if (this.selectedVariantId) {
                    const matched = this.variants.find(v => Number(v.id) === Number(this.selectedVariantId));
                    if (matched) {
                        return matched;
                    }
                }

                const defaultVariant = this.variants.find(v => Number(v.is_default) === 1);
                if (defaultVariant) {
                    this.selectedVariantId = defaultVariant.id;
                    return defaultVariant;
                }

                this.selectedVariantId = this.variants[0].id;
                return this.variants[0];
            },

            updateAvailableWeights() {
                if (!this.selectedType) {
                    this.availableWeights = [];
                    return;
                }

                const weights = this.variants
                    .filter(v => v.type_name === this.selectedType)
                    .map(v => v.weight_grams)
                    .filter(value => value !== null && value !== undefined && value !== '');

                const seen = new Set();
                this.availableWeights = weights.filter(weight => {
                    const key = typeof weight === 'number' ? weight : String(weight);
                    if (seen.has(key)) {
                        return false;
                    }
                    seen.add(key);
                    return true;
                }).sort((a, b) => {
                    if (typeof a === 'number' && typeof b === 'number') {
                        return a - b;
                    }
                    return 0;
                });

                if (this.availableWeights.length > 0 && !this.availableWeights.includes(this.selectedWeight)) {
                    this.selectedWeight = this.availableWeights[0];
                }
            },

            selectType(typeName) {
                this.selectedType = typeName;
                this.updateAvailableWeights();

                if (this.availableWeights.length === 0) {
                    const fallbackVariant = this.variants.find(v => v.type_name === typeName);
                    if (fallbackVariant) {
                        this.applyVariant(fallbackVariant);
                    }
                    return;
                }

                this.updateVariantFromSelection();
            },

            selectWeight(weight) {
                this.selectedWeight = weight;
                this.updateVariantFromSelection();
            },

            updateVariantFromSelection() {
                if (!this.selectedType) {
                    return;
                }

                let variant = null;
                if (this.selectedWeight !== null && this.selectedWeight !== undefined) {
                    variant = this.variants.find(v =>
                        v.type_name === this.selectedType &&
                        (Number(v.weight_grams) === Number(this.selectedWeight) || v.weight_grams == this.selectedWeight)
                    );
                }

                if (!variant) {
                    variant = this.variants.find(v => v.type_name === this.selectedType) || null;
                }

                if (variant) {
                    this.applyVariant(variant);
                }
            },

            applyVariant(variant, options = {}) {
                this.currentVariant = variant;
                this.selectedVariantId = variant?.id || null;
                this.selectedProductId = variant?.product_id || this.initialProductId;
                if (variant) {
                    this.selectedType = variant.type_name;
                    this.selectedWeight = variant.weight_grams;
                }

                this.activeDisplayName = variant?.variant_name || this.productName;
                this.updatePricingForVariant(variant);
                this.updateStockForVariant(variant);
                this.updateImagesForVariant(variant);

                if (!options.skipUrl) {
                    this.syncVariantToUrl();
                }
            },

            resetToProductDefaults() {
                this.currentVariant = null;
                this.selectedVariantId = null;
                this.selectedProductId = this.initialProductId;
                this.activeDisplayName = this.productName;
                this.updatePricingForVariant(null);
                this.updateStockForVariant(null);
                this.updateImagesForVariant(null);
            },

            updatePricingForVariant(variant) {
                const price = variant?.price ?? (this.productDiscountPrice ?? this.productPrice);
                const compareAt = variant?.compare_at_price ?? (this.productDiscountPrice !== null ? this.productPrice : null);

                this.activePricing.price = Number(price) || 0;
                this.activePricing.compareAt = compareAt !== null ? Number(compareAt) : null;
                this.activePricing.hasDiscount = this.activePricing.compareAt !== null && this.activePricing.compareAt > this.activePricing.price;
                this.activePricing.priceFormatted = this.formatCurrency(this.activePricing.price);
                this.activePricing.compareFormatted = this.activePricing.compareAt !== null
                    ? this.formatCurrency(this.activePricing.compareAt)
                    : '';

                if (this.activePricing.hasDiscount) {
                    const rate = (this.activePricing.compareAt - this.activePricing.price) / this.activePricing.compareAt;
                    this.activePricing.discountRate = Math.max(0, Math.round(rate * 100));
                    this.activePricing.badgeText = `${this.activePricing.discountRate}% OFF`;
                } else {
                    this.activePricing.discountRate = 0;
                    this.activePricing.badgeText = '';
                }
            },

            updateStockForVariant(variant) {
                const stock = variant?.stock ?? this.productStock ?? 0;
                this.activeStock = Number(stock);
                if (this.activeStock > 0 && this.quantity > this.activeStock) {
                    this.quantity = this.activeStock;
                }
                if (this.activeStock <= 0) {
                    this.quantity = 1;
                }
                this.updateStockLabel();
            },

            updateStockLabel() {
                this.activeStockLabel = this.activeStock > 0
                    ? `在庫あり（残り${this.activeStock}個）`
                    : '在庫切れ';
            },

            updateImagesForVariant(variant) {
                if (variant?.image_url) {
                    this.activeMainImage = variant.image_url;
                    this.activeThumbImage = variant.image_url;
                    return;
                }

                this.activeMainImage = this.defaultMainImage || this.fallbackImage || null;
                this.activeThumbImage = this.defaultThumbImage || this.activeMainImage;
            },

            syncVariantToUrl() {
                if (typeof window === 'undefined' || typeof URL === 'undefined') {
                    return;
                }

                try {
                    const currentUrl = new URL(window.location.href);
                    if (this.selectedVariantId) {
                        currentUrl.searchParams.set('variant_id', this.selectedVariantId);
                    } else {
                        currentUrl.searchParams.delete('variant_id');
                    }
                    window.history.replaceState({}, '', currentUrl);
                } catch (error) {
                    console.error('Failed to sync variant parameter', error);
                }
            },

            formatCurrency(value) {
                return `¥${Number(value || 0).toLocaleString('ja-JP')}`;
            }
        };
    }

    function initSwipers() {
        if (typeof Swiper === 'undefined') {
            return;
        }

        let thumbSwiper = null;
        if (document.querySelector('.productThumbSwiper')) {
            thumbSwiper = new Swiper('.productThumbSwiper', {
                spaceBetween: 10,
                slidesPerView: 4,
                freeMode: true,
                watchSlidesProgress: true,
                breakpoints: {
                    320: { slidesPerView: 3 },
                    640: { slidesPerView: 4 }
                }
            });
        }

        if (document.querySelector('.productMainSwiper')) {
            new Swiper('.productMainSwiper', {
                spaceBetween: 10,
                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev'
                },
                pagination: {
                    el: '.swiper-pagination',
                    clickable: true
                },
                thumbs: thumbSwiper ? { swiper: thumbSwiper } : undefined,
                autoplay: false,
                loop: false
            });
        }
    }

    function registerCompareButton() {
        const button = document.querySelector('[data-add-to-compare]');
        if (!button || typeof window.addToCompare !== 'function') {
            return;
        }
        const productId = Number(button.dataset.productId);
        if (!Number.isFinite(productId)) {
            return;
        }
        button.addEventListener('click', () => {
            window.addToCompare(productId);
        });
    }

    function registerShareButton() {
        const button = document.querySelector('[data-share-button]');
        if (!button) {
            return;
        }
        const shareUrl = pageConfig.productUrl || window.location.href;
        const shareText = `${pageConfig.productName || document.title} - おうちかふぇ`;
        button.addEventListener('click', () => {
            if (navigator.share) {
                navigator.share({ title: shareText, url: shareUrl }).catch(error => {
                    console.log('シェアエラー:', error);
                });
            } else if (window.SocialShare && typeof SocialShare.copyUrl === 'function') {
                SocialShare.copyUrl(shareUrl);
            }
        });
    }

    function registerViewHistory() {
        if (!window.ViewHistory || !viewHistoryConfig.productId) {
            return;
        }
        const productImage = viewHistoryConfig.productImage || pageConfig.fallbackImage || '';
        const price = Number(viewHistoryConfig.productPrice) || 0;
        ViewHistory.addProduct(viewHistoryConfig.productId, viewHistoryConfig.productName || '', price, productImage);
    }

    function openLightbox(index = 0) {
        if (!lightboxState.images.length) {
            return;
        }
        lightboxState.currentIndex = Math.max(0, Math.min(index, lightboxState.images.length - 1));
        const lightbox = document.createElement('div');
        lightbox.id = 'imageLightbox';
        lightbox.className = 'fixed inset-0 bg-black bg-opacity-90 z-50 flex items-center justify-center';
        lightbox.innerHTML = `
            <button onclick="closeLightbox()" class="absolute top-4 right-4 text-white text-4xl hover:text-gray-300 z-10">&times;</button>
            ${lightboxState.images.length > 1 ? `
                <button onclick="prevImage(event)" class="absolute left-4 text-white text-4xl hover:text-gray-300 z-10">&#8249;</button>
                <button onclick="nextImage(event)" class="absolute right-4 text-white text-4xl hover:text-gray-300 z-10">&#8250;</button>
            ` : ''}
            <img id="lightboxImage" src="${lightboxState.images[lightboxState.currentIndex]}" class="max-w-full max-h-full object-contain" alt="">
            <div class="absolute bottom-4 text-white text-sm">
                ${lightboxState.currentIndex + 1} / ${lightboxState.images.length}
            </div>
        `;
        document.body.appendChild(lightbox);
        document.body.style.overflow = 'hidden';
        document.addEventListener('keydown', handleLightboxKeydown);
    }

    function closeLightbox() {
        const lightbox = document.getElementById('imageLightbox');
        if (lightbox) {
            lightbox.remove();
            document.body.style.overflow = '';
            document.removeEventListener('keydown', handleLightboxKeydown);
        }
    }

    function prevImage(event) {
        event?.stopPropagation();
        if (!lightboxState.images.length) {
            return;
        }
        lightboxState.currentIndex = (lightboxState.currentIndex - 1 + lightboxState.images.length) % lightboxState.images.length;
        updateLightboxImage();
    }

    function nextImage(event) {
        event?.stopPropagation();
        if (!lightboxState.images.length) {
            return;
        }
        lightboxState.currentIndex = (lightboxState.currentIndex + 1) % lightboxState.images.length;
        updateLightboxImage();
    }

    function updateLightboxImage() {
        const img = document.getElementById('lightboxImage');
        if (img && lightboxState.images[lightboxState.currentIndex]) {
            img.src = lightboxState.images[lightboxState.currentIndex];
            const counter = document.querySelector('#imageLightbox .absolute.bottom-4');
            if (counter) {
                counter.textContent = `${lightboxState.currentIndex + 1} / ${lightboxState.images.length}`;
            }
        }
    }

    function handleLightboxKeydown(event) {
        if (event.key === 'Escape') {
            closeLightbox();
        } else if (event.key === 'ArrowLeft') {
            prevImage(event);
        } else if (event.key === 'ArrowRight') {
            nextImage(event);
        }
    }

    function attachLightboxTriggers() {
        if (!lightboxState.images.length) {
            return;
        }
        const triggers = document.querySelectorAll('[data-lightbox-index]');
        if (!triggers.length) {
            return;
        }
        triggers.forEach(trigger => {
            const index = Number(trigger.dataset.lightboxIndex);
            if (!Number.isFinite(index)) {
                return;
            }
            trigger.addEventListener('click', event => {
                event.preventDefault();
                openLightbox(index);
            });
        });
    }

    window.productPage = Object.assign(window.productPage || {}, {
        variantSelector,
        reviewsApp,
        wishlistButtonApp
    });

    window.openLightbox = openLightbox;
    window.closeLightbox = closeLightbox;
    window.prevImage = prevImage;
    window.nextImage = nextImage;

    document.addEventListener('DOMContentLoaded', () => {
        initSwipers();
        registerCompareButton();
        registerShareButton();
        registerViewHistory();
        attachLightboxTriggers();
    });
})();
