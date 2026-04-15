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
    TrendingUpIcon,
    MoreVertical,
    PencilLine,
    Flag
} from 'lucide-vue-next';

defineProps({
    student: Object,
    stats: Object
});

const quickActions = [
    {
        title: 'Academic Results',
        description: 'Check performance',
        icon: FileText,
        gradient: 'from-blue-500 to-indigo-600',
        glow: 'shadow-blue-500/20',
        href: route('exams.index')
    },
    {
        title: 'Class Schedule',
        description: 'Daily timetable',
        icon: Calendar,
        gradient: 'from-emerald-500 to-teal-600',
        glow: 'shadow-emerald-500/20',
        href: route('timetable.index')
    },
    {
        title: 'Fee Status',
        description: 'Payment records',
        icon: DollarSign,
        gradient: 'from-orange-500 to-amber-600',
        glow: 'shadow-orange-500/20',
        href: route('fees.index')
    },
    {
        title: 'Digital Library',
        description: 'Study resources',
        icon: Library,
        gradient: 'from-violet-500 to-purple-600',
        glow: 'shadow-violet-500/20',
        href: '#'
    }
];

const recentActivities = [
    {
        title: 'Assignment Submitted',
        description: 'Mathematics homework submitted on time',
        time: '2 hours ago',
        icon: Award,
        color: 'bg-forest/10 text-forest'
    },
    {
        title: 'Exam Result Published',
        description: 'Science midterm results now available',
        time: '1 day ago',
        icon: FileText,
        color: 'bg-terracotta/10 text-terracotta'
    }
];

const upcomingEvents = [
    { title: 'Mathematics Midterm', date: 'Feb 15, 2026', time: '9:00 AM', type: 'Examination', icon: PencilLine, color: 'bg-destructive/10 text-destructive' },
    { title: 'Science Project Due', date: 'Feb 16, 2026', time: '11:59 PM', type: 'Deadline', icon: Flag, color: 'bg-amber/10 text-amber' }
];

const recentGrades = [
    { subject: 'Advanced Mathematics', score: 85, grade: 'A', maxScore: 100, trend: 'up' },
    { subject: 'Physical Science', score: 78, grade: 'B+', maxScore: 100, trend: 'stable' },
    { subject: 'English Literature', score: 92, grade: 'A+', maxScore: 100, trend: 'up' }
];

const getInitials = (name) => {
    return name?.split(' ').map(word => word[0]).join('').toUpperCase().slice(0, 2) || 'ST';
};

const getGradeColor = (grade) => {
    if (grade.startsWith('A')) return 'text-forest';
    if (grade.startsWith('B')) return 'text-terracotta';
    return 'text-amber';
};
</script>

