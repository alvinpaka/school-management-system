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
    ClipboardList,
    Sparkles,
    ChevronRight,
    Plus,
    Zap,
    Activity,
    BookMarked,
    CalendarDays,
    Target,
    Trophy,
    Star,
    TrendingUpIcon
} from 'lucide-vue-next';

defineProps({
    student: Object,
    stats: Object
});

const quickActions = [
    {
        title: 'My Profile',
        description: 'View details',
        icon: UserCheck,
        color: 'from-blue-500 to-blue-600',
        href: route('profile.edit')
    },
    {
        title: 'My Classes',
        description: 'View schedule',
        icon: BookOpen,
        color: 'from-emerald-500 to-emerald-600',
        href: route('classes.index')
    },
    {
        title: 'Results',
        description: 'Check grades',
        icon: FileText,
        color: 'from-purple-500 to-purple-600',
        href: route('exams.index')
    },
    {
        title: 'Fees',
        description: 'Payment status',
        icon: DollarSign,
        color: 'from-orange-500 to-orange-600',
        href: route('fees.index')
    },
    {
        title: 'Library',
        description: 'Books & resources',
        icon: Library,
        color: 'from-pink-500 to-pink-600',
        href: '#'
    },
    {
        title: 'Assignments',
        description: 'Homework tasks',
        icon: Award,
        color: 'from-teal-500 to-teal-600',
        href: '#'
    }
];

const recentActivities = [
    {
        title: 'Assignment Submitted',
        description: 'Mathematics homework submitted on time',
        time: '2 hours ago',
        icon: Award,
        color: 'bg-emerald-100 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400'
    },
    {
        title: 'Exam Result Published',
        description: 'Science midterm results now available',
        time: '1 day ago',
        icon: FileText,
        color: 'bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400'
    },
    {
        title: 'Fee Payment Received',
        description: 'Tuition fee payment confirmed',
        time: '2 days ago',
        icon: DollarSign,
        color: 'bg-orange-100 dark:bg-orange-900/30 text-orange-600 dark:text-orange-400'
    },
    {
        title: 'Library Book Issued',
        description: 'Physics textbook borrowed',
        time: '3 days ago',
        icon: Library,
        color: 'bg-purple-100 dark:bg-purple-900/30 text-purple-600 dark:text-purple-400'
    }
];

const upcomingEvents = [
    { title: 'Mathematics Midterm Exam', date: 'Feb 15, 2026', time: '9:00 AM', type: 'exam' },
    { title: 'Science Project Due', date: 'Feb 16, 2026', time: '11:59 PM', type: 'assignment' },
    { title: 'Parent-Teacher Meeting', date: 'Feb 18, 2026', time: '2:00 PM', type: 'meeting' }
];

const recentGrades = [
    { subject: 'Mathematics', score: 85, grade: 'A', maxScore: 100 },
    { subject: 'Science', score: 78, grade: 'B+', maxScore: 100 },
    { subject: 'English', score: 92, grade: 'A+', maxScore: 100 }
];

const getInitials = (name) => {
    return name
        .split(' ')
        .map(word => word[0])
        .join('')
        .toUpperCase()
        .slice(0, 2);
};

const getGradeColor = (grade) => {
    if (grade.startsWith('A')) return 'text-emerald-600 dark:text-emerald-400';
    if (grade.startsWith('B')) return 'text-blue-600 dark:text-blue-400';
    if (grade.startsWith('C')) return 'text-orange-600 dark:text-orange-400';
    return 'text-red-600 dark:text-red-400';
};
</script>

