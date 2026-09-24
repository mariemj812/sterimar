/* ==========================================
   STÉRIMAR™ E-Commerce — Application Logic
   ========================================== */

// ==========================================
// PRODUCTS DATA
// ==========================================
const products = [
    {
        id: 0,
        wcId: 18,
        name: "Hygiène du Nez",
        category: "hygiene",
        categoryLabel: "Hygiène",
        categoryColor: "#00B8E5",
        type: "Physiologique",
        molecule: "Oligo-éléments",
        price: 16.00,
        image: "hygiene-adulte.png",
        description: "Spray nasal isotonique pour nettoyer, humidifier et protéger la muqueuse nasale au quotidien. Enrichi en oligo-éléments marins, il élimine les impuretés, poussières et allergènes tout en hydratant les cavités nasales.",
        features: [
            "Solution isotonique d'eau de mer",
            "Enrichi en oligo-éléments marins",
            "Nettoie, humidifie et protège",
            "Sans conservateur, sans gaz propulseur",
            "Micro-diffusion brevetée",
            "Dès 3 ans et adulte"
        ],
        usage: "1 à 2 pulvérisations dans chaque narine, 1 à 3 fois par jour. Pencher la tête sur le côté, insérer l'embout dans la narine supérieure et pulvériser. Moucher après utilisation.",
        audience: "Adulte & Enfant",
        volume: "100ml"
    },
    {
        id: 1,
        wcId: 25,
        name: "Nez sujet aux Rhumes",
        category: "rhume",
        categoryLabel: "Rhume",
        categoryColor: "#F15B2B",
        type: "Physiologique",
        molecule: "Soufre",
        price: 16.00,
        image: "cold-adulte.png",
        description: "Spray nasal préventif enrichi en soufre pour renforcer la résistance de la muqueuse nasale face aux agressions hivernales. Il aide à prévenir l'apparition des rhumes en maintenant une barrière nasale saine.",
        features: [
            "Enrichi en soufre",
            "Renforce la résistance nasale",
            "Prévention des rhumes",
            "Solution isotonique d'eau de mer",
            "Sans conservateur",
            "Dès 3 ans et adulte"
        ],
        usage: "2 à 3 pulvérisations dans chaque narine, 2 à 3 fois par jour. Utilisation recommandée dès les premiers signes de refroidissement ou en période hivernale.",
        audience: "Adulte & Enfant",
        volume: "100ml"
    },
    {
        id: 2,
        wcId: 36,
        name: "Nez Bouché",
        category: "rhume",
        categoryLabel: "Rhume",
        categoryColor: "#F15B2B",
        type: "Hypertonique",
        molecule: "Cuivre",
        price: 16.00,
        image: "nez-bouché-adulte.png",
        description: "Spray hypertonique enrichi en cuivre pour décongestionner rapidement le nez bouché. Son action osmotique aide à réduire l'œdème de la muqueuse nasale et facilite l'élimination du mucus en cas de rhume ou de sinusite.",
        features: [
            "Solution hypertonique concentrée",
            "Enrichi en cuivre anti-inflammatoire",
            "Décongestion rapide",
            "Action osmotique naturelle",
            "Sans vasoconstricteur",
            "Dès 3 ans et adulte"
        ],
        usage: "1 à 2 pulvérisations dans chaque narine, 2 à 3 fois par jour pendant la période de congestion. Ne pas dépasser 5 jours d'utilisation consécutifs.",
        audience: "Dès 3 ans",
        volume: "100ml"
    },
    {
        id: 3,
        wcId: 35,
        name: "Nez Allergique",
        category: "allergie",
        categoryLabel: "Allergie",
        categoryColor: "#79C142",
        type: "Physiologique",
        molecule: "Manganèse",
        price: 16.00,
        image: "allergie.png",
        description: "Spray enrichi en manganèse pour soulager les symptômes d'allergie nasale : éternuements, nez qui coule, congestion. Élu Meilleur Produit Pharma, il aide à éliminer les allergènes (pollens, acariens, poussières) et à apaiser la muqueuse nasale irritée.",
        features: [
            "Enrichi en manganèse anti-allergique",
            "Élimine pollens et allergènes",
            "Apaise la muqueuse irritée",
            "Élu Meilleur Produit Pharma",
            "Sans corticoïdes",
            "Adulte & Enfant dès 3 ans"
        ],
        usage: "2 à 3 pulvérisations dans chaque narine, 1 à 3 fois par jour. Utilisation recommandée pendant toute la durée de l'exposition aux allergènes.",
        audience: "Adulte & Enfant",
        volume: "100ml"
    },
    {
        id: 4,
        wcId: 32,
        name: "Hygiène du Nez Bébé",
        category: "bebe",
        categoryLabel: "Bébé",
        categoryColor: "#59AEE1",
        type: "Physiologique",
        molecule: "Oligo-éléments",
        price: 16.00,
        image: "hygiene-bébé.png",
        description: "Spray nasal pour les petits de 0 à 3 ans destiné à nettoyer et hydrater le nez et éliminer les impuretés. Ce spray prévient et diminue les symptômes nasaux (rhume, rhinite, sinusite). Nouvel embout sécurité bébé à forme douce et ergonomique.",
        features: [
            "Riche en oligo-éléments marins",
            "Solution physiologique",
            "Nouvel embout sécurité bébé",
            "Forme douce et ergonomique",
            "Sans conservateur",
            "De 0 à 3 ans"
        ],
        usage: "1 pulvérisation douce dans chaque narine, 1 à 3 fois par jour. Allonger le bébé sur le dos, tourner la tête sur le côté et pulvériser doucement. Moucher le nez du bébé.",
        audience: "Dès la naissance",
        volume: "100ml"
    },
    {
        id: 5,
        wcId: 34,
        name: "Nez Bouché Bébé",
        category: "bebe",
        categoryLabel: "Bébé",
        categoryColor: "#59AEE1",
        type: "Hypertonique",
        molecule: "Cuivre",
        price: 16.00,
        image: "nez-bouché-bébé.png",
        description: "Spray hypertonique enrichi en cuivre pour décongestionner rapidement le nez de bébé dès 3 mois. Avec son nouvel embout sécurité bébé à forme douce et ergonomique, moucher son bébé devient un jeu d'enfant.",
        features: [
            "Hypertonique enrichi en cuivre",
            "Décongestion rapide et naturelle",
            "Nouvel embout sécurité bébé",
            "Adapté aux narines de bébé",
            "Sans vasoconstricteur",
            "Dès 3 mois"
        ],
        usage: "1 pulvérisation dans chaque narine, 2 à 3 fois par jour. Allonger le bébé, tourner la tête et pulvériser doucement. Moucher après chaque application.",
        audience: "Dès 3 mois",
        volume: "100ml"
    },
    {
        id: 6,
        wcId: 25,
        name: "Stop & Protect Rhume",
        category: "rhume",
        categoryLabel: "Rhume",
        categoryColor: "#F15B2B",
        type: "Stop & Protect",
        molecule: "Soufre",
        price: 16.00,
        image: "cold-adulte.png",
        description: "Spray innovant combinant l'eau de mer enrichie en soufre avec une action protectrice. Stop & Protect aide à stopper le rhume dès les premiers symptômes et protège la muqueuse nasale grâce à un film protecteur. Format compact idéal pour emporter partout.",
        features: [
            "Action Stop : stoppe le rhume dès les premiers symptômes",
            "Action Protect : forme un film protecteur nasal",
            "Enrichi en soufre naturel",
            "Eau de mer microfiltrée",
            "Format compact 20ml",
            "Adulte & Enfant dès 6 ans"
        ],
        usage: "1 à 2 pulvérisations dans chaque narine, 3 à 6 fois par jour. À utiliser dès les premiers signes de rhume. Peut être utilisé en complément des autres sprays Stérimar.",
        audience: "Dès 6 ans",
        volume: "20ml"
    }
];

// ==========================================
// CART MANAGEMENT
// ==========================================
let cart = JSON.parse(localStorage.getItem('sterimar_cart')) || [];

function saveCart() {
    localStorage.setItem('sterimar_cart', JSON.stringify(cart));
    updateCartCount();
}

function updateCartCount() {
    const totalItems = cart.reduce((sum, item) => sum + item.quantity, 0);
    document.querySelectorAll('#cart-count, #mobile-cart-count, .cart-count').forEach(el => {
        el.textContent = totalItems;
        el.classList.add('bump');
        setTimeout(() => el.classList.remove('bump'), 400);
    });
}

function addToCart(productId, qty = 1) {
    const product = products[productId];
    const existingItem = cart.find(item => item.id === productId);
    
    if (existingItem) {
        existingItem.quantity += qty;
        existingItem.wcId = product.wcId;
    } else {
        cart.push({
            id: productId,
            wcId: product.wcId,
            name: product.name,
            price: product.price,
            image: product.image,
            category: product.categoryLabel,
            quantity: qty
        });
    }
    
    saveCart();
    showToast(`${product.name} ajouté au panier ✓`);

    // Meta Pixel AddToCart
    if (typeof fbq === 'function') {
        fbq('track', 'AddToCart', {
            content_name: product.name,
            content_ids: [product.wcId ? String(product.wcId) : String(productId)],
            content_type: 'product',
            value: (product.price || 0) * qty,
            currency: 'TND'
        });
    }
    
    // If on cart page, re-render
    if (document.getElementById('cart-items')) {
        renderCart();
    }
}

function removeFromCart(productId) {
    cart = cart.filter(item => item.id !== productId);
    saveCart();
    renderCart();
}

function updateQuantity(productId, delta) {
    const item = cart.find(item => item.id === productId);
    if (item) {
        item.quantity += delta;
        if (item.quantity <= 0) {
            removeFromCart(productId);
            return;
        }
        saveCart();
        renderCart();
    }
}

