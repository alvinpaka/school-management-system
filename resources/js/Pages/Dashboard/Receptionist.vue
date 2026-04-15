<script setup>
import { Card, CardContent, CardDescription, CardFooter, CardHeader, CardTitle } from '@/Components/ui/card';
import { Badge } from '@/Components/ui/badge';
import { Button } from '@/Components/ui/button';
import { Avatar, AvatarFallback, AvatarImage } from '@/Components/ui/avatar';
import { Separator } from '@/Components/ui/separator';
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
    <Head title="Receptionist Dashboard | EduManage Pro" />

    <Sidebar>
        <template #header-title>
            <div class="flex items-center space-x-3">
                <DoorOpen class="w-5 h-5 text-terracotta" />
                <span class="font-semibold text-warm-text text-dark-text">Front Desk</span>
            </div>
        </template>

        <div class="mx-auto max-w-7xl space-y-8 animate-fade-in-up">
            <!-- Professional Header Section -->
            <div class="relative overflow-hidden card-warm rounded-3xl lg:rounded-[3rem] p-6 lg:p-12">
                <div class="flex flex-col md:flex-row items-center md:items-end gap-6 lg:gap-8">
                    <div class="w-20 h-20 lg:w-24 lg:h-24 bg-white/50 bg-dark-bg/50 rounded-2xl lg:rounded-[2.5rem] flex items-center justify-center border border-terracotta/20 shadow-inner overflow-hidden">
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
                                Front Desk Command
                            </Badge>
                            <span class="text-[10px] font-black text-warm-muted text-dark-muted uppercase tracking-widest">Active Operations</span>
                        </div>
                        <h1 class="text-3xl lg:text-5xl font-black text-warm-text text-dark-text tracking-tighter leading-tight">
                            Welcome, {{ receptionist?.name || $page.props.auth.user.name }}
                        </h1>
                        <p class="text-warm-muted text-dark-muted font-bold text-xs lg:text-sm uppercase tracking-wide">
                            {{ new Date().toLocaleDateString('en-US', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' }) }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Operational Matrix -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 lg:gap-6">
                <!-- New Admissions -->
                <div class="card-warm p-5 lg:p-6 rounded-3xl lg:rounded-[2.5rem] group lg:hover:border-terracotta/30 transition-all duration-300">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 rounded-2xl bg-terracotta/10 dark:bg-terracotta/20 flex items-center justify-center text-terracotta font-bold shadow-inner transition-transform lg:group-hover:scale-110">
                            <UserPlus class="w-6 h-6" />
                        </div>
                        <Badge class="bg-terracotta text-white border-0 font-black text-[9px] uppercase tracking-widest">Today</Badge>
                    </div>
                    <p class="text-xs font-black text-warm-muted text-dark-muted/80 uppercase tracking-widest mb-1">New Admissions</p>
                    <h3 class="text-3xl font-black text-warm-text text-dark-text tracking-tighter leading-none">{{ todayStats.newAdmissions }}</h3>
                </div>

                <!-- Visitors -->
                <div class="card-warm p-5 lg:p-6 rounded-3xl lg:rounded-[2.5rem] group lg:hover:border-forest/30 transition-all duration-300">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 rounded-2xl bg-forest/10 dark:bg-forest/20 flex items-center justify-center text-forest dark:text-[#5AAA72] font-bold shadow-inner transition-transform lg:group-hover:scale-110">
                            <DoorOpen class="w-6 h-6" />
                        </div>
                        <Badge class="bg-muted border-0 text-warm-muted text-dark-muted font-black text-[9px] uppercase tracking-widest">+2 Active</Badge>
                    </div>
                    <p class="text-xs font-black text-warm-muted text-dark-muted/80 uppercase tracking-widest mb-1">Total Visitors</p>
                    <h3 class="text-3xl font-black text-warm-text text-dark-text tracking-tighter leading-none">{{ todayStats.visitors }}</h3>
                </div>

                <!-- Appointments -->
                <div class="card-warm p-5 lg:p-6 rounded-3xl lg:rounded-[2.5rem] group lg:hover:border-amber/30 transition-all duration-300">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 rounded-2xl bg-amber/10 dark:bg-amber/20 flex items-center justify-center text-amber font-bold shadow-inner transition-transform lg:group-hover:scale-110">
                            <CalendarDays class="w-6 h-6" />
                        </div>
                        <Badge class="bg-amber text-white border-0 font-black text-[9px] uppercase tracking-widest">Scheduled</Badge>
                    </div>
                    <p class="text-xs font-black text-warm-muted text-dark-muted/80 uppercase tracking-widest mb-1">Appointments</p>
                    <h3 class="text-3xl font-black text-warm-text text-dark-text tracking-tighter leading-none">{{ todayStats.appointments }}</h3>
                </div>

                <!-- Phone Calls -->
                <div class="card-warm p-5 lg:p-6 rounded-3xl lg:rounded-[2.5rem] group lg:hover:border-amber/30 transition-all duration-300">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 rounded-2xl bg-amber/10 dark:bg-amber/20 flex items-center justify-center text-amber font-bold shadow-inner transition-transform lg:group-hover:scale-110">
                            <Phone class="w-6 h-6" />
                        </div>
                        <Badge class="bg-muted border-0 text-warm-muted text-dark-muted font-black text-[9px] uppercase tracking-widest">Logs</Badge>
                    </div>
                    <p class="text-xs font-black text-warm-muted text-dark-muted/80 uppercase tracking-widest mb-1">Phone Calls</p>
                    <h3 class="text-3xl font-black text-warm-text text-dark-text tracking-tighter leading-none">{{ todayStats.phoneCalls }}</h3>
                </div>

                <!-- Check-ins -->
                <div class="card-warm p-5 lg:p-6 rounded-3xl lg:rounded-[2.5rem] group lg:hover:border-forest/30 transition-all duration-300">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 rounded-2xl bg-forest/10 dark:bg-forest/20 flex items-center justify-center text-forest dark:text-[#5AAA72] font-bold shadow-inner transition-transform lg:group-hover:scale-110">
                            <ClipboardList class="w-6 h-6" />
                        </div>
                        <Badge class="bg-forest text-white border-0 font-black text-[9px] uppercase tracking-widest">Completed</Badge>
                    </div>
                    <p class="text-xs font-black text-warm-muted text-dark-muted/80 uppercase tracking-widest mb-1">Total Check-ins</p>
                    <h3 class="text-3xl font-black text-warm-text text-dark-text tracking-tighter leading-none">{{ todayStats.checkIns }}</h3>
                </div>
            </div>

            <!-- Operational Intelligence Tools -->
            <div class="card-warm rounded-3xl lg:rounded-[3rem] overflow-hidden">
                <div class="p-6 lg:p-8 border-b border-terracotta/20 bg-terracotta/5">
                    <h3 class="text-2xl font-black tracking-tighter flex items-center gap-3 text-warm-text text-dark-text">
                        <Zap class="w-6 h-6 text-terracotta" />
                        Strategic Operational Terminal
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
                                <div class="w-14 h-14 rounded-2xl bg-white bg-dark-bg border border-terracotta/20 flex items-center justify-center shadow-inner lg:group-hover:bg-terracotta lg:group-hover:text-white lg:group-hover:border-terracotta transition-all">
                                    <component :is="action.icon" class="w-7 h-7 text-terracotta lg:group-hover:text-white" />
                                </div>
                                <div>
                                    <h3 class="text-xs font-black text-warm-text text-dark-text uppercase tracking-widest leading-none mb-1">{{ action.title }}</h3>
                                    <p class="text-[9px] font-bold text-warm-muted text-dark-muted uppercase tracking-widest opacity-60 line-clamp-1">{{ action.description }}</p>
                                </div>
                            </div>
                        </Link>
                    </div>
                </div>
            </div>

            <!-- Main Intelligence Hub -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Visitor Log & Communication -->
                <div class="lg:col-span-2 space-y-6 lg:space-y-8">
                    <div class="card-warm rounded-3xl lg:rounded-[3rem] overflow-hidden">
                        <div class="p-6 lg:p-8 border-b border-terracotta/20">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                                <h3 class="text-2xl font-black tracking-tighter flex items-center gap-3 text-warm-text text-dark-text">
                                    <DoorOpen class="w-6 h-6 text-terracotta" />
                                    Active Visitor Registry
                                </h3>
                                <div class="flex gap-2">
                                    <Button variant="ghost" size="sm" class="rounded-xl font-black text-[10px] uppercase tracking-widest text-warm-muted text-dark-muted border border-terracotta/20">
                                        <Search class="w-4 h-4 mr-2" /> Find
                                    </Button>
                                    <Button variant="ghost" size="sm" class="rounded-xl font-black text-[10px] uppercase tracking-widest text-warm-muted text-dark-muted border border-terracotta/20">
                                        <Plus class="w-4 h-4 mr-2" /> New Entry
                                    </Button>
                                </div>
                            </div>
                        </div>
                        <div class="p-4 lg:p-8">
                            <div class="space-y-4">
                                <div v-for="(visitor, index) in recentVisitors" :key="index" class="flex flex-col sm:flex-row sm:items-center gap-4 lg:gap-6 p-4 lg:p-5 rounded-3xl lg:rounded-[2.5rem] bg-terracotta/5 border border-terracotta/20 group transition-all lg:hover:bg-white lg:hover:bg-dark-bg lg:hover:shadow-md">
                                    <Avatar class="w-14 h-14 border-2 border-terracotta/20 lg:group-hover:scale-110 transition-transform">
                                        <AvatarFallback class="bg-terracotta/10 text-terracotta font-black text-base">
                                            {{ getInitials(visitor.name) }}
                                        </AvatarFallback>
                                    </Avatar>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center justify-between mb-2">
                                            <h4 class="text-lg font-black text-warm-text text-dark-text tracking-tighter truncate">{{ visitor.name }}</h4>
                                            <Badge :class="[
                                                'font-black text-[10px] uppercase h-6 px-3 border-0',
                                                visitor.status === 'completed' ? 'bg-forest/10 text-forest' : 'bg-amber/10 text-amber'
                                            ]">
                                                {{ visitor.status === 'completed' ? 'Cleared' : 'In Session' }}
                                            </Badge>
                                        </div>
                                        <p class="text-sm font-bold text-warm-muted text-dark-muted uppercase tracking-wide truncate mb-2">{{ visitor.purpose }}</p>
                                        <div class="flex flex-wrap items-center gap-6 text-[10px] font-black uppercase text-warm-muted text-dark-muted/80 opacity-80">
                                            <span class="flex items-center gap-1.5"><Clock class="w-3.5 h-3.5 text-forest/60" /> {{ visitor.timeIn }} - {{ visitor.timeOut }}</span>
                                            <span class="flex items-center gap-1.5"><UserCheck class="w-3.5 h-3.5 text-terracotta/60" /> Met: {{ visitor.personMet }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Phone log Tracking -->
                    <div class="card-warm rounded-3xl lg:rounded-[3rem] overflow-hidden">
                        <div class="p-6 lg:p-8 border-b border-terracotta/20 bg-terracotta/5">
                            <h3 class="text-xl font-black tracking-tighter flex items-center gap-3 text-warm-text text-dark-text/80">
                                <PhoneCall class="w-5 h-5 flex-shrink-0 text-terracotta" />
                                Communication Intelligence
                            </h3>
                        </div>
                        <div class="p-6 lg:p-8">
                            <div class="space-y-4">
                                <div v-for="(call, index) in recentCalls" :key="index" class="flex items-center justify-between p-4 rounded-[2rem] border border-terracotta/20 group transition-all hover:bg-terracotta/5">
                                    <div class="flex items-center gap-4">
                                        <div class="w-10 h-10 rounded-xl bg-amber/10 dark:bg-amber/20 flex items-center justify-center text-amber shrink-0 group-hover:scale-110 transition-transform">
                                            <Phone class="w-5 h-5" />
                                        </div>
                                        <div>
                                            <h4 class="text-sm font-black text-warm-text text-dark-text tracking-tight">{{ call.name }}</h4>
                                            <p class="text-[10px] font-bold text-warm-muted text-dark-muted uppercase tracking-widest">{{ call.time }} • {{ call.duration }}</p>
                                        </div>
                                    </div>
                                    <Badge variant="outline" class="font-black text-[9px] h-5 border-forest/30 text-forest uppercase">
                                        {{ call.status }}
                                    </Badge>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Strategic Insights Sidebar -->
                <div class="space-y-8">
                    <!-- Scheduled Appointments -->
                    <div class="card-warm rounded-3xl lg:rounded-[3rem] overflow-hidden">
                        <div class="p-6 lg:p-8 border-b border-terracotta/20">
                            <h3 class="text-xl font-black tracking-tighter flex items-center gap-3 text-warm-text text-dark-text">
                                <CalendarDays class="w-6 h-6 text-amber" />
                                Priority Meetings
                                <Badge class="bg-amber text-white border-0 font-black text-[10px]">{{ upcomingAppointments.length }}</Badge>
                            </h3>
                        </div>
                        <div class="p-6 lg:p-8">
                            <div class="space-y-4">
                                <div v-for="(appointment, index) in upcomingAppointments" :key="index" class="p-5 rounded-[2rem] bg-amber/5 border border-amber/20 group hover:border-amber transition-all">
                                    <div class="flex items-start justify-between mb-4">
                                        <div>
                                            <h4 class="text-base font-black text-warm-text text-dark-text tracking-tight leading-none mb-1">{{ appointment.title }}</h4>
                                            <p class="text-[10px] font-bold text-warm-muted text-dark-muted uppercase tracking-widest">{{ appointment.location }}</p>
                                        </div>
                                        <Badge variant="outline" class="font-black text-[8px] h-5 border-amber/30 text-amber uppercase">
                                            {{ appointment.type }}
                                        </Badge>
                                    </div>
                                    <div class="flex flex-wrap items-center gap-6 text-[10px] font-black uppercase text-warm-muted text-dark-muted/80">
                                        <span class="flex items-center gap-1.5"><Clock class="w-3.5 h-3.5 text-amber/60" /> {{ appointment.time }}</span>
                                        <span class="flex items-center gap-1.5"><Users class="w-3.5 h-3.5 text-terracotta/60" /> {{ appointment.attendees }} Attendees</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Operational Analytics -->
                    <div class="card-warm rounded-3xl lg:rounded-[3rem] overflow-hidden">
                        <div class="p-6 lg:p-8 border-b border-terracotta/20">
                            <h3 class="text-xl font-black tracking-tighter flex items-center gap-3 text-warm-text text-dark-text/80">
                                <BarChart3 class="w-5 h-5 text-terracotta" />
                                Institutional Pulse
                            </h3>
                        </div>
                        <div class="p-6 lg:p-8">
                            <div class="space-y-6">
                                <div v-for="(val, label) in {
                                    'Total Logins': todayStats.checkIns,
                                    'Admission Rate': todayStats.newAdmissions + ' Today',
                                    'Visitor Density': todayStats.visitors,
                                    'System Uptime': '99.9%'
                                }" :key="label" class="flex flex-col sm:flex-row sm:items-center justify-between py-1 group gap-1">
                                    <span class="text-[11px] font-black text-warm-muted text-dark-muted uppercase tracking-widest lg:group-hover:text-terracotta transition-colors">{{ label }}</span>
                                    <span class="text-sm font-black text-warm-text text-dark-text tracking-tight">{{ val }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Strategic Broadcasting -->
                    <div class="accent-terracotta rounded-3xl lg:rounded-[3rem] p-8 lg:p-10 text-white shadow-2xl shadow-terracotta/30 relative overflow-hidden group">
                        <div class="relative z-10 text-center space-y-6">
                            <div class="w-16 h-16 lg:w-20 lg:h-20 bg-white/10 dark:bg-white/5 rounded-2xl lg:rounded-[2rem] flex items-center justify-center mx-auto border border-white/20 dark:border-white/10 lg:group-hover:scale-110 transition-transform">
                                <MessageSquare class="w-8 h-8 lg:w-10 lg:h-10 text-white" />
                            </div>
                            <div>
                                <h3 class="text-xl lg:text-2xl font-black tracking-tighter leading-none mb-3">Announcement</h3>
                                <p class="text-white/70 text-xs font-bold uppercase tracking-wide leading-relaxed px-2">
                                    Broadcast high-priority directives to institutional stakeholders immediately.
                                </p>
                            </div>
                            <Button class="w-full bg-white text-terracotta hover:bg-white/90 font-black rounded-2xl h-12 text-[10px] uppercase tracking-widest border-0 shadow-lg">
                                <MessageSquare class="w-4 h-4 mr-2" /> Dispatch Signal
                            </Button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </Sidebar>
</template>