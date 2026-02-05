// main.js - Point d'entrée JavaScript principal

// Import des modules
import { initNavigation } from './components/navigation.js';
import { initModals } from './components/modal.js';
import { initForms } from './components/form-handler.js';
import { initCarousels } from './components/carousel.js';
import { setupAnimations } from './modules/animations.js';
import { setupApi } from './modules/api.js';
import { getCurrentYear } from './modules/function/utile/utile.js';

// Variables globales
const App = {
    config: {
        apiUrl: '/api',
        debug: true,
        version: '1.0.0'
    },
    state: {
        user: null,
        cart: [],
        theme: 'light'
    },
    modules: {}
};

// Initialisation de l'application
function initApp() {
    console.log(`🚀 ${document.title} - Version ${App.config.version}`);
    
    // Initialisation des modules
    try {
        initCoreModules();
        initPageSpecificModules();
        setupEventListeners();
        loadInitialData();
        
        console.log('✅ Application initialisée avec succès');
    } catch (error) {
        console.error('❌ Erreur lors de l\'initialisation:', error);
        showError('Une erreur est survenue lors du chargement de l\'application.');
    }
}

// Initialisation des modules de base
function initCoreModules() {
    App.modules.navigation = initNavigation();
    App.modules.modals = initModals();
    App.modules.forms = initForms();
    App.modules.carousels = initCarousels();
    App.modules.animations = setupAnimations();
    App.modules.api = setupApi(App.config.apiUrl);
}

// Initialisation des modules spécifiques à la page
function initPageSpecificModules() {
    const page = document.body.dataset.page || 'home';
    
    switch(page) {
        case 'home':
            initHomePage();
            break;
        case 'about':
            initAboutPage();
            break;
        case 'contact':
            initContactPage();
            break;
        case 'products':
            initProductsPage();
            break;
        default:
            console.log(`📄 Page: ${page}`);
    }
}

// Initialisation de la page d'accueil
function initHomePage() {
    console.log('🏠 Initialisation de la page d\'accueil');
    
    // Initialiser le slider de témoignages
    const testimonialSlider = document.querySelector('.testimonials-slider');
    if (testimonialSlider) {
        initTestimonialSlider();
    }
    
    // Initialiser les animations de scroll
    initScrollAnimations();
    
    // Charger les données dynamiques
    loadFeaturedContent();
}

// Initialisation de la page À propos
function initAboutPage() {
    console.log('📖 Initialisation de la page À propos');
    // Code spécifique à la page À propos
}

// Initialisation de la page Contact
function initContactPage() {
    console.log('📧 Initialisation de la page Contact');
    // Code spécifique à la page Contact
}

// Initialisation de la page Produits
function initProductsPage() {
    console.log('🛒 Initialisation de la page Produits');
    
    // Initialiser le filtrage des produits
    const filterButtons = document.querySelectorAll('.filter-btn');
    if (filterButtons.length > 0) {
        initProductFilters();
    }
    
    // Initialiser le tri des produits
    const sortSelect = document.querySelector('.sort-select');
    if (sortSelect) {
        initProductSorting();
    }
}

