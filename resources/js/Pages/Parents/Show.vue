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
        case 'father': return 'text-terracotta bg-terracotta/10 border-terracotta/20';
        case 'mother': return 'text-forest bg-forest/10 border-forest/20';
        default: return 'text-amber bg-amber/10 border-amber/20';
    }
};
</script>

<template>
    <Head :title="parent.user?.name + ' - Guardian Intelligence'" />

    <Sidebar>
        <template #header-title>
            <div class="flex items-center gap-2">
                <Link :href="route('parents.index')">
                    <Button variant="ghost" size="sm" class="rounded-xl hover:bg-terracotta/10 text-warm-text text-dark-text">
                        <ArrowLeft class="w-4 h-4 mr-2" />
                        Guardians
                    </Button>
                </Link>
                <span class="text-warm-muted text-dark-muted">/</span>
                <span class="font-black text-sm uppercase tracking-wider text-warm-muted text-dark-muted">Family Intelligence</span>
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
                                    v-if="parent.user?.photo"
                                    :src="`/storage/${parent.user.photo}`" 
                                    :alt="parent.user?.name"
                                    class="object-cover"
                                />
                                <AvatarFallback class="text-5xl font-black bg-terracotta text-white rounded-[3rem] uppercase">
                                    {{ getInitials(parent.user?.name) }}
                                </AvatarFallback>
                            </Avatar>
                        </div>
                        
                        <div class="text-center md:text-left space-y-4">
                            <div class="flex items-center justify-center md:justify-start gap-2">
                                <Badge :class="['rounded-full px-4 py-1 h-7 border-0 font-black uppercase text-[9px] tracking-widest', getRelationshipColor(parent.relationship_to_student)]">
                                    {{ parent.relationship_to_student || 'Guardian' }}
                                </Badge>
                                <span class="text-[10px] font-black text-warm-muted text-dark-muted uppercase tracking-widest">Verified Sponsor</span>
                            </div>
                            <h1 class="text-4xl md:text-5xl font-black text-warm-text text-dark-text tracking-tighter">
                                {{ parent.user?.name || 'Unknown' }}
                                <ShieldCheck class="inline-block w-8 h-8 text-forest ml-2" />
                            </h1>
                            <div class="flex flex-wrap items-center justify-center md:justify-start gap-6">
                                <div class="flex items-center text-sm font-bold text-warm-muted text-dark-muted uppercase tracking-wide">
                                    <Briefcase class="w-5 h-5 mr-2 text-terracotta" />
                                    {{ parent.occupation || 'Professional' }}
                                </div>
                                <div class="flex items-center text-sm font-bold text-warm-muted text-dark-muted uppercase tracking-wide">
                                    <Baby class="w-5 h-5 mr-2 text-forest" />
                                    {{ parent.students?.length || 0 }} Protected Dependents
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div v-if="isAdmin" class="flex items-center gap-3">
                        <Link :href="editRoute">
                            <Button class="accent-terracotta text-white font-black rounded-2xl h-14 px-8 shadow-md shadow-terracotta/30 transition-all">
                                <Edit class="w-5 h-5 mr-2" />
                                Modify Profile
                            </Button>
                        </Link>
                        <DropdownMenu>
                            <DropdownMenuTrigger as-child>
                                <Button variant="outline" class="rounded-2xl h-14 w-14 border-terracotta/20 text-warm-muted text-dark-muted">
                                    <MoreVertical class="w-6 h-6" />
                                </Button>
                            </DropdownMenuTrigger>
                            <DropdownMenuContent align="end" class="w-56 card-warm rounded-2xl p-2 shadow-xl">
                                <DropdownMenuItem class="rounded-xl h-10 font-bold gap-2 hover:bg-terracotta/10">
                                    <Download class="w-4 h-4" /> Account Statement
                                </DropdownMenuItem>
                                <DropdownMenuItem @click="showDeleteDialog = true" class="rounded-xl h-10 font-bold gap-2 text-destructive hover:bg-destructive/10">
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
                        { label: 'Dependents',     val: parent.students?.length || 0, icon: Baby,       color: 'terracotta' },
                        { label: 'Security Status', val: 'Verified',                  icon: ShieldCheck, color: 'forest' },
                        { label: 'Account Balance', val: 'Cleared',                   icon: DollarSign,  color: 'terracotta' },
                        { label: 'Recent Contact',  val: '2 Days ago',                icon: History,     color: 'amber' }
                    ]"
                    :key="idx"
                    class="card-warm p-6 rounded-[2.5rem] group transition-all duration-300"
                >
                    <div class="flex items-center justify-between mb-4">
                        <div :class="['w-12 h-12 rounded-2xl flex items-center justify-center transition-colors shadow-inner font-bold', `bg-${stat.color}/10 text-${stat.color}`]">
                            <component :is="stat.icon" class="w-6 h-6" />
                        </div>
                        <Badge class="bg-terracotta/5 border-0 text-warm-muted text-dark-muted font-black text-[9px] uppercase tracking-widest">{{ stat.label }}</Badge>
                    </div>
                    <h3 class="text-3xl font-black text-warm-text text-dark-text tracking-tighter">{{ stat.val }}</h3>
                </div>
            </div>

            <!-- Content Intelligence Tabs -->
            <Tabs v-model="activeTab" class="w-full">
                <TabsList class="p-1 bg-terracotta/5 border border-terracotta/20 rounded-2xl mb-8 inline-flex h-14">
                    <TabsTrigger value="overview" class="rounded-xl px-8 font-black uppercase text-[10px] tracking-widest data-[state=active]:bg-white dark:data-[state=active]:bg-dark-bg data-[state=active]:text-terracotta data-[state=active]:shadow-sm">
                        Biological Intel
                    </TabsTrigger>
                    <TabsTrigger value="children" class="rounded-xl px-8 font-black uppercase text-[10px] tracking-widest data-[state=active]:bg-white dark:data-[state=active]:bg-dark-bg data-[state=active]:text-terracotta data-[state=active]:shadow-sm">
                        Protected Dependents
                    </TabsTrigger>
                </TabsList>

                <TabsContent value="overview" class="grid grid-cols-1 lg:grid-cols-2 gap-8 animate-fade-in-up mt-0">
                    <div class="card-warm rounded-[3rem] overflow-hidden">
                        <div class="p-8 border-b border-terracotta/20 bg-terracotta/5">
                            <h3 class="text-2xl font-black tracking-tighter flex items-center gap-3 text-warm-text text-dark-text">
                                <UserCircle class="w-6 h-6 text-terracotta" />
                                Guardian Specifications
                            </h3>
                        </div>
                        <div class="p-8 space-y-6">
                            <div
                                v-for="item in [
                                    { icon: Mail,       label: 'Official Communications', val: parent.user?.email || 'N/A' },
                                    { icon: Smartphone, label: 'Mobile Link',             val: parent.phone || 'N/A' },
                                    { icon: Building,   label: 'Professional Role',       val: parent.occupation || 'Private Sector' },
                                    { icon: MapPin,     label: 'Residential Mapping',     val: parent.address || 'Unspecified' }
                                ]"
                                :key="item.label"
                                class="flex items-center gap-6 p-4 rounded-2xl hover:bg-terracotta/5 transition-all group"
                            >
                                <div class="w-12 h-12 rounded-xl bg-terracotta/10 flex items-center justify-center text-terracotta group-hover:bg-terracotta group-hover:text-white transition-colors shadow-inner">
                                    <component :is="item.icon" class="w-5 h-5" />
                                </div>
                                <div class="flex-1">
                                    <p class="text-[10px] font-black uppercase tracking-widest text-warm-muted text-dark-muted mb-1">{{ item.label }}</p>
                                    <p class="text-sm font-black text-warm-text text-dark-text truncate">{{ item.val }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-warm rounded-[3rem] overflow-hidden">
                        <div class="p-8 border-b border-terracotta/20 bg-terracotta/5">
                            <h3 class="text-2xl font-black tracking-tighter flex items-center gap-3 text-warm-text text-dark-text">
                                <Activity class="w-6 h-6 text-forest" />
                                Engagement Analysis
                            </h3>
                        </div>
                        <div class="p-8 space-y-8">
                            <div class="p-8 rounded-[2.5rem] accent-terracotta text-white shadow-lg text-center relative overflow-hidden group">
                                <p class="text-[10px] font-black uppercase tracking-widest opacity-80 mb-2">Portal Engagement</p>
                                <h3 class="text-5xl font-black italic tracking-tighter">High</h3>
                                <p class="text-xs font-bold mt-4 opacity-70">Last successful authorization verified 14 minutes ago.</p>
                            </div>
                            
                            <div class="p-6 rounded-[2rem] bg-amber/5 border border-amber/20">
                                <h4 class="font-black text-amber uppercase text-[10px] tracking-widest mb-4 flex items-center gap-2">
                                    <AlertCircle class="w-4 h-4" />
                                    Account Notes
                                </h4>
                                <p class="text-sm font-medium text-warm-muted text-dark-muted leading-relaxed italic">
                                    Guardian prefers communication via official mobile link during business hours. Verified payment initiator.
                                </p>
                            </div>
                        </div>
                    </div>
                </TabsContent>

                <TabsContent value="children" class="animate-fade-in-up mt-0">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <div
                            v-for="student in parent.students"
                            :key="student.id"
                            class="card-warm p-6 rounded-[2.5rem] group transition-all duration-300 relative overflow-hidden"
                        >
                            <div class="flex flex-col items-center text-center space-y-4">
                                <Avatar class="w-24 h-24 rounded-[2rem] border-2 border-terracotta/20 shadow-md">
                                    <AvatarImage v-if="student.user?.photo" :src="`/storage/${student.user.photo}`" />
                                    <AvatarFallback class="bg-terracotta text-white font-black text-2xl uppercase">{{ getInitials(student.user?.name) }}</AvatarFallback>
                                </Avatar>
                                <div>
                                    <h4 class="text-xl font-black text-warm-text text-dark-text tracking-tight leading-tight">{{ student.user?.name }}</h4>
                                    <p class="text-[10px] font-black text-terracotta uppercase tracking-widest mt-1">{{ student.academic_class?.name || 'Class 10A' }}</p>
                                </div>
                                <div class="flex gap-2 w-full pt-4">
                                    <Link :href="route('students.show', student.id)" class="w-full">
                                        <Button variant="outline" class="w-full rounded-xl h-10 font-black text-[10px] uppercase tracking-widest border-terracotta/20 hover:bg-terracotta/10">
                                            Intel Dashboard
                                        </Button>
                                    </Link>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Add Dependent -->
                        <div v-if="isAdmin" class="p-6 rounded-[3rem] border border-dashed border-terracotta/20 flex flex-col items-center justify-center text-center space-y-4 hover:bg-terracotta/5 transition-all cursor-pointer">
                            <div class="w-16 h-16 rounded-full bg-terracotta/5 flex items-center justify-center text-warm-muted text-dark-muted">
                                <Users class="w-8 h-8" />
                            </div>
                            <p class="text-xs font-black uppercase tracking-widest text-warm-muted text-dark-muted">Add Dependent</p>
                        </div>
                    </div>
                </TabsContent>
            </Tabs>
        </div>

        <!-- Purge Registry Confirmation -->
        <Dialog v-model:open="showDeleteDialog">
            <DialogContent class="card-warm rounded-[2.5rem] shadow-2xl">
                <DialogHeader>
                    <DialogTitle class="text-2xl font-black tracking-tighter text-destructive">Critical: Revoke Authorization?</DialogTitle>
                    <DialogDescription class="font-bold text-warm-muted text-dark-muted">
                        This action will redact <strong class="text-warm-text text-dark-text">{{ parent.user?.name }}</strong> from the secure guardian database. Linked dependents will lose their primary sponsor association. This is irreversible.
                    </DialogDescription>
                </DialogHeader>
                <DialogFooter class="gap-3">
                    <Button variant="outline" @click="showDeleteDialog = false" class="rounded-xl font-black border-terracotta/20">Abort</Button>
                    <Button variant="destructive" @click="deleteParent" class="rounded-xl font-black bg-destructive shadow-lg shadow-destructive/20">Decommission Auth</Button>
                </DialogFooter>
            </DialogContent>
        </Dialog>
    </Sidebar>
</template>