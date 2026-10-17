# Archive Ochre Palette Design

**Status:** Approved direction; implementation not started
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

## Implementation boundary

The implementation pass will update the shared token foundation and the existing references in navbar, hero, buttons, skills, projects, contact, footer, about, and detail-project styles. It will not change layout, typography, content, routes, animation behavior, or the selected project technology colors.

Verification should cover:

- Light and dark theme screenshots of the homepage and project detail page.
- CTA and link contrast, especially ochre text on paper and dark ink on ochre.
- Existing theme toggle behavior.
- Existing automated test suite plus a production asset build.

