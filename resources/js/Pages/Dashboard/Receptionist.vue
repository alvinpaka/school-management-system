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
    Phone,
    MessageSquare,
    ClipboardList,
    UserPlus,
    CalendarDays,
    DoorOpen,
    Sparkles,
    ChevronRight,
    Plus,
    Zap,
    Activity,
    LogIn,
    LogOut,
    Search,
    PhoneCall
} from 'lucide-vue-next';

defineProps({
    receptionist: Object,
    stats: Object
});

const quickActions = [
    {
        title: 'New Admission',
        description: 'Register student',
        icon: UserPlus,
        color: 'from-blue-500 to-blue-600',
        href: route('students.create')
    },
    {
        title: 'Check-In',
        description: 'Student arrival',
        icon: LogIn,
        color: 'from-emerald-500 to-emerald-600',
        href: route('attendance.index')
    },
    {
        title: 'Visitors',
        description: 'Manage visitors',
        icon: DoorOpen,
        color: 'from-purple-500 to-purple-600',
        href: '#'
    },
    {
        title: 'Calls',
        description: 'Phone logs',
        icon: Phone,
        color: 'from-orange-500 to-orange-600',
        href: '#'
    },
    {
        title: 'Appointments',
        description: 'Schedule meeting',
        icon: CalendarDays,
        color: 'from-pink-500 to-pink-600',
        href: '#'
    },
    {
        title: 'Messages',
        description: 'View messages',
        icon: MessageSquare,
        color: 'from-teal-500 to-teal-600',
        href: '#'
    }
];

const todayStats = {
    newAdmissions: 3,
    visitors: 12,
    appointments: 5,
    phoneCalls: 8,
    checkIns: 45
};

const recentVisitors = [
    {
        name: 'John Smith',
        purpose: 'Parent meeting',
        timeIn: '9:00 AM',
        timeOut: '9:45 AM',
        personMet: 'Ms. Johnson',
        status: 'completed'
    },
    {
        name: 'Sarah Wilson',
        purpose: 'Student inquiry',
        timeIn: '10:30 AM',
        timeOut: '11:00 AM',
        personMet: 'Mr. Brown',
        status: 'completed'
    },
    {
        name: 'Michael Davis',
        purpose: 'Document submission',
        timeIn: '11:15 AM',
        timeOut: 'In Progress',
        personMet: 'Ms. Davis',
        status: 'in-progress'
    },
    {
        name: 'Emily Johnson',
        purpose: 'School tour',
        timeIn: '12:00 PM',
        timeOut: 'In Progress',
        personMet: 'Reception',
        status: 'in-progress'
    }
];

const upcomingAppointments = [
    {
        title: 'Parent-Teacher Meeting',
        time: '2:00 PM',
        duration: '1 hour',
        location: 'Conference Room A',
        attendees: 5,
        type: 'meeting'
    },
    {
        title: 'School Tour',
        time: '3:30 PM',
        duration: '45 minutes',
        location: 'Main Office',
        attendees: 8,
        type: 'tour'
    },
    {
        title: 'Staff Meeting',
        time: '4:00 PM',
        duration: '30 minutes',
        location: 'Staff Room',
        attendees: 12,
        type: 'meeting'
    }
];

