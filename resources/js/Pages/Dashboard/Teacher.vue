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
    ClipboardList,
    Award,
    BarChart3,
    Sparkles,
    ChevronRight,
    Plus,
    Video,
    BookMarked,
    Target,
    Zap,
    CalendarDays,
    Activity,
    MessageSquare
} from 'lucide-vue-next';

defineProps({
    teacher: Object,
    stats: Object
});

const quickActions = [
    {
        title: 'Take Attendance',
        description: 'Mark attendance',
        icon: ClipboardList,
        color: 'from-emerald-500 to-emerald-600',
        href: route('attendance.index')
    },
    {
        title: 'My Classes',
        description: 'View classes',
        icon: BookOpen,
        color: 'from-blue-500 to-blue-600',
        href: route('classes.index')
    },
    {
        title: 'Grade Exams',
        description: 'Enter grades',
        icon: FileText,
        color: 'from-purple-500 to-purple-600',
        href: route('exams.index')
    },
    {
        title: 'Assignments',
        description: 'Give homework',
        icon: Award,
        color: 'from-orange-500 to-orange-600',
        href: '#'
    },
    {
        title: 'Schedule',
        description: 'View timetable',
        icon: Calendar,
        color: 'from-pink-500 to-pink-600',
        href: '#'
    },
    {
        title: 'Students',
        description: 'View students',
        icon: Users,
        color: 'from-teal-500 to-teal-600',
        href: route('students.index')
    }
];

const recentActivities = [
    {
        title: 'Attendance Marked',
        description: 'Class 10A - Mathematics',
        time: '2 hours ago',
        icon: ClipboardList,
        color: 'bg-emerald-100 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400'
    },
    {
        title: 'Exam Graded',
        description: 'Midterm exams - 45 students',
        time: '5 hours ago',
        icon: FileText,
        color: 'bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400'
    },
    {
        title: 'Assignment Posted',
        description: 'Science project - Class 9B',
        time: '1 day ago',
        icon: Award,
        color: 'bg-purple-100 dark:bg-purple-900/30 text-purple-600 dark:text-purple-400'
    },
    {
        title: 'Class Completed',
        description: 'Physics - Class 10B',
        time: '2 days ago',
        icon: BookOpen,
        color: 'bg-orange-100 dark:bg-orange-900/30 text-orange-600 dark:text-orange-400'
    }
];

const upcomingClasses = [
    {
        subject: 'Mathematics',
        class: 'Class 10A',
        time: '8:00 AM - 9:00 AM',
        room: 'Room 201',
        students: 45,
        status: 'upcoming'
    },
    {
        subject: 'Physics',
        class: 'Class 10B',
        time: '9:30 AM - 10:30 AM',
        room: 'Room 205',
        students: 42,
        status: 'upcoming'
    },
    {
        subject: 'Chemistry',
        class: 'Class 9A',
        time: '11:00 AM - 12:00 PM',
        room: 'Lab 301',
        students: 38,
        status: 'upcoming'
    }
];

