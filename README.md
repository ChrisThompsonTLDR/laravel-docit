# Docit – Documentation Static Site Generator

Docit builds Markdown documentation into a static site using Protocol UI. Output goes to a configurable directory (default `docs/dist`)
—perfect for GitHub Pages, GitLab Pages, or any static hosting.

**Docs:** [christhompsontldr.github.io/laravel-docit](https://christhompsontldr.github.io/laravel-docit)

## Quick Start

Add a `docit` script to your repo or package's `composer.json`:

**Composer-installed docit** (`vendor/christhompsontldr/laravel-docit`):

```json
{
  "scripts": {
    "docit": "php vendor/bin/build-docs"
  }
}
```

**Monorepo** (docit at `packages/laravel-docit`):

```json
{
  "scripts": {
    "docit": "php packages/laravel-docit/bin/build-docs"
  }
}
```

Then run:

```bash
composer docit
```

Or run the bin file directly: `php vendor/bin/build-docs` when installed via Composer, or `php packages/laravel-docit/bin/build-docs` in a monorepo.

## GitHub Actions Integration

Docit includes a reusable workflow for building and deploying docs. Set repo **Settings → Pages → Source** to **GitHub Actions**.

### Option A: Reusable workflow (docit in monorepo)

When docit lives at `packages/laravel-docit` in your repo, add to `.github/workflows/docs.yml`:

```yaml
name: Build and Deploy Docs

on:
  push:
    branches: [main]
  workflow_dispatch:

jobs:
  build:
    uses: ./packages/laravel-docit/.github/workflows/build-docs.yml
    with:
      docit-path: packages/laravel-docit
    permissions:
      contents: write
      pages: write
      id-token: write
```

### Option B: Composer-installed docit

When docit is installed via Composer, create `.github/workflows/docs.yml`:

```yaml
name: Build and Deploy Docs

on:
  push:
    branches: [main]
  workflow_dispatch:

jobs:
  build:
    uses: ChrisThompsonTLDR/laravel-docit/.github/workflows/build-docs.yml@main
    with:
      docit-path: vendor/christhompsontldr/laravel-docit
      base-path: /your-repo-name  # for GitHub Pages project sites
    permissions:
      contents: write
      pages: write
      id-token: write
```

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
