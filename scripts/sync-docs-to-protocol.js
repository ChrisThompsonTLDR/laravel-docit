#!/usr/bin/env node
/**
 * Sync docs/*.md to protocol-js/src/app for Protocol UI build.
 * Data source: docs. UI: Protocol.
 */

import fs from 'fs'
import path from 'path'
import { fileURLToPath } from 'url'

const __dirname = path.dirname(fileURLToPath(import.meta.url))
const root = path.resolve(__dirname, '..')
const docsDir = path.join(root, 'docs')
const protocolAppDir = path.join(root, 'protocol-js', 'src', 'app')

// Doc order and labels from config (keep in sync with config/docs.php)
const DOC_ORDER = ['index', 'installation', 'quick-start', 'github-actions', 'customization']
const DOC_LABELS = {
  index: 'Guide',
  'installation': 'Installation',
  'quick-start': 'Quick Start',
  'github-actions': 'GitHub Actions',
  'customization': 'Customization',
}

function extractFrontmatter(content) {
  const match = content.match(/^---\n([\s\S]*?)\n---\n([\s\S]*)$/)
  if (!match) return { frontmatter: {}, body: content }
  const frontmatter = {}
  for (const line of match[1].split('\n')) {
    const [key, ...valParts] = line.split(':')
    if (key) frontmatter[key.trim()] = valParts.join(':').trim()
  }
  return { frontmatter, body: match[2] }
}

function mdToMdx(mdContent, slug) {
  const { frontmatter, body } = extractFrontmatter(mdContent)
  const title = frontmatter.title || slug
  // Convert ::: card Title url \n desc ::: to markdown
  let bodyClean = body
    .replace(/::: card-grid\s*\n?/g, '')
    .replace(/::: card ([^\n]+)\n([\s\S]*?):::/g, (_, titleLink, desc) => {
      const parts = titleLink.trim().split(/\s+/)
      const cardTitle = parts.length > 1 ? parts.slice(0, -1).join(' ') : parts[0]
      const last = parts[parts.length - 1]
      const href = last && (last.startsWith('docs/') || !last.includes('/')) ? '/' + last.replace('docs/', '') : (parts[1] || '#')
      return `\n- **[${cardTitle}](${href})** – ${desc.trim()}\n`
    })
    .replace(/:::\s*\n?/g, '')

  return `export const metadata = {
  title: '${title.replace(/'/g, "\\'")}',
  description: 'Docit documentation',
}

${bodyClean}
`
}

function syncDocs() {
  if (!fs.existsSync(docsDir)) {
    console.error('docs directory not found')
    process.exit(1)
  }

  // Remove Protocol's default pages we're replacing
  const toRemove = ['installation', 'quick-start', 'github-actions', 'customization', 'sdks', 'authentication', 'pagination', 'errors', 'webhooks', 'contacts', 'conversations', 'messages', 'groups', 'attachments', 'quickstart']
  for (const name of toRemove) {
    const p = path.join(protocolAppDir, name)
    if (fs.existsSync(p)) {
      fs.rmSync(p, { recursive: true })
    }
  }

  // Write our docs
  for (const slug of DOC_ORDER) {
    const srcPath = path.join(docsDir, slug === 'index' ? 'index.md' : `${slug}.md`)
    if (!fs.existsSync(srcPath)) continue

    const md = fs.readFileSync(srcPath, 'utf8')
    const mdx = mdToMdx(md, slug)
    const destDir = slug === 'index' ? protocolAppDir : path.join(protocolAppDir, slug)
    fs.mkdirSync(destDir, { recursive: true })
    fs.writeFileSync(path.join(destDir, 'page.mdx'), mdx)
  }

  // Generate navigation config for Protocol
  const navLinks = DOC_ORDER.map(slug => ({
    title: DOC_LABELS[slug] || slug,
    href: slug === 'index' ? '/' : `/${slug}`,
  }))

  const editBaseUrl =
    process.env.DOCIT_EDIT_BASE_URL ||
    'https://github.com/ChrisThompsonTLDR/laravel-docit/edit/main'

  const navConfig = `// Auto-generated from docs - do not edit
export const navigation = [
  {
    title: 'Guides',
    links: ${JSON.stringify(navLinks, null, 6)},
  },
]

export const topLevelNavItems = [
  { href: '/', children: 'Guide' },
  { href: 'https://github.com/ChrisThompsonTLDR/laravel-docit', children: 'GitHub' },
]

export const showSignIn = false

export const editBaseUrl = ${JSON.stringify(editBaseUrl)}

export const siteName = ${JSON.stringify(process.env.DOCIT_SITE_NAME || 'laravel-docit')}
`

  const navPath = path.join(root, 'protocol-js', 'src', 'config', 'docit-navigation.js')
  fs.mkdirSync(path.dirname(navPath), { recursive: true })
  fs.writeFileSync(navPath, navConfig)

  console.log('Synced docs to protocol-js')
}

syncDocs()
