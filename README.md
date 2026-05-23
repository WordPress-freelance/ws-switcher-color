# WS Color Switcher

Dark / light switcher for **Avada** that remaps the theme's CSS color variables (`--awb-colorN`) without touching the theme. Floating toggle button, `[ws_theme_toggle]` shortcode, anti-FOUC head snippet, and `localStorage` persistence.

Part of the **WebStrategy** plugin suite — [wordpress-freelance.com](https://wordpress-freelance.com).

## How it works

Your live site is the **Dark** reference. For each Avada color variable you define a **Light** override value. When a visitor toggles the mode, the plugin adds a light class on `<html>` and swaps every mapped variable. No reload, no flash, no theme edit.

## Features

- Per-variable Dark → Light mapping for the `--awb-colorN` scale (prefix configurable).
- Floating toggle button — four corner positions — or the `[ws_theme_toggle]` shortcode.
- Anti-FOUC snippet printed in `<head>` so the right mode is applied before first paint.
- Visitor choice persisted in `localStorage`.
- Configurable default mode for first-time visitors.
- Generated CSS shown in the admin for manual reuse.

## Architecture

WPPB layout, strict separation of concerns:

```
ws-switcher-color/
├── ws-switcher-color.php          # bootstrap + constants
├── uninstall.php                  # option cleanup
├── includes/                      # orchestrator, loader, i18n + pure logic
│   ├── class-ws-switcher-color.php
│   ├── class-ws-switcher-color-loader.php
│   ├── class-ws-switcher-color-i18n.php
│   ├── class-ws-switcher-color-defaults.php
│   ├── class-ws-switcher-color-css-generator.php
│   └── class-ws-switcher-color-sanitizer.php
├── admin/                         # admin page, assets, partials
├── public/                        # frontend toggle, assets, shortcode
├── languages/                     # .pot + en_US / de_DE / es_ES
└── assets/                        # wordpress.org banners, icon, screenshots
```

The business logic (`Defaults`, `CSS_Generator`, `Sanitizer`) is isolated from WordPress so it can be unit-tested in plain PHP.

## Backward compatibility

Option keys are unchanged from 1.0.0 (`ws_switcher_mappings`, `ws_switcher_settings`). Upgrading is transparent.

## Development

Tests run on PHPUnit with WP_Mock. Dependencies live under `vendor/` (provisioned via git clone; `composer install` also works).

```bash
phpunit
```

The suite covers defaults, CSS generation, sanitization, the loader, i18n, and the admin / public controllers.

## Requirements

- WordPress 5.6+
- PHP 7.4+
- Avada (or any theme exposing a numbered CSS-variable color scale)

## License

GPL-2.0-or-later.
