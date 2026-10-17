# Archive Ochre Palette Design

**Status:** Approved direction; refined 2026-10-17 (see "Decisions of 2026-10-17"); implementation not started
**Date:** 2026-10-04
**Scope:** Light/dark theme color tokens and their use across the existing portfolio UI

## Context

The current emerald accent (`#0FB9A7`) reads as bright cyan beside the existing white background and black typography. It feels disconnected from the portfolio's serif/editorial direction and is visually common in modern SaaS interfaces.

The selected direction is **Archive Ochre**: a restrained, slightly aged yellow inspired by archival labels, old posters, and industrial print. It keeps a light mode but makes the accent feel intentional and less generic.

## Visual direction

- **Mood:** underground editorial, independent studio, tactile, understated
- **Base:** warm paper rather than pure white
- **Ink:** near-black rather than absolute black for a softer print feel
- **Accent:** muted ochre used as a signal/marker, not as a large-area fill
- **Shape/interaction:** preserve the existing layout and behavior; this change is a color-system pass, not a visual redesign

## Color tokens

### Light mode

| Token | Value | Intended use |
| --- | --- | --- |
| `--bg-primary` | `#E8E3D5` | Main page background / paper |
| `--bg-secondary` | `#F1EEE6` | Section contrast and soft surfaces |
| `--bg-card` | `#F7F4EC` | Cards and elevated content |
| `--text-primary` | `#171714` | Headings, primary copy, strong controls |
| `--text-secondary` | `#5D5B52` | Body copy and supporting text |
| `--text-muted` | `#817D70` | Metadata and low-emphasis labels |
| `--border-primary` | `#171714` | Intentional high-contrast borders |
| `--border-light` | `#E0DACC` | Subtle separators |
| `--border-dim` | `#D4CCB8` | Card and field borders |
| `--accent-primary` | `#B59A3A` | CTA, active state, markers, links |
| `--accent-primary-light` | `#D8C98F` | Soft accent surfaces |
| `--accent-primary-deep` | `#786522` | Hover states and accent text |
| `--accent-primary-rgb` | `181, 154, 58` | Alpha backgrounds and shadows |

### Dark mode

Dark mode keeps the same identity but raises the accent slightly for contrast instead of reusing the light-mode ochre unchanged.

| Token | Value | Intended use |
| --- | --- | --- |
| `--bg-primary` | `#11110F` | Main dark background |
| `--bg-secondary` | `#1C1B17` | Section contrast |
| `--bg-card` | `#242219` | Cards and elevated content |
| `--text-primary` | `#F1EEE5` | Headings and primary copy |
| `--text-secondary` | `#D0CAB9` | Body copy |
| `--text-muted` | `#AAA38F` | Metadata |
| `--border-primary` | `#4A4330` | Borders and controls |
| `--border-light` | `#29271F` | Subtle separators |
| `--border-dim` | `#3B3628` | Card borders |
| `--accent-primary` | `#C9AF55` | CTA, active state, markers |
| `--accent-primary-light` | `#E0D091` | Soft accent surfaces |
| `--accent-primary-deep` | `#A88D36` | Hover states |
| `--accent-primary-rgb` | `201, 175, 85` | Alpha backgrounds and shadows |

## Usage rules

1. Use the ochre accent for actions, active navigation, small dividers, category labels, icons, and selected states.
2. Use dark ink for text on ochre buttons; do not use white text on the light-mode ochre button.
3. Do not use ochre for long paragraphs or large page backgrounds. It should behave like a marker.
4. Keep gradients out of the core palette. A flat accent is part of the underground/editorial character.
5. Preserve the existing dark-mode toggle and component structure.
6. Use semantic `accent-primary` tokens in new or touched styles. Existing `accent-emerald` references should be migrated in the focused palette pass so the code no longer describes the wrong visual identity.

## Decisions of 2026-10-17

Made after measuring contrast and rendering the palette over the real pages (an injected-CSS simulation; no project files were changed).

1. **Token names.** `--accent-emerald*` becomes `--accent-primary*` everywhere (about 80 references in 14 files) and the roughly 40 hard-coded green hex/rgba values move to the tokens. The old names are not kept as aliases.
2. **Gradients.** The three green gradients in `base.css` become flat ochre, per usage rule 4.
3. **Status dot.** The green "available" dot in the hero (`#10b981`, `hero.css`) stays green: it is a status signal, not a brand accent. Project technology colours stay untouched.
4. **Coloured text on light mode uses the deep ochre.** Ochre `#B59A3A` on paper measures 2.1:1, so it is limited to non-text uses (buttons, dividers, icons, markers, focus rings). Links, labels and other coloured text use `--accent-primary-deep`. Dark mode keeps the plain accent (7 to 9:1).
5. **Contrast corrections to the tables above** (WCAG AA, 4.5:1 for text):
   - light `--accent-primary-deep` `#786522` (4.45:1 on paper) becomes `#6F5E1F` (about 5:1);
   - light `--text-muted` `#817D70` (3.2:1 on paper) becomes `#645F53` (about 5:1).
6. **Paper background in full.** Light `--bg-primary` is `#E8E3D5` as specified, not a lighter cream. Because the cream page makes the existing pale borders vanish, light-mode input fields and the email/WhatsApp contact pills get a darker border (to be chosen during implementation; at least 3:1 against the paper for the field outline).
7. **Hard-coded slate colours** in the footer and dark-mode overrides (for example `#94a3b8` footer links) move to the warm tokens, otherwise they stay blue against the warm dark theme.

## Implementation boundary

The implementation pass will update the shared token foundation and the existing references in navbar, hero, buttons, skills, projects, contact, footer, about, and detail-project styles. It will not change layout, typography, content, routes, animation behavior, or the selected project technology colors.

Verification should cover:

- Light and dark theme screenshots of the homepage and project detail page.
- CTA and link contrast, especially deep-ochre text on paper and dark ink on ochre.
- No leftover teal or green: a search of `resources/` for the old hex/rgba values and `accent-emerald` finds only the hero status dot.
- Form fields and contact pills are clearly visible in light mode; footer links are warm, not blue, in dark mode.
- Existing theme toggle behavior.
- Existing automated test suite plus a production asset build.