// Slider de témoignages
function initTestimonialSlider() {
    const track = document.querySelector('.testimonials-track');
    const slides = document.querySelectorAll('.testimonial-slide');
    const dots = document.querySelectorAll('.testimonial-dot');
    
    if (!track || slides.length === 0) return;
    
    let currentSlide = 0;
    const totalSlides = slides.length;
    
    // Fonction pour aller à un slide spécifique
    function goToSlide(index) {
        if (index < 0 || index >= totalSlides) return;
        
        currentSlide = index;
        track.style.transform = `translateX(-${currentSlide * 100}%)`;
        
        // Mettre à jour les dots
        dots.forEach((dot, i) => {
            dot.classList.toggle('active', i === currentSlide);
        });
    }
    
    // Événements pour les dots
    dots.forEach((dot, index) => {
        dot.addEventListener('click', () => goToSlide(index));
    });
    
    // Auto-slide (optionnel)
    let slideInterval;
    
    function startAutoSlide() {
        slideInterval = setInterval(() => {
            const nextSlide = (currentSlide + 1) % totalSlides;
            goToSlide(nextSlide);
        }, 5000);
    }
    
    function stopAutoSlide() {
        clearInterval(slideInterval);
    }
    
    // Démarrer l'auto-slide
    startAutoSlide();
    
    // Arrêter l'auto-slide au survol
    track.addEventListener('mouseenter', stopAutoSlide);
    track.addEventListener('mouseleave', startAutoSlide);
    
    // Navigation au clavier
    document.addEventListener('keydown', (e) => {
        if (e.key === 'ArrowLeft') {
            goToSlide(currentSlide - 1);
        } else if (e.key === 'ArrowRight') {
            goToSlide(currentSlide + 1);
        }
    });
    
    // Swipe sur mobile
    let touchStartX = 0;
    let touchEndX = 0;
    
    track.addEventListener('touchstart', (e) => {
        touchStartX = e.changedTouches[0].screenX;
    });
    
    track.addEventListener('touchend', (e) => {
        touchEndX = e.changedTouches[0].screenX;
        handleSwipe();
    });
    
    function handleSwipe() {
        const swipeThreshold = 50;
        const diff = touchStartX - touchEndX;
        
        if (Math.abs(diff) > swipeThreshold) {
            if (diff > 0) {
                // Swipe gauche
                goToSlide(currentSlide + 1);
            } else {
                // Swipe droite
                goToSlide(currentSlide - 1);
            }
        }
    }
}

// Animations au scroll
function initScrollAnimations() {
    const animatedElements = document.querySelectorAll('[data-scroll]');
    
    if (animatedElements.length === 0) return;
    
    const observerOptions = {
        root: null,
        rootMargin: '0px',
        threshold: 0.1
    };
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
                observer.unobserve(entry.target);
            }
        });
    }, observerOptions);
    
    animatedElements.forEach(element => {
        observer.observe(element);
    });
}

// Filtrage des produits
function initProductFilters() {
    const filterButtons = document.querySelectorAll('.filter-btn');
    const productCards = document.querySelectorAll('.product-card');
    
    filterButtons.forEach(button => {
        button.addEventListener('click', () => {
            // Mettre à jour le bouton actif
            filterButtons.forEach(btn => btn.classList.remove('active'));
            button.classList.add('active');
            
            // Filtrer les produits
            const filterValue = button.dataset.filter;
            
            productCards.forEach(card => {
                if (filterValue === 'all' || card.dataset.category === filterValue) {
                    card.style.display = 'block';
                    setTimeout(() => {
                        card.classList.add('visible');
                    }, 10);
                } else {
                    card.classList.remove('visible');
                    setTimeout(() => {
                        card.style.display = 'none';
                    }, 300);
                }
            });
        });
    });
}

// Tri des produits
function initProductSorting() {
    const sortSelect = document.querySelector('.sort-select');
    const productGrid = document.querySelector('.products-grid');
    
    if (!sortSelect || !productGrid) return;
    
    sortSelect.addEventListener('change', () => {
        const sortValue = sortSelect.value;
        const products = Array.from(productGrid.querySelectorAll('.product-card'));
        
        products.sort((a, b) => {
            const priceA = parseFloat(a.dataset.price);
            const priceB = parseFloat(b.dataset.price);
            
            switch(sortValue) {
                case 'price-asc':
                    return priceA - priceB;
                case 'price-desc':
                    return priceB - priceA;
                case 'name-asc':
                    return a.dataset.name.localeCompare(b.dataset.name);
                case 'name-desc':
                    return b.dataset.name.localeCompare(a.dataset.name);
                default:
                    return 0;
            }
        });
        
        // Réorganiser les produits
        products.forEach(product => {
            productGrid.appendChild(product);
        });
    });
}

