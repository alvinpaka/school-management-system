<script setup>
import { Card, CardContent, CardDescription, CardFooter, CardHeader, CardTitle } from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';
import { Separator } from '@/components/ui/separator';
import { Head, Link } from '@inertiajs/vue3';
import Sidebar from '@/Components/Sidebar.vue';
import { 
    Users,
    GraduationCap,
    BookOpen,
    Calendar,
    DollarSign,
    TrendingUp,
    UserCheck,
    FileText,
    Bell,
    Clock,
    CheckCircle,
    AlertCircle,
    Home,
    Award,
    BarChart3,
    Library,
    Book,
    Search,
    Plus,
    Edit,
    Trash2,
    Download,
    Eye,
    AlertTriangle,
    Archive,
    Sparkles,
    ChevronRight,
    Zap,
    Activity,
    BookMarked,
    UserPlus,
    Tag
} from 'lucide-vue-next';

defineProps({
    librarian: Object,
    stats: Object
});

const quickActions = [
    {
        title: 'Add Book',
        description: 'Register new',
        icon: Plus,
        color: 'from-blue-500 to-blue-600',
        href: '#'
    },
    {
        title: 'Search Books',
        description: 'Find in library',
        icon: Search,
        color: 'from-emerald-500 to-emerald-600',
        href: '#'
    },
    {
        title: 'Issue Book',
        description: 'Lend to student',
        icon: BookOpen,
        color: 'from-purple-500 to-purple-600',
        href: '#'
    },
    {
        title: 'Return Book',
        description: 'Process return',
        icon: Archive,
        color: 'from-orange-500 to-orange-600',
        href: '#'
    },
    {
        title: 'Members',
        description: 'Manage users',
        icon: Users,
        color: 'from-pink-500 to-pink-600',
        href: route('students.index')
    },
    {
        title: 'Collect Fine',
        description: 'Overdue fines',
        icon: DollarSign,
        color: 'from-indigo-500 to-indigo-600',
        href: '#'
    }
];

const recentActivities = [
    {
        title: 'Book Issued',
        description: 'Advanced Mathematics to John Doe',
        time: '1 hour ago',
        icon: BookOpen,
        color: 'bg-terracotta/10 text-terracotta'
    },
    {
        title: 'Book Returned',
        description: 'Physics Fundamentals by Jane Smith',
        time: '3 hours ago',
        icon: Archive,
        color: 'bg-forest/10 text-forest'
    },
    {
        title: 'New Member',
        description: 'Student library membership created',
        time: '5 hours ago',
        icon: UserPlus,
        color: 'bg-amber/10 text-amber'
    },
    {
        title: 'Fine Collected',
        description: 'KES 5,000 overdue fine payment',
        time: '1 day ago',
        icon: DollarSign,
        color: 'bg-destructive/10 text-destructive'
    }
];

const libraryStats = {
    totalBooks: 5420,
    issuedBooks: 1234,
    overdueBooks: 45,
    dueToday: 23,
    newMembers: 8,
    totalMembers: 156
};

const recentBooks = [
    {
        title: 'Advanced Mathematics',
        author: 'Robert Johnson',
        isbn: '978-0123456789',
        category: 'Education',
        status: 'available',
        dueDate: null,
        copies: 5
    },
    {
        title: 'Physics Fundamentals',
        author: 'Sarah Williams',
        isbn: '978-0123456790',
        category: 'Science',
        status: 'issued',
        dueDate: '2026-02-20',
        issuedTo: 'John Doe',
        copies: 3
    },
    {
        title: 'World History',
        author: 'Michael Brown',
        isbn: '978-0123456791',
        category: 'History',
        status: 'overdue',
        dueDate: '2026-02-10',
        issuedTo: 'Jane Smith',
        copies: 1
    }
];

const overdueBooks = [
    { title: 'Chemistry Basics', student: 'Alice Johnson', days: 5, fine: 5000 },
    { title: 'English Literature', student: 'Bob Williams', days: 3, fine: 3000 },
    { title: 'Biology Advanced', student: 'Carol Davis', days: 7, fine: 7000 }
];

const getInitials = (name) => {
    return name
        .split(' ')
        .map(word => word[0])
        .join('')
        .toUpperCase()
        .slice(0, 2);
};
</script>

