=== WS Color Switcher ===
Contributors: webstrategy
Tags: dark mode, light mode, avada, color switcher, css variables
Requires at least: 5.6
Tested up to: 6.8
Requires PHP: 7.4
Stable tag: 1.1.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Dark / light switcher for Avada that remaps CSS variables (--awb-colorN). Floating button, shortcode, anti-FOUC, localStorage persistence.

== Description ==

WS Color Switcher adds a dark / light mode to any Avada site without touching the theme files. It works by remapping the Avada global color variables (`--awb-color1`, `--awb-color2`, ...): you keep your current site as the "Dark" reference and define an override value for each color in "Light" mode.

When a visitor toggles the mode, the plugin swaps every mapped variable at the `html` level. No reload, no flash, no theme edit.

= What it does =

* Maps each Avada CSS color variable to a light-mode override value.
* Adds a floating toggle button (four corner positions) or a `[ws_theme_toggle]` shortcode you can drop anywhere — including an Avada HTML block in the header.
* Injects an anti-FOUC snippet in the page head so the correct mode is applied before first paint.
* Persists the visitor's choice in localStorage, so the mode sticks across pages and visits.
* Lets you pick the default mode shown to new visitors (dark or light).
* Shows the generated CSS in the admin so you can copy it and reuse it elsewhere if needed.

= Built for Avada =

The plugin targets the `--awb-colorN` variables that Avada / Fusion Builder generate from the Global Options palette. The CSS variable prefix is configurable, so it can be adapted to other setups that rely on a numbered CSS-variable color scale.

= WebStrategy =

WS Color Switcher is part of the WebStrategy plugin suite by [wordpress-freelance.com](https://wordpress-freelance.com).

== Installation ==

1. Upload the `ws-switcher-color` folder to `/wp-content/plugins/`, or install the ZIP from Plugins > Add New > Upload.
2. Activate the plugin.
3. Go to Tools > WS Color Switcher.
4. In "Color mappings", set the Light override value for each Avada color variable.
5. In "Settings", choose the default mode, the floating button visibility and its position.
6. Save. The toggle button appears on the frontend, or use the `[ws_theme_toggle]` shortcode wherever you want.

== Frequently Asked Questions ==

= Does it modify my Avada theme? =

No. The plugin only injects CSS that overrides the color variables when light mode is active. Nothing in the theme is touched, and deactivating the plugin restores the original look immediately.

= How do I place the button in my header? =

Disable the floating button in Settings, then add an Avada HTML block (or any block) containing the `[ws_theme_toggle]` shortcode where you want the button to appear.

= Which colors can I remap? =

Any CSS variable that follows the configured prefix and a number, e.g. `--awb-color1` through `--awb-colorN`. The prefix is editable in Settings.

= Will visitors keep their choice between pages? =

Yes. The selected mode is stored in localStorage and re-applied before the page renders, so there is no flash of the wrong theme.

== Screenshots ==

1. Color mappings — define the Light override for each Avada variable.
2. Settings — default mode, button visibility and position.
3. Frontend result — the same content in dark and light, toggled with the floating button or the shortcode.

== Changelog ==

= 1.1.0 =
* Full rewrite on the WPPB architecture (separate admin / public / includes layers).
* Business logic extracted into testable classes (defaults, CSS generator, sanitizer).
* Public assets enqueued unconditionally so the toggle keeps working when placed in a header builder.
* Added internationalization: French source plus English, German and Spanish translations.
* Option keys kept identical to 1.0.0 — upgrade is transparent, no reconfiguration needed.

= 1.0.0 =
* Initial release: dark / light switcher for Avada, floating button, shortcode, anti-FOUC, localStorage persistence.

== Upgrade Notice ==

= 1.1.0 =
Internal rewrite. Your existing mappings and settings are preserved. No action required.
