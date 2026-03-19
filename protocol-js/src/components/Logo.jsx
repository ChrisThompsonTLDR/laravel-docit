import { siteName } from '@/config/docit-navigation'

export function Logo(props) {
  return (
    <span className="text-lg font-semibold text-zinc-900 dark:text-white" {...props}>
      {siteName}
    </span>
  )
}
