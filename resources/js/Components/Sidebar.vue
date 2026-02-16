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
    DollarSign,
    UserCheck,
    ClipboardList,
    Edit3,
    Search,
    Users2,
    Library,
    Calculator,
    Bus,
    Bell,
    Palette,
    Map,
    User as UserIcon
} from 'lucide-vue-next';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import Header from '@/Components/Header.vue';

const page = usePage();
const user = computed(() => page.props.auth.user);
const searchQuery = ref('');
const searchResults = ref([]);
const showSearchResults = ref(false);
const isSearching = ref(false);
const isCollapsed = ref(false);
const isHovering = ref(false);
const hoveredItem = ref(null);

// ─── Role checks ──────────────────────────────────────────────
const isAdmin = computed(() => user.value?.roles?.includes('admin'));
const isTeacher = computed(() => user.value?.roles?.includes('teacher'));
const isStudent = computed(() => user.value?.roles?.includes('student'));
const isParent = computed(() => user.value?.roles?.includes('parent'));
const isAccountant = computed(() => user.value?.roles?.includes('accountant'));
const isLibrarian = computed(() => user.value?.roles?.includes('librarian'));
const isReceptionist = computed(() => user.value?.roles?.includes('receptionist'));

// ─── Permission helper ────────────────────────────────────────
const can = (permission) => {
  return user.value?.permissions?.includes(permission);
};

