---
title: Customization
navigation:
  priority: 40
---

# Customization

Create a `docit.json` file in your project root to customize the docs site.

## Site identity

```json
{
  "siteName": "My Project",
  "githubUrl": "https://github.com/username/repo",
  "editBaseUrl": "https://github.com/username/repo/edit/main"
}
```

`siteName` and `githubUrl` are auto-detected from your project's `composer.json` (`name` and `homepage`/`support.source`) if not set explicitly.

## Sidebar order and labels

```json
{
  "order": ["index", "installation", "quick-start", "customization"],
  "labels": {
    "index": "Guide",
    "installation": "Installation",
    "quick-start": "Quick Start",
    "customization": "Customization"
  }
}
```

## Custom containers (cards)

Use `::: card` and `::: card-grid` for card layouts:

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
