import './bootstrap';
import Lenis from 'lenis';

document.addEventListener("DOMContentLoaded", () => {

    // 0. INITIALIZE LENIS (Smooth Scroll)
    const lenis = new Lenis({
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

    function raf(time) {
        lenis.raf(time);
        requestAnimationFrame(raf);
    }

    requestAnimationFrame(raf);

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
    document.querySelectorAll('.text-reveal').forEach(el => splitChars(el));

    // 3. UNIFIED ANCHOR SCROLL (Desktop & Mobile)
    const navbarCollapse = document.getElementById('mainNavbar');
    const body = document.body;

    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            const targetId = this.getAttribute('href');
            if (!targetId || targetId === '#' || !targetId.startsWith('#')) return;

            const targetElement = document.querySelector(targetId);
            if (targetElement) {
                e.preventDefault();

                // Cek jika menu mobile sedang terbuka
                const isMobileMenuOpen = navbarCollapse && navbarCollapse.classList.contains('show');
                
                // Offset selaras dengan scroll-margin-top: 70px di CSS
                const scrollOptions = { 
                    offset: -70, 
                    duration: 1.4,
                    easing: (t) => Math.min(1, 1.001 - Math.pow(2, -10 * t))
                };

                if (isMobileMenuOpen && navbarCollapse.contains(this)) {
                    const bsCollapse = bootstrap.Collapse.getInstance(navbarCollapse) || new bootstrap.Collapse(navbarCollapse, { toggle: false });
                    if (bsCollapse) bsCollapse.hide();
                    
                    setTimeout(() => {
                        lenis.scrollTo(targetElement, scrollOptions);
                    }, 200);
                } else {
                    lenis.scrollTo(targetElement, scrollOptions);
                }

                // Update URL tanpa refresh (opsional)
                history.pushState(null, null, targetId);
            }
        });
    });


    // 1. FUNGSI JAM (Live Clock)
    const updateClock = () => {
        const clockElement = document.getElementById('live-clock');
        if (clockElement) {
            const now = new Date();
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            clockElement.textContent = `${hours}:${minutes}`;
        }
    };

    updateClock(); // Jalankan pertama kali saat halaman load
    setInterval(updateClock, 60000); // Update setiap 60 detik


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

    // Mulai mengamati
    document.querySelectorAll(animationSelectors).forEach(el => {
        observer.observe(el);
    });

    // 4. MOBILE MENU SCROLL LOCK (Lenis Integration)
    if (navbarCollapse) {
        navbarCollapse.addEventListener('show.bs.collapse', () => {
            body.classList.add('mobile-menu-open');
            lenis.stop(); // Kunci scroll Lenis saat menu terbuka
        });

        navbarCollapse.addEventListener('hide.bs.collapse', () => {
            body.classList.remove('mobile-menu-open');
            lenis.start(); // Aktifkan kembali scroll Lenis saat menu tertutup
        });
    }

    // 5. AUTO-SCROLL TO CONTACT ON ERROR/SUCCESS
    const hasErrors = document.querySelector('.is-invalid');
    const hasSuccess = document.querySelector('.alert-success');
    
    if (hasErrors || hasSuccess) {
        setTimeout(() => {
            const contactSection = document.getElementById('contact');
            if (contactSection) {
                lenis.scrollTo(contactSection); // Gunakan Lenis untuk konsistensi
            }
        }, 500);
    }

    // 6. THEME TOGGLE (Dark Mode) - Optimized
    const themeToggle = document.getElementById('theme-toggle');
    
    if (themeToggle) {
        themeToggle.addEventListener('click', () => {
            const isDark = document.documentElement.getAttribute('data-theme') === 'dark';
            const newTheme = isDark ? 'light' : 'dark';
            
            document.documentElement.setAttribute('data-theme', newTheme);
            localStorage.setItem('theme', newTheme);
        });
    }

    // 7. AUTO-EXPAND TEXTAREA (Pesan)
    const textareas = document.querySelectorAll('textarea');
    textareas.forEach(textarea => {
        // Atur tinggi awal jika ada isi (misal saat edit/error)
        if(textarea.value) {
            textarea.style.height = 'auto';
            textarea.style.height = (textarea.scrollHeight) + 'px';
        }

        textarea.addEventListener('input', function() {
            // Reset height sementara untuk mendapatkan scrollHeight yang sebenarnya
            this.style.height = 'auto';
            // Setel tinggi sesuai dengan konten di dalamnya
            this.style.height = (this.scrollHeight) + 'px';
            
            // Jaga-jaga agar scroll smooth Lenis tetap update
            if(window.lenis) {
                // Resize trigger, meski lenis bisa detect native DOM changes,
                // kadang butuh sedikit waktu
            }
        });
    });

    // 8. LANGUAGE TOGGLE (ID ↔ EN)
    const translations = {
        // Navbar
        'nav.about': 'About',
        'nav.skills': 'Skills',
        'nav.projects': 'Projects',
        'nav.contact': 'Contact Me',

        // Hero Section
        'hero.badge': 'DIGITAL WEB ARCHITECTURE',
        'hero.available': 'Available for work',
        'hero.role': 'Web Developer & Front End Enthusiast',
        'hero.extra': 'I build websites that combine modern design, high performance, and memorable user experiences.',
        'hero.btn.projects': 'See My Work',
        'hero.btn.contact': "Let's Chat",
        'hero.status': 'Ready for New Challenges',
        'hero.stat1.label': 'CLEAN CODE COMPLIANCE',
        'hero.stat2.label': 'DIGITAL SOLUTIONS CREATED',
        'hero.glass.badge': 'CURRENT STATUS',
        'hero.glass.text': 'Currently developing high-performance web applications with modern architecture.',

        'about.p1': 'Hi! I am Zenifen, a Front-End Developer diving deep into the world of web development. From writing my first line of HTML to building interactive web applications today, this journey has been full of surprises and exciting new learnings.',
        'about.p2': 'Currently, I am focused on developing skills in React, modern JavaScript, and creating UIs that are not only visually appealing but also comfortable to use. Every project is an opportunity to learn something new, from performance optimization to design system implementation.',
        'about.p3': 'For me, writing code is not just about correct syntax but about solving problems efficiently and elegantly. I believe every developer has a unique journey, and I am excited to keep growing in this field.',

        'about.stat1.number': '5+',
        'about.stat1.label': 'Projects',
        'about.stat1.number': '5+',
        'about.stat2.number': '',
        'about.stat2.label': 'Open to Collaborate',
        'about.stat3.number': '5+',
        'about.stat3.label': 'Technologies',
        'about.stat4.label': 'Always Learning',

    };

    const langToggle = document.getElementById('lang-toggle');
    const langLabel = langToggle ? langToggle.querySelector('.lang-label') : null;

    const originalTexts = {};
    document.querySelectorAll('[data-i18n]').forEach(el => {
        const key = el.getAttribute('data-i18n');
        originalTexts[key] = el.innerHTML;
    });

    const applyLanguage = (lang) => {
        document.querySelectorAll('[data-i18n]').forEach(el => {
            const key = el.getAttribute('data-i18n');
            if (lang === 'en' && translations[key]) {
                el.innerHTML = translations[key];
            } else if (lang === 'id' && originalTexts[key]) {
                el.innerHTML = originalTexts[key];
            }
        });

        if (langLabel) {
            langLabel.textContent = lang === 'en' ? 'ID' : 'EN';
        }

        document.documentElement.setAttribute('lang', lang);
    };

    const savedLang = localStorage.getItem('lang') || 'id';
    if (savedLang === 'en') {
        applyLanguage('en');
    }

    if (langToggle) {
        langToggle.addEventListener('click', () => {
            const currentLang = localStorage.getItem('lang') || 'id';
            const newLang = currentLang === 'id' ? 'en' : 'id';

            localStorage.setItem('lang', newLang);
            applyLanguage(newLang);
        });
    }

});