function renderCart() {
    const cartItemsEl = document.getElementById('cart-items');
    const cartEmptyEl = document.getElementById('cart-empty');
    const cartSummaryEl = document.getElementById('cart-summary');
    
    if (!cartItemsEl) return;
    
    if (cart.length === 0) {
        cartItemsEl.innerHTML = '';
        if (cartEmptyEl) cartEmptyEl.style.display = 'block';
        if (cartSummaryEl) cartSummaryEl.style.display = 'none';
        return;
    }
    
    if (cartEmptyEl) cartEmptyEl.style.display = 'none';
    if (cartSummaryEl) cartSummaryEl.style.display = 'block';
    
    cartItemsEl.innerHTML = cart.map(item => `
        <div class="cart-item" data-id="${item.id}">
            <div class="cart-item-image">
                <img src="${getAssetUrl(item.image)}" alt="${item.name}">
            </div>
            <div class="cart-item-info">
                <span class="cart-item-category">${item.category}</span>
                <h3>${item.name}</h3>
                <span class="cart-item-price-mobile">${(item.price * item.quantity).toFixed(2).replace('.', ',')} DT</span>
            </div>
            <div class="cart-item-actions">
                <div class="quantity-control">
                    <button onclick="updateQuantity(${item.id}, -1)" aria-label="Diminuer">−</button>
                    <span>${item.quantity}</span>
                    <button onclick="updateQuantity(${item.id}, 1)" aria-label="Augmenter">+</button>
                </div>
                <span class="cart-item-price">${(item.price * item.quantity).toFixed(2).replace('.', ',')} DT</span>
                <button class="cart-item-remove" onclick="removeFromCart(${item.id})" aria-label="Supprimer">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2"/>
                    </svg>
                </button>
            </div>
        </div>
    `).join('');
    
    // Update summary
    const subtotal = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
    const freeShippingThreshold = 100;
    const shipping = subtotal === 0 ? 0 : (subtotal >= freeShippingThreshold ? 0 : 7.00);
    const total = subtotal + shipping;
    
    const subtotalEl = document.getElementById('subtotal');
    const shippingEl = document.getElementById('shipping');
    const totalEl = document.getElementById('total');
    
    if (subtotalEl) subtotalEl.textContent = `${subtotal.toFixed(2).replace('.', ',')} DT`;
    if (shippingEl) shippingEl.textContent = subtotal === 0 ? '0,00 DT' : (shipping === 0 ? 'Gratuite' : `${shipping.toFixed(2).replace('.', ',')} DT`);
    if (totalEl) totalEl.textContent = `${total.toFixed(2).replace('.', ',')} DT`;

    // Free shipping dynamic progress bar
    const shippingTextEl = document.getElementById('free-shipping-text');
    const shippingProgressFill = document.getElementById('shipping-progress-fill');
    if (shippingTextEl && shippingProgressFill) {
        if (subtotal === 0) {
            shippingTextEl.innerHTML = '<span>🚚 Livraison gratuite dès <strong>100,00 DT</strong> d\'achat</span>';
            shippingProgressFill.style.width = '0%';
        } else if (subtotal >= freeShippingThreshold) {
            shippingTextEl.innerHTML = '<span>🎉 <strong>Félicitations !</strong> Vous bénéficiez de la <strong>livraison gratuite</strong> !</span>';
            shippingProgressFill.style.width = '100%';
            shippingProgressFill.style.background = '#79C142';
        } else {
            const diff = (freeShippingThreshold - subtotal).toFixed(2).replace('.', ',');
            const percent = Math.min(100, Math.round((subtotal / freeShippingThreshold) * 100));
            shippingTextEl.innerHTML = `<span>🚚 Plus que <strong>${diff} DT</strong> pour profiter de la <strong>livraison gratuite</strong> !</span>`;
            shippingProgressFill.style.width = `${percent}%`;
            shippingProgressFill.style.background = 'linear-gradient(90deg, #00B8E5, #0088cc)';
        }
    }
}

async function checkout() {
    if (cart.length === 0) return;

    // Meta Pixel InitiateCheckout
    if (typeof fbq === 'function') {
        const cartTotal = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
        fbq('track', 'InitiateCheckout', {
            num_items: cart.reduce((sum, item) => sum + item.quantity, 0),
            value: cartTotal,
            currency: 'TND'
        });
    }
    
    const checkoutBtn = document.getElementById('checkout-btn');
    if (checkoutBtn) {
        checkoutBtn.disabled = true;
        checkoutBtn.innerHTML = `<span>Synchronisation en cours...</span>`;
    }
    
    try {
        const formData = new FormData();
        formData.append('sterimar_wc_checkout', '1');
        formData.append('cart', JSON.stringify(cart));
        
        const response = await fetch('/index.php', {
            method: 'POST',
            body: formData
        });
        
        if (response.ok) {
            const data = await response.json();
            if (data.woocommerce && data.checkout_url) {
                window.location.href = data.checkout_url;
                return;
            }
        }
    } catch (err) {
        console.warn('WooCommerce sync note:', err);
    }
    
    const checkoutModal = document.getElementById('checkout-modal');
    if (checkoutModal) {
        checkoutModal.style.display = 'flex';
        cart = [];
        saveCart();
        renderCart();
    }
    
    if (checkoutBtn) {
        checkoutBtn.disabled = false;
        checkoutBtn.innerHTML = `Passer la commande <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m5 12h14m-7-7 7 7-7 7"/></svg>`;
    }
}

// ==========================================
// ASSET PATH RESOLVER (WordPress & Standalone)
// ==========================================
function getAssetUrl(path) {
    if (!path) return '';
    if (path.startsWith('http://') || path.startsWith('https://') || path.startsWith('data:') || path.startsWith('//')) {
        return path;
    }
    // 1. Injected by WordPress index.php
    if (typeof window !== 'undefined' && window.STERIMAR_THEME_URI) {
        const base = window.STERIMAR_THEME_URI.replace(/\/+$/, '') + '/';
        return base + path.replace(/^\/+/, '');
    }
    // 2. Auto-detect from app.js script tag
    const script = document.querySelector('script[src*="app.js"]');
    if (script && script.src) {
        const srcClean = script.src.split('?')[0];
        const base = srcClean.substring(0, srcClean.lastIndexOf('/') + 1);
        if (base && (base.startsWith('http') || base.startsWith('file:'))) {
            return base + path.replace(/^\/+/, '');
        }
    }
    // 3. Auto-detect from style.css link tag
    const link = document.querySelector('link[href*="style.css"]');
    if (link && link.href) {
        const hrefClean = link.href.split('?')[0];
        const base = hrefClean.substring(0, hrefClean.lastIndexOf('/') + 1);
        if (base && (base.startsWith('http') || base.startsWith('file:'))) {
            return base + path.replace(/^\/+/, '');
        }
    }
    return path;
}

// ==========================================
// CATEGORY BANNER DATA & MANAGEMENT
// ==========================================
const categoryBannerData = {
    all: {
        tag: "Collection Complète Stérimar™",
        title: "Toutes Nos Solutions",
        subtitle: "6 solutions naturelles à base d'eau de mer 100% naturelle de la Baie de Cancale pour toute la famille.",
        badge: "Gamme Complète • Physiologique & Hypertonique",
        image: "cat-banner-all.jpg?v=20260922g",
        themeColor: "#0077B6"
    },
    hygiene: {
        tag: "Hygiène & Prévention Quotidienne",
        title: "Gamme Hygiène du Nez",
        subtitle: "Lavez, hydratez et protégez vos fosses nasales tous les jours. Enrichi en oligo-éléments marins.",
        badge: "Physiologique • Dès la naissance & Adulte",
        image: "cat-banner-hygiene.jpg?v=20260922g",
        themeColor: "#00B8E5"
    },
    rhume: {
        tag: "Décongestion & Prévention Hivernale",
        title: "Gamme Rhume & Nez Bouché",
        subtitle: "Débouchez jusqu'à 6h et stoppez les symptômes du rhume grâce aux formules enrichies en Cuivre & Soufre.",
        badge: "Décongestion 6h • Cuivre & Soufre",
        image: "cat-banner-rhume.jpg?v=20260922g",
        themeColor: "#E05326"
    },
    allergie: {
        tag: "Protection Anti-Allergique Naturelle",
        title: "Gamme Nez Allergique",
        subtitle: "Élimine pollens, acariens et poils d'animaux. Formule enrichie en Manganèse protecteur et anti-allergique.",
        badge: "Protection Naturelle • Manganèse",
        image: "cat-banner-allergie.jpg?v=20260922g",
        themeColor: "#4E9B28"
    },
    bebe: {
        tag: "Douceur & Sécurité Nouveau-Né",
        title: "Gamme Stérimar™ Bébé",
        subtitle: "Spécialement formulé pour les tout-petits de 0 à 3 ans avec son embout sécurité pédiatrique breveté.",
        badge: "Dès la naissance • Embout sécurité pédiatrique",
        image: "cat-banner-bebe.jpg?v=20260922g",
        themeColor: "#3594D2"
    }
};

const bannerCategoryOrder = ['all', 'hygiene', 'rhume', 'allergie', 'bebe'];

function updateCategoryBanner(filterKey) {
    const data = categoryBannerData[filterKey] || categoryBannerData.all;
    const tagEl = document.getElementById('cat-banner-tag');
    const titleEl = document.getElementById('cat-banner-title');
    const subtitleEl = document.getElementById('cat-banner-subtitle');
    const badgeTextEl = document.getElementById('cat-banner-badge-text');
    const bannerBg = document.getElementById('cat-banner-bg');
    const bannerContent = document.querySelector('.category-banner-content');

    if (tagEl) tagEl.textContent = data.tag;
    if (titleEl) titleEl.textContent = data.title;
    if (subtitleEl) subtitleEl.textContent = data.subtitle;
    if (badgeTextEl) badgeTextEl.textContent = data.badge;
    
    const fullImgUrl = getAssetUrl(data.image);
    if (bannerBg) {
        bannerBg.style.backgroundImage = `url('${fullImgUrl}')`;
    }

    if (bannerContent) {
        bannerContent.style.animation = 'none';
        bannerContent.offsetHeight; // trigger reflow
        bannerContent.style.animation = 'fadeIn 0.35s ease forwards';
    }

    // Update active state of pagination dots
    const dots = document.querySelectorAll('.cat-banner-dot');
    dots.forEach(dot => {
        if (dot.dataset.filter === filterKey) {
            dot.classList.add('active');
        } else {
            dot.classList.remove('active');
        }
    });
}

