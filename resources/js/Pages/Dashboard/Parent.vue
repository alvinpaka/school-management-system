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
    Trophy,
    BarChart3,
    Eye,
    MessageCircle,
    Phone,
    Sparkles,
    ChevronRight,
    Plus,
    Zap,
    Activity,
    Heart,
    Baby,
    CreditCard,
    CalendarDays,
    Award
} from 'lucide-vue-next';

defineProps({
    parent: Object,
    children: Array
});

const quickActions = [
    {
        title: 'My Children',
        description: 'View profiles',
        icon: Baby,
        color: 'from-purple-500 to-purple-600',
        href: route('students.index')
    },
    {
        title: 'Pay Fees',
        description: 'Make payment',
        icon: DollarSign,
        color: 'from-emerald-500 to-emerald-600',
        href: route('fees.index')
    },
    {
        title: 'View Results',
        description: 'Check grades',
        icon: FileText,
        color: 'from-blue-500 to-blue-600',
        href: route('exams.index')
    },
    {
        title: 'Attendance',
        description: 'View records',
        icon: CheckCircle,
        color: 'from-orange-500 to-orange-600',
        href: route('attendance.index')
    },
    {
        title: 'Calendar',
        description: 'School events',
        icon: Calendar,
        color: 'from-pink-500 to-pink-600',
        href: '#'
    },
    {
        title: 'Contact',
        description: 'Message school',
        icon: MessageCircle,
        color: 'from-teal-500 to-teal-600',
        href: '#'
    }
];

const recentActivities = [
    {
        title: 'Fee Payment Made',
        description: 'UGX 200,000 paid successfully',
        time: '2 hours ago',
        icon: DollarSign,
        color: 'bg-emerald-100 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400'
    },
    {
        title: 'Parent-Teacher Meeting',
        description: 'Scheduled for tomorrow at 2:00 PM',
        time: '1 day ago',
        icon: Calendar,
        color: 'bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400'
    },
    {
        title: 'Exam Result Published',
        description: 'Midterm results now available',
        time: '2 days ago',
        icon: FileText,
        color: 'bg-purple-100 dark:bg-purple-900/30 text-purple-600 dark:text-purple-400'
    },
    {
        title: 'Report Card Generated',
        description: 'Term 1 report card ready',
        time: '3 days ago',
        icon: Award,
        color: 'bg-orange-100 dark:bg-orange-900/30 text-orange-600 dark:text-orange-400'
    }
];

const upcomingEvents = [
    { title: 'Parent-Teacher Conference', date: 'Feb 20, 2026', time: '2:00 PM', type: 'meeting' },
    { title: 'Sports Day', date: 'Mar 1, 2026', time: '9:00 AM', type: 'event' },
    { title: 'Fee Payment Deadline', date: 'Mar 5, 2026', time: 'All Day', type: 'deadline' }
];

const getInitials = (name) => {
    if (!name) return 'ST';
    return name
        .split(' ')
        .map(word => word[0])
        .join('')
        .toUpperCase()
        .slice(0, 2);
};
</script>

