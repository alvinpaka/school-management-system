<script setup>
import { computed, ref } from 'vue';
import { usePage, router } from '@inertiajs/vue3';
import { 
    Bell,
    User as UserIcon,
    Menu
} from 'lucide-vue-next';
import { Button } from '@/Components/ui/button';
import { Badge } from '@/Components/ui/badge';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuSeparator,
    DropdownMenuTrigger,
} from '@/Components/ui/dropdown-menu';
import DarkModeToggle from '@/Components/DarkModeToggle.vue';

const emit = defineEmits(['toggle-mobile-menu']);

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
  <header class="bg-card border-b border-border px-4 lg:px-6 py-3 lg:py-4">
    <div class="flex items-center justify-between">
      <!-- Left Side - Page Title & Mobile Toggle -->
      <div class="flex items-center space-x-2 lg:space-x-4 min-w-0">
        <Button 
            @click="emit('toggle-mobile-menu')"
            variant="ghost" 
            size="icon" 
            class="lg:hidden h-9 w-9 rounded-xl text-warm-muted text-dark-muted mr-1 hover:bg-terracotta/10"
        >
            <Menu class="w-5 h-5" />
        </Button>
        <h1 class="text-lg lg:text-xl font-black text-warm-text text-dark-text truncate tracking-tighter uppercase">
          <slot name="header-title" />
        </h1>
      </div>
      
      <!-- Right Side - Actions -->
      <div class="flex items-center space-x-4">
        <!-- Notifications -->
        <div class="relative">
          <Button variant="ghost" size="sm" class="relative p-2 rounded-xl hover:bg-terracotta/10 text-warm-muted text-dark-muted">
            <Bell class="w-5 h-5" />
            <Badge class="absolute top-1 right-1 h-4 w-4 rounded-full p-0 flex items-center justify-center text-[10px] bg-terracotta text-white border-0 font-black">
              3
            </Badge>
          </Button>
        </div>
        
        <!-- Dark Mode Toggle -->
        <DarkModeToggle variant="ghost" />
        
        <!-- User Dropdown -->
        <DropdownMenu>
          <DropdownMenuTrigger as-child>
            <Button variant="ghost" size="sm" class="flex items-center space-x-2 p-1 lg:pr-3 rounded-2xl hover:bg-terracotta/10 transition-all">
              <div class="w-9 h-9 lg:w-10 lg:h-10 rounded-xl overflow-hidden bg-terracotta/10 border-2 border-terracotta/20 shadow-sm">
                <img 
                  v-if="user?.photo" 
                  :src="`/storage/${user.photo}`" 
                  :alt="user?.name"
                  class="w-full h-full object-cover"
                />
                <div v-else class="flex items-center justify-center h-full">
                  <UserIcon class="w-5 h-5 text-terracotta" />
                </div>
              </div>
              <div class="hidden md:block text-left">
                <div class="text-xs font-black text-warm-text text-dark-text leading-tight truncate max-w-[120px]">
                  {{ user?.name || 'User' }}
                </div>
                <div class="text-[10px] font-bold text-terracotta uppercase tracking-wider">
                  {{ userRole }}
                </div>
              </div>
              <svg class="w-3 h-3 text-warm-muted text-dark-muted hidden md:block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
              </svg>
            </Button>
          </DropdownMenuTrigger>
          <DropdownMenuContent align="end" class="w-56 mt-2 rounded-2xl card-warm p-2 shadow-2xl animate-scale-up">
            <div class="px-3 py-2">
              <div class="text-sm font-black text-warm-text text-dark-text">
                {{ user?.name || 'User' }}
              </div>
              <div class="text-[10px] font-bold text-warm-muted text-dark-muted uppercase tracking-widest">
                {{ user?.email || '' }}
              </div>
            </div>
            <DropdownMenuSeparator class="bg-terracotta/20" />
            <DropdownMenuItem as-child class="rounded-xl cursor-pointer hover:bg-terracotta/10 m-1">
              <a :href="route('profile.edit')" class="w-full flex items-center px-2 py-1.5 font-bold text-xs uppercase tracking-wider text-warm-text text-dark-text hover:text-terracotta">
                Profile
              </a>
            </DropdownMenuItem>
            <DropdownMenuItem @click="router.post(route('logout'))" class="rounded-xl cursor-pointer hover:bg-destructive/10 m-1">
              <div class="w-full flex items-center px-2 py-1.5 font-black text-xs uppercase tracking-[0.2em] text-destructive">
                Log Out
              </div>
            </DropdownMenuItem>
          </DropdownMenuContent>
        </DropdownMenu>
      </div>
    </div>
  </header>
</template>