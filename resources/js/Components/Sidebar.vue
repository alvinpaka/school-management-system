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
    User as UserIcon,
    ChevronLeft,
    LogOut,
    CheckCircle2,
    Settings,
    LayoutDashboard
} from 'lucide-vue-next';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import Header from '@/Components/Header.vue';

const page = usePage();
const user = computed(() => page.props.auth.user);
const userRoles = computed(() => user.value?.roles || []);
const userPermissions = computed(() => user.value?.permissions || []);

const searchQuery = ref('');
const searchResults = ref([]);
const showSearchResults = ref(false);
const isSearching = ref(false);
const isCollapsed = ref(false);
const isMobileOpen = ref(false);
const hoveredItem = ref(null);

// ─── Declarative Menu Schema ──────────────────────────────────
const MENU_SCHEMA = [
  {
    name: 'Dashboard',
    href: 'dashboard',
    icon: LayoutDashboard,
    roles: ['admin', 'teacher', 'student', 'parent', 'accountant', 'librarian', 'receptionist']
  },
  {
    name: 'Students',
    parentName: 'Children Profiles',
    href: 'students.index',
    icon: Users,
    roles: ['admin', 'teacher', 'accountant', 'librarian', 'receptionist', 'parent'],
    permission: 'view students'
  },
  {
    name: 'Teachers',
    href: 'teachers.index',
    icon: UserCheck,
    roles: ['admin', 'teacher', 'accountant', 'receptionist'],
    permission: 'view teachers'
  },
  {
    name: 'Parents',
    href: 'parents.index',
    icon: Users2,
    roles: ['admin', 'teacher', 'accountant', 'receptionist'],
    permission: 'view parents'
  },
  {
    name: 'Library',
    href: 'library.index',
    icon: Library,
    roles: ['admin', 'librarian', 'teacher', 'student'],
    permission: 'view library'
  },
  {
    name: 'Accounting',
    parentName: 'Fee Management',
    studentName: 'My Fees',
    href: 'fees.index',
    icon: Calculator,
    roles: ['admin', 'accountant', 'student', 'parent'],
    permission: 'view fees'
  },
  {
    name: 'Academic',
    type: 'separator',
    roles: ['admin', 'teacher', 'student', 'parent']
  },
  {
    name: 'Classes',
    href: 'classes.index',
    icon: BookOpen,
    roles: ['admin', 'teacher', 'receptionist', 'accountant'],
    permission: 'view classes'
  },
  {
    name: 'Subjects',
    studentName: 'My Subjects',
    href: 'subjects.index',
    icon: Edit3,
    roles: ['admin', 'teacher', 'student'],
    permission: 'view subjects'
  },
  {
    name: 'Time Table',
    studentName: 'My Schedule',
    href: 'timetable.index',
    icon: Calendar,
    roles: ['admin', 'teacher', 'student', 'receptionist'],
    permission: 'view class routine'
  },
  {
    name: 'Attendance',
    parentName: 'Daily Attendance',
    studentName: 'My Attendance',
    href: 'attendance.index',
    icon: ClipboardList,
    roles: ['admin', 'teacher', 'student', 'parent'],
    permission: 'view attendance'
  },
  {
    name: 'Evaluations',
    type: 'separator',
    roles: ['admin', 'teacher', 'student', 'parent']
  },
  {
    name: 'Exams',
    studentName: 'My Exams',
    href: 'exams.index',
    icon: FileText,
    roles: ['admin', 'teacher', 'student'],
    permission: 'view exams'
  },
  {
    name: 'Notice Board',
    href: 'notice.index',
    icon: Bell,
    roles: ['admin', 'teacher', 'student', 'accountant', 'librarian', 'receptionist'],
    permission: 'view notices'
  },
  {
    name: 'School Map',
    href: 'map.index',
    icon: Map,
    roles: ['admin', 'teacher', 'student', 'receptionist'],
    permission: 'view map'
  },
  {
    name: 'Reports',
    parentName: 'Report Cards',
    studentName: 'My Reports',
    href: 'report-cards.index',
    icon: FileText,
    roles: ['admin', 'teacher', 'student', 'parent', 'accountant'],
    permission: 'view report cards'
  }
];

// ─── Filtered Menu Items ──────────────────────────────────────
const menuItems = computed(() => {
  return MENU_SCHEMA.filter(item => {
    // 1. Check Role
    const hasRole = item.roles.some(role => userRoles.value.includes(role));
    if (!hasRole) return false;

    // 2. Check Permission (if specified)
    if (item.permission && !userPermissions.value.includes(item.permission)) {
      // Admin bypass
      if (userRoles.value.includes('admin')) return true;
      return false;
    }

    return true;
  }).map(item => {
    if (item.type === 'separator') return item;
    
    // Dynamic Name based on role
    let displayName = item.name;
    if (userRoles.value.includes('parent') && item.parentName) {
      displayName = item.parentName;
    } else if (userRoles.value.includes('student') && item.studentName) {
      displayName = item.studentName;
    }

    return {
      ...item,
      name: displayName,
      active: route().current(item.href.split('.')[0] + '.*') || route().current(item.href)
    };
  });
});

