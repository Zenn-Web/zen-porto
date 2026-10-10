/**
 * detail-animations.js
 * GSAP + ScrollTrigger powered animations for the Split-Screen Detail Project Page.
 * Loaded only on the detail page via @push('scripts').
 */
import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';
import { prefersReducedMotion } from './motion';

gsap.registerPlugin(ScrollTrigger);

document.addEventListener('DOMContentLoaded', () => {

    // Guard: only run on detail page
    if (!document.querySelector('.dp-split-section')) return;

    // "Reduce motion": no animation, just the end state the animations would have reached.
    if (prefersReducedMotion()) {
        gsap.set('[data-dp-anim]', { opacity: 1, x: 0, y: 0 });
        gsap.set('.dp-narrative-timeline-track', { scaleY: 1 });
        gsap.set('.dp-narrative-timeline-dot', { opacity: 1 });
        return;
    }

    // ═══════════════════════════════════
    // 1. SIDEBAR ENTRANCE — Cinematic Fade Up Stagger
    // ═══════════════════════════════════
    const sidebarItems = document.querySelectorAll('.dp-sidebar-sticky .dp-sidebar-element');
    
    if (sidebarItems.length) {
        gsap.fromTo(sidebarItems,
            { opacity: 0, y: 24 },
            {
                opacity: 1,
                y: 0,
                duration: 0.8,
                ease: 'power3.out',
                stagger: 0.12,
                delay: 0.15
            }
        );
    }

    // ═══════════════════════════════════
    // 2. SHOWCASE IMAGE ENTRANCE
    // ═══════════════════════════════════
    const showcaseWrap = document.querySelector('.dp-showcase-wrap');
    
    if (showcaseWrap) {
        gsap.fromTo(showcaseWrap,
            { opacity: 0, x: -30 },
            {
                opacity: 1,
                x: 0,
                duration: 0.95,
                ease: 'power3.out',
                delay: 0.2
            }
        );
    }

    // ═══════════════════════════════════
    // 3. NARRATIVE — Timeline drawing on scroll
    // ═══════════════════════════════════
    const narrativeTrack = document.querySelector('.dp-narrative-timeline-track');
    const narrativeDot = document.querySelector('.dp-narrative-timeline-dot');

    if (narrativeTrack) {
        // Draw timeline track scaleY on scroll (scrubbed)
        gsap.to(narrativeTrack, {
            scaleY: 1,
            ease: 'none',
            scrollTrigger: {
                trigger: '.dp-narrative-body',
                start: 'top 75%',
                end: 'bottom 60%',
                scrub: 1.2,
            }
        });

        // Trigger dot to fade in
        gsap.to(narrativeDot, {
            opacity: 1,
            duration: 0.4,
            ease: 'power2.out',
            scrollTrigger: {
                trigger: '.dp-narrative-body',
                start: 'top 75%',
                toggleActions: 'play none none none',
            }
        });
    }

    // Narrative header entrance
    const narrativeHeader = document.querySelector('.dp-narrative-header');
    if (narrativeHeader) {
        gsap.fromTo(narrativeHeader,
            { opacity: 0, y: 20 },
            {
                opacity: 1,
                y: 0,
                duration: 0.7,
                ease: 'power2.out',
                scrollTrigger: {
                    trigger: narrativeHeader,
                    start: 'top 85%',
                    toggleActions: 'play none none none',
                }
            }
        );
    }

    // Narrative body text entrance
    const narrativeText = document.querySelector('.dp-narrative-text');
    if (narrativeText) {
        gsap.fromTo(narrativeText,
            { opacity: 0, y: 15 },
            {
                opacity: 1,
                y: 0,
                duration: 0.8,
                ease: 'power2.out',
                scrollTrigger: {
                    trigger: narrativeText,
                    start: 'top 80%',
                    toggleActions: 'play none none none',
                }
            }
        );
    }



    // ═══════════════════════════════════
    // 4. REFRESH ScrollTrigger on resize
    // ═══════════════════════════════════
    let resizeTimeout;
    window.addEventListener('resize', () => {
        clearTimeout(resizeTimeout);
        resizeTimeout = setTimeout(() => {
            ScrollTrigger.refresh();
        }, 250);
    });

});
