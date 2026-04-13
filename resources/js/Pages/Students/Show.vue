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
    Calendar,
    MapPin,
    Edit,
    Trash2,
    GraduationCap,
    Home,
    Users,
    BookOpen,
    ClipboardList,
    Download,
    Share2,
    MoreVertical,
    UserCircle,
    Briefcase,
    Heart,
    Activity,
    FileText,
    Award,
    DollarSign,
    History,
    Sparkles,
    ShieldCheck,
    Cake,
    Smartphone,
    UserCheck,
    AlertCircle
} from 'lucide-vue-next';

const props = defineProps({
    student: {
        type: Object,
        required: true
    }
});

const page = usePage();
const currentUser = computed(() => page.props.auth.user);
const userRoles = computed(() => currentUser.value?.roles || []);
const isAdminOrTeacher = computed(() => userRoles.value.some(r => ['admin', 'teacher'].includes(r)));

const activeTab = ref('overview');
const showDeleteDialog = ref(false);

const deleteStudent = () => {
    showDeleteDialog.value = false;
    router.delete(route('students.destroy', props.student.id));
};

const getStatusColor = (status) => {
    switch (status) {
        case 'active':    return 'bg-forest/10 text-forest border-forest/20';
        case 'inactive':  return 'bg-destructive/10 text-destructive border-destructive/20';
        case 'graduated': return 'bg-terracotta/10 text-terracotta border-terracotta/20';
        default:          return 'bg-warm-muted/10 text-warm-muted border-warm-muted/20';
    }
};

const getInitials = (name) => {
    return name?.split(' ').map(word => word[0]).join('').toUpperCase().slice(0, 2) || 'ST';
};

const formatDate = (date) => {
    if (!date) return 'Not specified';
    return new Date(date).toLocaleDateString('en-US', { 
        year: 'numeric', 
        month: 'long', 
        day: 'numeric' 
    });
};

const calculateAge = (dob) => {
    if (!dob) return 'N/A';
    const today = new Date();
    const birthDate = new Date(dob);
    let age = today.getFullYear() - birthDate.getFullYear();
    const m = today.getMonth() - birthDate.getMonth();
    if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) {
        age--;
    }
    return age;
};
</script>