const can = (permission) => {
  if (userRoles.value.includes('admin')) return true;
  return userPermissions.value.includes(permission);
};

const toggleSidebar = () => {
    isCollapsed.value = !isCollapsed.value;
};

const toggleMobileMenu = () => {
    isMobileOpen.value = !isMobileOpen.value;
};

const searchStudents = async () => {
  if (!searchQuery.value || searchQuery.value.trim().length < 2) {
    searchResults.value = [];
    showSearchResults.value = false;
    return;
  }
  
  isSearching.value = true;
  try {
    const response = await fetch(route('students.search', { search: searchQuery.value }), {
      method: 'GET',
      headers: { 'Accept': 'application/json' },
      credentials: 'same-origin'
    });
    
    if (response.ok) {
      const data = await response.json();
      searchResults.value = data.students || [];
      showSearchResults.value = searchResults.value.length > 0;
    }
  } catch (error) {
    console.error("Search failed", error);
  } finally {
    isSearching.value = false;
  }
};

const selectStudent = (student) => {
  searchQuery.value = '';
  showSearchResults.value = false;
  router.visit(route('students.show', student.id));
};
</script>

<template>
  <div class="flex h-screen bg-background text-foreground transition-colors duration-500 overflow-hidden font-sans relative">
    <!-- Mobile Backdrop -->
    <div 
      v-if="isMobileOpen" 
      @click="isMobileOpen = false"
      class="fixed inset-0 bg-background/80 backdrop-blur-sm z-40 lg:hidden transition-opacity duration-300"
    ></div>

    <!-- Sidebar -->
    <aside 
      :class="[
        'bg-card transition-all duration-500 ease-in-out border-r border-border fixed lg:relative z-50 flex flex-col shadow-sm h-full',
        isCollapsed ? 'lg:w-24' : 'lg:w-72',
        isMobileOpen ? 'translate-x-0 w-72' : '-translate-x-full lg:translate-x-0 w-0 lg:w-auto'
      ]"
    >
      <!-- Logo Section -->
      <div class="p-6 mb-2">
        <div class="flex items-center justify-between">
            <Link href="/" class="flex items-center gap-3 group">
                <div class="w-12 h-12 bg-primary rounded-2xl flex items-center justify-center shadow-lg shadow-primary/20 transform transition-all duration-500 group-hover:scale-105">
                    <GraduationCap class="w-7 h-7 text-primary-foreground" />
                </div>
                <div v-if="!isCollapsed" class="animate-fade-in whitespace-nowrap">
                    <h1 class="text-xl font-black text-foreground tracking-tighter">EduManage <span class="text-primary">Pro</span></h1>
                    <p class="text-[9px] uppercase tracking-[0.2em] font-bold text-muted-foreground/80">Institution OS</p>
                </div>
            </Link>
            <Button 
                @click="toggleSidebar"
                variant="ghost" 
                size="icon" 
                class="ml-2 h-8 w-8 rounded-xl opacity-0 group-hover:opacity-100 lg:opacity-100 transition-opacity hover:bg-accent hover:text-accent-foreground hidden lg:flex"
            >
                <ChevronLeft :class="['w-4 h-4 transition-transform duration-500', isCollapsed ? 'rotate-180' : '']" />
            </Button>
            <!-- Mobile Close Button -->
            <Button 
                @click="isMobileOpen = false"
                variant="ghost" 
                size="icon" 
                class="lg:hidden h-8 w-8 rounded-xl"
            >
                <ChevronLeft class="w-4 h-4" />
            </Button>
        </div>
      </div>

      <!-- Search Box (Conditional) -->
      <div v-if="!isCollapsed" class="px-6 mb-6 animate-fade-in">
        <div class="relative group">
            <div class="absolute left-3 top-1/2 -translate-y-1/2 text-muted-foreground group-focus-within:text-primary transition-colors">
                <Search class="w-4 h-4" />
            </div>
            <Input 
                v-model="searchQuery"
                @input="searchStudents"
                placeholder="Search students..." 
                class="h-11 pl-10 bg-muted/50 border-border rounded-xl text-sm focus:ring-2 focus:ring-primary/20"
            />
            
            <!-- Search Results Mini-Flyout -->
            <div v-if="showSearchResults && searchResults.length" class="absolute top-12 left-0 right-0 bg-popover text-popover-foreground rounded-2xl border border-border p-2 shadow-2xl z-50 animate-scale-up">
                <div v-for="student in searchResults.slice(0, 5)" :key="student.id" @click="selectStudent(student)" class="flex items-center gap-3 p-2 hover:bg-accent hover:text-accent-foreground rounded-xl cursor-pointer transition-all">
                    <div class="w-8 h-8 rounded-lg bg-primary/10 flex items-center justify-center text-primary font-bold text-xs uppercase">{{ student.user.name.charAt(0) }}</div>
                    <div class="min-w-0">
                        <p class="text-xs font-bold truncate text-foreground">{{ student.user.name }}</p>
                        <p class="text-[10px] text-muted-foreground truncate">{{ student.admission_number }}</p>
                    </div>
                </div>
            </div>
        </div>
      </div>

      <!-- Nav Items -->
      <nav class="flex-1 px-4 space-y-1.5 overflow-y-auto custom-scrollbar pb-10">
        <template v-for="(item, idx) in menuItems" :key="idx">
          <!-- Separator / Label -->
          <div v-if="item.type === 'separator'" class="px-4 pt-6 pb-2">
            <p v-if="!isCollapsed || isMobileOpen" class="text-[10px] font-black uppercase tracking-[0.2em] text-muted-foreground/60 animate-fade-in">{{ item.name }}</p>
            <div v-else class="h-[1px] bg-border mx-2" />
          </div>

          <!-- Link Item -->
          <Link
            v-else
            :href="route(item.href)"
            @mouseenter="hoveredItem = item.name"
            @mouseleave="hoveredItem = null"
            @click="isMobileOpen = false"
            :class="[
                'group flex items-center px-4 py-3.5 rounded-2xl transition-all duration-300 relative overflow-hidden',
                item.active 
                    ? 'bg-primary text-primary-foreground shadow-lg shadow-primary/20' 
                    : 'text-muted-foreground hover:bg-accent hover:text-accent-foreground',
                isCollapsed && !isMobileOpen ? 'justify-center px-0' : ''
            ]"
          >
            <component 
                :is="item.icon" 
                :class="[
                    'w-5 h-5 transition-all duration-300 relative z-10',
                    item.active ? 'scale-110' : 'group-hover:scale-110',
                    isCollapsed && !isMobileOpen ? '' : 'mr-4'
                ]" 
            />
            <span v-if="!isCollapsed || isMobileOpen" class="text-sm font-bold tracking-tight relative z-10">{{ item.name }}</span>
            
            <!-- Tooltip for Collapsed -->
            <div v-if="isCollapsed && !isMobileOpen && hoveredItem === item.name" class="absolute left-full ml-4 px-3 py-2 bg-popover text-popover-foreground border border-border rounded-xl text-xs font-black shadow-2xl animate-fade-in-right z-50 whitespace-nowrap">
                {{ item.name }}
            </div>
          </Link>
        </template>
      </nav>

      <!-- Bottom Profile Section -->
      <div class="p-4 border-t border-border">
        <div 
            :class="[
                'rounded-2xl p-4 transition-all duration-300',
                isCollapsed && !isMobileOpen ? 'items-center p-2' : 'bg-muted/50'
            ]"
        >
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-primary flex items-center justify-center text-primary-foreground font-black shadow-md">
                    {{ user.name.charAt(0) }}
                </div>
                <div v-if="!isCollapsed || isMobileOpen" class="flex-1 min-w-0 animate-fade-in">
                    <p class="text-xs font-black text-foreground truncate">{{ user.name }}</p>
                    <p class="text-[10px] font-bold text-primary uppercase tracking-wider truncate">{{ userRoles[0] }}</p>
                </div>
                <Link 
                    v-if="!isCollapsed || isMobileOpen" 
                    :href="route('logout')" 
                    method="post" 
                    as="button" 
                    class="p-2 hover:bg-destructive shadow-sm hover:text-destructive-foreground rounded-lg text-muted-foreground transition-all"
                >
                    <LogOut class="w-4 h-4" />
                </Link>
            </div>
        </div>
      </div>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 flex flex-col min-w-0 relative h-screen bg-background transition-colors duration-500">
        <!-- Floating Header -->
        <Header 
            @toggle-mobile-menu="toggleMobileMenu"
            class="z-20 border-b border-border bg-background/95 backdrop-blur supports-[backdrop-filter]:bg-background/60"
        >
            <template #header-title>
                <slot name="header-title" />
            </template>
        </Header>

        <!-- Content Area -->
        <div class="flex-1 overflow-y-auto px-4 lg:px-6 py-4 lg:py-8 custom-scrollbar relative z-10">
            <div class="relative z-10 max-w-[1600px] mx-auto animate-fade-in-up">
                <slot />
            </div>
        </div>
    </main>
  </div>
</template>

<style scoped>
.custom-scrollbar::-webkit-scrollbar {
  width: 5px;
}
.custom-scrollbar::-webkit-scrollbar-track {
  background: transparent;
}
.custom-scrollbar::-webkit-scrollbar-thumb {
  background: rgba(215, 241, 99, 0.1);
  border-radius: 10px;
}
.custom-scrollbar::-webkit-scrollbar-thumb:hover {
  background: rgba(99, 102, 241, 0.2);
}

@keyframes fade-in-right {
    from { opacity: 0; transform: translateX(-10px); }
    to { opacity: 1; transform: translateX(0); }
}

.animate-fade-in-right {
    animation: fade-in-right 0.3s ease-out;
}
</style>
