'use client'

import { usePathname } from 'next/navigation'

import { editBaseUrl } from '@/config/docit-navigation'

export function EditPageLink() {
  const pathname = usePathname()
  const slug = pathname === '/' ? 'index' : pathname.replace(/^\//, '')
  const editUrl = `${editBaseUrl}/docs/${slug}.md`

  return (
    <a
      href={editUrl}
      target="_blank"
      rel="noopener noreferrer"
      className="inline-flex items-center gap-1.5 text-sm text-zinc-600 transition hover:text-zinc-900 dark:text-zinc-400 dark:hover:text-white"
    >
      <svg
        viewBox="0 0 20 20"
        fill="none"
        stroke="currentColor"
        strokeWidth="1.5"
        strokeLinecap="round"
        strokeLinejoin="round"
        className="h-4 w-4"
        aria-hidden
      >
        <path d="M11 4H4a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2v-7" />
        <path d="M14.5 2.5a2.121 2.121 0 0 1 3 3L12 10l-4 1 1-4 4.5-4.5Z" />
      </svg>
      Edit this page
    </a>
  )
}
