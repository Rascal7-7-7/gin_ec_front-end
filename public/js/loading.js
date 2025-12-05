/**
 * Global Loading Overlay
 * Phase 16: UX改善機能
 * 
 * 【このファイルの目的】
 * Ajaxリクエスト中やページ遷移時に、画面全体を覆うローディング表示を管理します。
 * 「読み込み中...」のくるくる回るアニメーションを表示するためのコードです。
 * 
 * 【使い方】
 * LoadingOverlay.show()  → ローディング表示開始
 * LoadingOverlay.hide()  → ローディング表示終了
 */

const LoadingOverlay = {
    overlay: null,       // ローディング用のHTML要素を格納
    requestCount: 0,     // 同時実行中のリクエスト数をカウント
    
    /**
     * 初期化
     * 
     * 【動作】
     * 1. ローディング用のHTML要素を作成
     * 2. 画面全体を覆う半透明の背景を設定
     * 3. 中央に「読み込み中...」のスピナーを配置
     * 4. ページのbodyタグに追加
     */
    init() {
        // 既に初期化済みなら何もしない
        if (this.overlay) return;
        
        // div要素を動的に作成
        this.overlay = document.createElement('div');
        this.overlay.id = 'loading-overlay';            // ID属性を設定
        this.overlay.className = 'loading-overlay';     // class属性を設定
        
        // ローディング表示のHTML（スピナーとテキスト）
        this.overlay.innerHTML = `
            <div class="loading-spinner">
                <div class="spinner"></div>
                <p class="loading-text">読み込み中...</p>
            </div>
        `;
        
        // ページのbodyタグの末尾に追加
        document.body.appendChild(this.overlay);
        
        // CSSスタイルを追加
        this.addStyles();
    },
    
    /**
     * スタイル追加
     */
    addStyles() {
        if (document.getElementById('loading-overlay-styles')) return;
        
        const style = document.createElement('style');
        style.id = 'loading-overlay-styles';
        style.textContent = `
            .loading-overlay {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background-color: rgba(0, 0, 0, 0.5);
                display: none;
                justify-content: center;
                align-items: center;
                z-index: 9999;
                backdrop-filter: blur(2px);
            }
            
            .loading-overlay.active {
                display: flex;
            }
            
            .loading-spinner {
                text-align: center;
            }
            
            .spinner {
                border: 4px solid rgba(255, 255, 255, 0.3);
                border-radius: 50%;
                border-top: 4px solid #ffffff;
                width: 50px;
                height: 50px;
                animation: spin 1s linear infinite;
                margin: 0 auto 1rem;
            }
            
            .loading-text {
                color: #ffffff;
                font-size: 1rem;
                font-weight: 500;
            }
            
            @keyframes spin {
                0% { transform: rotate(0deg); }
                100% { transform: rotate(360deg); }
            }
        `;
        document.head.appendChild(style);
    },
    
    /**
     * ローディング表示
     */
    show() {
        this.init();
        this.requestCount++;
        this.overlay.classList.add('active');
    },
    
    /**
     * ローディング非表示
     */
    hide() {
        this.requestCount--;
        if (this.requestCount <= 0) {
            this.requestCount = 0;
            if (this.overlay) {
                this.overlay.classList.remove('active');
            }
        }
    },
    
    /**
     * 強制非表示
     */
    forceHide() {
        this.requestCount = 0;
        if (this.overlay) {
            this.overlay.classList.remove('active');
        }
    }
};

/**
 * Fetch APIのラッパー（自動ローディング表示）
 */
window.fetchWithLoading = async function(url, options = {}) {
    LoadingOverlay.show();
    
    try {
        const response = await fetch(url, options);
        return response;
    } finally {
        LoadingOverlay.hide();
    }
};

/**
 * フォーム送信時のローディング表示
 */
document.addEventListener('DOMContentLoaded', function() {
    // 初期化
    LoadingOverlay.init();
    
    // フォーム送信時にローディング表示
    document.addEventListener('submit', function(e) {
        // Ajax送信以外（通常のフォーム送信）の場合のみローディング表示
        if (!e.target.classList.contains('no-loading')) {
            LoadingOverlay.show();
        }
    });
    
    // ページ離脱時にローディング非表示
    window.addEventListener('beforeunload', function() {
        LoadingOverlay.forceHide();
    });
    
    // ページ読み込み完了時にローディング非表示（念のため）
    window.addEventListener('load', function() {
        LoadingOverlay.forceHide();
    });
});

// グローバルに公開
window.LoadingOverlay = LoadingOverlay;
