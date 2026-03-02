---
title: Customization
navigation:
  priority: 40
---

# Customization

Docit uses HydePHP under the hood. Customize via config files in the docit package (or publish them to your project).

## Site identity

In `config/hyde.php` or `.env`:

```
SITE_NAME=My Project
SITE_URL=https://username.github.io/my-repo/
```

## Documentation sidebar

Edit `config/docs.php`:

- **order** – Page identifiers in sidebar order
- **labels** – Custom display names
- **header** – Sidebar title
- **footer** – Markdown link at bottom (e.g. "Back to home")

## Navigation menu

In `config/hyde.php`, the `navigation` key controls the main nav:

```php
'navigation' => [
    'order' => ['index' => 0, 'docs/index' => 100],
    'labels' => ['index' => 'Home', 'docs/index' => 'Documentation'],
    'exclude' => ['404'],
],
```

## Content directories

By default, docs live in `docs/`, pages in `_pages`. These are configured in `config/hyde.php` under `source_directories` and `output_directories`.

## Custom containers (cards)

Docit adds a Markdown extension for VitePress-style custom containers. Use `::: card` and `::: card-grid` for card layouts:

```md
::: card-grid
::: card Installation docs/installation
Composer or monorepo. Add the `docit` script and run `composer docit`.
:::
::: card Quick Start docs/quick-start
Build, configure output dir, and deploy.
:::
:::
```

- **`::: card Title url`** – A single card. Optional `url` makes the card a link.
- **`::: card-grid`** – Wraps cards in a responsive grid (2 cols on sm, 3 on lg).