// Chargement des données initiales
async function loadInitialData() {
    try {
        // Charger les données utilisateur
        const userData = await loadUserData();
        if (userData) {
            App.state.user = userData;
            updateUIForUser();
        }
        
        // Charger le panier
        const cartData = loadCartFromStorage();
        if (cartData) {
            App.state.cart = cartData;
            updateCartCount();
        }
        
        // Charger le thème
        const savedTheme = localStorage.getItem('theme');
        if (savedTheme) {
            setTheme(savedTheme);
        }
        
    } catch (error) {
        console.warn('⚠️ Impossible de charger les données initiales:', error);
    }
}

// Charger les données utilisateur
async function loadUserData() {
    try {
        const response = await fetch(`${App.config.apiUrl}/user.json`);
        if (!response.ok) throw new Error('Network response was not ok');
        return await response.json();
    } catch (error) {
        return null;
    }
}

// Mettre à jour l'UI pour l'utilisateur
function updateUIForUser() {
    const user = App.state.user;
    if (!user) return;
    
    // Mettre à jour le nom d'utilisateur dans la navigation
    const userElement = document.querySelector('.user-greeting');
    if (userElement) {
        userElement.textContent = `Bonjour, ${user.name}`;
    }
}

// Charger le contenu en vedette
async function loadFeaturedContent() {
    try {
        const response = await fetch(`${App.config.apiUrl}/featured.json`);
        const data = await response.json();
        
        // Mettre à jour l'interface avec les données
        updateFeaturedContent(data);
    } catch (error) {
        console.warn('⚠️ Impossible de charger le contenu en vedette:', error);
    }
}

function updateFeaturedContent(data) {
    // Implémentation spécifique selon la structure des données
    if (App.config.debug) {
        console.log('📊 Données en vedette chargées:', data);
    }
}

// Configuration des écouteurs d'événements globaux
function setupEventListeners() {
    // Gestionnaire d'erreurs global
    window.addEventListener('error', handleGlobalError);
    
    // Gestionnaire de redimensionnement
    window.addEventListener('resize', debounce(handleResize, 250));
    
    // Gestionnaire de scroll
    window.addEventListener('scroll', throttle(handleScroll, 100));
    
    // Gestionnaire de changement de thème
    const themeToggle = document.querySelector('.theme-toggle');
    if (themeToggle) {
        themeToggle.addEventListener('click', toggleTheme);
    }
    
    // Gestionnaire pour les liens externes
    document.addEventListener('click', handleExternalLinks);
}

// Gestion des erreurs globales
function handleGlobalError(event) {
    console.error('🚨 Erreur globale:', event.error);
    
    // Envoyer l'erreur à un service de suivi (optionnel)
    if (App.config.debug) {
        showError(`Une erreur s'est produite: ${event.message}`);
    }
}

// Gestion du redimensionnement
function handleResize() {
    // Mettre à jour les dimensions si nécessaire
    if (App.config.debug) {
        console.log('📱 Fenêtre redimensionnée:', window.innerWidth, 'x', window.innerHeight);
    }
}

// Gestion du scroll
function handleScroll() {
    const scrollY = window.scrollY;
    const header = document.querySelector('.header');
    
    // Ajouter/supprimer la classe scrolled sur l'en-tête
    if (scrollY > 100) {
        header?.classList.add('scrolled');
    } else {
        header?.classList.remove('scrolled');
    }
    
    // Animation de la progression du scroll
    const scrollProgress = document.querySelector('.scroll-progress');
    if (scrollProgress) {
        const windowHeight = document.documentElement.scrollHeight - window.innerHeight;
        const scrolled = (window.scrollY / windowHeight) * 100;
        scrollProgress.style.width = `${scrolled}%`;
    }
}

// Basculer entre les thèmes clair/sombre
function toggleTheme() {
    const newTheme = App.state.theme === 'light' ? 'dark' : 'light';
    setTheme(newTheme);
}

function setTheme(theme) {
    App.state.theme = theme;
    document.documentElement.setAttribute('data-theme', theme);
    localStorage.setItem('theme', theme);
    
    // Mettre à jour le texte du bouton
    const themeToggle = document.querySelector('.theme-toggle');
    if (themeToggle) {
        themeToggle.textContent = theme === 'light' ? '🌙' : '☀️';
        themeToggle.setAttribute('aria-label', `Basculer en mode ${theme === 'light' ? 'sombre' : 'clair'}`);
    }
}