const recentCalls = [
    { name: 'Parent Inquiry', time: '10:15 AM', duration: '5 min', status: 'completed' },
    { name: 'Admission Query', time: '11:30 AM', duration: '8 min', status: 'completed' },
    { name: 'Teacher Request', time: '12:45 PM', duration: '3 min', status: 'completed' }
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
    <Head title="Receptionist Dashboard" />

    <Sidebar>
        <template #header-title>
            <div class="flex items-center space-x-3">
                <DoorOpen class="w-5 h-5" />
                <span class="font-semibold">Front Desk</span>
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
                                <AvatarFallback class="text-2xl font-bold bg-teal-500 text-white">
                                    {{ getInitials($page.props.auth.user.name) }}
                                </AvatarFallback>
                            </Avatar>
                            <div class="mt-4">
                                <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white flex items-center gap-2">
                                    Welcome back, {{ $page.props.auth.user.name }}
                                    <PhoneCall class="w-6 h-6 text-teal-500" />
                                </h1>
                                <p class="text-base text-gray-600 dark:text-gray-400">
                                    Front desk operations and visitor management
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

            <!-- Today's Stats -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6">
                <!-- New Admissions -->
                <Card class="hover:shadow-lg transition-all duration-200 border-blue-500">
                    <CardContent class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div class="p-3 bg-blue-100 dark:bg-blue-900/30 rounded-xl">
                                <UserPlus class="w-6 h-6 text-blue-600 dark:text-blue-400" />
                            </div>
                            <Badge variant="secondary" class="bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400">
                                Today
                            </Badge>
                        </div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">New Admissions</p>
                        <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ todayStats.newAdmissions }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                            <span class="text-blue-600 dark:text-blue-400 font-medium">Students registered</span>
                        </p>
                    </CardContent>
                </Card>

                <!-- Visitors -->
                <Card class="hover:shadow-lg transition-all duration-200 border-emerald-500">
                    <CardContent class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div class="p-3 bg-emerald-100 dark:bg-emerald-900/30 rounded-xl">
                                <DoorOpen class="w-6 h-6 text-emerald-600 dark:text-emerald-400" />
                            </div>
                            <Badge variant="secondary" class="bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400">
                                <TrendingUp class="w-3 h-3 mr-1" />
                                +2
                            </Badge>
                        </div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Visitors Today</p>
                        <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ todayStats.visitors }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                            <span class="text-emerald-600 dark:text-emerald-400 font-medium">2 currently in</span>
                        </p>
                    </CardContent>
                </Card>

                <!-- Appointments -->
                <Card class="hover:shadow-lg transition-all duration-200 border-purple-500">
                    <CardContent class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div class="p-3 bg-purple-100 dark:bg-purple-900/30 rounded-xl">
                                <CalendarDays class="w-6 h-6 text-purple-600 dark:text-purple-400" />
                            </div>
                            <Badge variant="secondary" class="bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-400">
                                Scheduled
                            </Badge>
                        </div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Appointments</p>
                        <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ todayStats.appointments }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                            <span class="text-purple-600 dark:text-purple-400 font-medium">Next at 2:00 PM</span>
                        </p>
                    </CardContent>
                </Card>

                <!-- Phone Calls -->
                <Card class="hover:shadow-lg transition-all duration-200 border-orange-500">
                    <CardContent class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div class="p-3 bg-orange-100 dark:bg-orange-900/30 rounded-xl">
                                <Phone class="w-6 h-6 text-orange-600 dark:text-orange-400" />
                            </div>
                            <Badge variant="secondary" class="bg-orange-100 text-orange-700 dark:bg-orange-900/30 dark:text-orange-400">
                                Handled
                            </Badge>
                        </div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Phone Calls</p>
                        <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ todayStats.phoneCalls }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                            <span class="text-orange-600 dark:text-orange-400 font-medium">Avg 6 min each</span>
                        </p>
                    </CardContent>
                </Card>

                <!-- Check-ins -->
                <Card class="hover:shadow-lg transition-all duration-200 border-teal-500">
                    <CardContent class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div class="p-3 bg-teal-100 dark:bg-teal-900/30 rounded-xl">
                                <ClipboardList class="w-6 h-6 text-teal-600 dark:text-teal-400" />
                            </div>
                            <Badge variant="secondary" class="bg-teal-100 text-teal-700 dark:bg-teal-900/30 dark:text-teal-400">
                                Active
                            </Badge>
                        </div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Check-ins</p>
                        <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ todayStats.checkIns }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                            <span class="text-teal-600 dark:text-teal-400 font-medium">Students arrived</span>
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
                                <Zap class="w-5 h-5 text-teal-500" />
                                Quick Actions
                            </CardTitle>
                            <CardDescription>
                                Front desk operations and visitor management
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
                <!-- Visitor Log -->
                <div class="lg:col-span-2 space-y-6">
                    <Card>
                        <CardHeader>
                            <div class="flex items-center justify-between">
                                <div>
                                    <CardTitle class="flex items-center gap-2">
                                        <DoorOpen class="w-5 h-5" />
                                        Today's Visitors
                                    </CardTitle>
                                    <CardDescription>
                                        Recent visitor check-ins and check-outs
                                    </CardDescription>
                                </div>
                                <div class="flex items-center gap-2">
                                    <Button variant="ghost" size="sm">
                                        <Search class="w-4 h-4" />
                                    </Button>
                                    <Button variant="ghost" size="sm">
                                        <Plus class="w-4 h-4 mr-1" />
                                        New
                                    </Button>
                                </div>
                            </div>
                        </CardHeader>
                        <CardContent>
                            <div class="space-y-3">
                                <div
                                    v-for="(visitor, index) in recentVisitors"
                                    :key="index"
                                    class="flex items-center gap-4 p-4 rounded-xl border border-gray-100 dark:border-gray-800 hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-all duration-200"
                                >
                                    <Avatar class="w-12 h-12">
                                        <AvatarFallback class="bg-gradient-to-br from-teal-500 to-cyan-600 text-white font-bold">
                                            {{ getInitials(visitor.name) }}
                                        </AvatarFallback>
                                    </Avatar>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center justify-between mb-1">
                                            <h4 class="text-sm font-semibold text-gray-900 dark:text-white">{{ visitor.name }}</h4>
                                            <Badge 
                                                :class="[
                                                    visitor.status === 'completed' ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400' : 
                                                    'bg-orange-100 text-orange-700 dark:bg-orange-900/30 dark:text-orange-400'
                                                ]"
                                            >
                                                {{ visitor.status === 'completed' ? 'Completed' : 'In Progress' }}
                                            </Badge>
                                        </div>
                                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">{{ visitor.purpose }}</p>
                                        <div class="flex items-center gap-3 text-xs text-gray-500 dark:text-gray-400">
                                            <span class="flex items-center gap-1">
                                                <Clock class="w-3 h-3" />
                                                {{ visitor.timeIn }} - {{ visitor.timeOut }}
                                            </span>
                                            <span class="flex items-center gap-1">
                                                <UserCheck class="w-3 h-3" />
                                                Met: {{ visitor.personMet }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </CardContent>
                    </Card>

                    <!-- Recent Calls -->
                    <Card>
                        <CardHeader>
                            <CardTitle class="flex items-center gap-2">
                                <Phone class="w-5 h-5" />
                                Recent Calls
                            </CardTitle>
                            <CardDescription>
                                Today's phone call log
                            </CardDescription>
                        </CardHeader>
                        <CardContent>
                            <div class="space-y-3">
                                <div
                                    v-for="(call, index) in recentCalls"
                                    :key="index"
                                    class="flex items-center justify-between p-3 rounded-lg border border-gray-100 dark:border-gray-800"
                                >
                                    <div class="flex items-center gap-3">
                                        <div class="p-2 bg-orange-100 dark:bg-orange-900/30 rounded-lg">
                                            <Phone class="w-4 h-4 text-orange-600 dark:text-orange-400" />
                                        </div>
                                        <div>
                                            <h4 class="text-sm font-semibold text-gray-900 dark:text-white">{{ call.name }}</h4>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ call.time }} • {{ call.duration }}</p>
                                        </div>
                                    </div>
                                    <Badge variant="outline" class="border-emerald-300 text-emerald-700 dark:border-emerald-700 dark:text-emerald-400">
                                        {{ call.status }}
                                    </Badge>
                                </div>
                            </div>
                        </CardContent>
                    </Card>
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Upcoming Appointments -->
                    <Card>
                        <CardHeader>
                            <div class="flex items-center justify-between">
                                <CardTitle class="flex items-center gap-2">
                                    <CalendarDays class="w-5 h-5" />
                                    Appointments
                                    <Badge variant="secondary" class="ml-1">{{ upcomingAppointments.length }}</Badge>
                                </CardTitle>
                            </div>
                        </CardHeader>
                        <CardContent>
                            <div class="space-y-3">
                                <div
                                    v-for="(appointment, index) in upcomingAppointments"
                                    :key="index"
                                    class="p-3 rounded-lg border border-purple-200 dark:border-purple-800 bg-purple-50 dark:bg-purple-900/10"
                                >
                                    <div class="flex items-start justify-between mb-2">
                                        <div>
                                            <h4 class="text-sm font-semibold text-gray-900 dark:text-white">{{ appointment.title }}</h4>
                                            <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">{{ appointment.location }}</p>
                                        </div>
                                        <Badge variant="outline" class="border-purple-300 text-purple-700 dark:border-purple-700 dark:text-purple-400 text-xs">
                                            {{ appointment.type }}
                                        </Badge>
                                    </div>
                                    <div class="flex items-center gap-3 text-xs text-gray-500 dark:text-gray-400 mt-2">
                                        <span class="flex items-center gap-1">
                                            <Clock class="w-3 h-3" />
                                            {{ appointment.time }}
                                        </span>
                                        <span class="flex items-center gap-1">
                                            <Users class="w-3 h-3" />
                                            {{ appointment.attendees }} people
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <Button variant="outline" class="w-full mt-3" size="sm">
                                <Plus class="w-4 h-4 mr-2" />
                                New Appointment
                            </Button>
                        </CardContent>
                    </Card>

                    <!-- Quick Summary -->
                    <Card>
                        <CardHeader>
                            <CardTitle class="flex items-center gap-2">
                                <BarChart3 class="w-5 h-5" />
                                Today's Summary
                            </CardTitle>
                        </CardHeader>
                        <CardContent>
                            <div class="space-y-4">
                                <div class="flex items-center justify-between py-2">
                                    <span class="text-sm text-gray-600 dark:text-gray-400">Total Visitors</span>
                                    <span class="text-sm font-semibold text-gray-900 dark:text-white">{{ todayStats.visitors }}</span>
                                </div>
                                <Separator />
                                <div class="flex items-center justify-between py-2">
                                    <span class="text-sm text-gray-600 dark:text-gray-400">New Admissions</span>
                                    <span class="text-sm font-semibold text-blue-600 dark:text-blue-400">{{ todayStats.newAdmissions }}</span>
                                </div>
                                <Separator />
                                <div class="flex items-center justify-between py-2">
                                    <span class="text-sm text-gray-600 dark:text-gray-400">Phone Calls</span>
                                    <span class="text-sm font-semibold text-orange-600 dark:text-orange-400">{{ todayStats.phoneCalls }}</span>
                                </div>
                                <Separator />
                                <div class="flex items-center justify-between py-2">
                                    <span class="text-sm text-gray-600 dark:text-gray-400">Check-ins</span>
                                    <span class="text-sm font-semibold text-emerald-600 dark:text-emerald-400">{{ todayStats.checkIns }}</span>
                                </div>
                            </div>
                        </CardContent>
                    </Card>

                    <!-- Quick Communication -->
                    <Card class="bg-teal-500 border-0 text-white">
                        <CardContent class="p-6 text-center">
                            <MessageSquare class="w-12 h-12 mx-auto mb-3 opacity-90" />
                            <h3 class="font-semibold mb-2">Announcement</h3>
                            <p class="text-sm text-teal-100 mb-4">Broadcast messages to staff or parents</p>
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