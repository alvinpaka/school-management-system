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
    Edit,
    Tag,
    FileText,
    Layers,
    Clock,
    Award,
    Binary,
    Palette,
    Atom,
    Globe,
    MoreVertical
} from 'lucide-vue-next';

const props = defineProps({
    subject: {
        type: Object,
        required: true
    }
});

const page = usePage();
const user = computed(() => page.props.auth.user);
const userRoles = computed(() => user.value?.roles || []);
const isAdminOrTeacher = computed(() => userRoles.value.some(r => ['admin', 'teacher'].includes(r)));

const getSubjectIcon = (name) => {
    const n = name.toLowerCase();
    if (n.includes('math') || n.includes('calc')) return Binary;
    if (n.includes('science') || n.includes('phys') || n.includes('chem')) return Atom;
    if (n.includes('art') || n.includes('design')) return Palette;
    if (n.includes('language') || n.includes('english') || n.includes('history')) return Globe;
    return BookOpen;
};

const getSubjectColor = (type) => {
    return type === 'Core' || type === 'theory'
        ? 'text-indigo-600 bg-indigo-500/10 border-indigo-500/20' 
        : 'text-amber-600 bg-amber-500/10 border-amber-500/20';
};
</script>

<template>
    <Head :title="subject.name + ' - Course Details'" />

    <Sidebar>
        <template #header-title>
            <div class="flex items-center gap-2">
                <Link :href="route('subjects.index')">
                    <Button variant="ghost" size="sm" class="rounded-xl hover:bg-white/10">
                        <ArrowLeft class="w-4 h-4 mr-2" />
                        Curriculum
                    </Button>
                </Link>
                <span class="text-gray-400">/</span>
                <span class="font-black text-sm uppercase tracking-wider text-gray-500">Course Intelligence</span>
            </div>
        </template>

        <div class="space-y-8 animate-fade-in-up">
            <!-- Professional Header Section -->
            <div class="relative overflow-hidden bg-white dark:bg-slate-900 shadow-sm rounded-[2.5rem] border border-slate-200 dark:border-slate-800 p-8 md:p-12">
                <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-8">
                    <div class="flex items-center gap-8">
                        <div class="w-24 h-24 bg-slate-50 dark:bg-slate-800 rounded-[2rem] flex items-center justify-center border border-slate-200 dark:border-slate-700 shadow-sm">
                            <component :is="getSubjectIcon(subject.name)" class="w-12 h-12 text-indigo-600" />
                        </div>
                        <div>
                            <div class="flex items-center gap-2 mb-2">
                                <Badge :class="['rounded-lg px-3 py-1 text-[10px] font-black uppercase tracking-widest border-0', getSubjectColor(subject.type)]">
                                    {{ subject.type }}
                                </Badge>
                                <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Code: #{{ subject.code }}</span>
                            </div>
                            <h1 class="text-4xl md:text-5xl font-black text-gray-900 dark:text-white mb-2 tracking-tighter">
                                {{ subject.name }}
                            </h1>
                            <p class="text-lg text-gray-500 dark:text-gray-400 font-medium">
                                Academic course specifications and curriculum details.
                            </p>
                        </div>
                    </div>
                    
                    <div v-if="isAdminOrTeacher" class="flex flex-wrap items-center gap-3">
                        <Link :href="route('subjects.edit', subject.id)">
                            <Button class="bg-indigo-600 hover:bg-indigo-700 text-white font-black rounded-2xl h-14 px-8 shadow-md">
                                <Edit class="w-5 h-5 mr-2" />
                                Edit Course
                            </Button>
                        </Link>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Main Intelligence -->
                <div class="lg:col-span-2 space-y-8">
                    <Card class="bg-white dark:bg-slate-900 shadow-sm border-slate-200 dark:border-slate-800 rounded-[3rem] overflow-hidden">
                        <CardHeader class="p-8 border-b border-slate-50 dark:border-slate-800">
                            <CardTitle class="text-2xl font-black tracking-tighter flex items-center gap-3">
                                <FileText class="w-6 h-6 text-indigo-600" />
                                Course Syllabus & Overview
                            </CardTitle>
                        </CardHeader>
                        <CardContent class="p-8">
                            <div class="prose prose-slate dark:prose-invert max-w-none">
                                <p class="text-lg text-gray-600 dark:text-gray-300 leading-relaxed font-medium">
                                    {{ subject.description || 'This course offers a comprehensive deep-dive into the core principles of ' + subject.name + '. Students will engage with both historical frameworks and contemporary applications, ensuring a robust mastery of the subject matter.' }}
                                </p>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mt-12">
                                    <div class="p-6 rounded-[2rem] bg-slate-50/50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-700">
                                        <div class="flex items-center gap-4 mb-4">
                                            <div class="w-10 h-10 rounded-xl bg-blue-50 dark:bg-blue-900/20 flex items-center justify-center text-blue-600">
                                                <Award class="w-5 h-5" />
                                            </div>
                                            <h4 class="font-black text-gray-900 dark:text-white uppercase tracking-tighter">Learning Objectives</h4>
                                        </div>
                                        <ul class="space-y-2 text-sm text-gray-500 font-bold">
                                            <li>• Master fundamental theoretical frameworks</li>
                                            <li>• Apply concepts to real-world scenarios</li>
                                            <li>• Develop critical analytical methodologies</li>
                                        </ul>
                                    </div>
                                    <div class="p-6 rounded-[2rem] bg-slate-50/50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-700">
                                        <div class="flex items-center gap-4 mb-4">
                                            <div class="w-10 h-10 rounded-xl bg-orange-50 dark:bg-orange-900/20 flex items-center justify-center text-orange-600">
                                                <Layers class="w-5 h-5" />
                                            </div>
                                            <h4 class="font-black text-gray-900 dark:text-white uppercase tracking-tighter">Assessment Method</h4>
                                        </div>
                                        <p class="text-sm text-gray-500 font-bold">
                                            Continuous assessment through periodic examinations, project-based learning, and interactive class participation modules.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </CardContent>
                    </Card>
                </div>

                <!-- Course Stats -->
                <div class="space-y-8">
                    <div class="bg-white dark:bg-slate-900 rounded-[3rem] border border-slate-200 dark:border-slate-800 p-8 shadow-sm">
                        <h3 class="text-xl font-black text-gray-900 dark:text-white tracking-tighter mb-8 bg-indigo-50 dark:bg-indigo-900/20 rounded-xl px-4 py-1 inline-block">Course Metrics</h3>
                        
                        <div class="space-y-6">
                            <div class="flex items-center justify-between p-4 rounded-2xl bg-slate-50 dark:bg-slate-800 border border-slate-100 dark:border-slate-700 group">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 rounded-xl bg-indigo-50 dark:bg-indigo-900/20 flex items-center justify-center text-indigo-600 group-hover:scale-110 transition-transform">
                                        <Binary class="w-5 h-5" />
                                    </div>
                                    <div>
                                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Course Code</p>
                                        <p class="text-sm font-black text-gray-900 dark:text-white">{{ subject.code }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="flex items-center justify-between p-4 rounded-2xl bg-slate-50 dark:bg-slate-800 border border-slate-100 dark:border-slate-700 group">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 rounded-xl bg-emerald-50 dark:bg-emerald-900/20 flex items-center justify-center text-emerald-600 group-hover:scale-110 transition-transform">
                                        <Clock class="w-5 h-5" />
                                    </div>
                                    <div>
                                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Credit Load</p>
                                        <p class="text-sm font-black text-gray-900 dark:text-white">{{ subject.credits || 3 }} Units</p>
                                    </div>
                                </div>
                            </div>

                            <div class="flex items-center justify-between p-4 rounded-2xl bg-slate-50 dark:bg-slate-800 border border-slate-100 dark:border-slate-700 group">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 rounded-xl bg-amber-50 dark:bg-amber-900/20 flex items-center justify-center text-amber-600 group-hover:scale-110 transition-transform">
                                        <Users class="w-5 h-5" />
                                    </div>
                                    <div>
                                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Student Enrollment</p>
                                        <p class="text-sm font-black text-gray-900 dark:text-white">124 Enrolled</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <Link :href="route('subjects.index')" class="block mt-8">
                            <Button variant="outline" class="w-full h-12 border-slate-100 dark:border-slate-800 text-gray-700 dark:text-gray-300 hover:text-indigo-600 dark:hover:text-indigo-400 font-black rounded-2xl">
                                <ArrowLeft class="w-4 h-4 mr-2" />
                                Back to Curriculum
                            </Button>
                        </Link>
                    </div>

                    <!-- Faculty / Mentor -->
                    <div class="bg-white dark:bg-slate-900 rounded-[3rem] border border-slate-200 dark:border-slate-800 p-8 shadow-sm">
                        <h3 class="text-xl font-black text-gray-900 dark:text-white tracking-tighter mb-6">Principal Mentor</h3>
                        <div class="flex items-center gap-4">
                            <div class="w-16 h-16 rounded-[1.5rem] bg-indigo-600 flex items-center justify-center text-white font-black text-2xl shadow-md">
                                DR
                            </div>
                            <div>
                                <h4 class="text-lg font-black text-gray-900 dark:text-white leading-none mb-1">Dr. Alex Rivers</h4>
                                <p class="text-xs font-bold text-gray-500 uppercase tracking-wider">Senior Academic Lead</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
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