const menuItems = computed(() => {
  const items = [
    {
      name: 'Dashboard',
      href: route('dashboard'),
      icon: Home,
      active: route().current('dashboard')
    },
    {
      name: 'My Profile',
      href: route('profile.edit'),
      icon: UserIcon,
      active: route().current('profile.*')
    }
  ];

  // ── Admin: full access to everything ────────────────────────
  if (isAdmin.value) {
    items.push(
      {
        name: 'Students',
        href: route('students.index'),
        icon: Users,
        active: route().current('students.*'),
        permission: 'create students'
      },
      {
        name: 'Teachers',
        href: route('teachers.index'),
        icon: UserCheck,
        active: route().current('teachers.*'),
        permission: 'create teachers'
      },
      {
        name: 'Parents',
        href: route('parents.index'),
        icon: Users2,
        active: route().current('parents.*'),
        permission: 'create parents'
      },
      {
        name: 'Library',
        href: route('library.index'),
        icon: Library,
        active: route().current('library.*'),
        permission: 'create library'
      },
      {
        name: 'Account',
        href: route('fees.index'),
        icon: Calculator,
        active: route().current('fees.*'),
        permission: 'create fees'
      },
      {
        name: 'Classes',
        href: route('classes.index'),
        icon: BookOpen,
        active: route().current('classes.*'),
        permission: 'create classes'
      },
      {
        name: 'Subjects',
        href: route('subjects.index'),
        icon: Edit3,
        active: route().current('subjects.*'),
        permission: 'create subjects'
      },
      {
        name: 'Class Routine',
        href: route('timetable.index'),
        icon: Calendar,
        active: route().current('timetable.*'),
        permission: 'create class routine'
      },
      {
        name: 'Attendance',
        href: route('attendance.index'),
        icon: ClipboardList,
        active: route().current('attendance.*'),
        permission: 'create attendance'
      },
      {
        name: 'Exams',
        href: route('exams.index'),
        icon: FileText,
        active: route().current('exams.*'),
        permission: 'create exams'
      },
      {
        name: 'Transport',
        href: route('transport.index'),
        icon: Bus,
        active: route().current('transport.*'),
        permission: 'create transport'
      },
      {
        name: 'Notice',
        href: route('notice.index'),
        icon: Bell,
        active: route().current('notice.*'),
        permission: 'create notices'
      },
      {
        name: 'Map',
        href: route('map.index'),
        icon: Map,
        active: route().current('map.*'),
        permission: 'create map'
      },
      {
        name: 'Report Cards',
        href: route('report-cards.index'),
        icon: FileText,
        active: route().current('report-cards.*'),
        permission: 'create report cards'
      }
    );
  }
  // ── Receptionist: front desk management ─────────────────────
  else if (isReceptionist.value) {
    items.push(
      {
        name: 'Students',
        href: route('students.create'),
        icon: Users,
        active: route().current('students.*'),
        permission: 'create students'
      },
      {
        name: 'Teachers',
        href: route('teachers.index'),
        icon: UserCheck,
        active: route().current('teachers.*'),
        permission: 'view teachers'
      },
      {
        name: 'Parents',
        href: route('parents.create'),
        icon: Users2,
        active: route().current('parents.*'),
        permission: 'create parents'
      },
      {
        name: 'Classes',
        href: route('classes.index'),
        icon: BookOpen,
        active: route().current('classes.*'),
        permission: 'view classes'
      },
      {
        name: 'Class Routine',
        href: route('timetable.index'),
        icon: Calendar,
        active: route().current('timetable.*'),
        permission: 'view class routine'
      },
      {
        name: 'Transport',
        href: route('transport.index'),
        icon: Bus,
        active: route().current('transport.*'),
        permission: 'view transport'
      },
      {
        name: 'Notice',
        href: route('notice.index'),
        icon: Bell,
        active: route().current('notice.*'),
        permission: 'view notices'
      },
      {
        name: 'Map',
        href: route('map.index'),
        icon: Map,
        active: route().current('map.*'),
        permission: 'view map'
      }
    );
  }
  // ── Librarian: library-only ─────────────────────────────────
  else if (isLibrarian.value) {
    items.push(
      {
        name: 'Students',
        href: route('students.index'),
        icon: Users,
        active: route().current('students.*'),
        permission: 'view students'
      },
      {
        name: 'Teachers',
        href: route('teachers.index'),
        icon: UserCheck,
        active: route().current('teachers.*'),
        permission: 'view teachers'
      },
      {
        name: 'Library',
        href: route('library.index'),
        icon: Library,
        active: route().current('library.*'),
        permission: 'view library'
      },
      {
        name: 'Notices',
        href: route('notice.index'),
        icon: Bell,
        active: route().current('notice.*'),
        permission: 'view notices'
      }
    );
  }
  // ── Accountant: finance-focused ─────────────────────────────
  else if (isAccountant.value) {
    items.push(
      {
        name: 'Students',
        href: route('students.index'),
        icon: Users,
        active: route().current('students.*'),
        permission: 'view students'
      },
      {
        name: 'Teachers',
        href: route('teachers.index'),
        icon: UserCheck,
        active: route().current('teachers.*'),
        permission: 'view teachers'
      },
      {
        name: 'Parents',
        href: route('parents.index'),
        icon: Users2,
        active: route().current('parents.*'),
        permission: 'view parents'
      },
      {
        name: 'Account',
        href: route('fees.index'),
        icon: Calculator,
        active: route().current('fees.*'),
        permission: 'view fees'
      },
      {
        name: 'Classes',
        href: route('classes.index'),
        icon: BookOpen,
        active: route().current('classes.*'),
        permission: 'view classes'
      },
      {
        name: 'Transport',
        href: route('transport.index'),
        icon: Bus,
        active: route().current('transport.*'),
        permission: 'view transport'
      },
      {
        name: 'Notices',
        href: route('notice.index'),
        icon: Bell,
        active: route().current('notice.*'),
        permission: 'view notices'
      },
      {
        name: 'Report Cards',
        href: route('report-cards.index'),
        icon: FileText,
        active: route().current('report-cards.*'),
        permission: 'view report cards'
      }
    );
  }
  // ── Teacher: academic management ────────────────────────────
  else if (isTeacher.value) {
    items.push(
      {
        name: 'Students',
        href: route('students.index'),
        icon: Users,
        active: route().current('students.*'),
        permission: 'view students'
      },
      {
        name: 'Teachers',
        href: route('teachers.index'),
        icon: UserCheck,
        active: route().current('teachers.*'),
        permission: 'view teachers'
      },
      {
        name: 'Parents',
        href: route('parents.index'),
        icon: Users2,
        active: route().current('parents.*'),
        permission: 'view parents'
      },
      {
        name: 'Library',
        href: route('library.index'),
        icon: Library,
        active: route().current('library.*'),
        permission: 'view library'
      },
      {
        name: 'Classes',
        href: route('classes.index'),
        icon: BookOpen,
        active: route().current('classes.*'),
        permission: 'view classes'
      },
      {
        name: 'Subjects',
        href: route('subjects.index'),
        icon: Edit3,
        active: route().current('subjects.*'),
        permission: 'view subjects'
      },
      {
        name: 'Class Routine',
        href: route('timetable.index'),
        icon: Calendar,
        active: route().current('timetable.*'),
        permission: 'view class routine'
      },
      {
        name: 'Attendance',
        href: route('attendance.index'),
        icon: ClipboardList,
        active: route().current('attendance.*'),
        permission: 'create attendance'
      },
      {
        name: 'Exams',
        href: route('exams.index'),
        icon: FileText,
        active: route().current('exams.*'),
        permission: 'create exams'
      },
      {
        name: 'Grades',
        href: route('grades.index'),
        icon: FileText,
        active: route().current('grades.*'),
        permission: 'create grades'
      },
      {
        name: 'Notice',
        href: route('notice.index'),
        icon: Bell,
        active: route().current('notice.*'),
        permission: 'create notices'
      },
      {
        name: 'Map',
        href: route('map.index'),
        icon: Map,
        active: route().current('map.*'),
        permission: 'view map'
      },
      {
        name: 'Report Cards',
        href: route('report-cards.index'),
        icon: FileText,
        active: route().current('report-cards.*'),
        permission: 'create report cards'
      }
    );
  }

  // ── Student: limited personal access ────────────────────────
  else if (isStudent.value) {
    items.push(
      {
        name: 'Students',
        href: route('students.index'),
        icon: Users,
        active: route().current('students.*'),
        permission: 'view students'
      },
      {
        name: 'Teachers',
        href: route('teachers.index'),
        icon: UserCheck,
        active: route().current('teachers.*'),
        permission: 'view teachers'
      },
      {
        name: 'Library',
        href: route('library.index'),
        icon: Library,
        active: route().current('library.*'),
        permission: 'view library'
      },
      {
        name: 'Account',
        href: route('fees.index'),
        icon: Calculator,
        active: route().current('fees.*'),
        permission: 'view fees'
      },
      {
        name: 'My Classes',
        href: route('classes.index'),
        icon: BookOpen,
        active: route().current('classes.*'),
        permission: 'view classes'
      },
      {
        name: 'Subjects',
        href: route('subjects.index'),
        icon: Edit3,
        active: route().current('subjects.*'),
        permission: 'view subjects'
      },
      {
        name: 'Class Routine',
        href: route('timetable.index'),
        icon: Calendar,
        active: route().current('timetable.*'),
        permission: 'view class routine'
      },
      {
        name: 'My Attendance',
        href: route('attendance.index'),
        icon: ClipboardList,
        active: route().current('attendance.*'),
        permission: 'view attendance'
      },
      {
        name: 'Exams',
        href: route('exams.index'),
        icon: FileText,
        active: route().current('exams.*'),
        permission: 'view exams'
      },
      {
        name: 'My Results',
        href: route('grades.index'),
        icon: FileText,
        active: route().current('grades.*'),
        permission: 'view grades'
      },
      {
        name: 'Transport',
        href: route('transport.index'),
        icon: Bus,
        active: route().current('transport.*'),
        permission: 'view transport'
      },
      {
        name: 'Notices',
        href: route('notice.index'),
        icon: Bell,
        active: route().current('notice.*'),
        permission: 'view notices'
      },
      {
        name: 'Map',
        href: route('map.index'),
        icon: Map,
        active: route().current('map.*'),
        permission: 'view map'
      },
      {
        name: 'Report Cards',
        href: route('report-cards.index'),
        icon: FileText,
        active: route().current('report-cards.*'),
        permission: 'view report cards'
      }
    );
  }

  // ── Parent: monitor child performance ───────────────────────
  else if (isParent.value) {
    items.push(
      {
        name: 'My Children',
        href: route('students.index'),
        icon: Users,
        active: route().current('students.*'),
        permission: 'view students'
      },
      {
        name: 'My Children\'s Teachers',
        href: route('teachers.index'),
        icon: UserCheck,
        active: route().current('teachers.*'),
        permission: 'view teachers'
      },
      {
        name: 'Parents',
        href: route('parents.index'),
        icon: Users2,
        active: route().current('parents.*'),
        permission: 'view parents'
      },
      {
        name: 'Library',
        href: route('library.index'),
        icon: Library,
        active: route().current('library.*'),
        permission: 'view library'
      },
      {
        name: 'My Children\'s Fees',
        href: route('fees.index'),
        icon: Calculator,
        active: route().current('fees.*'),
        permission: 'view fees'
      },
      {
        name: 'My Children\'s Classes',
        href: route('classes.index'),
        icon: BookOpen,
        active: route().current('classes.*'),
        permission: 'view classes'
      },
      {
        name: 'Subjects',
        href: route('subjects.index'),
        icon: Edit3,
        active: route().current('subjects.*'),
        permission: 'view subjects'
      },
      {
        name: 'Class Routine',
        href: route('timetable.index'),
        icon: Calendar,
        active: route().current('timetable.*'),
        permission: 'view class routine'
      },
      {
        name: 'My Children\'s Attendance',
        href: route('attendance.index'),
        icon: ClipboardList,
        active: route().current('attendance.*'),
        permission: 'view attendance'
      },
      {
        name: 'My Children\'s Exams',
        href: route('exams.index'),
        icon: FileText,
        active: route().current('exams.*'),
        permission: 'view exams'
      },
      {
        name: 'My Children\'s Grades',
        href: route('grades.index'),
        icon: FileText,
        active: route().current('grades.*'),
        permission: 'view grades'
      },
      {
        name: 'Transport',
        href: route('transport.index'),
        icon: Bus,
        active: route().current('transport.*'),
        permission: 'view transport'
      },
      {
        name: 'Notices',
        href: route('notice.index'),
        icon: Bell,
        active: route().current('notice.*'),
        permission: 'view notices'
      },
      {
        name: 'Map',
        href: route('map.index'),
        icon: Map,
        active: route().current('map.*'),
        permission: 'view map'
      },
      {
        name: 'My Children\'s Report Cards',
        href: route('report-cards.index'),
        icon: FileText,
        active: route().current('report-cards.*'),
        permission: 'view report cards'
      }
    );
  }

  return items;
});

