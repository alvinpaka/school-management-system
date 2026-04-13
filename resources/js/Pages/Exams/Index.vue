<script setup>
import { Head, Link, router, usePage, useForm } from '@inertiajs/vue3';
import { ref, watch, computed } from 'vue';
import Sidebar from '@/Components/Sidebar.vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import Pagination from '@/components/ui/pagination.vue';
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogHeader,
    DialogTitle,
    DialogTrigger,
    DialogFooter,
} from '@/components/ui/dialog';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';
import { Separator } from '@/components/ui/separator';
import { 
    Plus,
    Edit,
    Trash2,
    Search,
    Filter,
    Download,
    Eye,
    Calendar,
    Clock,
    FileText,
    Trophy,
    TrendingUp,
    BarChart3,
    MoreVertical,
    CheckCircle2,
    Award,
    Target,
    Zap,
    Save,
    BookOpen
} from 'lucide-vue-next';

const props = defineProps({
    exams: Object,
    filters: Object,
    subjects: Array
});

const page = usePage();
const user = computed(() => page.props.auth.user);
const userRoles = computed(() => user.value?.roles || []);
const isAdminOrTeacher = computed(() => userRoles.value.some(r => ['admin', 'teacher'].includes(r)));

const searchQuery = ref(props.filters?.search || '');
const isCreateDialogOpen = ref(false);

// Create exam form
const form = useForm({
    name: '',
    subject_id: '',
    date: '',
    start_time: '',
    end_time: '',
    max_marks: 100
});

const submitForm = () => {
    form.post(route('exams.store'), {
        onSuccess: () => {
            isCreateDialogOpen.value = false;
            form.reset();
            form.max_marks = 100;
        },
    });
};

const deleteExam = (id) => {
    if (confirm('Are you sure you want to delete this exam?')) {
        router.delete(route('exams.destroy', id));
    }
};

const handlePageChange = (page) => {
    const params = { page };
    if (searchQuery.value) {
        params.search = searchQuery.value;
    }
    router.get(route('exams.index'), params, { preserveState: true });
};

// Watch for search query changes
watch(searchQuery, (newValue) => {
    router.get(route('exams.index'), { 
        search: newValue, 
        page: 1 
    }, { preserveState: true });
}, { debounce: 300 });

const getStatusBadgeClass = (status) => {
    switch (status) {
        case 'completed': return 'bg-forest/10 text-forest border-forest/20';
        case 'ongoing': return 'bg-amber/10 text-amber border-amber/20';
        default: return 'bg-terracotta/10 text-terracotta border-terracotta/20';
    }
};

const getGradeColor = (grade) => {
    if (!grade) return 'text-warm-muted';
    if (grade.startsWith('A')) return 'text-forest';
    if (grade.startsWith('B')) return 'text-terracotta';
    if (grade.startsWith('C')) return 'text-amber';
    return 'text-destructive';
};
</script>