<template>
    <Head title="Student Dashboard | EduManage Pro" />

    <Sidebar>
        <template #header-title>
            <div class="flex items-center gap-2">
                <div class="p-2 bg-terracotta/10 rounded-lg">
                    <GraduationCap class="w-4 h-4 text-terracotta" />
                </div>
                <span class="font-black text-sm uppercase tracking-wider text-warm-muted text-dark-muted/80">Student Portal</span>
            </div>
        </template>

        <div class="space-y-8 animate-fade-in-up">
            <!-- Dramatic Welcome Header -->
            <div class="relative overflow-hidden card-warm rounded-3xl lg:rounded-[2.5rem] p-6 lg:p-12">
                <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-8">
                    <div class="flex flex-col md:flex-row items-center gap-6 lg:gap-8">
                        <div class="relative group">
                            <Avatar class="w-20 h-20 lg:w-24 lg:h-24 border-2 border-terracotta/20 rounded-2xl lg:rounded-3xl relative">
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
                        <div class="text-center md:text-left">
                            <div class="flex flex-wrap items-center justify-center md:justify-start gap-2 mb-2">
                                <Badge class="bg-terracotta/10 text-terracotta border-0 font-black">{{ student?.academic_class?.name || 'Class 10A' }}</Badge>
                                <Badge variant="outline" class="font-bold border-terracotta/20 text-warm-muted text-dark-muted">Roll: {{ student?.roll_number || '042' }}</Badge>
                            </div>
                            <h1 class="text-3xl lg:text-5xl font-black text-warm-text text-dark-text mb-1 lg:mb-2 tracking-tighter leading-tight">
                                Keep shining, {{ $page.props.auth.user.name.split(' ')[0] }}! <span class="animate-pulse">🚀</span>
                            </h1>
                            <p class="text-base lg:text-lg text-warm-muted text-dark-muted font-medium">
                                You have <span class="text-forest font-black">3 assignments</span> due this week.
                            </p>
                        </div>
                    </div>
                
                    <div class="flex flex-col items-start md:items-end mt-6 md:mt-0">
                        <div class="bg-white/50 bg-dark-bg/50 px-4 lg:px-6 py-2 lg:py-3 rounded-2xl border border-terracotta/20 text-left md:text-right">
                            <p class="text-[10px] font-black uppercase tracking-widest text-terracotta mb-1">Academic Progress</p>
                            <p class="text-sm lg:text-base font-bold text-warm-text text-dark-text">
                                {{ new Date().toLocaleDateString('en-US', { weekday: 'long', month: 'long', day: 'numeric' }) }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stats & Analytics Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                
                <!-- Main Content (8 cols) -->
                <div class="lg:col-span-8 space-y-8">
                    
                    <!-- Premium Stats Grid -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 lg:gap-6">
                        <div class="card-warm p-5 lg:p-6 rounded-3xl lg:rounded-[2rem] group cursor-pointer lg:hover:border-terracotta/30 transition-all duration-300">
                            <div class="flex items-center justify-between mb-6">
                                <div class="w-14 h-14 rounded-2xl bg-terracotta/10 flex items-center justify-center text-terracotta">
                                    <Trophy class="w-7 h-7" />
                                </div>
                                <Badge class="bg-terracotta/10 text-terracotta border-0 font-black">TOP 5%</Badge>
                            </div>
                            <p class="text-xs font-black uppercase tracking-widest text-warm-muted text-dark-muted/80 mb-1">Academic GPA</p>
                            <h3 class="text-4xl font-black text-warm-text text-dark-text tracking-tighter">3.82</h3>
                        </div>

                        <div class="card-warm p-5 lg:p-6 rounded-3xl lg:rounded-[2rem] group cursor-pointer lg:hover:border-forest/30 transition-all duration-300">
                            <div class="flex items-center justify-between mb-6">
                                <div class="w-14 h-14 rounded-2xl bg-forest/10 flex items-center justify-center text-forest">
                                    <CheckCircle class="w-7 h-7" />
                                </div>
                                <Badge class="bg-forest/10 text-forest border-0 font-black">EXCELLENT</Badge>
                            </div>
                            <p class="text-xs font-black uppercase tracking-widest text-warm-muted text-dark-muted/80 mb-1">Attendance</p>
                            <h3 class="text-4xl font-black text-warm-text text-dark-text tracking-tighter">98.2%</h3>
                        </div>

                        <div class="card-warm p-5 lg:p-6 rounded-3xl lg:rounded-[2rem] group cursor-pointer lg:hover:border-amber/30 transition-all duration-300">
                            <div class="flex items-center justify-between mb-6">
                                <div class="w-14 h-14 rounded-2xl bg-amber/10 flex items-center justify-center text-amber">
                                    <Award class="w-7 h-7" />
                                </div>
                                <Badge class="bg-amber/10 text-amber border-0 font-black">3 PENDING</Badge>
                            </div>
                            <p class="text-xs font-black uppercase tracking-widest text-warm-muted text-dark-muted/80 mb-1">Assignments</p>
                            <h3 class="text-4xl font-black text-warm-text text-dark-text tracking-tighter">12</h3>
                        </div>
                    </div>

                    <!-- Grade Intelligence -->
                    <div class="card-warm rounded-3xl lg:rounded-[2.5rem] overflow-hidden">
                        <div class="p-6 lg:p-8 border-b border-terracotta/20 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                            <div>
                                <h3 class="text-2xl font-black text-warm-text text-dark-text tracking-tighter">Recent Performance</h3>
                                <p class="text-sm text-warm-muted text-dark-muted font-medium">Your latest examination metrics</p>
                            </div>
                            <Button variant="outline" class="rounded-xl border-terracotta/20 px-4 font-bold text-warm-muted text-dark-muted lg:hover:text-terracotta w-full sm:w-auto">
                                All Reports
                            </Button>
                        </div>
                        <div class="p-4 lg:p-6 space-y-4">
                            <div 
                                v-for="grade in recentGrades" 
                                :key="grade.subject"
                                class="flex flex-col sm:flex-row sm:items-center gap-4 lg:gap-6 p-4 rounded-3xl lg:hover:bg-terracotta/5 transition-all group"
                            >
                                <div class="w-14 h-14 lg:w-16 lg:h-16 rounded-2xl bg-terracotta/5 flex items-center justify-center text-terracotta lg:group-hover:bg-terracotta lg:group-hover:text-white transition-colors">
                                    <BookOpen class="w-7 h-7 lg:w-8 lg:h-8" />
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center justify-between mb-2">
                                        <h4 class="text-lg font-black text-warm-text text-dark-text truncate tracking-tight">{{ grade.subject }}</h4>
                                        <span :class="['text-2xl font-black italic tracking-tighter', getGradeColor(grade.grade)]">{{ grade.grade }}</span>
                                    </div>
                                    <div class="w-full h-2 bg-terracotta/10 rounded-full overflow-hidden">
                                        <div 
                                            class="h-full bg-terracotta transition-all duration-1000" 
                                            :style="{ width: `${grade.score}%` }"
                                        ></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Command Tools -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <Link 
                            v-for="action in quickActions" 
                            :key="action.title" 
                            :href="action.href"
                            class="card-warm p-4 rounded-3xl group transition-all duration-500"
                        >
                            <div class="flex items-center gap-5 relative z-10">
                                <div class="w-14 h-14 lg:w-16 lg:h-16 rounded-2xl bg-terracotta/5 flex items-center justify-center text-terracotta transition-all duration-500 lg:group-hover:bg-terracotta lg:group-hover:text-white lg:group-hover:shadow-lg lg:group-hover:shadow-terracotta/20 lg:group-hover:scale-105">
                                    <component :is="action.icon" class="w-7 h-7 lg:w-8 lg:h-8" />
                                </div>
                                <div class="flex-1">
                                    <h4 class="text-base lg:text-lg font-black text-warm-text text-dark-text tracking-tight">{{ action.title }}</h4>
                                    <p class="text-xs lg:text-sm text-warm-muted text-dark-muted font-medium">{{ action.description }}</p>
                                </div>
                            </div>
                        </Link>
                    </div>
                </div>

                <!-- Right Sidebar (4 cols) -->
                <div class="lg:col-span-4 space-y-6 lg:space-y-8">
                    
                    <!-- Events Intelligence -->
                    <div class="card-warm rounded-3xl lg:rounded-[2.5rem] p-6 lg:p-8">
                        <div class="flex items-center justify-between mb-8">
                            <h3 class="text-2xl font-black text-warm-text text-dark-text tracking-tighter">Timeline</h3>
                            <div class="w-10 h-10 rounded-xl bg-terracotta/5 flex items-center justify-center text-terracotta">
                                <CalendarDays class="w-5 h-5" />
                            </div>
                        </div>
                        <div class="space-y-6">
                            <div v-for="event in upcomingEvents" :key="event.title" class="relative pl-6 border-l-2 border-dashed border-terracotta/20">
                                <div class="absolute -left-[9px] top-0 w-4 h-4 rounded-full bg-warm-bg bg-dark-bg border-2 border-terracotta"></div>
                                <div class="mb-4">
                                    <div class="flex items-center justify-between mb-2">
                                        <span :class="['px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest', event.color]">
                                            {{ event.type }}
                                        </span>
                                        <span class="text-[10px] font-black text-warm-muted text-dark-muted/80">{{ event.date }}</span>
                                    </div>
                                    <h4 class="text-sm font-black text-warm-text text-dark-text mb-2 leading-tight">{{ event.title }}</h4>
                                </div>
                            </div>
                        </div>
                        <Button variant="outline" class="w-full mt-6 rounded-2xl border-terracotta/20 font-black h-12 text-warm-muted text-dark-muted hover:text-terracotta">
                            Full Calendar
                        </Button>
                    </div>

                    <!-- Live Stream / Study Hub -->
                    <div class="rounded-3xl lg:rounded-[2.5rem] accent-terracotta p-6 lg:p-8 text-white shadow-xl shadow-terracotta/30 text-center relative overflow-hidden group">
                        <div class="relative z-10">
                            <div class="w-16 h-16 lg:w-20 lg:h-20 bg-white/10 dark:bg-white/5 rounded-2xl lg:rounded-3xl flex items-center justify-center mx-auto mb-6 border border-white/20 dark:border-white/10 lg:group-hover:scale-110 transition-transform">
                                <Zap class="w-8 h-8 lg:w-10 lg:h-10 text-white" />
                            </div>
                            <h4 class="text-xl lg:text-2xl font-black mb-2 tracking-tighter">Learn Pro</h4>
                            <p class="text-white/80 text-xs lg:text-sm font-medium mb-6 lg:mb-8 leading-relaxed">
                                Access the world's most advanced digital curriculum and resources.
                            </p>
                            <Button class="w-full bg-white text-terracotta hover:bg-white/90 font-black rounded-2xl h-11 lg:h-12 shadow-md border-0">
                                Enter Learning Hub
                            </Button>
                        </div>
                    </div>

                    <!-- Peer Activity -->
                    <div class="card-warm rounded-3xl lg:rounded-[2.5rem] p-6 lg:p-8">
                        <h3 class="text-xl font-black text-warm-text text-dark-text tracking-tighter mb-6 flex items-center gap-2">
                            <Activity class="w-5 h-5 text-forest" />
                            Active Peers
                        </h3>
                        <div class="flex -space-x-4 overflow-hidden mb-6">
                            <Avatar v-for="i in 5" :key="i" class="inline-block border-4 border-warm-bg dark:border-dark-bg w-12 h-12">
                                <AvatarImage :src="`https://i.pravatar.cc/100?u=${i}`" />
                                <AvatarFallback class="bg-terracotta/10 text-terracotta">S</AvatarFallback>
                            </Avatar>
                            <div class="w-12 h-12 rounded-full bg-terracotta/5 border-4 border-warm-bg dark:border-dark-bg flex items-center justify-center text-xs font-black text-warm-muted text-dark-muted/80">
                                +12
                            </div>
                        </div>
                        <p class="text-xs font-bold text-warm-muted text-dark-muted/80 leading-relaxed uppercase tracking-wider">
                            <span class="text-forest font-black">18 students</span> from your section are currently studying in the digital lab.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </Sidebar>
</template>