<template>
    <Head title="Parent Dashboard" />

    <Sidebar>
        <template #header-title>
            <div class="flex items-center space-x-3">
                <Heart class="w-5 h-5" />
                <span class="font-semibold">Parent Portal</span>
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
                                <AvatarFallback class="text-2xl font-bold bg-purple-500 text-white">
                                    {{ getInitials($page.props.auth.user.name) }}
                                </AvatarFallback>
                            </Avatar>
                            <div class="mt-4">
                                <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white flex items-center gap-2">
                                    Welcome back, {{ $page.props.auth.user.name }}
                                    <Heart class="w-6 h-6 text-pink-500" />
                                </h1>
                                <p class="text-base text-gray-600 dark:text-gray-400">
                                    Stay connected with your children's education
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
                <!-- Total Children -->
                <Card class="hover:shadow-lg transition-all duration-200 border-purple-500">
                    <CardContent class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div class="p-3 bg-purple-100 dark:bg-purple-900/30 rounded-xl">
                                <Baby class="w-6 h-6 text-purple-600 dark:text-purple-400" />
                            </div>
                            <Badge variant="secondary" class="bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-400">
                                Enrolled
                            </Badge>
                        </div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">My Children</p>
                        <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ children?.length || 0 }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                            <span class="text-purple-600 dark:text-purple-400 font-medium">Active students</span>
                        </p>
                    </CardContent>
                </Card>

                <!-- Fees Paid -->
                <Card class="hover:shadow-lg transition-all duration-200 border-emerald-500">
                    <CardContent class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div class="p-3 bg-emerald-100 dark:bg-emerald-900/30 rounded-xl">
                                <CheckCircle class="w-6 h-6 text-emerald-600 dark:text-emerald-400" />
                            </div>
                            <Badge variant="secondary" class="bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400">
                                Paid
                            </Badge>
                        </div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Fees Paid</p>
                        <p class="text-3xl font-bold text-gray-900 dark:text-white">UGX 550K</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                            <span class="text-emerald-600 dark:text-emerald-400 font-medium">This term</span>
                        </p>
                    </CardContent>
                </Card>

                <!-- Pending Fees -->
                <Card class="hover:shadow-lg transition-all duration-200 border-orange-500">
                    <CardContent class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div class="p-3 bg-orange-100 dark:bg-orange-900/30 rounded-xl">
                                <AlertCircle class="w-6 h-6 text-orange-600 dark:text-orange-400" />
                            </div>
                            <Badge variant="secondary" class="bg-orange-100 text-orange-700 dark:bg-orange-900/30 dark:text-orange-400">
                                Due
                            </Badge>
                        </div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Pending Fees</p>
                        <p class="text-3xl font-bold text-gray-900 dark:text-white">UGX 200K</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                            <span class="text-orange-600 dark:text-orange-400 font-medium">Due Mar 5</span>
                        </p>
                    </CardContent>
                </Card>

                <!-- Average Attendance -->
                <Card class="hover:shadow-lg transition-all duration-200 border-blue-500">
                    <CardContent class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div class="p-3 bg-blue-100 dark:bg-blue-900/30 rounded-xl">
                                <TrendingUp class="w-6 h-6 text-blue-600 dark:text-blue-400" />
                            </div>
                            <Badge variant="secondary" class="bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400">
                                Excellent
                            </Badge>
                        </div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Avg Attendance</p>
                        <p class="text-3xl font-bold text-gray-900 dark:text-white">93.5%</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                            <span class="text-blue-600 dark:text-blue-400 font-medium">This term</span>
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
                                <Zap class="w-5 h-5 text-purple-500" />
                                Quick Actions
                            </CardTitle>
                            <CardDescription>
                                Essential parent portal features
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
                <!-- My Children & Activities -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- My Children -->
                    <Card>
                        <CardHeader>
                            <div class="flex items-center justify-between">
                                <div>
                                    <CardTitle class="flex items-center gap-2">
                                        <Baby class="w-5 h-5" />
                                        My Children
                                    </CardTitle>
                                    <CardDescription>
                                        Academic progress overview
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
                                    v-for="(child, index) in children"
                                    :key="index"
                                    class="flex items-center gap-4 p-4 rounded-xl border border-gray-100 dark:border-gray-800 hover:border-purple-200 dark:hover:border-purple-800 transition-all duration-200"
                                >
                                    <Avatar class="w-16 h-16">
                                        <AvatarImage 
                                            v-if="child.user?.photo"
                                            :src="`/storage/${child.user.photo}`" 
                                            :alt="child.user?.name"
                                        />
                                        <AvatarFallback class="text-xl font-bold bg-gradient-to-br from-blue-500 to-purple-600 text-white">
                                            {{ getInitials(child.user?.name) }}
                                        </AvatarFallback>
                                    </Avatar>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center justify-between mb-2">
                                            <h4 class="text-base font-semibold text-gray-900 dark:text-white">
                                                {{ child.user?.name || 'Student' }}
                                            </h4>
                                            <Badge :class="child.status === 'active' ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400' : 'bg-gray-100 text-gray-700 dark:bg-gray-900/30 dark:text-gray-400'">
                                                {{ child.status === 'active' ? 'Active' : 'Inactive' }}
                                            </Badge>
                                        </div>
                                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">
                                            {{ child.academic_class?.name || 'No Class' }} - {{ child.section?.name || 'No Section' }}
                                        </p>
                                        <div class="flex items-center gap-3 text-xs text-gray-500 dark:text-gray-400">
                                            <span class="flex items-center gap-1">
                                                <GraduationCap class="w-3 h-3" />
                                                {{ child.admission_number }}
                                            </span>
                                            <span class="flex items-center gap-1">
                                                <Trophy class="w-3 h-3 text-yellow-500" />
                                                Grade: A-
                                            </span>
                                            <span class="flex items-center gap-1">
                                                <CheckCircle class="w-3 h-3 text-emerald-500" />
                                                95% Attendance
                                            </span>
                                        </div>
                                    </div>
                                    <Button variant="outline" size="sm">
                                        <Eye class="w-4 h-4" />
                                    </Button>
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
                                Your recent parent portal activities
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

                    <!-- Payment Summary -->
                    <Card>
                        <CardHeader>
                            <CardTitle class="flex items-center gap-2">
                                <CreditCard class="w-5 h-5" />
                                Payment Summary
                            </CardTitle>
                        </CardHeader>
                        <CardContent>
                            <div class="space-y-4">
                                <div class="flex items-center justify-between py-2">
                                    <span class="text-sm text-gray-600 dark:text-gray-400">Total Fees</span>
                                    <span class="text-sm font-semibold text-gray-900 dark:text-white">UGX 750K</span>
                                </div>
                                <Separator />
                                <div class="flex items-center justify-between py-2">
                                    <span class="text-sm text-gray-600 dark:text-gray-400">Amount Paid</span>
                                    <span class="text-sm font-semibold text-emerald-600 dark:text-emerald-400">UGX 550K</span>
                                </div>
                                <Separator />
                                <div class="flex items-center justify-between py-2">
                                    <span class="text-sm text-gray-600 dark:text-gray-400">Balance</span>
                                    <span class="text-sm font-semibold text-orange-600 dark:text-orange-400">UGX 200K</span>
                                </div>
                                <Separator />
                                <div class="flex items-center justify-between py-2">
                                    <span class="text-sm text-gray-600 dark:text-gray-400">Due Date</span>
                                    <span class="text-sm font-semibold text-red-600 dark:text-red-400">Mar 5, 2026</span>
                                </div>
                            </div>
                            <Button class="w-full mt-4" size="sm">
                                <DollarSign class="w-4 h-4 mr-2" />
                                Make Payment
                            </Button>
                        </CardContent>
                    </Card>

                    <!-- Quick Contact -->
                    <Card class="bg-purple-500 border-0 text-white">
                        <CardContent class="p-6 text-center">
                            <MessageCircle class="w-12 h-12 mx-auto mb-3 opacity-90" />
                            <h3 class="font-semibold mb-2">Need Help?</h3>
                            <p class="text-sm text-purple-100 mb-4">Contact the school administration or your child's teacher</p>
                            <Button variant="secondary" size="sm" class="w-full">
                                <Phone class="w-4 h-4 mr-2" />
                                Contact School
                            </Button>
                        </CardContent>
                    </Card>
                </div>
            </div>
        </div>
    </Sidebar>
</template>