function initCategoryBannerNav() {
    const prevBtn = document.getElementById('cat-banner-prev');
    const nextBtn = document.getElementById('cat-banner-next');
    const dots = document.querySelectorAll('.cat-banner-dot');
    const bannerSection = document.querySelector('.category-banner-section');
    
    if (!prevBtn && !nextBtn && dots.length === 0) return;
    
    function getCurrentCategory() {
        const activeFilterBtn = document.querySelector('.filter-btn.active');
        return activeFilterBtn ? activeFilterBtn.dataset.filter : 'all';
    }
    
    function setCategory(categoryKey) {
        const targetBtn = document.querySelector(`.filter-btn[data-filter="${categoryKey}"]`);
        if (targetBtn) {
            targetBtn.click();
        } else {
            updateCategoryBanner(categoryKey);
        }
    }
    
    function navigateBanner(direction) {
        const currentCat = getCurrentCategory();
        let currentIndex = bannerCategoryOrder.indexOf(currentCat);
        if (currentIndex === -1) currentIndex = 0;
        
        let newIndex;
        if (direction === 'next') {
            newIndex = (currentIndex + 1) % bannerCategoryOrder.length;
        } else {
            newIndex = (currentIndex - 1 + bannerCategoryOrder.length) % bannerCategoryOrder.length;
        }
        
        setCategory(bannerCategoryOrder[newIndex]);
    }
    
    if (prevBtn) {
        prevBtn.addEventListener('click', (e) => {
            e.preventDefault();
            e.stopPropagation();
            navigateBanner('prev');
        });
    }
    
    if (nextBtn) {
        nextBtn.addEventListener('click', (e) => {
            e.preventDefault();
            e.stopPropagation();
            navigateBanner('next');
        });
    }
    
    dots.forEach(dot => {
        dot.addEventListener('click', (e) => {
            e.preventDefault();
            e.stopPropagation();
            const filter = dot.dataset.filter;
            if (filter) setCategory(filter);
        });
    });
    
    // Touch swipe support for mobile
    if (bannerSection) {
        let touchStartX = 0;
        let touchEndX = 0;
        
        bannerSection.addEventListener('touchstart', (e) => {
            if (e.changedTouches && e.changedTouches.length > 0) {
                touchStartX = e.changedTouches[0].screenX;
            }
        }, { passive: true });
        
        bannerSection.addEventListener('touchend', (e) => {
            if (e.changedTouches && e.changedTouches.length > 0) {
                touchEndX = e.changedTouches[0].screenX;
                const diffX = touchStartX - touchEndX;
                if (Math.abs(diffX) > 40) {
                    if (diffX > 0) {
                        navigateBanner('next');
                    } else {
                        navigateBanner('prev');
                    }
                }
            }
        }, { passive: true });
    }
}

// ==========================================
// FILTER PRODUCTS
// ==========================================
function handleHashFilter() {
    const hash = window.location.hash.replace('#', '').toLowerCase();
    if (hash) {
        const targetBtn = document.querySelector(`.filter-btn[data-filter="${hash}"]`);
        if (targetBtn) {
            targetBtn.click();
            const filterBar = document.getElementById('filter-bar') || document.getElementById('shop-products');
            if (filterBar) {
                setTimeout(() => {
                    filterBar.scrollIntoView({ behavior: 'smooth' });
                }, 100);
            }
            return;
        }
        if (categoryBannerData[hash]) {
            updateCategoryBanner(hash);
        }
    } else {
        updateCategoryBanner('all');
    }
}

function initFilters() {
    const filterBtns = document.querySelectorAll('.filter-btn');
    const productCards = document.querySelectorAll('.product-card-full');
    
    if (filterBtns.length === 0) return;
    
    filterBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            const filter = btn.dataset.filter;
            
            // Update active state
            filterBtns.forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            
            // Update category banner
            updateCategoryBanner(filter);
            
            // Filter products with animation
            productCards.forEach(card => {
                if (filter === 'all' || card.dataset.category === filter) {
                    card.classList.remove('hidden');
                    card.style.animation = 'fadeInUp 0.5s var(--ease-out) forwards';
                } else {
                    card.classList.add('hidden');
                }
            });
        });
    });
    
    // Check for hash filters on initial load
    handleHashFilter();
    
    // Listen for hashchange (e.g. clicking category links in footer or navigation)
    window.addEventListener('hashchange', handleHashFilter);
}

// ==========================================
// TOAST NOTIFICATIONS
// ==========================================
function showToast(message) {
    // Remove existing toast
    const existing = document.querySelector('.toast');
    if (existing) existing.remove();
    
    const toast = document.createElement('div');
    toast.className = 'toast';
    toast.innerHTML = `<span class="toast-icon">🛒</span> ${message}`;
    document.body.appendChild(toast);
    
    requestAnimationFrame(() => {
        toast.classList.add('show');
    });
    
    setTimeout(() => {
        toast.classList.remove('show');
        setTimeout(() => toast.remove(), 400);
    }, 2500);
}

// ==========================================
// NAVBAR
// ==========================================
function initNavbar() {
    const navbar = document.getElementById('navbar');
    const navToggle = document.getElementById('nav-toggle');
    const navLinks = document.getElementById('nav-links');
    const navPanier = document.getElementById('nav-panier');
    
    // Inject mobile direct account button if not present
    if (navToggle && !document.getElementById('mobile-header-account')) {
        const mobileAccount = document.createElement('a');
        mobileAccount.href = '/creation-compte/';
        mobileAccount.id = 'mobile-header-account';
        mobileAccount.className = 'mobile-header-account';
        mobileAccount.setAttribute('aria-label', 'Mon compte');
        mobileAccount.innerHTML = `
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                <circle cx="12" cy="7" r="4" />
            </svg>
        `;
        navToggle.parentNode.insertBefore(mobileAccount, navToggle);
    }

    // Inject mobile direct cart button if not present
    if (navToggle && navPanier && !document.getElementById('mobile-header-cart')) {
        const mobileCart = document.createElement('a');
        mobileCart.href = navPanier.getAttribute('href') || '/panier/';
        mobileCart.id = 'mobile-header-cart';
        mobileCart.className = 'mobile-header-cart';
        mobileCart.setAttribute('aria-label', 'Panier');
        
        const currentCount = cart.reduce((sum, item) => sum + item.quantity, 0);
        mobileCart.innerHTML = `
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="9" cy="21" r="1" />
                <circle cx="20" cy="21" r="1" />
                <path d="m1 1 4 0 2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6" />
            </svg>
            <span class="cart-count mobile-cart-badge" id="mobile-cart-count">${currentCount}</span>
        `;
        navToggle.parentNode.insertBefore(mobileCart, navToggle);
    }

    updateNavAccount();
    
    // Scroll effect
    window.addEventListener('scroll', () => {
        if (window.scrollY > 50) {
            navbar.classList.add('scrolled');
        } else {
            navbar.classList.remove('scrolled');
        }
    }, { passive: true });
    
    // Mobile toggle
    if (navToggle && navLinks) {
        navToggle.addEventListener('click', (e) => {
            e.stopPropagation();
            const isOpen = navLinks.classList.toggle('active');
            navToggle.classList.toggle('active', isOpen);
            navToggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
            document.body.classList.toggle('menu-open', isOpen);
        });
        
        // Close on link click
        navLinks.querySelectorAll('.nav-link').forEach(link => {
            link.addEventListener('click', () => {
                navToggle.classList.remove('active');
                navToggle.setAttribute('aria-expanded', 'false');
                navLinks.classList.remove('active');
                document.body.classList.remove('menu-open');
            });
        });

        // Close when clicking outside navbar
        document.addEventListener('click', (e) => {
            if (navLinks.classList.contains('active') && !navbar.contains(e.target)) {
                navToggle.classList.remove('active');
                navToggle.setAttribute('aria-expanded', 'false');
                navLinks.classList.remove('active');
                document.body.classList.remove('menu-open');
            }
        });

        // Close on Escape key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && navLinks.classList.contains('active')) {
                navToggle.classList.remove('active');
                navToggle.setAttribute('aria-expanded', 'false');
                navLinks.classList.remove('active');
                document.body.classList.remove('menu-open');
            }
        });
    }
}

// ==========================================
// SCROLL REVEAL ANIMATIONS
// ==========================================
function initScrollReveal() {
    const revealElements = document.querySelectorAll(
        '.category-card, .product-card, .product-card-full, .benefit-card, .value-card, .timeline-item'
    );
    
    revealElements.forEach(el => el.classList.add('reveal'));
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry, index) => {
            if (entry.isIntersecting) {
                setTimeout(() => {
                    entry.target.classList.add('visible');
                }, index * 80);
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.1, rootMargin: '0px 0px -50px 0px' });
    
    revealElements.forEach(el => observer.observe(el));
}

// ==========================================
// MODAL CLOSE ON OUTSIDE CLICK
// ==========================================
function initModalClose() {
    document.addEventListener('click', (e) => {
        if (e.target.classList.contains('modal-overlay')) {
            closeModal();
            // Also close checkout modal
            const checkoutModal = document.getElementById('checkout-modal');
            if (checkoutModal) checkoutModal.style.display = 'none';
        }
    });
    
    // ESC key
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            closeModal();
            const checkoutModal = document.getElementById('checkout-modal');
            if (checkoutModal) checkoutModal.style.display = 'none';
        }
    });
}

// ==========================================
// SWIPER SLIDER
// ==========================================
function initSwiper() {
    if (typeof Swiper !== 'undefined' && document.querySelector('.mySwiper')) {
        new Swiper('.mySwiper', {
            loop: true,
            autoplay: {
                delay: 5000,
                disableOnInteraction: false,
            },
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
        });
    }

    if (typeof Swiper !== 'undefined' && document.querySelector('.conseil-swiper')) {
        new Swiper('.conseil-swiper', {
            loop: true,
            autoplay: {
                delay: 6000,
                disableOnInteraction: false,
            },
            navigation: {
                nextEl: '.swiper-button-next-conseil',
                prevEl: '.swiper-button-prev-conseil',
            },
        });
    }

    if (typeof Swiper !== 'undefined' && document.querySelector('.productSwiper')) {
        new Swiper('.productSwiper', {
            loop: true,
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
        });
    }
}

// ==========================================
// STÉRIMAR™ AI HEALTH & PRODUCT ASSISTANT
// ==========================================
// ==========================================
// STÉRIMAR™ AI HEALTH & PRODUCT ASSISTANT
// ==========================================
const aiChatState = {
    lastProductId: null,
    patientProfile: null,
    lastSymptom: null,
    turnCount: 0
};

const productPageUrls = {
    0: '/produit-hygiene-du-nez/',
    1: '/produit-nez-sujet-aux-rhumes/',
    2: '/produit-nez-bouche/',
    3: '/produit-nez-allergique/',
    4: '/produit-hygiene-du-nez-bebe/',
    5: '/produit-nez-bouche-bebe/',
    6: '/boutique/#rhume'
};

function setAIQuickPrompts(prompts) {
    const container = document.querySelector('.ai-quick-prompts');
    if (!container) return;
    container.innerHTML = '';
    prompts.forEach(p => {
        const chip = document.createElement('button');
        chip.type = 'button';
        chip.className = 'ai-prompt-chip';
        chip.textContent = p.label;
        chip.dataset.prompt = p.prompt || p.label;
        chip.addEventListener('click', () => {
            handleUserMessage(chip.dataset.prompt);
        });
        container.appendChild(chip);
    });
}

