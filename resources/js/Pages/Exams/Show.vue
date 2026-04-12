<script setup>
import { Head, Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import Sidebar from '@/Components/Sidebar.vue';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';
import { 
    ArrowLeft,
    Calendar,
    Clock,
    Trophy,
    BookOpen,
    User,
    Edit,
    Target,
    Zap,
    MoreVertical,
    FileText,
    BarChart3,
    CheckCircle2,
    ShieldCheck,
    Award
} from 'lucide-vue-next';

const props = defineProps({
    exam: {
        type: Object,
        required: true
    }
});

const page = usePage();
const user = computed(() => page.props.auth.user);
const userRoles = computed(() => user.value?.roles || []);
const isAdminOrTeacher = computed(() => userRoles.value.some(r => ['admin', 'teacher'].includes(r)));

const getStatusColor = (status) => {
    switch (status) {
        case 'completed': return 'bg-emerald-500/10 text-emerald-600 border-emerald-500/20';
        case 'ongoing':   return 'bg-amber-500/10 text-amber-600 border-amber-500/20';
        default:          return 'bg-blue-500/10 text-blue-600 border-blue-500/20';
    }
};
</script>

<template>
    <Head :title="exam.name + ' - Assessment Details'" />

    <Sidebar>
        <template #header-title>
            <div class="flex items-center gap-2">
                <Link :href="route('exams.index')">
                    <Button variant="ghost" size="sm" class="rounded-xl hover:bg-white/10">
                        <ArrowLeft class="w-4 h-4 mr-2" />
                        Assessments
                    </Button>
                </Link>
                <span class="text-gray-400">/</span>
                <span class="font-black text-sm uppercase tracking-wider text-gray-500">Exam Intelligence</span>
            </div>
        </template>

        <div class="space-y-8 animate-fade-in-up">
            <!-- Header -->
            <div class="relative overflow-hidden bg-white dark:bg-slate-900 shadow-sm rounded-[3rem] border border-slate-200 dark:border-slate-800 p-8 md:p-12">
                <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-8">
                    <div class="flex items-center gap-8">
                        <div class="w-24 h-24 bg-slate-50 dark:bg-slate-800 rounded-[2rem] flex items-center justify-center border border-slate-200 dark:border-slate-700 shadow-sm">
                            <Trophy class="w-12 h-12 text-amber-500" />
                        </div>
                        <div>
                            <div class="flex items-center gap-2 mb-2">
                                <Badge :class="['rounded-lg px-3 py-1 text-[10px] font-black uppercase tracking-widest border-0 shadow-sm', getStatusColor(exam.status)]">
                                    {{ exam.status }}
                                </Badge>
                                <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Term: {{ exam.term || 'Primary' }}</span>
                            </div>
                            <h1 class="text-4xl md:text-5xl font-black text-gray-900 dark:text-white mb-2 tracking-tighter">
                                {{ exam.name }}
                            </h1>
                            <p class="text-lg text-gray-500 dark:text-gray-400 font-medium">
                                Professional examination registry and assessment scheduling.
                            </p>
                        </div>
                    </div>

                    <div v-if="isAdminOrTeacher" class="flex flex-wrap items-center gap-3">
                        <Link :href="route('exams.edit', exam.id)">
                            <Button class="bg-indigo-600 hover:bg-indigo-700 text-white font-black rounded-2xl h-14 px-8 shadow-md">
                                <Edit class="w-5 h-5 mr-2" />
                                Edit Schedule
                            </Button>
                        </Link>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Main Content -->
                <div class="lg:col-span-2 space-y-8">

                    <!-- Examination Details Card -->
                    <Card class="bg-white dark:bg-slate-900 shadow-sm border-slate-200 dark:border-slate-800 rounded-[3rem] overflow-hidden">
                        <CardHeader class="p-8 border-b border-slate-50 dark:border-slate-800">
                            <CardTitle class="text-2xl font-black tracking-tighter flex items-center gap-3">
                                <ShieldCheck class="w-6 h-6 text-indigo-600" />
                                Examination Integrity & Details
                            </CardTitle>
                        </CardHeader>
                        <CardContent class="p-8">
                            <!-- ✅ Single grid wrapping all three detail blocks -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                                <!-- Time Window -->
                                <div class="md:col-span-2 p-6 rounded-[2rem] bg-gray-50/50 dark:bg-white/5 border border-white/10 group">
                                    <div class="flex items-center gap-4 mb-4">
                                        <div class="w-10 h-10 rounded-xl bg-orange-500/10 flex items-center justify-center text-orange-600 group-hover:scale-110 transition-transform">
                                            <Clock class="w-5 h-5" />
                                        </div>
                                        <h4 class="font-black text-gray-900 dark:text-white uppercase tracking-tighter">Time Window</h4>
                                    </div>
                                    <p class="text-2xl font-black text-gray-900 dark:text-white tracking-tight">
                                        {{ exam.start_time }} — {{ exam.end_time }}
                                    </p>
                                </div>

                                <!-- Max Score -->
                                <div class="p-6 rounded-[2rem] bg-gray-50/50 dark:bg-white/5 border border-white/10 group">
                                    <div class="flex items-center gap-4 mb-4">
                                        <div class="w-10 h-10 rounded-xl bg-emerald-500/10 flex items-center justify-center text-emerald-600 group-hover:scale-110 transition-transform">
                                            <Target class="w-5 h-5" />
                                        </div>
                                        <h4 class="font-black text-gray-900 dark:text-white uppercase tracking-tighter">Maximum Score</h4>
                                    </div>
                                    <p class="text-4xl font-black text-emerald-600 tracking-tighter">
                                        {{ exam.max_marks }} Pts
                                    </p>
                                </div>

                                <!-- System Priority -->
                                <div class="p-6 rounded-[2rem] bg-indigo-600 text-white shadow-xl shadow-indigo-500/20">
                                    <div class="flex items-center gap-4 mb-4">
                                        <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center">
                                            <Zap class="w-5 h-5" />
                                        </div>
                                        <h4 class="font-black uppercase tracking-tighter">System Priority</h4>
                                    </div>
                                    <p class="text-sm font-bold opacity-90">
                                        High-priority academic assessment. Ensure all materials are prepared 30 minutes prior to start time.
                                    </p>
                                </div>

                            </div>
                        </CardContent>
                    </Card>

                    <!-- Student Results Overview -->
                    <Card v-if="!isAdminOrTeacher && exam.status === 'completed'" class="glass shadow-2xl border-white/10 rounded-[3rem] overflow-hidden bg-gradient-to-br from-emerald-500/5 to-transparent">
                        <CardHeader class="p-8">
                            <CardTitle class="text-2xl font-black tracking-tighter flex items-center gap-3">
                                <CheckCircle2 class="w-6 h-6 text-emerald-600" />
                                My Performance Scorecard
                            </CardTitle>
                        </CardHeader>
                        <CardContent class="p-8 pt-0">
                            <div class="flex items-center justify-between p-8 glass bg-white/50 dark:bg-slate-900/50 rounded-[2.5rem] border-emerald-500/20">
                                <div>
                                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Final Result</p>
                                    <h3 class="text-5xl font-black italic tracking-tighter text-emerald-600">A-</h3>
                                </div>
                                <div class="text-right">
                                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Total Points</p>
                                    <h3 class="text-3xl font-black text-gray-900 dark:text-white tracking-tighter">88 / 100</h3>
                                </div>
                            </div>
                        </CardContent>
                    </Card>

                </div><!-- end lg:col-span-2 -->

                <!-- Right Sidebar -->
                <div class="space-y-8">
                    <div class="glass-card rounded-[3rem] border-white/10 p-8">
                        <h3 class="text-xl font-black text-gray-900 dark:text-white tracking-tighter mb-8 bg-indigo-500/10 rounded-xl px-4 py-1 inline-block">Exam Context</h3>

                        <div class="space-y-6">
                            <div class="flex items-center justify-between p-4 rounded-2xl bg-white/5 border border-white/10 group">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 rounded-xl bg-blue-500/10 flex items-center justify-center text-blue-600 group-hover:scale-110 transition-transform">
                                        <BookOpen class="w-5 h-5" />
                                    </div>
                                    <div>
                                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Subject</p>
                                        <p class="text-sm font-black text-gray-900 dark:text-white">{{ exam.subject?.name || 'General' }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="flex items-center justify-between p-4 rounded-2xl bg-white/5 border border-white/10 group">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 rounded-xl bg-orange-500/10 flex items-center justify-center text-orange-600 group-hover:scale-110 transition-transform">
                                        <Award class="w-5 h-5" />
                                    </div>
                                    <div>
                                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Weightage</p>
                                        <p class="text-sm font-black text-gray-900 dark:text-white">35% of Total</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <Link :href="route('exams.index')" class="block mt-8">
                            <Button variant="outline" class="w-full h-12 glass border-white/10 text-gray-700 hover:text-indigo-600 hover:bg-white/50 font-black rounded-2xl">
                                <ArrowLeft class="w-4 h-4 mr-2" />
                                Return to List
                            </Button>
                        </Link>
                    </div>

                    <!-- Staff Console -->
                    <div v-if="isAdminOrTeacher" class="glass-card rounded-[3rem] border-white/10 p-8 bg-gradient-to-br from-indigo-600/5 to-transparent">
                        <h3 class="text-xl font-black text-gray-900 dark:text-white tracking-tighter mb-6">Staff Console</h3>
                        <div class="grid grid-cols-1 gap-4">
                            <Link :href="route('exams.grades.enter', exam.id)">
                                <Button class="w-full h-12 bg-emerald-600 hover:bg-emerald-700 text-white font-black rounded-2xl shadow-lg shadow-emerald-500/20">
                                    <BarChart3 class="w-4 h-4 mr-2" />
                                    Input Results
                                </Button>
                            </Link>
                        </div>
                    </div>
                </div><!-- end sidebar -->

            </div><!-- end grid -->
        </div><!-- end space-y-8 -->
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