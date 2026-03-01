---
title: Customization
navigation:
  priority: 40
  group: Configuration
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

By default, docs live in `_docs`, pages in `_pages`. These are configured in `config/hyde.php` under `source_directories` and `output_directories`.