window.handleAICartAdd = function(productId) {
    if (typeof addToCart === 'function') {
        addToCart(productId, 1);
    }
    const btn = document.getElementById(`ai-add-btn-${productId}`);
    if (btn) {
        btn.classList.add('added');
        btn.innerHTML = '✓ Ajouté au panier !';
    }
    const msgs = document.getElementById('ai-messages');
    if (msgs) {
        const p = products[productId];
        const confirmMsg = document.createElement('div');
        confirmMsg.className = 'ai-msg bot';
        confirmMsg.innerHTML = `✅ <strong>${p.name}</strong> a bien été ajouté à votre panier (16,00 DT) !<br><br><a href="panier.html" class="ai-product-view-btn" style="display:inline-flex; margin-top:4px;">👉 Voir mon panier & Commander</a>`;
        msgs.appendChild(confirmMsg);
        msgs.scrollTop = msgs.scrollHeight;
    }
    setAIQuickPrompts([
        { label: "🛍️ Voir mon panier", prompt: "Voir mon panier" },
        { label: "🚚 Délais de livraison", prompt: "Quels sont les délais de livraison ?" },
        { label: "💡 Mode d'emploi", prompt: "Comment bien utiliser ce produit ?" }
    ]);
};

function initAIAssistant() {
    // Remove conflicting external plugin chatbot if present in DOM
    const extBot = document.getElementById('wp-ai-chatbot');
    if (extBot) extBot.remove();
    document.querySelectorAll('.raplsaich-chatbot, .raplsaich-launcher, .raplsaich-chat-widget, [class*="raplsaich"]').forEach(el => el.remove());

    if (document.getElementById('sterimar-ai-launcher')) return;

    const launcher = document.createElement('div');
    launcher.id = 'sterimar-ai-launcher';
    launcher.className = 'sterimar-ai-launcher';
    launcher.innerHTML = `
        <div class="ai-launcher-badge" id="ai-launcher-badge">
            <span>💬 Conseiller Stérimar IA</span>
        </div>
        <button class="ai-launcher-btn" id="ai-launcher-btn" aria-label="Ouvrir le Conseiller IA">
            <span class="ai-pulse"></span>
            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M12 2a8 8 0 0 0-8 8c0 2.4 1 4.5 2.7 6l-.7 4 4.3-1.4A7.9 7.9 0 0 0 12 18a8 8 0 0 0 8-8 8 8 0 0 0-8-8z"/>
                <path d="M8 10h.01M12 10h.01M16 10h.01"/>
            </svg>
        </button>
    `;

    const modal = document.createElement('div');
    modal.id = 'sterimar-ai-modal';
    modal.className = 'sterimar-ai-modal';
    modal.innerHTML = `
        <div class="ai-header">
            <div class="ai-header-info">
                <div class="ai-avatar">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2">
                        <circle cx="12" cy="12" r="10"/>
                        <path d="M8 14s1.5 2 4 2 4-2 4-2"/>
                        <line x1="9" y1="9" x2="9.01" y2="9"/>
                        <line x1="15" y1="9" x2="15.01" y2="9"/>
                    </svg>
                </div>
                <div>
                    <div class="ai-header-title">Conseiller Stérimar™ IA</div>
                    <div class="ai-header-status"><span class="ai-status-dot"></span> En ligne • Expert ORL & Soins</div>
                </div>
            </div>
            <div class="ai-header-actions">
                <button class="ai-header-btn" id="ai-reset-btn" title="Réinitialiser la conversation">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 12a9 9 0 0 1 9-9 9.75 9.75 0 0 1 6.74 2.74L21 8"/><path d="M21 3v5h-5"/><path d="M21 12a9 9 0 0 1-9 9 9.75 9.75 0 0 1-6.74-2.74L3 16"/><path d="M8 16H3v5"/></svg>
                </button>
                <button class="ai-header-btn" id="ai-close-btn" title="Fermer">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                </button>
            </div>
        </div>
        <div class="ai-messages" id="ai-messages">
            <div class="ai-msg bot">
                👋 <strong>Bonjour et bienvenue chez Stérimar™ Tunisie !</strong><br>
                Je suis votre <strong>Conseiller IA santé ORL</strong>. Décrivez-moi vos symptômes ou vos besoins pour recevoir une recommandation clinique sur-mesure.
            </div>
        </div>
        <div class="ai-quick-prompts">
            <button type="button" class="ai-prompt-chip" data-prompt="Quel produit pour un nez bouché ?">🤧 Nez bouché</button>
            <button type="button" class="ai-prompt-chip" data-prompt="Quel spray choisir pour mon bébé ?">👶 Soin Bébé</button>
            <button type="button" class="ai-prompt-chip" data-prompt="J'ai une allergie au pollen">🌸 Allergies</button>
            <button type="button" class="ai-prompt-chip" data-prompt="Quels sont les tarifs et délais de livraison ?">🚚 Livraison</button>
        </div>
        <form class="ai-input-form" id="ai-input-form">
            <input type="text" class="ai-input-field" id="ai-input-text" placeholder="Posez votre question (français, derja...)" autocomplete="off">
            <button type="button" class="ai-mic-btn" id="ai-mic-btn" title="Parler au micro" aria-label="Microphone">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M12 1a3 3 0 0 0-3 3v8a3 3 0 0 0 6 0V4a3 3 0 0 0-3-3z"/>
                    <path d="M19 10v2a7 7 0 0 1-14 0v-2"/>
                    <line x1="12" y1="19" x2="12" y2="23"/>
                    <line x1="8" y1="23" x2="16" y2="23"/>
                </svg>
            </button>
            <button type="submit" class="ai-send-btn" aria-label="Envoyer">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
            </button>
        </form>
    `;

    document.body.appendChild(launcher);
    document.body.appendChild(modal);

    const openChat = () => {
        modal.classList.add('active');
        const badge = document.getElementById('ai-launcher-badge');
        if (badge) badge.style.display = 'none';
        const input = document.getElementById('ai-input-text');
        if (input) input.focus();
    };

    const closeChat = () => {
        modal.classList.remove('active');
    };

    document.getElementById('ai-launcher-btn').addEventListener('click', () => {
        if (modal.classList.contains('active')) {
            closeChat();
        } else {
            openChat();
        }
    });

    const launcherBadge = document.getElementById('ai-launcher-badge');
    if (launcherBadge) launcherBadge.addEventListener('click', openChat);
    
    document.getElementById('ai-close-btn').addEventListener('click', closeChat);

    document.getElementById('ai-reset-btn').addEventListener('click', () => {
        aiChatState.lastProductId = null;
        aiChatState.patientProfile = null;
        aiChatState.lastSymptom = null;
        aiChatState.turnCount = 0;
        const msgs = document.getElementById('ai-messages');
        msgs.innerHTML = `
            <div class="ai-msg bot">
                👋 <strong>Bonjour et bienvenue chez Stérimar™ Tunisie !</strong><br>
                Je suis votre conseiller IA. Décrivez-moi vos symptômes (nez bouché, allergie, soin bébé...) ou vos questions sur nos produits et la livraison.
            </div>
        `;
        setAIQuickPrompts([
            { label: "🤧 Nez bouché", prompt: "Quel produit pour un nez bouché ?" },
            { label: "👶 Soin Bébé", prompt: "Quel spray choisir pour mon bébé ?" },
            { label: "🌸 Allergies", prompt: "J'ai une allergie au pollen" },
            { label: "🚚 Livraison", prompt: "Quels sont les tarifs et délais de livraison ?" }
        ]);
    });

    document.querySelectorAll('.ai-prompt-chip').forEach(chip => {
        chip.addEventListener('click', () => {
            const prompt = chip.dataset.prompt;
            handleUserMessage(prompt);
        });
    });

    // Voice recognition (Web Speech API)
    const micBtn = document.getElementById('ai-mic-btn');
    if (micBtn) {
        const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
        if (SpeechRecognition) {
            const recognition = new SpeechRecognition();
            recognition.lang = 'fr-FR';
            recognition.interimResults = false;
            recognition.maxAlternatives = 1;
            let isListening = false;

            micBtn.addEventListener('click', () => {
                if (!isListening) {
                    try {
                        recognition.start();
                        micBtn.classList.add('listening');
                        isListening = true;
                        if (typeof showToast === 'function') showToast('🎙️ Parlez maintenant...');
                    } catch (e) {
                        console.error('Speech recognition start error:', e);
                    }
                } else {
                    recognition.stop();
                    micBtn.classList.remove('listening');
                    isListening = false;
                }
            });

            recognition.onresult = (event) => {
                const transcript = event.results[0][0].transcript;
                const input = document.getElementById('ai-input-text');
                if (input) input.value = transcript;
                micBtn.classList.remove('listening');
                isListening = false;
                if (transcript.trim()) {
                    handleUserMessage(transcript.trim());
                    if (input) input.value = '';
                }
            };

            recognition.onerror = () => {
                micBtn.classList.remove('listening');
                isListening = false;
            };

            recognition.onend = () => {
                micBtn.classList.remove('listening');
                isListening = false;
            };
        } else {
            micBtn.style.display = 'none';
        }
    }

    document.getElementById('ai-input-form').addEventListener('submit', (e) => {
        e.preventDefault();
        const input = document.getElementById('ai-input-text');
        const text = input.value.trim();
        if (text) {
            handleUserMessage(text);
            input.value = '';
        }
    });
}

function handleUserMessage(userText) {
    const msgs = document.getElementById('ai-messages');
    if (!msgs) return;

    aiChatState.turnCount++;

    // Append user message
    const userMsgEl = document.createElement('div');
    userMsgEl.className = 'ai-msg user';
    userMsgEl.textContent = userText;
    msgs.appendChild(userMsgEl);
    msgs.scrollTop = msgs.scrollHeight;

    // Animated Typing indicator with jumping dots
    const typingEl = document.createElement('div');
    typingEl.className = 'ai-typing-indicator';
    typingEl.id = 'ai-typing-indicator';
    typingEl.innerHTML = '<span class="ai-typing-dot"></span><span class="ai-typing-dot"></span><span class="ai-typing-dot"></span>';
    msgs.appendChild(typingEl);
    msgs.scrollTop = msgs.scrollHeight;

    // Natural human-like response delay (450ms - 750ms)
    const delay = Math.floor(Math.random() * 300) + 450;
    setTimeout(() => {
        const activeTyping = document.getElementById('ai-typing-indicator');
        if (activeTyping) activeTyping.remove();
        generateAIResponse(userText);
    }, delay);
}

