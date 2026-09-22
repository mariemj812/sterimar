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
    
    const checkoutBtn = document.getElementById('checkout-btn');
    if (checkoutBtn) {
        checkoutBtn.disabled = true;
        checkoutBtn.innerHTML = `<span>Synchronisation en cours...</span>`;
    }
    
    try {
        const formData = new FormData();
        formData.append('sterimar_wc_checkout', '1');
        formData.append('cart', JSON.stringify(cart));
        
        const response = await fetch(window.location.pathname.includes('.html') ? 'index.php' : window.location.href, {
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
        subtitle: "7 solutions naturelles à base d'eau de mer 100% naturelle de la Baie de Cancale pour toute la famille.",
        badge: "Gamme Complète • Physiologique & Hypertonique",
        image: "cat-banner-all.jpg?v=20260922d",
        themeColor: "#0077B6"
    },
    hygiene: {
        tag: "Hygiène & Prévention Quotidienne",
        title: "Gamme Hygiène du Nez",
        subtitle: "Lavez, hydratez et protégez vos fosses nasales tous les jours. Enrichi en oligo-éléments marins.",
        badge: "Physiologique • Dès la naissance & Adulte",
        image: "cat-banner-hygiene.jpg?v=20260922d",
        themeColor: "#00B8E5"
    },
    rhume: {
        tag: "Décongestion & Prévention Hivernale",
        title: "Gamme Rhume & Nez Bouché",
        subtitle: "Débouchez jusqu'à 6h et stoppez les symptômes du rhume grâce aux formules enrichies en Cuivre & Soufre.",
        badge: "Décongestion 6h • Cuivre & Soufre",
        image: "cat-banner-rhume.jpg?v=20260922d",
        themeColor: "#E05326"
    },
    allergie: {
        tag: "Protection Anti-Allergique Naturelle",
        title: "Gamme Nez Allergique",
        subtitle: "Élimine pollens, acariens et poils d'animaux. Formule enrichie en Manganèse protecteur et anti-allergique.",
        badge: "Protection Naturelle • Manganèse",
        image: "cat-banner-allergie.jpg?v=20260922d",
        themeColor: "#4E9B28"
    },
    bebe: {
        tag: "Douceur & Sécurité Nouveau-Né",
        title: "Gamme Stérimar™ Bébé",
        subtitle: "Spécialement formulé pour les tout-petits de 0 à 3 ans avec son embout sécurité pédiatrique breveté.",
        badge: "Dès la naissance • Embout sécurité pédiatrique",
        image: "cat-banner-bebe.jpg?v=20260922d",
        themeColor: "#3594D2"
    }
};

function updateCategoryBanner(filterKey) {
    const data = categoryBannerData[filterKey] || categoryBannerData.all;
    const tagEl = document.getElementById('cat-banner-tag');
    const titleEl = document.getElementById('cat-banner-title');
    const subtitleEl = document.getElementById('cat-banner-subtitle');
    const badgeTextEl = document.getElementById('cat-banner-badge-text');
    const bannerBg = document.getElementById('cat-banner-bg');

    if (tagEl) tagEl.textContent = data.tag;
    if (titleEl) titleEl.textContent = data.title;
    if (subtitleEl) subtitleEl.textContent = data.subtitle;
    if (badgeTextEl) badgeTextEl.textContent = data.badge;
    
    const fullImgUrl = getAssetUrl(data.image);
    if (bannerBg) {
        bannerBg.style.backgroundImage = `url('${fullImgUrl}')`;
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
    
    // Inject mobile direct cart button if not present
    if (navToggle && navPanier && !document.getElementById('mobile-header-cart')) {
        const mobileCart = document.createElement('a');
        mobileCart.href = navPanier.getAttribute('href') || 'panier.html';
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
            document.body.classList.toggle('menu-open', isOpen);
        });
        
        // Close on link click
        navLinks.querySelectorAll('.nav-link').forEach(link => {
            link.addEventListener('click', () => {
                navToggle.classList.remove('active');
                navLinks.classList.remove('active');
                document.body.classList.remove('menu-open');
            });
        });

        // Close when clicking outside navbar
        document.addEventListener('click', (e) => {
            if (navLinks.classList.contains('active') && !navbar.contains(e.target)) {
                navToggle.classList.remove('active');
                navLinks.classList.remove('active');
                document.body.classList.remove('menu-open');
            }
        });

        // Close on Escape key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && navLinks.classList.contains('active')) {
                navToggle.classList.remove('active');
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
function initAIAssistant() {
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
                👋 <strong>Bonjour !</strong> Je suis votre conseiller Stérimar™ IA. Décrivez-moi vos symptômes ou vos besoins pour que je vous recommande le spray nasal le plus adapté.
            </div>
        </div>
        <div class="ai-quick-prompts">
            <button class="ai-prompt-chip" data-prompt="Quel produit pour un nez bouché ?">🤧 Nez bouché</button>
            <button class="ai-prompt-chip" data-prompt="Quel spray choisir pour mon bébé ?">👶 Soin Bébé</button>
            <button class="ai-prompt-chip" data-prompt="J'ai une allergie au pollen">🌸 Allergies</button>
            <button class="ai-prompt-chip" data-prompt="Comment bien utiliser Stérimar ?">💡 Posologie</button>
        </div>
        <form class="ai-input-form" id="ai-input-form">
            <input type="text" class="ai-input-field" id="ai-input-text" placeholder="Posez votre question santé du nez..." autocomplete="off">
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
        const msgs = document.getElementById('ai-messages');
        msgs.innerHTML = `
            <div class="ai-msg bot">
                👋 <strong>Bonjour !</strong> Je suis votre conseiller Stérimar™ IA. Décrivez-moi vos symptômes ou vos besoins pour que je vous recommande le spray nasal le plus adapté.
            </div>
        `;
    });

    document.querySelectorAll('.ai-prompt-chip').forEach(chip => {
        chip.addEventListener('click', () => {
            const prompt = chip.dataset.prompt;
            handleUserMessage(prompt);
        });
    });

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

    // Append user message
    const userMsgEl = document.createElement('div');
    userMsgEl.className = 'ai-msg user';
    userMsgEl.textContent = userText;
    msgs.appendChild(userMsgEl);
    msgs.scrollTop = msgs.scrollHeight;

    // Typing indicator
    const typingEl = document.createElement('div');
    typingEl.className = 'ai-msg bot';
    typingEl.id = 'ai-typing-indicator';
    typingEl.innerHTML = '<em>Le Conseiller Stérimar réfléchit... 🌊</em>';
    msgs.appendChild(typingEl);
    msgs.scrollTop = msgs.scrollHeight;

    setTimeout(() => {
        const activeTyping = document.getElementById('ai-typing-indicator');
        if (activeTyping) activeTyping.remove();
        generateAIResponse(userText);
    }, 500);
}

