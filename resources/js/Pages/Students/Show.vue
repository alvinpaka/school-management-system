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
    UserCheck,      // ✅ added
    AlertCircle     // ✅ added
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
        case 'active':    return 'text-emerald-600 bg-emerald-500/10 border-emerald-500/20';
        case 'inactive':  return 'text-rose-600 bg-rose-500/10 border-rose-500/20';
        case 'graduated': return 'text-blue-600 bg-blue-500/10 border-blue-500/20';
        default:          return 'text-gray-600 bg-gray-500/10 border-gray-500/20';
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
                    <Button variant="ghost" size="sm" class="rounded-xl hover:bg-white/10">
                        <ArrowLeft class="w-4 h-4 mr-2" />
                        Students
                    </Button>
                </Link>
                <span class="text-gray-400">/</span>
                <span class="font-black text-sm uppercase tracking-wider text-gray-500">Student Intelligence</span>
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
                                    v-if="student.user?.photo"
                                    :src="`/storage/${student.user.photo}`" 
                                    :alt="student.user.name"
                                    class="object-cover"
                                />
                                <AvatarFallback class="text-5xl font-black bg-indigo-600 text-white rounded-[3rem] uppercase">
                                    {{ getInitials(student.user.name) }}
                                </AvatarFallback>
                            </Avatar>
                        </div>
                        
                        <div class="text-center md:text-left space-y-4">
                            <div class="flex items-center justify-center md:justify-start gap-2">
                                <Badge :class="['rounded-full px-4 py-1 h-7 border-0 font-black uppercase text-[9px] tracking-widest', getStatusColor(student.status)]">
                                    {{ student.status }}
                                </Badge>
                                <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Adm: #{{ student.admission_number }}</span>
                            </div>
                            <h1 class="text-4xl md:text-5xl font-black text-gray-900 dark:text-white tracking-tighter">
                                {{ student.user.name }}
                            </h1>
                            <div class="flex flex-wrap items-center justify-center md:justify-start gap-6">
                                <div class="flex items-center text-sm font-bold text-gray-500 uppercase tracking-wide">
                                    <GraduationCap class="w-5 h-5 mr-2 text-indigo-600" />
                                    {{ student.academic_class?.name || 'Class 10A' }}
                                    <span v-if="student.section?.name" class="ml-1 text-indigo-600">• Section {{ student.section.name }}</span>
                                </div>
                                <div class="flex items-center text-sm font-bold text-gray-500 uppercase tracking-wide">
                                    <ShieldCheck class="w-5 h-5 mr-2 text-emerald-600" />
                                    Roll: {{ student.roll_number }}
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div v-if="isAdminOrTeacher" class="flex items-center gap-3">
                        <Link :href="route('students.edit', student.id)">
                            <Button class="bg-indigo-600 hover:bg-indigo-700 text-white font-black rounded-2xl h-14 px-8 shadow-md transition-all">
                                <Edit class="w-5 h-5 mr-2" />
                                Edit Profile
                            </Button>
                        </Link>
                        <DropdownMenu>
                            <DropdownMenuTrigger as-child>
                                <Button variant="outline" class="rounded-2xl h-14 w-14 border-slate-200 dark:border-slate-800 text-gray-400">
                                    <MoreVertical class="w-6 h-6" />
                                </Button>
                            </DropdownMenuTrigger>
                            <DropdownMenuContent align="end" class="w-56 bg-white dark:bg-slate-900 rounded-2xl border-slate-200 dark:border-slate-800 p-2 shadow-xl">
                                <DropdownMenuItem class="rounded-xl h-10 font-bold gap-2 focus:bg-indigo-50 dark:focus:bg-indigo-900/20">
                                    <Download class="w-4 h-4" /> Download Dossier
                                </DropdownMenuItem>
                                <DropdownMenuItem @click="showDeleteDialog = true" class="rounded-xl h-10 font-bold gap-2 text-rose-600 focus:bg-rose-50 dark:focus:bg-rose-900/20">
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
                        { label: 'Attendance',   val: '98.2%',   icon: UserCheck,  color: 'indigo' },
                        { label: 'Average GPA',  val: '3.92',    icon: Award,      color: 'emerald' },
                        { label: 'Assignments',  val: '14/15',   icon: FileText,   color: 'orange' },
                        { label: 'Fees Status',  val: 'Cleared', icon: DollarSign, color: 'rose' }
                    ]"
                    :key="idx"
                    class="bg-white dark:bg-slate-900 p-6 rounded-[2.5rem] border border-slate-100 dark:border-slate-800 shadow-sm group hover:border-indigo-500/30 transition-all duration-300"
                >
                    <div class="flex items-center justify-between mb-4">
                        <!-- ✅ font-bold moved inside the array string -->
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
                    <TabsTrigger value="overview" class="rounded-xl px-8 font-black uppercase text-[10px] tracking-widest data-[state=active]:bg-white dark:data-[state=active]:bg-slate-700 data-[state=active]:text-indigo-600 data-[state=active]:shadow-sm">
                        Biological Intel
                    </TabsTrigger>
                    <TabsTrigger value="academic" class="rounded-xl px-8 font-black uppercase text-[10px] tracking-widest data-[state=active]:bg-white dark:data-[state=active]:bg-slate-700 data-[state=active]:text-indigo-600 data-[state=active]:shadow-sm">
                        Academic Log
                    </TabsTrigger>
                    <TabsTrigger value="parent" class="rounded-xl px-8 font-black uppercase text-[10px] tracking-widest data-[state=active]:bg-white dark:data-[state=active]:bg-slate-700 data-[state=active]:text-indigo-600 data-[state=active]:shadow-sm">
                        Family Portal
                    </TabsTrigger>
                </TabsList>

                <TabsContent value="overview" class="grid grid-cols-1 lg:grid-cols-2 gap-8 animate-fade-in-up mt-0">
                    <Card class="bg-white dark:bg-slate-900 shadow-sm border-slate-200 dark:border-slate-800 rounded-[3rem] overflow-hidden">
                        <CardHeader class="p-8 border-b border-slate-50 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/50">
                            <CardTitle class="text-2xl font-black tracking-tighter flex items-center gap-3">
                                <UserCircle class="w-6 h-6 text-indigo-600" />
                                Personal Specifications
                            </CardTitle>
                        </CardHeader>
                        <CardContent class="p-8 space-y-6">
                            <div
                                v-for="item in [
                                    { icon: Mail,       label: 'Communications', val: student.user.email },
                                    { icon: Smartphone, label: 'Mobile Link',    val: student.phone || 'N/A' },
                                    { icon: Cake,       label: 'Birth Registry', val: formatDate(student.date_of_birth), extra: calculateAge(student.date_of_birth) + ' years' },
                                    { icon: MapPin,     label: 'Postal Address', val: student.address || 'Unspecified' }
                                ]"
                                :key="item.label"
                                class="flex items-center gap-6 p-4 rounded-2xl hover:bg-slate-50 dark:hover:bg-slate-800 transition-all group"
                            >
                                <div class="w-12 h-12 rounded-xl bg-indigo-50 dark:bg-indigo-900/20 flex items-center justify-center text-indigo-600 group-hover:bg-indigo-600 group-hover:text-white transition-colors">
                                    <component :is="item.icon" class="w-5 h-5" />
                                </div>
                                <div class="flex-1">
                                    <p class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-1">{{ item.label }}</p>
                                    <p class="text-sm font-black text-gray-900 dark:text-white truncate">{{ item.val }}</p>
                                </div>
                                <Badge v-if="item.extra" class="bg-indigo-50 dark:bg-indigo-900/20 text-indigo-600 border-0 font-black">{{ item.extra }}</Badge>
                            </div>
                        </CardContent>
                    </Card>

                    <Card class="bg-white dark:bg-slate-900 shadow-sm border-slate-200 dark:border-slate-800 rounded-[3rem] overflow-hidden">
                        <CardHeader class="p-8 border-b border-slate-50 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/50">
                            <CardTitle class="text-2xl font-black tracking-tighter flex items-center gap-3">
                                <Heart class="w-6 h-6 text-rose-600" />
                                Health & Bio-Analytics
                            </CardTitle>
                        </CardHeader>
                        <CardContent class="p-8 space-y-6">
                            <div class="grid grid-cols-2 gap-4">
                                <div class="p-6 rounded-[2rem] bg-emerald-50 dark:bg-emerald-900/10 border border-emerald-100 dark:border-emerald-900/20 text-center">
                                    <p class="text-[10px] font-black uppercase opacity-60 text-emerald-600 mb-2">Blood Group</p>
                                    <p class="text-4xl font-black tracking-tighter text-emerald-600">{{ student.blood_group || 'O+' }}</p>
                                </div>
                                <div class="p-6 rounded-[2rem] bg-indigo-50 dark:bg-indigo-900/10 border border-indigo-100 dark:border-indigo-900/20 text-center text-indigo-600">
                                    <p class="text-[10px] font-black uppercase opacity-60 mb-2">Genotype</p>
                                    <p class="text-4xl font-black tracking-tighter">AA</p>
                                </div>
                            </div>
                            <div class="p-6 rounded-[2rem] bg-rose-50 dark:bg-rose-900/10 border border-rose-100 dark:border-rose-900/20">
                                <h4 class="font-black text-rose-600 uppercase text-[10px] tracking-widest mb-4 flex items-center gap-2">
                                    <AlertCircle class="w-4 h-4" />
                                    Critical Medical Alerts
                                </h4>
                                <p class="text-sm font-medium text-gray-600 dark:text-gray-400 leading-relaxed">
                                    {{ student.medical_conditions || 'None reported. Student is cleared for all physical activities.' }}
                                </p>
                            </div>
                        </CardContent>
                    </Card>
                </TabsContent>

                <TabsContent value="academic" class="animate-fade-in-up mt-0">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <Card class="bg-white dark:bg-slate-900 shadow-sm border-slate-200 dark:border-slate-800 rounded-[3rem] overflow-hidden">
                            <CardHeader class="p-8 border-b border-slate-50 dark:border-slate-800">
                                <CardTitle class="text-2xl font-black tracking-tighter flex items-center gap-3 text-indigo-600">
                                    <BookOpen class="w-6 h-6" />
                                    Academic Summary
                                </CardTitle>
                            </CardHeader>
                            <CardContent class="p-8 grid grid-cols-1 gap-6">
                                <div
                                    v-for="item in [
                                        { label: 'Admission Date',   val: formatDate(student.admission_date) },
                                        { label: 'Academic Year',    val: '2025/2026' },
                                        { label: 'Section Capacity', val: '42 / 45' },
                                        { label: 'Attendance Rate',  val: '98.5%' }
                                    ]"
                                    :key="item.label"
                                    class="flex justify-between items-center p-4 bg-slate-50 dark:bg-slate-800 rounded-2xl border border-slate-100 dark:border-slate-700"
                                >
                                    <span class="text-xs font-black uppercase tracking-widest text-gray-500">{{ item.label }}</span>
                                    <span class="text-sm font-black text-gray-900 dark:text-white">{{ item.val }}</span>
                                </div>
                            </CardContent>
                        </Card>
                        
                        <!-- Performance Graph Mockup -->
                        <div class="bg-indigo-600 rounded-[3rem] p-8 flex flex-col justify-center items-center text-center text-white shadow-lg overflow-hidden relative group">
                            <div class="relative z-10 w-full space-y-6">
                                <h4 class="text-2xl font-black tracking-tighter">Learning Velocity</h4>
                                <div class="flex items-end justify-center gap-4 h-32">
                                    <div v-for="h in [0.4, 0.6, 1, 0.8, 0.9]" :key="h" class="w-6 bg-white/20 rounded-t-lg transition-all hover:bg-white" :style="{ height: `${h * 100}%` }"></div>
                                </div>
                                <p class="text-xs font-bold opacity-80 uppercase tracking-widest">Growth Curve: +15% this term</p>
                            </div>
                        </div>
                    </div>
                </TabsContent>

                <TabsContent value="parent" class="animate-fade-in-up mt-0">
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                        <div class="lg:col-span-2">
                            <Card class="bg-white dark:bg-slate-900 shadow-sm border-slate-200 dark:border-slate-800 rounded-[3rem] overflow-hidden">
                                <CardHeader class="p-8 border-b border-slate-50 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/50">
                                    <CardTitle class="text-2xl font-black tracking-tighter flex items-center gap-3">
                                        <Users class="w-6 h-6 text-indigo-600" />
                                        Primary Guardian Details
                                    </CardTitle>
                                </CardHeader>
                                <CardContent class="p-8">
                                    <div class="flex items-center gap-8 mb-8">
                                        <Avatar class="w-24 h-24 rounded-[2rem] border-2 border-slate-100 dark:border-slate-800">
                                            <AvatarFallback class="bg-indigo-600 text-white font-black text-2xl uppercase">
                                                {{ student.parent_user?.user?.name ? getInitials(student.parent_user.user.name) : 'GR' }}
                                            </AvatarFallback>
                                        </Avatar>
                                        <div>
                                            <h3 class="text-2xl font-black text-gray-900 dark:text-white tracking-tighter leading-none mb-2">
                                                {{ student.parent_user?.user?.name || student.parent_name }}
                                            </h3>
                                            <Badge class="bg-indigo-50 dark:bg-indigo-900/20 text-indigo-600 border-0 font-black uppercase tracking-widest text-[9px]">
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
                                            class="p-4 bg-slate-50 dark:bg-slate-800 rounded-2xl border border-slate-100 dark:border-slate-700"
                                        >
                                            <div class="flex items-center gap-3 mb-1">
                                                <component :is="info.icon" class="w-4 h-4 text-gray-400" />
                                                <span class="text-[9px] font-black uppercase tracking-widest text-gray-500">{{ info.label }}</span>
                                            </div>
                                            <p class="text-sm font-black text-gray-900 dark:text-white">{{ info.val }}</p>
                                        </div>
                                    </div>
                                </CardContent>
                            </Card>
                        </div>
                        
                        <div class="bg-white dark:bg-slate-900 rounded-[3rem] border border-slate-200 dark:border-slate-800 p-8 flex flex-col items-center justify-center text-center space-y-6 shadow-sm">
                            <div class="w-20 h-20 rounded-[2rem] bg-indigo-50 dark:bg-indigo-900/20 flex items-center justify-center text-indigo-600">
                                <History class="w-10 h-10" />
                            </div>
                            <div>
                                <h4 class="text-xl font-black tracking-tighter">Family History</h4>
                                <p class="text-xs font-bold text-gray-500 leading-relaxed max-w-[200px] mt-2">
                                    3 active siblings currently enrolled in the EdManage ecosystem.
                                </p>
                            </div>
                            <Button variant="outline" class="w-full rounded-2xl h-12 font-black border-indigo-100 text-indigo-600 hover:bg-indigo-50">
                                Link Siblings
                            </Button>
                        </div>
                    </div>
                </TabsContent>
            </Tabs>
        </div>

        <!-- Purge Registry Confirmation -->
        <Dialog v-model:open="showDeleteDialog">
            <DialogContent class="bg-white dark:bg-slate-900 rounded-[2.5rem] border-slate-200 dark:border-slate-800 shadow-2xl">
                <DialogHeader>
                    <DialogTitle class="text-2xl font-black tracking-tighter text-rose-600">Critical: Purge Registry?</DialogTitle>
                    <DialogDescription class="font-bold text-gray-500">
                        This action will permanently redact <strong>{{ student.user.name }}</strong> from all academic and biological databases. This operation is irreversible.
                    </DialogDescription>
                </DialogHeader>
                <DialogFooter class="gap-3">
                    <Button variant="outline" @click="showDeleteDialog = false" class="rounded-xl font-black border-slate-200 dark:border-slate-800">Abort</Button>
                    <Button variant="destructive" @click="deleteStudent" class="rounded-xl font-black bg-rose-600 shadow-lg shadow-rose-500/20">Purge Record</Button>
                </DialogFooter>
            </DialogContent>
        </Dialog>
    </Sidebar>
</template>

<style scoped>
.animate-fade-in-up {
    animation: fadeInUp 0.5s ease-out forwards;
}

@keyframes fadeInUp {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}
</style>