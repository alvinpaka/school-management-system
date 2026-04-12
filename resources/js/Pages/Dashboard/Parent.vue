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
    Award,
    MoreVertical,
    PieChart
} from 'lucide-vue-next';

defineProps({
    parent: Object,
    children: Array
});

const quickActions = [
    {
        title: 'Children Profiles',
        description: 'Academic overview',
        icon: Baby,
        gradient: 'from-purple-500 to-indigo-600',
        glow: 'shadow-purple-500/20',
        href: route('students.index')
    },
    {
        title: 'Fee Management',
        description: 'Payments & invoices',
        icon: CreditCard,
        gradient: 'from-emerald-500 to-teal-600',
        glow: 'shadow-emerald-500/20',
        href: route('fees.index')
    },
    {
        title: 'Report Cards',
        description: 'Examination results',
        icon: FileText,
        gradient: 'from-blue-500 to-cyan-600',
        glow: 'shadow-blue-500/20',
        href: route('exams.index')
    },
    {
        title: 'Daily Attendance',
        description: 'Presence registry',
        icon: CheckCircle,
        gradient: 'from-orange-500 to-amber-600',
        glow: 'shadow-orange-500/20',
        href: route('attendance.index')
    }
];

const recentActivities = [
    {
        title: 'Fee Payment Successful',
        description: 'UGX 200,000 processed for Term 1',
        time: '2 hours ago',
        icon: DollarSign,
        color: 'text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-950/50'
    },
    {
        title: 'Meeting Scheduled',
        description: 'Parent-Teacher conference for tomorrow',
        time: '1 day ago',
        icon: Calendar,
        color: 'text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-950/50'
    }
];

const upcomingEvents = [
    { title: 'Academic Conference', date: 'Feb 20, 2026', time: '02:00 PM', type: 'Meeting', color: 'text-indigo-600 bg-indigo-50' },
    { title: 'Annual Sports Day', date: 'Mar 01, 2026', time: '09:00 AM', type: 'Event', color: 'text-emerald-600 bg-emerald-50' }
];

const getInitials = (name) => {
    return name?.split(' ').map(word => word[0]).join('').toUpperCase().slice(0, 2) || 'PR';
};
</script>