<template>
    <Head :title="isAdminOrTeacher ? 'Exam Management | EduManage Pro' : 'My Academic Results | EduManage Pro'" />

    <Sidebar>
        <template #header-title>
            <div class="flex items-center gap-2">
                <div class="p-2 bg-terracotta/10 rounded-lg">
                    <Trophy class="w-4 h-4 text-terracotta" />
                </div>
                <span class="font-black text-sm uppercase tracking-wider text-warm-muted dark:text-dark-muted">
                    {{ isAdminOrTeacher ? 'Examination Hub' : 'Personal Performance' }}
                </span>
            </div>
        </template>

        <div class="space-y-8 animate-fade-in-up">
            <!-- Dramatic Header Section -->
            <div class="relative overflow-hidden card-warm rounded-[2.5rem] p-8 md:p-12">
                <div class="absolute -top-24 -right-24 w-64 h-64 bg-terracotta/5 blur-[80px] rounded-full animate-float"></div>
                <div class="absolute -bottom-24 -left-24 w-64 h-64 bg-forest/5 blur-[80px] rounded-full animate-float" style="animation-delay: 2s"></div>
                
                <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-8">
                    <div>
                        <h1 class="text-4xl md:text-5xl font-black text-warm-text dark:text-dark-text mb-2 tracking-tighter">
                            {{ isAdminOrTeacher ? 'Exam Schedule' : 'My Results' }}
                        </h1>
                        <p class="text-lg text-warm-muted dark:text-dark-muted font-medium">
                            {{ isAdminOrTeacher 
                                ? 'Coordinate academic assessments and monitor student grading cycles.' 
                                : 'Track your academic milestones and review your performance trends.' }}
                        </p>
                    </div>
                    
                    <div v-if="isAdminOrTeacher" class="flex items-center gap-3">
                        <Dialog v-model:open="isCreateDialogOpen">
                            <DialogTrigger as-child>
                                <Button class="accent-terracotta text-white font-black rounded-2xl h-14 px-8 shadow-xl shadow-terracotta/30">
                                    <Plus class="w-5 h-5 mr-2" />
                                    Schedule Assessment
                                </Button>
                            </DialogTrigger>
                            <DialogContent class="w-[95vw] max-w-5xl max-h-[90vh] overflow-y-auto card-warm">
                                <DialogHeader>
                                    <DialogTitle class="text-2xl font-black tracking-tight text-warm-text dark:text-dark-text">Schedule New Assessment</DialogTitle>
                                    <DialogDescription class="text-warm-muted dark:text-dark-muted">
                                        Create a new exam or assessment schedule.
                                    </DialogDescription>
                                </DialogHeader>
                                <form @submit.prevent="submitForm" class="space-y-5 py-4">
                                    <div class="space-y-2">
                                        <Label for="exam_name" class="font-medium text-warm-text dark:text-dark-text">Exam Name *</Label>
                                        <Input
                                            id="exam_name"
                                            v-model="form.name"
                                            type="text"
                                            placeholder="e.g., Mid-Term Mathematics"
                                            required
                                            class="h-11 border-terracotta/20 focus:ring-terracotta/30"
                                        />
                                        <div v-if="form.errors.name" class="text-destructive text-sm">{{ form.errors.name }}</div>
                                    </div>
                                    
                                    <div class="space-y-2">
                                        <Label for="subject_id" class="font-medium text-warm-text dark:text-dark-text">Subject *</Label>
                                        <select
                                            id="subject_id"
                                            v-model="form.subject_id"
                                            class="flex h-11 w-full rounded-md border border-terracotta/20 bg-background px-3 py-2 text-sm focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-terracotta/30 text-warm-text dark:text-dark-text"
                                            required
                                        >
                                            <option value="">Select a subject</option>
                                            <option v-for="subject in subjects" :key="subject.id" :value="subject.id">
                                                {{ subject.name }}
                                            </option>
                                        </select>
                                        <div v-if="form.errors.subject_id" class="text-destructive text-sm">{{ form.errors.subject_id }}</div>
                                    </div>

                                    <div class="grid grid-cols-2 gap-4">
                                        <div class="space-y-2">
                                            <Label for="exam_date" class="font-medium text-warm-text dark:text-dark-text">Exam Date *</Label>
                                            <Input
                                                id="exam_date"
                                                v-model="form.date"
                                                type="date"
                                                required
                                                class="h-11 border-terracotta/20 focus:ring-terracotta/30"
                                            />
                                            <div v-if="form.errors.date" class="text-destructive text-sm">{{ form.errors.date }}</div>
                                        </div>
                                        <div class="space-y-2">
                                            <Label for="max_marks" class="font-medium text-warm-text dark:text-dark-text">Maximum Marks *</Label>
                                            <Input
                                                id="max_marks"
                                                v-model="form.max_marks"
                                                type="number"
                                                min="1"
                                                required
                                                class="h-11 border-terracotta/20 focus:ring-terracotta/30"
                                            />
                                            <div v-if="form.errors.max_marks" class="text-destructive text-sm">{{ form.errors.max_marks }}</div>
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-2 gap-4">
                                        <div class="space-y-2">
                                            <Label for="start_time" class="font-medium text-warm-text dark:text-dark-text">Start Time *</Label>
                                            <Input
                                                id="start_time"
                                                v-model="form.start_time"
                                                type="time"
                                                required
                                                class="h-11 border-terracotta/20 focus:ring-terracotta/30"
                                            />
                                            <div v-if="form.errors.start_time" class="text-destructive text-sm">{{ form.errors.start_time }}</div>
                                        </div>
                                        <div class="space-y-2">
                                            <Label for="end_time" class="font-medium text-warm-text dark:text-dark-text">End Time *</Label>
                                            <Input
                                                id="end_time"
                                                v-model="form.end_time"
                                                type="time"
                                                required
                                                class="h-11 border-terracotta/20 focus:ring-terracotta/30"
                                            />
                                            <div v-if="form.errors.end_time" class="text-destructive text-sm">{{ form.errors.end_time }}</div>
                                        </div>
                                    </div>

                                    <DialogFooter class="gap-3 pt-4 border-t border-terracotta/20">
                                        <Button type="button" variant="outline" @click="isCreateDialogOpen = false" class="border-terracotta/20">
                                            Cancel
                                        </Button>
                                        <Button type="submit" :disabled="form.processing" class="accent-terracotta text-white">
                                            <Save class="w-4 h-4 mr-2" />
                                            {{ form.processing ? 'Scheduling...' : 'Schedule Exam' }}
                                        </Button>
                                    </DialogFooter>
                                </form>
                            </DialogContent>
                        </Dialog>
                    </div>
                </div>
            </div>

            <!-- Dashboard Stats -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div class="card-warm p-6 rounded-[2rem] group">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 rounded-2xl bg-terracotta/10 flex items-center justify-center text-terracotta">
                            <Target class="w-6 h-6" />
                        </div>
                        <span class="text-2xl font-black tracking-tighter text-warm-text dark:text-dark-text">{{ exams.total || 0 }}</span>
                    </div>
                    <p class="text-[10px] font-black uppercase text-warm-muted dark:text-dark-muted tracking-widest">Total Assessments</p>
                </div>
                
                <div class="card-warm p-6 rounded-[2rem] group">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 rounded-2xl bg-forest/10 flex items-center justify-center text-forest">
                            <TrendingUp class="w-6 h-6" />
                        </div>
                        <span class="text-2xl font-black tracking-tighter text-forest">
                            {{ isAdminOrTeacher ? '82%' : '85.4%' }}
                        </span>
                    </div>
                    <p class="text-[10px] font-black uppercase text-warm-muted dark:text-dark-muted tracking-widest">
                        {{ isAdminOrTeacher ? 'Class Average' : 'Personal GPA' }}
                    </p>
                </div>

                <div class="card-warm p-6 rounded-[2rem] group">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 rounded-2xl bg-amber/10 flex items-center justify-center text-amber">
                            <Zap class="w-6 h-6" />
                        </div>
                        <span class="text-2xl font-black tracking-tighter text-amber">
                            {{ exams.data.filter(e => e.status === 'ongoing').length }}
                        </span>
                    </div>
                    <p class="text-[10px] font-black uppercase text-warm-muted dark:text-dark-muted tracking-widest">Ongoing Exams</p>
                </div>

                <div class="card-warm p-6 rounded-[2rem] group">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 rounded-2xl bg-terracotta/10 flex items-center justify-center text-terracotta">
                            <Award class="w-6 h-6" />
                        </div>
                        <span class="text-2xl font-black tracking-tighter text-warm-text dark:text-dark-text">Top 5</span>
                    </div>
                    <p class="text-[10px] font-black uppercase text-warm-muted dark:text-dark-muted tracking-widest">Academic Standing</p>
                </div>
            </div>

            <!-- Main Table Card -->
            <div class="card-warm rounded-[3rem] overflow-hidden">
                <div class="p-8 border-b border-terracotta/20 bg-terracotta/5">
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                        <div>
                            <h3 class="text-2xl font-black tracking-tighter text-warm-text dark:text-dark-text">
                                {{ isAdminOrTeacher ? 'Assessment Inventory' : 'My Performance Ledger' }}
                            </h3>
                            <p class="font-medium text-warm-muted dark:text-dark-muted">
                                {{ isAdminOrTeacher ? 'Comprehensive list of all scheduled and previous exams' : 'View your detailed subject-wise breakdown' }}
                            </p>
                        </div>
                        <div class="flex items-center gap-2">
                            <div class="relative group">
                                <Search class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-warm-muted dark:text-dark-muted group-focus-within:text-terracotta transition-colors" />
                                <input
                                    type="text"
                                    v-model="searchQuery"
                                    placeholder="Search assessments..."
                                    class="pl-10 pr-4 h-12 w-full sm:w-64 bg-white dark:bg-dark-bg border border-terracotta/20 shadow-sm rounded-2xl text-sm font-bold focus:ring-4 focus:ring-terracotta/10 transition-all outline-none text-warm-text dark:text-dark-text"
                                />
                            </div>
                            <Button variant="outline" class="h-12 w-12 bg-white dark:bg-dark-bg border border-terracotta/20 shadow-sm rounded-2xl">
                                <Filter class="w-4 h-4 text-warm-muted dark:text-dark-muted" />
                            </Button>
                        </div>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-terracotta/5 text-left border-b border-terracotta/20">
                                <th class="py-5 px-8 text-[10px] font-black uppercase text-warm-muted dark:text-dark-muted tracking-widest">Exam & Subject</th>
                                <th v-if="isAdminOrTeacher" class="py-5 px-8 text-[10px] font-black uppercase text-warm-muted dark:text-dark-muted tracking-widest">Class</th>
                                <th class="py-5 px-8 text-[10px] font-black uppercase text-warm-muted dark:text-dark-muted tracking-widest">Schedule</th>
                                <th class="py-5 px-8 text-[10px] font-black uppercase text-warm-muted dark:text-dark-muted tracking-widest text-center">Status</th>
                                <th :class="isAdminOrTeacher ? 'text-right' : 'text-center'" class="py-5 px-8 text-[10px] font-black uppercase text-warm-muted dark:text-dark-muted tracking-widest">
                                    {{ isAdminOrTeacher ? 'Actions' : 'Performance' }}
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-terracotta/20">
                            <tr v-for="exam in exams.data" :key="exam.id" class="hover:bg-terracotta/5 transition-colors group">
                                <td class="py-6 px-8">
                                    <div class="flex items-center gap-4">
                                        <div class="w-12 h-12 bg-white dark:bg-dark-bg rounded-2xl flex items-center justify-center border border-terracotta/20 group-hover:scale-110 transition-transform">
                                            <div class="w-8 h-8 rounded-xl bg-terracotta/10 flex items-center justify-center text-terracotta">
                                                <FileText class="w-4 h-4" />
                                            </div>
                                        </div>
                                        <div>
                                            <div class="font-black text-warm-text dark:text-dark-text tracking-tight">{{ exam.name }}</div>
                                            <div class="text-xs font-bold text-warm-muted dark:text-dark-muted uppercase tracking-widest">{{ exam.subject?.name || 'General' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td v-if="isAdminOrTeacher" class="py-6 px-8">
                                    <Badge variant="outline" class="rounded-xl border-terracotta/20 bg-white/50 dark:bg-dark-bg/50 px-3 font-bold text-warm-text dark:text-dark-text">
                                        {{ exam.academic_class?.name || 'TBA' }}
                                    </Badge>
                                </td>
                                <td class="py-6 px-8">
                                    <div class="flex items-center gap-2 text-sm font-bold text-warm-text dark:text-dark-text">
                                        <Calendar class="w-4 h-4 text-terracotta" />
                                        {{ new Date(exam.date).toLocaleDateString(undefined, { month: 'short', day: 'numeric', year: 'numeric' }) }}
                                    </div>
                                    <div class="flex items-center gap-2 text-[10px] font-black uppercase text-warm-muted dark:text-dark-muted mt-1">
                                        <Clock class="w-3 h-3" />
                                        {{ exam.start_time }} - {{ exam.end_time }}
                                    </div>
                                </td>
                                <td class="py-6 px-8 text-center">
                                    <Badge :class="['rounded-full px-4 py-1 h-7 border-0 font-black uppercase text-[9px] tracking-widest', getStatusBadgeClass(exam.status)]">
                                        {{ exam.status }}
                                    </Badge>
                                </td>
                                <td class="py-6 px-8">
                                    <!-- Admin Actions -->
                                    <div v-if="isAdminOrTeacher" class="flex items-center justify-end">
                                        <DropdownMenu>
                                            <DropdownMenuTrigger as-child>
                                                <Button variant="ghost" class="w-10 h-10 p-0 rounded-xl hover:bg-terracotta/10">
                                                    <MoreVertical class="w-4 h-4 text-warm-muted dark:text-dark-muted" />
                                                </Button>
                                            </DropdownMenuTrigger>
                                            <DropdownMenuContent align="end" class="w-56 card-warm rounded-[1.5rem] p-2">
                                                <DropdownMenuItem as-child>
                                                    <Link :href="route('exams.show', exam.id)" class="rounded-xl p-3 font-bold gap-3 flex items-center hover:bg-terracotta/10">
                                                        <div class="w-8 h-8 rounded-lg bg-terracotta/10 flex items-center justify-center text-terracotta">
                                                            <Eye class="w-4 h-4" />
                                                        </div>
                                                        View Profile
                                                    </Link>
                                                </DropdownMenuItem>
                                                <DropdownMenuItem as-child>
                                                    <Link :href="route('exams.edit', exam.id)" class="rounded-xl p-3 font-bold gap-3 flex items-center hover:bg-terracotta/10">
                                                        <div class="w-8 h-8 rounded-lg bg-terracotta/10 flex items-center justify-center text-terracotta">
                                                            <Edit class="w-4 h-4" />
                                                        </div>
                                                        Modify Schedule
                                                    </Link>
                                                </DropdownMenuItem>
                                                <DropdownMenuItem as-child>
                                                    <Link :href="route('exams.grades.enter', exam.id)" class="rounded-xl p-3 font-bold gap-3 flex items-center hover:bg-terracotta/10">
                                                        <div class="w-8 h-8 rounded-lg bg-forest/10 flex items-center justify-center text-forest">
                                                            <BarChart3 class="w-4 h-4" />
                                                        </div>
                                                        Input Marks
                                                    </Link>
                                                </DropdownMenuItem>
                                                <Separator class="my-2 bg-terracotta/20" />
                                                <DropdownMenuItem @click="deleteExam(exam.id)" class="rounded-xl p-3 font-bold gap-3 flex items-center text-destructive hover:bg-destructive/10">
                                                    <div class="w-8 h-8 rounded-lg bg-destructive/10 flex items-center justify-center">
                                                        <Trash2 class="w-4 h-4" />
                                                    </div>
                                                    Delete Permanently
                                                </DropdownMenuItem>
                                            </DropdownMenuContent>
                                        </DropdownMenu>
                                    </div>
                                    
                                    <!-- Student View (Performance) -->
                                    <div v-else class="text-center">
                                        <div v-if="exam.status === 'completed'" class="flex items-center justify-center gap-3">
                                            <div class="text-right">
                                                <div class="text-xl font-black italic tracking-tighter" :class="getGradeColor('A')">A-</div>
                                                <div class="text-[9px] font-black uppercase text-warm-muted dark:text-dark-muted tracking-widest">GPA: 3.7</div>
                                            </div>
                                            <div class="w-10 h-10 rounded-xl bg-white dark:bg-dark-bg border border-terracotta/20 shadow-sm flex items-center justify-center text-forest">
                                                <CheckCircle2 class="w-5 h-5" />
                                            </div>
                                        </div>
                                        <div v-else class="text-xs font-bold text-warm-muted dark:text-dark-muted italic">
                                            Awaiting Results
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr v-if="exams.data.length === 0">
                                <td colspan="5" class="py-20 text-center">
                                    <div class="w-20 h-20 bg-terracotta/5 rounded-3xl flex items-center justify-center mx-auto mb-4">
                                        <FileText class="w-10 h-10 text-terracotta/30" />
                                    </div>
                                    <p class="text-warm-muted dark:text-dark-muted font-bold">No assessments found for this period.</p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="p-8 border-t border-terracotta/20 bg-terracotta/5">
                    <Pagination 
                        :data="exams" 
                        @page-change="handlePageChange"
                    />
                </div>
            </div>
        </div>
    </Sidebar>
</template>