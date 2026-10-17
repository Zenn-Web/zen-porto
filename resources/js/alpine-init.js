// Alpine itself is bundled and started by Livewire (@livewireScripts). This module only
// registers custom behavior; it must never import, expose, or start Alpine.
import { prefersReducedMotion } from './motion';

document.addEventListener('alpine:init', () => {
    // Mobile navbar. `mounted` controls display, `open` controls the visual (.show) state;
    // they are split so the nav-item enter transitions run (display must change a frame
    // earlier than the class) and the exit transition finishes before display is removed.
    window.Alpine.data('navMenu', () => ({
        open: false,
        mounted: false,
        closeTimer: null,

        toggle() {
            this.open ? this.close() : this.openMenu();
        },

        openMenu() {
            clearTimeout(this.closeTimer);
            this.mounted = true;
            this.$nextTick(() => {
                this.$refs.menu.offsetHeight; // force reflow so the transitions start from the closed state
                this.open = true;
                this.syncScrollLock();
            });
        },

        close() {
            if (!this.open && !this.mounted) {
                return;
            }
            this.open = false;
            this.syncScrollLock();
            this.closeTimer = setTimeout(() => {
                this.mounted = false;
                // Announce only after Alpine has applied `display: none`: anchor scrolling measures the
                // navbar height on this event, and the open menu would still be counted before that.
                this.$nextTick(() => window.dispatchEvent(new CustomEvent('nav-closed')));
            }, prefersReducedMotion() ? 0 : 450);
        },

        // Lock page scroll (and Lenis) while the mobile menu is open.
        syncScrollLock() {
            document.body.classList.toggle('mobile-menu-open', this.open);
            if (this.open) {
                window.lenis?.stop();
            } else {
                window.lenis?.start();
            }
        },
    }));

    // Projects carousel (below 992px the project list scrolls sideways and snaps). Only the position
    // controls need script: `active` follows the scroll, `go(i)` scrolls to a project, `prev()`/`next()` step one.
    window.Alpine.data('projectsCarousel', () => ({
        active: 0,

        get count() {
            return this.$refs.track.children.length;
        },

        onScroll() {
            const track = this.$refs.track;
            const items = [...track.children];
            if (items.length < 2) {
                return;
            }
            // The last card can never reach the start edge, so the end of the track means "last".
            if (track.scrollLeft + track.clientWidth >= track.scrollWidth - 2) {
                this.active = items.length - 1;
                return;
            }
            let nearest = 0;
            items.forEach((item, i) => {
                if (Math.abs(item.offsetLeft - track.scrollLeft) < Math.abs(items[nearest].offsetLeft - track.scrollLeft)) {
                    nearest = i;
                }
            });
            this.active = nearest;
        },

        prev() {
            this.go(Math.max(0, this.active - 1));
        },

        next() {
            this.go(Math.min(this.count - 1, this.active + 1));
        },

        go(index) {
            const track = this.$refs.track;
            const item = track.children[index];
            if (item) {
                track.scrollTo({ left: item.offsetLeft, behavior: prefersReducedMotion() ? 'auto' : 'smooth' });
            }
        },
    }));

    // Skills tree: one group is open at a time. On wide screens the role is a hub with the groups on a ring around
    // it, joined by dashed lines drawn here (SVG) from the real positions of the nodes, so they follow the layout,
    // the language and the window size; the line to the open group flows. Below 992px the tree is a pure CSS vertical
    // list and nothing is drawn.
    window.Alpine.data('skillsTree', (initial = 0) => ({
        active: initial,
        observer: null,

        init() {
            this.$nextTick(() => this.draw());
            this.$watch('active', () => this.$nextTick(() => this.draw()));
            if (window.ResizeObserver) {
                this.observer = new ResizeObserver(() => this.draw());
                this.observer.observe(this.$el);
            }
            document.fonts?.ready.then(() => this.draw());
        },

        destroy() {
            this.observer?.disconnect();
        },

        select(index) {
            this.active = index;
        },

        focusGroup(index) {
            const groups = this.$el.querySelectorAll('.skill-node--group');
            groups[Math.max(0, Math.min(groups.length - 1, index))]?.focus();
        },

        draw() {
            const svg = this.$refs.links;
            if (!svg) {
                return;
            }
            svg.replaceChildren();
            if (!window.matchMedia('(min-width: 992px)').matches) {
                return;
            }

            const box = this.$el.getBoundingClientRect();
            svg.setAttribute('viewBox', `0 0 ${box.width} ${box.height}`);

            const centre = (el) => {
                const r = el.getBoundingClientRect();
                return [r.left + r.width / 2 - box.left, r.top + r.height / 2 - box.top];
            };
            // One straight line from the middle of the hub to the middle of each group. The nodes sit above the
            // lines, so only the stretch between them shows. The line to the open group flows.
            const hub = centre(this.$refs.root);
            this.$el.querySelectorAll('.skill-node--group').forEach((group, i) => {
                const to = centre(group);
                const path = document.createElementNS('http://www.w3.org/2000/svg', 'path');
                path.setAttribute('d', `M${hub[0]},${hub[1]} L${to[0]},${to[1]}`);
                path.setAttribute('class', 'skill-link' + (i === this.active ? ' is-active' : ''));
                svg.appendChild(path);
            });
        },
    }));

    window.Alpine.store('lang', {
        current: document.documentElement.getAttribute('lang') || 'id',
        toggle() {
            const newLang = this.current === 'id' ? 'en' : 'id';
            this.current = newLang;

            // Ganti teks semua elemen bertranslasi. Nilai data-i18n-* bisa berasal dari
            // database, jadi SELALU ditulis sebagai teks biasa (textContent), tidak
            // pernah di-parse sebagai HTML.
            document.querySelectorAll('[data-i18n-id]').forEach(el => {
                const attr = newLang === 'en' ? 'data-i18n-en' : 'data-i18n-id';
                const val = el.getAttribute(attr);
                if (val !== null) {
                    el.textContent = val;
                }
            });

            // Allowlist terjemahan statis yang memang berisi markup (mis. <strong>):
            // kedua varian bahasa sudah dirender server-side dari file lang,
            // di sini hanya ditukar visibilitasnya.
            document.querySelectorAll('[data-i18n-lang]').forEach(el => {
                el.hidden = el.getAttribute('data-i18n-lang') !== newLang;
            });

            // Update lang attribute di <html>
            document.documentElement.setAttribute('lang', newLang);

            // Update label tombol toggle (ID ↔ EN)
            const langLabel = document.querySelector('#lang-toggle .lang-label');
            if (langLabel) {
                langLabel.textContent = newLang === 'en' ? 'ID' : 'EN';
            }

            // Paksa Lenis resize untuk re-sync scroll position setelah konten berubah
            if (window.lenis && typeof window.lenis.resize === 'function') {
                window.lenis.resize();
            }

            // Sync ke server (fire-and-forget, tidak menunggu response)
            const token = document.querySelector('meta[name="csrf-token"]')?.content ?? '';
            fetch(`/lang/${newLang}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': token,
                    'Accept': 'application/json'
                }
            }).then(() => {
                // The language is stored in the session now: let Livewire components re-render in it.
                window.Livewire?.dispatch('locale-changed');
            }).catch(() => {});
        }
    });
});