<template>
    <Head title="Parent Dashboard | EduManage Pro" />

    <Sidebar>
        <template #header-title>
            <div class="flex items-center gap-2">
                <div class="p-2 bg-primary/10 rounded-lg">
                    <Heart class="w-4 h-4 text-primary" />
                </div>
                <span class="font-black text-sm uppercase tracking-wider text-muted-foreground">Family Portal</span>
            </div>
        </template>

        <div class="space-y-8 animate-fade-in-up">
            <!-- Dramatic Welcome Header -->
            <div class="relative overflow-hidden bg-card shadow-sm rounded-3xl lg:rounded-[2.5rem] border border-border p-6 lg:p-12">
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 lg:gap-8">
                        <div class="flex flex-col md:flex-row items-center gap-6">
                            <div class="relative group">
                                <Avatar class="w-20 h-20 lg:w-24 lg:h-24 border-2 border-border rounded-2xl lg:rounded-3xl relative">
                                    <AvatarImage 
                                        v-if="$page.props.auth.user.photo"
                                        :src="`/storage/${$page.props.auth.user.photo}`" 
                                        :alt="$page.props.auth.user.name"
                                        class="object-cover"
                                    />
                                    <AvatarFallback class="text-xl lg:text-3xl font-black bg-purple-600 text-white rounded-2xl lg:rounded-3xl uppercase">
                                        {{ getInitials($page.props.auth.user.name) }}
                                    </AvatarFallback>
                                </Avatar>
                            </div>
                            <div class="text-center md:text-left">
                                <h1 class="text-3xl lg:text-5xl font-black text-foreground mb-1 lg:mb-2 tracking-tighter leading-tight">
                                    Hello, {{ $page.props.auth.user.name.split(' ')[0] }}! <span class="animate-bounce inline-block">👋</span>
                                </h1>
                                <p class="text-base lg:text-lg text-muted-foreground font-medium">
                                    Stay engaged with your children's <span class="text-primary font-black">academic journey</span> today.
                                </p>
                            </div>
                        </div>
                    
                        <div class="flex flex-col items-start md:items-end">
                            <div class="bg-muted px-4 lg:px-6 py-2 lg:py-3 rounded-2xl border border-border text-left md:text-right">
                                <p class="text-[10px] font-black uppercase tracking-widest text-primary mb-1">Parental Insights</p>
                                <p class="text-sm lg:text-base font-bold text-foreground">
                                    {{ new Date().toLocaleDateString('en-US', { weekday: 'long', month: 'long', day: 'numeric' }) }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

            <!-- Stats & Children Overview -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                
                <!-- Main Content (8 cols) -->
                <div class="lg:col-span-8 space-y-8">
                    
                    <!-- Premium Stats Grid -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 lg:gap-6">
                        <div class="bg-card p-5 lg:p-6 rounded-3xl lg:rounded-[2rem] border border-border shadow-sm group cursor-pointer lg:hover:border-primary/30 transition-all duration-300">
                             <div class="flex items-center justify-between mb-6">
                                <div class="w-14 h-14 rounded-2xl bg-primary/10 flex items-center justify-center text-primary">
                                    <Baby class="w-7 h-7" />
                                </div>
                                <Badge class="bg-primary/10 text-primary border-0 font-black">{{ children?.length || 0 }} TOTAL</Badge>
                            </div>
                            <p class="text-xs font-black uppercase tracking-widest text-muted-foreground/80 mb-1">My Children</p>
                            <h3 class="text-4xl font-black text-foreground tracking-tighter">Enrolled</h3>
                        </div>

                        <div class="bg-card p-5 lg:p-6 rounded-3xl lg:rounded-[2rem] border border-border shadow-sm group cursor-pointer lg:hover:border-emerald-500/30 transition-all duration-300">
                            <div class="flex items-center justify-between mb-6">
                                <div class="w-14 h-14 rounded-2xl bg-emerald-50 dark:bg-emerald-500/10 flex items-center justify-center text-emerald-600">
                                    <DollarSign class="w-7 h-7" />
                                </div>
                                <Badge class="bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 border-0 font-black">75% PAID</Badge>
                            </div>
                            <p class="text-xs font-black uppercase tracking-widest text-muted-foreground/80 mb-1">Total Fees</p>
                            <h3 class="text-4xl font-black text-foreground tracking-tighter">UGX 550K</h3>
                        </div>

                        <div class="bg-card p-5 lg:p-6 rounded-3xl lg:rounded-[2rem] border border-border shadow-sm group cursor-pointer lg:hover:border-primary/30 transition-all duration-300">
                            <div class="flex items-center justify-between mb-6">
                                <div class="w-14 h-14 rounded-2xl bg-primary/10 flex items-center justify-center text-primary">
                                    <TrendingUp class="w-7 h-7" />
                                </div>
                                <Badge class="bg-primary/10 text-primary border-0 font-black">EXCELLENT</Badge>
                            </div>
                            <p class="text-xs font-black uppercase tracking-widest text-muted-foreground/80 mb-1">Avg Attendance</p>
                            <h3 class="text-4xl font-black text-foreground tracking-tighter">93.5%</h3>
                        </div>
                    </div>

                    <!-- Children Intelligence -->
                    <div class="space-y-4">
                        <div class="flex items-center justify-between px-2">
                            <h2 class="text-xl font-black text-primary tracking-tight uppercase px-4 py-1 bg-primary/10 rounded-lg inline-block">My Children</h2>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 lg:gap-6">
                            <div 
                                v-for="(child, index) in children" 
                                :key="index"
                                class="bg-card p-6 rounded-3xl lg:rounded-[2.5rem] border border-border shadow-sm lg:hover:border-primary/30 group transition-all duration-500"
                            >
                                <div class="flex items-center gap-4 lg:gap-5 mb-6">
                                    <Avatar class="w-16 h-16 lg:w-20 lg:h-20 rounded-2xl lg:rounded-3xl border-2 border-border">
                                        <AvatarImage 
                                            v-if="child.user?.photo"
                                            :src="`/storage/${child.user.photo}`" 
                                            :alt="child.user?.name"
                                        />
                                        <AvatarFallback class="text-xl lg:text-2xl font-black bg-indigo-600 text-white rounded-2xl lg:rounded-3xl uppercase">
                                            {{ getInitials(child.user?.name) }}
                                        </AvatarFallback>
                                    </Avatar>
                                    <div>
                                        <h4 class="text-xl font-black text-foreground tracking-tighter">{{ child.user?.name || 'Student' }}</h4>
                                        <Badge class="bg-primary/10 text-primary border-0 font-black mt-1">{{ child.academic_class?.name || 'Class 10A' }}</Badge>
                                    </div>
                                </div>
                                <div class="space-y-4">
                                    <div class="flex items-center justify-between p-3 rounded-2xl bg-muted border border-border">
                                        <span class="text-xs font-black text-muted-foreground/80 uppercase tracking-widest">Attendance</span>
                                        <span class="text-xs font-black text-emerald-500">95.2%</span>
                                    </div>
                                    <div class="flex items-center justify-between p-3 rounded-2xl bg-muted border border-border">
                                        <span class="text-xs font-black text-muted-foreground/80 uppercase tracking-widest">Academic Rank</span>
                                        <span class="text-xs font-black text-primary">Top 10%</span>
                                    </div>
                                </div>
                                <Button variant="outline" class="w-full mt-6 rounded-2xl font-black text-primary hover:bg-primary/10 border-primary/20">
                                    Full Student Profile
                                </Button>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Command Tools -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <Link 
                            v-for="action in quickActions" 
                            :key="action.title" 
                            :href="action.href"
                            class="bg-card p-4 rounded-3xl border border-border lg:hover:border-primary/30 shadow-sm group transition-all duration-500"
                        >
                            <div class="flex items-center gap-4 lg:gap-5 relative z-10">
                                <div class="w-14 h-14 lg:w-16 lg:h-16 rounded-2xl bg-muted flex items-center justify-center text-muted-foreground transition-all duration-500 lg:group-hover:bg-primary lg:group-hover:text-white lg:group-hover:shadow-lg lg:group-hover:shadow-primary/20">
                                    <component :is="action.icon" class="w-7 h-7 lg:w-8 lg:h-8" />
                                </div>
                                <div class="flex-1">
                                    <h4 class="text-base lg:text-lg font-black text-foreground tracking-tight">{{ action.title }}</h4>
                                    <p class="text-xs lg:text-sm text-muted-foreground font-medium">{{ action.description }}</p>
                                </div>
                            </div>
                        </Link>
                    </div>
                </div>

                <!-- Right Sidebar (4 cols) -->
                <div class="lg:col-span-4 space-y-6 lg:space-y-8">
                    
                    <!-- Events Intelligence -->
                    <div class="bg-card rounded-3xl lg:rounded-[2.5rem] border border-border shadow-sm p-6 lg:p-8">
                        <div class="flex items-center justify-between mb-8">
                            <h3 class="text-2xl font-black text-foreground tracking-tighter">Timeline</h3>
                            <div class="w-10 h-10 rounded-xl bg-muted flex items-center justify-center text-primary">
                                <CalendarDays class="w-5 h-5" />
                            </div>
                        </div>
                    <div class="space-y-6">
                        <div v-for="event in upcomingEvents" :key="event.title" class="relative pl-6 border-l-2 border-dashed border-primary/20">
                            <div class="absolute -left-[9px] top-0 w-4 h-4 rounded-full bg-card border-2 border-primary"></div>
                            <div class="mb-4">
                                <div class="flex items-center justify-between mb-2">
                                    <span :class="['px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest', event.color]">
                                        {{ event.type }}
                                    </span>
                                    <span class="text-[10px] font-black text-muted-foreground/80">{{ event.date }}</span>
                                </div>
                                <h4 class="text-sm font-black text-foreground mb-2 leading-tight">{{ event.title }}</h4>
                            </div>
                        </div>
                    </div>
                    <Button variant="outline" class="w-full mt-6 rounded-2xl border-border font-black h-12 text-muted-foreground hover:text-primary">
                        View All Events
                    </Button>
                </div>

                <!-- Payment Summary -->
                <div class="bg-card rounded-3xl lg:rounded-[2.5rem] border border-border shadow-sm p-6 lg:p-8 overflow-hidden relative">
                    <h3 class="text-xl font-black text-foreground tracking-tighter mb-8 flex items-center gap-2">
                        <CreditCard class="w-5 h-5 text-emerald-500" />
                        Fee Intelligence
                    </h3>
                    <div class="space-y-4 mb-8">
                        <div class="flex justify-between items-end">
                            <div>
                                <p class="text-[10px] font-black text-muted-foreground/80 uppercase tracking-widest mb-1">Total Due</p>
                                <p class="text-2xl font-black text-foreground tracking-tighter">UGX 750K</p>
                            </div>
                            <div class="text-right">
                                <p class="text-[10px] font-black text-emerald-500 uppercase tracking-widest mb-1">Paid</p>
                                <p class="text-lg font-black text-emerald-600 tracking-tighter">UGX 550K</p>
                            </div>
                        </div>
                        <div class="h-3 bg-muted rounded-full overflow-hidden">
                            <div class="h-full bg-emerald-500 rounded-full" style="width: 75%"></div>
                        </div>
                    </div>
                    <Button class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-black rounded-2xl h-12 shadow-md">
                        Clear Outstanding : UGX 200K
                    </Button>
                </div>

                    <!-- Communication Channel -->
                    <div class="rounded-3xl lg:rounded-[2.5rem] bg-primary p-6 lg:p-8 text-primary-foreground shadow-xl lg:shadow-primary/20 relative overflow-hidden group text-center">
                        <div class="relative z-10">
                            <div class="w-16 h-16 bg-white/10 rounded-2xl flex items-center justify-center mx-auto mb-4 border border-white/20">
                                <MessageCircle class="w-8 h-8 text-white" />
                            </div>
                            <h4 class="text-xl font-black mb-2 tracking-tighter">Support Hub</h4>
                            <p class="text-primary-foreground/80 text-xs font-medium mb-6 leading-relaxed">
                                Direct line to school admin and academic mentors.
                            </p>
                            <Button class="w-full bg-background text-primary hover:bg-background/90 font-black rounded-2xl h-11 shadow-md border-0">
                                Start Inquiry
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
  background: rgba(139, 92, 246, 0.1);
  border-radius: 10px;
}
.custom-scrollbar::-webkit-scrollbar-thumb:hover {
  background: rgba(139, 92, 246, 0.2);
}
</style>