function normalizeAIQuery(text) {
    return text
        .toLowerCase()
        .normalize('NFD').replace(/[\u0300-\u036f]/g, '')
        .replace(/[.,\/#!$%\^&\*;:{}=\-_`~()?'"’]/g, ' ')
        .replace(/\s+/g, ' ')
        .trim();
}

function hasAnyWord(str, list) {
    return list.some(w => str.includes(w));
}

function generateAIResponse(query) {
    const q = normalizeAIQuery(query);
    const msgs = document.getElementById('ai-messages');
    let responseText = "";
    let recommendedProductId = null;
    let nextPrompts = [];

    // 1. Direct Purchase / Add to Cart command
    if (hasAnyWord(q, ['acheter', 'commander', 'ajoute au panier', 'ajouter au panier', 'ajoute le', 'je le prends', 'je veux acheter', 'chri', 'nchri', 'nheb nechri'])) {
        if (aiChatState.lastProductId !== null && products[aiChatState.lastProductId]) {
            const p = products[aiChatState.lastProductId];
            if (typeof addToCart === 'function') {
                addToCart(p.id, 1);
            }
            const cartQty = (typeof cart !== 'undefined' && Array.isArray(cart)) ? cart.reduce((s, i) => s + i.quantity, 0) : 1;
            responseText = `🛒 <strong>C'est fait !</strong> J'ai ajouté <strong>${p.name}</strong> à votre panier (<strong>16,00 DT</strong>).<br><br>Vous avez actuellement <strong>${cartQty} article(s)</strong> dans votre panier.<br>📦 Rappel : La livraison est <strong>GRATUITE dès 100 DT d'achat</strong> (ou pack 4 sprays).<br><br><a href="panier.html" class="ai-product-view-btn" style="display:inline-flex; margin-top:4px;">👉 Voir mon panier & Finaliser ma commande</a>`;
            nextPrompts = [
                { label: "🛍️ Voir mon panier", prompt: "Voir mon panier" },
                { label: "🚚 Délais de livraison", prompt: "Quels sont les délais de livraison ?" },
                { label: "💳 Modes de paiement", prompt: "Quels sont les modes de paiement ?" },
                { label: "💡 Posologie", prompt: "Comment bien utiliser ce produit ?" }
            ];
        } else {
            responseText = "Quel spray Stérimar™ souhaitez-vous ajouter à votre panier ? Dites-moi vos symptômes ou choisissez directement parmi nos soins phares ci-dessous :";
            nextPrompts = [
                { label: "🤧 Nez Bouché (16 DT)", prompt: "Je veux Stérimar Nez Bouché" },
                { label: "👶 Soin Bébé (16 DT)", prompt: "Quel spray choisir pour mon bébé ?" },
                { label: "🌸 Nez Allergique (16 DT)", prompt: "Je veux Stérimar Nez Allergique" },
                { label: "🧴 Hygiène Quotidienne (16 DT)", prompt: "Je veux Stérimar Hygiène du Nez" }
            ];
        }
    }
    // 2. Cart status & checkout link
    else if (hasAnyWord(q, ['panier', 'mon panier', 'finaliser ma commande', 'regarder mon panier'])) {
        const cartQty = (typeof cart !== 'undefined' && Array.isArray(cart)) ? cart.reduce((s, i) => s + i.quantity, 0) : 0;
        responseText = `Vous avez actuellement <strong>${cartQty} article(s)</strong> dans votre panier.<br><br>Vous pouvez finaliser votre commande en toute sécurité avec paiement en espèces à la livraison :<br><br><a href="panier.html" class="ai-product-view-btn" style="display:inline-flex; margin-top:4px;">🛒 Accéder à mon panier (${cartQty})</a>`;
        nextPrompts = [
            { label: "🚚 Délais de livraison", prompt: "Quels sont les délais de livraison ?" },
            { label: "💳 Modes de paiement", prompt: "Quels sont les modes de paiement ?" },
            { label: "🛍️ Continuer mes achats", prompt: "Voir la boutique" }
        ];
    }
    // 3. Greetings & Small Talk (French, Derja, Arabic)
    else if (hasAnyWord(q, ['salut', 'bonjour', 'bonsoir', 'coucou', 'hello', 'hi', 'ahla', '3asslema', 'aslema', 'marhba', 'salem', 'salam', 'labes', 'qui es tu', 'qui est tu', 'chbik', 'aide', 'sos', 'chbih', 'winek'])) {
        const hour = new Date().getHours();
        const salutation = (hour >= 18 || hour < 5) ? 'Bonsoir' : 'Bonjour';
        responseText = `👋 <strong>${salutation} et bienvenue chez Stérimar™ Tunisie !</strong><br><br>Je suis votre <strong>Conseiller IA Expert ORL</strong>. Je suis formé pour :<br>• Vous guider vers le spray adapté selon vos symptômes et votre âge<br>• Répondre à vos questions posologie, grossesse et soins pédiatriques<br>• Vous informer sur les tarifs et la livraison partout en Tunisie.<br><br>💬 <em>Quel symptôme ressentez-vous actuellement ?</em>`;
        nextPrompts = [
            { label: "🤧 Nez bouché", prompt: "Quel produit pour un nez bouché ?" },
            { label: "👶 Soin Bébé", prompt: "Quel spray choisir pour mon bébé ?" },
            { label: "🌸 Allergies", prompt: "J'ai une allergie au pollen" },
            { label: "🚚 Délais & Prix", prompt: "Quels sont les tarifs et délais de livraison ?" }
        ];
    }
    // 4. Baby & Newborn Care (Crucial Pediatric Logic)
    else if (hasAnyWord(q, ['bebe', 'nourrisson', 'naissance', 'nouveau ne', 'sghir', 'wildy', 'benti', 'mouchage', 'pediatre', '0 mois', '1 mois', '2 mois', '3 mois', 'maternite'])) {
        aiChatState.patientProfile = 'baby';
        
        // Newborn < 3 months specifically
        if (hasAnyWord(q, ['0 mois', '1 mois', '2 mois', 'naissance', 'nouveau ne', 'tout petit', 'nouveau-ne', 'premiers mois'])) {
            responseText = `👶 <strong>Pour un nourrisson de moins de 3 mois :</strong><br><br>Seule une solution isotonique physiologique est autorisée. Nous vous conseillons exclusivement <strong>Stérimar™ Hygiène du Nez Bébé</strong> (dès la naissance).<br><br>⚠️ <em>Règle de sécurité pédiatrique :</em> Les sprays hypertoniques décongestionnants forts sont strictement réservés aux bébés de <strong>plus de 3 mois</strong>.<br><br>Son embout exclusif avec collerette de sécurité protège les petites narines délicates de bébé.`;
            recommendedProductId = 4;
            nextPrompts = [
                { label: "🛒 Ajouter Hygiène Bébé", prompt: "Ajouter Hygiène Bébé au panier" },
                { label: "💡 Comment moucher bébé ?", prompt: "Comment bien moucher un bébé ?" },
                { label: "🚚 Délais de livraison", prompt: "Quels sont les délais de livraison ?" }
            ];
        }
        // Baby 3 months+ with blocked / stuffy nose
        else if (hasAnyWord(q, ['bouche', 'bloque', 'congestion', 'mablou3', 'masdoud', 'enrhume', 'rhume', 'greb'])) {
            responseText = `👶 <strong>Pour un bébé dès 3 mois au nez bouché ou encombré :</strong><br><br>Nous vous recommandons <strong>Stérimar™ Nez Bouché Bébé</strong> (Solution Hypertonique enrichie en Cuivre).<br><br>✨ <strong>Action douce et naturelle :</strong><br>• Décongestionne rapidement par effet osmotique naturel sans vasoconstricteur chimique<br>• Le Cuivre aide à limiter la prolifération bactérienne<br>• Embout sécurité ergonomique adapté aux narines de bébé<br>• Recommandé avant les repas et le sommeil pour aider bébé à mieux respirer et téter.`;
            recommendedProductId = 5;
            nextPrompts = [
                { label: "🛒 Ajouter au panier (16 DT)", prompt: "Ajouter au panier" },
                { label: "💡 Comment moucher bébé ?", prompt: "Comment bien moucher un bébé ?" },
                { label: "🧴 Voir Hygiène Quotidienne", prompt: "Quel spray pour l'hygiène quotidienne de bébé ?" }
            ];
        }
        // General baby hygiene
        else {
            responseText = `👶 <strong>Pour l'hygiène quotidienne de bébé (dès la naissance) :</strong><br><br><strong>Stérimar™ Hygiène du Nez Bébé</strong> est une formule physiologique d'eau de mer puisée en Baie de Cancale.<br><br>Il lave en douceur, humidifie les cavités nasales et prévient les affections ORL (rhumes, rhinopharyngites, otites). Son jet micro-diffusé respecte la fragilité des nouveau-nés.`;
            recommendedProductId = 4;
            nextPrompts = [
                { label: "🛒 Ajouter au panier", prompt: "Ajouter au panier" },
                { label: "💡 Comment l'utiliser ?", prompt: "Comment bien moucher un bébé ?" },
                { label: "🤧 S'il a le nez bouché ?", prompt: "Quel produit si mon bébé a le nez bouché ?" }
            ];
        }
    }
    // 5. Allergies, Pollen, Rhinite, Dust Mites
    else if (hasAnyWord(q, ['allergie', 'allergique', 'pollen', 'acarien', 'poussiere', 'eternu', 'yeux', 'demangeaison', 'printemps', 'hasassiya', '3tass', 'rhinite'])) {
        responseText = `🌸 <strong>Pour soulager et prévenir les allergies nasales :</strong><br><br>Le soin d'excellence est <strong>Stérimar™ Nez Allergique</strong>, enrichi en <strong>Manganèse</strong> (Élu Meilleur Produit Pharma).<br><br>🛡️ <strong>Efficacité cliniquement reconnue :</strong><br>• Élimine mécaniquement les allergènes au contact de la muqueuse (pollens, poussières, acariens, poils)<br>• Le Manganèse freine la libération d'histamine et apaise la muqueuse irritée<br>• <strong>100% naturel, sans corticoïdes, sans somnolence et sans accoutumance</strong><br>• Recommandé pour l'adulte et l'enfant dès 3 ans tout au long de la saison des allergies.`;
        recommendedProductId = 3;
        nextPrompts = [
            { label: "🛒 Ajouter au panier (16 DT)", prompt: "Ajouter au panier" },
            { label: "💡 Posologie allergie", prompt: "Quelle est la posologie pour Stérimar Nez Allergique ?" },
            { label: "🤰 Compatible grossesse ?", prompt: "Puis-je l'utiliser pendant la grossesse ?" },
            { label: "🚚 Délais de livraison", prompt: "Quels sont les délais de livraison ?" }
        ];
    }
    // 6. Blocked Nose / Congestion / Sinusitis (Adult & Child 3+)
    else if (hasAnyWord(q, ['bouche', 'debouch', 'bloque', 'congestion', 'sinusite', 'respirer', 'ronfle', 'etouffe', 'khachmi mablou3', 'masdoud', 'mzarra9'])) {
        responseText = `🤧 <strong>Pour déboucher rapidement le nez (dès 3 ans et adulte) :</strong><br><br>Le soin incontournable est <strong>Stérimar™ Nez Bouché (Hypertonique)</strong>, enrichi en <strong>Cuivre</strong>.<br><br>🌊 <strong>Comment agit-il ?</strong><br>• Sa concentration en sel de mer plus élevée (~22 g/L) crée un <strong>effet d'osmose naturelle</strong> qui dégonfle la muqueuse nasale en quelques minutes, avec une efficacité constatée jusqu'à <strong>6 heures</strong>.<br>• Le Cuivre antibactérien aide à lutter contre les infections hivernales.<br>• <strong>Sans vasoconstricteur chimique :</strong> aucun effet rebond ni dépendance !`;
        recommendedProductId = 2;
        nextPrompts = [
            { label: "🛒 Ajouter au panier (16 DT)", prompt: "Ajouter au panier" },
            { label: "💡 Combien de fois par jour ?", prompt: "Combien de fois par jour utiliser Stérimar Nez Bouché ?" },
            { label: "👶 Et pour mon bébé ?", prompt: "Quel spray pour un bébé au nez bouché ?" },
            { label: "🚚 Commander en ligne", prompt: "Quels sont les tarifs et délais de livraison ?" }
        ];
    }
    // 7. Cold / Winter Immunity / Early Symptoms
    else if (hasAnyWord(q, ['rhume', 'hiver', 'froid', 'gorge', 'prevenir', 'prevention', 'protection', 'greb', 'grippe', 'chmouma', 'berd', 'immunite'])) {
        responseText = `❄️ <strong>Face au rhume et aux agressions de l'hiver :</strong><br><br>1️⃣ <strong>En prévention & dès les premiers frissons :</strong> <strong>Stérimar™ Nez sujet aux Rhumes</strong>, enrichi en <strong>Soufre</strong> naturel, régénère la muqueuse et renforce vos défenses immunitaires locales.<br><br>2️⃣ <strong>Pour stopper le rhume déclaré :</strong> <strong>Stérimar™ Stop & Protect Rhume</strong> (format nomade 20ml) forme un film protecteur actif qui bloque la prolifération virale.`;
        recommendedProductId = 1;
        nextPrompts = [
            { label: "🛒 Ajouter Nez sujet aux Rhumes", prompt: "Ajouter Nez sujet aux Rhumes au panier" },
            { label: "🤧 Mon nez est très bouché", prompt: "J'ai le nez très bouché" },
            { label: "💡 Mode d'emploi", prompt: "Comment bien utiliser Stérimar ?" }
        ];
    }
    // 8. Daily Hygiene & Dry Mucosa / Air Conditioning
    else if (hasAnyWord(q, ['hygiene', 'lavage', 'nettoyer', 'quotidien', 'tous les jours', 'secheresse', 'sec', 'croutes', 'climatisation', 'pollution', 'croûtes'])) {
        responseText = `🌊 <strong>Hygiène nasale quotidienne & Bien-être respiratoire :</strong><br><br><strong>Stérimar™ Hygiène du Nez</strong> est la formule originale à base de 100% d'eau de mer isotonique puisée en Baie de Cancale.<br><br>✨ <strong>Bénéfices au quotidien :</strong><br>• Lave les cavités nasales en douceur et évacue poussières et impuretés<br>• Réhydrate la muqueuse asséchée par la climatisation ou le chauffage<br>• Prévient les infections ORL et améliore la qualité du sommeil<br>• Utilisable quotidiennement sans limitation (adulte et enfant dès 3 ans).`;
        recommendedProductId = 0;
        nextPrompts = [
            { label: "🛒 Ajouter au panier (16 DT)", prompt: "Ajouter au panier" },
            { label: "💡 Conseils d'utilisation", prompt: "Comment bien utiliser Stérimar ?" },
            { label: "👶 Version Bébé disponible ?", prompt: "Quel spray choisir pour mon bébé ?" }
        ];
    }
    // 9. Pregnancy & Breastfeeding Safety
    else if (hasAnyWord(q, ['enceinte', 'grossesse', 'allaitement', 'allaite', 'nourrice', 'bebe dans le ventre', 'femme enceinte', 'grosses'])) {
        responseText = `🤰 <strong>100% sûr et sans danger pendant la grossesse et l'allaitement !</strong><br><br>Tous les sprays Stérimar™ sont composés exclusivement d'<strong>eau de mer 100% naturelle</strong> et d'oligo-éléments marins.<br><br>✅ <strong>Sans corticoïdes</strong><br>✅ <strong>Sans conservateurs chimiques</strong><br>✅ <strong>Sans vasoconstricteurs</strong> (aucun risque d'hypertension ni d'impact fœtal)<br><br>👉 Idéal pour soulager naturellement la <strong>rhinite de grossesse</strong> et respirer librement sans prendre de médicaments.`;
        recommendedProductId = 0;
        nextPrompts = [
            { label: "🧴 Hygiène Quotidienne", prompt: "Je veux Stérimar Hygiène du Nez" },
            { label: "🤧 Nez Bouché décongestion", prompt: "Quel produit pour un nez bouché ?" },
            { label: "🌸 Allergies de grossesse", prompt: "J'ai une allergie au pollen" }
        ];
    }
    // 10. Difference Isotonic vs Hypertonic
    else if (hasAnyWord(q, ['isotonique', 'hypertonique', 'difference', 'sel', 'salinite', 'osmose', 'concentration'])) {
        responseText = `🔬 <strong>Différence entre Solution Isotonique et Hypertonique :</strong><br><br>💧 <strong>Solution Isotonique (ex : Stérimar Hygiène) :</strong><br>• Concentration en sel marin identique à celle de nos cellules (<strong>9 g/L</strong>).<br>• Rôle : Laver, humidifier et protéger sans agresser. Utilisable <strong>tous les jours sans limite</strong>.<br><br>⚡ <strong>Solution Hypertonique (ex : Stérimar Nez Bouché) :</strong><br>• Concentration en sel plus élevée (~<strong>22 g/L</strong>).<br>• Rôle : Par phénomène d'<strong>osmose</strong>, elle attire l'excès d'eau hors de la muqueuse enflée pour déboucher le nez en quelques minutes. Recommandée lors des périodes de congestion (5 à 7 jours).`;
        nextPrompts = [
            { label: "🤧 Voir Stérimar Nez Bouché", prompt: "Quel produit pour un nez bouché ?" },
            { label: "🧴 Voir Stérimar Hygiène", prompt: "Je veux Stérimar Hygiène du Nez" },
            { label: "👶 Et pour mon bébé ?", prompt: "Quel spray choisir pour mon bébé ?" }
        ];
    }
    // 11. Dosage & How to Use (Adult & Baby)
    else if (hasAnyWord(q, ['posologie', 'comment utiliser', 'mode d emploi', 'combien de fois', 'frequence', 'kifech nestamel', 'tuto', 'utilisation'])) {
        responseText = `💡 <strong>Guide d'utilisation Stérimar™ :</strong><br><br>👤 <strong>Adulte & Enfant (dès 3 ans) :</strong><br>1. Mouchez-vous au préalable.<br>2. Inclinez la tête sur le côté au-dessus du lavabo (jamais en arrière).<br>3. Insérez délicatement l'embout dans la narine supérieure et effectuez 1 à 2 pulvérisations.<br>4. Laissez agir quelques secondes, puis mouchez-vous.<br>5. Répétez pour l'autre narine.<br><br>👶 <strong>Pour Bébé (0 à 3 ans) :</strong><br>Allongez bébé sur le dos, tournez sa tête sur le côté et pulvérisez doucement avec l'embout sécurité. Redressez-le et essuyez son nez.<br><br>🚿 <em>Rincez l'embout à l'eau chaude savonneuse après chaque utilisation.</em>`;
        nextPrompts = [
            { label: "🤧 Quel produit choisir ?", prompt: "Quel produit pour un nez bouché ?" },
            { label: "👶 Soin Bébé", prompt: "Quel spray choisir pour mon bébé ?" },
            { label: "🛍️ Voir la boutique", prompt: "Voir la boutique" }
        ];
    }
    // 12. Price, Packs & Discounts in Tunisia
    else if (hasAnyWord(q, ['prix', 'tarif', 'combien', 'cout', 'b9adech', '9adeh', 'soum', 'soumou', 'chhal', 'promo', 'remise', 'reduction', 'pack', 'dinar', 'dt'])) {
        responseText = `💰 <strong>Tarifs officiels Stérimar™ Tunisie :</strong><br><br>• Tous nos sprays 100ml et Stop & Protect sont au prix unique de <strong>16,00 DT</strong>.<br>• <strong>🎉 OFFRE SPÉCIALE :</strong> La livraison est <strong>100% GRATUITE dès 100 DT d'achat</strong> (ou pour l'achat de notre pack famille 4 sprays) !<br>• Frais de port standard : 7,00 DT sur toute la Tunisie pour les commandes inférieures à 100 DT.<br>• Paiement sécurisé en espèces à la livraison.`;
        nextPrompts = [
            { label: "🛍️ Commander en boutique", prompt: "Voir la boutique" },
            { label: "🚚 Délais de livraison", prompt: "Quels sont les délais de livraison ?" },
            { label: "💳 Modes de paiement", prompt: "Quels sont les modes de paiement ?" }
        ];
    }
    // 13. Delivery & Cities in Tunisia
    else if (hasAnyWord(q, ['livraison', 'delai', 'temps', 'towslo', 'livreur', 'tunis', 'sousse', 'sfax', 'nabeul', 'bizerte', 'kairouan', 'monastir', 'gabes', 'djerba', 'ariana', 'ben arous', 'manouba', 'partout', 'livrez'])) {
        responseText = `🚚 <strong>Livraison express partout en Tunisie :</strong><br><br>• <strong>Délais :</strong> 24h à 48h ouvrées chez vous.<br>• <strong>Couverture :</strong> Les 24 gouvernorats (Grand Tunis, Sousse, Sfax, Sahel, Nabeul, Bizerte, Kairouan, Sud, etc.).<br>• <strong>Frais :</strong> 7,00 DT, et <strong>GRATUITE dès 100 DT d'achat</strong>.<br>• Le livreur vous contacte systématiquement par téléphone avant son passage.`;
        nextPrompts = [
            { label: "🛒 Passer commande", prompt: "Voir la boutique" },
            { label: "💳 Paiement à la livraison", prompt: "Quels sont les modes de paiement ?" },
            { label: "📞 Contacter le service client", prompt: "Quel est le numéro de téléphone ?" }
        ];
    }
    // 14. Payment Methods
    else if (hasAnyWord(q, ['paiement', 'payer', 'espece', 'especes', 'carte', 'cash', 'flous', 'kifech nkhales'])) {
        responseText = `💳 <strong>Mode de paiement 100% sécurisé :</strong><br><br>Nous proposons le <strong>Paiement à la livraison (Cash on Delivery)</strong> :<br>• Aucun paiement par carte bancaire exigé sur le site.<br>• Vous ne réglez qu'au moment de recevoir votre colis entre les mains du transporteur !`;
        nextPrompts = [
            { label: "🛍️ Choisir mes produits", prompt: "Voir la boutique" },
            { label: "🚚 Délais de livraison", prompt: "Quels sont les délais de livraison ?" },
            { label: "📦 Voir mon panier", prompt: "Voir mon panier" }
        ];
    }
    // 15. Phone, Human Advisor & Contact
    else if (hasAnyWord(q, ['telephone', 'numero', 'contact', 'parler', 'humain', 'whatsapp', 'boutique physique', 'adresse', 'service client', 'magasin', 'appel'])) {
        responseText = `📞 <strong>Service Client Stérimar™ Tunisie :</strong><br><br>• <strong>Téléphone / WhatsApp :</strong> <a href="tel:+21629550043" style="color:#0077B6; font-weight:700;">+216 29 550 043</a><br>• <strong>E-mail :</strong> <a href="mailto:commercial@sterimar.shop" style="color:#0077B6; font-weight:700;">commercial@sterimar.shop</a><br>• <strong>Horaires :</strong> Du Lundi au Samedi de 8h30 à 18h00.<br><br>Notre équipe est à votre disposition pour vous conseiller ou enregistrer votre commande directement par téléphone !`;
        nextPrompts = [
            { label: "🛍️ Commander sur le site", prompt: "Voir la boutique" },
            { label: "🚚 Délais de livraison", prompt: "Quels sont les délais de livraison ?" },
            { label: "🤧 Nez bouché", prompt: "Quel produit pour un nez bouché ?" }
        ];
    }
    // 16. Red Flags / Medical Warning
    else if (hasAnyWord(q, ['fievre', 'temperature', 'otite', 'oreille', 'douleur', 'mal', '38', '39', 'saignement', 'saigne', 'sang', 'urgent'])) {
        responseText = `⚠️ <strong>Conseil de prudence médicale :</strong><br><br>Les soins Stérimar™ apportent un soulagement naturel, mais en présence de :<br>• Une fièvre supérieure à 38,5°C<br>• Une vive douleur à l'oreille (suspicion d'otite)<br>• Des sécrétions purulentes épaisses au-delà de 7 jours<br><br>👉 <strong>Consultez rapidement un médecin généraliste, un pédiatre ou un spécialiste ORL.</strong>`;
        nextPrompts = [
            { label: "🧴 Stérimar Hygiène doux", prompt: "Je veux Stérimar Hygiène du Nez" },
            { label: "👶 Soin Bébé", prompt: "Quel spray choisir pour mon bébé ?" },
            { label: "📞 Service client", prompt: "Quel est le numéro de téléphone ?" }
        ];
    }
    // 17. Thank you & Politeness (French / Derja)
    else if (hasAnyWord(q, ['merci', 'aychek', '3aychek', 'chokran', 'parfait', 'super', 'merci beaucoup', 'top', 'sa7a', 'يعيشك', 'شكرا'])) {
        responseText = `Avec grand plaisir ! 😊 Prenez bien soin de vous et de la respiration de votre famille 🌊<br><br>Stérimar™ reste à vos côtés. Souhaitez-vous voir nos produits ou finaliser une commande ?`;
        nextPrompts = [
            { label: "🛍️ Voir la boutique", prompt: "Voir la boutique" },
            { label: "🛒 Voir mon panier", prompt: "Voir mon panier" },
            { label: "🚚 Délais de livraison", prompt: "Quels sont les délais de livraison ?" }
        ];
    }
    // 18. Smart Fallback with interactive triage
    else {
        responseText = `Je suis à votre disposition pour trouver la solution Stérimar™ parfaitement adaptée à votre situation.<br><br>De quoi s'agit-il principalement ? Choisissez une option ci-dessous ou précisez votre demande :`;
        nextPrompts = [
            { label: "🤧 Nez bouché & Sinusite", prompt: "Quel produit pour un nez bouché ?" },
            { label: "👶 Bébé ou Nourrisson", prompt: "Quel spray choisir pour mon bébé ?" },
            { label: "🌸 Allergies de saison", prompt: "J'ai une allergie au pollen" },
            { label: "🧴 Hygiène quotidienne", prompt: "Je veux Stérimar Hygiène du Nez" },
            { label: "🚚 Livraison & Tarifs", prompt: "Quels sont les tarifs et délais de livraison ?" }
        ];
    }

    const botMsgEl = document.createElement('div');
    botMsgEl.className = 'ai-msg bot';
    botMsgEl.innerHTML = responseText;

    // Render interactive product card if recommended
    if (recommendedProductId !== null && products[recommendedProductId]) {
        const p = products[recommendedProductId];
        aiChatState.lastProductId = p.id;
        const pageUrl = productPageUrls[p.id] || 'boutique.html';
        const cardEl = document.createElement('div');
        cardEl.className = 'ai-product-card-msg';
        cardEl.innerHTML = `
            <div class="ai-product-card-head">
                <img src="${getAssetUrl(p.image)}" alt="${p.name}">
                <div style="flex: 1;">
                    <div class="ai-product-card-title">${p.name}</div>
                    <span class="ai-product-tag">${p.categoryLabel} • ${p.volume || '100ml'}</span>
                    <div class="ai-product-card-price" style="margin-top:2px;">${p.price.toFixed(2).replace('.', ',')} DT</div>
                </div>
            </div>
            <div class="ai-product-card-actions">
                <button type="button" class="ai-product-add-btn" id="ai-add-btn-${p.id}" onclick="handleAICartAdd(${p.id})">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m12 5v14m-7-7h14"/></svg>
                    Ajouter au panier (16,00 DT)
                </button>
                <a href="${pageUrl}" class="ai-product-view-btn">
                    Voir la fiche &gt;
                </a>
            </div>
        `;
        botMsgEl.appendChild(cardEl);
    }

    msgs.appendChild(botMsgEl);
    msgs.scrollTop = msgs.scrollHeight;

    // Update quick prompts dynamically for this context
    if (nextPrompts && nextPrompts.length > 0) {
        setAIQuickPrompts(nextPrompts);
    }
}

// ==========================================
// AUTHENTICATION & ESPACE CLIENT
// ==========================================
function updateNavAccount() {
    try {
        const storedUser = localStorage.getItem('sterimar_user');
        const navAccount = document.getElementById('nav-account');
        const navAccountLabel = document.getElementById('nav-account-label');
        if (storedUser) {
            const user = JSON.parse(storedUser);
            if (navAccountLabel) {
                navAccountLabel.textContent = user.firstName || 'Mon Compte';
            }
            if (navAccount) {
                navAccount.title = `Bonjour ${user.firstName || ''} (${user.email || ''})`;
            }
        } else {
            if (navAccountLabel) {
                navAccountLabel.textContent = 'Compte';
            }
            if (navAccount) {
                navAccount.title = 'Mon compte';
            }
        }
    } catch (e) {
        console.error('Error updating nav account:', e);
    }
}

function switchAuthTab(tab) {
    const tabRegister = document.getElementById('tab-btn-register');
    const tabLogin = document.getElementById('tab-btn-login');
    const panelRegister = document.getElementById('panel-register');
    const panelLogin = document.getElementById('panel-login');

    if (!tabRegister || !tabLogin || !panelRegister || !panelLogin) return;

    if (tab === 'register') {
        tabRegister.classList.add('active');
        tabRegister.setAttribute('aria-selected', 'true');
        tabLogin.classList.remove('active');
        tabLogin.setAttribute('aria-selected', 'false');

        panelRegister.classList.add('active');
        panelLogin.classList.remove('active');
    } else {
        tabLogin.classList.add('active');
        tabLogin.setAttribute('aria-selected', 'true');
        tabRegister.classList.remove('active');
        tabRegister.setAttribute('aria-selected', 'false');

        panelLogin.classList.add('active');
        panelRegister.classList.remove('active');
    }
}

function togglePasswordVisibility(inputId, btn) {
    const input = document.getElementById(inputId);
    if (!input) return;
    const isPassword = input.type === 'password';
    input.type = isPassword ? 'text' : 'password';
    
    // Toggle icon
    if (isPassword) {
        btn.innerHTML = `
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/>
                <line x1="1" y1="1" x2="23" y2="23"/>
            </svg>
        `;
        btn.setAttribute('aria-label', 'Masquer le mot de passe');
    } else {
        btn.innerHTML = `
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                <circle cx="12" cy="12" r="3"/>
            </svg>
        `;
        btn.setAttribute('aria-label', 'Afficher le mot de passe');
    }
}

function checkPasswordStrength(password) {
    const b1 = document.getElementById('meter-bar-1');
    const b2 = document.getElementById('meter-bar-2');
    const b3 = document.getElementById('meter-bar-3');
    const b4 = document.getElementById('meter-bar-4');
    const label = document.getElementById('meter-feedback-label');

    if (!b1 || !b2 || !b3 || !b4 || !label) return;

    if (!password) {
        [b1, b2, b3, b4].forEach(b => b.style.backgroundColor = 'var(--gray-200)');
        label.textContent = 'Non renseigné';
        label.style.color = 'var(--gray-400)';
        return;
    }

    let score = 0;
    if (password.length >= 8) score++;
    if (/[A-Z]/.test(password)) score++;
    if (/[0-9]/.test(password)) score++;
    if (/[^A-Za-z0-9]/.test(password)) score++;

    [b1, b2, b3, b4].forEach(b => b.style.backgroundColor = 'var(--gray-200)');

    if (score === 1) {
        b1.style.backgroundColor = '#FE3200';
        label.textContent = 'Faible (ajoutez majuscules ou chiffres)';
        label.style.color = '#FE3200';
    } else if (score === 2) {
        b1.style.backgroundColor = '#F15B2B';
        b2.style.backgroundColor = '#F15B2B';
        label.textContent = 'Moyen';
        label.style.color = '#F15B2B';
    } else if (score === 3) {
        b1.style.backgroundColor = '#FFB703';
        b2.style.backgroundColor = '#FFB703';
        b3.style.backgroundColor = '#FFB703';
        label.textContent = 'Bon (sécurisé)';
        label.style.color = '#0077B6';
    } else {
        [b1, b2, b3, b4].forEach(b => b.style.backgroundColor = '#4CAF50');
        label.textContent = 'Excellent ! 🔒';
        label.style.color = '#4CAF50';
    }
}

function handleRegisterSubmit(e) {
    e.preventDefault();
    const civility = document.querySelector('input[name="reg-civility"]:checked')?.value || 'Mme';
    const firstName = document.getElementById('reg-firstname')?.value.trim();
    const lastName = document.getElementById('reg-lastname')?.value.trim();
    const email = document.getElementById('reg-email')?.value.trim();
    const phone = document.getElementById('reg-phone')?.value.trim();
    const password = document.getElementById('reg-password')?.value;
    const confirmPassword = document.getElementById('reg-confirm-password')?.value;

    if (!firstName || !lastName || !email || !password) {
        showAuthToast('Erreur', 'Veuillez remplir tous les champs obligatoires.', 'error');
        return;
    }

    if (password.length < 6) {
        showAuthToast('Mot de passe trop court', 'Le mot de passe doit comporter au moins 6 caractères.', 'error');
        return;
    }

    if (password !== confirmPassword) {
        showAuthToast('Erreur de mot de passe', 'Les deux mots de passe ne correspondent pas.', 'error');
        return;
    }

    const userData = {
        civility,
        firstName,
        lastName,
        email,
        phone,
        newsletter: document.getElementById('reg-newsletter')?.checked ?? true,
        createdAt: new Date().toISOString()
    };

    localStorage.setItem('sterimar_user', JSON.stringify(userData));
    showAuthToast('Compte créé ! 🎉', `Bienvenue ${firstName} dans votre Espace Client Stérimar™.`, 'success');
    
    updateNavAccount();
    renderAuthView();
}

function handleLoginSubmit(e) {
    e.preventDefault();
    const email = document.getElementById('login-email')?.value.trim();
    const password = document.getElementById('login-password')?.value;

    if (!email || !password) {
        showAuthToast('Erreur', 'Veuillez saisir votre e-mail et votre mot de passe.', 'error');
        return;
    }

    // Check if user exists or simulate
    let userData = null;
    try {
        const stored = localStorage.getItem('sterimar_user');
        if (stored) {
            userData = JSON.parse(stored);
        }
    } catch (err) {}

    if (!userData || userData.email.toLowerCase() !== email.toLowerCase()) {
        const extractedName = email.split('@')[0].replace(/[._-]/g, ' ');
        const parts = extractedName.split(' ');
        const firstName = parts[0].charAt(0).toUpperCase() + parts[0].slice(1);
        const lastName = parts[1] ? (parts[1].charAt(0).toUpperCase() + parts[1].slice(1)) : 'Client';
        
        userData = {
            civility: 'Mme',
            firstName,
            lastName,
            email,
            phone: '+216 29 550 043',
            newsletter: true,
            createdAt: new Date().toISOString()
        };
        localStorage.setItem('sterimar_user', JSON.stringify(userData));
    }

    showAuthToast('Connexion réussie ! 🌊', `Ravi de vous revoir, ${userData.firstName}.`, 'success');
    updateNavAccount();
    renderAuthView();
}

function handleLogout() {
    localStorage.removeItem('sterimar_user');
    showAuthToast('Déconnexion', 'Vous avez été déconnecté avec succès.', 'success');
    updateNavAccount();
    renderAuthView();
}

function simulateSocialAuth(provider) {
    const defaultUser = {
        civility: 'Mme',
        firstName: provider === 'Google' ? 'Sarah' : 'Myriam',
        lastName: provider === 'Google' ? 'Ben Salem' : 'Trabelsi',
        email: provider === 'Google' ? 'sarah.bensalem@gmail.com' : 'myriam.t@facebook.com',
        phone: '+216 29 550 043',
        newsletter: true,
        provider,
        createdAt: new Date().toISOString()
    };
    localStorage.setItem('sterimar_user', JSON.stringify(defaultUser));
    showAuthToast(`Connexion via ${provider} !`, `Bienvenue ${defaultUser.firstName}.`, 'success');
    updateNavAccount();
    renderAuthView();
}

function handleForgotPassword() {
    const email = document.getElementById('login-email')?.value.trim();
    if (email) {
        showAuthToast('Lien envoyé 📧', `Un email de réinitialisation a été envoyé à ${email}.`, 'success');
    } else {
        showAuthToast('Mot de passe oublié', 'Veuillez saisir votre adresse e-mail ci-dessus.', 'error');
    }
}

function renderAuthView() {
    const guestView = document.getElementById('auth-guest-view');
    const dashboardView = document.getElementById('auth-dashboard-view');
    if (!guestView || !dashboardView) return;

    let user = null;
    try {
        const stored = localStorage.getItem('sterimar_user');
        if (stored) user = JSON.parse(stored);
    } catch (e) {}

    const titleEl = document.getElementById('page-header-title');
    const descEl = document.getElementById('page-header-desc');

    if (user) {
        guestView.style.display = 'none';
        dashboardView.style.display = 'block';

        const fullName = `${user.firstName || ''} ${user.lastName || ''}`.trim() || 'Client Stérimar';
        const initials = ((user.firstName?.[0] || 'S') + (user.lastName?.[0] || 'B')).toUpperCase();

        const nameEl = document.getElementById('dashboard-user-fullname');
        const emailEl = document.getElementById('dashboard-user-email');
        const avatarEl = document.getElementById('user-avatar-initials');
        const civilityEl = document.getElementById('dash-civility');
        const phoneEl = document.getElementById('dash-phone');

        if (nameEl) nameEl.textContent = fullName;
        if (emailEl) emailEl.textContent = user.email || 'contact@sterimar.shop';
        if (avatarEl) avatarEl.textContent = initials;
        if (civilityEl) civilityEl.textContent = user.civility === 'M.' ? 'Monsieur' : 'Madame';
        if (phoneEl) phoneEl.textContent = user.phone || '+216 29 550 043';

        if (titleEl) titleEl.textContent = `Bienvenue, ${user.firstName || ''} !`;
        if (descEl) descEl.textContent = 'Gérez vos commandes, vos informations de livraison et vos avantages fidélité.';
    } else {
        guestView.style.display = 'grid';
        dashboardView.style.display = 'none';

        if (titleEl) titleEl.textContent = 'Espace Client & Connexion';
        if (descEl) descEl.textContent = 'Créez votre compte pour suivre vos livraisons en temps réel, gérer vos adresses et profiter d\'offres de santé exclusives.';
    }
}

let toastTimer = null;
function showAuthToast(title, msg, type = 'success') {
    const toast = document.getElementById('auth-toast');
    if (!toast) return;

    const titleEl = document.getElementById('auth-toast-title');
    const msgEl = document.getElementById('auth-toast-msg');
    const iconEl = document.getElementById('auth-toast-icon');

    if (titleEl) titleEl.textContent = title;
    if (msgEl) msgEl.textContent = msg;
    if (iconEl) iconEl.textContent = type === 'success' ? '✓' : '⚠️';

    toast.className = `auth-toast show ${type}`;

    if (toastTimer) clearTimeout(toastTimer);
    toastTimer = setTimeout(() => {
        toast.classList.remove('show');
    }, 4000);
}

// ==========================================
// INITIALIZATION
// ==========================================
document.addEventListener('DOMContentLoaded', () => {
    updateCartCount();
    initNavbar();
    updateNavAccount();
    initSwiper();
    initFilters();
    initCategoryBannerNav();
    initScrollReveal();
    initModalClose();
    initAIAssistant();
    
    // Render cart if on cart page
    if (document.getElementById('cart-items')) {
        renderCart();
    }

    // Render auth if on creation-compte page
    if (document.getElementById('auth-guest-view')) {
        renderAuthView();
    }

    // ==========================================
    // META PIXEL — RETARGETING EVENTS
    // ==========================================
    if (typeof fbq === 'function') {

        // --- ViewContent: fires on every product detail page ---
        const productPage = document.querySelector('.product-page');
        if (productPage) {
            const addToCartBtn = productPage.querySelector('[onclick*="addToCart"]');
            let productId = null;
            if (addToCartBtn) {
                const match = addToCartBtn.getAttribute('onclick').match(/addToCart\((\d+)/);
                if (match) productId = parseInt(match[1]);
            }
            const product = (productId !== null && products[productId]) ? products[productId] : null;
            fbq('track', 'ViewContent', {
                content_name: product ? product.name : (document.querySelector('h1') ? document.querySelector('h1').textContent.trim() : 'Produit Stérimar'),
                content_ids: [product ? String(product.wcId || productId) : '0'],
                content_type: 'product',
                value: product ? product.price : 0,
                currency: 'TND'
            });
        }

        // --- Contact: fires when the contact form is successfully submitted ---
        const contactForm = document.getElementById('contact-form');
        if (contactForm) {
            const origSubmit = window.handleContactSubmit;
            if (typeof origSubmit === 'function') {
                window.handleContactSubmit = async function(e) {
                    await origSubmit(e);
                    fbq('track', 'Contact');
                };
            } else {
                contactForm.addEventListener('submit', function() {
                    fbq('track', 'Contact');
                });
            }
        }

        // --- CompleteRegistration: fires when user creates an account ---
        const registerForm = document.getElementById('register-form');
        if (registerForm) {
            registerForm.addEventListener('submit', function() {
                fbq('track', 'CompleteRegistration', {
                    content_name: 'Stérimar Tunisie',
                    status: true
                });
            });
        }

        // --- Search: fires when user uses boutique category filters ---
        document.querySelectorAll('.filter-btn, [data-category]').forEach(function(btn) {
            btn.addEventListener('click', function() {
                const cat = btn.getAttribute('data-category') || btn.textContent.trim();
                if (cat && cat !== 'all') {
                    fbq('track', 'Search', {
                        search_string: cat,
                        content_category: 'Produits Stérimar'
                    });
                }
            });
        });
    }
});