// Gérer les liens externes
function handleExternalLinks(event) {
    const link = event.target.closest('a');
    if (!link) return;
    
    const href = link.getAttribute('href');
    
    // Vérifier si c'est un lien externe
    if (href && href.startsWith('http') && !href.includes(window.location.hostname)) {
        // Ajouter des attributs pour la sécurité
        link.setAttribute('rel', 'noopener noreferrer');
        link.setAttribute('target', '_blank');
        
        // Optionnel: suivre les clics externes
        if (App.config.debug) {
            console.log('🔗 Lien externe cliqué:', href);
        }
    }
}

// Afficher une erreur à l'utilisateur
function showError(message) {
    // Créer un élément d'erreur
    const errorDiv = document.createElement('div');
    errorDiv.className = 'error-message';
    errorDiv.innerHTML = `
        <div class="error-content">
            <span class="error-icon">⚠️</span>
            <span class="error-text">${message}</span>
            <button class="error-close">&times;</button>
        </div>
    `;
    
    // Ajouter au document
    document.body.appendChild(errorDiv);
    
    // Ajouter des styles
    errorDiv.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        background: #f8d7da;
        color: #721c24;
        padding: 1rem;
        border-radius: 4px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        z-index: 9999;
        max-width: 400px;
        animation: slideInRight 0.3s ease-out;
    `;
    
    // Gérer la fermeture
    const closeBtn = errorDiv.querySelector('.error-close');
    closeBtn.addEventListener('click', () => {
        errorDiv.style.animation = 'slideOutRight 0.3s ease-out';
        setTimeout(() => errorDiv.remove(), 300);
    });
    
    // Auto-fermeture après 5 secondes
    setTimeout(() => {
        if (errorDiv.parentNode) {
            errorDiv.style.animation = 'slideOutRight 0.3s ease-out';
            setTimeout(() => errorDiv.remove(), 300);
        }
    }, 5000);
}

// Gérer le panier
function addToCart(product) {
    App.state.cart.push(product);
    saveCartToStorage();
    updateCartCount();
    showNotification('Produit ajouté au panier');
}

function removeFromCart(productId) {
    App.state.cart = App.state.cart.filter(item => item.id !== productId);
    saveCartToStorage();
    updateCartCount();
}

function saveCartToStorage() {
    try {
        localStorage.setItem('cart', JSON.stringify(App.state.cart));
    } catch (error) {
        console.warn('⚠️ Impossible de sauvegarder le panier:', error);
    }
}

function loadCartFromStorage() {
    try {
        const cartData = localStorage.getItem('cart');
        return cartData ? JSON.parse(cartData) : [];
    } catch (error) {
        console.warn('⚠️ Impossible de charger le panier:', error);
        return [];
    }
}

function updateCartCount() {
    const cartCount = document.querySelector('.cart-count');
    if (cartCount) {
        const count = App.state.cart.length;
        cartCount.textContent = count;
        cartCount.style.display = count > 0 ? 'flex' : 'none';
    }
}

function showNotification(message) {
    // Implémentation de notification
    if ('Notification' in window && Notification.permission === 'granted') {
        new Notification(message);
    }
}

// Fonctions utilitaires
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

function throttle(func, limit) {
    let inThrottle;
    return function() {
        const args = arguments;
        const context = this;
        if (!inThrottle) {
            func.apply(context, args);
            inThrottle = true;
            setTimeout(() => inThrottle = false, limit);
        }
    };
}

// Démarrer l'application quand le DOM est chargé
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initApp);
} else {
    initApp();
}

// Exposer l'application globalement (optionnel)
window.App = App;

// Support des anciens navigateurs
if (!Array.prototype.forEach) {
    Array.prototype.forEach = function(callback) {
        for (let i = 0; i < this.length; i++) {
            callback(this[i], i, this);
        }
    };
}

console.log('📦 main.js chargé');
