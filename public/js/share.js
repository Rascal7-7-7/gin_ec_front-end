/**
 * SNS Share Functionality
 * 
 * 【このファイルの目的】
 * 商品をSNS（X/Twitter、Facebook、LINE）でシェアする機能を提供します。
 * URLをクリップボードにコピーする機能も含まれています。
 * 
 * 【使い方】
 * SocialShare.shareOnX(url, text)       → Xでシェア
 * SocialShare.shareOnFacebook(url)      → Facebookでシェア
 * SocialShare.shareOnLine(url, text)    → LINEでシェア
 * SocialShare.copyUrl(url)              → URLをコピー
 */

const SocialShare = {
    /**
     * X (Twitter) でシェア
     * 
     * 【動作】
     * 1. Xのシェア用URLを構築
     * 2. ポップアップウィンドウを開く
     * 3. ユーザーはX上で投稿内容を編集して送信
     * 
     * @param {string} url - シェアするURL（商品ページのURLなど）
     * @param {string} text - シェア時のテキスト（商品名など）
     */
    shareOnX: function(url, text) {
        // encodeURIComponent: URLに含めるために文字列をエンコード（特殊文字を変換）
        const shareUrl = `https://twitter.com/intent/tweet?url=${encodeURIComponent(url)}&text=${encodeURIComponent(text)}`;
        // 550x420のポップアップウィンドウを開く
        this.openPopup(shareUrl, 'Share on X', 550, 420);
    },

    /**
     * Facebook でシェア
     * 
     * 【動作】
     * 1. Facebookのシェア用URLを構築
     * 2. ポップアップウィンドウを開く
     * 3. ユーザーはFacebook上でコメントを追加して投稿
     * 
     * @param {string} url - シェアするURL
     */
    shareOnFacebook: function(url) {
        const shareUrl = `https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(url)}`;
        this.openPopup(shareUrl, 'Share on Facebook', 550, 420);
    },

    /**
     * LINE でシェア
     * 
     * 【動作】
     * 1. LINEのシェア用URLを構築
     * 2. ポップアップウィンドウを開く
     * 3. ユーザーはLINE上で送信先を選択
     * 
     * @param {string} url - シェアするURL
     * @param {string} text - シェア時のテキスト
     */
    shareOnLine: function(url, text) {
        const shareUrl = `https://social-plugins.line.me/lineit/share?url=${encodeURIComponent(url)}&text=${encodeURIComponent(text)}`;
        this.openPopup(shareUrl, 'Share on LINE', 550, 420);
    },

    /**
     * Copy URL to clipboard
     */
    copyUrl: function(url) {
        if (navigator.clipboard && window.isSecureContext) {
            navigator.clipboard.writeText(url).then(() => {
                this.showToast('URLをコピーしました');
            }).catch(err => {
                console.error('Failed to copy:', err);
                this.fallbackCopyUrl(url);
            });
        } else {
            this.fallbackCopyUrl(url);
        }
    },

    /**
     * Fallback copy method for older browsers
     */
    fallbackCopyUrl: function(url) {
        const textArea = document.createElement('textarea');
        textArea.value = url;
        textArea.style.position = 'fixed';
        textArea.style.left = '-999999px';
        document.body.appendChild(textArea);
        textArea.focus();
        textArea.select();
        
        try {
            document.execCommand('copy');
            this.showToast('URLをコピーしました');
        } catch (err) {
            console.error('Fallback: Oops, unable to copy', err);
            this.showToast('コピーに失敗しました', 'error');
        }
        
        document.body.removeChild(textArea);
    },

    /**
     * Open popup window
     */
    openPopup: function(url, title, width, height) {
        const left = (screen.width - width) / 2;
        const top = (screen.height - height) / 2;
        const features = `width=${width},height=${height},left=${left},top=${top},toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=yes`;
        
        window.open(url, title, features);
    },

    /**
     * Show toast notification
     */
    showToast: function(message, type = 'success') {
        // Check if a global toast function exists
        if (typeof window.showToast === 'function') {
            window.showToast(message, type);
            return;
        }

        // Fallback: Create simple toast
        const toast = document.createElement('div');
        toast.className = `fixed bottom-4 right-4 px-6 py-3 rounded-lg shadow-lg z-50 ${
            type === 'success' ? 'bg-green-600' : 'bg-red-600'
        } text-white font-medium`;
        toast.textContent = message;
        
        document.body.appendChild(toast);
        
        setTimeout(() => {
            toast.style.opacity = '0';
            toast.style.transition = 'opacity 0.3s';
            setTimeout(() => {
                document.body.removeChild(toast);
            }, 300);
        }, 2000);
    },

    bindShareButtons: function() {
        const buttons = document.querySelectorAll('[data-share-action]');
        buttons.forEach(button => {
            button.addEventListener('click', (event) => {
                event.preventDefault();
                const action = button.dataset.shareAction;
                const url = button.dataset.shareUrl;
                const text = button.dataset.shareText || '';

                if (!url) {
                    console.warn('shareUrl is missing for share button');
                    return;
                }

                switch (action) {
                    case 'x':
                        SocialShare.shareOnX(url, text);
                        break;
                    case 'facebook':
                        SocialShare.shareOnFacebook(url);
                        break;
                    case 'line':
                        SocialShare.shareOnLine(url, text);
                        break;
                    case 'copy':
                        SocialShare.copyUrl(url);
                        break;
                    default:
                        console.warn('Unknown share action:', action);
                }
            });
        });
    }
};

// Global function for easy access
window.shareOnX = (url, text) => SocialShare.shareOnX(url, text);
window.shareOnFacebook = (url) => SocialShare.shareOnFacebook(url);
window.shareOnLine = (url, text) => SocialShare.shareOnLine(url, text);
window.copyUrl = (url) => SocialShare.copyUrl(url);

document.addEventListener('DOMContentLoaded', () => {
    SocialShare.bindShareButtons();
});
