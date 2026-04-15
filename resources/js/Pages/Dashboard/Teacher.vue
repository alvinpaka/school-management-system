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
    MessageSquare,
    MoreVertical
} from 'lucide-vue-next';

defineProps({
    teacher: Object,
    stats: Object
});

const quickActions = [
    {
        title: 'Take Attendance',
        description: 'Class presence registry',
        icon: ClipboardList,
        gradient: 'from-emerald-500 to-teal-600',
        glow: 'shadow-emerald-500/20',
        href: route('attendance.index')
    },
    {
        title: 'Grade Exams',
        description: 'Performance assessment',
        icon: FileText,
        gradient: 'from-blue-500 to-indigo-600',
        glow: 'shadow-blue-500/20',
        href: route('exams.index')
    },
    {
        title: 'Assignments',
        description: 'Knowledge milestones',
        icon: Award,
        gradient: 'from-orange-500 to-amber-600',
        glow: 'shadow-orange-500/20',
        href: '#'
    },
    {
        title: 'My Schedule',
        description: 'Academic timeline',
        icon: Calendar,
        gradient: 'from-violet-500 to-purple-600',
        glow: 'shadow-violet-500/20',
        href: '#'
    }
];

const recentActivities = [
    {
        title: 'Attendance Marked',
        description: 'Class 10A - Mathematics',
        time: '2 hours ago',
        icon: ClipboardList,
        color: 'bg-forest/10 text-forest'
    },
    {
        title: 'Exam Graded',
        description: 'Midterm exams - 45 students',
        time: '5 hours ago',
        icon: FileText,
        color: 'bg-terracotta/10 text-terracotta'
    }
];

const upcomingClasses = [
    {
        subject: 'Mathematics',
        class: 'Class 10A',
        time: '08:00 AM - 09:00 AM',
        room: 'Room 201',
        students: 45,
        status: 'upcoming'
    },
    {
        subject: 'Physics',
        class: 'Class 10B',
        time: '09:30 AM - 10:30 AM',
        room: 'Room 205',
        students: 42,
        status: 'upcoming'
    }
];

const pendingTasks = [
    { title: 'Grade Math Assignments', count: 15, priority: 'high', color: 'bg-destructive/10 text-destructive border-destructive/20' },
    { title: 'Review Lab Reports', count: 8, priority: 'medium', color: 'bg-amber/10 text-amber border-amber/20' }
];

const getInitials = (name) => {
    return name?.split(' ').map(word => word[0]).join('').toUpperCase().slice(0, 2) || 'TR';
};
</script>

