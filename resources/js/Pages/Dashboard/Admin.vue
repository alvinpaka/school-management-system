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
    Settings,
    ArrowUp,
    ArrowDown,
    Activity,
    Award,
    CalendarDays,
    Sparkles,
    ChevronRight,
    Plus,
    BarChart3,
    PieChart,
    Baby,
    HeartHandshake,
    Zap
} from 'lucide-vue-next';

defineProps({
    stats: Object,
    recentActivities: Array
});

const quickActions = [
    {
        title: 'Add Student',
        description: 'Enroll new student',
        icon: Users,
        color: 'from-blue-500 to-blue-600',
        href: route('students.create')
    },
    {
        title: 'Add Teacher',
        description: 'Hire new teacher',
        icon: UserCheck,
        color: 'from-emerald-500 to-emerald-600',
        href: route('teachers.create')
    },
    {
        title: 'View Students',
        description: 'Browse all students',
        icon: GraduationCap,
        color: 'from-purple-500 to-purple-600',
        href: route('students.index')
    },
    {
        title: 'View Teachers',
        description: 'Browse all teachers',
        icon: UserCheck,
        color: 'from-teal-500 to-teal-600',
        href: route('teachers.index')
    },
    {
        title: 'Fee Collection',
        description: 'Manage payments',
        icon: DollarSign,
        color: 'from-orange-500 to-orange-600',
        href: route('fees.index')
    },
    {
        title: 'Exams',
        description: 'View exam results',
        icon: FileText,
        color: 'from-pink-500 to-pink-600',
        href: route('exams.index')
    }
];

// Mock upcoming events (replace with actual data)
const upcomingEvents = [
    {
        title: 'Parent-Teacher Conference',
        date: 'Feb 20, 2026',
        time: '2:00 PM',
        type: 'Meeting',
        color: 'bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-400'
    },
    {
        title: 'Mid-Term Exams Begin',
        date: 'Feb 25, 2026',
        time: '8:00 AM',
        type: 'Exam',
        color: 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400'
    },
    {
        title: 'Sports Day Event',
        date: 'Mar 1, 2026',
        time: '9:00 AM',
        type: 'Event',
        color: 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400'
    }
];

// Mock notifications (replace with actual data)
const notifications = [
    {
        title: 'New admission request',
        description: '5 new students pending approval',
        icon: Users,
        time: '10 min ago',
        unread: true
    },
    {
        title: 'Fee payment received',
        description: 'UGX 500,000 from John Doe',
        icon: DollarSign,
        time: '1 hour ago',
        unread: true
    },
    {
        title: 'System backup completed',
        description: 'Daily backup successful',
        icon: CheckCircle,
        time: '2 hours ago',
        unread: false
    }
];

