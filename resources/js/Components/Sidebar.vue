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
const isScrolled = ref(false);

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
    studentName: 'My Children',
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

let ticking = false;
const handleScroll = () => {
    if (!ticking) {
        requestAnimationFrame(() => {
            isScrolled.value = window.scrollY > 20;
            ticking = false;
        });
        ticking = true;
    }
};

// Add scroll listener with passive option for better performance
import { onMounted, onUnmounted } from 'vue';
onMounted(() => {
    window.addEventListener('scroll', handleScroll, { passive: true });
});
onUnmounted(() => {
    window.removeEventListener('scroll', handleScroll);
});
</script>

<template>
<div class="min-h-screen bg-warm-bg bg-dark-bg text-warm-text text-dark-text relative transition-colors duration-300 font-serif">
    <!-- Background Orbs (optimized) -->
    <div class="fixed inset-0 pointer-events-none overflow-hidden z-0 contain-strict">
        <div class="hero-orb-1 absolute -top-[15%] -left-[10%] w-[50%] h-[50%] blur-[80px] animate-float will-change-transform" />
        <div class="hero-orb-2 absolute top-[30%] -right-[5%] w-[40%] h-[40%] blur-[80px] animate-float will-change-transform" style="animation-delay: 3s" />
        <div class="hero-orb-3 absolute -bottom-[10%] left-[15%] w-[35%] h-[35%] blur-[80px] animate-float will-change-transform" style="animation-delay: 6s" />
    </div>

    <!-- Savanna Texture Overlay -->
    <div class="savanna-texture fixed inset-0 pointer-events-none z-0 opacity-30" />

    <!-- Main Layout -->
    <div class="flex h-screen relative z-10 font-sans">
        <!-- Mobile Backdrop -->
        <div 
            v-if="isMobileOpen" 
            @click="isMobileOpen = false"
            class="fixed inset-0 bg-warm-text/60 dark:bg-dark-text/60 backdrop-blur-sm z-40 lg:hidden transition-opacity duration-300"
        ></div>

        <!-- Sidebar -->
        <aside 
            :class="[
                'bg-warm-bg/95 bg-dark-bg/95 backdrop-blur-sm transition-all duration-500 ease-in-out border-r border-warm-border border-dark-border fixed lg:relative z-50 flex flex-col shadow-xl h-full',
                isCollapsed ? 'lg:w-24' : 'lg:w-72',
                isMobileOpen ? 'translate-x-0 w-72' : '-translate-x-full lg:translate-x-0 w-0 lg:w-auto'
            ]"
        >
            <!-- Logo Section -->
            <div class="p-6 mb-2">
                <div class="flex items-center justify-between">
                    <Link href="/dashboard" class="flex items-center gap-3 group">
                        <div class="w-12 h-12 bg-terracotta rounded-2xl flex items-center justify-center shadow-lg shadow-terracotta/30 transform transition-all duration-500 group-hover:scale-105">
                            <GraduationCap class="w-7 h-7 text-white" />
                        </div>
                        <div v-if="!isCollapsed" class="animate-fade-in whitespace-nowrap">
                            <h1 class="text-xl font-black text-warm-text text-dark-text tracking-tighter">EduManage <span class="text-terracotta">Pro</span></h1>
                            <p class="text-[9px] uppercase tracking-[0.2em] font-bold text-terracotta/60">Nairobi · Kenya</p>
                        </div>
                    </Link>
                    <button 
                        @click="toggleSidebar"
                        class="ml-2 h-8 w-8 rounded-xl opacity-0 group-hover:opacity-100 lg:opacity-100 transition-all hover:bg-terracotta/10 hidden lg:flex items-center justify-center"
                    >
                        <ChevronLeft :class="['w-4 h-4 text-warm-muted text-dark-muted transition-transform duration-500', isCollapsed ? 'rotate-180' : '']" />
                    </button>
                    <!-- Mobile Close Button -->
                    <button 
                        @click="isMobileOpen = false"
                        class="lg:hidden h-8 w-8 rounded-xl hover:bg-terracotta/10 flex items-center justify-center"
                    >
                        <ChevronLeft class="w-4 h-4 text-warm-muted text-dark-muted" />
                    </button>
                </div>
            </div>

            <!-- Search Box (Conditional) -->
            <div v-if="!isCollapsed" class="px-6 mb-6 animate-fade-in">
                <div class="relative group">
                    <div class="absolute left-3 top-1/2 -translate-y-1/2 text-warm-muted/60 text-dark-muted/60 group-focus-within:text-terracotta transition-colors">
                        <Search class="w-4 h-4" />
                    </div>
                    <input 
                        v-model="searchQuery"
                        @input="searchStudents"
                        placeholder="Search students..." 
                        class="w-full h-11 pl-10 bg-white/50 bg-dark-bg/50 border border-terracotta/20 rounded-xl text-sm text-warm-text text-dark-text placeholder:text-warm-muted/50 focus:outline-none focus:ring-2 focus:ring-terracotta/30 focus:border-terracotta transition-all"
                    />
                    
                    <!-- Search Results -->
                    <div v-if="showSearchResults && searchResults.length" class="absolute top-12 left-0 right-0 bg-white bg-dark-bg rounded-2xl border border-terracotta/20 p-2 shadow-2xl z-50 animate-scale-up">
                        <div v-for="student in searchResults.slice(0, 5)" :key="student.id" @click="selectStudent(student)" class="flex items-center gap-3 p-2 hover:bg-terracotta/5 rounded-xl cursor-pointer transition-all">
                            <div class="w-8 h-8 rounded-lg bg-terracotta/10 flex items-center justify-center text-terracotta font-bold text-xs uppercase">{{ student.user.name.charAt(0) }}</div>
                            <div class="min-w-0">
                                <p class="text-xs font-bold truncate text-warm-text text-dark-text">{{ student.user.name }}</p>
                                <p class="text-[10px] text-warm-muted/70 text-dark-muted/70 truncate">{{ student.admission_number }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Nav Items -->
            <nav class="flex-1 px-4 space-y-1.5 overflow-y-auto pb-10 custom-scrollbar">
                <template v-for="(item, idx) in menuItems" :key="idx">
                    <!-- Separator / Label -->
                    <div v-if="item.type === 'separator'" class="px-4 pt-6 pb-2">
                        <p v-if="!isCollapsed || isMobileOpen" class="text-[10px] font-black uppercase tracking-[0.2em] text-terracotta/60 animate-fade-in">{{ item.name }}</p>
                        <div v-else class="h-px bg-terracotta/20 mx-2" />
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
                                ? 'bg-terracotta text-white shadow-lg shadow-terracotta/30' 
                                : 'text-warm-muted text-dark-muted hover:bg-terracotta/10 hover:text-terracotta',
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
                        <div v-if="isCollapsed && !isMobileOpen && hoveredItem === item.name" class="absolute left-full ml-4 px-3 py-2 bg-white bg-dark-bg border border-terracotta/20 rounded-xl text-xs font-black shadow-2xl animate-fade-in-right z-50 whitespace-nowrap">
                            {{ item.name }}
                        </div>
                    </Link>
                </template>
            </nav>

            <!-- Bottom Profile Section -->
            <div class="p-4 border-t border-terracotta/20">
                <div 
                    :class="[
                        'rounded-2xl p-4 transition-all duration-300',
                        isCollapsed && !isMobileOpen ? 'items-center p-2' : 'bg-terracotta/5'
                    ]"
                >
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-terracotta flex items-center justify-center text-white font-black shadow-md">
                            {{ user.name.charAt(0) }}
                        </div>
                        <div v-if="!isCollapsed || isMobileOpen" class="flex-1 min-w-0 animate-fade-in">
                            <p class="text-xs font-black text-warm-text text-dark-text truncate">{{ user.name }}</p>
                            <p class="text-[10px] font-bold text-terracotta uppercase tracking-wider truncate">{{ userRoles[0] }}</p>
                        </div>
                        <Link 
                            v-if="!isCollapsed || isMobileOpen" 
                            :href="route('logout')" 
                            method="post" 
                            as="button" 
                            class="p-2 hover:bg-destructive/10 hover:text-destructive rounded-lg text-warm-muted/70 transition-all"
                        >
                            <LogOut class="w-4 h-4" />
                        </Link>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 flex flex-col min-w-0 relative h-screen overflow-hidden">
            <!-- Floating Header -->
            <Header 
                @toggle-mobile-menu="toggleMobileMenu"
                :class="[
                    'transition-all duration-500 z-20',
                    isScrolled ? 'glass-warm shadow-xl shadow-terracotta/10' : 'bg-transparent'
                ]"
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
</div>
</template>
