<script setup>
import { shallowRef } from 'vue';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';
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
    Activity,
    Award,
    CalendarDays,
    ChevronRight,
    Plus,
    BarChart3,
    HeartHandshake,
    Zap,
    Sparkles
} from 'lucide-vue-next';

defineProps({
    stats: Object,
    recentActivities: Array
});

// Cache current date to avoid recalculating on every render
const currentDate = new Date().toLocaleDateString('en-US', { weekday: 'long', month: 'long', day: 'numeric' });

// Use shallowRef for static data to avoid reactivity overhead
const quickActions = shallowRef([
    { title: 'Enroll Student', description: 'New admission registry', icon: Users, color: 'terracotta', href: route('students.create') },
    { title: 'Recruit Teacher', description: 'Faculty management', icon: UserCheck, color: 'forest', href: route('teachers.create') },
    { title: 'Fee Collection', description: 'Financial accounting', icon: DollarSign, color: 'amber', href: route('fees.index') },
    { title: 'Academic Exams', description: 'Grading & results', icon: FileText, color: 'terracotta', href: route('exams.index') }
]);

const upcomingEvents = shallowRef([
    { title: 'Parent-Teacher Summit', date: 'Feb 20, 2026', time: '2:00 PM', type: 'Conference', icon: HeartHandshake, color: 'terracotta' },
    { title: 'Mid-Term Examinations', date: 'Feb 25, 2026', time: '8:00 AM', type: 'Academic', icon: Award, color: 'forest' }
]);

const colorMap = {
    terracotta: 'bg-terracotta/10 dark:bg-terracotta/20 text-terracotta',
    forest: 'bg-forest/10 dark:bg-forest/20 text-forest dark:text-[#5AAA72]',
    amber: 'bg-amber/10 dark:bg-amber/20 text-amber',
    destructive: 'bg-destructive/10 dark:bg-destructive/20 text-destructive'
};

