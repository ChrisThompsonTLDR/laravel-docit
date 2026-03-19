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
const projectRoot = process.env.DOCIT_PROJECT_ROOT ? path.resolve(process.env.DOCIT_PROJECT_ROOT) : null
const docsDir = projectRoot
  ? path.join(projectRoot, process.env.DOCIT_DOCS_DIR || 'docs')
  : path.join(root, 'docs')
const protocolAppDir = path.join(root, 'protocol-js', 'src', 'app')

// Doc order and labels: use project config if present, else package defaults
const DOCIT_CONFIG_PATH = projectRoot ? path.join(projectRoot, 'docit.json') : null
let DOC_ORDER = ['index', 'installation', 'quick-start', 'github-actions', 'customization']
let DOC_LABELS = {
  index: 'Guide',
  'installation': 'Installation',
  'quick-start': 'Quick Start',
  'github-actions': 'GitHub Actions',
  'customization': 'Customization',
}
let docitEditBaseUrl = null
let docitSiteName = null
let docitGithubUrl = null

// Auto-detect siteName and githubUrl from host package's composer.json
const composerJsonPath = projectRoot ? path.join(projectRoot, 'composer.json') : null
if (composerJsonPath && fs.existsSync(composerJsonPath)) {
  try {
    const composer = JSON.parse(fs.readFileSync(composerJsonPath, 'utf8'))
    if (composer.name) {
      // Use the package part after the vendor prefix as the site name
      const sepIndex = composer.name.indexOf('/')
      docitSiteName = sepIndex !== -1 ? composer.name.slice(sepIndex + 1) : composer.name
    }
    if (composer.homepage) {
      docitGithubUrl = composer.homepage
    } else if (composer.support && composer.support.source) {
      docitGithubUrl = composer.support.source
    }
  } catch (e) {
    // Silently ignore invalid composer.json; built-in defaults will be used
  }
}

if (DOCIT_CONFIG_PATH && fs.existsSync(DOCIT_CONFIG_PATH)) {
  try {
    const cfg = JSON.parse(fs.readFileSync(DOCIT_CONFIG_PATH, 'utf8'))
    if (cfg.order) DOC_ORDER = cfg.order
    if (cfg.labels) DOC_LABELS = { ...DOC_LABELS, ...cfg.labels }
    if (cfg.editBaseUrl) docitEditBaseUrl = cfg.editBaseUrl
    if (cfg.siteName) docitSiteName = cfg.siteName
    if (cfg.githubUrl) docitGithubUrl = cfg.githubUrl
  } catch (e) {
    // Silently ignore invalid docit.json; previously detected values will be used
  }
} else if (projectRoot && fs.existsSync(docsDir)) {
  // Auto-discover: index first, then rest alphabetically
  const files = fs.readdirSync(docsDir).filter(f => f.endsWith('.md'))
  const slugs = files.map(f => f === 'index.md' ? 'index' : f.replace(/\.md$/, ''))
  const rest = slugs.filter(s => s !== 'index').sort()
  DOC_ORDER = slugs.includes('index') ? ['index', ...rest] : rest
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
  title: ${JSON.stringify(title)},
  description: ${JSON.stringify((process.env.DOCIT_SITE_NAME || docitSiteName || 'Docit') + ' documentation')},
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

  const githubUrl =
    process.env.DOCIT_GITHUB_URL ||
    docitGithubUrl ||
    'https://github.com/ChrisThompsonTLDR/laravel-docit'

  const editBaseUrl =
    process.env.DOCIT_EDIT_BASE_URL ||
    docitEditBaseUrl ||
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
  { href: ${JSON.stringify(githubUrl)}, children: 'GitHub' },
]

export const showSignIn = false

export const editBaseUrl = ${JSON.stringify(editBaseUrl)}

export const siteName = ${JSON.stringify(process.env.DOCIT_SITE_NAME || docitSiteName || 'laravel-docit')}
`

  const navPath = path.join(root, 'protocol-js', 'src', 'config', 'docit-navigation.js')
  fs.mkdirSync(path.dirname(navPath), { recursive: true })
  fs.writeFileSync(navPath, navConfig)

  console.log('Synced docs to protocol-js')
}

syncDocs()
