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
    MapPin,
    Edit,
    Trash2,
    Users,
    Home,
    Briefcase,
    Heart,
    MoreVertical,
    Download,
    Share2,
    FileText,
    Activity,
    UserCircle,
    Baby,
    GraduationCap,
    ShieldCheck,
    Smartphone,
    Building,
    ExternalLink,
    AlertCircle,
    History,
    DollarSign
} from 'lucide-vue-next';

const props = defineProps({
    parent: {
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

const editRoute = computed(() => {
    if (!props.parent?.id) return '#';
    return route('parents.edit', { parent: props.parent.id });
});

const deleteRoute = computed(() => {
    if (!props.parent?.id) return '#';
    return route('parents.destroy', { parent: props.parent.id });
});

const deleteParent = () => {
    showDeleteDialog.value = false;
    router.delete(deleteRoute.value);
};

const getInitials = (name) => {
    return name?.split(' ').map(word => word[0]).join('').toUpperCase().slice(0, 2) || 'GR';
};

const getRelationshipColor = (relationship) => {
    switch (relationship?.toLowerCase()) {
        case 'father': return 'text-blue-600 bg-blue-500/10 border-blue-500/20';
        case 'mother': return 'text-rose-600 bg-rose-500/10 border-rose-500/20';
        default: return 'text-purple-600 bg-purple-500/10 border-purple-500/20';
    }
};
</script>

<template>
    <Head :title="parent.user?.name + ' - Guardian Intelligence'" />

    <Sidebar>
        <template #header-title>
            <div class="flex items-center gap-2">
                <Link :href="route('parents.index')">
                    <Button variant="ghost" size="sm" class="rounded-xl hover:bg-white/10">
                        <ArrowLeft class="w-4 h-4 mr-2" />
                        Guardians
                    </Button>
                </Link>
                <span class="text-gray-400">/</span>
                <span class="font-black text-sm uppercase tracking-wider text-gray-500">Family Intelligence</span>
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
                                    v-if="parent.user?.photo"
                                    :src="`/storage/${parent.user.photo}`" 
                                    :alt="parent.user?.name"
                                    class="object-cover"
                                />
                                <AvatarFallback class="text-5xl font-black bg-indigo-600 text-white rounded-[3rem] uppercase">
                                    {{ getInitials(parent.user?.name) }}
                                </AvatarFallback>
                            </Avatar>
                        </div>
                        
                        <div class="text-center md:text-left space-y-4">
                            <div class="flex items-center justify-center md:justify-start gap-2">
                                <Badge :class="['rounded-full px-4 py-1 h-7 border-0 font-black uppercase text-[9px] tracking-widest', getRelationshipColor(parent.relationship_to_student)]">
                                    {{ parent.relationship_to_student || 'Guardian' }}
                                </Badge>
                                <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Verified Sponsor</span>
                            </div>
                            <h1 class="text-4xl md:text-5xl font-black text-gray-900 dark:text-white tracking-tighter">
                                {{ parent.user?.name || 'Unknown' }}
                                <ShieldCheck class="inline-block w-8 h-8 text-indigo-500 ml-2" />
                            </h1>
                            <div class="flex flex-wrap items-center justify-center md:justify-start gap-6">
                                <div class="flex items-center text-sm font-bold text-gray-500 uppercase tracking-wide">
                                    <Briefcase class="w-5 h-5 mr-2 text-indigo-600" />
                                    {{ parent.occupation || 'Professional' }}
                                </div>
                                <div class="flex items-center text-sm font-bold text-gray-500 uppercase tracking-wide">
                                    <Baby class="w-5 h-5 mr-2 text-purple-600" />
                                    {{ parent.students?.length || 0 }} Protected Dependents
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div v-if="isAdmin" class="flex items-center gap-3">
                        <Link :href="editRoute">
                            <Button class="bg-indigo-600 hover:bg-indigo-700 text-white font-black rounded-2xl h-14 px-8 shadow-md transition-all">
                                <Edit class="w-5 h-5 mr-2" />
                                Modify Profile
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
                                    <Download class="w-4 h-4" /> Account Statement
                                </DropdownMenuItem>
                                <DropdownMenuItem @click="showDeleteDialog = true" class="rounded-xl h-10 font-bold gap-2 text-rose-600 focus:bg-rose-50 dark:focus:bg-rose-900/20">
                                    <Trash2 class="w-4 h-4" /> Revoke Access
                                </DropdownMenuItem>
                            </DropdownMenuContent>
                        </DropdownMenu>
                    </div>
                </div>
            </div>

            <!-- Family Analytics -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div
                    v-for="(stat, idx) in [
                        { label: 'Dependents',     val: parent.students?.length || 0, icon: Baby,       color: 'purple' },
                        { label: 'Security Status', val: 'Verified',                  icon: ShieldCheck, color: 'emerald' },
                        { label: 'Account Balance', val: 'Cleared',                   icon: DollarSign,  color: 'indigo' },
                        { label: 'Recent Contact',  val: '2 Days ago',                icon: History,     color: 'slate' }
                    ]"
                    :key="idx"
                    class="bg-white dark:bg-slate-900 p-6 rounded-[2.5rem] border border-slate-100 dark:border-slate-800 shadow-sm group hover:border-indigo-500/30 transition-all duration-300"
                >
                    <div class="flex items-center justify-between mb-4">
                        <!-- ✅ font-bold moved inside the array, syntax is now valid -->
                        <div :class="['w-12 h-12 rounded-2xl flex items-center justify-center transition-colors shadow-inner font-bold', `bg-${stat.color}-50 dark:bg-${stat.color}-900/10 text-${stat.color}-600`]">
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
                    <TabsTrigger value="children" class="rounded-xl px-8 font-black uppercase text-[10px] tracking-widest data-[state=active]:bg-white dark:data-[state=active]:bg-slate-700 data-[state=active]:text-indigo-600 data-[state=active]:shadow-sm">
                        Protected Dependents
                    </TabsTrigger>
                </TabsList>

                <TabsContent value="overview" class="grid grid-cols-1 lg:grid-cols-2 gap-8 animate-fade-in-up mt-0">
                    <Card class="bg-white dark:bg-slate-900 shadow-sm border-slate-200 dark:border-slate-800 rounded-[3rem] overflow-hidden">
                        <CardHeader class="p-8 border-b border-slate-50 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/50">
                            <CardTitle class="text-2xl font-black tracking-tighter flex items-center gap-3">
                                <UserCircle class="w-6 h-6 text-indigo-600" />
                                Guardian Specifications
                            </CardTitle>
                        </CardHeader>
                        <CardContent class="p-8 space-y-6">
                            <div
                                v-for="item in [
                                    { icon: Mail,       label: 'Official Communications', val: parent.user?.email || 'N/A' },
                                    { icon: Smartphone, label: 'Mobile Link',             val: parent.phone || 'N/A' },
                                    { icon: Building,   label: 'Professional Role',       val: parent.occupation || 'Private Sector' },
                                    { icon: MapPin,     label: 'Residential Mapping',     val: parent.address || 'Unspecified' }
                                ]"
                                :key="item.label"
                                class="flex items-center gap-6 p-4 rounded-2xl hover:bg-slate-50 dark:hover:bg-slate-800 transition-all group"
                            >
                                <div class="w-12 h-12 rounded-xl bg-indigo-50 dark:bg-indigo-900/20 flex items-center justify-center text-indigo-600 group-hover:bg-indigo-600 group-hover:text-white transition-colors shadow-inner">
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
                                <Activity class="w-6 h-6 text-purple-600" />
                                Engagement Analysis
                            </CardTitle>
                        </CardHeader>
                        <CardContent class="p-8 space-y-8">
                            <div class="p-8 rounded-[2.5rem] bg-indigo-600 text-white shadow-lg text-center relative overflow-hidden group">
                                <p class="text-[10px] font-black uppercase tracking-widest opacity-80 mb-2">Portal Engagement</p>
                                <h3 class="text-5xl font-black italic tracking-tighter">High</h3>
                                <p class="text-xs font-bold mt-4 opacity-70">Last successful authorization verified 14 minutes ago.</p>
                            </div>
                            
                            <div class="p-6 rounded-[2rem] bg-amber-50 dark:bg-amber-900/10 border border-amber-100 dark:border-amber-900/20">
                                <h4 class="font-black text-amber-600 uppercase text-[10px] tracking-widest mb-4 flex items-center gap-2">
                                    <AlertCircle class="w-4 h-4" />
                                    Account Notes
                                </h4>
                                <p class="text-sm font-medium text-gray-600 dark:text-gray-400 leading-relaxed italic">
                                    Guardian prefers communication via official mobile link during business hours. Verified payment initiator.
                                </p>
                            </div>
                        </CardContent>
                    </Card>
                </TabsContent>

                <TabsContent value="children" class="animate-fade-in-up mt-0">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <div
                            v-for="student in parent.students"
                            :key="student.id"
                            class="bg-white dark:bg-slate-900 p-6 rounded-[2.5rem] border border-slate-100 dark:border-slate-800 hover:border-indigo-500/30 transition-all duration-300 group relative overflow-hidden shadow-sm"
                        >
                            <div class="flex flex-col items-center text-center space-y-4">
                                <Avatar class="w-24 h-24 rounded-[2rem] border-2 border-slate-100 dark:border-slate-800 shadow-md">
                                    <AvatarImage v-if="student.user?.photo" :src="`/storage/${student.user.photo}`" />
                                    <AvatarFallback class="bg-indigo-600 text-white font-black text-2xl uppercase">{{ getInitials(student.user?.name) }}</AvatarFallback>
                                </Avatar>
                                <div>
                                    <h4 class="text-xl font-black text-gray-900 dark:text-white tracking-tight leading-tight">{{ student.user?.name }}</h4>
                                    <p class="text-[10px] font-black text-indigo-600 uppercase tracking-widest mt-1">{{ student.academic_class?.name || 'Class 10A' }}</p>
                                </div>
                                <div class="flex gap-2 w-full pt-4">
                                    <Link :href="route('students.show', student.id)" class="w-full">
                                        <Button variant="outline" class="w-full rounded-xl h-10 font-black text-[10px] uppercase tracking-widest border-slate-100 dark:border-slate-800">
                                            Intel Dashboard
                                        </Button>
                                    </Link>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Add Dependent -->
                        <div v-if="isAdmin" class="p-6 rounded-[3rem] border border-dashed border-slate-200 dark:border-slate-800 flex flex-col items-center justify-center text-center space-y-4 hover:bg-slate-50 dark:hover:bg-slate-800 transition-all cursor-pointer">
                            <div class="w-16 h-16 rounded-full bg-slate-50 dark:bg-slate-800 flex items-center justify-center text-gray-400">
                                <Users class="w-8 h-8" />
                            </div>
                            <p class="text-xs font-black uppercase tracking-widest text-gray-400">Add Dependent</p>
                        </div>
                    </div>
                </TabsContent>
            </Tabs>
        </div>

        <!-- Purge Registry Confirmation -->
        <Dialog v-model:open="showDeleteDialog">
            <DialogContent class="bg-white dark:bg-slate-900 rounded-[2.5rem] border-slate-200 dark:border-slate-800 shadow-2xl">
                <DialogHeader>
                    <DialogTitle class="text-2xl font-black tracking-tighter text-rose-600">Critical: Revoke Authorization?</DialogTitle>
                    <DialogDescription class="font-bold text-gray-500">
                        This action will redact <strong>{{ parent.user?.name }}</strong> from the secure guardian database. Linked dependents will lose their primary sponsor association. This is irreversible.
                    </DialogDescription>
                </DialogHeader>
                <DialogFooter class="gap-3">
                    <Button variant="outline" @click="showDeleteDialog = false" class="rounded-xl font-black border-slate-200 dark:border-slate-800">Abort</Button>
                    <Button variant="destructive" @click="deleteParent" class="rounded-xl font-black bg-rose-600 shadow-lg shadow-rose-500/20">Decommission Auth</Button>
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