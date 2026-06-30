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
                
                // Offset selaras dengan scroll-margin-top: 75px di CSS (= tinggi navbar)
                const scrollOptions = { 
                    offset: -75, 
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
    
    // Inject style to force transition on all elements during theme switching
    (function() {
        if (document.getElementById('theme-force-style')) return;
        const style = document.createElement('style');
        style.id = 'theme-force-style';
        style.textContent = `
            html.theme-switching,
            html.theme-switching *,
            html.theme-switching *::before,
            html.theme-switching *::after {
                transition: background-color 0.3s ease, color 0.3s ease, border-color 0.3s ease, opacity 0.3s ease, box-shadow 0.3s ease !important;
                transition-delay: 0s !important;
                animation: none !important;
                animation-delay: 0s !important;
            }
        `;
        document.head.appendChild(style);
    })();

    if (themeToggle) {
        themeToggle.addEventListener('click', () => {
            // Enable transition styling
            document.documentElement.classList.add('theme-switching');
            
            // Force browser reflow to apply transition styles immediately
            document.documentElement.offsetHeight;
            
            // Toggle the theme attribute
            const isDark = document.documentElement.getAttribute('data-theme') === 'dark';
            const newTheme = isDark ? 'light' : 'dark';
            
            document.documentElement.setAttribute('data-theme', newTheme);
            localStorage.setItem('theme', newTheme);
            
            // Disable transition styling once transition completes (300ms)
            setTimeout(() => {
                document.documentElement.classList.remove('theme-switching');
            }, 300);
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
        'nav.home': 'Home',
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

        // About Section
        "about.title": "About Me",
        "about.p1": 'Hi! I am Zenifen, a <strong>Front-End Developer</strong> focused on building interactive web interfaces. Starting from a curiosity about HTML, I now design modern web architectures using <strong>React, modern JavaScript, and Tailwind CSS</strong>.',
        "about.p2": 'For me, <i>coding</i> is not just about syntax, but about solving problems. From <strong>performance optimization</strong> (Core Web Vitals) to <strong>Design System</strong> implementation, I ensure every line of code makes a real impact on user comfort.',
        "about.stat1.number": "5+",
        "about.stat1.label": "Real Projects",
        "about.stat2.number": "100+",
        "about.stat2.label": "Code Commits",
        "about.stat3.number": "10+",
        "about.stat3.label": "Technologies",
        "about.vision.label": "Vision & Dedication",
        "about.vision.text": 'Aspiring to build <strong>impactful digital solutions</strong> for various industry needs, and actively contributing to advancing the future ecosystem of web technology.',

        // Skills Section
        "skills.title": "My Skills",
        "skills.programming": "WEB PROGRAMMING",
        "skills.programming.desc": "Building modern interfaces and robust backend architectures for the best user experience.",
        "skills.fokus": "Main Focus (Proficient)",
        "skills.eksplorasi": "Exploration (Familiar)",
        "skills.praktik": "Implementation Practices",
        "skills.web.p1": "Building interactive & <i>reusable</i> UI components",
        "skills.web.p2": "Implementing MVC (Model-View-Controller) architecture patterns",
        "skills.web.p3": "Optimizing asset performance and layout responsiveness",
        "skills.tools": "TOOLS & WORKFLOW",
        "skills.tools.desc": "Using a modern tool ecosystem to ensure productivity, collaboration, and code quality.",
        "skills.kontrol": "Version Control & API",
        "skills.lingkungan": "Development Environment",
        "skills.tools.p1": "Collaborative code management using <i>Git Flow</i> principles",
        "skills.tools.p2": "API <i>endpoint</i> testing for secure system integration",
        "skills.tools.p3": "Utilizing AI <i>tools</i> for <i>debugging</i> acceleration",

        // Projects Section
        "projects.title": "My Projects",
        "projects.detail": "Detail",
        "projects.empty": "No projects to show yet.",

        // Project detail page elements
        "project.back": "Back to Portfolio",
        "project.tech_stack": "Tech Stack",
        "project.flow": "Workflow / Flow Description",
        "project.live_demo": "Live Demo",
        "project.github": "View on GitHub",

        // Dynamic Project details
        "project.title.company-website-eyegil": "Company Website Development - Eyegil.com",
        "project.category.company-website-eyegil": "UI/UX &nbsp;&bull;&nbsp; DIGITAL BUSINESS &nbsp;&bull;&nbsp; Enterprise Solutions Provider &nbsp;&bull;&nbsp; Custom Software Development Services",
        "project.description.company-website-eyegil": "Contributed to building a digital backbone that increases company operational efficiency by up to 40% through integrated systems.",
        "project.flow_description.company-website-eyegil": "This project began with client needs analysis to build a digital platform. The process continued with UI/UX design using Figma, frontend development with Laravel Blade and Bootstrap, and backend integration for content management and customer services.",

        "project.title.umkm-business-tumbuh": "MSME Business Management - TUMBUH",
        "project.category.umkm-business-tumbuh": "UI/UX &nbsp;&bull;&nbsp; DEVELOPMENT &nbsp;&bull;&nbsp; Personal Growth Platform &nbsp;&bull;&nbsp; AI Growth Partner",
        "project.description.umkm-business-tumbuh": "Contributed to an AI experiment that accelerates MSME growth through automated data processing and financial reporting.",
        "project.flow_description.umkm-business-tumbuh": "This application was developed using Next.js for the frontend and Python for the AI backend. Key features include an automated dashboard, real-time financial reports, and AI-based business recommendations. The development process included user research, prototyping, agile development, and testing.",

        "project.title.training-platform-amazain": "Training Platform - AMAZAIN",
        "project.category.training-platform-amazain": "UI/UX &nbsp;&bull;&nbsp; DEVELOPMENT &nbsp;&bull;&nbsp; Training Consultant &nbsp;&bull;&nbsp; Human Capital Development",
        "project.description.training-platform-amazain": "Contributed to developing a digital education platform designed to accelerate the mastery of strategic skills in the digital era.",
        "project.flow_description.training-platform-amazain": "This platform was built with a modular architecture that enables course, user, and certification management. Key features include an enrollment system, interactive learning, progress tracking, and payment gateway integration. Developed using the agile Scrum method.",

        // Contact Section
        "contact.eyebrow": "Contact Me",
        "contact.title": "Get In Touch",
        "contact.subtitle": "Let's work together for your digital solutions.",

        // Footer Section
        "footer.tagline": '"It\'s not about whether you can\'t do it. It\'s about whether you don\'t do it" - Zen',
        "footer.navigation": "NAVIGATION",
        "footer.home": "Home",
        "footer.about": "About",
        "footer.projects": "Projects",
        "footer.skills": "Skills",
        "footer.contact": "Contact",
        "footer.still_making": "Still Building Websites",
        "footer.rights": "All rights reserved.",
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