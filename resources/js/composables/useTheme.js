import { ref, computed } from 'vue'
import { useLocalStorage } from '@vueuse/core'

export function useTheme() {
  const theme = useLocalStorage('theme', 'light')

  const isDark = computed(() => theme.value === 'dark')

  const toggleTheme = () => {
    theme.value = theme.value === 'light' ? 'dark' : 'light'
    updateDocumentClass()
  }

  const setTheme = (newTheme) => {
    theme.value = newTheme
    updateDocumentClass()
  }

  const updateDocumentClass = () => {
    const root = document.documentElement
    if (theme.value === 'dark') {
      root.classList.add('dark')
    } else {
      root.classList.remove('dark')
    }
  }

  // Initialize theme on mount
  if (typeof document !== 'undefined') {
    updateDocumentClass()
  }

  return {
    theme,
    isDark,
    toggleTheme,
    setTheme,
    updateDocumentClass
  }
}
