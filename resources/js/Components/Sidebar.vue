<script setup>
import { ref, computed } from 'vue';
import { Link, usePage, router } from '@inertiajs/vue3';
import { 
    Home,
    Users,
    GraduationCap,
    BookOpen,
    Calendar,
    FileText,
    User,
    Settings,
    Search
} from 'lucide-vue-next';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import Header from '@/Components/Header.vue';

const page = usePage();
const user = computed(() => page.props.auth.user);
const searchQuery = ref('');
const isCollapsed = ref(false);

const isAdmin = computed(() => user.value?.roles?.includes('admin'));
const isTeacher = computed(() => user.value?.roles?.includes('teacher'));
const isStudent = computed(() => user.value?.roles?.includes('student'));

const menuItems = computed(() => {
  const items = [
    {
      name: 'Dashboard',
      href: route('dashboard'),
      icon: Home,
      active: route().current('dashboard')
    }
  ];

  if (isAdmin.value) {
    items.push(
      {
        name: 'Students',
        href: route('students.index'),
        icon: Users,
        active: route().current('students.*')
      },
      {
        name: 'Teachers',
        href: route('teachers.index'),
        icon: GraduationCap,
        active: route().current('teachers.*')
      },
      {
        name: 'Classes',
        href: route('classes.index'),
        icon: BookOpen,
        active: route().current('classes.*')
      },
      {
        name: 'Subjects',
        href: route('subjects.index'),
        icon: FileText,
        active: route().current('subjects.*')
      },
      {
        name: 'Attendance',
        href: route('attendance.index'),
        icon: Calendar,
        active: route().current('attendance.*')
      },
      {
        name: 'Exams',
        href: route('exams.index'),
        icon: FileText,
        active: route().current('exams.*')
      },
      {
        name: 'Fees',
        href: route('fees.index'),
        icon: FileText,
        active: route().current('fees.*')
      }
    );
  }

  if (isTeacher.value) {
    items.push(
      {
        name: 'My Classes',
        href: route('classes.index'),
        icon: BookOpen,
        active: route().current('classes.*')
      },
      {
        name: 'Attendance',
        href: route('attendance.index'),
        icon: Calendar,
        active: route().current('attendance.*')
      },
      {
        name: 'Exams',
        href: route('exams.index'),
        icon: FileText,
        active: route().current('exams.*')
      },
      {
        name: 'Grades',
        href: '#',
        icon: FileText,
        active: false
      }
    );
  }

  if (isStudent.value) {
    items.push(
      {
        name: 'My Classes',
        href: route('classes.index'),
        icon: BookOpen,
        active: route().current('classes.*')
      },
      {
        name: 'Grades',
        href: route('grades.index'),
        icon: FileText,
        active: route().current('grades.*')
      },
      {
        name: 'Timetable',
        href: route('timetable.index'),
        icon: Calendar,
        active: route().current('timetable.*')
      }
    );
  }

  return items;
});

const toggleSidebar = () => {
  isCollapsed.value = !isCollapsed.value;
};

const performSearch = () => {
  if (searchQuery.value.trim()) {
    router.get(route('students.index', { search: searchQuery.value }));
  }
};
</script>