const toggleSidebar = () => {
  isCollapsed.value = !isCollapsed.value;
  // Add haptic feedback-like animation
  const sidebar = document.querySelector('.sidebar-main');
  if (sidebar) {
    sidebar.style.transform = 'scale(0.98)';
    setTimeout(() => {
      sidebar.style.transform = 'scale(1)';
    }, 150);
  }
};

const performSearch = () => {
  if (searchQuery.value.trim()) {
    router.get(route('students.index', { search: searchQuery.value }));
  }
};

const searchStudents = async () => {
  if (!searchQuery.value || searchQuery.value.trim().length < 2) {
    searchResults.value = [];
    showSearchResults.value = false;
    return;
  }
  
  isSearching.value = true;
  try {
    // Use GET for search (no CSRF issues)
    const response = await fetch(route('students.search', { search: searchQuery.value }), {
      method: 'GET',
      headers: {
        'Accept': 'application/json'
      },
      credentials: 'same-origin'
    });
    
    if (response.ok) {
      const data = await response.json();
      searchResults.value = data.students || [];
      showSearchResults.value = searchResults.value.length > 0;
    } else {
      searchResults.value = [];
      showSearchResults.value = false;
    }
  } catch (error) {
    searchResults.value = [];
    showSearchResults.value = false;
  } finally {
    isSearching.value = false;
  }
};

