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

        <div class="mx-auto max-w-7xl space-y-6">
            <!-- Welcome Header -->
            <Card class="overflow-hidden border-0">
                <div class="h-24"></div>
                <CardContent class="relative pt-0 pb-6 -mt-12">
                    <div class="flex flex-col md:flex-row md:items-end md:justify-between">
                        <div class="flex items-center space-x-4">
                            <Avatar class="w-20 h-20 border-2 border-gray dark:border-gray-700">
                                <AvatarImage 
                                    v-if="$page.props.auth.user.photo"
                                    :src="`/storage/${$page.props.auth.user.photo}`" 
                                    :alt="$page.props.auth.user.name"
                                />
                                <AvatarFallback class="text-2xl font-bold bg-indigo-500 text-white">
                                    {{ getInitials($page.props.auth.user.name) }}
                                </AvatarFallback>
                            </Avatar>
                            <div class="mt-4">
                                <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white flex items-center gap-2">
                                    Welcome back, {{ $page.props.auth.user.name }}
                                    <BookMarked class="w-6 h-6 text-indigo-500" />
                                </h1>
                                <p class="text-base text-gray-600 dark:text-gray-400">
                                    Managing knowledge and resources
                                </p>
                            </div>
                        </div>
                        <div class="mt-4 md:mt-0 mb-2">
                            <div class="text-sm text-gray-600 dark:text-gray-400">
                                {{ new Date().toLocaleDateString('en-US', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' }) }}
                            </div>
                        </div>
                    </div>
                </CardContent>
            </Card>

            <!-- Library Stats -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Total Books -->
                <Card class="hover:shadow-lg transition-all duration-200 border-indigo-500">
                    <CardContent class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div class="p-3 bg-indigo-100 dark:bg-indigo-900/30 rounded-xl">
                                <Library class="w-6 h-6 text-indigo-600 dark:text-indigo-400" />
                            </div>
                            <Badge variant="secondary" class="bg-indigo-100 text-indigo-700 dark:bg-indigo-900/30 dark:text-indigo-400">
                                Catalog
                            </Badge>
                        </div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Total Books</p>
                        <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ libraryStats.totalBooks.toLocaleString() }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                            <span class="text-indigo-600 dark:text-indigo-400 font-medium">In collection</span>
                        </p>
                    </CardContent>
                </Card>

                <!-- Books Issued -->
                <Card class="hover:shadow-lg transition-all duration-200 border-emerald-500">
                    <CardContent class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div class="p-3 bg-emerald-100 dark:bg-emerald-900/30 rounded-xl">
                                <BookOpen class="w-6 h-6 text-emerald-600 dark:text-emerald-400" />
                            </div>
                            <Badge variant="secondary" class="bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400">
                                Active
                            </Badge>
                        </div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Books Issued</p>
                        <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ libraryStats.issuedBooks }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                            <span class="text-emerald-600 dark:text-emerald-400 font-medium">Currently out</span>
                        </p>
                    </CardContent>
                </Card>

                <!-- Overdue Books -->
                <Card class="hover:shadow-lg transition-all duration-200 border-orange-500">
                    <CardContent class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div class="p-3 bg-orange-100 dark:bg-orange-900/30 rounded-xl">
                                <AlertTriangle class="w-6 h-6 text-orange-600 dark:text-orange-400" />
                            </div>
                            <Badge variant="secondary" class="bg-orange-100 text-orange-700 dark:bg-orange-900/30 dark:text-orange-400">
                                Alert
                            </Badge>
                        </div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Overdue Books</p>
                        <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ libraryStats.overdueBooks }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                            <span class="text-orange-600 dark:text-orange-400 font-medium">Requires action</span>
                        </p>
                    </CardContent>
                </Card>

                <!-- Due Today -->
                <Card class="hover:shadow-lg transition-all duration-200 border-red-500">
                    <CardContent class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div class="p-3 bg-red-100 dark:bg-red-900/30 rounded-xl">
                                <Clock class="w-6 h-6 text-red-600 dark:text-red-400" />
                            </div>
                            <Badge variant="secondary" class="bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400">
                                Today
                            </Badge>
                        </div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Due Today</p>
                        <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ libraryStats.dueToday }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                            <span class="text-red-600 dark:text-red-400 font-medium">Return deadline</span>
                        </p>
                    </CardContent>
                </Card>
            </div>

            <!-- Quick Actions -->
            <Card>
                <CardHeader>
                    <div class="flex items-center justify-between">
                        <div>
                            <CardTitle class="flex items-center gap-2">
                                <Zap class="w-5 h-5 text-indigo-500" />
                                Quick Actions
                            </CardTitle>
                            <CardDescription>
                                Library management tools
                            </CardDescription>
                        </div>
                    </div>
                </CardHeader>
                <CardContent>
                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
                        <Link
                            v-for="action in quickActions"
                            :key="action.title"
                            :href="action.href"
                            class="group relative overflow-hidden rounded-xl border-2 border-gray-200 dark:border-gray-700 hover:border-transparent transition-all duration-300"
                        >
                            <div class="absolute inset-0 bg-gradient-to-br opacity-0 group-hover:opacity-100 transition-opacity duration-300" :class="action.color"></div>
                            <div class="relative p-4 flex flex-col items-center text-center space-y-3">
                                <div class="p-3 rounded-xl bg-gray-100 dark:bg-gray-800 group-hover:bg-white/20 transition-colors duration-300">
                                    <component :is="action.icon" class="w-6 h-6 text-gray-700 dark:text-gray-300 group-hover:text-white transition-colors duration-300" />
                                </div>
                                <div>
                                    <h3 class="text-sm font-semibold text-gray-900 dark:text-white group-hover:text-white transition-colors duration-300">
                                        {{ action.title }}
                                    </h3>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 group-hover:text-white/80 transition-colors duration-300 mt-1">
                                        {{ action.description }}
                                    </p>
                                </div>
                            </div>
                        </Link>
                    </div>
                </CardContent>
            </Card>

            <!-- Main Content Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Recent Books & Activities -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Recent Books -->
                    <Card>
                        <CardHeader>
                            <div class="flex items-center justify-between">
                                <div>
                                    <CardTitle class="flex items-center gap-2">
                                        <Book class="w-5 h-5" />
                                        Recent Books
                                    </CardTitle>
                                    <CardDescription>
                                        Latest library catalog
                                    </CardDescription>
                                </div>
                                <Button variant="ghost" size="sm">
                                    View All
                                    <ChevronRight class="w-4 h-4 ml-1" />
                                </Button>
                            </div>
                        </CardHeader>
                        <CardContent>
                            <div class="space-y-3">
                                <div
                                    v-for="(book, index) in recentBooks"
                                    :key="index"
                                    class="flex items-start gap-4 p-4 rounded-xl border border-gray-100 dark:border-gray-800 hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-all duration-200"
                                >
                                    <div 
                                        :class="[
                                            'p-2.5 rounded-lg flex-shrink-0',
                                            book.status === 'available' ? 'bg-emerald-100 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400' :
                                            book.status === 'issued' ? 'bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400' :
                                            'bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400'
                                        ]"
                                    >
                                        <Book class="w-5 h-5" />
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center justify-between mb-1">
                                            <h4 class="text-sm font-semibold text-gray-900 dark:text-white">{{ book.title }}</h4>
                                            <Badge 
                                                :class="[
                                                    book.status === 'available' ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400' :
                                                    book.status === 'issued' ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400' :
                                                    'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400'
                                                ]"
                                            >
                                                {{ book.status === 'available' ? 'Available' : book.status === 'issued' ? 'Issued' : 'Overdue' }}
                                            </Badge>
                                        </div>
                                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">by {{ book.author }}</p>
                                        <div class="flex items-center gap-3 text-xs text-gray-500 dark:text-gray-400">
                                            <span class="flex items-center gap-1">
                                                <Tag class="w-3 h-3" />
                                                {{ book.isbn }}
                                            </span>
                                            <span class="flex items-center gap-1">
                                                <BookOpen class="w-3 h-3" />
                                                {{ book.category }}
                                            </span>
                                            <span class="flex items-center gap-1">
                                                <Archive class="w-3 h-3" />
                                                {{ book.copies }} copies
                                            </span>
                                        </div>
                                        <div v-if="book.dueDate" class="flex items-center gap-3 text-xs text-gray-500 dark:text-gray-400 mt-2">
                                            <span class="flex items-center gap-1">
                                                <Clock class="w-3 h-3" />
                                                Due: {{ book.dueDate }}
                                            </span>
                                            <span class="flex items-center gap-1">
                                                <Users class="w-3 h-3" />
                                                To: {{ book.issuedTo }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </CardContent>
                    </Card>

                    <!-- Recent Activities -->
                    <Card>
                        <CardHeader>
                            <CardTitle class="flex items-center gap-2">
                                <Activity class="w-5 h-5" />
                                Recent Activities
                            </CardTitle>
                            <CardDescription>
                                Latest library operations
                            </CardDescription>
                        </CardHeader>
                        <CardContent>
                            <div class="space-y-3">
                                <div
                                    v-for="activity in recentActivities"
                                    :key="activity.title"
                                    class="flex items-start gap-4 p-4 rounded-xl border border-gray-100 dark:border-gray-800 hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-all duration-200 group"
                                >
                                    <div :class="[activity.color, 'p-2.5 rounded-lg flex-shrink-0 group-hover:scale-110 transition-transform duration-200']">
                                        <component :is="activity.icon" class="w-5 h-5" />
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <h4 class="text-sm font-semibold text-gray-900 dark:text-white">{{ activity.title }}</h4>
                                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ activity.description }}</p>
                                        <div class="flex items-center gap-2 mt-2">
                                            <Clock class="w-3 h-3 text-gray-400" />
                                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ activity.time }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </CardContent>
                    </Card>
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Overdue Books -->
                    <Card>
                        <CardHeader>
                            <CardTitle class="flex items-center gap-2">
                                <AlertTriangle class="w-5 h-5" />
                                Overdue Books
                                <Badge variant="destructive" class="ml-1">{{ overdueBooks.length }}</Badge>
                            </CardTitle>
                        </CardHeader>
                        <CardContent>
                            <div class="space-y-3">
                                <div
                                    v-for="book in overdueBooks"
                                    :key="book.title"
                                    class="p-3 rounded-lg border border-red-200 dark:border-red-800 bg-red-50 dark:bg-red-900/10"
                                >
                                    <h4 class="text-sm font-semibold text-gray-900 dark:text-white mb-1">{{ book.title }}</h4>
                                    <p class="text-xs text-gray-600 dark:text-gray-400 mb-2">{{ book.student }}</p>
                                    <div class="flex items-center justify-between text-xs">
                                        <Badge variant="outline" class="border-red-300 text-red-700 dark:border-red-700 dark:text-red-400">
                                            {{ book.days }} days overdue
                                        </Badge>
                                        <span class="text-red-600 dark:text-red-400 font-medium">UGX {{ book.fine.toLocaleString() }}</span>
                                    </div>
                                </div>
                            </div>
                        </CardContent>
                    </Card>

                    <!-- Member Stats -->
                    <Card>
                        <CardHeader>
                            <CardTitle class="flex items-center gap-2">
                                <Users class="w-5 h-5" />
                                Members
                            </CardTitle>
                        </CardHeader>
                        <CardContent>
                            <div class="space-y-4">
                                <div class="flex items-center justify-between py-2">
                                    <span class="text-sm text-gray-600 dark:text-gray-400">Total Members</span>
                                    <span class="text-sm font-semibold text-gray-900 dark:text-white">{{ libraryStats.totalMembers }}</span>
                                </div>
                                <Separator />
                                <div class="flex items-center justify-between py-2">
                                    <span class="text-sm text-gray-600 dark:text-gray-400">New This Month</span>
                                    <span class="text-sm font-semibold text-emerald-600 dark:text-emerald-400">{{ libraryStats.newMembers }}</span>
                                </div>
                                <Separator />
                                <div class="flex items-center justify-between py-2">
                                    <span class="text-sm text-gray-600 dark:text-gray-400">Active Readers</span>
                                    <span class="text-sm font-semibold text-blue-600 dark:text-blue-400">{{ libraryStats.issuedBooks }}</span>
                                </div>
                            </div>
                            <Button class="w-full mt-4" size="sm" variant="outline">
                                <UserPlus class="w-4 h-4 mr-2" />
                                Add Member
                            </Button>
                        </CardContent>
                    </Card>

                    <!-- Quick Stats -->
                    <Card class="bg-indigo-500 border-0 text-white">
                        <CardContent class="p-6 text-center">
                            <Library class="w-12 h-12 mx-auto mb-3 opacity-90" />
                            <h3 class="font-semibold mb-2">Library Reports</h3>
                            <p class="text-sm text-indigo-100 mb-4">Generate comprehensive library statistics</p>
                            <Button variant="secondary" size="sm" class="w-full">
                                <Download class="w-4 h-4 mr-2" />
                                Download Report
                            </Button>
                        </CardContent>
                    </Card>
                </div>
            </div>
        </div>
    </Sidebar>
</template>