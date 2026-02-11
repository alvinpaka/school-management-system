<script setup>
import { computed, ref } from 'vue';
import { usePage, router } from '@inertiajs/vue3';
import { 
    Bell,
    User
} from 'lucide-vue-next';
import { Button } from '@/components/ui/button';
import { Badge } from '@/components/ui/badge';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
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
        <Dropdown align="right" width="48">
          <template #trigger>
            <Button variant="ghost" size="sm" class="flex items-center space-x-2">
              <div class="w-8 h-8 bg-purple-500 rounded-full flex items-center justify-center">
                <User class="w-4 h-4 text-white" />
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
          </template>

          <template #content>
            <div class="px-4 py-3 border-b border-gray-200 dark:border-gray-600">
              <div class="text-base font-medium text-gray-800 dark:text-gray-200">
                {{ user?.name || 'User' }}
              </div>
              <div class="text-sm font-medium text-gray-500">
                {{ user?.email || '' }}
              </div>
            </div>

            <div class="py-1">
              <DropdownLink :href="route('profile.edit')">
                Profile
              </DropdownLink>
              <DropdownLink
                :href="route('logout')"
                method="post"
                as="button"
              >
                Log Out
              </DropdownLink>
            </div>
          </template>
        </Dropdown>
      </div>
    </div>
  </header>
</template>

<style scoped>
/* Header specific styles */
</style>
