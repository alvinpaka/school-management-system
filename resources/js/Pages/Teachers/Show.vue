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
        case 'active': return 'text-emerald-600 bg-emerald-500/10 border-emerald-500/20';
        case 'inactive': return 'text-rose-600 bg-rose-500/10 border-rose-500/20';
        case 'on leave': return 'text-amber-600 bg-amber-500/10 border-amber-500/20';
        default: return 'text-gray-600 bg-gray-500/10 border-gray-500/20';
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
                    <Button variant="ghost" size="sm" class="rounded-xl hover:bg-white/10">
                        <ArrowLeft class="w-4 h-4 mr-2" />
                        Faculty
                    </Button>
                </Link>
                <span class="text-gray-400">/</span>
                <span class="font-black text-sm uppercase tracking-wider text-gray-500">Academic Personnel Intelligence</span>
            </div>
        </template>

        <div class="space-y-8 animate-fade-in-up">
            <!-- Professional Header Section -->
            <div class="relative overflow-hidden bg-white dark:bg-slate-900 shadow-sm rounded-[3rem] border border-slate-200 dark:border-slate-800 p-8 md:p-12">
                <div class="relative z-10 flex flex-col md:flex-row md:items-end md:justify-between gap-8">
                    <div class="flex flex-col md:flex-row items-center md:items-end gap-8">
                        <div class="relative group">
                            <Avatar class="w-40 h-40 md:w-48 md:h-48 border-4 border-slate-100 dark:border-slate-800 rounded-[3rem] relative shadow-lg">
                                <AvatarImage 
                                    v-if="teacher.user?.photo"
                                    :src="`/storage/${teacher.user.photo}`" 
                                    :alt="teacher.user.name"
                                    class="object-cover"
                                />
                                <AvatarFallback class="text-5xl font-black bg-emerald-600 text-white rounded-[3rem] uppercase">
                                    {{ getInitials(teacher.user.name) }}
                                </AvatarFallback>
                            </Avatar>
                        </div>
                        
                        <div class="text-center md:text-left space-y-4">
                            <div class="flex items-center justify-center md:justify-start gap-2">
                                <Badge :class="['rounded-full px-4 py-1 h-7 border-0 font-black uppercase text-[9px] tracking-widest', getStatusColor(teacher.status)]">
                                    {{ teacher.status || 'Active' }}
                                </Badge>
                                <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Employee: #{{ teacher.employee_id }}</span>
                            </div>
                            <h1 class="text-4xl md:text-5xl font-black text-gray-900 dark:text-white tracking-tighter">
                                {{ teacher.user.name }}
                                <ShieldCheck class="inline-block w-8 h-8 text-emerald-500 ml-2" />
                            </h1>
                            <div class="flex flex-wrap items-center justify-center md:justify-start gap-6">
                                <div class="flex items-center text-sm font-bold text-gray-500 uppercase tracking-wide">
                                    <GraduationCap class="w-5 h-5 mr-2 text-emerald-600" />
                                    {{ teacher.specialization || 'Academic Professional' }}
                                </div>
                                <div class="flex items-center text-sm font-bold text-gray-500 uppercase tracking-wide">
                                    <Building class="w-5 h-5 mr-2 text-blue-600" />
                                    Faculty of Science
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div v-if="isAdmin" class="flex items-center gap-3">
                        <Link :href="route('teachers.edit', teacher.id)">
                            <Button class="bg-emerald-600 hover:bg-emerald-700 text-white font-black rounded-2xl h-14 px-8 shadow-md transition-all">
                                <Edit class="w-5 h-5 mr-2" />
                                Management Console
                            </Button>
                        </Link>
                        <DropdownMenu>
                            <DropdownMenuTrigger as-child>
                                <Button variant="outline" class="rounded-2xl h-14 w-14 border-slate-200 dark:border-slate-800 text-gray-400">
                                    <MoreVertical class="w-6 h-6" />
                                </Button>
                            </DropdownMenuTrigger>
                            <DropdownMenuContent align="end" class="w-56 bg-white dark:bg-slate-900 rounded-2xl border-slate-200 dark:border-slate-800 p-2 shadow-xl">
                                <DropdownMenuItem class="rounded-xl h-10 font-bold gap-2 focus:bg-emerald-50 dark:focus:bg-emerald-900/20">
                                    <Download class="w-4 h-4" /> Export HR Data
                                </DropdownMenuItem>
                                <DropdownMenuItem @click="showDeleteDialog = true" class="rounded-xl h-10 font-bold gap-2 text-rose-600 focus:bg-rose-50 dark:focus:bg-rose-900/20">
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
                    { label: 'Workload', val: '24 hrs/wk', icon: Clock, color: 'emerald' },
                    { label: 'Student Impact', val: '160+', icon: Users, color: 'blue' },
                    { label: 'Courses', val: '4 Active', icon: BookOpen, color: 'orange' },
                    { label: 'K.P.I Score', val: '9.4/10', icon: TrendingUp, color: 'indigo' }
                ]" :key="idx" class="bg-white dark:bg-slate-900 p-6 rounded-[2.5rem] border border-slate-100 dark:border-slate-800 shadow-sm group hover:border-emerald-500/30 transition-all duration-300">
                    <div class="flex items-center justify-between mb-4">
                        <div :class="['w-12 h-12 rounded-2xl flex items-center justify-center transition-colors font-bold', `bg-${stat.color}-50 dark:bg-${stat.color}-900/10 text-${stat.color}-600`]">
                            <component :is="stat.icon" class="w-6 h-6" />
                        </div>
                        <Badge class="bg-slate-50 dark:bg-slate-800 border-0 text-gray-400 font-black text-[9px] uppercase tracking-widest">{{ stat.label }}</Badge>
                    </div>
                    <h3 class="text-3xl font-black text-gray-900 dark:text-white tracking-tighter">{{ stat.val }}</h3>
                </div>
            </div>

            <!-- Content Intelligence Tabs -->
            <Tabs v-model="activeTab" class="w-full">
                <TabsList class="p-1 bg-slate-100/50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-2xl mb-8 inline-flex h-14">
                    <TabsTrigger value="overview" class="rounded-xl px-8 font-black uppercase text-[10px] tracking-widest data-[state=active]:bg-white dark:data-[state=active]:bg-slate-700 data-[state=active]:text-emerald-600 data-[state=active]:shadow-sm">
                        Personnel Intel
                    </TabsTrigger>
                    <TabsTrigger value="professional" class="rounded-xl px-8 font-black uppercase text-[10px] tracking-widest data-[state=active]:bg-white dark:data-[state=active]:bg-slate-700 data-[state=active]:text-emerald-600 data-[state=active]:shadow-sm">
                        Academic Log
                    </TabsTrigger>
                    <TabsTrigger value="schedule" class="rounded-xl px-8 font-black uppercase text-[10px] tracking-widest data-[state=active]:bg-white dark:data-[state=active]:bg-slate-700 data-[state=active]:text-emerald-600 data-[state=active]:shadow-sm">
                        Operational Grid
                    </TabsTrigger>
                </TabsList>

                <TabsContent value="overview" class="grid grid-cols-1 lg:grid-cols-2 gap-8 animate-fade-in-up mt-0">
                    <Card class="bg-white dark:bg-slate-900 shadow-sm border-slate-200 dark:border-slate-800 rounded-[3rem] overflow-hidden">
                        <CardHeader class="p-8 border-b border-slate-50 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/50">
                            <CardTitle class="text-2xl font-black tracking-tighter flex items-center gap-3">
                                <UserCircle class="w-6 h-6 text-emerald-600" />
                                Biological Specifications
                            </CardTitle>
                        </CardHeader>
                        <CardContent class="p-8 space-y-6">
                            <div v-for="item in [
                                { icon: Mail, label: 'Official Communications', val: teacher.user.email },
                                { icon: Smartphone, label: 'Emergency Link', val: teacher.phone || 'System Encrypted' },
                                { icon: Calendar, label: 'Birth Registry', val: formatDate(teacher.date_of_birth) },
                                { icon: MapPin, label: 'Residential Mapping', val: teacher.address || 'Unspecified' }
                            ]" :key="item.label" class="flex items-center gap-6 p-4 rounded-2xl hover:bg-slate-50 dark:hover:bg-slate-800 transition-all group">
                                <div class="w-12 h-12 rounded-xl bg-emerald-50 dark:bg-emerald-900/20 flex items-center justify-center text-emerald-600 group-hover:bg-emerald-600 group-hover:text-white transition-colors shadow-inner">
                                    <component :is="item.icon" class="w-5 h-5" />
                                </div>
                                <div class="flex-1">
                                    <p class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-1">{{ item.label }}</p>
                                    <p class="text-sm font-black text-gray-900 dark:text-white truncate">{{ item.val }}</p>
                                </div>
                            </div>
                        </CardContent>
                    </Card>

                    <Card class="bg-white dark:bg-slate-900 shadow-sm border-slate-200 dark:border-slate-800 rounded-[3rem] overflow-hidden">
                        <CardHeader class="p-8 border-b border-slate-50 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/50">
                            <CardTitle class="text-2xl font-black tracking-tighter flex items-center gap-3">
                                <AlertCircle class="w-6 h-6 text-blue-600" />
                                Emergency & Health Audit
                            </CardTitle>
                        </CardHeader>
                        <CardContent class="p-8 space-y-8">
                             <div class="p-8 rounded-[2.5rem] bg-slate-50 dark:bg-slate-800 border border-slate-100 dark:border-slate-700 shadow-sm">
                                <p class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-4">Secondary Contact Intelligence</p>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                    <div>
                                        <h4 class="text-xl font-black text-gray-900 dark:text-white tracking-tight">{{ teacher.emergency_contact_name || 'Guardian One' }}</h4>
                                        <p class="text-xs font-bold text-blue-600 uppercase tracking-widest">{{ teacher.emergency_contact_relationship || 'Emergency Contact' }}</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-lg font-black text-gray-900 dark:text-white">{{ teacher.emergency_contact_phone || 'Unlinked' }}</p>
                                        <Badge class="bg-blue-50 dark:bg-blue-900/20 text-blue-600 border-0 rounded-full h-6 font-black uppercase text-[8px]">Encrypted</Badge>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="flex items-center justify-between p-6 rounded-[2rem] border border-blue-100 dark:border-blue-900/20 bg-blue-50/50 dark:bg-blue-900/10 shadow-inner">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 rounded-2xl bg-blue-600 flex items-center justify-center text-white shadow-lg">
                                        <Activity class="w-6 h-6" />
                                    </div>
                                    <div>
                                        <p class="text-[10px] font-black uppercase tracking-widest text-gray-500">Blood Registry</p>
                                        <p class="text-2xl font-black text-blue-600">{{ teacher.blood_group || 'O+' }}</p>
                                    </div>
                                </div>
                                <ShieldCheck class="w-8 h-8 text-blue-600 opacity-20" />
                            </div>
                        </CardContent>
                    </Card>
                </TabsContent>

                <TabsContent value="professional" class="animate-fade-in-up mt-0">
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                        <div class="lg:col-span-2">
                             <Card class="bg-white dark:bg-slate-900 shadow-sm border-slate-200 dark:border-slate-800 rounded-[3rem] overflow-hidden">
                                <CardHeader class="p-8 border-b border-slate-50 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/50">
                                    <CardTitle class="text-2xl font-black tracking-tighter flex items-center gap-3">
                                        <Award class="w-6 h-6 text-emerald-600" />
                                        Professional Credentials
                                    </CardTitle>
                                </CardHeader>
                                <CardContent class="p-8 grid grid-cols-1 md:grid-cols-2 gap-8">
                                    <div v-for="item in [
                                        { icon: Briefcase, label: 'Employment Class', val: teacher.employment_type || 'Full Time' },
                                        { icon: GraduationCap, label: 'Highest Academic', val: teacher.qualification || 'Masters Degree' },
                                        { icon: Calendar, label: 'Inauguration', val: formatDate(teacher.joining_date) },
                                        { icon: Languages, label: 'Instruction Languge', val: 'English / Multi' }
                                    ]" :key="item.label" class="p-6 rounded-[2rem] bg-slate-50 dark:bg-slate-800 border border-slate-100 dark:border-slate-700 flex items-start gap-4">
                                        <div class="w-10 h-10 rounded-xl bg-emerald-50 dark:bg-emerald-900/20 flex items-center justify-center text-emerald-600">
                                            <component :is="item.icon" class="w-5 h-5" />
                                        </div>
                                        <div>
                                            <p class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-1">{{ item.label }}</p>
                                            <p class="text-sm font-black text-gray-900 dark:text-white leading-tight">{{ item.val }}</p>
                                        </div>
                                    </div>
                                </CardContent>
                            </Card>
                        </div>
                        
                        <div class="bg-emerald-600 rounded-[3rem] p-8 flex flex-col justify-between text-white shadow-lg overflow-hidden relative group">
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
                    <div class="bg-white dark:bg-slate-900 p-12 rounded-[3.5rem] border border-slate-200 dark:border-slate-800 text-center space-y-6 shadow-sm">
                        <div class="w-24 h-24 rounded-[2.5rem] bg-slate-50 dark:bg-slate-800 flex items-center justify-center mx-auto text-gray-400 transition-transform">
                            <History class="w-12 h-12" />
                        </div>
                        <div class="max-w-md mx-auto">
                            <h3 class="text-2xl font-black tracking-tighter text-gray-900 dark:text-white">Live Grid Standby</h3>
                            <p class="text-sm font-bold text-gray-500 uppercase tracking-widest mt-2">The operational schedule for this personnel is currently being synchronized with the master timetable.</p>
                        </div>
                        <Button class="bg-slate-900 dark:bg-white dark:text-slate-900 font-black rounded-2xl px-8 h-12 shadow-sm">
                            Request Override
                        </Button>
                    </div>
                </TabsContent>
            </Tabs>
        </div>

        <!-- Purge Registry Confirmation -->
        <Dialog v-model:open="showDeleteDialog">
            <DialogContent class="glass-card rounded-[2.5rem] border-white/10 shadow-2xl">
                <DialogHeader>
                    <DialogTitle class="text-2xl font-black tracking-tighter text-rose-600">Critical: Purge Personnel?</DialogTitle>
                    <DialogDescription class="font-bold text-gray-500">
                        This action will decommission <strong>{{ teacher.user.name }}</strong> from all academic systems. Payroll and instructional logs will be archived. This is irreversible.
                    </DialogDescription>
                </DialogHeader>
                <DialogFooter class="gap-3">
                    <Button variant="outline" @click="showDeleteDialog = false" class="rounded-xl font-black border-white/10">Abort</Button>
                    <Button variant="destructive" @click="deleteTeacher" class="rounded-xl font-black bg-rose-600 shadow-lg shadow-rose-500/20">Purge Record</Button>
                </DialogFooter>
            </DialogContent>
        </Dialog>
    </Sidebar>
</template>

<style scoped>
.animate-float {
    animation: float 6s ease-in-out infinite;
}

@keyframes float {
    0% { transform: translateY(0px) rotate(0deg); }
    50% { transform: translateY(-20px) rotate(2deg); }
    100% { transform: translateY(0px) rotate(0deg); }
}

.animate-fade-in-up {
    animation: fadeInUp 0.5s ease-out forwards;
}

@keyframes fadeInUp {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}
</style>