<script setup>
import { Head, Link, router, usePage, useForm } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import Sidebar from '@/Components/Sidebar.vue';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Label } from '@/components/ui/label';
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
    AlertCircle,
    AlertTriangle,
    Plus,
    X
} from 'lucide-vue-next';

const props = defineProps({
    teacher: {
        type: Object,
        required: true
    },
    availableClasses: {
        type: Array,
        default: () => []
    },
    availableSubjects: {
        type: Array,
        default: () => []
    }
});

const page = usePage();
const currentUser = computed(() => page.props.auth.user);
const userRoles = computed(() => currentUser.value?.roles || []);
const isAdmin = computed(() => userRoles.value.includes('admin'));

const activeTab = ref('overview');
const showDeleteDialog = ref(false);
const showAssignClassDialog = ref(false);
const showAssignSubjectDialog = ref(false);
const showRemoveClassDialog = ref(false);
const showEditClassTeacherDialog = ref(false);
const showEditSubjectsDialog = ref(false);
const selectedClassId = ref('');
const selectedSubjectId = ref('');
const classToRemove = ref(null);
const classToEdit = ref(null);
const classToEditSubjects = ref(null);

const assignClassForm = useForm({
    class_id: '',
    section_id: '',
    is_class_teacher: false,
    subject_ids: []
});

const editClassTeacherForm = useForm({
    class_id: '',
    section_id: '',
    is_class_teacher: false
});

const editSubjectsForm = useForm({
    class_id: '',
    section_id: '',
    is_class_teacher: false,
    subject_ids: []
});

const removeClassForm = useForm({
    class_id: '',
    section_id: ''
});

const assignSubjectForm = useForm({
    subject_id: '',
    academic_class_id: ''
});

const removeSubjectForm = useForm({
    subject_id: '',
    academic_class_id: ''
});

const openAssignDialog = () => {
    selectedClassId.value = '';
    assignClassForm.class_id = '';
    assignClassForm.section_id = '';
    assignClassForm.is_class_teacher = false;
    assignClassForm.subject_ids = [];
    showAssignClassDialog.value = true;
};

const openEditClassTeacherDialog = (cls) => {
    classToEdit.value = cls;
    // Reset form first to ensure clean state
    editClassTeacherForm.reset();
    // Explicitly convert to boolean - handles both boolean true and integer 1 from database
    const isClassTeacher = cls.is_class_teacher === true || cls.is_class_teacher === 1 || cls.is_class_teacher === '1';
    editClassTeacherForm.class_id = cls.id;
    editClassTeacherForm.section_id = cls.section_id || '';
    editClassTeacherForm.is_class_teacher = isClassTeacher;
    showEditClassTeacherDialog.value = true;
};

const updateClassTeacherStatus = () => {
    editClassTeacherForm.post(route('teachers.assign-class', props.teacher.id), {
        preserveState: true,
        preserveScroll: true,
        onSuccess: () => {
            showEditClassTeacherDialog.value = false;
            classToEdit.value = null;
            editClassTeacherForm.reset();
        },
    });
};

const openEditSubjectsDialog = (cls) => {
    classToEditSubjects.value = cls;
    editSubjectsForm.reset();
    editSubjectsForm.class_id = cls.id;
    editSubjectsForm.section_id = cls.section_id || '';
    editSubjectsForm.is_class_teacher = cls.is_class_teacher || false;
    // Pre-select already assigned subjects for this class-section
    const assignedSubjects = getSubjectsForClass(cls.id, cls.section_id);
    editSubjectsForm.subject_ids = assignedSubjects.map(s => s.id);
    showEditSubjectsDialog.value = true;
};

const updateSubjects = () => {
    editSubjectsForm.post(route('teachers.assign-class', props.teacher.id), {
        preserveState: true,
        preserveScroll: true,
        onSuccess: () => {
            showEditSubjectsDialog.value = false;
            classToEditSubjects.value = null;
            editSubjectsForm.reset();
        },
    });
};

const openAssignSubjectDialog = () => {
    selectedSubjectId.value = '';
    assignSubjectForm.subject_id = '';
    assignSubjectForm.academic_class_id = '';
    showAssignSubjectDialog.value = true;
};

const assignClass = () => {
    assignClassForm.post(route('teachers.assign-class', props.teacher.id), {
        onSuccess: () => {
            showAssignClassDialog.value = false;
            assignClassForm.reset();
        },
    });
};

const assignSubject = () => {
    assignSubjectForm.post(route('teachers.assign-subject', props.teacher.id), {
        onSuccess: () => {
            showAssignSubjectDialog.value = false;
            assignSubjectForm.reset();
        },
    });
};

