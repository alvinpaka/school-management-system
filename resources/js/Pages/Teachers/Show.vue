<script setup>
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import Sidebar from '@/Components/Sidebar.vue';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Tabs, TabsContent, TabsList, TabsTrigger } from '@/components/ui/tabs';
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';
import { Separator } from '@/components/ui/separator';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
} from '@/components/ui/dialog';
import { 
    ArrowLeft,
    User,
    Mail,
    Phone,
    Briefcase,
    Edit,
    Trash2,
    Calendar,
    MapPin,
    GraduationCap,
    BookOpen,
    Users,
    Clock,
    Award,
    MoreVertical,
    Download,
    Share2,
    FileText,
    Activity,
    UserCircle,
    Building,
    TrendingUp,
    ShieldCheck,
    Smartphone,
    Languages,
    History,
    AlertCircle
} from 'lucide-vue-next';

const props = defineProps({
    teacher: {
        type: Object,
        required: true
    }
});

const page = usePage();
const currentUser = computed(() => page.props.auth.user);
const userRoles = computed(() => currentUser.value?.roles || []);
const isAdmin = computed(() => userRoles.value.includes('admin'));

const activeTab = ref('overview');
const showDeleteDialog = ref(false);

const deleteTeacher = () => {
    showDeleteDialog.value = false;
    router.delete(route('teachers.destroy', props.teacher.id));
};

const getStatusColor = (status) => {
    switch (status?.toLowerCase()) {
        case 'active': return 'text-forest bg-forest/10 border-forest/20';
        case 'inactive': return 'text-destructive bg-destructive/10 border-destructive/20';
        case 'on leave': return 'text-amber bg-amber/10 border-amber/20';
        default: return 'text-warm-muted bg-warm-muted/10 border-warm-muted/20';
    }
};

const getInitials = (name) => {
    return name?.split(' ').map(word => word[0]).join('').toUpperCase().slice(0, 2) || 'FA';
};

const formatDate = (date) => {
    if (!date) return 'Not specified';
    return new Date(date).toLocaleDateString('en-US', { 
        year: 'numeric', 
        month: 'long', 
        day: 'numeric' 
    });
};
</script>