<template>
    <Head title="Student Dashboard" />

    <Sidebar>
        <template #header-title>
            <div class="flex items-center space-x-3">
                <GraduationCap class="w-5 h-5" />
                <span class="font-semibold">Student Portal</span>
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
                                <AvatarFallback class="text-2xl font-bold bg-blue-500 text-white">
                                    {{ getInitials($page.props.auth.user.name) }}
                                </AvatarFallback>
                            </Avatar>
                            <div class="mt-4">
                                <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white flex items-center gap-2">
                                    Welcome back, {{ $page.props.auth.user.name }}
                                    <Star class="w-6 h-6 text-yellow-500" />
                                </h1>
                                <p class="text-base text-gray-600 dark:text-gray-400">
                                    Keep learning and achieving your goals
                                </p>
                                <div class="flex items-center gap-2 mt-2">
                                    <Badge variant="outline" class="text-xs">
                                        {{ student?.academic_class?.name || 'No Class' }}
                                    </Badge>
                                    <Badge variant="outline" class="text-xs">
                                        Roll No: {{ student?.roll_number || 'N/A' }}
                                    </Badge>
                                </div>
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
                <!-- Overall Grade -->
                <Card class="hover:shadow-lg transition-all duration-200  border-blue-500">
                    <CardContent class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div class="p-3 bg-blue-100 dark:bg-blue-900/30 rounded-xl">
                                <Trophy class="w-6 h-6 text-blue-600 dark:text-blue-400" />
                            </div>
                            <Badge variant="secondary" class="bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400">
                                GPA
                            </Badge>
                        </div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Overall Grade</p>
                        <p class="text-3xl font-bold text-gray-900 dark:text-white">A-</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                            <span class="text-blue-600 dark:text-blue-400 font-medium">3.7 GPA</span>
                        </p>
                    </CardContent>
                </Card>

                <!-- Attendance -->
                <Card class="hover:shadow-lg transition-all duration-200  border-emerald-500">
                    <CardContent class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div class="p-3 bg-emerald-100 dark:bg-emerald-900/30 rounded-xl">
                                <CheckCircle class="w-6 h-6 text-emerald-600 dark:text-emerald-400" />
                            </div>
                            <Badge variant="secondary" class="bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400">
                                <TrendingUp class="w-3 h-3 mr-1" />
                                Excellent
                            </Badge>
                        </div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Attendance</p>
                        <p class="text-3xl font-bold text-gray-900 dark:text-white">95.5%</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                            <span class="text-emerald-600 dark:text-emerald-400 font-medium">172/180 days</span>
                        </p>
                    </CardContent>
                </Card>

                <!-- Pending Assignments -->
                <Card class="hover:shadow-lg transition-all duration-200  border-orange-500">
                    <CardContent class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div class="p-3 bg-orange-100 dark:bg-orange-900/30 rounded-xl">
                                <Award class="w-6 h-6 text-orange-600 dark:text-orange-400" />
                            </div>
                            <Badge variant="secondary" class="bg-orange-100 text-orange-700 dark:bg-orange-900/30 dark:text-orange-400">
                                Pending
                            </Badge>
                        </div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Assignments</p>
                        <p class="text-3xl font-bold text-gray-900 dark:text-white">3</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                            <span class="text-orange-600 dark:text-orange-400 font-medium">Due this week</span>
                        </p>
                    </CardContent>
                </Card>

                <!-- Fee Status -->
                <Card class="hover:shadow-lg transition-all duration-200  border-purple-500">
                    <CardContent class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div class="p-3 bg-purple-100 dark:bg-purple-900/30 rounded-xl">
                                <DollarSign class="w-6 h-6 text-purple-600 dark:text-purple-400" />
                            </div>
                            <Badge variant="secondary" class="bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-400">
                                Paid
                            </Badge>
                        </div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Fee Status</p>
                        <p class="text-3xl font-bold text-gray-900 dark:text-white">UGX 0</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                            <span class="text-emerald-600 dark:text-emerald-400 font-medium">Fully paid</span>
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
                                <Zap class="w-5 h-5 text-blue-500" />
                                Quick Actions
                            </CardTitle>
                            <CardDescription>
                                Essential student portal features
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
                <!-- Recent Grades & Activities -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Recent Grades -->
                    <Card>
                        <CardHeader>
                            <div class="flex items-center justify-between">
                                <div>
                                    <CardTitle class="flex items-center gap-2">
                                        <BarChart3 class="w-5 h-5" />
                                        Recent Grades
                                    </CardTitle>
                                    <CardDescription>
                                        Your latest exam results
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
                                    v-for="grade in recentGrades"
                                    :key="grade.subject"
                                    class="flex items-center gap-4 p-4 rounded-xl border border-gray-100 dark:border-gray-800 hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-all duration-200"
                                >
                                    <div class="p-3 bg-blue-100 to-cyan-100 dark:from-blue-900/30 dark:to-cyan-900/30 rounded-lg">
                                        <BookOpen class="w-5 h-5 text-blue-600 dark:text-blue-400" />
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <h4 class="text-sm font-semibold text-gray-900 dark:text-white mb-1">{{ grade.subject }}</h4>
                                        <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                                            <div class="bg-blue-500 to-cyan-500 h-2 rounded-full" :style="`width: ${grade.score}%`"></div>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <div :class="['text-2xl font-bold', getGradeColor(grade.grade)]">{{ grade.grade }}</div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">{{ grade.score }}/{{ grade.maxScore }}</div>
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
                                Your recent student portal activities
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
                    <!-- Upcoming Events -->
                    <Card>
                        <CardHeader>
                            <CardTitle class="flex items-center gap-2">
                                <CalendarDays class="w-5 h-5" />
                                Upcoming Events
                                <Badge variant="secondary" class="ml-1">{{ upcomingEvents.length }}</Badge>
                            </CardTitle>
                        </CardHeader>
                        <CardContent>
                            <div class="space-y-3">
                                <div
                                    v-for="event in upcomingEvents"
                                    :key="event.title"
                                    class="p-3 rounded-lg border border-gray-200 dark:border-gray-800 bg-gray-50 dark:bg-gray-900/50"
                                >
                                    <div class="flex items-start justify-between mb-2">
                                        <h4 class="text-sm font-semibold text-gray-900 dark:text-white">{{ event.title }}</h4>
                                        <Badge variant="outline" class="text-xs">{{ event.type }}</Badge>
                                    </div>
                                    <div class="flex items-center gap-2 text-xs text-gray-500 dark:text-gray-400">
                                        <Calendar class="w-3 h-3" />
                                        {{ event.date }} at {{ event.time }}
                                    </div>
                                </div>
                            </div>
                        </CardContent>
                    </Card>

                    <!-- Class Schedule -->
                    <Card>
                        <CardHeader>
                            <CardTitle class="flex items-center gap-2">
                                <BookOpen class="w-5 h-5" />
                                Class Info
                            </CardTitle>
                        </CardHeader>
                        <CardContent>
                            <div class="space-y-4">
                                <div class="flex items-center justify-between py-2">
                                    <span class="text-sm text-gray-600 dark:text-gray-400">Class</span>
                                    <span class="text-sm font-semibold text-gray-900 dark:text-white">{{ student?.academic_class?.name || 'N/A' }}</span>
                                </div>
                                <Separator />
                                <div class="flex items-center justify-between py-2">
                                    <span class="text-sm text-gray-600 dark:text-gray-400">Section</span>
                                    <span class="text-sm font-semibold text-gray-900 dark:text-white">{{ student?.section?.name || 'N/A' }}</span>
                                </div>
                                <Separator />
                                <div class="flex items-center justify-between py-2">
                                    <span class="text-sm text-gray-600 dark:text-gray-400">Roll Number</span>
                                    <span class="text-sm font-semibold text-gray-900 dark:text-white">{{ student?.roll_number || 'N/A' }}</span>
                                </div>
                                <Separator />
                                <div class="flex items-center justify-between py-2">
                                    <span class="text-sm text-gray-600 dark:text-gray-400">Admission No</span>
                                    <span class="text-sm font-semibold text-gray-900 dark:text-white">{{ student?.admission_number || 'N/A' }}</span>
                                </div>
                            </div>
                            <Button class="w-full mt-4" size="sm" variant="outline">
                                <FileText class="w-4 h-4 mr-2" />
                                View Timetable
                            </Button>
                        </CardContent>
                    </Card>

                    <!-- Motivational Card -->
                    <Card class="bg-blue-500 to-cyan-600 border-0 text-white">
                        <CardContent class="p-6 text-center">
                            <Trophy class="w-12 h-12 mx-auto mb-3 opacity-90" />
                            <h3 class="font-semibold mb-2">Keep Going!</h3>
                            <p class="text-sm text-blue-100 mb-4">You're doing great! Stay focused on your goals.</p>
                            <Badge variant="secondary" class="bg-white/20 text-white border-0">
                                95.5% Attendance
                            </Badge>
                        </CardContent>
                    </Card>
                </div>
            </div>
        </div>
    </Sidebar>
</template>