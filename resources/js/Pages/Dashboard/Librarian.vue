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
        color: 'bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400'
    },
    {
        title: 'Book Returned',
        description: 'Physics Fundamentals by Jane Smith',
        time: '3 hours ago',
        icon: Archive,
        color: 'bg-emerald-100 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400'
    },
    {
        title: 'New Member',
        description: 'Student library membership created',
        time: '5 hours ago',
        icon: UserPlus,
        color: 'bg-purple-100 dark:bg-purple-900/30 text-purple-600 dark:text-purple-400'
    },
    {
        title: 'Fine Collected',
        description: 'UGX 5,000 overdue fine payment',
        time: '1 day ago',
        icon: DollarSign,
        color: 'bg-orange-100 dark:bg-orange-900/30 text-orange-600 dark:text-orange-400'
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
    <Head title="Librarian Dashboard" />

    <Sidebar>
        <template #header-title>
            <div class="flex items-center space-x-3">
                <Library class="w-5 h-5" />
                <span class="font-semibold">Library Management</span>
            </div>
        </template>

        <div class="mx-auto max-w-7xl space-y-8 animate-fade-in-up">
            <!-- Professional Header Section -->
            <div class="relative overflow-hidden bg-card shadow-sm rounded-3xl lg:rounded-[3rem] border border-border p-6 lg:p-12">
                <div class="flex flex-col md:flex-row items-center md:items-end gap-6 lg:gap-8">
                    <div class="w-20 h-20 lg:w-24 lg:h-24 bg-muted rounded-2xl lg:rounded-[2.5rem] flex items-center justify-center border border-border shadow-inner overflow-hidden">
                        <Avatar class="w-full h-full rounded-none">
                            <AvatarImage 
                                v-if="$page.props.auth.user.photo"
                                :src="`/storage/${$page.props.auth.user.photo}`" 
                                :alt="$page.props.auth.user.name"
                                class="object-cover"
                            />
                            <AvatarFallback class="text-xl lg:text-2xl font-black bg-primary text-primary-foreground">
                                {{ getInitials($page.props.auth.user.name) }}
                            </AvatarFallback>
                        </Avatar>
                    </div>
                    
                    <div class="text-center md:text-left space-y-2 lg:space-y-4">
                        <div class="flex items-center justify-center md:justify-start gap-2">
                            <Badge class="bg-primary/10 text-primary border-0 font-black uppercase text-[9px] tracking-widest px-3 lg:px-4 py-1 h-7">
                                Knowledge Custodian
                            </Badge>
                            <span class="text-[10px] font-black text-muted-foreground uppercase tracking-widest">Library HQ</span>
                        </div>
                        <h1 class="text-3xl lg:text-5xl font-black text-foreground tracking-tighter leading-tight">
                            Welcome, {{ librarian?.name || $page.props.auth.user.name }}
                        </h1>
                        <p class="text-muted-foreground font-bold text-xs lg:text-sm uppercase tracking-wide">
                            {{ new Date().toLocaleDateString('en-US', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' }) }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Library Matrix -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-6">
                <!-- Catalog Size -->
                <div class="bg-card p-5 lg:p-6 rounded-3xl lg:rounded-[2.5rem] border border-border shadow-sm group hover:border-primary/30 transition-all duration-300">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 rounded-2xl bg-indigo-50 dark:bg-indigo-900/10 flex items-center justify-center text-indigo-600 font-bold shadow-inner transition-transform group-hover:scale-110">
                            <Library class="w-6 h-6" />
                        </div>
                        <Badge class="bg-indigo-500 text-white border-0 font-black text-[9px] uppercase tracking-widest">Catalog</Badge>
                    </div>
                    <p class="text-xs font-black text-muted-foreground/80 uppercase tracking-widest mb-1">Total Books</p>
                    <h3 class="text-3xl font-black text-foreground tracking-tighter leading-none">{{ libraryStats.totalBooks.toLocaleString() }}</h3>
                </div>

                <!-- Active Loans -->
                <div class="bg-card p-5 lg:p-6 rounded-3xl lg:rounded-[2.5rem] border border-border shadow-sm group hover:border-emerald-500/30 transition-all duration-300">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 rounded-2xl bg-emerald-50 dark:bg-emerald-900/10 flex items-center justify-center text-emerald-600 font-bold shadow-inner transition-transform group-hover:scale-110">
                            <BookOpen class="w-6 h-6" />
                        </div>
                        <Badge class="bg-muted border-0 text-muted-foreground font-black text-[9px] uppercase tracking-widest">Active</Badge>
                    </div>
                    <p class="text-xs font-black text-muted-foreground/80 uppercase tracking-widest mb-1">Books Issued</p>
                    <h3 class="text-3xl font-black text-foreground tracking-tighter leading-none">{{ libraryStats.issuedBooks }}</h3>
                </div>

                <!-- Overdue Status -->
                <div class="bg-card p-5 lg:p-6 rounded-3xl lg:rounded-[2.5rem] border border-border shadow-sm group hover:border-orange-500/30 transition-all duration-300">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 rounded-2xl bg-orange-50 dark:bg-orange-900/10 flex items-center justify-center text-orange-600 font-bold shadow-inner transition-transform group-hover:scale-110">
                            <AlertTriangle class="w-6 h-6" />
                        </div>
                        <Badge class="bg-orange-500 text-white border-0 font-black text-[9px] uppercase tracking-widest">Alerts</Badge>
                    </div>
                    <p class="text-xs font-black text-muted-foreground/80 uppercase tracking-widest mb-1">Overdue Books</p>
                    <h3 class="text-3xl font-black text-foreground tracking-tighter leading-none">{{ libraryStats.overdueBooks }}</h3>
                </div>

                <!-- Daily Deadline -->
                <div class="bg-card p-5 lg:p-6 rounded-3xl lg:rounded-[2.5rem] border border-border shadow-sm group hover:border-rose-500/30 transition-all duration-300">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 rounded-2xl bg-rose-50 dark:bg-rose-900/10 flex items-center justify-center text-rose-600 font-bold shadow-inner transition-transform group-hover:scale-110">
                            <Clock class="w-6 h-6" />
                        </div>
                        <Badge class="bg-muted border-0 text-muted-foreground font-black text-[9px] uppercase tracking-widest">Today</Badge>
                    </div>
                    <p class="text-xs font-black text-muted-foreground/80 uppercase tracking-widest mb-1">Due Today</p>
                    <h3 class="text-3xl font-black text-foreground tracking-tighter leading-none">{{ libraryStats.dueToday }}</h3>
                </div>
            </div>

            <!-- Library Intelligence Tools -->
            <Card class="bg-card shadow-sm border-border rounded-3xl lg:rounded-[3rem] overflow-hidden">
                <CardHeader class="p-6 lg:p-8 border-b border-border bg-muted/50">
                    <CardTitle class="text-2xl font-black tracking-tighter flex items-center gap-3">
                        <Zap class="w-6 h-6 text-primary" />
                        Strategic Library Terminal
                    </CardTitle>
                </CardHeader>
                <CardContent class="p-6 lg:p-8">
                    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4 lg:gap-6">
                        <Link
                            v-for="action in quickActions"
                            :key="action.title"
                            :href="action.href"
                            class="group relative p-6 rounded-[2rem] bg-muted border border-border transition-all duration-300 lg:hover:border-primary lg:hover:shadow-lg"
                        >
                            <div class="flex flex-col items-center text-center space-y-4">
                                <div class="w-14 h-14 rounded-2xl bg-card border border-border flex items-center justify-center shadow-inner lg:group-hover:bg-primary lg:group-hover:text-primary-foreground lg:group-hover:border-primary transition-all">
                                    <component :is="action.icon" class="w-7 h-7" />
                                </div>
                                <div>
                                    <h3 class="text-xs font-black text-foreground uppercase tracking-widest leading-none mb-1">{{ action.title }}</h3>
                                    <p class="text-[9px] font-bold text-muted-foreground uppercase tracking-widest opacity-60 line-clamp-1">{{ action.description }}</p>
                                </div>
                            </div>
                        </Link>
                    </div>
                </CardContent>
            </Card>

            <!-- Library Resource Hub -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Recent Catalog & Actions -->
                <div class="lg:col-span-2 space-y-6 lg:space-y-8">
                    <Card class="bg-card shadow-sm border-border rounded-3xl lg:rounded-[3rem] overflow-hidden">
                        <CardHeader class="p-6 lg:p-8 border-b border-border">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                                <CardTitle class="text-2xl font-black tracking-tighter flex items-center gap-3">
                                    <Book class="w-6 h-6 text-primary" />
                                    Active Catalog
                                </CardTitle>
                                <Button variant="ghost" size="sm" class="rounded-xl font-black text-[10px] uppercase tracking-widest text-muted-foreground border border-border w-full sm:w-auto">
                                    View Repository <ChevronRight class="w-4 h-4 ml-1" />
                                </Button>
                            </div>
                        </CardHeader>
                        <CardContent class="p-4 lg:p-8">
                            <div class="space-y-4">
                                <div v-for="(book, index) in recentBooks" :key="index" class="flex flex-col sm:flex-row sm:items-center gap-4 lg:gap-6 p-4 lg:p-5 rounded-3xl lg:rounded-[2.5rem] bg-muted/50 border border-border group transition-all lg:hover:bg-card lg:hover:shadow-md">
                                    <div :class="[
                                        'w-12 h-12 rounded-2xl flex items-center justify-center font-bold shadow-inner shrink-0 lg:group-hover:scale-110 transition-transform',
                                        book.status === 'available' ? 'bg-emerald-50 text-emerald-600' :
                                        book.status === 'issued' ? 'bg-blue-50 text-blue-600' :
                                        'bg-rose-50 text-rose-600'
                                    ]">
                                        <Book class="w-6 h-6" />
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center justify-between mb-2">
                                            <h4 class="text-lg font-black text-foreground tracking-tighter truncate">{{ book.title }}</h4>
                                            <Badge :class="[
                                                'font-black text-[10px] uppercase h-6 px-3 border-0',
                                                book.status === 'available' ? 'bg-emerald-50 text-emerald-600' :
                                                book.status === 'issued' ? 'bg-blue-50 text-blue-600' :
                                                'bg-rose-50 text-rose-600'
                                            ]">
                                                {{ book.status }}
                                            </Badge>
                                        </div>
                                        <div class="flex flex-wrap items-center gap-4 text-[10px] font-black uppercase text-muted-foreground/80">
                                            <span class="flex items-center gap-1.5"><Tag class="w-3.5 h-3.5 text-indigo-400" /> {{ book.isbn }}</span>
                                            <span class="flex items-center gap-1.5"><BookOpen class="w-3.5 h-3.5 text-blue-400" /> {{ book.category }}</span>
                                            <span class="flex items-center gap-1.5"><Archive class="w-3.5 h-3.5 text-amber-400" /> {{ book.copies }} Unit(s)</span>
                                        </div>
                                        <div v-if="book.dueDate" class="mt-2 pt-2 border-t border-border flex items-center gap-4 text-[9px] font-black uppercase text-indigo-500">
                                            <span class="flex items-center gap-1.5"><Clock class="w-3 h-3" /> Due: {{ book.dueDate }}</span>
                                            <span class="flex items-center gap-1.5"><Users class="w-3 h-3" /> Holder: {{ book.issuedTo }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </CardContent>
                    </Card>

                    <!-- Activity Log -->
                    <Card class="bg-card shadow-sm border-border rounded-3xl lg:rounded-[3rem] overflow-hidden">
                        <CardHeader class="p-6 lg:p-8 border-b border-border bg-muted/50">
                            <CardTitle class="text-xl font-black tracking-tighter flex items-center gap-3">
                                <Activity class="w-5 h-5 text-primary" />
                                Resource Circulation Log
                            </CardTitle>
                        </CardHeader>
                        <CardContent class="p-6 lg:p-8">
                            <div class="space-y-4">
                                <div v-for="activity in recentActivities" :key="activity.title" class="flex items-center gap-6 p-4 rounded-[2rem] border border-border group transition-all hover:bg-muted/50">
                                    <div :class="[activity.color, 'w-10 h-10 rounded-xl flex items-center justify-center shrink-0 group-hover:scale-110 transition-transform']">
                                        <component :is="activity.icon" class="w-5 h-5" />
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <h4 class="text-sm font-black text-foreground tracking-tight">{{ activity.title }}</h4>
                                        <p class="text-xs font-bold text-muted-foreground uppercase tracking-wide truncate">{{ activity.description }}</p>
                                        <div class="flex items-center gap-2 mt-1 text-[9px] font-black uppercase text-gray-400">
                                            <Clock class="w-3 h-3 text-indigo-400" /> {{ activity.time }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </CardContent>
                    </Card>
                </div>

                <!-- Strategic Sidebar intelligence -->
                <div class="space-y-8">
                    <!-- Overdue Inventory Alerts -->
                    <Card class="bg-card shadow-sm border-border rounded-3xl lg:rounded-[3rem] overflow-hidden">
                        <CardHeader class="p-6 lg:p-8 border-b border-border">
                            <CardTitle class="text-xl font-black tracking-tighter flex items-center gap-3">
                                <AlertTriangle class="w-6 h-6 text-rose-500" />
                                Delinquency Alerts
                                <Badge class="bg-rose-500 text-white border-0 font-black text-[10px]">{{ overdueBooks.length }}</Badge>
                            </CardTitle>
                        </CardHeader>
                        <CardContent class="p-6 lg:p-8">
                            <div class="space-y-4">
                                <div v-for="book in overdueBooks" :key="book.title" class="p-5 rounded-[2rem] bg-rose-50/50 dark:bg-rose-900/10 border border-rose-100 dark:border-rose-900/20 group hover:border-rose-500 transition-all">
                                    <h4 class="text-base font-black text-foreground tracking-tight leading-none mb-2">{{ book.title }}</h4>
                                    <p class="text-[10px] font-bold text-muted-foreground uppercase tracking-widest mb-4">{{ book.student }}</p>
                                    <div class="flex items-center justify-between">
                                        <Badge variant="outline" class="font-black text-[9px] h-6 border-rose-200 text-rose-700 uppercase">
                                            +{{ book.days }} Days Delay
                                        </Badge>
                                        <span class="text-sm font-black text-rose-600">UGX {{ book.fine.toLocaleString() }}</span>
                                    </div>
                                </div>
                            </div>
                        </CardContent>
                    </Card>

                    <!-- Membership Analytics -->
                    <Card class="bg-card shadow-sm border-border rounded-3xl lg:rounded-[3rem] overflow-hidden">
                        <CardHeader class="p-6 lg:p-8 border-b border-border">
                            <CardTitle class="text-xl font-black tracking-tighter flex items-center gap-3 text-foreground/80">
                                <Users class="w-5 h-5" />
                                Member Engagement
                            </CardTitle>
                        </CardHeader>
                        <CardContent class="p-6 lg:p-8">
                            <div class="space-y-6">
                                <div v-for="(val, label) in {
                                    'Institutional Members': libraryStats.totalMembers,
                                    'Acquisition Rate': libraryStats.newMembers + ' Monthly',
                                    'Active Readers': libraryStats.issuedBooks,
                                    'Circulation Ratio': Math.round((libraryStats.issuedBooks / libraryStats.totalBooks) * 100) + '%'
                                }" :key="label" class="flex flex-col sm:flex-row sm:items-center justify-between py-1 group gap-1">
                                    <span class="text-[11px] font-black text-muted-foreground uppercase tracking-widest lg:group-hover:text-primary transition-colors">{{ label }}</span>
                                    <span class="text-sm font-black text-foreground tracking-tight">{{ val }}</span>
                                </div>
                                <Button class="w-full h-12 rounded-[1.5rem] border border-border font-black text-[10px] uppercase tracking-widest lg:hover:bg-primary lg:hover:text-primary-foreground lg:hover:border-primary transition-all" variant="outline">
                                    <UserPlus class="w-4 h-4 mr-2" /> Register Member
                                </Button>
                            </div>
                        </CardContent>
                    </Card>

                    <!-- Analytical Export -->
                    <div class="bg-primary rounded-3xl lg:rounded-[3rem] p-8 lg:p-10 text-primary-foreground shadow-2xl relative overflow-hidden group">
                        <div class="relative z-10 text-center space-y-6">
                            <div class="w-16 h-16 lg:w-20 lg:h-20 bg-white/10 rounded-2xl lg:rounded-[2rem] flex items-center justify-center mx-auto border border-white/20 lg:group-hover:scale-110 transition-transform">
                                <Library class="w-8 h-8 lg:w-10 lg:h-10 text-indigo-300" />
                            </div>
                            <div>
                                <h3 class="text-xl lg:text-2xl font-black tracking-tighter leading-none mb-3">Catalog Analytics</h3>
                                <p class="text-primary-foreground/60 text-xs font-bold uppercase tracking-wide leading-relaxed px-2">
                                    Export comprehensive library health and resource utilization metrics.
                                </p>
                            </div>
                            <Button class="w-full bg-background text-primary hover:bg-background/90 font-black rounded-2xl h-12 text-[10px] uppercase tracking-widest border-0 shadow-lg">
                                <Download class="w-4 h-4 mr-2" /> Export Global Intel
                            </Button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </Sidebar>
</template>