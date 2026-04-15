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
    BookOpen,
    Users,
    Grid3x3,
    Edit,
    Layers,
    GraduationCap,
    Clock,
    UserCheck,
    MoreVertical,
    Activity,
    Award
} from 'lucide-vue-next';

const props = defineProps({
    academicClass: {
        type: Object,
        required: true
    }
});

const page = usePage();
const user = computed(() => page.props.auth.user);
const userRoles = computed(() => user.value?.roles || []);
const isAdminOrTeacher = computed(() => userRoles.value.some(r => ['admin', 'teacher'].includes(r)));
</script>

<template>
    <Head :title="academicClass.name + ' - Class Intel'" />

    <Sidebar>
        <template #header-title>
            <div class="flex items-center gap-2">
                <Link :href="route('classes.index')">
                    <Button variant="ghost" size="sm" class="rounded-xl hover:bg-terracotta/10 text-warm-text text-dark-text">
                        <ArrowLeft class="w-4 h-4 mr-2" />
                        Infrastructure
                    </Button>
                </Link>
                <span class="text-warm-muted text-dark-muted">/</span>
                <span class="font-black text-sm uppercase tracking-wider text-warm-muted text-dark-muted">Class Intelligence</span>
            </div>
        </template>

        <div class="space-y-8 animate-fade-in-up">
            <!-- Professional Header Section -->
            <div class="relative overflow-hidden card-warm rounded-[3rem] p-8 md:p-12">
                <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-8">
                    <div class="flex items-center gap-8">
                        <div class="w-24 h-24 bg-terracotta/5 rounded-[2rem] flex items-center justify-center border border-terracotta/20 shadow-inner">
                            <GraduationCap class="w-12 h-12 text-terracotta" />
                        </div>
                        <div>
                            <div class="flex items-center gap-2 mb-2">
                                <Badge class="rounded-lg px-3 py-1 text-[10px] font-black uppercase tracking-widest border-0 bg-terracotta/10 text-terracotta">
                                    {{ academicClass.grade_level ? 'Level ' + academicClass.grade_level : 'Standard' }}
                                </Badge>
                                <span class="text-[10px] font-black text-warm-muted text-dark-muted uppercase tracking-widest">Code: {{ academicClass.code }}</span>
                            </div>
                            <h1 class="text-4xl md:text-5xl font-black text-warm-text text-dark-text mb-2 tracking-tighter">
                                {{ academicClass.name }}
                            </h1>
                            <p class="text-lg text-warm-muted text-dark-muted font-medium">
                                Institutional academic group and student collective management.
                            </p>
                        </div>
                    </div>

                    <div v-if="isAdminOrTeacher" class="flex flex-wrap items-center gap-3">
                        <Link :href="route('classes.edit', academicClass.id)">
                            <Button class="accent-terracotta text-white font-black rounded-2xl h-14 px-8 shadow-md shadow-terracotta/30">
                                <Edit class="w-5 h-5 mr-2" />
                                Edit Class
                            </Button>
                        </Link>
                    </div>
                </div>
            </div>

            <!-- Two-column grid wrapping BOTH the main content and the sidebar -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                <!-- Left: Main content (spans 2 cols) -->
                <div class="lg:col-span-2 space-y-8">

                    <!-- Sections Grouping -->
                    <div>
                        <h3 class="text-xl font-black text-warm-text text-dark-text uppercase tracking-tighter mb-4 px-2">Assigned Sections</h3>
                        <div v-if="academicClass.sections?.length > 0" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div
                                v-for="section in academicClass.sections"
                                :key="section.id"
                                class="card-warm p-6 rounded-[2rem] group transition-all duration-300 overflow-hidden relative"
                            >
                                <div class="absolute -top-10 -right-10 w-24 h-24 bg-terracotta/5 blur-[30px] rounded-full group-hover:scale-150 transition-transform"></div>
                                <div class="flex items-center justify-between relative z-10">
                                    <div class="flex items-center gap-4">
                                        <div class="w-12 h-12 rounded-2xl bg-terracotta/10 flex items-center justify-center text-terracotta">
                                            <Layers class="w-6 h-6" />
                                        </div>
                                        <div>
                                            <h4 class="text-xl font-black text-warm-text text-dark-text tracking-tight">{{ section.name }}</h4>
                                            <div class="flex flex-col gap-0.5 mt-1">
                                                <p class="text-xs font-bold text-warm-muted text-dark-muted uppercase tracking-widest flex items-center gap-1.5">
                                                    <Users class="w-3 h-3 text-terracotta" />
                                                    Enrollment: {{ section.students_count }} Students
                                                </p>
                                                <p class="text-[10px] font-black text-terracotta uppercase tracking-[0.1em] flex items-center gap-1.5">
                                                    <UserCheck class="w-3 h-3" />
                                                    Lead: {{ section.class_teacher?.name || 'Unassigned' }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <Badge class="bg-forest/10 text-forest border-0 font-black">ACTIVE</Badge>
                                </div>
                            </div>
                        </div>
                        <div v-else class="card-warm p-12 rounded-[2rem] text-center">
                            <Layers class="w-12 h-12 text-warm-muted/30 mx-auto mb-4" />
                            <p class="text-warm-muted text-dark-muted font-bold uppercase tracking-widest italic">No sections initialized for this class</p>
                        </div>
                    </div>

                    <!-- Academic Performance (Mockup) -->
                    <div class="card-warm rounded-[3.5rem] overflow-hidden">
                        <div class="p-8 border-b border-terracotta/20 bg-terracotta/5">
                            <h3 class="text-2xl font-black tracking-tighter flex items-center gap-3 text-warm-text text-dark-text">
                                <Activity class="w-6 h-6 text-terracotta" />
                                Class Learning Metrics
                            </h3>
                        </div>
                        <div class="p-8">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                <div class="p-8 rounded-[2.5rem] accent-terracotta text-white shadow-xl shadow-terracotta/30 text-center">
                                    <Award class="w-10 h-10 mx-auto mb-4 text-white/80" />
                                    <p class="text-[10px] font-black uppercase tracking-widest opacity-80 mb-1">Average Performance</p>
                                    <h3 class="text-5xl font-black italic tracking-tighter">84.5%</h3>
                                </div>
                                <div class="p-8 rounded-[2.5rem] bg-forest text-white shadow-xl shadow-forest/30 text-center">
                                    <UserCheck class="w-10 h-10 mx-auto mb-4 text-white/80" />
                                    <p class="text-[10px] font-black uppercase tracking-widest opacity-80 mb-1">Attendance Rate</p>
                                    <h3 class="text-5xl font-black italic tracking-tighter">96.2%</h3>
                                </div>
                            </div>
                        </div>
                    </div>

                </div><!-- end lg:col-span-2 -->

                <!-- Right Sidebar (1 col) -->
                <div class="space-y-8">
                    <div class="card-warm rounded-[3rem] p-8">
                        <h3 class="text-xl font-black text-warm-text text-dark-text tracking-tighter mb-8 bg-terracotta/5 rounded-xl px-4 py-1 inline-block">Vital Statistics</h3>

                        <div class="space-y-6">
                            <div class="flex items-center justify-between p-4 rounded-2xl bg-terracotta/5 border border-terracotta/20 group">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 rounded-xl bg-terracotta/10 flex items-center justify-center text-terracotta group-hover:scale-110 transition-transform">
                                        <Grid3x3 class="w-5 h-5" />
                                    </div>
                                    <div>
                                        <p class="text-[10px] font-black text-warm-muted text-dark-muted uppercase tracking-widest">Class Code</p>
                                        <p class="text-sm font-black text-warm-text text-dark-text">{{ academicClass.code }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="flex items-center justify-between p-4 rounded-2xl bg-terracotta/5 border border-terracotta/20 group">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 rounded-xl bg-forest/10 flex items-center justify-center text-forest group-hover:scale-110 transition-transform">
                                        <Users class="w-5 h-5" />
                                    </div>
                                    <div>
                                        <p class="text-[10px] font-black text-warm-muted text-dark-muted uppercase tracking-widest">Total Population</p>
                                        <p class="text-sm font-black text-warm-text text-dark-text">{{ academicClass.total_population }} Students</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <Link :href="route('classes.index')" class="block mt-8">
                            <Button variant="outline" class="w-full h-12 border-terracotta/20 text-warm-muted text-dark-muted hover:text-terracotta hover:bg-terracotta/5 font-black rounded-2xl">
                                <ArrowLeft class="w-4 h-4 mr-2" />
                                Back to Infrastructure
                            </Button>
                        </Link>
                    </div>

                    <!-- Section Management Tool -->
                    <div v-if="isAdminOrTeacher" class="rounded-[3rem] accent-terracotta p-8 text-white shadow-2xl shadow-terracotta/30 relative overflow-hidden group">
                        <div class="absolute -bottom-10 -right-10 w-48 h-48 bg-white/10 dark:bg-white/5 blur-[40px] rounded-full group-hover:scale-150 transition-transform duration-1000"></div>
                        <div class="relative z-10 text-center">
                            <div class="w-20 h-20 bg-white/10 dark:bg-white/5 rounded-3xl flex items-center justify-center mx-auto mb-6 border border-white/20 dark:border-white/10">
                                <Layers class="w-10 h-10 text-white" />
                            </div>
                            <h4 class="text-2xl font-black mb-2 tracking-tighter">Infrastructure</h4>
                            <p class="text-white/80 text-sm font-medium mb-8 leading-relaxed">
                                Manage class sections, seat distribution, and academic allocation.
                            </p>
                            <Button class="w-full bg-white text-terracotta hover:bg-white/90 font-black rounded-2xl h-12 shadow-2xl">
                                Configure Sections
                            </Button>
                        </div>
                    </div>
                </div><!-- end sidebar -->

            </div><!-- end grid -->
        </div><!-- end space-y-8 -->
    </Sidebar>
</template>