// Helper function to resolve icon components
const getIconComponent = (iconName) => {
    const icons = {
        Users,
        DollarSign,
        Calendar,
        FileText,
        GraduationCap,
        UserCheck,
        Bell,
        Clock,
        CheckCircle
    };
    return icons[iconName] || Users;
};

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
    <Head title="Admin Dashboard" />

    <Sidebar>
        <template #header-title>
            <div class="flex items-center space-x-3">
                <Home class="w-5 h-5" />
                <span class="font-semibold">Dashboard</span>
            </div>
        </template>

        <div class="mx-auto max-w-7xl space-y-6">
            <!-- Welcome Header -->
            <Card class="overflow-hidden border-0">
                <div class="h-20"></div>
                <CardContent class="relative pt-0 pb-6 -mt-12">
                    <div class="flex flex-col md:flex-row md:items-end md:justify-between">
                        <div class="flex items-center space-x-4">
                            <Avatar class="w-20 h-20 border-2 border-gray-200 dark:border-gray-700">
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
                                    <Sparkles class="w-6 h-6 text-yellow-500" />
                                </h1>
                                <p class="text-base text-gray-600 dark:text-gray-400">
                                    Here's what's happening in your school today
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

            <!-- Stats Overview -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Total Students -->
                <Card class="hover:shadow-lg transition-all duration-200 border-blue-500">
                    <CardContent class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div class="p-3 bg-blue-100 dark:bg-blue-900/30 rounded-xl">
                                <GraduationCap class="w-6 h-6 text-blue-600 dark:text-blue-400" />
                            </div>
                            <Badge variant="secondary" class="bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400">
                                <TrendingUp class="w-3 h-3 mr-1" />
                                +12%
                            </Badge>
                        </div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Total Students</p>
                        <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ stats.students_count }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                            <span class="text-blue-600 dark:text-blue-400 font-medium">+23</span> this month
                        </p>
                    </CardContent>
                </Card>

                <!-- Total Teachers -->
                <Card class="hover:shadow-lg transition-all duration-200 border-emerald-500">
                    <CardContent class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div class="p-3 bg-emerald-100 dark:bg-emerald-900/30 rounded-xl">
                                <UserCheck class="w-6 h-6 text-emerald-600 dark:text-emerald-400" />
                            </div>
                            <Badge variant="secondary" class="bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400">
                                <TrendingUp class="w-3 h-3 mr-1" />
                                +5%
                            </Badge>
                        </div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Total Teachers</p>
                        <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ stats.teachers_count }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                            <span class="text-emerald-600 dark:text-emerald-400 font-medium">+2</span> this month
                        </p>
                    </CardContent>
                </Card>

                <!-- Total Classes -->
                <Card class="hover:shadow-lg transition-all duration-200 border-purple-500">
                    <CardContent class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div class="p-3 bg-purple-100 dark:bg-purple-900/30 rounded-xl">
                                <BookOpen class="w-6 h-6 text-purple-600 dark:text-purple-400" />
                            </div>
                            <Badge variant="secondary" class="bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-400">
                                Stable
                            </Badge>
                        </div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Total Classes</p>
                        <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ stats.classes_count }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                            <span class="text-purple-600 dark:text-purple-400 font-medium">{{ stats.classes_count }}</span> active
                        </p>
                    </CardContent>
                </Card>

                <!-- Revenue -->
                <Card class="hover:shadow-lg transition-all duration-200 border-orange-500">
                    <CardContent class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div class="p-3 bg-orange-100 dark:bg-orange-900/30 rounded-xl">
                                <DollarSign class="w-6 h-6 text-orange-600 dark:text-orange-400" />
                            </div>
                            <Badge variant="secondary" class="bg-orange-100 text-orange-700 dark:bg-orange-900/30 dark:text-orange-400">
                                Today
                            </Badge>
                        </div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Revenue</p>
                        <p class="text-3xl font-bold text-gray-900 dark:text-white">UGX 0</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                            <span class="text-orange-600 dark:text-orange-400 font-medium">UGX 12.5M</span> this month
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
                                <Zap class="w-5 h-5 text-yellow-500" />
                                Quick Actions
                            </CardTitle>
                            <CardDescription>
                                Frequently used features for faster access
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
                <!-- Recent Activities -->
                <div class="lg:col-span-2 space-y-6">
                    <Card>
                        <CardHeader>
                            <div class="flex items-center justify-between">
                                <div>
                                    <CardTitle class="flex items-center gap-2">
                                        <Activity class="w-5 h-5" />
                                        Recent Activities
                                    </CardTitle>
                                    <CardDescription>
                                        Latest system activities and updates
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
                                    v-for="activity in recentActivities"
                                    :key="activity.title"
                                    class="flex items-start gap-4 p-4 rounded-xl border border-gray-100 dark:border-gray-800 hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-all duration-200 group"
                                >
                                    <div :class="[activity.color, 'p-2.5 rounded-lg flex-shrink-0 group-hover:scale-110 transition-transform duration-200']">
                                        <component :is="getIconComponent(activity.icon)" class="w-5 h-5" />
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

                    <!-- Upcoming Events -->
                    <Card>
                        <CardHeader>
                            <div class="flex items-center justify-between">
                                <div>
                                    <CardTitle class="flex items-center gap-2">
                                        <CalendarDays class="w-5 h-5" />
                                        Upcoming Events
                                    </CardTitle>
                                    <CardDescription>
                                        Important dates and schedules
                                    </CardDescription>
                                </div>
                                <Button variant="ghost" size="sm">
                                    <Plus class="w-4 h-4 mr-1" />
                                    Add Event
                                </Button>
                            </div>
                        </CardHeader>
                        <CardContent>
                            <div class="space-y-3">
                                <div
                                    v-for="event in upcomingEvents"
                                    :key="event.title"
                                    class="flex items-center gap-4 p-4 rounded-xl border border-gray-100 dark:border-gray-800 hover:border-indigo-200 dark:hover:border-indigo-800 transition-all duration-200"
                                >
                                    <div class="flex-shrink-0 text-center">
                                        <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ event.date.split(' ')[1].replace(',', '') }}</div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">{{ event.date.split(' ')[0] }}</div>
                                    </div>
                                    <Separator orientation="vertical" class="h-12" />
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center gap-2 mb-1">
                                            <h4 class="text-sm font-semibold text-gray-900 dark:text-white">{{ event.title }}</h4>
                                            <Badge :class="event.color" variant="secondary">{{ event.type }}</Badge>
                                        </div>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 flex items-center gap-1">
                                            <Clock class="w-3 h-3" />
                                            {{ event.time }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </CardContent>
                    </Card>
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Notifications -->
                    <Card>
                        <CardHeader>
                            <div class="flex items-center justify-between">
                                <CardTitle class="flex items-center gap-2">
                                    <Bell class="w-5 h-5" />
                                    Notifications
                                    <Badge variant="destructive" class="ml-1">2</Badge>
                                </CardTitle>
                            </div>
                        </CardHeader>
                        <CardContent>
                            <div class="space-y-3">
                                <div
                                    v-for="notification in notifications"
                                    :key="notification.title"
                                    :class="[
                                        'p-3 rounded-lg transition-all duration-200 cursor-pointer',
                                        notification.unread 
                                            ? 'bg-indigo-50 dark:bg-indigo-900/20 border border-indigo-200 dark:border-indigo-800' 
                                            : 'hover:bg-gray-50 dark:hover:bg-gray-800/50'
                                    ]"
                                >
                                    <div class="flex items-start gap-3">
                                        <div class="p-2 bg-white dark:bg-gray-800 rounded-lg">
                                            <component :is="notification.icon" class="w-4 h-4 text-gray-700 dark:text-gray-300" />
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-center gap-2 mb-1">
                                                <h4 class="text-sm font-semibold text-gray-900 dark:text-white">{{ notification.title }}</h4>
                                                <div v-if="notification.unread" class="w-2 h-2 bg-indigo-500 rounded-full"></div>
                                            </div>
                                            <p class="text-xs text-gray-600 dark:text-gray-400">{{ notification.description }}</p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ notification.time }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <Button variant="ghost" class="w-full mt-3" size="sm">
                                View All Notifications
                            </Button>
                        </CardContent>
                    </Card>

                    <!-- System Status -->
                    <Card>
                        <CardHeader>
                            <CardTitle class="flex items-center gap-2">
                                <Activity class="w-5 h-5" />
                                System Status
                            </CardTitle>
                            <CardDescription>
                                Current system health
                            </CardDescription>
                        </CardHeader>
                        <CardContent>
                            <div class="space-y-4">
                                <div class="flex items-center justify-between p-3 rounded-lg bg-emerald-50 dark:bg-emerald-900/20">
                                    <div class="flex items-center gap-3">
                                        <div class="w-2 h-2 bg-emerald-500 rounded-full animate-pulse"></div>
                                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Database</span>
                                    </div>
                                    <Badge variant="secondary" class="bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400">
                                        <CheckCircle class="w-3 h-3 mr-1" />
                                        Online
                                    </Badge>
                                </div>
                                
                                <div class="flex items-center justify-between p-3 rounded-lg bg-emerald-50 dark:bg-emerald-900/20">
                                    <div class="flex items-center gap-3">
                                        <div class="w-2 h-2 bg-emerald-500 rounded-full animate-pulse"></div>
                                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">API Server</span>
                                    </div>
                                    <Badge variant="secondary" class="bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400">
                                        <CheckCircle class="w-3 h-3 mr-1" />
                                        Online
                                    </Badge>
                                </div>
                                
                                <div class="flex items-center justify-between p-3 rounded-lg bg-amber-50 dark:bg-amber-900/20">
                                    <div class="flex items-center gap-3">
                                        <div class="w-2 h-2 bg-amber-500 rounded-full animate-pulse"></div>
                                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Storage</span>
                                    </div>
                                    <Badge variant="secondary" class="bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400">
                                        <AlertCircle class="w-3 h-3 mr-1" />
                                        75% Full
                                    </Badge>
                                </div>
                            </div>
                        </CardContent>
                    </Card>

                    <!-- Quick Stats -->
                    <Card>
                        <CardHeader>
                            <CardTitle class="flex items-center gap-2">
                                <BarChart3 class="w-5 h-5" />
                                Today's Summary
                            </CardTitle>
                        </CardHeader>
                        <CardContent>
                            <div class="space-y-3">
                                <div class="flex items-center justify-between py-2">
                                    <span class="text-sm text-gray-600 dark:text-gray-400">Present Students</span>
                                    <span class="text-sm font-semibold text-gray-900 dark:text-white">{{ stats.students_count - 15 }}/{{ stats.students_count }}</span>
                                </div>
                                <Separator />
                                <div class="flex items-center justify-between py-2">
                                    <span class="text-sm text-gray-600 dark:text-gray-400">Active Teachers</span>
                                    <span class="text-sm font-semibold text-gray-900 dark:text-white">{{ stats.teachers_count }}/{{ stats.teachers_count }}</span>
                                </div>
                                <Separator />
                                <div class="flex items-center justify-between py-2">
                                    <span class="text-sm text-gray-600 dark:text-gray-400">Ongoing Classes</span>
                                    <span class="text-sm font-semibold text-gray-900 dark:text-white">8/{{ stats.classes_count }}</span>
                                </div>
                            </div>
                        </CardContent>
                    </Card>
                </div>
            </div>
        </div>
    </Sidebar>
</template>