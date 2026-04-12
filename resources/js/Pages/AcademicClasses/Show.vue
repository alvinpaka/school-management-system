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
                    <Button variant="ghost" size="sm" class="rounded-xl hover:bg-white/10">
                        <ArrowLeft class="w-4 h-4 mr-2" />
                        Infrastructure
                    </Button>
                </Link>
                <span class="text-gray-400">/</span>
                <span class="font-black text-sm uppercase tracking-wider text-gray-500">Class Intelligence</span>
            </div>
        </template>

        <div class="space-y-8 animate-fade-in-up">
            <!-- Professional Header Section -->
            <div class="relative overflow-hidden bg-white dark:bg-slate-900 shadow-sm rounded-[3rem] border border-slate-200 dark:border-slate-800 p-8 md:p-12">
                <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-8">
                    <div class="flex items-center gap-8">
                        <div class="w-24 h-24 bg-slate-50 dark:bg-slate-800 rounded-[2rem] flex items-center justify-center border border-slate-200 dark:border-slate-700 shadow-inner">
                            <GraduationCap class="w-12 h-12 text-indigo-600" />
                        </div>
                        <div>
                            <div class="flex items-center gap-2 mb-2">
                                <Badge class="rounded-lg px-3 py-1 text-[10px] font-black uppercase tracking-widest border-0 bg-indigo-50 text-indigo-600 dark:bg-indigo-900/20">
                                    {{ academicClass.level || 'Standard' }}
                                </Badge>
                                <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Section: 104-A</span>
                            </div>
                            <h1 class="text-4xl md:text-5xl font-black text-gray-900 dark:text-white mb-2 tracking-tighter">
                                {{ academicClass.name }}
                            </h1>
                            <p class="text-lg text-gray-500 dark:text-gray-400 font-medium">
                                Institutional academic group and student collective management.
                            </p>
                        </div>
                    </div>

                    <div v-if="isAdminOrTeacher" class="flex flex-wrap items-center gap-3">
                        <Link :href="route('academic-classes.edit', academicClass.id)">
                            <Button class="bg-indigo-600 hover:bg-indigo-700 text-white font-black rounded-2xl h-14 px-8 shadow-md">
                                <Edit class="w-5 h-5 mr-2" />
                                Edit Class
                            </Button>
                        </Link>
                    </div>
                </div>
            </div>

            <!-- ✅ Two-column grid wrapping BOTH the main content and the sidebar -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                <!-- Left: Main content (spans 2 cols) -->
                <div class="lg:col-span-2 space-y-8">

                    <!-- Sections Grouping -->
                    <div>
                        <h3 class="text-xl font-black text-gray-900 dark:text-white uppercase tracking-tighter mb-4 px-2">Assigned Sections</h3>
                        <div v-if="academicClass.sections?.length > 0" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div
                                v-for="section in academicClass.sections"
                                :key="section.id"
                                class="glass-card p-6 rounded-[2rem] border-white/10 hover:border-white/30 transition-all duration-300 group overflow-hidden relative"
                            >
                                <div class="absolute -top-10 -right-10 w-24 h-24 bg-blue-500/5 blur-[30px] rounded-full group-hover:scale-150 transition-transform"></div>
                                <div class="flex items-center justify-between relative z-10">
                                    <div class="flex items-center gap-4">
                                        <div class="w-12 h-12 rounded-2xl bg-blue-500/10 flex items-center justify-center text-blue-600">
                                            <Layers class="w-6 h-6" />
                                        </div>
                                        <div>
                                            <h4 class="text-xl font-black text-gray-900 dark:text-white tracking-tight">{{ section.name }}</h4>
                                            <p class="text-xs font-bold text-gray-500 uppercase tracking-widest">Enrollment: 42 Students</p>
                                        </div>
                                    </div>
                                    <Badge class="bg-emerald-500/10 text-emerald-600 border-0 font-black">ACTIVE</Badge>
                                </div>
                            </div>
                        </div>
                        <div v-else class="glass-card p-12 rounded-[2rem] border-white/10 text-center">
                            <Layers class="w-12 h-12 text-gray-300 mx-auto mb-4" />
                            <p class="text-gray-500 font-bold uppercase tracking-widest italic">No sections initialized for this class</p>
                        </div>
                    </div>

                    <!-- Academic Performance (Mockup) -->
                    <Card class="glass shadow-2xl border-white/10 rounded-[3.5rem] overflow-hidden">
                        <CardHeader class="p-8 border-b border-white/5 bg-gradient-to-r from-blue-500/5 to-transparent">
                            <CardTitle class="text-2xl font-black tracking-tighter flex items-center gap-3">
                                <Activity class="w-6 h-6 text-blue-600" />
                                Class Learning Metrics
                            </CardTitle>
                        </CardHeader>
                        <CardContent class="p-8">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                <div class="p-8 rounded-[2.5rem] bg-indigo-600 text-white shadow-xl shadow-indigo-500/20 text-center">
                                    <Award class="w-10 h-10 mx-auto mb-4 text-indigo-200" />
                                    <p class="text-[10px] font-black uppercase tracking-widest opacity-80 mb-1">Average Performance</p>
                                    <h3 class="text-5xl font-black italic tracking-tighter">84.5%</h3>
                                </div>
                                <div class="p-8 rounded-[2.5rem] bg-blue-600 text-white shadow-xl shadow-blue-500/20 text-center">
                                    <UserCheck class="w-10 h-10 mx-auto mb-4 text-blue-200" />
                                    <p class="text-[10px] font-black uppercase tracking-widest opacity-80 mb-1">Attendance Rate</p>
                                    <h3 class="text-5xl font-black italic tracking-tighter">96.2%</h3>
                                </div>
                            </div>
                        </CardContent>
                    </Card>

                </div><!-- end lg:col-span-2 -->

                <!-- Right Sidebar (1 col) -->
                <div class="space-y-8">
                    <div class="glass-card rounded-[3rem] border-white/10 p-8">
                        <h3 class="text-xl font-black text-gray-900 dark:text-white tracking-tighter mb-8 bg-blue-500/10 rounded-xl px-4 py-1 inline-block">Vital Statistics</h3>

                        <div class="space-y-6">
                            <div class="flex items-center justify-between p-4 rounded-2xl bg-white/5 border border-white/10 group">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 rounded-xl bg-blue-500/10 flex items-center justify-center text-blue-600 group-hover:scale-110 transition-transform">
                                        <Grid3x3 class="w-5 h-5" />
                                    </div>
                                    <div>
                                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Class Code</p>
                                        <p class="text-sm font-black text-gray-900 dark:text-white">{{ academicClass.code }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="flex items-center justify-between p-4 rounded-2xl bg-white/5 border border-white/10 group">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 rounded-xl bg-emerald-500/10 flex items-center justify-center text-emerald-600 group-hover:scale-110 transition-transform">
                                        <Users class="w-5 h-5" />
                                    </div>
                                    <div>
                                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Total Population</p>
                                        <p class="text-sm font-black text-gray-900 dark:text-white">~160 Students</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <Link :href="route('classes.index')" class="block mt-8">
                            <Button variant="outline" class="w-full h-12 glass border-white/10 text-gray-700 hover:text-blue-600 hover:bg-white/50 font-black rounded-2xl">
                                <ArrowLeft class="w-4 h-4 mr-2" />
                                Back to Infrastructure
                            </Button>
                        </Link>
                    </div>

                    <!-- Section Management Tool -->
                    <div v-if="isAdminOrTeacher" class="rounded-[3rem] bg-gradient-to-br from-blue-700 to-indigo-800 p-8 text-white shadow-2xl relative overflow-hidden group">
                        <div class="absolute -bottom-10 -right-10 w-48 h-48 bg-white/10 blur-[40px] rounded-full group-hover:scale-150 transition-transform duration-1000"></div>
                        <div class="relative z-10 text-center">
                            <div class="w-20 h-20 bg-white/10 rounded-3xl flex items-center justify-center mx-auto mb-6 backdrop-blur-xl border border-white/20">
                                <Layers class="w-10 h-10 text-blue-200" />
                            </div>
                            <h4 class="text-2xl font-black mb-2 tracking-tighter">Infrastructure</h4>
                            <p class="text-blue-100 text-sm font-medium mb-8 leading-relaxed">
                                Manage class sections, seat distribution, and academic allocation.
                            </p>
                            <Button class="w-full bg-white text-blue-600 hover:bg-white/90 font-black rounded-2xl h-12 shadow-2xl">
                                Configure Sections
                            </Button>
                        </div>
                    </div>
                </div><!-- end sidebar -->

            </div><!-- end grid -->
        </div><!-- end space-y-8 -->
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