const openRemoveClassDialog = (cls) => {
    classToRemove.value = cls;
    removeClassForm.class_id = cls.id;
    removeClassForm.section_id = cls.section_id || '';
    showRemoveClassDialog.value = true;
};

const confirmRemoveClass = () => {
    removeClassForm.post(route('teachers.remove-class', props.teacher.id), {
        onSuccess: () => {
            showRemoveClassDialog.value = false;
            classToRemove.value = null;
        },
    });
};

const removeSubject = (subjectId, classId = null) => {
    if (confirm('Are you sure you want to remove this subject from the teacher?')) {
        removeSubjectForm.subject_id = subjectId;
        removeSubjectForm.academic_class_id = classId;
        removeSubjectForm.post(route('teachers.remove-subject', props.teacher.id));
    }
};

// Filter out classes where teacher is assigned to ALL sections
const unassignedClasses = computed(() => {
    const assignedClassSections = props.teacher.classes?.map(c => ({
        classId: c.id,
        sectionId: c.section_id
    })) || [];
    
    return props.availableClasses.filter(cls => {
        // Get all assigned sections for this class
        const assignedSectionsForClass = assignedClassSections
            .filter(acs => acs.classId === cls.id)
            .map(acs => acs.sectionId);
        
        // If class has no sections, check if teacher is assigned to class without section
        if (!cls.sections || cls.sections.length === 0) {
            return !assignedSectionsForClass.includes(null) && !assignedSectionsForClass.includes('');
        }
        
        // Allow class if teacher isn't assigned to all its sections
        return assignedSectionsForClass.length < cls.sections.length;
    });
});

// Get available sections for selected class (filtering out already assigned)
const availableSectionsForSelectedClass = computed(() => {
    if (!assignClassForm.class_id) return [];
    
    const selectedClass = props.availableClasses.find(c => c.id === assignClassForm.class_id);
    if (!selectedClass?.sections) return [];
    
    // Get sections already assigned to this teacher for this class
    const assignedSectionIds = props.teacher.classes
        ?.filter(c => c.id === assignClassForm.class_id)
        .map(c => c.section_id) || [];
    
    // Filter out already assigned sections
    return selectedClass.sections.filter(s => !assignedSectionIds.includes(s.id));
});

// Filter out already assigned subjects (optionally filter by class too)
const unassignedSubjects = computed(() => {
    const assignedSubjectIds = props.teacher.subjects?.map(s => s.id) || [];
    return props.availableSubjects.filter(s => !assignedSubjectIds.includes(s.id));
});

// Get subjects assigned to a specific class-section
const getSubjectsForClass = (classId, sectionId = null) => {
    if (!props.teacher.subjects) return [];
    return props.teacher.subjects.filter(s => 
        s.academic_class_id === classId && 
        s.section_id === sectionId
    );
};

