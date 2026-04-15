<script setup>
import { Head, Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import Sidebar from '@/Components/Sidebar.vue';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/Components/ui/card';
import { Badge } from '@/Components/ui/badge';
import { Button } from '@/Components/ui/button';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuTrigger,
} from '@/Components/ui/dropdown-menu';
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
        case 'completed': return 'bg-forest/10 text-forest border-forest/20';
        case 'ongoing':   return 'bg-amber/10 text-amber border-amber/20';
        default:          return 'bg-terracotta/10 text-terracotta border-terracotta/20';
    }
};
</script>

<template>
    <Head :title="exam.name + ' - Assessment Details'" />

    <Sidebar>
        <template #header-title>
            <div class="flex items-center gap-2">
                <Link :href="route('exams.index')">
                    <Button variant="ghost" size="sm" class="rounded-xl hover:bg-terracotta/10 text-warm-text text-dark-text">
                        <ArrowLeft class="w-4 h-4 mr-2" />
                        Assessments
                    </Button>
                </Link>
                <span class="text-warm-muted text-dark-muted">/</span>
                <span class="font-black text-sm uppercase tracking-wider text-warm-muted text-dark-muted">Exam Intelligence</span>
            </div>
        </template>

        <div class="space-y-8 animate-fade-in-up">
            <!-- Header -->
            <div class="relative overflow-hidden card-warm rounded-[3rem] p-8 md:p-12">
                <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-8">
                    <div class="flex items-center gap-8">
                        <div class="w-24 h-24 bg-terracotta/5 rounded-[2rem] flex items-center justify-center border border-terracotta/20 shadow-sm">
                            <Trophy class="w-12 h-12 text-amber" />
                        </div>
                        <div>
                            <div class="flex items-center gap-2 mb-2">
                                <Badge :class="['rounded-lg px-3 py-1 text-[10px] font-black uppercase tracking-widest border-0 shadow-sm', getStatusColor(exam.status)]">
                                    {{ exam.status }}
                                </Badge>
                                <span class="text-[10px] font-black text-warm-muted text-dark-muted uppercase tracking-widest">Term: {{ exam.term || 'Primary' }}</span>
                            </div>
                            <h1 class="text-4xl md:text-5xl font-black text-warm-text text-dark-text mb-2 tracking-tighter">
                                {{ exam.name }}
                            </h1>
                            <p class="text-lg text-warm-muted text-dark-muted font-medium">
                                Professional examination registry and assessment scheduling.
                            </p>
                        </div>
                    </div>

                    <div v-if="isAdminOrTeacher" class="flex flex-wrap items-center gap-3">
                        <Link :href="route('exams.edit', exam.id)">
                            <Button class="accent-terracotta text-white font-black rounded-2xl h-14 px-8 shadow-md shadow-terracotta/30">
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
                    <div class="card-warm rounded-[3rem] overflow-hidden">
                        <div class="p-8 border-b border-terracotta/20">
                            <h3 class="text-2xl font-black tracking-tighter flex items-center gap-3 text-warm-text text-dark-text">
                                <ShieldCheck class="w-6 h-6 text-terracotta" />
                                Examination Integrity & Details
                            </h3>
                        </div>
                        <div class="p-8">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                                <!-- Time Window -->
                                <div class="md:col-span-2 p-6 rounded-[2rem] bg-terracotta/5 border border-terracotta/20 group">
                                    <div class="flex items-center gap-4 mb-4">
                                        <div class="w-10 h-10 rounded-xl bg-amber/10 flex items-center justify-center text-amber group-hover:scale-110 transition-transform">
                                            <Clock class="w-5 h-5" />
                                        </div>
                                        <h4 class="font-black text-warm-text text-dark-text uppercase tracking-tighter">Time Window</h4>
                                    </div>
                                    <p class="text-2xl font-black text-warm-text text-dark-text tracking-tight">
                                        {{ exam.start_time }} — {{ exam.end_time }}
                                    </p>
                                </div>

                                <!-- Max Score -->
                                <div class="p-6 rounded-[2rem] bg-terracotta/5 border border-terracotta/20 group">
                                    <div class="flex items-center gap-4 mb-4">
                                        <div class="w-10 h-10 rounded-xl bg-forest/10 flex items-center justify-center text-forest group-hover:scale-110 transition-transform">
                                            <Target class="w-5 h-5" />
                                        </div>
                                        <h4 class="font-black text-warm-text text-dark-text uppercase tracking-tighter">Maximum Score</h4>
                                    </div>
                                    <p class="text-4xl font-black text-forest tracking-tighter">
                                        {{ exam.max_marks }} Pts
                                    </p>
                                </div>

                                <!-- System Priority -->
                                <div class="p-6 rounded-[2rem] accent-terracotta text-white shadow-xl shadow-terracotta/30">
                                    <div class="flex items-center gap-4 mb-4">
                                        <div class="w-10 h-10 rounded-xl bg-white/20 dark:bg-white/10 flex items-center justify-center">
                                            <Zap class="w-5 h-5" />
                                        </div>
                                        <h4 class="font-black uppercase tracking-tighter">System Priority</h4>
                                    </div>
                                    <p class="text-sm font-bold opacity-90">
                                        High-priority academic assessment. Ensure all materials are prepared 30 minutes prior to start time.
                                    </p>
                                </div>

                            </div>
                        </div>
                    </div>

                    <!-- Student Results Overview -->
                    <div v-if="!isAdminOrTeacher && exam.status === 'completed'" class="card-warm rounded-[3rem] overflow-hidden bg-gradient-to-br from-forest/5 to-transparent">
                        <div class="p-8">
                            <h3 class="text-2xl font-black tracking-tighter flex items-center gap-3 text-warm-text text-dark-text">
                                <CheckCircle2 class="w-6 h-6 text-forest" />
                                My Performance Scorecard
                            </h3>
                        </div>
                        <div class="p-8 pt-0">
                            <div class="flex items-center justify-between p-8 bg-white bg-dark-bg rounded-[2.5rem] border border-forest/20">
                                <div>
                                    <p class="text-[10px] font-black text-warm-muted text-dark-muted uppercase tracking-widest mb-1">Final Result</p>
                                    <h3 class="text-5xl font-black italic tracking-tighter text-forest">A-</h3>
                                </div>
                                <div class="text-right">
                                    <p class="text-[10px] font-black text-warm-muted text-dark-muted uppercase tracking-widest mb-1">Total Points</p>
                                    <h3 class="text-3xl font-black text-warm-text text-dark-text tracking-tighter">88 / 100</h3>
                                </div>
                            </div>
                        </div>
                    </div>

                </div><!-- end lg:col-span-2 -->

                <!-- Right Sidebar -->
                <div class="space-y-8">
                    <div class="card-warm rounded-[3rem] p-8">
                        <h3 class="text-xl font-black text-warm-text text-dark-text tracking-tighter mb-8 bg-terracotta/5 rounded-xl px-4 py-1 inline-block">Exam Context</h3>

                        <div class="space-y-6">
                            <div class="flex items-center justify-between p-4 rounded-2xl bg-terracotta/5 border border-terracotta/20 group">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 rounded-xl bg-terracotta/10 flex items-center justify-center text-terracotta group-hover:scale-110 transition-transform">
                                        <BookOpen class="w-5 h-5" />
                                    </div>
                                    <div>
                                        <p class="text-[10px] font-black text-warm-muted text-dark-muted uppercase tracking-widest">Subject</p>
                                        <p class="text-sm font-black text-warm-text text-dark-text">{{ exam.subject?.name || 'General' }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="flex items-center justify-between p-4 rounded-2xl bg-terracotta/5 border border-terracotta/20 group">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 rounded-xl bg-amber/10 flex items-center justify-center text-amber group-hover:scale-110 transition-transform">
                                        <Award class="w-5 h-5" />
                                    </div>
                                    <div>
                                        <p class="text-[10px] font-black text-warm-muted text-dark-muted uppercase tracking-widest">Weightage</p>
                                        <p class="text-sm font-black text-warm-text text-dark-text">35% of Total</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <Link :href="route('exams.index')" class="block mt-8">
                            <Button variant="outline" class="w-full h-12 border-terracotta/20 text-warm-muted text-dark-muted hover:text-terracotta hover:bg-terracotta/5 font-black rounded-2xl">
                                <ArrowLeft class="w-4 h-4 mr-2" />
                                Return to List
                            </Button>
                        </Link>
                    </div>

                    <!-- Staff Console -->
                    <div v-if="isAdminOrTeacher" class="card-warm rounded-[3rem] p-8 bg-gradient-to-br from-terracotta/5 to-transparent">
                        <h3 class="text-xl font-black text-warm-text text-dark-text tracking-tighter mb-6">Staff Console</h3>
                        <div class="grid grid-cols-1 gap-4">
                            <Link :href="route('exams.grades.enter', exam.id)">
                                <Button class="w-full h-12 accent-terracotta text-white font-black rounded-2xl shadow-lg shadow-terracotta/30">
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