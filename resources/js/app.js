/**
 * app.js — Vanilla JS core (non-Alpine)
 * Sengaja tidak dimigrasikan ke Alpine:
 * - Lenis smooth scroll (library eksternal)
 * - Navbar height updater (util kecil, tanpa state UI)
 * - Anchor-scroll (terikat erat ke Lenis)
 * - IntersectionObserver scroll-reveal (risiko re-timing animasi)
 * Navbar mobile (buka/tutup + scroll lock Lenis) ada di komponen Alpine `navMenu`.
 */
import './alpine-init';
import Lenis from 'lenis';
import { prefersReducedMotion } from './motion';

document.addEventListener("DOMContentLoaded", () => {
    const reduceMotion = prefersReducedMotion();

    // 0. INITIALIZE LENIS (Smooth Scroll). With "reduce motion" there is no smooth scrolling:
    // a stand-in with the same few methods the rest of the code uses scrolls natively and instantly.
    const lenis = reduceMotion
        ? {
            scrollTo: (y) => window.scrollTo(0, y),
            stop() {},
            start() {},
            resize() {},
            raf() {},
        }
        : new Lenis({
            duration: 1.2,
            easing: (t) => Math.min(1, 1.001 - Math.pow(2, -10 * t)),
            direction: 'vertical',
            gestureDirection: 'vertical',
            smooth: true,
            mouseMultiplier: 1,
            smoothTouch: false,
            touchMultiplier: 2,
            infinite: false,
        });
    window.lenis = lenis;

    // Dynamic navbar height calculation for perfect scroll offset
    const updateNavbarHeight = () => {
        const navbar = document.querySelector('.custom-navbar');
        if (navbar) {
            const h = navbar.offsetHeight;
            document.documentElement.style.setProperty('--navbar-height', `${h}px`);
            document.documentElement.style.scrollPaddingTop = `${h}px`;
        }
    };
    updateNavbarHeight();
    setTimeout(updateNavbarHeight, 150); // Fallback for transition rendering delay
    window.addEventListener('resize', updateNavbarHeight);

    function raf(time) {
        lenis.raf(time);
        requestAnimationFrame(raf);
    }

    if (!reduceMotion) {
        requestAnimationFrame(raf);
    }

    // 0.1 TEXT REVEAL LOGIC (Split Characters - Fixed Word Breaking)
    const splitChars = (el) => {
        const walk = document.createTreeWalker(el, NodeFilter.SHOW_TEXT, null, false);
        const textNodes = [];
        let node;
        while (node = walk.nextNode()) textNodes.push(node);

        let charIndex = 0;
        textNodes.forEach(textNode => {
            const text = textNode.textContent;
            const fragment = document.createDocumentFragment();
            
            // Pecah berdasarkan kata (dan simpan spasi)
            const words = text.split(/(\s+)/);
            
            words.forEach(word => {
                if (word.trim() === '') {
                    // Jika hanya spasi, biarkan sebagai node teks biasa
                    fragment.appendChild(document.createTextNode(word));
                } else {
                    // Bungkus kata dalam span agar tidak terpotong (nowrap)
                    const wordSpan = document.createElement('span');
                    wordSpan.style.display = 'inline-block';
                    wordSpan.style.whiteSpace = 'nowrap';
                    
                    word.split('').forEach(char => {
                        const span = document.createElement('span');
                        span.textContent = char;
                        span.classList.add('char');
                        span.style.transitionDelay = `${charIndex * 35}ms`;
                        wordSpan.appendChild(span);
                        charIndex++;
                    });
                    fragment.appendChild(wordSpan);
                }
            });
            textNode.parentNode.replaceChild(fragment, textNode);
        });
    };

    // Terapkan splitChars ke elemen yang ditandai
    if (!reduceMotion) {
        document.querySelectorAll('.text-reveal').forEach(el => splitChars(el));
    }

    // 3. UNIFIED ANCHOR SCROLL (Desktop & Mobile)
    const navbarCollapse = document.getElementById('mainNavbar');

    document.querySelectorAll('a[href*="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            const href = this.getAttribute('href');
            const hashIndex = href.indexOf('#');
            if (hashIndex === -1) return;

            const targetId = href.substring(hashIndex);
            if (!targetId || targetId === '#') return;

            // Resolve full URL to check pathname
            const targetUrl = new URL(this.href, window.location.href);
            if (targetUrl.pathname !== window.location.pathname) {
                // Allow default navigation to redirect to the home page
                return;
            }

            const targetElement = document.querySelector(targetId);
            if (targetElement) {
                e.preventDefault();

                // Cek jika menu mobile sedang terbuka
                const isMobileMenuOpen = navbarCollapse && navbarCollapse.classList.contains('show');

                const doScroll = () => {
                    const navbar = document.querySelector('.custom-navbar');
                    const navbarH = navbar ? navbar.offsetHeight : 68;
                    
                    // Calculate EXACT absolute scroll position — no ambiguity with Lenis offset behavior
                    const elementAbsoluteTop = targetElement.getBoundingClientRect().top + window.pageYOffset;
                    const scrollTarget = elementAbsoluteTop - navbarH;

                    lenis.scrollTo(scrollTarget, {
                        duration: 1.4,
                        easing: (t) => Math.min(1, 1.001 - Math.pow(2, -10 * t))
                    });
                };

                if (isMobileMenuOpen && navbarCollapse.contains(this)) {
                    // Alpine navMenu closes, then announces `nav-closed`; measure the navbar only after that.
                    window.addEventListener('nav-closed', doScroll, { once: true });
                    window.dispatchEvent(new CustomEvent('nav-close'));
                } else {
                    doScroll();
                }

                // Update URL tanpa refresh (opsional)
                history.pushState(null, null, targetId);
            }
        });
    });


    // 2. LOGIKA ANIMASI (Intersection Observer) - CSS Transition Based
    const observerOptions = {
        threshold: 0, 
        rootMargin: "100px 0px 100px 0px"
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry, index) => {
            if (entry.isIntersecting) {
                const el = entry.target;
                if (el.classList.contains('reveal-active')) return;

                // Cap the stagger index to avoid very long delays
                const staggerIndex = index % 8;
                el.style.transitionDelay = `${staggerIndex * 100}ms`;
                
                el.classList.add('reveal-active');

                // Menambahkan kelas animation-finished setelah transisi selesai (biasanya 800ms - 1000ms)
                // Ini penting untuk mengaktifkan efek hover di SASS
                const transitionDuration = parseFloat(getComputedStyle(el).transitionDuration) * 1000 || 800;
                const transitionDelay = parseFloat(getComputedStyle(el).transitionDelay) * 1000 || 0;
                
                setTimeout(() => {
                    el.classList.add('animation-finished');
                }, transitionDuration + transitionDelay + 50);

                observer.unobserve(el);
            }
        });
    }, observerOptions);

    // Selektor elemen yang akan dianimasikan - Fokus pada blok utama
    const animationSelectors = [
        '.reveal-ready',
        '.animate-on-scroll', 
        '.image-sweep', 
        '.profile-card-horizontal', 
        '.stack-animated',
        '.card-skill-v2',
        '.hero-location-badge',
        '.animate-text',
        '.animate-buttons',
        '.form-group',
        '.btn-send-contact',
        '.project-card',
        'section h1', 'section h2'
    ].join(', ');

    // Mulai mengamati. With "reduce motion" everything is shown at once (and hover effects enabled).
    document.querySelectorAll(animationSelectors).forEach(el => {
        if (reduceMotion) {
            el.classList.add('reveal-active', 'animation-finished');
        } else {
            observer.observe(el);
        }
    });

    // 4. MOBILE MENU SCROLL LOCK: handled by the Alpine `navMenu` component (alpine-init.js).

});