// Get subjects not yet assigned to selected class
const unassignedSubjectsForSelectedClass = computed(() => {
    if (!assignClassForm.class_id) return [];
    const assignedToClass = getSubjectsForClass(assignClassForm.class_id);
    const assignedIds = assignedToClass.map(s => s.id);
    return props.availableSubjects.filter(s => !assignedIds.includes(s.id));
});

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
                    <Button variant="ghost" size="sm" class="rounded-xl hover:bg-terracotta/10 text-warm-text text-dark-text">
                        <ArrowLeft class="w-4 h-4 mr-2" />
                        Faculty
                    </Button>
                </Link>
                <span class="text-warm-muted text-dark-muted">/</span>
                <span class="font-black text-sm uppercase tracking-wider text-warm-muted text-dark-muted">Academic Personnel Intelligence</span>
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
                                <span class="text-[10px] font-black text-warm-muted text-dark-muted uppercase tracking-widest">Employee: #{{ teacher.employee_id }}</span>
                            </div>
                            <h1 class="text-4xl md:text-5xl font-black text-warm-text text-dark-text tracking-tighter">
                                {{ teacher.user.name }}
                                <ShieldCheck class="inline-block w-8 h-8 text-forest ml-2" />
                            </h1>
                            <div class="flex flex-wrap items-center justify-center md:justify-start gap-6">
                                <div class="flex items-center text-sm font-bold text-warm-muted text-dark-muted uppercase tracking-wide">
                                    <GraduationCap class="w-5 h-5 mr-2 text-terracotta" />
                                    {{ teacher.role ? (teacher.role.charAt(0).toUpperCase() + teacher.role.slice(1)) : 'Teacher' }}
                                </div>
                                <div class="flex items-center text-sm font-bold text-warm-muted text-dark-muted uppercase tracking-wide">
                                    <Building class="w-5 h-5 mr-2 text-terracotta" />
                                    {{ teacher.specialization || 'Faculty of Science' }}
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
                                <Button variant="outline" class="rounded-2xl h-14 w-14 border-terracotta/20 text-warm-muted text-dark-muted">
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
                    { label: 'Role', val: teacher.role ? (teacher.role.charAt(0).toUpperCase() + teacher.role.slice(1)) : 'Teacher', icon: GraduationCap, color: 'terracotta' },
                    { label: 'Student Impact', val: teacher.total_students || '0', icon: Users, color: 'forest' },
                    { label: 'Classes', val: (teacher.classes?.length || 0) + ' Assigned', icon: BookOpen, color: 'amber' },
                    { label: 'Subjects', val: (new Set(teacher.subjects?.map(s => s.name) || [])).size + ' Teaching', icon: BookOpen, color: 'terracotta' }
                ]" :key="idx" class="card-warm p-6 rounded-[2.5rem] group transition-all duration-300">
                    <div class="flex items-center justify-between mb-4">
                        <div :class="['w-12 h-12 rounded-2xl flex items-center justify-center transition-colors font-bold', `bg-${stat.color}/10 text-${stat.color}`]">
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
                            <h3 class="text-2xl font-black tracking-tighter flex items-center gap-3 text-warm-text text-dark-text">
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
                                    <p class="text-[10px] font-black uppercase tracking-widest text-warm-muted text-dark-muted mb-1">{{ item.label }}</p>
                                    <p class="text-sm font-black text-warm-text text-dark-text truncate">{{ item.val }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-warm rounded-[3rem] overflow-hidden">
                        <div class="p-8 border-b border-terracotta/20 bg-terracotta/5">
                            <h3 class="text-2xl font-black tracking-tighter flex items-center gap-3 text-warm-text text-dark-text">
                                <AlertCircle class="w-6 h-6 text-amber" />
                                Emergency & Health Audit
                            </h3>
                        </div>
                        <div class="p-8 space-y-8">
                            <div class="p-8 rounded-[2.5rem] bg-terracotta/5 border border-terracotta/20 shadow-sm">
                                <p class="text-[10px] font-black uppercase tracking-widest text-warm-muted text-dark-muted mb-4">Secondary Contact Intelligence</p>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                    <div>
                                        <h4 class="text-xl font-black text-warm-text text-dark-text tracking-tight">{{ teacher.emergency_contact_name || 'Guardian One' }}</h4>
                                        <p class="text-xs font-bold text-terracotta uppercase tracking-widest">{{ teacher.emergency_contact_relationship || 'Emergency Contact' }}</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-lg font-black text-warm-text text-dark-text">{{ teacher.emergency_contact_phone || 'Unlinked' }}</p>
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
                                        <p class="text-[10px] font-black uppercase tracking-widest text-warm-muted text-dark-muted">Blood Registry</p>
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
                        <div class="lg:col-span-2 space-y-8">
                            <!-- Professional Credentials -->
                            <div class="card-warm rounded-[3rem] overflow-hidden">
                                <div class="p-8 border-b border-terracotta/20 bg-terracotta/5">
                                    <h3 class="text-2xl font-black tracking-tighter flex items-center gap-3 text-warm-text text-dark-text">
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
                                            <p class="text-[10px] font-black uppercase tracking-widest text-warm-muted text-dark-muted mb-1">{{ item.label }}</p>
                                            <p class="text-sm font-black text-warm-text text-dark-text leading-tight">{{ item.val }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Teaching Assignments (Combined Class + Subjects) -->
                            <div v-if="isAdmin" class="card-warm rounded-[3rem] overflow-hidden">
                                <div class="p-8 border-b border-terracotta/20 bg-terracotta/5 flex items-center justify-between">
                                    <h3 class="text-2xl font-black tracking-tighter flex items-center gap-3 text-warm-text text-dark-text">
                                        <GraduationCap class="w-6 h-6 text-terracotta" />
                                        Teaching Assignments
                                    </h3>
                                    <Button v-if="unassignedClasses.length > 0" @click="openAssignDialog" class="accent-terracotta text-white font-black rounded-2xl h-10 px-4 text-xs">
                                        <Plus class="w-4 h-4 mr-1" />
                                        Assign Class & Subject
                                    </Button>
                                </div>
                                <div class="p-8">
                                    <div v-if="teacher.classes?.length === 0" class="text-center py-8">
                                        <GraduationCap class="w-12 h-12 mx-auto text-warm-muted/30 mb-4" />
                                        <p class="text-sm font-bold text-warm-muted text-dark-muted">No teaching assignments</p>
                                        <p class="text-xs text-warm-muted/70 mt-1">Assign this teacher to classes with subjects they will teach</p>
                                    </div>
                                    <div v-else class="space-y-4">
                                        <div v-for="(cls, idx) in teacher.classes" :key="cls.id + '-' + (cls.section_id || idx)" class="p-4 rounded-2xl bg-terracotta/5 border border-terracotta/20">
                                            <div class="flex items-center justify-between mb-3">
                                                <div class="flex items-center gap-3">
                                                    <div class="w-10 h-10 rounded-xl bg-terracotta/10 flex items-center justify-center text-terracotta">
                                                        <Building class="w-5 h-5" />
                                                    </div>
                                                    <div>
                                                        <p class="text-sm font-black text-warm-text text-dark-text flex items-center gap-2">
                                                            {{ cls.name }}
                                                            <Badge v-if="cls.is_class_teacher" class="bg-forest/10 text-forest border-0 text-[9px] font-black px-2 py-0">Class Teacher</Badge>
                                                        </p>
                                                        <p class="text-xs text-warm-muted text-dark-muted">{{ cls.code }} {{ cls.section_name ? '• ' + cls.section_name + ' Section' : '' }}</p>
                                                    </div>
                                                </div>
                                                <div class="flex items-center gap-2">
                                                    <Button 
                                                        variant="outline" 
                                                        size="sm" 
                                                        @click="openEditSubjectsDialog(cls)" 
                                                        class="rounded-xl border-amber/20 text-amber hover:bg-amber/10 h-8 px-3 text-xs"
                                                    >
                                                        <BookOpen class="w-3 h-3 mr-1" />
                                                        {{ getSubjectsForClass(cls.id, cls.section_id).length > 0 ? 'Edit Subjects' : 'Add Subjects' }}
                                                    </Button>
                                                    <Button 
                                                        variant="outline" 
                                                        size="sm" 
                                                        @click="openEditClassTeacherDialog(cls)" 
                                                        class="rounded-xl border-terracotta/20 text-terracotta hover:bg-terracotta/10 h-8 px-3 text-xs"
                                                    >
                                                        <ShieldCheck class="w-3 h-3 mr-1" />
                                                        {{ cls.is_class_teacher ? 'Edit Status' : 'Set as Class Teacher' }}
                                                    </Button>
                                                    <Button variant="outline" size="sm" @click="openRemoveClassDialog(cls)" class="rounded-xl border-destructive/20 text-destructive hover:bg-destructive/10 h-8 px-3 text-xs">
                                                        <Trash2 class="w-3 h-3 mr-1" />
                                                        Remove
                                                    </Button>
                                                </div>
                                            </div>
                                            <!-- Subjects for this class-section -->
                                            <div v-if="getSubjectsForClass(cls.id, cls.section_id).length > 0" class="pl-13 ml-9 space-y-2">
                                                <p class="text-[10px] font-black uppercase tracking-widest text-warm-muted text-dark-muted mb-2">Teaching:</p>
                                                <div class="flex flex-wrap gap-2">
                                                    <Badge v-for="subject in getSubjectsForClass(cls.id, cls.section_id)" :key="subject.id" class="bg-amber/10 text-amber border-0 text-[10px] font-black px-2 py-1">
                                                        <BookOpen class="w-3 h-3 mr-1" />
                                                        {{ subject.name }}
                                                    </Badge>
                                                </div>
                                            </div>
                                            <div v-else class="pl-13 ml-9">
                                                <p class="text-xs text-warm-muted/70 italic">No subjects assigned for this class</p>
                                            </div>
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
                                
                                <div class="flex items-center gap-4 p-4 bg-white/10 dark:bg-white/5 rounded-2xl border border-white/20 dark:border-white/10">
                                    <div class="w-10 h-10 rounded-xl bg-white/20 dark:bg-white/10 flex items-center justify-center">
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
                            <h3 class="text-2xl font-black tracking-tighter text-warm-text text-dark-text">Live Grid Standby</h3>
                            <p class="text-sm font-bold text-warm-muted text-dark-muted uppercase tracking-widest mt-2">The operational schedule for this personnel is currently being synchronized with the master timetable.</p>
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
                    <DialogDescription class="font-bold text-warm-muted text-dark-muted">
                        This action will decommission <strong class="text-warm-text text-dark-text">{{ teacher.user.name }}</strong> from all academic systems. Payroll and instructional logs will be archived. This is irreversible.
                    </DialogDescription>
                </DialogHeader>
                <DialogFooter class="gap-3">
                    <Button variant="outline" @click="showDeleteDialog = false" class="rounded-xl font-black border-terracotta/20">Abort</Button>
                    <Button variant="destructive" @click="deleteTeacher" class="rounded-xl font-black bg-destructive shadow-lg shadow-destructive/20">Purge Record</Button>
                </DialogFooter>
            </DialogContent>
        </Dialog>

        <!-- Assign Class Dialog -->
        <Dialog v-model:open="showAssignClassDialog">
            <DialogContent class="card-warm rounded-xl rounded-[2.5rem] shadow-2xl max-w-2xl">
                <DialogHeader>
                    <DialogTitle class="text-2xl font-black tracking-tighter text-warm-text text-dark-text">Assign to Class</DialogTitle>
                    <DialogDescription class="font-bold text-warm-muted text-dark-muted">
                        Select a class and section to assign <strong class="text-warm-text text-dark-text">{{ teacher.user.name }}</strong> to.
                    </DialogDescription>
                </DialogHeader>
                <div class="py-4 space-y-4">
                    <div v-if="unassignedClasses.length === 0" class="text-center py-4">
                        <p class="text-sm text-warm-muted">No available classes to assign.</p>
                        <p class="text-xs text-warm-muted/70 mt-1">This teacher is already assigned to all classes.</p>
                    </div>
                    <div v-else class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Left Column: Class Selection -->
                        <div class="space-y-2">
                            <Label class="text-sm font-bold text-warm-text text-dark-text flex items-center gap-2">
                                <Building class="w-4 h-4 text-terracotta" />
                                Select Class
                            </Label>
                            <div class="space-y-2 max-h-64 overflow-y-auto">
                                <button
                                    v-for="cls in unassignedClasses"
                                    :key="cls.id"
                                    @click="assignClassForm.class_id = cls.id; assignClassForm.section_id = ''; assignClassForm.subject_ids = [];"
                                    :class="[
                                        'w-full p-3 rounded-xl text-left flex items-center gap-3 transition-all',
                                        assignClassForm.class_id === cls.id
                                            ? 'bg-terracotta/10 border-2 border-terracotta'
                                            : 'bg-terracotta/5 border-2 border-transparent hover:border-terracotta/30'
                                    ]"
                                >
                                    <div class="w-10 h-10 rounded-xl bg-terracotta/10 flex items-center justify-center text-terracotta">
                                        <Building class="w-5 h-5" />
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-black text-warm-text text-dark-text truncate">{{ cls.name }}</p>
                                        <p class="text-xs text-warm-muted text-dark-muted">{{ cls.code }}</p>
                                    </div>
                                    <div v-if="assignClassForm.class_id === cls.id" class="ml-auto flex-shrink-0">
                                        <ShieldCheck class="w-5 h-5 text-terracotta" />
                                    </div>
                                </button>
                            </div>
                        </div>

                        <!-- Right Column: Section, Class Teacher, Subjects -->
                        <div class="space-y-4">
                            <!-- Section Selection (if class selected) -->
                            <div v-if="assignClassForm.class_id" class="space-y-2">
                                <Label class="text-sm font-bold text-warm-text text-dark-text flex items-center gap-2">
                                    <Users class="w-4 h-4 text-terracotta" />
                                    Select Section
                                    <span class="text-destructive">*</span>
                                </Label>
                                <div v-if="availableSectionsForSelectedClass.length === 0" class="text-xs text-warm-muted italic p-3 bg-terracotta/5 rounded-xl border border-terracotta/20">
                                    All sections already assigned for this class.
                                </div>
                                <select
                                    v-else
                                    v-model="assignClassForm.section_id"
                                    :class="[
                                        'w-full h-10 rounded-xl border bg-white bg-dark-bg px-3 text-sm',
                                        assignClassForm.errors.section_id ? 'border-destructive' : 'border-terracotta/20'
                                    ]"
                                    required
                                >
                                    <option value="" disabled>Select Section (Required)</option>
                                    <option v-for="section in availableSectionsForSelectedClass" :key="section.id" :value="section.id">
                                        {{ section.name }}
                                    </option>
                                </select>
                                <p v-if="assignClassForm.errors.section_id" class="text-xs text-destructive">{{ assignClassForm.errors.section_id }}</p>
                            </div>

                            <!-- Class Teacher Checkbox -->
                            <div v-if="assignClassForm.class_id && assignClassForm.section_id" class="flex items-center gap-3 p-3 rounded-xl bg-forest/5 border border-forest/20">
                                <input
                                    type="checkbox"
                                    id="is_class_teacher"
                                    v-model="assignClassForm.is_class_teacher"
                                    class="w-5 h-5 rounded border-forest text-forest focus:ring-forest flex-shrink-0"
                                />
                                <Label for="is_class_teacher" class="text-sm font-bold text-warm-text text-dark-text cursor-pointer">
                                    Set as Class Teacher
                                    <span class="block text-xs font-normal text-warm-muted">Responsible for student reports</span>
                                </Label>
                            </div>

                            <!-- Subject Selection -->
                            <div v-if="assignClassForm.class_id" class="space-y-2">
                                <Label class="text-sm font-bold text-warm-text text-dark-text flex items-center gap-2">
                                    <BookOpen class="w-4 h-4 text-terracotta" />
                                    Select Subjects (Optional)
                                </Label>
                                <div v-if="unassignedSubjectsForSelectedClass.length === 0" class="text-xs text-warm-muted italic p-2">
                                    No available subjects for this class.
                                </div>
                                <div v-else class="grid grid-cols-1 gap-2 max-h-48 overflow-y-auto">
                                    <label
                                        v-for="subject in unassignedSubjectsForSelectedClass"
                                        :key="subject.id"
                                        class="flex items-center gap-3 p-2 rounded-xl hover:bg-terracotta/5 cursor-pointer border border-transparent hover:border-terracotta/20"
                                    >
                                        <input
                                            type="checkbox"
                                            :value="subject.id"
                                            v-model="assignClassForm.subject_ids"
                                            class="w-4 h-4 rounded border-terracotta text-terracotta focus:ring-terracotta flex-shrink-0"
                                        />
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-bold text-warm-text text-dark-text">{{ subject.name }}</p>
                                            <p class="text-xs text-warm-muted">{{ subject.code }}</p>
                                        </div>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <DialogFooter class="gap-3">
                    <Button variant="outline" @click="showAssignClassDialog = false" class="rounded-xl font-black border-terracotta/20">Cancel</Button>
                    <Button 
                        @click="assignClass" 
                        :disabled="!assignClassForm.class_id || !assignClassForm.section_id || assignClassForm.processing"
                        class="accent-terracotta text-white font-black rounded-xl shadow-lg shadow-terracotta/20"
                    >
                        {{ assignClassForm.processing ? 'Assigning...' : 'Assign Class & Subjects' }}
                    </Button>
                </DialogFooter>
            </DialogContent>
        </Dialog>

        <!-- Assign Subject Dialog -->
        <Dialog v-model:open="showAssignSubjectDialog">
            <DialogContent class="card-warm rounded-xl rounded-[2.5rem] shadow-2xl max-w-md">
                <DialogHeader>
                    <DialogTitle class="text-2xl font-black tracking-tighter text-warm-text text-dark-text">Assign Subject</DialogTitle>
                    <DialogDescription class="font-bold text-warm-muted text-dark-muted">
                        Select a subject to assign to <strong class="text-warm-text text-dark-text">{{ teacher.user.name }}</strong>.
                    </DialogDescription>
                </DialogHeader>
                <div class="py-4 space-y-4">
                    <div v-if="unassignedSubjects.length === 0" class="text-center py-4">
                        <p class="text-sm text-warm-muted">No available subjects to assign.</p>
                        <p class="text-xs text-warm-muted/70 mt-1">This teacher is already assigned to all subjects.</p>
                    </div>
                    <div v-else>
                        <p class="text-xs font-bold text-warm-muted uppercase tracking-widest mb-2">Select Subject</p>
                        <div class="space-y-2 max-h-40 overflow-y-auto">
                            <button
                                v-for="subject in unassignedSubjects"
                                :key="subject.id"
                                @click="assignSubjectForm.subject_id = subject.id"
                                :class="[
                                    'w-full p-3 rounded-xl text-left flex items-center gap-3 transition-all',
                                    assignSubjectForm.subject_id === subject.id
                                        ? 'bg-terracotta/10 border-2 border-terracotta'
                                        : 'bg-terracotta/5 border-2 border-transparent hover:border-terracotta/30'
                                ]"
                            >
                                <div class="w-10 h-10 rounded-xl bg-terracotta/10 flex items-center justify-center text-terracotta">
                                    <BookOpen class="w-5 h-5" />
                                </div>
                                <div>
                                    <p class="text-sm font-black text-warm-text text-dark-text">{{ subject.name }}</p>
                                    <p class="text-xs text-warm-muted text-dark-muted">{{ subject.code }} {{ subject.type ? '• ' + subject.type : '' }}</p>
                                </div>
                                <div v-if="assignSubjectForm.subject_id === subject.id" class="ml-auto">
                                    <ShieldCheck class="w-5 h-5 text-terracotta" />
                                </div>
                            </button>
                        </div>

                        <!-- Optional: Select Class for Subject -->
                        <div class="mt-4">
                            <p class="text-xs font-bold text-warm-muted uppercase tracking-widest mb-2">For Specific Class (Optional)</p>
                            <select
                                v-model="assignSubjectForm.academic_class_id"
                                class="w-full h-12 rounded-xl border border-terracotta/20 bg-white bg-dark-bg px-4 text-sm focus:ring-2 focus:ring-terracotta/30"
                            >
                                <option value="">All Classes (General Assignment)</option>
                                <option v-for="cls in availableClasses" :key="cls.id" :value="cls.id">
                                    {{ cls.name }} ({{ cls.code }})
                                </option>
                            </select>
                        </div>
                    </div>
                </div>
                <DialogFooter class="gap-3">
                    <Button variant="outline" @click="showAssignSubjectDialog = false" class="rounded-xl font-black border-terracotta/20">Cancel</Button>
                    <Button 
                        @click="assignSubject" 
                        :disabled="!assignSubjectForm.subject_id || assignSubjectForm.processing"
                        class="accent-terracotta text-white font-black rounded-xl shadow-lg shadow-terracotta/20"
                    >
                        {{ assignSubjectForm.processing ? 'Assigning...' : 'Assign Subject' }}
                    </Button>
                </DialogFooter>
            </DialogContent>
        </Dialog>

        <!-- Remove Class Confirmation Dialog -->
        <Dialog v-model:open="showRemoveClassDialog">
            <DialogContent class="card-warm rounded-xl rounded-[2.5rem] shadow-2xl max-w-md">
                <DialogHeader>
                    <DialogTitle class="text-2xl font-black tracking-tighter text-destructive flex items-center gap-3">
                        <AlertTriangle class="w-6 h-6" />
                        Remove Teaching Assignment
                    </DialogTitle>
                    <DialogDescription class="font-bold text-warm-muted text-dark-muted">
                        Are you sure you want to remove <strong class="text-warm-text text-dark-text">{{ teacher.user?.name }}</strong> from this class assignment?
                    </DialogDescription>
                </DialogHeader>
                <div class="py-4" v-if="classToRemove">
                    <div class="p-4 rounded-2xl bg-destructive/5 border border-destructive/20">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-destructive/10 flex items-center justify-center text-destructive">
                                <Building class="w-5 h-5" />
                            </div>
                            <div>
                                <p class="text-sm font-black text-warm-text text-dark-text">{{ classToRemove.name }}</p>
                                <p class="text-xs text-warm-muted text-dark-muted">{{ classToRemove.code }} {{ classToRemove.section_name ? '• ' + classToRemove.section_name + ' Section' : '' }}</p>
                            </div>
                        </div>
                        <div v-if="getSubjectsForClass(classToRemove.id).length > 0" class="mt-3 pt-3 border-t border-destructive/10">
                            <p class="text-[10px] font-black uppercase tracking-widest text-destructive/70 mb-2">Will also remove subject assignments:</p>
                            <div class="flex flex-wrap gap-2">
                                <Badge v-for="subject in getSubjectsForClass(classToRemove.id)" :key="subject.id" class="bg-destructive/10 text-destructive border-0 text-[10px] font-black px-2 py-1">
                                    <BookOpen class="w-3 h-3 mr-1" />
                                    {{ subject.name }}
                                </Badge>
                            </div>
                        </div>
                    </div>
                    <p class="text-xs text-warm-muted mt-4 text-center">This action cannot be undone.</p>
                </div>
                <DialogFooter class="gap-3">
                    <Button variant="outline" @click="showRemoveClassDialog = false" class="rounded-xl font-black border-terracotta/20">Cancel</Button>
                    <Button 
                        @click="confirmRemoveClass" 
                        :disabled="removeClassForm.processing"
                        class="bg-destructive hover:bg-destructive/90 text-white font-black rounded-xl shadow-lg shadow-destructive/20"
                    >
                        <Trash2 class="w-4 h-4 mr-1" />
                        {{ removeClassForm.processing ? 'Removing...' : 'Remove Assignment' }}
                    </Button>
                </DialogFooter>
            </DialogContent>
        </Dialog>

        <!-- Edit Class Teacher Status Dialog -->
        <Dialog v-model:open="showEditClassTeacherDialog">
            <DialogContent class="card-warm rounded-xl rounded-[2.5rem] shadow-2xl max-w-md">
                <DialogHeader>
                    <DialogTitle class="text-2xl font-black tracking-tighter text-terracotta flex items-center gap-3">
                        <ShieldCheck class="w-6 h-6" />
                        Class Teacher Status
                    </DialogTitle>
                    <DialogDescription class="font-bold text-warm-muted text-dark-muted">
                        Manage class teacher status for <strong class="text-warm-text text-dark-text">{{ teacher.user.name }}</strong> in {{ classToEdit?.name }} {{ classToEdit?.section_name ? '(' + classToEdit?.section_name + ' Section)' : '' }}.
                    </DialogDescription>
                </DialogHeader>
                <div class="py-6">
                    <div class="flex items-center gap-4 p-4 rounded-2xl bg-terracotta/5 border border-terracotta/20">
                        <input
                            type="checkbox"
                            id="edit_is_class_teacher"
                            v-model="editClassTeacherForm.is_class_teacher"
                            class="w-6 h-6 rounded border-terracotta text-terracotta focus:ring-terracotta flex-shrink-0"
                        />
                        <div>
                            <Label for="edit_is_class_teacher" class="text-base font-black text-warm-text text-dark-text cursor-pointer">
                                Set as Class Teacher
                            </Label>
                            <p class="text-xs text-warm-muted">When enabled, this teacher will be responsible for managing student reports and class operations.</p>
                        </div>
                    </div>
                </div>
                <DialogFooter class="gap-3">
                    <Button variant="outline" @click="showEditClassTeacherDialog = false" class="rounded-xl font-black border-terracotta/20">Cancel</Button>
                    <Button 
                        @click="updateClassTeacherStatus" 
                        :disabled="editClassTeacherForm.processing"
                        class="accent-terracotta text-white font-black rounded-xl shadow-lg shadow-terracotta/20"
                    >
                        <ShieldCheck class="w-4 h-4 mr-1" />
                        {{ editClassTeacherForm.processing ? 'Updating...' : (editClassTeacherForm.is_class_teacher ? 'Set as Class Teacher' : 'Remove Class Teacher Status') }}
                    </Button>
                </DialogFooter>
            </DialogContent>
        </Dialog>

        <!-- Edit Subjects Dialog -->
        <Dialog v-model:open="showEditSubjectsDialog">
            <DialogContent class="card-warm rounded-[2.5rem] shadow-2xl shadow-terracotta/10 max-w-md border-2 border-terracotta/20">
                <DialogHeader class="border-b border-terracotta/10 pb-4">
                    <DialogTitle class="text-2xl font-black tracking-tighter text-terracotta flex items-center gap-3">
                        <div class="w-12 h-12 rounded-2xl bg-terracotta/10 flex items-center justify-center">
                            <BookOpen class="w-6 h-6 text-terracotta" />
                        </div>
                        Edit Subjects
                    </DialogTitle>
                    <DialogDescription class="font-bold text-warm-muted text-dark-muted pt-2">
                        Manage subjects for <strong class="text-warm-text text-dark-text">{{ teacher.user.name }}</strong> in {{ classToEditSubjects?.name }} {{ classToEditSubjects?.section_name ? '(' + classToEditSubjects?.section_name + ' Section)' : '' }}.
                    </DialogDescription>
                </DialogHeader>
                <div class="py-6">
                    <div class="space-y-3">
                        <Label class="text-sm font-black uppercase tracking-widest text-warm-muted flex items-center gap-2">
                            <BookOpen class="w-4 h-4 text-terracotta" />
                            Select Subjects
                        </Label>
                        <div v-if="availableSubjects.length === 0" class="text-sm text-warm-muted italic p-4 bg-terracotta/5 rounded-2xl border border-terracotta/20">
                            No available subjects.
                        </div>
                        <div v-else class="grid grid-cols-1 gap-2 max-h-64 overflow-y-auto p-2 bg-terracotta/5 rounded-2xl border border-terracotta/10">
                            <label
                                v-for="subject in availableSubjects"
                                :key="subject.id"
                                :class="[
                                    'flex items-center gap-3 p-3 rounded-xl cursor-pointer border transition-all',
                                    editSubjectsForm.subject_ids.includes(subject.id)
                                        ? 'bg-terracotta/10 border-terracotta/30'
                                        : 'bg-white/50 border-transparent hover:border-terracotta/20'
                                ]"
                            >
                                <input
                                    type="checkbox"
                                    :value="subject.id"
                                    v-model="editSubjectsForm.subject_ids"
                                    class="w-5 h-5 rounded border-terracotta text-terracotta focus:ring-terracotta flex-shrink-0"
                                />
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-black text-warm-text text-dark-text">{{ subject.name }}</p>
                                    <p class="text-xs text-warm-muted">{{ subject.code }}</p>
                                </div>
                                <div v-if="editSubjectsForm.subject_ids.includes(subject.id)" class="flex-shrink-0">
                                    <ShieldCheck class="w-4 h-4 text-terracotta" />
                                </div>
                            </label>
                        </div>
                    </div>
                </div>
                <DialogFooter class="gap-3 border-t border-terracotta/10 pt-4">
                    <Button variant="outline" @click="showEditSubjectsDialog = false" class="rounded-xl font-black border-terracotta/20 hover:bg-terracotta/5">Cancel</Button>
                    <Button 
                        @click="updateSubjects" 
                        :disabled="editSubjectsForm.processing"
                        class="accent-terracotta text-white font-black rounded-xl shadow-lg shadow-terracotta/20"
                    >
                        <BookOpen class="w-4 h-4 mr-1" />
                        {{ editSubjectsForm.processing ? 'Saving...' : 'Save Subjects' }}
                    </Button>
                </DialogFooter>
            </DialogContent>
        </Dialog>
    </Sidebar>
</template>