<template>
    <Head title="Teacher Dashboard | EduManage Pro" />

    <Sidebar>
        <template #header-title>
            <div class="flex items-center gap-2">
                <div class="p-2 bg-terracotta/10 rounded-lg">
                    <GraduationCap class="w-4 h-4 text-terracotta" />
                </div>
                <span class="font-black text-sm uppercase tracking-wider text-warm-muted text-dark-muted/80">Academic Hub</span>
            </div>
        </template>

        <div class="space-y-8 animate-fade-in-up">
            <!-- Dramatic Welcome Header -->
            <div class="relative overflow-hidden card-warm rounded-3xl lg:rounded-[2.5rem] p-6 lg:p-12">
                <div class="absolute -top-24 -right-24 w-64 h-64 bg-terracotta/5 blur-[80px] rounded-full animate-float"></div>
                <div class="absolute -bottom-24 -left-24 w-64 h-64 bg-forest/5 blur-[80px] rounded-full animate-float" style="animation-delay: 2s"></div>
                
                <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6 lg:gap-8">
                    <div class="flex items-center gap-4 lg:gap-6">
                        <div class="relative group">
                            <Avatar class="w-20 h-20 lg:w-24 lg:h-24 border-2 border-terracotta/20 rounded-3xl relative">
                                <AvatarImage 
                                    v-if="$page.props.auth.user.photo"
                                    :src="`/storage/${$page.props.auth.user.photo}`" 
                                    :alt="$page.props.auth.user.name"
                                    class="object-cover"
                                />
                                <AvatarFallback class="text-2xl lg:text-3xl font-black bg-terracotta text-white rounded-3xl uppercase">
                                    {{ getInitials($page.props.auth.user.name) }}
                                </AvatarFallback>
                            </Avatar>
                        </div>
                        <div>
                            <h1 class="text-3xl lg:text-5xl font-black text-warm-text text-dark-text mb-2 tracking-tighter">
                                Morning, Professor {{ $page.props.auth.user.name.split(' ')[0] }}! <span class="animate-pulse">✨</span>
                            </h1>
                            <p class="text-base lg:text-lg text-warm-muted text-dark-muted font-medium">
                                Your next lecture on <span class="text-terracotta font-black">Mathematics</span> begins in 15 minutes.
                            </p>
                        </div>
                    </div>
                    
                    <div class="flex flex-col items-start md:items-end">
                        <div class="bg-white/50 bg-dark-bg/50 px-4 lg:px-6 py-2 lg:py-3 rounded-2xl border border-terracotta/20 text-left md:text-right">
                            <p class="text-[10px] font-black uppercase tracking-widest text-terracotta mb-1">Academic Calendar</p>
                            <p class="font-bold text-warm-text text-dark-text">
                                {{ new Date().toLocaleDateString('en-US', { weekday: 'long', month: 'long', day: 'numeric' }) }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stats & Quick Actions -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                
                <!-- Main Content (8 cols) -->
                <div class="lg:col-span-8 space-y-8">
                    
                    <!-- Premium Stats Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 lg:gap-6">
                        <div class="card-warm p-6 rounded-3xl lg:rounded-[2rem] group cursor-pointer hover:border-terracotta/30 transition-all duration-300">
                            <div class="flex items-center justify-between mb-6">
                                <div class="w-14 h-14 rounded-2xl bg-terracotta/10 flex items-center justify-center text-terracotta">
                                    <BookOpen class="w-7 h-7" />
                                </div>
                                <Badge class="bg-terracotta/10 text-terracotta border-0 font-black">ACTIVE</Badge>
                            </div>
                            <p class="text-xs font-black uppercase tracking-widest text-warm-muted text-dark-muted/80 mb-1">My Classes</p>
                            <h3 class="text-4xl font-black text-warm-text text-dark-text tracking-tighter">{{ stats?.classes || 8 }}</h3>
                        </div>

                        <div class="card-warm p-6 rounded-3xl lg:rounded-[2rem] group cursor-pointer hover:border-terracotta/30 transition-all duration-300">
                            <div class="flex items-center justify-between mb-6">
                                <div class="w-14 h-14 rounded-2xl bg-terracotta/10 flex items-center justify-center text-terracotta">
                                    <Users class="w-7 h-7" />
                                </div>
                                <Badge class="bg-terracotta/10 text-terracotta border-0 font-black">TOTAL</Badge>
                            </div>
                            <p class="text-xs font-black uppercase tracking-widest text-warm-muted text-dark-muted/80 mb-1">Students</p>
                            <h3 class="text-4xl font-black text-warm-text text-dark-text tracking-tighter">{{ stats?.students || 156 }}</h3>
                        </div>

                        <div class="card-warm p-6 rounded-3xl lg:rounded-[2rem] group cursor-pointer hover:border-amber/30 transition-all duration-300">
                            <div class="flex items-center justify-between mb-6">
                                <div class="w-14 h-14 rounded-2xl bg-amber/10 flex items-center justify-center text-amber">
                                    <Target class="w-7 h-7" />
                                </div>
                                <Badge class="bg-amber/10 text-amber border-0 font-black">26 PENDING</Badge>
                            </div>
                            <p class="text-xs font-black uppercase tracking-widest text-warm-muted text-dark-muted/80 mb-1">Tasks</p>
                            <h3 class="text-4xl font-black text-warm-text text-dark-text tracking-tighter">15</h3>
                        </div>
                    </div>

                    <!-- Today's Schedule -->
                    <div class="space-y-4">
                        <div class="flex items-center justify-between px-2">
                            <h2 class="text-xl font-black text-terracotta tracking-tight uppercase px-4 py-1 bg-terracotta/10 rounded-lg inline-block">Lecture Schedule</h2>
                            <Button variant="ghost" class="font-bold text-terracotta hover:bg-terracotta/10 rounded-xl">Full Timetable</Button>
                        </div>
                        <div class="space-y-4">
                            <div 
                                v-for="classItem in upcomingClasses" 
                                :key="classItem.subject"
                                class="card-warm p-5 lg:p-6 rounded-3xl group transition-all duration-500"
                            >
                                <div class="flex flex-col md:flex-row md:items-center gap-4 lg:gap-6">
                                    <div class="flex-shrink-0 w-24 md:text-center flex md:block items-center gap-2">
                                        <div class="text-2xl lg:text-3xl font-black text-warm-text text-dark-text">{{ classItem.time.split(':')[0] }}</div>
                                        <div class="text-[10px] font-black uppercase text-warm-muted text-dark-muted/80 tracking-widest">{{ classItem.time.split(' ')[1] }}</div>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex flex-wrap items-center gap-3 mb-2">
                                            <h4 class="text-lg lg:text-xl font-black text-warm-text text-dark-text tracking-tight">{{ classItem.subject }}</h4>
                                            <Badge class="bg-terracotta/10 text-terracotta border-0 font-black">{{ classItem.class }}</Badge>
                                        </div>
                                        <div class="flex flex-wrap items-center gap-4 text-xs lg:text-sm text-warm-muted text-dark-muted font-medium">
                                            <div class="flex items-center gap-1.5">
                                                <Home class="w-4 h-4 text-terracotta" />
                                                {{ classItem.room }}
                                            </div>
                                            <div class="flex items-center gap-1.5">
                                                <Users class="w-4 h-4 text-terracotta" />
                                                {{ classItem.students }} Students Registered
                                            </div>
                                        </div>
                                    </div>
                                    <Button class="bg-terracotta hover:bg-terracotta/90 text-white font-black rounded-2xl h-11 lg:h-12 px-6 lg:px-8 shadow-lg shadow-terracotta/20 lg:group-hover:scale-105 transition-all w-full md:w-auto">
                                        Initialize Class
                                    </Button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Command Tools -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <Link 
                            v-for="action in quickActions" 
                            :key="action.title" 
                            :href="action.href"
                            class="card-warm p-4 rounded-3xl group transition-all duration-500 overflow-hidden relative"
                        >
                            <div class="flex items-center gap-5 relative z-10">
                                <div class="w-16 h-16 rounded-2xl bg-terracotta/10 flex items-center justify-center text-terracotta transition-transform duration-500 group-hover:rotate-6 group-hover:scale-110">
                                    <component :is="action.icon" class="w-8 h-8" />
                                </div>
                                <div class="flex-1">
                                    <h4 class="text-lg font-black text-warm-text text-dark-text tracking-tight">{{ action.title }}</h4>
                                    <p class="text-sm text-warm-muted text-dark-muted font-medium">{{ action.description }}</p>
                                </div>
                            </div>
                        </Link>
                    </div>
                </div>

                <!-- Right Sidebar (4 cols) -->
                <div class="lg:col-span-4 space-y-6 lg:space-y-8">
                    
                    <!-- Pending Intelligence -->
                    <div class="card-warm rounded-3xl lg:rounded-[2.5rem] p-6 lg:p-8">
                        <div class="flex items-center justify-between mb-8">
                            <h3 class="text-2xl font-black text-warm-text text-dark-text tracking-tighter">Academic Tasks</h3>
                            <div class="w-10 h-10 rounded-xl bg-terracotta/5 flex items-center justify-center text-terracotta">
                                <Target class="w-5 h-5" />
                            </div>
                        </div>
                        <div class="space-y-4">
                            <div v-for="task in pendingTasks" :key="task.title" :class="['p-4 rounded-2xl border transition-all lg:hover:scale-[1.02]', task.color]">
                                <div class="flex items-center justify-between mb-2">
                                    <h4 class="text-sm font-black tracking-tight text-warm-text text-dark-text">{{ task.title }}</h4>
                                    <Badge class="bg-white/50 bg-dark-bg/50 border-0 font-black text-warm-text text-dark-text">{{ task.count }}</Badge>
                                </div>
                                <div class="text-[10px] font-black uppercase tracking-widest opacity-60">{{ task.priority }} PRIORITY</div>
                            </div>
                        </div>
                        <Button variant="ghost" class="w-full mt-6 rounded-2xl border-dashed border-terracotta/20 text-warm-muted text-dark-muted font-bold h-12 hover:text-terracotta">
                            <Plus class="w-4 h-4 mr-2" />
                            New Task
                        </Button>
                    </div>

                    <!-- Insights & Performance -->
                    <div class="card-warm rounded-3xl lg:rounded-[2.5rem] p-6 lg:p-8">
                        <h3 class="text-xl font-black text-warm-text text-dark-text tracking-tighter mb-6 flex items-center gap-2">
                            <BarChart3 class="w-5 h-5 text-terracotta" />
                            Performance Insights
                        </h3>
                        <div class="space-y-6">
                            <div>
                                <div class="flex justify-between mb-2">
                                    <span class="text-xs font-black text-warm-muted text-dark-muted/80 uppercase">Attendance Analytics</span>
                                    <span class="text-xs font-black text-forest">95.5%</span>
                                </div>
                                <div class="h-2 bg-terracotta/10 rounded-full overflow-hidden">
                                    <div class="h-full bg-forest rounded-full" style="width: 95.5%"></div>
                                </div>
                            </div>
                            <div>
                                <div class="flex justify-between mb-2">
                                    <span class="text-xs font-black text-warm-muted text-dark-muted/80 uppercase">Avg. Academic Score</span>
                                    <span class="text-xs font-black text-terracotta">B+ (84%)</span>
                                </div>
                                <div class="h-2 bg-terracotta/10 rounded-full overflow-hidden">
                                    <div class="h-full bg-terracotta rounded-full" style="width: 84%"></div>
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-4 mt-8">
                                <div class="p-4 bg-terracotta/5 rounded-2xl text-center border border-terracotta/20">
                                    <p class="text-[10px] font-black uppercase text-terracotta mb-1">Peer Rating</p>
                                    <p class="text-xl font-black text-warm-text text-dark-text">4.8/5.0</p>
                                </div>
                                <div class="p-4 bg-forest/5 rounded-2xl text-center border border-forest/20">
                                    <p class="text-[10px] font-black uppercase text-forest mb-1">Efficiency</p>
                                    <p class="text-xl font-black text-warm-text text-dark-text">92%</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Communication Channel -->
                    <div class="rounded-3xl lg:rounded-[2.5rem] accent-terracotta p-8 text-white shadow-xl shadow-terracotta/30 relative overflow-hidden group">
                        <div class="absolute -bottom-10 -right-10 w-40 h-40 bg-white/10 dark:bg-white/5 blur-[40px] rounded-full lg:group-hover:scale-150 transition-transform"></div>
                        <div class="relative z-10">
                            <h4 class="text-xl font-black mb-2 flex items-center gap-2">
                                <MessageSquare class="w-5 h-5 text-white/80" />
                                Student Portal
                            </h4>
                            <p class="text-white/80 text-sm font-medium mb-6 leading-relaxed">
                                Broadcast announcements or start direct mentorship sessions with your students.
                            </p>
                            <Button class="w-full bg-white text-terracotta hover:bg-white/90 font-black rounded-2xl h-11 border-0">
                                Launch Messaging
                            </Button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </Sidebar>
</template>