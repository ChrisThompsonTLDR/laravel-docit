# Docit – Documentation Static Site Generator

Docit is a HydePHP-based static site generator for building documentation sites. It builds Markdown content into a static site and copies the output to your project's `docs/` directory.

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

Docit includes a reusable workflow for building and deploying docs. This repo uses it to build its own docs (see `.github/workflows/docs.yml`). Choose one of the following:

### Option A: Reusable workflow (docit in monorepo)

When docit lives at `packages/laravel-docit` in your repo, add this to `.github/workflows/docs.yml`:

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
      commit: true
```

### Option B: Copy workflow to root (Composer-installed docit)

When docit is installed via Composer at `vendor/christhompsontldr/laravel-docit`, copy the workflow logic into your root `.github/workflows/docs.yml`:

```yaml
name: Build and Deploy Docs

on:
  push:
    branches: [main]
  workflow_dispatch:

jobs:
  build:
    runs-on: ubuntu-latest
    permissions:
      contents: write
    steps:
      - uses: actions/checkout@v4
      - uses: shivammathur/setup-php@v2
        with:
          php-version: '8.2'
      - uses: actions/setup-node@v4
        with:
          node-version: '20'
      - name: Build docs
        run: php vendor/christhompsontldr/laravel-docit/bin/build-docs
      - name: Add .nojekyll
        run: touch docs/.nojekyll
      - name: Commit docs
        uses: EndBug/add-and-commit@v9
        with:
          add: docs
          message: Build docs site
```

---

# HydePHP - Elegant and Powerful Static Site Generator

[![Latest Version on Packagist](https://img.shields.io/packagist/v/hyde/framework?include_prereleases)](https://packagist.org/packages/hyde/framework)
[![Total Downloads on Packagist](https://img.shields.io/packagist/dt/hyde/framework)](https://packagist.org/packages/hyde/framework)
[![License MIT](https://img.shields.io/github/license/hydephp/hyde)](https://github.com/hydephp/hyde/blob/master/LICENSE.md)
[![Test Coverage](https://codecov.io/gh/hydephp/develop/branch/master/graph/badge.svg?token=G6N2161TOT)](https://codecov.io/gh/hydephp/develop)
[![Test Suite](https://github.com/hydephp/develop/actions/workflows/continuous-integration.yml/badge.svg)](https://github.com/hydephp/develop/actions/workflows/continuous-integration.yml)


## Make static websites, blogs, and documentation pages with the tools you already know and love.

### About HydePHP

HydePHP is a content-first Laravel-powered console application that allows you to create static HTML pages, blog posts, and documentation sites,
using Markdown.

Build sites in record-time with a full batteries-included TailwindCSS frontend that just works without any fuss.

### Speed & simplicity first, full control when you need it.

Hyde is all about letting you get started quickly by giving you a full-featured frontend starter kit, while also giving you the power and freedom of doing things the way you want to.

Markdown-first, no Blade required.
Hyde comes with hand-crafted frontend templates, so you can focus on your content.
You don't _need_ to customize anything. But you _can_ customize everything.

See the documentation and learn more at https://hydephp.com/docs


## Features

### Content Creation

- Create blog posts using Markdown and Front Matter.
- Create documentation pages from plain Markdown, no front matter needed!
- Create pages using Markdown.
- You can scaffold blog posts and Markdown pages to automatically fill in the front matter.

### Built-in Frontend

- Hyde comes with a TailwindCSS starter kit, so you can start making content right away.
- The starter kit is fully responsive, has a dark mode theme, and is customizable.
- The frontend is accessible to screen-readers and rich with semantic HTML and microdata.
- Hyde automatically chooses the right layout to use depending on the content being rendered.
- Hyde also fills in and creates content like navigation menus and sidebars automatically.

### Easy Asset Managing

- The Hyde starter comes with [HydeFront](https://github.com/hydephp/hydefront) to serve the base stylesheet and JavaScript through the jsDelivr CDN.
- Hyde ships with precompiled and minified TailwindCSS styles in the app.css file, you can also load them through the CDN.
- This means that all the styles you need are already installed. However, if you want to customize the included Tailwind config, or if you add new Tailwind classes through Markdown or HTML, you can simply run the `npm run build` command to recompile the styles using the pre-configured Tailwind and Vite setup.

### Customization

- You don't need to configure anything as Hyde is shipped with sensible defaults.
- You can, however, customize nearly everything. Here are just a few out of many examples:
- All frontend components and page layouts use Hyde's bundled views, which you
  can publish and customize, just like in Laravel.
- Override many of the dynamic content features like the menus and footer.


## Getting Started - High-level overview

> See [Installation Guide](https://hydephp.com/docs/1.x/installation) and [Getting Started](https://hydephp.com/docs/1.x/getting-started) for the full details.

It's a breeze to get started with Hyde. Create a new Hyde project using Composer:

```bash
composer create-project hyde/hyde
```

Next, place your Markdown files in one of the content directories: `_posts`, `_docs`, and `_pages` (which also accepts HTML). You can also use the `hyde:make` commands to scaffold them.

When you're ready, run the build command to compile your static site which will save your HTML files in the `_site` directory.

```bash
php hyde build
```


## Resources

### Changelog

Please see [CHANGELOG](https://github.com/hydephp/develop/blob/master/CHANGELOG.md) for more information on what has changed recently.

### Contributing

HydePHP is an open-source project, contributions are very welcome!

Development is made in the HydePHP Monorepo, which you can find here https://github.com/hydephp/develop.

### Security

If you discover any security-related issues, please email emma@desilva.se instead of using the issue tracker,
or use the GitHub [Security Advisory](https://github.com/hydephp/develop/security/advisories) page.
All vulnerabilities will be promptly addressed.

### Credits

-   [Emma De Silva](https://github.com/emmadesilva), feel free to buy me a coffee! https://www.buymeacoffee.com/caen
-   [All Contributors](../../contributors)

### License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
