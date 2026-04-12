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
    Zap,
    TrendingDown,
    MoreVertical
} from 'lucide-vue-next';

defineProps({
    stats: Object,
    recentActivities: Array
});

const quickActions = [
    {
        title: 'Enroll Student',
        description: 'New admission registry',
        icon: Users,
        gradient: 'from-blue-500 to-indigo-600',
        glow: 'shadow-blue-500/20',
        href: route('students.create')
    },
    {
        title: 'Recruit Teacher',
        description: 'Faculty management',
        icon: UserCheck,
        gradient: 'from-emerald-500 to-teal-600',
        glow: 'shadow-emerald-500/20',
        href: route('teachers.create')
    },
    {
        title: 'Fee Collection',
        description: 'Financial accounting',
        icon: DollarSign,
        gradient: 'from-orange-500 to-amber-600',
        glow: 'shadow-orange-500/20',
        href: route('fees.index')
    },
    {
        title: 'Academic Exams',
        description: 'Grading & results',
        icon: FileText,
        gradient: 'from-violet-500 to-purple-600',
        glow: 'shadow-violet-500/20',
        href: route('exams.index')
    }
];

const upcomingEvents = [
    {
        title: 'Parent-Teacher Summit',
        date: 'Feb 20, 2026',
        time: '2:00 PM',
        type: 'Conference',
        icon: HeartHandshake,
        color: 'text-indigo-600 dark:text-indigo-400 bg-indigo-50 dark:bg-indigo-950/50'
    },
    {
        title: 'Mid-Term Examinations',
        date: 'Feb 25, 2026',
        time: '8:00 AM',
        type: 'Academic',
        icon: Award,
        color: 'text-rose-600 dark:text-rose-400 bg-rose-50 dark:bg-rose-950/50'
    }
];

const getInitials = (name) => {
    return name?.split(' ').map(word => word[0]).join('').toUpperCase().slice(0, 2) || 'AD';
};

const getIconComponent = (iconName) => {
    const icons = { Users, DollarSign, Calendar, FileText, GraduationCap, UserCheck, Bell, Clock, CheckCircle };
    return icons[iconName] || Activity;
};
</script>

