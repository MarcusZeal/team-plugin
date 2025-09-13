# Team People Grid (ACF)

WordPress plugin that renders a sortable/filterable team grid with hover overlays and full-screen modals rendered in the footer (outside builder wrappers). Built to integrate with ACF Pro for custom fields.

## Features

- Custom Post Type: `tp_person`
- Custom taxonomies: `tp_role` (hierarchical), `tp_location`, `tp_focus`
- ACF fields: headshot, specialty, title/role label, short intro, biography, social links repeater
- Shortcode: `[team_grid]` with filter pills (role taxonomy) and optional tabs (location taxonomy)
- Hover overlay with quick info and “open” button
- Modal with name, specialty, socials, focus tags, and bio; prev/next navigation; Escape/arrow key support
- Templatable: copy files from `templates/` into `yourtheme/team-plugin/`
- Theming with CSS variables (scoped to `.tp-theme`)
- Font Awesome automatically enqueued (skips if another FA is detected)

## Shortcode

`[team_grid columns="3" role_taxonomy="tp_role" tab_taxonomy="tp_location" focus_taxonomy="tp_focus" orderby="menu_order" order="ASC" posts_per_page="-1" include="" exclude=""]`

### Attributes

- `columns`: Number of grid columns (2–4 typical)
- `role_taxonomy`: Taxonomy used for the filter pills (default `tp_role`)
- `tab_taxonomy`: Optional taxonomy used for the top tabs (default none)
- `focus_taxonomy`: Taxonomy used for tag chips within modal (default `tp_focus`)
- `orderby`/`order`: WP_Query sorting (defaults support menu order)
- `posts_per_page`: `-1` for all
- `include`/`exclude`: Comma-separated post IDs

## ACF

Requires ACF Pro for fields. The plugin auto-registers a field group on `tp_person`. If ACF is not activated, the grid still works with core fields (title, featured image, content) but ACF fields will be absent.

Social Links repeater now uses a `Platform` dropdown (LinkedIn, X/Twitter, GitHub, Website, Email, etc.) and the plugin renders the correct Font Awesome icon automatically. Optional `Label` can override screen-reader text.

If you prefer to manage fields in the ACF UI, you can disable the programmatic group by removing the `acf/init` hook in `includes/class-tp-plugin.php`.

## Taxonomies

- Create terms in:
  - `Team Roles (tp_role)` – e.g. Investors, Early Stage, Late Stage, Specialists
  - `Team Locations (tp_location)` – e.g. Global, Bay Area, London, Bangalore, Emeritus
  - `Focus Areas (tp_focus)` – e.g. AI, Cloud / SaaS, Security

Assign relevant terms on each person.

## Templates

Override by copying from `templates/` to `yourtheme/team-plugin/` and editing:

- `filter-bar.php` – tabs and filter pills
- `card.php` – grid card + hover overlay
- `modal.php` – footer modal content
- `no-results.php`

## Accessibility

- Semantics: list role for grid, dialog role for modal
- Keyboard: Escape to close; arrows for prev/next
- Focus management can be enhanced per project requirements

## Notes

- Modals render in `wp_footer` via `TP_Modal_Registry::print_all()` to avoid page-builder containers.
- Scripts/styles are enqueued only when the shortcode or modals are present.
- Font Awesome: override or disable via filters:
  - `add_filter( 'tp/fontawesome_src', fn() => 'https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6/css/all.min.css' );`
  - `add_filter( 'tp/enqueue_fontawesome', '__return_false' );`

## Theming (CSS Variables)

All UI colors/spacing/fonts are driven by variables scoped to the wrapper (`.tp-theme`). Override any of these in your theme or page builder:

```
.tp-theme{
  --tp-font: Inter, ui-sans-serif, system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
  --tp-text: #111;               /* text color */
  --tp-muted: #555;              /* muted text */
  --tp-bg: #fff;                 /* surfaces */
  --tp-card-bg: #fafafa;         /* cards */
  --tp-border: #e6e6e6;          /* borders */
  --tp-accent: #111;             /* primary accent */
  --tp-accent-soft: #c5d1ba;     /* soft accent (CTA button) */
  --tp-pill-bg: #f7f7f7;         /* filter pill */
  --tp-pill-text: #111;
  --tp-pill-bg-active: #111;
  --tp-pill-text-active: #fff;
  --tp-tab-muted: #9aa0a6;       /* top tabs inactive */
  --tp-tab-active: #111;         /* top tabs active */
  --tp-tab-underline: #111;      /* top tabs underline */
  --tp-overlay-start: rgba(0,0,0,.08); /* hover overlay gradient */
  --tp-overlay-end: rgba(0,0,0,.55);
  --tp-overlay-blur: blur(3px);  /* set to 'none' to disable */
  --tp-overlay-text: #fff;
  --tp-radius: 14px;             /* card radius */
  --tp-radius-lg: 16px;          /* modal radius */
  --tp-shadow: 0 10px 30px rgba(0,0,0,.25);
  --tp-gap: 20px;                /* grid gap */
  --tp-pad: 16px;                /* spacing */
  --tp-modal-backdrop: rgba(0,0,0,.55);
  --tp-button-bg: #c5d1ba;       /* overlay button */
  --tp-button-text: #111;
}
```