<template>
    <Head :title="student.user.name + ' - Student Profile'" />

    <Sidebar>
        <template #header-title>
            <div class="flex items-center gap-2">
                <Link :href="route('students.index')">
                    <Button variant="ghost" size="sm" class="rounded-xl hover:bg-terracotta/10">
                        <ArrowLeft class="w-4 h-4 mr-2" />
                        Students
                    </Button>
                </Link>
                <span class="text-warm-muted dark:text-dark-muted">/</span>
                <span class="font-black text-sm uppercase tracking-wider text-warm-muted dark:text-dark-muted">Student Intelligence</span>
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
                                    v-if="student.user?.photo"
                                    :src="`/storage/${student.user.photo}`" 
                                    :alt="student.user.name"
                                    class="object-cover"
                                />
                                <AvatarFallback class="text-5xl font-black bg-terracotta text-white rounded-[3rem] uppercase">
                                    {{ getInitials(student.user.name) }}
                                </AvatarFallback>
                            </Avatar>
                        </div>
                        
                        <div class="text-center md:text-left space-y-4">
                            <div class="flex items-center justify-center md:justify-start gap-2">
                                <Badge :class="['rounded-full px-4 py-1 h-7 border-0 font-black uppercase text-[9px] tracking-widest', getStatusColor(student.status)]">
                                    {{ student.status }}
                                </Badge>
                                <span class="text-[10px] font-black text-warm-muted dark:text-dark-muted uppercase tracking-widest">Adm: #{{ student.admission_number }}</span>
                            </div>
                            <h1 class="text-4xl md:text-5xl font-black text-warm-text dark:text-dark-text tracking-tighter">
                                {{ student.user.name }}
                            </h1>
                            <div class="flex flex-wrap items-center justify-center md:justify-start gap-6">
                                <div class="flex items-center text-sm font-bold text-warm-muted dark:text-dark-muted uppercase tracking-wide">
                                    <GraduationCap class="w-5 h-5 mr-2 text-terracotta" />
                                    {{ student.academic_class?.name || 'Class 10A' }}
                                    <span v-if="student.section?.name" class="ml-1 text-terracotta">• Section {{ student.section.name }}</span>
                                </div>
                                <div class="flex items-center text-sm font-bold text-warm-muted dark:text-dark-muted uppercase tracking-wide">
                                    <ShieldCheck class="w-5 h-5 mr-2 text-forest" />
                                    Roll: {{ student.roll_number }}
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div v-if="isAdminOrTeacher" class="flex items-center gap-3">
                        <Link :href="route('students.edit', student.id)">
                            <Button class="accent-terracotta text-white font-black rounded-2xl h-14 px-8 shadow-md shadow-terracotta/30 transition-all">
                                <Edit class="w-5 h-5 mr-2" />
                                Edit Profile
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
                                    <Download class="w-4 h-4" /> Download Dossier
                                </DropdownMenuItem>
                                <DropdownMenuItem @click="showDeleteDialog = true" class="rounded-xl h-10 font-bold gap-2 text-destructive hover:bg-destructive/10">
                                    <Trash2 class="w-4 h-4" /> Purge Record
                                </DropdownMenuItem>
                            </DropdownMenuContent>
                        </DropdownMenu>
                    </div>
                </div>
            </div>

            <!-- Dashboard Stats -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div
                    v-for="(stat, idx) in [
                        { label: 'Attendance',   val: '98.2%',   icon: UserCheck,  color: 'forest' },
                        { label: 'Average GPA',  val: '3.92',    icon: Award,      color: 'terracotta' },
                        { label: 'Assignments',  val: '14/15',   icon: FileText,   color: 'amber' },
                        { label: 'Fees Status',  val: 'Cleared', icon: DollarSign, color: 'forest' }
                    ]"
                    :key="idx"
                    class="card-warm p-6 rounded-[2.5rem] group transition-all duration-300"
                >
                    <div class="flex items-center justify-between mb-4">
                        <div :class="['w-12 h-12 rounded-2xl flex items-center justify-center transition-colors font-bold', `bg-${stat.color}/10 text-${stat.color}`]">
                            <component :is="stat.icon" class="w-6 h-6" />
                        </div>
                        <Badge class="bg-terracotta/5 dark:bg-terracotta/5 border-0 text-warm-muted dark:text-dark-muted font-black text-[9px] uppercase tracking-widest">{{ stat.label }}</Badge>
                    </div>
                    <h3 class="text-3xl font-black text-warm-text dark:text-dark-text tracking-tighter">{{ stat.val }}</h3>
                </div>
            </div>

            <!-- Content Intelligence Tabs -->
            <Tabs v-model="activeTab" class="w-full">
                <TabsList class="p-1 bg-terracotta/5 border border-terracotta/20 rounded-2xl mb-8 inline-flex h-14">
                    <TabsTrigger value="overview" class="rounded-xl px-8 font-black uppercase text-[10px] tracking-widest data-[state=active]:bg-white dark:data-[state=active]:bg-dark-bg data-[state=active]:text-terracotta data-[state=active]:shadow-sm">
                        Biological Intel
                    </TabsTrigger>
                    <TabsTrigger value="academic" class="rounded-xl px-8 font-black uppercase text-[10px] tracking-widest data-[state=active]:bg-white dark:data-[state=active]:bg-dark-bg data-[state=active]:text-terracotta data-[state=active]:shadow-sm">
                        Academic Log
                    </TabsTrigger>
                    <TabsTrigger value="parent" class="rounded-xl px-8 font-black uppercase text-[10px] tracking-widest data-[state=active]:bg-white dark:data-[state=active]:bg-dark-bg data-[state=active]:text-terracotta data-[state=active]:shadow-sm">
                        Family Portal
                    </TabsTrigger>
                </TabsList>

                <TabsContent value="overview" class="grid grid-cols-1 lg:grid-cols-2 gap-8 animate-fade-in-up mt-0">
                    <div class="card-warm rounded-[3rem] overflow-hidden">
                        <div class="p-8 border-b border-terracotta/20 bg-terracotta/5">
                            <h3 class="text-2xl font-black tracking-tighter flex items-center gap-3 text-warm-text dark:text-dark-text">
                                <UserCircle class="w-6 h-6 text-terracotta" />
                                Personal Specifications
                            </h3>
                        </div>
                        <div class="p-8 space-y-6">
                            <div
                                v-for="item in [
                                    { icon: Mail,       label: 'Communications', val: student.user.email },
                                    { icon: Smartphone, label: 'Mobile Link',    val: student.phone || 'N/A' },
                                    { icon: Cake,       label: 'Birth Registry', val: formatDate(student.date_of_birth), extra: calculateAge(student.date_of_birth) + ' years' },
                                    { icon: MapPin,     label: 'Postal Address', val: student.address || 'Unspecified' }
                                ]"
                                :key="item.label"
                                class="flex items-center gap-6 p-4 rounded-2xl hover:bg-terracotta/5 transition-all group"
                            >
                                <div class="w-12 h-12 rounded-xl bg-terracotta/10 flex items-center justify-center text-terracotta group-hover:bg-terracotta group-hover:text-white transition-colors">
                                    <component :is="item.icon" class="w-5 h-5" />
                                </div>
                                <div class="flex-1">
                                    <p class="text-[10px] font-black uppercase tracking-widest text-warm-muted dark:text-dark-muted mb-1">{{ item.label }}</p>
                                    <p class="text-sm font-black text-warm-text dark:text-dark-text truncate">{{ item.val }}</p>
                                </div>
                                <Badge v-if="item.extra" class="bg-terracotta/10 text-terracotta border-0 font-black">{{ item.extra }}</Badge>
                            </div>
                        </div>
                    </div>

                    <div class="card-warm rounded-[3rem] overflow-hidden">
                        <div class="p-8 border-b border-terracotta/20 bg-terracotta/5">
                            <h3 class="text-2xl font-black tracking-tighter flex items-center gap-3 text-warm-text dark:text-dark-text">
                                <Heart class="w-6 h-6 text-amber" />
                                Health & Bio-Analytics
                            </h3>
                        </div>
                        <div class="p-8 space-y-6">
                            <div class="grid grid-cols-2 gap-4">
                                <div class="p-6 rounded-[2rem] bg-forest/5 border border-forest/20 text-center">
                                    <p class="text-[10px] font-black uppercase opacity-60 text-forest mb-2">Blood Group</p>
                                    <p class="text-4xl font-black tracking-tighter text-forest">{{ student.blood_group || 'O+' }}</p>
                                </div>
                                <div class="p-6 rounded-[2rem] bg-terracotta/5 border border-terracotta/20 text-center text-terracotta">
                                    <p class="text-[10px] font-black uppercase opacity-60 mb-2">Genotype</p>
                                    <p class="text-4xl font-black tracking-tighter">AA</p>
                                </div>
                            </div>
                            <div class="p-6 rounded-[2rem] bg-amber/5 border border-amber/20">
                                <h4 class="font-black text-amber uppercase text-[10px] tracking-widest mb-4 flex items-center gap-2">
                                    <AlertCircle class="w-4 h-4" />
                                    Critical Medical Alerts
                                </h4>
                                <p class="text-sm font-medium text-warm-muted dark:text-dark-muted leading-relaxed">
                                    {{ student.medical_conditions || 'None reported. Student is cleared for all physical activities.' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </TabsContent>

                <TabsContent value="academic" class="animate-fade-in-up mt-0">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <div class="card-warm rounded-[3rem] overflow-hidden">
                            <div class="p-8 border-b border-terracotta/20">
                                <h3 class="text-2xl font-black tracking-tighter flex items-center gap-3 text-warm-text dark:text-dark-text">
                                    <BookOpen class="w-6 h-6 text-terracotta" />
                                    Academic Summary
                                </h3>
                            </div>
                            <div class="p-8 grid grid-cols-1 gap-6">
                                <div
                                    v-for="item in [
                                        { label: 'Admission Date',   val: formatDate(student.admission_date) },
                                        { label: 'Academic Year',    val: '2025/2026' },
                                        { label: 'Section Capacity', val: '42 / 45' },
                                        { label: 'Attendance Rate',  val: '98.5%' }
                                    ]"
                                    :key="item.label"
                                    class="flex justify-between items-center p-4 bg-terracotta/5 rounded-2xl border border-terracotta/20"
                                >
                                    <span class="text-xs font-black uppercase tracking-widest text-warm-muted dark:text-dark-muted">{{ item.label }}</span>
                                    <span class="text-sm font-black text-warm-text dark:text-dark-text">{{ item.val }}</span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Performance Graph Mockup -->
                        <div class="accent-terracotta rounded-[3rem] p-8 flex flex-col justify-center items-center text-center text-white shadow-lg shadow-terracotta/30 overflow-hidden relative group">
                            <div class="relative z-10 w-full space-y-6">
                                <h4 class="text-2xl font-black tracking-tighter">Learning Velocity</h4>
                                <div class="flex items-end justify-center gap-4 h-32">
                                    <div v-for="h in [0.4, 0.6, 1, 0.8, 0.9]" :key="h" class="w-6 bg-white/20 rounded-t-lg transition-all hover:bg-white/40" :style="{ height: `${h * 100}%` }"></div>
                                </div>
                                <p class="text-xs font-bold opacity-80 uppercase tracking-widest">Growth Curve: +15% this term</p>
                            </div>
                        </div>
                    </div>
                </TabsContent>

                <TabsContent value="parent" class="animate-fade-in-up mt-0">
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                        <div class="lg:col-span-2">
                            <div class="card-warm rounded-[3rem] overflow-hidden">
                                <div class="p-8 border-b border-terracotta/20 bg-terracotta/5">
                                    <h3 class="text-2xl font-black tracking-tighter flex items-center gap-3 text-warm-text dark:text-dark-text">
                                        <Users class="w-6 h-6 text-terracotta" />
                                        Primary Guardian Details
                                    </h3>
                                </div>
                                <div class="p-8">
                                    <div class="flex items-center gap-8 mb-8">
                                        <Avatar class="w-24 h-24 rounded-[2rem] border-2 border-terracotta/20">
                                            <AvatarFallback class="bg-terracotta text-white font-black text-2xl uppercase">
                                                {{ student.parent_user?.user?.name ? getInitials(student.parent_user.user.name) : 'GR' }}
                                            </AvatarFallback>
                                        </Avatar>
                                        <div>
                                            <h3 class="text-2xl font-black text-warm-text dark:text-dark-text tracking-tighter leading-none mb-2">
                                                {{ student.parent_user?.user?.name || student.parent_name }}
                                            </h3>
                                            <Badge class="bg-terracotta/10 text-terracotta border-0 font-black uppercase tracking-widest text-[9px]">
                                                Verified Legal Guardian
                                            </Badge>
                                        </div>
                                    </div>
                                    
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div
                                            v-for="info in [
                                                { icon: Mail,       label: 'Email',      val: student.parent_user?.user?.email || student.parent_email || 'N/A' },
                                                { icon: Smartphone, label: 'Mobile',     val: student.parent_user?.phone || student.parent_phone || 'N/A' },
                                                { icon: Briefcase,  label: 'Occupation', val: student.parent_user?.occupation || 'Not reported' },
                                                { icon: Heart,      label: 'Kinship',    val: student.parent_user?.relationship_to_student || 'Guardian' }
                                            ]"
                                            :key="info.label"
                                            class="p-4 bg-terracotta/5 rounded-2xl border border-terracotta/20"
                                        >
                                            <div class="flex items-center gap-3 mb-1">
                                                <component :is="info.icon" class="w-4 h-4 text-warm-muted dark:text-dark-muted" />
                                                <span class="text-[9px] font-black uppercase tracking-widest text-warm-muted dark:text-dark-muted">{{ info.label }}</span>
                                            </div>
                                            <p class="text-sm font-black text-warm-text dark:text-dark-text">{{ info.val }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="card-warm rounded-[3rem] p-8 flex flex-col items-center justify-center text-center space-y-6">
                            <div class="w-20 h-20 rounded-[2rem] bg-terracotta/10 flex items-center justify-center text-terracotta">
                                <History class="w-10 h-10" />
                            </div>
                            <div>
                                <h4 class="text-xl font-black tracking-tighter text-warm-text dark:text-dark-text">Family History</h4>
                                <p class="text-xs font-bold text-warm-muted dark:text-dark-muted leading-relaxed max-w-[200px] mt-2">
                                    3 active siblings currently enrolled in the EduManage ecosystem.
                                </p>
                            </div>
                            <Button variant="outline" class="w-full rounded-2xl h-12 font-black border-terracotta/20 text-terracotta hover:bg-terracotta/10">
                                Link Siblings
                            </Button>
                        </div>
                    </div>
                </TabsContent>
            </Tabs>
        </div>

        <!-- Purge Registry Confirmation -->
        <Dialog v-model:open="showDeleteDialog">
            <DialogContent class="card-warm rounded-[2.5rem] shadow-2xl">
                <DialogHeader>
                    <DialogTitle class="text-2xl font-black tracking-tighter text-destructive">Critical: Purge Registry?</DialogTitle>
                    <DialogDescription class="font-bold text-warm-muted dark:text-dark-muted">
                        This action will permanently redact <strong class="text-warm-text dark:text-dark-text">{{ student.user.name }}</strong> from all academic and biological databases. This operation is irreversible.
                    </DialogDescription>
                </DialogHeader>
                <DialogFooter class="gap-3">
                    <Button variant="outline" @click="showDeleteDialog = false" class="rounded-xl font-black border-terracotta/20">Abort</Button>
                    <Button variant="destructive" @click="deleteStudent" class="rounded-xl font-black bg-destructive shadow-lg shadow-destructive/20">Purge Record</Button>
                </DialogFooter>
            </DialogContent>
        </Dialog>
    </Sidebar>
</template>