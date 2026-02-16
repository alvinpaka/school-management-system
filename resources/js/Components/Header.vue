<script setup>
import { computed, ref } from 'vue';
import { usePage, router } from '@inertiajs/vue3';
import { 
    Bell,
    User as UserIcon
} from 'lucide-vue-next';
import { Button } from '@/components/ui/button';
import { Badge } from '@/components/ui/badge';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuSeparator,
    DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';
import DarkModeToggle from '@/Components/DarkModeToggle.vue';

const page = usePage();
const user = computed(() => page.props.auth.user);

const isAdmin = computed(() => user.value?.roles?.includes('admin'));
const isTeacher = computed(() => user.value?.roles?.includes('teacher'));
const isStudent = computed(() => user.value?.roles?.includes('student'));

const userRole = computed(() => {
  if (isAdmin.value) return 'Administrator';
  if (isTeacher.value) return 'Teacher';
  if (isStudent.value) return 'Student';
  return 'User';
});
</script>

<template>
  <header class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 px-6 py-4">
    <div class="flex items-center justify-between">
      <!-- Left Side - Page Title -->
      <div class="flex items-center space-x-4">
        <h1 class="text-xl font-semibold text-gray-900 dark:text-white">
          <slot name="header-title" />
        </h1>
      </div>
      
      <!-- Right Side - Actions -->
      <div class="flex items-center space-x-4">
        <!-- Notifications -->
        <div class="relative">
          <Button variant="ghost" size="sm" class="relative p-2">
            <Bell class="w-5 h-5 text-gray-500 dark:text-gray-400" />
            <Badge class="absolute -top-1 -right-1 h-5 w-5 rounded-full p-0 flex items-center justify-center text-xs bg-red-500 text-white border-0">
              3
            </Badge>
          </Button>
        </div>
        
        <!-- Dark Mode Toggle -->
        <DarkModeToggle variant="ghost" />
        
        <!-- User Dropdown -->
        <DropdownMenu>
          <DropdownMenuTrigger as-child>
            <Button variant="ghost" size="sm" class="flex items-center space-x-2">
              <div class="w-14 h-14 rounded-full overflow-hidden bg-gray-200 dark:bg-gray-700 border-2 border-gray-300 dark:border-gray-600">
                <img 
                  v-if="user?.photo" 
                  :src="`/storage/${user.photo}`" 
                  :alt="user?.name"
                  class="w-full h-full object-cover"
                />
                <div v-else class="flex items-center justify-center h-full">
                  <UserIcon class="w-4 h-4 text-gray-400" />
                </div>
              </div>
              <div class="hidden sm:block text-left">
                <div class="text-sm font-medium text-gray-900 dark:text-white">
                  {{ user?.name || 'User' }}
                </div>
                <div class="text-xs text-gray-500 dark:text-gray-400">
                  {{ userRole }}
                </div>
              </div>
              <svg class="w-4 h-4 text-gray-500 dark:text-gray-400 hidden sm:block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
              </svg>
            </Button>
          </DropdownMenuTrigger>
          <DropdownMenuContent align="end" class="w-56">
            <div class="px-2 py-1.5 text-sm">
              <div class="font-medium text-gray-900 dark:text-white">
                {{ user?.name || 'User' }}
              </div>
              <div class="text-gray-500 dark:text-gray-400">
                {{ user?.email || '' }}
              </div>
            </div>
            <DropdownMenuSeparator />
            <DropdownMenuItem as-child>
              <a :href="route('profile.edit')" class="w-full cursor-pointer">
                Profile
              </a>
            </DropdownMenuItem>
            <DropdownMenuItem @click="router.post(route('logout'))" class="cursor-pointer">
              Log Out
            </DropdownMenuItem>
          </DropdownMenuContent>
        </DropdownMenu>
      </div>
    </div>
  </header>
</template>

<style scoped>
/* Header specific styles */
</style>