function generateAIResponse(query) {
    const q = query.toLowerCase();
    const msgs = document.getElementById('ai-messages');
    let responseText = "";
    let recommendedProductId = null;

    if (q.includes('bébé') || q.includes('bebe') || q.includes('nourrisson') || q.includes('enfant') || q.includes('naissance')) {
        if (q.includes('bouché') || q.includes('bouche') || q.includes('enrhumé') || q.includes('rhume') || q.includes('congestion')) {
            responseText = "Pour un bébé au nez bouché ou encombré dès 3 mois, nous vous recommandons <strong>Stérimar™ Nez Bouché Bébé</strong>. Enrichi en cuivre, il décongestionne en douceur avec un embout de sécurité ergonomique.";
            recommendedProductId = 5;
        } else {
            responseText = "Pour le lavage quotidien et la prévention chez les tout-petits dès la naissance, nous vous recommandons <strong>Stérimar™ Hygiène du Nez Bébé</strong> avec embout sécurité exclusif.";
            recommendedProductId = 4;
        }
    } else if (q.includes('allergie') || q.includes('pollen') || q.includes('acarien') || q.includes('éternu') || q.includes('rhinite')) {
        responseText = "Pour soulager les allergies nasales (pollens, poussières, poils d'animaux), le produit idéal est <strong>Stérimar™ Nez Allergique</strong>, enrichi en Manganèse protecteur (Élu Meilleur Produit Pharma).";
        recommendedProductId = 3;
    } else if (q.includes('bouché') || q.includes('bouche') || q.includes('débouch') || q.includes('sinusite') || q.includes('forte congestion')) {
        responseText = "En cas de forte congestion nasale, optez pour <strong>Stérimar™ Nez Bouché (Hypertonique)</strong>. Enrichi en Cuivre, il débouche le nez jusqu'à 6 heures par action osmotique naturelle sans accoutumance.";
        recommendedProductId = 2;
    } else if (q.includes('rhume') || q.includes('froid') || q.includes('hiver') || q.includes('prévenir') || q.includes('gorge')) {
        if (q.includes('stop') || q.includes('dès les premiers')) {
            responseText = "Pour stopper le rhume dès les premiers frissons, découvrez <strong>Stérimar™ Stop & Protect Rhume</strong> avec son action barrière protectrice.";
            recommendedProductId = 6;
        } else {
            responseText = "Pour renforcer vos défenses nasales face aux agressions hivernales, utilisez <strong>Stérimar™ Nez sujet aux Rhumes</strong>, enrichi en Soufre naturel.";
            recommendedProductId = 1;
        }
    } else if (q.includes('posologie') || q.includes('comment utiliser') || q.includes('mode d\'emploi') || q.includes('utilisation')) {
        responseText = "<strong>Conseil d'utilisation Stérimar™ :</strong><br>• En hygiène quotidienne : 1 à 2 pulvérisations par narine, 1 à 3 fois par jour.<br>• En décongestion (Nez Bouché) : 1 à 2 pulvérisations, 2 à 3 fois par jour pendant 5 jours max.<br>• Inclinez la tête sur le côté, insérez délicatement l'embout et pulvérisez. Mouchez après usage.";
        recommendedProductId = 0;
    } else if (q.includes('prix') || q.includes('tarif') || q.includes('combien') || q.includes('livraison')) {
        responseText = "Tous nos sprays Stérimar™ 100ml et Stop & Protect sont au prix unique de <strong>16,00 DT</strong>. La livraison est assurée sous 24/48h en Tunisie (7,00 DT, et <strong>GRATUITE dès 100 DT d'achat</strong>).";
        recommendedProductId = 0;
    } else {
        responseText = "Pour un confort nasal optimal et une respiration saine au quotidien, <strong>Stérimar™ Hygiène du Nez</strong> à l'eau de mer 100% naturelle de la Baie de Cancale est la référence incontournable.";
        recommendedProductId = 0;
    }

    const botMsgEl = document.createElement('div');
    botMsgEl.className = 'ai-msg bot';
    botMsgEl.innerHTML = responseText;

    if (recommendedProductId !== null && products[recommendedProductId]) {
        const p = products[recommendedProductId];
        const cardEl = document.createElement('div');
        cardEl.className = 'ai-product-card-msg';
        cardEl.innerHTML = `
            <div class="ai-product-card-head">
                <img src="${getAssetUrl(p.image)}" alt="${p.name}">
                <div>
                    <div class="ai-product-card-title">${p.name}</div>
                    <div class="ai-product-card-price">${p.price.toFixed(2).replace('.', ',')} DT</div>
                </div>
            </div>
            <button class="ai-product-add-btn" onclick="addToCart(${p.id})">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m12 5v14m-7-7h14"/></svg>
                Ajouter au panier (16,00 DT)
            </button>
        `;
        botMsgEl.appendChild(cardEl);
    }

    msgs.appendChild(botMsgEl);
    msgs.scrollTop = msgs.scrollHeight;
}

// ==========================================
// INITIALIZATION
// ==========================================
document.addEventListener('DOMContentLoaded', () => {
    updateCartCount();
    initNavbar();
    initSwiper();
    initFilters();
    initScrollReveal();
    initModalClose();
    initAIAssistant();
    
    // Render cart if on cart page
    if (document.getElementById('cart-items')) {
        renderCart();
    }
});
