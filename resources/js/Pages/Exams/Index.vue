<script setup>
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import { ref, watch, computed } from 'vue';
import Sidebar from '@/Components/Sidebar.vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';
import Pagination from '@/components/ui/pagination.vue';
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
    Zap
} from 'lucide-vue-next';

const props = defineProps({
    exams: Object,
    filters: Object
});

const page = usePage();
const user = computed(() => page.props.auth.user);
const userRoles = computed(() => user.value?.roles || []);
const isAdminOrTeacher = computed(() => userRoles.value.some(r => ['admin', 'teacher'].includes(r)));

const searchQuery = ref(props.filters?.search || '');

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
        case 'completed': return 'bg-emerald-500/10 text-emerald-600 border-emerald-500/20';
        case 'ongoing': return 'bg-amber-500/10 text-amber-600 border-amber-500/20';
        default: return 'bg-blue-500/10 text-blue-600 border-blue-500/20';
    }
};

const getGradeColor = (grade) => {
    if (!grade) return 'text-slate-400';
    if (grade.startsWith('A')) return 'text-emerald-600';
    if (grade.startsWith('B')) return 'text-blue-600';
    if (grade.startsWith('C')) return 'text-amber-600';
    return 'text-rose-600';
};
</script>

<template>
    <Head :title="isAdminOrTeacher ? 'Exam Management' : 'My Academic Results'" />

    <Sidebar>
        <template #header-title>
            <div class="flex items-center gap-2">
                <div class="p-2 bg-indigo-600/10 rounded-lg">
                    <Trophy class="w-4 h-4 text-indigo-600" />
                </div>
                <span class="font-black text-sm uppercase tracking-wider text-gray-500">
                    {{ isAdminOrTeacher ? 'Examination Hub' : 'Personal Performance' }}
                </span>
            </div>
        </template>

        <div class="space-y-8 animate-fade-in-up">
            <!-- Dramatic Header Section -->
            <div class="relative overflow-hidden glass-card rounded-[2.5rem] border-white/20 p-8 md:p-12">
                <div class="absolute -top-24 -right-24 w-64 h-64 bg-indigo-600/10 blur-[80px] rounded-full animate-float"></div>
                <div class="absolute -bottom-24 -left-24 w-64 h-64 bg-violet-600/10 blur-[80px] rounded-full animate-float" style="animation-delay: 2s"></div>
                
                <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-8">
                    <div>
                        <h1 class="text-4xl md:text-5xl font-black text-gray-900 dark:text-white mb-2 tracking-tighter">
                            {{ isAdminOrTeacher ? 'Exam Schedule' : 'My Results' }}
                        </h1>
                        <p class="text-lg text-gray-500 dark:text-gray-400 font-medium">
                            {{ isAdminOrTeacher 
                                ? 'Coordinate academic assessments and monitor student grading cycles.' 
                                : 'Track your academic milestones and review your performance trends.' }}
                        </p>
                    </div>
                    
                    <div v-if="isAdminOrTeacher" class="flex items-center gap-3">
                        <Link :href="route('exams.create')">
                            <Button class="bg-indigo-600 hover:bg-indigo-700 text-white font-black rounded-2xl h-14 px-8 shadow-xl shadow-indigo-500/20">
                                <Plus class="w-5 h-5 mr-2" />
                                Schedule Assessment
                            </Button>
                        </Link>
                    </div>
                </div>
            </div>

            <!-- Dashboard Stats -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div class="glass-card p-6 rounded-[2rem] border-white/10 group">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 rounded-2xl bg-indigo-500/10 flex items-center justify-center text-indigo-600">
                            <Target class="w-6 h-6" />
                        </div>
                        <span class="text-2xl font-black tracking-tighter">{{ exams.total || 0 }}</span>
                    </div>
                    <p class="text-[10px] font-black uppercase text-gray-400 tracking-widest">Total Assessments</p>
                </div>
                
                <div class="glass-card p-6 rounded-[2rem] border-white/10 group">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 rounded-2xl bg-emerald-500/10 flex items-center justify-center text-emerald-600">
                            <TrendingUp class="w-6 h-6" />
                        </div>
                        <span class="text-2xl font-black tracking-tighter text-emerald-600">
                            {{ isAdminOrTeacher ? '82%' : '85.4%' }}
                        </span>
                    </div>
                    <p class="text-[10px] font-black uppercase text-gray-400 tracking-widest">
                        {{ isAdminOrTeacher ? 'Class Average' : 'Personal GPA' }}
                    </p>
                </div>

                <div class="glass-card p-6 rounded-[2rem] border-white/10 group">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 rounded-2xl bg-blue-500/10 flex items-center justify-center text-blue-600">
                            <Zap class="w-6 h-6" />
                        </div>
                        <span class="text-2xl font-black tracking-tighter">
                            {{ exams.data.filter(e => e.status === 'ongoing').length }}
                        </span>
                    </div>
                    <p class="text-[10px] font-black uppercase text-gray-400 tracking-widest">Ongoing Exams</p>
                </div>

                <div class="glass-card p-6 rounded-[2rem] border-white/10 group">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 rounded-2xl bg-amber-500/10 flex items-center justify-center text-amber-600">
                            <Award class="w-6 h-6" />
                        </div>
                        <span class="text-2xl font-black tracking-tighter">Top 5</span>
                    </div>
                    <p class="text-[10px] font-black uppercase text-gray-400 tracking-widest">Academic Standing</p>
                </div>
            </div>

            <!-- Main Table Card -->
            <Card class="glass shadow-2xl border-white/10 rounded-[3rem] overflow-hidden">
                <CardHeader class="p-8 border-b border-white/5 bg-white/50 dark:bg-white/5">
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                        <div>
                            <CardTitle class="text-2xl font-black tracking-tighter">
                                {{ isAdminOrTeacher ? 'Assessment Inventory' : 'My Performance Ledger' }}
                            </CardTitle>
                            <CardDescription class="font-medium text-gray-500">
                                {{ isAdminOrTeacher ? 'Comprehensive list of all scheduled and previous exams' : 'View your detailed subject-wise breakdown' }}
                            </CardDescription>
                        </div>
                        <div class="flex items-center gap-2">
                            <div class="relative group">
                                <Search class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 group-focus-within:text-indigo-500 transition-colors" />
                                <input
                                    type="text"
                                    v-model="searchQuery"
                                    placeholder="Search assessments..."
                                    class="pl-10 pr-4 h-12 w-64 glass bg-white/50 border-white/10 rounded-2xl text-sm font-bold focus:ring-4 focus:ring-indigo-500/10 transition-all outline-none"
                                />
                            </div>
                            <Button variant="outline" class="h-12 w-12 glass rounded-2xl border-white/10">
                                <Filter class="w-4 h-4" />
                            </Button>
                        </div>
                    </div>
                </CardHeader>
                <CardContent class="p-0">
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="bg-gray-50/50 dark:bg-slate-900/50 text-left border-b border-white/10">
                                    <th class="py-5 px-8 text-[10px] font-black uppercase text-gray-400 tracking-widest">Exam & Subject</th>
                                    <th v-if="isAdminOrTeacher" class="py-5 px-8 text-[10px] font-black uppercase text-gray-400 tracking-widest">Class</th>
                                    <th class="py-5 px-8 text-[10px] font-black uppercase text-gray-400 tracking-widest">Schedule</th>
                                    <th class="py-5 px-8 text-[10px] font-black uppercase text-gray-400 tracking-widest text-center">Status</th>
                                    <th :class="isAdminOrTeacher ? 'text-right' : 'text-center'" class="py-5 px-8 text-[10px] font-black uppercase text-gray-400 tracking-widest">
                                        {{ isAdminOrTeacher ? 'Actions' : 'Performance' }}
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-white/5">
                                <tr v-for="exam in exams.data" :key="exam.id" class="hover:bg-white/5 dark:hover:bg-slate-800/50 transition-colors group">
                                    <td class="py-6 px-8">
                                        <div class="flex items-center gap-4">
                                            <div class="w-12 h-12 bg-white/50 dark:bg-slate-800 rounded-2xl flex items-center justify-center border border-white/10 group-hover:scale-110 transition-transform">
                                                <div class="w-8 h-8 rounded-xl bg-indigo-500/10 flex items-center justify-center text-indigo-600">
                                                    <FileText class="w-4 h-4" />
                                                </div>
                                            </div>
                                            <div>
                                                <div class="font-black text-gray-900 dark:text-white tracking-tight">{{ exam.name }}</div>
                                                <div class="text-xs font-bold text-gray-400 uppercase tracking-widest">{{ exam.subject?.name || 'General' }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td v-if="isAdminOrTeacher" class="py-6 px-8">
                                        <Badge variant="outline" class="rounded-xl border-white/10 bg-white/50 px-3 font-bold">
                                            {{ exam.academic_class?.name || 'TBA' }}
                                        </Badge>
                                    </td>
                                    <td class="py-6 px-8">
                                        <div class="flex items-center gap-2 text-sm font-bold text-gray-700 dark:text-gray-300">
                                            <Calendar class="w-4 h-4 text-gray-400" />
                                            {{ new Date(exam.date).toLocaleDateString(undefined, { month: 'short', day: 'numeric', year: 'numeric' }) }}
                                        </div>
                                        <div class="flex items-center gap-2 text-[10px] font-black uppercase text-gray-400 mt-1">
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
                                                    <Button variant="ghost" class="w-10 h-10 p-0 rounded-xl hover:bg-white/10">
                                                        <MoreVertical class="w-4 h-4" />
                                                    </Button>
                                                </DropdownMenuTrigger>
                                                <DropdownMenuContent align="end" class="w-56 glass rounded-[1.5rem] p-2 border-white/10">
                                                    <DropdownMenuItem as-child>
                                                        <Link :href="route('exams.show', exam.id)" class="rounded-xl p-3 font-bold gap-3 flex items-center">
                                                            <div class="w-8 h-8 rounded-lg bg-blue-500/10 flex items-center justify-center text-blue-600">
                                                                <Eye class="w-4 h-4" />
                                                            </div>
                                                            View Profile
                                                        </Link>
                                                    </DropdownMenuItem>
                                                    <DropdownMenuItem as-child>
                                                        <Link :href="route('exams.edit', exam.id)" class="rounded-xl p-3 font-bold gap-3 flex items-center">
                                                            <div class="w-8 h-8 rounded-lg bg-indigo-500/10 flex items-center justify-center text-indigo-600">
                                                                <Edit class="w-4 h-4" />
                                                            </div>
                                                            Modify Schedule
                                                        </Link>
                                                    </DropdownMenuItem>
                                                    <DropdownMenuItem as-child>
                                                        <Link :href="route('exams.grades.enter', exam.id)" class="rounded-xl p-3 font-bold gap-3 flex items-center">
                                                            <div class="w-8 h-8 rounded-lg bg-emerald-500/10 flex items-center justify-center text-emerald-600">
                                                                <BarChart3 class="w-4 h-4" />
                                                            </div>
                                                            Input Marks
                                                        </Link>
                                                    </DropdownMenuItem>
                                                    <Separator class="my-2 bg-white/5" />
                                                    <DropdownMenuItem @click="deleteExam(exam.id)" class="rounded-xl p-3 font-bold gap-3 flex items-center text-rose-600 focus:text-rose-600">
                                                        <div class="w-8 h-8 rounded-lg bg-rose-500/10 flex items-center justify-center">
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
                                                    <div class="text-[9px] font-black uppercase text-gray-400 tracking-widest">GPA: 3.7</div>
                                                </div>
                                                <div class="w-10 h-10 rounded-xl glass border-emerald-500/20 flex items-center justify-center text-emerald-600">
                                                    <CheckCircle2 class="w-5 h-5" />
                                                </div>
                                            </div>
                                            <div v-else class="text-xs font-bold text-gray-400 italic">
                                                Awaiting Results
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr v-if="exams.data.length === 0">
                                    <td colspan="5" class="py-20 text-center">
                                        <div class="w-20 h-20 bg-slate-100 dark:bg-slate-800 rounded-3xl flex items-center justify-center mx-auto mb-4">
                                            <FileText class="w-10 h-10 text-slate-300" />
                                        </div>
                                        <p class="text-gray-400 font-bold">No assessments found for this period.</p>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="p-8 border-t border-white/5 bg-gray-50/30 dark:bg-slate-900/30">
                        <Pagination 
                            :data="exams" 
                            @page-change="handlePageChange"
                        />
                    </div>
                </CardContent>
            </Card>
        </div>
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

                                                                