const pendingTasks = [
    { title: 'Grade Math Assignments', count: 15, priority: 'high' },
    { title: 'Review Lab Reports', count: 8, priority: 'medium' },
    { title: 'Prepare Lesson Plans', count: 3, priority: 'low' }
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
    <Head title="Teacher Dashboard" />

    <Sidebar>
        <template #header-title>
            <div class="flex items-center space-x-3">
                <GraduationCap class="w-5 h-5" />
                <span class="font-semibold">Teacher Dashboard</span>
            </div>
        </template>

        <div class="mx-auto max-w-7xl space-y-6">
            <!-- Welcome Header -->
            <Card class="overflow-hidden border-0">
                <div class="h-24"></div>
                <CardContent class="relative pt-0 pb-6 -mt-12">
                    <div class="flex flex-col md:flex-row md:items-end md:justify-between">
                        <div class="flex items-center space-x-4">
                            <Avatar class="w-20 h-20 border-2 border-gray-200 dark:border-gray-700">
                                <AvatarImage 
                                    v-if="$page.props.auth.user.photo"
                                    :src="`/storage/${$page.props.auth.user.photo}`" 
                                    :alt="$page.props.auth.user.name"
                                />
                                <AvatarFallback class="text-2xl font-bold bg-emerald-500 text-white">
                                    {{ getInitials($page.props.auth.user.name) }}
                                </AvatarFallback>
                            </Avatar>
                            <div class="mt-4">
                                <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white flex items-center gap-2">
                                    Welcome back, {{ $page.props.auth.user.name }}
                                    <BookMarked class="w-6 h-6 text-emerald-500" />
                                </h1>
                                <p class="text-base text-gray-600 dark:text-gray-400">
                                    Ready to inspire young minds today
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
                <!-- My Classes -->
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
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">My Classes</p>
                        <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ stats?.classes || 8 }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                            <span class="text-emerald-600 dark:text-emerald-400 font-medium">{{ upcomingClasses.length }}</span> classes today
                        </p>
                    </CardContent>
                </Card>

                <!-- Total Students -->
                <Card class="hover:shadow-lg transition-all duration-200 border-blue-500">
                    <CardContent class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div class="p-3 bg-blue-100 dark:bg-blue-900/30 rounded-xl">
                                <Users class="w-6 h-6 text-blue-600 dark:text-blue-400" />
                            </div>
                            <Badge variant="secondary" class="bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400">
                                Total
                            </Badge>
                        </div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">My Students</p>
                        <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ stats?.students || 156 }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                            <span class="text-blue-600 dark:text-blue-400 font-medium">Across all classes</span>
                        </p>
                    </CardContent>
                </Card>

                <!-- Today's Classes -->
                <Card class="hover:shadow-lg transition-all duration-200 border-purple-500">
                    <CardContent class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div class="p-3 bg-purple-100 dark:bg-purple-900/30 rounded-xl">
                                <Calendar class="w-6 h-6 text-purple-600 dark:text-purple-400" />
                            </div>
                            <Badge variant="secondary" class="bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-400">
                                Today
                            </Badge>
                        </div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Today's Classes</p>
                        <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ upcomingClasses.length }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                            <span class="text-purple-600 dark:text-purple-400 font-medium">Next at 8:00 AM</span>
                        </p>
                    </CardContent>
                </Card>

                <!-- Pending Tasks -->
                <Card class="hover:shadow-lg transition-all duration-200 border-orange-500">
                    <CardContent class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div class="p-3 bg-orange-100 dark:bg-orange-900/30 rounded-xl">
                                <Target class="w-6 h-6 text-orange-600 dark:text-orange-400" />
                            </div>
                            <Badge variant="secondary" class="bg-orange-100 text-orange-700 dark:bg-orange-900/30 dark:text-orange-400">
                                Pending
                            </Badge>
                        </div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Pending Tasks</p>
                        <p class="text-3xl font-bold text-gray-900 dark:text-white">26</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                            <span class="text-orange-600 dark:text-orange-400 font-medium">15</span> high priority
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
                                <Zap class="w-5 h-5 text-emerald-500" />
                                Quick Actions
                            </CardTitle>
                            <CardDescription>
                                Essential teaching tools for quick access
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
                <!-- Today's Schedule & Activities -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Today's Schedule -->
                    <Card>
                        <CardHeader>
                            <div class="flex items-center justify-between">
                                <div>
                                    <CardTitle class="flex items-center gap-2">
                                        <CalendarDays class="w-5 h-5" />
                                        Today's Schedule
                                    </CardTitle>
                                    <CardDescription>
                                        Your upcoming classes today
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
                                    v-for="classItem in upcomingClasses"
                                    :key="classItem.subject"
                                    class="flex items-center gap-4 p-4 rounded-xl border border-gray-100 dark:border-gray-800 hover:border-emerald-200 dark:hover:border-emerald-800 transition-all duration-200"
                                >
                                    <div class="flex-shrink-0 text-center">
                                        <div class="text-xl font-bold text-gray-900 dark:text-white">{{ classItem.time.split(' - ')[0].split(':')[0] }}</div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">{{ classItem.time.split(' - ')[0].split(' ')[1] }}</div>
                                    </div>
                                    <Separator orientation="vertical" class="h-12" />
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center gap-2 mb-1">
                                            <h4 class="text-sm font-semibold text-gray-900 dark:text-white">{{ classItem.subject }}</h4>
                                            <Badge variant="secondary" class="text-xs">{{ classItem.class }}</Badge>
                                        </div>
                                        <div class="flex items-center gap-3 text-xs text-gray-500 dark:text-gray-400">
                                            <span class="flex items-center gap-1">
                                                <Clock class="w-3 h-3" />
                                                {{ classItem.time }}
                                            </span>
                                            <span class="flex items-center gap-1">
                                                <Home class="w-3 h-3" />
                                                {{ classItem.room }}
                                            </span>
                                            <span class="flex items-center gap-1">
                                                <Users class="w-3 h-3" />
                                                {{ classItem.students }} students
                                            </span>
                                        </div>
                                    </div>
                                    <Button size="sm" variant="outline">
                                        Start Class
                                    </Button>
                                </div>
                            </div>
                        </CardContent>
                    </Card>

                    <!-- Recent Activities -->
                    <Card>
                        <CardHeader>
                            <div class="flex items-center justify-between">
                                <div>
                                    <CardTitle class="flex items-center gap-2">
                                        <Activity class="w-5 h-5" />
                                        Recent Activities
                                    </CardTitle>
                                    <CardDescription>
                                        Your latest teaching activities
                                    </CardDescription>
                                </div>
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
                    <!-- Pending Tasks -->
                    <Card>
                        <CardHeader>
                            <div class="flex items-center justify-between">
                                <CardTitle class="flex items-center gap-2">
                                    <Target class="w-5 h-5" />
                                    Pending Tasks
                                    <Badge variant="destructive" class="ml-1">{{ pendingTasks.length }}</Badge>
                                </CardTitle>
                            </div>
                        </CardHeader>
                        <CardContent>
                            <div class="space-y-3">
                                <div
                                    v-for="task in pendingTasks"
                                    :key="task.title"
                                    :class="[
                                        'p-3 rounded-lg border transition-all duration-200 cursor-pointer',
                                        task.priority === 'high' ? 'border-red-200 dark:border-red-800 bg-red-50 dark:bg-red-900/10' :
                                        task.priority === 'medium' ? 'border-orange-200 dark:border-orange-800 bg-orange-50 dark:bg-orange-900/10' :
                                        'border-blue-200 dark:border-blue-800 bg-blue-50 dark:bg-blue-900/10'
                                    ]"
                                >
                                    <div class="flex items-start justify-between mb-2">
                                        <h4 class="text-sm font-semibold text-gray-900 dark:text-white">{{ task.title }}</h4>
                                        <Badge 
                                            variant="outline" 
                                            :class="[
                                                task.priority === 'high' ? 'border-red-300 text-red-700 dark:border-red-700 dark:text-red-400' :
                                                task.priority === 'medium' ? 'border-orange-300 text-orange-700 dark:border-orange-700 dark:text-orange-400' :
                                                'border-blue-300 text-blue-700 dark:border-blue-700 dark:text-blue-400'
                                            ]"
                                        >
                                            {{ task.count }} items
                                        </Badge>
                                    </div>
                                    <Badge 
                                        variant="secondary" 
                                        class="text-xs"
                                        :class="[
                                            task.priority === 'high' ? 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400' :
                                            task.priority === 'medium' ? 'bg-orange-100 text-orange-700 dark:bg-orange-900/30 dark:text-orange-400' :
                                            'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400'
                                        ]"
                                    >
                                        {{ task.priority }} priority
                                    </Badge>
                                </div>
                            </div>
                            <Button variant="outline" class="w-full mt-3" size="sm">
                                <Plus class="w-4 h-4 mr-2" />
                                Add Task
                            </Button>
                        </CardContent>
                    </Card>

                    <!-- Performance Summary -->
                    <Card>
                        <CardHeader>
                            <CardTitle class="flex items-center gap-2">
                                <BarChart3 class="w-5 h-5" />
                                Performance
                            </CardTitle>
                        </CardHeader>
                        <CardContent>
                            <div class="space-y-4">
                                <div class="flex items-center justify-between py-2">
                                    <span class="text-sm text-gray-600 dark:text-gray-400">Attendance Rate</span>
                                    <span class="text-sm font-semibold text-emerald-600 dark:text-emerald-400">95.5%</span>
                                </div>
                                <Separator />
                                <div class="flex items-center justify-between py-2">
                                    <span class="text-sm text-gray-600 dark:text-gray-400">Avg. Grade</span>
                                    <span class="text-sm font-semibold text-blue-600 dark:text-blue-400">B+</span>
                                </div>
                                <Separator />
                                <div class="flex items-center justify-between py-2">
                                    <span class="text-sm text-gray-600 dark:text-gray-400">Completed Tasks</span>
                                    <span class="text-sm font-semibold text-purple-600 dark:text-purple-400">142/168</span>
                                </div>
                                <Separator />
                                <div class="flex items-center justify-between py-2">
                                    <span class="text-sm text-gray-600 dark:text-gray-400">Student Rating</span>
                                    <span class="text-sm font-semibold text-orange-600 dark:text-orange-400">4.8/5.0</span>
                                </div>
                            </div>
                        </CardContent>
                    </Card>

                    <!-- Quick Message -->
                    <Card class="bg-emerald-500 border-0 text-white">
                        <CardContent class="p-6 text-center">
                            <MessageSquare class="w-12 h-12 mx-auto mb-3 opacity-90" />
                            <h3 class="font-semibold mb-2">Message Students</h3>
                            <p class="text-sm text-emerald-100 mb-4">Send announcements or updates to your classes</p>
                            <Button variant="secondary" size="sm" class="w-full">
                                <MessageSquare class="w-4 h-4 mr-2" />
                                Send Message
                            </Button>
                        </CardContent>
                    </Card>
                </div>
            </div>
        </div>
    </Sidebar>
</template>