const getInitials = (name) => {
    if (!name) return 'AD';
    return name.split(' ').map(w => w[0]).join('').toUpperCase().slice(0, 2);
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
                <div class="p-2 bg-terracotta/10 rounded-lg">
                    <Home class="w-4 h-4 text-terracotta" />
                </div>
                <span class="font-black text-sm uppercase tracking-wider text-warm-muted dark:text-dark-muted">Overview</span>
            </div>
        </template>

        <div class="space-y-8">
            <!-- Dramatic Welcome Header -->
            <div class="relative overflow-hidden card-warm rounded-3xl lg:rounded-[2.5rem] p-6 lg:p-12">
                <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-8">
                    <div class="flex items-center gap-4 lg:gap-6">
                        <div class="relative">
                            <Avatar class="w-16 h-16 lg:w-24 lg:h-24 border-2 border-terracotta/20 rounded-2xl lg:rounded-3xl">
                                <AvatarImage 
                                    v-if="$page.props.auth.user.photo"
                                    :src="`/storage/${$page.props.auth.user.photo}`" 
                                    :alt="$page.props.auth.user.name"
                                    class="object-cover"
                                />
                                <AvatarFallback class="text-xl lg:text-3xl font-black bg-terracotta text-white rounded-2xl lg:rounded-3xl uppercase">
                                    {{ getInitials($page.props.auth.user.name) }}
                                </AvatarFallback>
                            </Avatar>
                        </div>
                        <div>
                            <h1 class="text-3xl lg:text-5xl font-black text-warm-text dark:text-dark-text mb-1 lg:mb-2 tracking-tighter leading-tight">
                                Welcome, {{ $page.props.auth.user.name.split(' ')[0] }}! 👋
                            </h1>
                            <p class="text-base lg:text-lg text-warm-muted dark:text-dark-muted font-medium">
                                The management engine is running at <span class="text-terracotta font-black">99.9% peak efficiency</span>.
                            </p>
                        </div>
                    </div>
                    
                    <div class="flex flex-col items-start md:items-end">
                        <div class="bg-white/50 dark:bg-dark-bg/50 px-4 lg:px-6 py-2 lg:py-3 rounded-2xl border border-terracotta/20 text-left md:text-right">
                            <p class="text-[10px] font-black uppercase tracking-widest text-terracotta mb-1">Current Session</p>
                            <p class="text-sm lg:text-base font-bold text-warm-text dark:text-dark-text">{{ currentDate }}</p>
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
                        <div class="card-warm p-5 lg:p-6 rounded-3xl lg:rounded-[2rem] hover:border-terracotta/30 transition-colors">
                            <div class="flex items-center justify-between mb-6">
                                <div class="w-14 h-14 rounded-2xl bg-terracotta/10 dark:bg-terracotta/20 flex items-center justify-center text-terracotta">
                                    <GraduationCap class="w-7 h-7" />
                                </div>
                                <Badge class="bg-terracotta/10 dark:bg-terracotta/20 text-terracotta border-0 font-black">
                                    <TrendingUp class="w-3 h-3 mr-1" />
                                    +12%
                                </Badge>
                            </div>
                            <p class="text-xs font-black uppercase tracking-widest text-warm-muted dark:text-dark-muted/80 mb-1">Total Students</p>
                            <h3 class="text-4xl font-black text-warm-text dark:text-dark-text tracking-tighter">{{ stats.students_count || 0 }}</h3>
                        </div>

                        <!-- Faculty -->
                        <div class="card-warm p-5 lg:p-6 rounded-3xl lg:rounded-[2rem] hover:border-forest/30 transition-colors">
                            <div class="flex items-center justify-between mb-6">
                                <div class="w-14 h-14 rounded-2xl bg-forest/10 dark:bg-forest/20 flex items-center justify-center text-forest dark:text-[#5AAA72]">
                                    <UserCheck class="w-7 h-7" />
                                </div>
                                <Badge class="bg-forest/10 dark:bg-forest/20 text-forest dark:text-[#5AAA72] border-0 font-black">
                                    <TrendingUp class="w-3 h-3 mr-1" />
                                    +5.2%
                                </Badge>
                            </div>
                            <p class="text-xs font-black uppercase tracking-widest text-warm-muted dark:text-dark-muted/80 mb-1">Expert Faculty</p>
                            <h3 class="text-4xl font-black text-warm-text dark:text-dark-text tracking-tighter">{{ stats.teachers_count || 0 }}</h3>
                        </div>

                        <!-- Finance (Revenue) -->
                        <div class="card-warm p-5 lg:p-6 rounded-3xl lg:rounded-[2rem] hover:border-amber/30 transition-colors">
                            <div class="flex items-center justify-between mb-6">
                                <div class="w-14 h-14 rounded-2xl bg-amber/10 dark:bg-amber/20 flex items-center justify-center text-amber">
                                    <DollarSign class="w-7 h-7" />
                                </div>
                                <Badge class="bg-amber/10 dark:bg-amber/20 text-amber border-0 font-black">
                                    <TrendingUp class="w-3 h-3 mr-1" />
                                    +8.3%
                                </Badge>
                            </div>
                            <p class="text-xs font-black uppercase tracking-widest text-warm-muted dark:text-dark-muted/80 mb-1">Revenue Flow</p>
                            <h3 class="text-4xl font-black text-warm-text dark:text-dark-text tracking-tighter">KES 12.5M</h3>
                        </div>
                    </div>

                    <!-- Quick Command Center -->
                    <div class="space-y-4">
                        <div class="flex items-center justify-between px-2">
                            <h2 class="text-xl font-black text-warm-text dark:text-dark-text tracking-tight uppercase px-2 py-1 bg-terracotta/5 rounded-lg inline-block">Command Center</h2>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <Link
                                v-for="action in quickActions"
                                :key="action.title"
                                :href="action.href"
                                class="card-warm p-4 rounded-3xl hover:border-terracotta/30"
                            >
                                <div class="flex items-center gap-5">
                                    <div :class="['w-16 h-16 rounded-2xl flex items-center justify-center', colorMap[action.color]]">
                                        <component :is="action.icon" class="w-8 h-8" />
                                    </div>
                                    <div class="flex-1">
                                        <h4 class="text-lg font-black text-warm-text dark:text-dark-text tracking-tight">{{ action.title }}</h4>
                                        <p class="text-sm text-warm-muted dark:text-dark-muted font-medium">{{ action.description }}</p>
                                    </div>
                                    <div class="w-10 h-10 rounded-full border border-terracotta/20 flex items-center justify-center">
                                        <ChevronRight class="w-5 h-5 text-warm-muted dark:text-dark-muted" />
                                    </div>
                                </div>
                            </Link>
                        </div>
                    </div>

                    <!-- Activity Intelligence -->
                    <div class="card-warm rounded-3xl lg:rounded-[2.5rem] overflow-hidden">
                        <div class="p-6 lg:p-8 border-b border-terracotta/20 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                            <div>
                                <h3 class="text-2xl font-black text-warm-text dark:text-dark-text tracking-tighter">Live Activity</h3>
                                <p class="text-sm text-warm-muted dark:text-dark-muted font-medium">Real-time system event monitor</p>
                            </div>
                            <Button variant="outline" class="rounded-xl border-terracotta/20 px-4 font-bold text-warm-muted dark:text-dark-muted hover:text-terracotta w-full sm:w-auto">
                                History
                            </Button>
                        </div>
                        <div class="p-4 space-y-2 max-h-[400px] overflow-y-auto custom-scrollbar">
                            <div
                                v-for="(activity, idx) in recentActivities"
                                :key="idx"
                                class="flex items-center gap-4 p-4 rounded-2xl hover:bg-terracotta/5 transition-colors"
                            >
                                <div :class="[colorMap[activity.color] || colorMap.terracotta, 'w-12 h-12 rounded-xl flex items-center justify-center']">
                                    <component :is="getIconComponent(activity.icon)" class="w-6 h-6" />
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center justify-between mb-1">
                                        <h4 class="text-sm font-black text-warm-text dark:text-dark-text truncate">{{ activity.title }}</h4>
                                        <span class="text-[10px] font-black uppercase text-warm-muted dark:text-dark-muted/80 tracking-wider">{{ activity.time }}</span>
                                    </div>
                                    <p class="text-xs text-warm-muted dark:text-dark-muted font-medium line-clamp-1">{{ activity.description }}</p>
                                </div>
                            </div>
                            <div v-if="!recentActivities.length" class="p-12 text-center">
                                <Activity class="w-12 h-12 text-warm-muted/40 dark:text-dark-muted/40 mx-auto mb-4" />
                                <p class="text-warm-muted dark:text-dark-muted font-medium tracking-tight">System is idling quietly...</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Sidebar (4 cols) -->
                <div class="lg:col-span-4 space-y-8">
                    
                    <!-- Events Intelligence -->
                    <div class="card-warm rounded-3xl lg:rounded-[2.5rem] p-6 lg:p-8">
                        <div class="flex items-center justify-between mb-8">
                            <h3 class="text-2xl font-black text-warm-text dark:text-dark-text tracking-tighter">Schedule</h3>
                            <div class="w-10 h-10 rounded-xl bg-terracotta/5 flex items-center justify-center text-terracotta cursor-pointer hover:bg-terracotta hover:text-white transition-colors">
                                <Plus class="w-5 h-5" />
                            </div>
                        </div>
                        <div class="space-y-6">
                            <div v-for="event in upcomingEvents" :key="event.title" class="relative pl-6 border-l-2 border-dashed border-terracotta/20">
                                <div class="absolute -left-[9px] top-0 w-4 h-4 rounded-full bg-warm-bg dark:bg-dark-bg border-2 border-terracotta"></div>
                                <div class="mb-4">
                                    <div class="flex items-center justify-between mb-2">
                                        <span :class="['px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest', colorMap[event.color]]">
                                            {{ event.type }}
                                        </span>
                                        <span class="text-[10px] font-black text-warm-muted dark:text-dark-muted/80">{{ event.date }}</span>
                                    </div>
                                    <h4 class="text-sm font-black text-warm-text dark:text-dark-text mb-2 leading-tight">{{ event.title }}</h4>
                                    <div class="flex items-center gap-2 text-warm-muted dark:text-dark-muted/80">
                                        <Clock class="w-3 h-3" />
                                        <span class="text-[11px] font-bold">{{ event.time }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <Button variant="outline" class="w-full mt-6 rounded-2xl border-terracotta/20 font-black h-12 text-warm-muted dark:text-dark-muted hover:text-terracotta">
                            Full Calendar
                        </Button>
                    </div>

                    <!-- System Pulse -->
                    <div class="card-warm rounded-3xl lg:rounded-[2.5rem] p-6 lg:p-8 relative overflow-hidden">
                        <div class="relative z-10">
                            <h3 class="text-xl font-black text-warm-text dark:text-dark-text tracking-tighter mb-6 flex items-center gap-2">
                                <Activity class="w-5 h-5 text-terracotta" />
                                System Pulse
                            </h3>
                            <div class="space-y-4">
                                <div class="p-4 rounded-2xl bg-terracotta/5 border border-terracotta/20 flex items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        <div class="w-2 h-2 rounded-full bg-forest"></div>
                                        <span class="text-sm font-bold text-warm-text dark:text-dark-text/80">Database Cluster</span>
                                    </div>
                                    <Badge class="bg-forest/10 dark:bg-forest/20 text-forest dark:text-[#5AAA72] border-0 font-black">ACTIVE</Badge>
                                </div>
                                <div class="p-4 rounded-2xl bg-terracotta/5 border border-terracotta/20 flex items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        <div class="w-2 h-2 rounded-full bg-forest"></div>
                                        <span class="text-sm font-bold text-warm-text dark:text-dark-text/80">Auth Engine</span>
                                    </div>
                                    <Badge class="bg-forest/10 dark:bg-forest/20 text-forest dark:text-[#5AAA72] border-0 font-black">ACTIVE</Badge>
                                </div>
                                <div class="p-4 rounded-2xl bg-terracotta/5 border border-terracotta/20 flex items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        <div class="w-2 h-2 rounded-full bg-amber"></div>
                                        <span class="text-sm font-bold text-warm-text dark:text-dark-text/80">API Gateway</span>
                                    </div>
                                    <Badge class="bg-amber/10 dark:bg-amber/20 text-amber border-0 font-black">BUSY</Badge>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Pro Support Card -->
                    <div class="rounded-3xl lg:rounded-[2.5rem] accent-terracotta p-6 lg:p-8 text-white shadow-xl shadow-terracotta/30 relative overflow-hidden">
                        <div class="relative z-10">
                            <h4 class="text-xl font-black mb-2 flex items-center gap-2">
                                <Sparkles class="w-5 h-5" />
                                Priority Concierge
                            </h4>
                            <p class="text-white/80 text-sm font-medium mb-6 leading-relaxed">
                                Get instant expert assistance from our premium 24/7 dedicated support team.
                            </p>
                            <Button class="w-full bg-white text-terracotta hover:bg-white/90 font-black rounded-2xl h-11 shadow-md border-0">
                                Connect Now
                            </Button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </Sidebar>
</template>