<template>
    <Head title="Admin Dashboard | EduManage Pro" />

    <Sidebar>
        <template #header-title>
            <div class="flex items-center gap-2">
                <div class="p-2 bg-primary/10 rounded-lg">
                    <Home class="w-4 h-4 text-primary" />
                </div>
                <span class="font-black text-sm uppercase tracking-wider text-muted-foreground">Overview</span>
            </div>
        </template>

        <div class="space-y-8 animate-fade-in-up">
            <!-- Dramatic Welcome Header -->
            <div class="relative overflow-hidden bg-card shadow-sm rounded-3xl lg:rounded-[2.5rem] border border-border p-6 lg:p-12">
                <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-8">
                    <div class="flex items-center gap-4 lg:gap-6">
                        <div class="relative group">
                            <Avatar class="w-16 h-16 lg:w-24 lg:h-24 border-2 border-border rounded-2xl lg:rounded-3xl relative">
                                <AvatarImage 
                                    v-if="$page.props.auth.user.photo"
                                    :src="`/storage/${$page.props.auth.user.photo}`" 
                                    :alt="$page.props.auth.user.name"
                                    class="object-cover"
                                />
                                <AvatarFallback class="text-xl lg:text-3xl font-black bg-indigo-600 text-white rounded-2xl lg:rounded-3xl uppercase">
                                    {{ getInitials($page.props.auth.user.name) }}
                                </AvatarFallback>
                            </Avatar>
                        </div>
                        <div>
                            <h1 class="text-3xl lg:text-5xl font-black text-foreground mb-1 lg:mb-2 tracking-tighter leading-tight">
                                Welcome, {{ $page.props.auth.user.name.split(' ')[0] }}! <span class="animate-pulse">👋</span>
                            </h1>
                            <p class="text-base lg:text-lg text-muted-foreground font-medium">
                                The management engine is running at <span class="text-emerald-500 font-black">99.9% peak efficiency</span>.
                            </p>
                        </div>
                    </div>
                    
                    <div class="flex flex-col items-start md:items-end">
                        <div class="bg-muted px-4 lg:px-6 py-2 lg:py-3 rounded-2xl border border-border text-left md:text-right">
                            <p class="text-[10px] font-black uppercase tracking-widest text-primary mb-1">Current Session</p>
                            <p class="text-sm lg:text-base font-bold text-foreground">
                                {{ new Date().toLocaleDateString('en-US', { weekday: 'long', month: 'long', day: 'numeric' }) }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Dashboard Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                
                <!-- Main Content (8 cols) -->
                <div class="lg:col-span-8 space-y-8">
                    
                    <!-- Premium Stats Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 lg:gap-6">
                        <!-- Students -->
                        <div class="bg-card p-5 lg:p-6 rounded-3xl lg:rounded-[2rem] border border-border shadow-sm group cursor-pointer hover:border-primary/30 transition-all duration-300">
                            <div class="flex items-center justify-between mb-6">
                                <div class="w-14 h-14 rounded-2xl bg-indigo-50 dark:bg-indigo-500/10 flex items-center justify-center text-indigo-600 group-hover:bg-indigo-600 group-hover:text-white transition-colors duration-300">
                                    <GraduationCap class="w-7 h-7" />
                                </div>
                                <Badge class="bg-indigo-50 dark:bg-indigo-500/10 text-indigo-600 border-0 font-black">
                                    <TrendingUp class="w-3 h-3 mr-1" />
                                    +12%
                                </Badge>
                            </div>
                            <p class="text-xs font-black uppercase tracking-widest text-muted-foreground/80 mb-1">Total Students</p>
                            <h3 class="text-4xl font-black text-foreground tracking-tighter">{{ stats.students_count || 0 }}</h3>
                        </div>

                        <!-- Faculty -->
                        <div class="bg-card p-5 lg:p-6 rounded-3xl lg:rounded-[2rem] border border-border shadow-sm group cursor-pointer hover:border-emerald-500/30 transition-all duration-300">
                            <div class="flex items-center justify-between mb-6">
                                <div class="w-14 h-14 rounded-2xl bg-emerald-50 dark:bg-emerald-500/10 flex items-center justify-center text-emerald-600 group-hover:bg-emerald-600 group-hover:text-white transition-colors duration-300">
                                    <UserCheck class="w-7 h-7" />
                                </div>
                                <Badge class="bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 border-0 font-black">
                                    <TrendingUp class="w-3 h-3 mr-1" />
                                    +5.2%
                                </Badge>
                            </div>
                            <p class="text-xs font-black uppercase tracking-widest text-muted-foreground/80 mb-1">Expert Faculty</p>
                            <h3 class="text-4xl font-black text-foreground tracking-tighter">{{ stats.teachers_count || 0 }}</h3>
                        </div>

                        <!-- Finance (Revenue) -->
                        <div class="bg-card p-5 lg:p-6 rounded-3xl lg:rounded-[2rem] border border-border shadow-sm group cursor-pointer hover:border-rose-500/30 transition-all duration-300">
                            <div class="flex items-center justify-between mb-6">
                                <div class="w-14 h-14 rounded-2xl bg-rose-50 dark:bg-rose-500/10 flex items-center justify-center text-rose-600 group-hover:bg-rose-600 group-hover:text-white transition-colors duration-300">
                                    <DollarSign class="w-7 h-7" />
                                </div>
                                <Badge class="bg-rose-50 dark:bg-rose-500/10 text-rose-600 border-0 font-black">
                                    <TrendingDown class="w-3 h-3 mr-1" />
                                    -2.1%
                                </Badge>
                            </div>
                            <p class="text-xs font-black uppercase tracking-widest text-muted-foreground/80 mb-1">Revenue Flow</p>
                            <h3 class="text-4xl font-black text-foreground tracking-tighter">12.5M</h3>
                        </div>
                    </div>

                    <!-- Quick Command Center -->
                    <div class="space-y-4">
                        <div class="flex items-center justify-between px-2">
                            <h2 class="text-xl font-black text-foreground tracking-tight uppercase px-2 py-1 bg-muted rounded-lg inline-block">Command Center</h2>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <Link 
                                v-for="action in quickActions" 
                                :key="action.title" 
                                :href="action.href"
                                class="bg-card p-4 rounded-3xl border border-border hover:border-primary/30 shadow-sm group transition-all duration-500"
                            >
                                <div class="flex items-center gap-5 relative z-10">
                                    <div class="w-16 h-16 rounded-2xl bg-muted flex items-center justify-center text-muted-foreground transition-all duration-500 group-hover:bg-primary group-hover:text-white group-hover:shadow-lg group-hover:shadow-primary/20">
                                        <component :is="action.icon" class="w-8 h-8" />
                                    </div>
                                    <div class="flex-1">
                                        <h4 class="text-lg font-black text-foreground tracking-tight">{{ action.title }}</h4>
                                        <p class="text-sm text-muted-foreground font-medium">{{ action.description }}</p>
                                    </div>
                                    <div class="w-10 h-10 rounded-full border border-border flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all duration-500 group-hover:translate-x-0 translate-x-4">
                                        <ChevronRight class="w-5 h-5 text-muted-foreground" />
                                    </div>
                                </div>
                            </Link>
                        </div>
                    </div>

                    <!-- Activity Intelligence -->
                    <div class="bg-card rounded-3xl lg:rounded-[2.5rem] border border-border shadow-sm overflow-hidden">
                        <div class="p-6 lg:p-8 border-b border-border flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                            <div>
                                <h3 class="text-2xl font-black text-foreground tracking-tighter">Live Activity</h3>
                                <p class="text-sm text-muted-foreground font-medium">Real-time system event monitor</p>
                            </div>
                            <Button variant="outline" class="rounded-xl border-border px-4 font-bold text-muted-foreground hover:text-primary w-full sm:w-auto">
                                History
                            </Button>
                        </div>
                        <div class="p-4 space-y-2 max-h-[400px] overflow-y-auto custom-scrollbar">
                            <div 
                                v-for="(activity, idx) in recentActivities" 
                                :key="idx"
                                class="flex items-center gap-4 p-4 rounded-2xl hover:bg-muted transition-all group"
                            >
                                <div :class="[activity.color, 'w-12 h-12 rounded-xl flex items-center justify-center group-hover:scale-105 transition-transform']">
                                    <component :is="getIconComponent(activity.icon)" class="w-6 h-6" />
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center justify-between mb-1">
                                        <h4 class="text-sm font-black text-foreground truncate">{{ activity.title }}</h4>
                                        <span class="text-[10px] font-black uppercase text-muted-foreground/80 tracking-wider">{{ activity.time }}</span>
                                    </div>
                                    <p class="text-xs text-muted-foreground font-medium line-clamp-1">{{ activity.description }}</p>
                                </div>
                            </div>
                            <div v-if="!recentActivities.length" class="p-12 text-center">
                                <Activity class="w-12 h-12 text-muted-foreground/40 mx-auto mb-4" />
                                <p class="text-muted-foreground font-medium tracking-tight">System is idling quietly...</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Sidebar (4 cols) -->
                <div class="lg:col-span-4 space-y-8">
                    
                    <!-- Events Intelligence -->
                    <div class="bg-card rounded-3xl lg:rounded-[2.5rem] border border-border shadow-sm p-6 lg:p-8">
                        <div class="flex items-center justify-between mb-8">
                            <h3 class="text-2xl font-black text-foreground tracking-tighter">Schedule</h3>
                            <div class="w-10 h-10 rounded-xl bg-muted flex items-center justify-center text-primary cursor-pointer hover:bg-primary hover:text-white hover:scale-110 transition-all">
                                <Plus class="w-5 h-5" />
                            </div>
                        </div>
                        <div class="space-y-6">
                            <div v-for="event in upcomingEvents" :key="event.title" class="relative pl-6 border-l-2 border-dashed border-border">
                                <div class="absolute -left-[9px] top-0 w-4 h-4 rounded-full bg-card border-2 border-primary"></div>
                                <div class="mb-4">
                                    <div class="flex items-center justify-between mb-2">
                                        <span :class="['px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest', event.color]">
                                            {{ event.type }}
                                        </span>
                                        <span class="text-[10px] font-black text-muted-foreground/80">{{ event.date }}</span>
                                    </div>
                                    <h4 class="text-sm font-black text-foreground mb-2 leading-tight">{{ event.title }}</h4>
                                    <div class="flex items-center gap-2 text-muted-foreground/80">
                                        <Clock class="w-3 h-3" />
                                        <span class="text-[11px] font-bold">{{ event.time }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <Button variant="outline" class="w-full mt-6 rounded-2xl border-border font-black h-12 text-muted-foreground hover:text-primary">
                            Full Calendar
                        </Button>
                    </div>

                    <!-- System Pulse -->
                    <div class="bg-card rounded-3xl lg:rounded-[2.5rem] border border-border shadow-sm p-6 lg:p-8 relative overflow-hidden">
                        <div class="relative z-10">
                            <h3 class="text-xl font-black text-foreground tracking-tighter mb-6 flex items-center gap-2">
                                <Activity class="w-5 h-5 text-emerald-500" />
                                System Pulse
                            </h3>
                            <div class="space-y-4">
                                <div class="p-4 rounded-2xl bg-muted border border-border flex items-center justify-between group cursor-pointer hover:bg-card transition-all">
                                    <div class="flex items-center gap-3">
                                        <div class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse-glow"></div>
                                        <span class="text-sm font-bold text-foreground/80">Database Cluster</span>
                                    </div>
                                    <Badge class="bg-emerald-50 dark:bg-emerald-500/10 text-emerald-500 border-0 font-black">ACTIVE</Badge>
                                </div>
                                <div class="p-4 rounded-2xl bg-muted border border-border flex items-center justify-between group cursor-pointer hover:bg-card transition-all">
                                    <div class="flex items-center gap-3">
                                        <div class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse-glow"></div>
                                        <span class="text-sm font-bold text-foreground/80">Auth Engine</span>
                                    </div>
                                    <Badge class="bg-emerald-50 dark:bg-emerald-500/10 text-emerald-500 border-0 font-black">ACTIVE</Badge>
                                </div>
                                <div class="p-4 rounded-2xl bg-muted border border-border flex items-center justify-between group cursor-pointer hover:bg-card transition-all">
                                    <div class="flex items-center gap-3">
                                        <div class="w-2 h-2 rounded-full bg-amber-500"></div>
                                        <span class="text-sm font-bold text-foreground/80">Object Storage</span>
                                    </div>
                                    <Badge class="bg-amber-50 dark:bg-amber-500/10 text-amber-500 border-0 font-black">75% CAP</Badge>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Pro Support Card -->
                    <div class="rounded-3xl lg:rounded-[2.5rem] bg-primary p-6 lg:p-8 text-primary-foreground shadow-xl shadow-primary/20 relative overflow-hidden group">
                        <div class="relative z-10">
                            <h4 class="text-xl font-black mb-2 flex items-center gap-2">
                                <Sparkles class="w-5 h-5" />
                                Priority Concierge
                            </h4>
                            <p class="text-primary-foreground/80 text-sm font-medium mb-6 leading-relaxed">
                                Get instant expert assistance from our premium 24/7 dedicated support team.
                            </p>
                            <Button class="w-full bg-background text-primary hover:bg-background/90 font-black rounded-2xl h-11 shadow-md border-0">
                                Connect Now
                            </Button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </Sidebar>
</template>

<style scoped>
.custom-scrollbar::-webkit-scrollbar {
  width: 4px;
}
.custom-scrollbar::-webkit-scrollbar-track {
  background: transparent;
}
.custom-scrollbar::-webkit-scrollbar-thumb {
  background: rgba(99, 102, 241, 0.1);
  border-radius: 10px;
}
.custom-scrollbar::-webkit-scrollbar-thumb:hover {
  background: rgba(99, 102, 241, 0.2);
}
</style>