<template>
    <Head title="Librarian Dashboard | EduManage Pro" />

    <Sidebar>
        <template #header-title>
            <div class="flex items-center space-x-3">
                <Library class="w-5 h-5 text-terracotta" />
                <span class="font-semibold text-warm-text dark:text-dark-text">Library Management</span>
            </div>
        </template>

        <div class="mx-auto max-w-7xl space-y-8 animate-fade-in-up">
            <!-- Professional Header Section -->
            <div class="relative overflow-hidden card-warm rounded-3xl lg:rounded-[3rem] p-6 lg:p-12">
                <div class="flex flex-col md:flex-row items-center md:items-end gap-6 lg:gap-8">
                    <div class="w-20 h-20 lg:w-24 lg:h-24 bg-white/50 dark:bg-dark-bg/50 rounded-2xl lg:rounded-[2.5rem] flex items-center justify-center border border-terracotta/20 shadow-inner overflow-hidden">
                        <Avatar class="w-full h-full rounded-none">
                            <AvatarImage 
                                v-if="$page.props.auth.user.photo"
                                :src="`/storage/${$page.props.auth.user.photo}`" 
                                :alt="$page.props.auth.user.name"
                                class="object-cover"
                            />
                            <AvatarFallback class="text-xl lg:text-2xl font-black bg-terracotta text-white">
                                {{ getInitials($page.props.auth.user.name) }}
                            </AvatarFallback>
                        </Avatar>
                    </div>
                    
                    <div class="text-center md:text-left space-y-2 lg:space-y-4">
                        <div class="flex items-center justify-center md:justify-start gap-2">
                            <Badge class="bg-terracotta/10 text-terracotta border-0 font-black uppercase text-[9px] tracking-widest px-3 lg:px-4 py-1 h-7">
                                Knowledge Custodian
                            </Badge>
                            <span class="text-[10px] font-black text-warm-muted dark:text-dark-muted uppercase tracking-widest">Library HQ</span>
                        </div>
                        <h1 class="text-3xl lg:text-5xl font-black text-warm-text dark:text-dark-text tracking-tighter leading-tight">
                            Welcome, {{ librarian?.name || $page.props.auth.user.name }}
                        </h1>
                        <p class="text-warm-muted dark:text-dark-muted font-bold text-xs lg:text-sm uppercase tracking-wide">
                            {{ new Date().toLocaleDateString('en-US', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' }) }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Library Matrix -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-6">
                <!-- Catalog Size -->
                <div class="card-warm p-5 lg:p-6 rounded-3xl lg:rounded-[2.5rem] group hover:border-terracotta/30 transition-all duration-300">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 rounded-2xl bg-terracotta/10 dark:bg-terracotta/20 flex items-center justify-center text-terracotta font-bold shadow-inner transition-transform group-hover:scale-110">
                            <Library class="w-6 h-6" />
                        </div>
                        <Badge class="bg-terracotta text-white border-0 font-black text-[9px] uppercase tracking-widest">Catalog</Badge>
                    </div>
                    <p class="text-xs font-black text-warm-muted dark:text-dark-muted/80 uppercase tracking-widest mb-1">Total Books</p>
                    <h3 class="text-3xl font-black text-warm-text dark:text-dark-text tracking-tighter leading-none">{{ libraryStats.totalBooks.toLocaleString() }}</h3>
                </div>

                <!-- Active Loans -->
                <div class="card-warm p-5 lg:p-6 rounded-3xl lg:rounded-[2.5rem] group hover:border-forest/30 transition-all duration-300">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 rounded-2xl bg-forest/10 dark:bg-forest/20 flex items-center justify-center text-forest dark:text-[#5AAA72] font-bold shadow-inner transition-transform group-hover:scale-110">
                            <BookOpen class="w-6 h-6" />
                        </div>
                        <Badge class="bg-muted border-0 text-warm-muted dark:text-dark-muted font-black text-[9px] uppercase tracking-widest">Active</Badge>
                    </div>
                    <p class="text-xs font-black text-warm-muted dark:text-dark-muted/80 uppercase tracking-widest mb-1">Books Issued</p>
                    <h3 class="text-3xl font-black text-warm-text dark:text-dark-text tracking-tighter leading-none">{{ libraryStats.issuedBooks }}</h3>
                </div>

                <!-- Overdue Status -->
                <div class="card-warm p-5 lg:p-6 rounded-3xl lg:rounded-[2.5rem] group hover:border-amber/30 transition-all duration-300">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 rounded-2xl bg-amber/10 dark:bg-amber/20 flex items-center justify-center text-amber font-bold shadow-inner transition-transform group-hover:scale-110">
                            <AlertTriangle class="w-6 h-6" />
                        </div>
                        <Badge class="bg-amber text-white border-0 font-black text-[9px] uppercase tracking-widest">Alerts</Badge>
                    </div>
                    <p class="text-xs font-black text-warm-muted dark:text-dark-muted/80 uppercase tracking-widest mb-1">Overdue Books</p>
                    <h3 class="text-3xl font-black text-warm-text dark:text-dark-text tracking-tighter leading-none">{{ libraryStats.overdueBooks }}</h3>
                </div>

                <!-- Daily Deadline -->
                <div class="card-warm p-5 lg:p-6 rounded-3xl lg:rounded-[2.5rem] group hover:border-destructive/30 transition-all duration-300">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 rounded-2xl bg-destructive/10 dark:bg-destructive/20 flex items-center justify-center text-destructive font-bold shadow-inner transition-transform group-hover:scale-110">
                            <Clock class="w-6 h-6" />
                        </div>
                        <Badge class="bg-muted border-0 text-warm-muted dark:text-dark-muted font-black text-[9px] uppercase tracking-widest">Today</Badge>
                    </div>
                    <p class="text-xs font-black text-warm-muted dark:text-dark-muted/80 uppercase tracking-widest mb-1">Due Today</p>
                    <h3 class="text-3xl font-black text-warm-text dark:text-dark-text tracking-tighter leading-none">{{ libraryStats.dueToday }}</h3>
                </div>
            </div>

            <!-- Library Intelligence Tools -->
            <div class="card-warm rounded-3xl lg:rounded-[3rem] overflow-hidden">
                <div class="p-6 lg:p-8 border-b border-terracotta/20 bg-terracotta/5">
                    <h3 class="text-2xl font-black tracking-tighter flex items-center gap-3 text-warm-text dark:text-dark-text">
                        <Zap class="w-6 h-6 text-terracotta" />
                        Strategic Library Terminal
                    </h3>
                </div>
                <div class="p-6 lg:p-8">
                    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4 lg:gap-6">
                        <Link
                            v-for="action in quickActions"
                            :key="action.title"
                            :href="action.href"
                            class="group relative p-6 rounded-[2rem] bg-terracotta/5 border border-terracotta/20 transition-all duration-300 lg:hover:border-terracotta lg:hover:shadow-lg"
                        >
                            <div class="flex flex-col items-center text-center space-y-4">
                                <div class="w-14 h-14 rounded-2xl bg-white dark:bg-dark-bg border border-terracotta/20 flex items-center justify-center shadow-inner lg:group-hover:bg-terracotta lg:group-hover:text-white lg:group-hover:border-terracotta transition-all">
                                    <component :is="action.icon" class="w-7 h-7 text-terracotta lg:group-hover:text-white" />
                                </div>
                                <div>
                                    <h3 class="text-xs font-black text-warm-text dark:text-dark-text uppercase tracking-widest leading-none mb-1">{{ action.title }}</h3>
                                    <p class="text-[9px] font-bold text-warm-muted dark:text-dark-muted uppercase tracking-widest opacity-60 line-clamp-1">{{ action.description }}</p>
                                </div>
                            </div>
                        </Link>
                    </div>
                </div>
            </div>

            <!-- Library Resource Hub -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Recent Catalog & Actions -->
                <div class="lg:col-span-2 space-y-6 lg:space-y-8">
                    <div class="card-warm rounded-3xl lg:rounded-[3rem] overflow-hidden">
                        <div class="p-6 lg:p-8 border-b border-terracotta/20">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                                <h3 class="text-2xl font-black tracking-tighter flex items-center gap-3 text-warm-text dark:text-dark-text">
                                    <Book class="w-6 h-6 text-terracotta" />
                                    Active Catalog
                                </h3>
                                <Button variant="ghost" size="sm" class="rounded-xl font-black text-[10px] uppercase tracking-widest text-warm-muted dark:text-dark-muted border border-terracotta/20 w-full sm:w-auto">
                                    View Repository <ChevronRight class="w-4 h-4 ml-1" />
                                </Button>
                            </div>
                        </div>
                        <div class="p-4 lg:p-8">
                            <div class="space-y-4">
                                <div v-for="(book, index) in recentBooks" :key="index" class="flex flex-col sm:flex-row sm:items-center gap-4 lg:gap-6 p-4 lg:p-5 rounded-3xl lg:rounded-[2.5rem] bg-terracotta/5 border border-terracotta/20 group transition-all lg:hover:bg-white lg:hover:shadow-md">
                                    <div :class="[
                                        'w-12 h-12 rounded-2xl flex items-center justify-center font-bold shadow-inner shrink-0 lg:group-hover:scale-110 transition-transform',
                                        book.status === 'available' ? 'bg-forest/10 text-forest' :
                                        book.status === 'issued' ? 'bg-terracotta/10 text-terracotta' :
                                        'bg-destructive/10 text-destructive'
                                    ]">
                                        <Book class="w-6 h-6" />
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center justify-between mb-2">
                                            <h4 class="text-lg font-black text-warm-text dark:text-dark-text tracking-tighter truncate">{{ book.title }}</h4>
                                            <Badge :class="[
                                                'font-black text-[10px] uppercase h-6 px-3 border-0',
                                                book.status === 'available' ? 'bg-forest/10 text-forest' :
                                                book.status === 'issued' ? 'bg-terracotta/10 text-terracotta' :
                                                'bg-destructive/10 text-destructive'
                                            ]">
                                                {{ book.status }}
                                            </Badge>
                                        </div>
                                        <div class="flex flex-wrap items-center gap-4 text-[10px] font-black uppercase text-warm-muted dark:text-dark-muted/80">
                                            <span class="flex items-center gap-1.5"><Tag class="w-3.5 h-3.5 text-terracotta/80" /> {{ book.isbn }}</span>
                                            <span class="flex items-center gap-1.5"><BookOpen class="w-3.5 h-3.5 text-terracotta/80" /> {{ book.category }}</span>
                                            <span class="flex items-center gap-1.5"><Archive class="w-3.5 h-3.5 text-amber" /> {{ book.copies }} Unit(s)</span>
                                        </div>
                                        <div v-if="book.dueDate" class="mt-2 pt-2 border-t border-terracotta/20 flex items-center gap-4 text-[9px] font-black uppercase text-terracotta">
                                            <span class="flex items-center gap-1.5"><Clock class="w-3 h-3" /> Due: {{ book.dueDate }}</span>
                                            <span class="flex items-center gap-1.5"><Users class="w-3 h-3" /> Holder: {{ book.issuedTo }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Activity Log -->
                    <div class="card-warm rounded-3xl lg:rounded-[3rem] overflow-hidden">
                        <div class="p-6 lg:p-8 border-b border-terracotta/20 bg-terracotta/5">
                            <h3 class="text-xl font-black tracking-tighter flex items-center gap-3 text-warm-text dark:text-dark-text">
                                <Activity class="w-5 h-5 text-terracotta" />
                                Resource Circulation Log
                            </h3>
                        </div>
                        <div class="p-6 lg:p-8">
                            <div class="space-y-4">
                                <div v-for="activity in recentActivities" :key="activity.title" class="flex items-center gap-6 p-4 rounded-[2rem] border border-terracotta/20 group transition-all hover:bg-terracotta/5">
                                    <div :class="[activity.color, 'w-10 h-10 rounded-xl flex items-center justify-center shrink-0 group-hover:scale-110 transition-transform']">
                                        <component :is="activity.icon" class="w-5 h-5" />
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <h4 class="text-sm font-black text-warm-text dark:text-dark-text tracking-tight">{{ activity.title }}</h4>
                                        <p class="text-xs font-bold text-warm-muted dark:text-dark-muted uppercase tracking-wide truncate">{{ activity.description }}</p>
                                        <div class="flex items-center gap-2 mt-1 text-[9px] font-black uppercase text-warm-muted dark:text-dark-muted/60">
                                            <Clock class="w-3 h-3 text-terracotta/80" /> {{ activity.time }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Strategic Sidebar intelligence -->
                <div class="space-y-8">
                    <!-- Overdue Inventory Alerts -->
                    <div class="card-warm rounded-3xl lg:rounded-[3rem] overflow-hidden">
                        <div class="p-6 lg:p-8 border-b border-terracotta/20">
                            <h3 class="text-xl font-black tracking-tighter flex items-center gap-3 text-warm-text dark:text-dark-text">
                                <AlertTriangle class="w-6 h-6 text-amber" />
                                Delinquency Alerts
                                <Badge class="bg-amber text-white border-0 font-black text-[10px]">{{ overdueBooks.length }}</Badge>
                            </h3>
                        </div>
                        <div class="p-6 lg:p-8">
                            <div class="space-y-4">
                                <div v-for="book in overdueBooks" :key="book.title" class="p-5 rounded-[2rem] bg-amber/5 border border-amber/20 group hover:border-amber transition-all">
                                    <h4 class="text-base font-black text-warm-text dark:text-dark-text tracking-tight leading-none mb-2">{{ book.title }}</h4>
                                    <p class="text-[10px] font-bold text-warm-muted dark:text-dark-muted uppercase tracking-widest mb-4">{{ book.student }}</p>
                                    <div class="flex items-center justify-between">
                                        <Badge variant="outline" class="font-black text-[9px] h-6 border-amber/30 text-amber uppercase">
                                            +{{ book.days }} Days Delay
                                        </Badge>
                                        <span class="text-sm font-black text-destructive">KES {{ book.fine.toLocaleString() }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Membership Analytics -->
                    <div class="card-warm rounded-3xl lg:rounded-[3rem] overflow-hidden">
                        <div class="p-6 lg:p-8 border-b border-terracotta/20">
                            <h3 class="text-xl font-black tracking-tighter flex items-center gap-3 text-warm-text dark:text-dark-text/80">
                                <Users class="w-5 h-5 text-terracotta" />
                                Member Engagement
                            </h3>
                        </div>
                        <div class="p-6 lg:p-8">
                            <div class="space-y-6">
                                <div v-for="(val, label) in {
                                    'Institutional Members': libraryStats.totalMembers,
                                    'Acquisition Rate': libraryStats.newMembers + ' Monthly',
                                    'Active Readers': libraryStats.issuedBooks,
                                    'Circulation Ratio': Math.round((libraryStats.issuedBooks / libraryStats.totalBooks) * 100) + '%'
                                }" :key="label" class="flex flex-col sm:flex-row sm:items-center justify-between py-1 group gap-1">
                                    <span class="text-[11px] font-black text-warm-muted dark:text-dark-muted uppercase tracking-widest lg:group-hover:text-terracotta transition-colors">{{ label }}</span>
                                    <span class="text-sm font-black text-warm-text dark:text-dark-text tracking-tight">{{ val }}</span>
                                </div>
                                <Button class="w-full h-12 rounded-[1.5rem] border border-terracotta/20 font-black text-[10px] uppercase tracking-widest lg:hover:bg-terracotta lg:hover:text-white lg:hover:border-terracotta transition-all" variant="outline">
                                    <UserPlus class="w-4 h-4 mr-2" /> Register Member
                                </Button>
                            </div>
                        </div>
                    </div>

                    <!-- Analytical Export -->
                    <div class="accent-terracotta rounded-3xl lg:rounded-[3rem] p-8 lg:p-10 text-white shadow-2xl shadow-terracotta/30 relative overflow-hidden group">
                        <div class="relative z-10 text-center space-y-6">
                            <div class="w-16 h-16 lg:w-20 lg:h-20 bg-white/10 rounded-2xl lg:rounded-[2rem] flex items-center justify-center mx-auto border border-white/20 lg:group-hover:scale-110 transition-transform">
                                <Library class="w-8 h-8 lg:w-10 lg:h-10 text-white" />
                            </div>
                            <div>
                                <h3 class="text-xl lg:text-2xl font-black tracking-tighter leading-none mb-3">Catalog Analytics</h3>
                                <p class="text-white/70 text-xs font-bold uppercase tracking-wide leading-relaxed px-2">
                                    Export comprehensive library health and resource utilization metrics.
                                </p>
                            </div>
                            <Button class="w-full bg-white text-terracotta hover:bg-white/90 font-black rounded-2xl h-12 text-[10px] uppercase tracking-widest border-0 shadow-lg">
                                <Download class="w-4 h-4 mr-2" /> Export Global Intel
                            </Button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </Sidebar>
</template>