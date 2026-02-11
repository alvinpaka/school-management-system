<template>
  <button
    @click="toggleTheme"
    :class="cn(
      'inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50',
      'h-10 w-10',
      'hover:bg-accent hover:text-accent-foreground',
      variant === 'outline' && 'border border-input bg-background hover:bg-accent hover:text-accent-foreground',
      variant === 'ghost' && 'hover:bg-accent hover:text-accent-foreground',
      className
    )"
    :aria-label="isDark ? 'Switch to light mode' : 'Switch to dark mode'"
  >
    <Sun 
      v-if="isDark" 
      :class="cn('h-4 w-4', className)"
    />
    <Moon 
      v-else 
      :class="cn('h-4 w-4', className)"
    />
  </button>
</template>

<script setup>
import { Sun, Moon } from 'lucide-vue-next'
import { useTheme } from '@/composables/useTheme'
import { cn } from '@/lib/utils'

defineProps({
  variant: {
    type: String,
    default: 'ghost',
    validator: (value) => ['default', 'outline', 'ghost'].includes(value)
  },
  className: {
    type: String,
    default: ''
  }
})

const { isDark, toggleTheme } = useTheme()
</script>
