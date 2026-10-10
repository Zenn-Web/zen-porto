// The visitor's "reduce motion" operating-system setting, read once when the page loads.
export const prefersReducedMotion = () =>
    window.matchMedia('(prefers-reduced-motion: reduce)').matches;
