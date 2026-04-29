import './bootstrap';

document.addEventListener("DOMContentLoaded", () => {

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


    // 2. LOGIKA ANIMASI (Intersection Observer)
    const observerOptions = {
        threshold: 0.1, // Diperkecil agar lebih sensitif
        rootMargin: "0px 0px -50px 0px"
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const container = entry.target;
                container.classList.add('active');

                // Seleksi semua elemen yang ingin dianimasikan
                const elements = container.querySelectorAll(
                    'h1, h2, h3, h4, h5, h6, p, ul, .card, .animate-text, .animate-buttons, ' +
                    '.detail-item, .contact-socials, .form-group, .button-wrapper, .image-sweep, ' +
                    '.animate-on-scroll, .profile-card-horizontal, .hero-location-badge, .badge-tech, .resource-img'
                );

                elements.forEach((el, index) => {
                    animateElement(el, index);
                });

                // Berhenti mengamati section ini setelah animasi jalan
                observer.unobserve(container);
            }
        });
    }, observerOptions);

    // Mulai mengamati semua section
    document.querySelectorAll('section').forEach(section => {
        observer.observe(section);
    });


    // 3. FUNGSI INTI ANIMASI
    function animateElement(el, index) {

        // Cegah animasi dobel pada elemen yang sama
        if (el.classList.contains('animated')) return;
        el.classList.add('animated');

        let keyframes;
        let options = {
            duration: 800,
            delay: index * 100, // Efek muncul bergantian (stagger)
            fill: 'forwards',
            easing: 'cubic-bezier(0.22, 1, 0.36, 1)'
        };

        // Animasi KHUSUS untuk Foto (Sweep / Tirai Terbuka)
        if (el.classList.contains('image-sweep')) {
            keyframes = [
                { clipPath: 'inset(0 100% 0 0)', WebkitClipPath: 'inset(0 100% 0 0)', opacity: 0 },
                { clipPath: 'inset(0 0% 0 0)', WebkitClipPath: 'inset(0 0% 0 0)', opacity: 1 }
            ];
            options.duration = 1000;
            options.easing = 'cubic-bezier(0.77, 0, 0.175, 1)';
        }
        // Animasi STANDAR untuk Teks & Card (Fade Up)
        else {
            keyframes = [
                { opacity: 0, transform: 'translateY(20px)' },
                { opacity: 1, transform: 'translateY(0)' }
            ];
        }

        const animation = el.animate(keyframes, options);

        // Kunci posisi akhir dan tambahkan class penanda selesai
        animation.onfinish = () => {
            el.classList.add('animation-finished');
            el.style.opacity = '1';
            el.style.visibility = 'visible';

            if (el.classList.contains('image-sweep')) {
                el.style.clipPath = 'inset(0 0% 0 0)';
                el.style.webkitClipPath = 'inset(0 0% 0 0)';
            } else {
                el.style.transform = 'translateY(0)';
            }
        };
    }

    // 4. MOBILE MENU ENHANCEMENT
    const navbarCollapse = document.getElementById('mainNavbar');
    const body = document.body;

    if (navbarCollapse) {
        navbarCollapse.addEventListener('show.bs.collapse', () => {
            body.style.overflow = 'hidden';
            body.classList.add('mobile-menu-open');
        });

        navbarCollapse.addEventListener('hide.bs.collapse', () => {
            body.style.overflow = '';
            body.classList.remove('mobile-menu-open');
        });
        
        const navLinks = navbarCollapse.querySelectorAll('.nav-link');
        navLinks.forEach(link => {
            link.addEventListener('click', () => {
                const bsCollapse = bootstrap.Collapse.getInstance(navbarCollapse);
                if (bsCollapse) {
                    bsCollapse.hide();
                }
            });
        });
    }

    // 5. AUTO-SCROLL TO CONTACT ON ERROR/SUCCESS
    const hasErrors = document.querySelector('.is-invalid');
    const hasSuccess = document.querySelector('.alert-success');
    
    if (hasErrors || hasSuccess) {
        // Gunakan timeout sedikit agar tidak tabrakan dengan loading awal
        setTimeout(() => {
            const contactSection = document.getElementById('contact');
            if (contactSection) {
                contactSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        }, 300);
    }

    // 6. THEME TOGGLE (Dark Mode)
    const themeToggle = document.getElementById('theme-toggle');
    const currentTheme = localStorage.getItem('theme') || 'light';

    // Apply current theme on load
    if (currentTheme === 'dark') {
        document.documentElement.setAttribute('data-theme', 'dark');
    }

    if (themeToggle) {
        themeToggle.addEventListener('click', () => {
            const isDark = document.documentElement.getAttribute('data-theme') === 'dark';
            const newTheme = isDark ? 'light' : 'dark';
            
            document.documentElement.setAttribute('data-theme', newTheme);
            localStorage.setItem('theme', newTheme);
        });
    }

});