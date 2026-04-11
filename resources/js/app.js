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
        threshold: 0.15,
        rootMargin: "0px 0px -50px 0px"
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const container = entry.target;
                container.classList.add('active');

                // Seleksi semua elemen yang ingin dianimasikan
                const elements = container.querySelectorAll(
                    'h1, h2, h3, p, .card, .row, .animate-text, .animate-buttons, ' +
                    '.detail-item, .contact-socials, .form-group, .button-wrapper, .image-sweep, ' +
                    '.animate-on-scroll, .profile-card-horizontal'
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
                { clipPath: 'inset(0 100% 0 0)', opacity: 0, visibility: 'visible' },
                { clipPath: 'inset(0 0% 0 0)',   opacity: 1, visibility: 'visible' }
            ];
            options.duration = 1000;
            options.easing = 'cubic-bezier(0.77, 0, 0.175, 1)';
        }
        // Animasi STANDAR untuk Teks & Card (Fade Up)
        else {
            keyframes = [
                { opacity: 0, transform: 'translateY(30px)', visibility: 'hidden' },
                { opacity: 1, transform: 'translateY(0)',    visibility: 'visible' }
            ];
        }

        const animation = el.animate(keyframes, options);

        // Kunci posisi akhir agar tidak reset setelah animasi selesai
        animation.onfinish = () => {
            el.style.opacity = '1';
            el.style.visibility = 'visible';

            if (el.classList.contains('image-sweep')) {
                el.style.clipPath = 'inset(0 0% 0 0)';
            } else {
                el.style.transform = 'translateY(0)';
            }
        };
    }

});