<template>
  <div class="flex h-screen bg-gray-50 dark:bg-gray-900">
    <!-- Sidebar -->
    <div 
      :class="[
        'bg-white dark:bg-gray-800 border-r border-gray-200 dark:border-gray-700 transition-all duration-300 ease-in-out',
        isCollapsed ? 'w-20' : 'w-64'
      ]"
      class="flex flex-col"
    >
      <!-- Logo Section -->
      <div class="flex items-center justify-between p-4">
        <div v-if="!isCollapsed" class="flex items-center space-x-3">
          <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center">
            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
            </svg>
          </div>
          <span class="text-lg font-bold text-gray-800 dark:text-white">EduManage</span>
        </div>
        <div v-else class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center mx-auto">
          <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
          </svg>
        </div>
        <Button
          @click="toggleSidebar"
          variant="ghost"
          size="sm"
          class="p-1 hover:bg-gray-100 dark:hover:bg-gray-700"
        >
          <svg 
            :class="['w-5 h-5 text-gray-500 dark:text-gray-400 transition-transform duration-200', isCollapsed ? 'rotate-180' : '']" 
            fill="none" 
            stroke="currentColor" 
            viewBox="0 0 24 24"
          >
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m0 0l7 7 7-7" />
          </svg>
        </Button>
      </div>

      <!-- Search Section (Admin Only) -->
      <div v-if="isAdmin && !isCollapsed" class="p-4 border-b border-gray-200 dark:border-gray-700">
        <div class="relative">
          <Input
            v-model="searchQuery"
            @keyup.enter="performSearch"
            placeholder="Search students..."
            class="pl-10"
          />
          <div class="absolute left-3 top-1/2 -translate-y-1/2">
            <Search class="w-4 h-4 text-gray-400" />
          </div>
        </div>
      </div>

      <!-- Navigation Menu -->
      <nav class="flex-1 p-4 space-y-2 overflow-y-auto">
        <template v-for="item in menuItems" :key="item.name">
          <Link
            :href="item.href"
            :class="[
              'flex items-center px-3 py-2.5 rounded-lg text-sm font-medium transition-colors duration-200',
              item.active 
                ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 border-l-4 border-blue-600 dark:border-blue-400' 
                : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700'
            ]"
          >
            <component 
              :is="item.icon" 
              :class="['w-5 h-5 flex-shrink-0', isCollapsed ? 'mx-auto' : 'mr-3']" 
            />
            <span v-if="!isCollapsed">{{ item.name }}</span>
          </Link>
        </template>
      </nav>

      <!-- User Section -->
      <div class="p-4 border-t border-gray-200 dark:border-gray-700">
        <div v-if="!isCollapsed" class="flex items-center space-x-3">
          <div class="w-8 h-8 bg-purple-500 rounded-full flex items-center justify-center">
            <User class="w-4 h-4 text-white" />
          </div>
          <div class="flex-1 min-w-0">
            <p class="text-sm font-medium text-gray-900 dark:text-white truncate">
              {{ user?.name || 'User' }}
            </p>
            <p class="text-xs text-gray-500 dark:text-gray-400 truncate">
              {{ isAdmin ? 'Administrator' : isTeacher ? 'Teacher' : 'Student' }}
            </p>
          </div>
        </div>
        <div v-else class="w-8 h-8 bg-purple-500 rounded-full flex items-center justify-center mx-auto">
          <User class="w-4 h-4 text-white" />
        </div>
      </div>
    </div>

    <!-- Main Content Area -->
    <div class="flex-1 flex flex-col overflow-hidden">
      <!-- Header Component -->
      <Header>
        <template #header-title>
          <slot name="header-title" />
        </template>
      </Header>

      <!-- Page Content -->
      <main class="flex-1 overflow-y-auto bg-gray-50 dark:bg-gray-900 p-6">
        <slot />
      </main>
    </div>
  </div>
</template>

<style scoped>
/* Custom scrollbar for sidebar */
nav::-webkit-scrollbar {
  width: 4px;
}

nav::-webkit-scrollbar-track {
  background: transparent;
}

nav::-webkit-scrollbar-thumb {
  background-color: rgba(156, 163, 175, 0.3);
  border-radius: 2px;
}

nav::-webkit-scrollbar-thumb:hover {
  background-color: rgba(156, 163, 175, 0.5);
}

.dark nav::-webkit-scrollbar-thumb {
  background-color: rgba(75, 85, 99, 0.3);
}

.dark nav::-webkit-scrollbar-thumb:hover {
  background-color: rgba(75, 85, 99, 0.5);
}
</style>