const selectStudent = (student) => {
  searchQuery.value = '';
  searchResults.value = [];
  showSearchResults.value = false;
  router.visit(route('students.show', student.id));
};

const clearSearch = () => {
  searchQuery.value = '';
  searchResults.value = [];
  showSearchResults.value = false;
};

// Close search results when clicking outside
const handleClickOutside = (event) => {
  const searchContainer = event.target.closest('.search-container');
  if (!searchContainer) {
    showSearchResults.value = false;
  }
};

// Add interactive hover effects
const setHoveredItem = (itemName) => {
  hoveredItem.value = itemName;
};

const clearHoveredItem = () => {
  hoveredItem.value = null;
};
</script>

<template>
  <div class="flex h-screen bg-gray-50 dark:bg-gray-900">
    <!-- Sidebar -->
    <div 
      :class="[
        'bg-white dark:bg-gray-800 border-r border-gray-200 dark:border-gray-700 transition-all duration-300 ease-in-out shadow-lg sidebar-main',
        isCollapsed ? 'w-20' : 'w-64'
      ]"
      class="flex flex-col relative"
      @mouseenter="isHovering = true"
      @mouseleave="isHovering = false"
    >
      <!-- Logo Section -->
      <div class="flex items-center justify-between p-4 border-b border-gray-100 dark:border-gray-700">
        <div v-if="!isCollapsed" class="flex items-center space-x-3">
          <div class="w-10 h-10 bg-blue-600 rounded-xl flex items-center justify-center shadow-md transform transition-transform duration-200 hover:scale-105">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
            </svg>
          </div>
          <div>
            <span class="text-xl font-bold text-gray-800 dark:text-white">EduManage</span>
            <div class="text-xs text-gray-500 dark:text-gray-400">School System</div>
          </div>
        </div>
        <div v-else class="w-10 h-10 bg-blue-600 rounded-xl flex items-center justify-center mx-auto shadow-md transform transition-transform duration-200 hover:scale-105">
          <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
          </svg>
        </div>
        <Button
          @click="toggleSidebar"
          variant="ghost"
          size="sm"
          class="p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-all duration-200 hover:scale-110 active:scale-95"
        >
          <svg 
            :class="['w-5 h-5 text-gray-500 dark:text-gray-400 transition-all duration-300', isCollapsed ? 'rotate-180' : '']" 
            fill="none" 
            stroke="currentColor" 
            viewBox="0 0 24 24"
          >
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m0 0l7 7 7-7" />
          </svg>
        </Button>
      </div>

      <!-- Search Section (Admin & Receptionist) -->
      <div v-if="(isAdmin || isReceptionist) && !isCollapsed" class="p-4">
        <div class="search-container relative">
          <div class="relative border border-gray-200 dark:border-gray-700 rounded-xl focus-within:border-blue-500 focus-within:ring-2 focus-within:ring-blue-200 dark:focus-within:ring-blue-800 transition-all duration-200">
            <Input
              v-model="searchQuery"
              @input="searchStudents"
              @keyup.enter="performSearch"
              @focus="() => showSearchResults = (searchResults.value || []).length > 0"
              placeholder="Search students..."
              class="pl-10 border-0 focus:ring-0 bg-gray-50 dark:bg-gray-700"
            />
            <div class="absolute left-3 top-1/2 -translate-y-1/2">
              <Search class="w-4 h-4 text-gray-400" />
            </div>
            <div v-if="isSearching" class="absolute right-3 top-1/2 -translate-y-1/2">
              <div class="animate-spin w-4 h-4 border-2 border-gray-300 border-t-blue-600 rounded-full"></div>
            </div>
            <button
              v-if="searchQuery"
              @click="clearSearch"
              class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors duration-200"
            >
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
              </svg>
            </button>
          </div>
          
          <!-- Search Results Dropdown -->
          <div 
            v-if="showSearchResults && searchResults.length > 0"
            @click.stop
            class="absolute top-full left-0 right-0 mt-2 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl shadow-2xl z-[9999] max-h-80 overflow-y-auto backdrop-blur-sm"
          >
            <div class="py-2">
              <div
                v-for="student in searchResults"
                :key="student.id"
                @click="selectStudent(student)"
                class="px-4 py-3 hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer transition-all duration-200 flex items-center space-x-3 border-b border-gray-100 dark:border-gray-700 last:border-b-0"
              >
                <div class="w-10 h-10 rounded-full overflow-hidden bg-gray-200 dark:bg-gray-700 border-2 border-gray-300 dark:border-gray-600 flex-shrink-0 shadow-sm">
                  <img 
                    v-if="student.user.photo" 
                    :src="`/storage/${student.user.photo}`" 
                    :alt="student.user.name"
                    class="w-full h-full object-cover"
                  />
                  <div v-else class="flex items-center justify-center h-full">
                    <span class="text-blue-600 text-sm font-bold">{{ student.user.name.charAt(0).toUpperCase() }}</span>
                  </div>
                </div>
                <div class="flex-1 min-w-0">
                  <div class="font-semibold text-gray-900 dark:text-white truncate">{{ student.user.name }}</div>
                  <div class="text-sm text-gray-500 dark:text-gray-400 truncate">{{ student.user.email }}</div>
                  <div class="text-xs text-blue-600 dark:text-blue-400 font-medium">{{ student.admission_number }}</div>
                </div>
                <div class="text-gray-400">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                  </svg>
                </div>
              </div>
            </div>
            <div v-if="searchResults.length === 0 && searchQuery.trim().length >= 2" class="px-4 py-3 text-sm text-gray-500 dark:text-gray-400 text-center">
              No students found
            </div>
          </div>
        </div>
      </div>

      
      <!-- Navigation Menu -->
      <nav class="flex-1 p-4 space-y-1 overflow-y-auto">
        <template v-for="item in menuItems.filter(item => !item.permission || can(item.permission))" :key="item.name">
          <Link
            :href="item.href"
            @mouseenter="() => setHoveredItem(item.name)"
            @mouseleave="clearHoveredItem"
            :class="[
              'group flex items-center px-3 py-3 rounded-xl text-sm font-medium transition-all duration-200 relative overflow-hidden',
              item.active 
                ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 border border-blue-200 dark:border-blue-700 shadow-sm' 
                : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 hover:shadow-sm',
              hoveredItem === item.name && !item.active ? 'translate-x-1' : '',
              isCollapsed ? 'justify-center' : ''
            ]"
          >
            <!-- Animated background effect -->
            <div 
              v-if="hoveredItem === item.name && !item.active"
              class="absolute inset-0 bg-blue-50 dark:bg-blue-900/20 opacity-0 animate-pulse"
            ></div>
            
            <component 
              :is="item.icon" 
              :class="[
                'w-5 h-5 flex-shrink-0 relative z-10 transition-transform duration-200',
                isCollapsed ? 'mx-auto' : 'mr-3',
                hoveredItem === item.name ? 'scale-110' : '',
                item.active ? 'text-blue-600 dark:text-blue-400' : ''
              ]" 
            />
            <span 
              v-if="!isCollapsed" 
              class="relative z-10 transition-all duration-200"
              :class="hoveredItem === item.name ? 'font-semibold' : ''"
            >
              {{ item.name }}
            </span>
            
            <!-- Active indicator -->
            <div 
              v-if="item.active && !isCollapsed"
              class="absolute right-2 top-1/2 -translate-y-1/2 w-2 h-2 bg-blue-600 dark:bg-blue-400 rounded-full animate-pulse"
            ></div>
          </Link>
        </template>
      </nav>
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
  width: 6px;
}

nav::-webkit-scrollbar-track {
  background: transparent;
}

nav::-webkit-scrollbar-thumb {
  background-color: rgba(156, 163, 175, 0.2);
  border-radius: 3px;
  transition: background-color 0.2s;
}

nav::-webkit-scrollbar-thumb:hover {
  background-color: rgba(156, 163, 175, 0.4);
}

.dark nav::-webkit-scrollbar-thumb {
  background-color: rgba(75, 85, 99, 0.2);
}

.dark nav::-webkit-scrollbar-thumb:hover {
  background-color: rgba(75, 85, 99, 0.4);
}

/* Sidebar animations */
.sidebar-main {
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

/* Hover effects */
.group:hover .group-hover\:translate-x-1 {
  transform: translateX(0.25rem);
}

/* Pulse animation for active indicators */
@keyframes pulse {
  0%, 100% {
    opacity: 1;
  }
  50% {
    opacity: 0.5;
  }
}

.animate-pulse {
  animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}

/* Smooth transitions for all interactive elements */
* {
  transition-property: color, background-color, border-color, transform, box-shadow;
  transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
  transition-duration: 200ms;
}
</style>
