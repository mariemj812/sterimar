/* ==========================================
   STÉRIMAR™ E-Commerce — Application Logic
   ========================================== */

// ==========================================
// PRODUCTS DATA
// ==========================================
const products = [
    {
        id: 0,
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
    document.querySelectorAll('#cart-count').forEach(el => {
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
    } else {
        cart.push({
            id: productId,
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
        <div class="cart-item" id="cart-item-${item.id}">
            <div class="cart-item-image">
                <img src="${item.image}" alt="${item.name}">
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

function checkout() {
    if (cart.length === 0) return;
    
    const checkoutModal = document.getElementById('checkout-modal');
    if (checkoutModal) {
        checkoutModal.style.display = 'flex';
        cart = [];
        saveCart();
        renderCart();
    }
}

// ==========================================
// PRODUCT MODAL
// ==========================================
function openModal(productId) {
    const product = products[productId];
    const modal = document.getElementById('product-modal');
    
    if (!modal || !product) return;
    
    document.getElementById('modal-img').src = product.image;
    document.getElementById('modal-img').alt = product.name;
    document.getElementById('modal-category').textContent = product.categoryLabel;
    document.getElementById('modal-category').style.background = product.categoryColor;
    document.getElementById('modal-title').textContent = product.name;
    document.getElementById('modal-description').textContent = product.description;
    document.getElementById('modal-price').textContent = `${product.price.toFixed(2).replace('.', ',')} DT`;
    
    // Badges
    const badgesEl = document.getElementById('modal-badges');
    badgesEl.innerHTML = `
        <span class="detail-chip">🧪 ${product.molecule}</span>
        <span class="detail-chip">📋 ${product.type}</span>
        <span class="detail-chip">👤 ${product.audience}</span>
        <span class="detail-chip">📦 ${product.volume}</span>
    `;
    
    // Features
    const featuresList = document.getElementById('modal-features-list');
    featuresList.innerHTML = product.features.map(f => `<li>${f}</li>`).join('');
    
    // Usage
    document.getElementById('modal-usage-text').textContent = product.usage;
    
    // Add to cart button
    document.getElementById('modal-add-btn').setAttribute('onclick', `addToCart(${productId}); closeModal();`);
    
    modal.classList.add('active');
    document.body.style.overflow = 'hidden';
}

function closeModal() {
    const modal = document.getElementById('product-modal');
    if (modal) {
        modal.classList.remove('active');
        document.body.style.overflow = '';
    }
}

// ==========================================
// FILTER PRODUCTS
// ==========================================
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
    
    // Check for hash filters
    const hash = window.location.hash.replace('#', '');
    if (hash) {
        const targetBtn = document.querySelector(`.filter-btn[data-filter="${hash}"]`);
        if (targetBtn) targetBtn.click();
    }
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
    
    // Scroll effect
    window.addEventListener('scroll', () => {
        if (window.scrollY > 50) {
            navbar.classList.add('scrolled');
        } else {
            navbar.classList.remove('scrolled');
        }
    });
    
    // Mobile toggle
    if (navToggle && navLinks) {
        navToggle.addEventListener('click', () => {
            navToggle.classList.toggle('active');
            navLinks.classList.toggle('active');
        });
        
        // Close on link click
        navLinks.querySelectorAll('.nav-link').forEach(link => {
            link.addEventListener('click', () => {
                navToggle.classList.remove('active');
                navLinks.classList.remove('active');
            });
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
// INITIALIZATION
// ==========================================
document.addEventListener('DOMContentLoaded', () => {
    updateCartCount();
    initNavbar();
    initSwiper();
    initFilters();
    initScrollReveal();
    initModalClose();
    
    // Render cart if on cart page
    if (document.getElementById('cart-items')) {
        renderCart();
    }
});