<template>
    <Head :title="teacher.user.name + ' - Faculty Profile'" />

    <Sidebar>
        <template #header-title>
            <div class="flex items-center gap-2">
                <Link :href="route('teachers.index')">
                    <Button variant="ghost" size="sm" class="rounded-xl hover:bg-terracotta/10 text-warm-text dark:text-dark-text">
                        <ArrowLeft class="w-4 h-4 mr-2" />
                        Faculty
                    </Button>
                </Link>
                <span class="text-warm-muted dark:text-dark-muted">/</span>
                <span class="font-black text-sm uppercase tracking-wider text-warm-muted dark:text-dark-muted">Academic Personnel Intelligence</span>
            </div>
        </template>

        <div class="space-y-8 animate-fade-in-up">
            <!-- Professional Header Section -->
            <div class="relative overflow-hidden card-warm rounded-[3rem] p-8 md:p-12">
                <div class="relative z-10 flex flex-col md:flex-row md:items-end md:justify-between gap-8">
                    <div class="flex flex-col md:flex-row items-center md:items-end gap-8">
                        <div class="relative group">
                            <Avatar class="w-40 h-40 md:w-48 md:h-48 border-4 border-terracotta/20 rounded-[3rem] relative shadow-lg">
                                <AvatarImage 
                                    v-if="teacher.user?.photo"
                                    :src="`/storage/${teacher.user.photo}`" 
                                    :alt="teacher.user.name"
                                    class="object-cover"
                                />
                                <AvatarFallback class="text-5xl font-black bg-terracotta text-white rounded-[3rem] uppercase">
                                    {{ getInitials(teacher.user.name) }}
                                </AvatarFallback>
                            </Avatar>
                        </div>
                        
                        <div class="text-center md:text-left space-y-4">
                            <div class="flex items-center justify-center md:justify-start gap-2">
                                <Badge :class="['rounded-full px-4 py-1 h-7 border-0 font-black uppercase text-[9px] tracking-widest', getStatusColor(teacher.status)]">
                                    {{ teacher.status || 'Active' }}
                                </Badge>
                                <span class="text-[10px] font-black text-warm-muted dark:text-dark-muted uppercase tracking-widest">Employee: #{{ teacher.employee_id }}</span>
                            </div>
                            <h1 class="text-4xl md:text-5xl font-black text-warm-text dark:text-dark-text tracking-tighter">
                                {{ teacher.user.name }}
                                <ShieldCheck class="inline-block w-8 h-8 text-forest ml-2" />
                            </h1>
                            <div class="flex flex-wrap items-center justify-center md:justify-start gap-6">
                                <div class="flex items-center text-sm font-bold text-warm-muted dark:text-dark-muted uppercase tracking-wide">
                                    <GraduationCap class="w-5 h-5 mr-2 text-terracotta" />
                                    {{ teacher.specialization || 'Academic Professional' }}
                                </div>
                                <div class="flex items-center text-sm font-bold text-warm-muted dark:text-dark-muted uppercase tracking-wide">
                                    <Building class="w-5 h-5 mr-2 text-terracotta" />
                                    Faculty of Science
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div v-if="isAdmin" class="flex items-center gap-3">
                        <Link :href="route('teachers.edit', teacher.id)">
                            <Button class="accent-terracotta text-white font-black rounded-2xl h-14 px-8 shadow-md shadow-terracotta/30 transition-all">
                                <Edit class="w-5 h-5 mr-2" />
                                Management Console
                            </Button>
                        </Link>
                        <DropdownMenu>
                            <DropdownMenuTrigger as-child>
                                <Button variant="outline" class="rounded-2xl h-14 w-14 border-terracotta/20 text-warm-muted dark:text-dark-muted">
                                    <MoreVertical class="w-6 h-6" />
                                </Button>
                            </DropdownMenuTrigger>
                            <DropdownMenuContent align="end" class="w-56 card-warm rounded-2xl p-2 shadow-xl">
                                <DropdownMenuItem class="rounded-xl h-10 font-bold gap-2 hover:bg-terracotta/10">
                                    <Download class="w-4 h-4" /> Export HR Data
                                </DropdownMenuItem>
                                <DropdownMenuItem @click="showDeleteDialog = true" class="rounded-xl h-10 font-bold gap-2 text-destructive hover:bg-destructive/10">
                                    <Trash2 class="w-4 h-4" /> Decommission Record
                                </DropdownMenuItem>
                            </DropdownMenuContent>
                        </DropdownMenu>
                    </div>
                </div>
            </div>

            <!-- Professional Analytics -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div v-for="(stat, idx) in [
                    { label: 'Workload', val: '24 hrs/wk', icon: Clock, color: 'terracotta' },
                    { label: 'Student Impact', val: '160+', icon: Users, color: 'forest' },
                    { label: 'Courses', val: '4 Active', icon: BookOpen, color: 'amber' },
                    { label: 'K.P.I Score', val: '9.4/10', icon: TrendingUp, color: 'terracotta' }
                ]" :key="idx" class="card-warm p-6 rounded-[2.5rem] group transition-all duration-300">
                    <div class="flex items-center justify-between mb-4">
                        <div :class="['w-12 h-12 rounded-2xl flex items-center justify-center transition-colors font-bold', `bg-${stat.color}/10 text-${stat.color}`]">
                            <component :is="stat.icon" class="w-6 h-6" />
                        </div>
                        <Badge class="bg-terracotta/5 border-0 text-warm-muted dark:text-dark-muted font-black text-[9px] uppercase tracking-widest">{{ stat.label }}</Badge>
                    </div>
                    <h3 class="text-3xl font-black text-warm-text dark:text-dark-text tracking-tighter">{{ stat.val }}</h3>
                </div>
            </div>

            <!-- Content Intelligence Tabs -->
            <Tabs v-model="activeTab" class="w-full">
                <TabsList class="p-1 bg-terracotta/5 border border-terracotta/20 rounded-2xl mb-8 inline-flex h-14">
                    <TabsTrigger value="overview" class="rounded-xl px-8 font-black uppercase text-[10px] tracking-widest data-[state=active]:bg-white dark:data-[state=active]:bg-dark-bg data-[state=active]:text-terracotta data-[state=active]:shadow-sm">
                        Personnel Intel
                    </TabsTrigger>
                    <TabsTrigger value="professional" class="rounded-xl px-8 font-black uppercase text-[10px] tracking-widest data-[state=active]:bg-white dark:data-[state=active]:bg-dark-bg data-[state=active]:text-terracotta data-[state=active]:shadow-sm">
                        Academic Log
                    </TabsTrigger>
                    <TabsTrigger value="schedule" class="rounded-xl px-8 font-black uppercase text-[10px] tracking-widest data-[state=active]:bg-white dark:data-[state=active]:bg-dark-bg data-[state=active]:text-terracotta data-[state=active]:shadow-sm">
                        Operational Grid
                    </TabsTrigger>
                </TabsList>

                <TabsContent value="overview" class="grid grid-cols-1 lg:grid-cols-2 gap-8 animate-fade-in-up mt-0">
                    <div class="card-warm rounded-[3rem] overflow-hidden">
                        <div class="p-8 border-b border-terracotta/20 bg-terracotta/5">
                            <h3 class="text-2xl font-black tracking-tighter flex items-center gap-3 text-warm-text dark:text-dark-text">
                                <UserCircle class="w-6 h-6 text-terracotta" />
                                Biological Specifications
                            </h3>
                        </div>
                        <div class="p-8 space-y-6">
                            <div v-for="item in [
                                { icon: Mail, label: 'Official Communications', val: teacher.user.email },
                                { icon: Smartphone, label: 'Emergency Link', val: teacher.phone || 'System Encrypted' },
                                { icon: Calendar, label: 'Birth Registry', val: formatDate(teacher.date_of_birth) },
                                { icon: MapPin, label: 'Residential Mapping', val: teacher.address || 'Unspecified' }
                            ]" :key="item.label" class="flex items-center gap-6 p-4 rounded-2xl hover:bg-terracotta/5 transition-all group">
                                <div class="w-12 h-12 rounded-xl bg-terracotta/10 flex items-center justify-center text-terracotta group-hover:bg-terracotta group-hover:text-white transition-colors shadow-inner">
                                    <component :is="item.icon" class="w-5 h-5" />
                                </div>
                                <div class="flex-1">
                                    <p class="text-[10px] font-black uppercase tracking-widest text-warm-muted dark:text-dark-muted mb-1">{{ item.label }}</p>
                                    <p class="text-sm font-black text-warm-text dark:text-dark-text truncate">{{ item.val }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-warm rounded-[3rem] overflow-hidden">
                        <div class="p-8 border-b border-terracotta/20 bg-terracotta/5">
                            <h3 class="text-2xl font-black tracking-tighter flex items-center gap-3 text-warm-text dark:text-dark-text">
                                <AlertCircle class="w-6 h-6 text-amber" />
                                Emergency & Health Audit
                            </h3>
                        </div>
                        <div class="p-8 space-y-8">
                            <div class="p-8 rounded-[2.5rem] bg-terracotta/5 border border-terracotta/20 shadow-sm">
                                <p class="text-[10px] font-black uppercase tracking-widest text-warm-muted dark:text-dark-muted mb-4">Secondary Contact Intelligence</p>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                    <div>
                                        <h4 class="text-xl font-black text-warm-text dark:text-dark-text tracking-tight">{{ teacher.emergency_contact_name || 'Guardian One' }}</h4>
                                        <p class="text-xs font-bold text-terracotta uppercase tracking-widest">{{ teacher.emergency_contact_relationship || 'Emergency Contact' }}</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-lg font-black text-warm-text dark:text-dark-text">{{ teacher.emergency_contact_phone || 'Unlinked' }}</p>
                                        <Badge class="bg-terracotta/10 text-terracotta border-0 rounded-full h-6 font-black uppercase text-[8px]">Encrypted</Badge>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="flex items-center justify-between p-6 rounded-[2rem] border border-amber/20 bg-amber/5 shadow-inner">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 rounded-2xl bg-amber flex items-center justify-center text-white shadow-lg">
                                        <Activity class="w-6 h-6" />
                                    </div>
                                    <div>
                                        <p class="text-[10px] font-black uppercase tracking-widest text-warm-muted dark:text-dark-muted">Blood Registry</p>
                                        <p class="text-2xl font-black text-amber">{{ teacher.blood_group || 'O+' }}</p>
                                    </div>
                                </div>
                                <ShieldCheck class="w-8 h-8 text-amber opacity-20" />
                            </div>
                        </div>
                    </div>
                </TabsContent>

                <TabsContent value="professional" class="animate-fade-in-up mt-0">
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                        <div class="lg:col-span-2">
                            <div class="card-warm rounded-[3rem] overflow-hidden">
                                <div class="p-8 border-b border-terracotta/20 bg-terracotta/5">
                                    <h3 class="text-2xl font-black tracking-tighter flex items-center gap-3 text-warm-text dark:text-dark-text">
                                        <Award class="w-6 h-6 text-terracotta" />
                                        Professional Credentials
                                    </h3>
                                </div>
                                <div class="p-8 grid grid-cols-1 md:grid-cols-2 gap-8">
                                    <div v-for="item in [
                                        { icon: Briefcase, label: 'Employment Class', val: teacher.employment_type || 'Full Time' },
                                        { icon: GraduationCap, label: 'Highest Academic', val: teacher.qualification || 'Masters Degree' },
                                        { icon: Calendar, label: 'Inauguration', val: formatDate(teacher.joining_date) },
                                        { icon: Languages, label: 'Instruction Language', val: 'English / Multi' }
                                    ]" :key="item.label" class="p-6 rounded-[2rem] bg-terracotta/5 border border-terracotta/20 flex items-start gap-4">
                                        <div class="w-10 h-10 rounded-xl bg-terracotta/10 flex items-center justify-center text-terracotta">
                                            <component :is="item.icon" class="w-5 h-5" />
                                        </div>
                                        <div>
                                            <p class="text-[10px] font-black uppercase tracking-widest text-warm-muted dark:text-dark-muted mb-1">{{ item.label }}</p>
                                            <p class="text-sm font-black text-warm-text dark:text-dark-text leading-tight">{{ item.val }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="accent-terracotta rounded-[3rem] p-8 flex flex-col justify-between text-white shadow-lg shadow-terracotta/30 overflow-hidden relative group">
                            <div class="relative z-10">
                                <TrendingUp class="w-12 h-12 mb-6" />
                                <h4 class="text-3xl font-black tracking-tighter mb-2">Faculty Impact</h4>
                                <p class="text-xs font-bold opacity-80 uppercase tracking-widest mb-8">Tenure: {{ teacher.experience || '6' }} years</p>
                                
                                <div class="flex items-center gap-4 p-4 bg-white/10 rounded-2xl border border-white/20">
                                    <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center">
                                        <Users class="w-5 h-5" />
                                    </div>
                                    <div>
                                        <p class="text-xl font-black">1.2k+</p>
                                        <p class="text-[9px] font-bold opacity-70 uppercase tracking-widest">Graduated Students</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </TabsContent>

                <TabsContent value="schedule" class="animate-fade-in-up mt-0">
                    <div class="card-warm p-12 rounded-[3.5rem] text-center space-y-6">
                        <div class="w-24 h-24 rounded-[2.5rem] bg-terracotta/5 flex items-center justify-center mx-auto text-terracotta transition-transform">
                            <History class="w-12 h-12" />
                        </div>
                        <div class="max-w-md mx-auto">
                            <h3 class="text-2xl font-black tracking-tighter text-warm-text dark:text-dark-text">Live Grid Standby</h3>
                            <p class="text-sm font-bold text-warm-muted dark:text-dark-muted uppercase tracking-widest mt-2">The operational schedule for this personnel is currently being synchronized with the master timetable.</p>
                        </div>
                        <Button class="accent-terracotta text-white font-black rounded-2xl px-8 h-12 shadow-sm shadow-terracotta/30">
                            Request Override
                        </Button>
                    </div>
                </TabsContent>
            </Tabs>
        </div>

        <!-- Purge Registry Confirmation -->
        <Dialog v-model:open="showDeleteDialog">
            <DialogContent class="card-warm rounded-xl rounded-[2.5rem] shadow-2xl">
                <DialogHeader>
                    <DialogTitle class="text-2xl font-black tracking-tighter text-destructive">Critical: Purge Personnel?</DialogTitle>
                    <DialogDescription class="font-bold text-warm-muted dark:text-dark-muted">
                        This action will decommission <strong class="text-warm-text dark:text-dark-text">{{ teacher.user.name }}</strong> from all academic systems. Payroll and instructional logs will be archived. This is irreversible.
                    </DialogDescription>
                </DialogHeader>
                <DialogFooter class="gap-3">
                    <Button variant="outline" @click="showDeleteDialog = false" class="rounded-xl font-black border-terracotta/20">Abort</Button>
                    <Button variant="destructive" @click="deleteTeacher" class="rounded-xl font-black bg-destructive shadow-lg shadow-destructive/20">Purge Record</Button>
                </DialogFooter>
            </DialogContent>
        </Dialog>
    </Sidebar>
</template>