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
        ? 'text-terracotta bg-terracotta/10 border-terracotta/20' 
        : 'text-amber bg-amber/10 border-amber/20';
};
</script>

<template>
    <Head :title="subject.name + ' - Course Details'" />

    <Sidebar>
        <template #header-title>
            <div class="flex items-center gap-2">
                <Link :href="route('subjects.index')">
                    <Button variant="ghost" size="sm" class="rounded-xl hover:bg-terracotta/10 text-warm-text text-dark-text">
                        <ArrowLeft class="w-4 h-4 mr-2" />
                        Curriculum
                    </Button>
                </Link>
                <span class="text-warm-muted text-dark-muted">/</span>
                <span class="font-black text-sm uppercase tracking-wider text-warm-muted text-dark-muted">Course Intelligence</span>
            </div>
        </template>

        <div class="space-y-8 animate-fade-in-up">
            <!-- Professional Header Section -->
            <div class="relative overflow-hidden card-warm rounded-[2.5rem] p-8 md:p-12">
                <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-8">
                    <div class="flex items-center gap-8">
                        <div class="w-24 h-24 bg-terracotta/5 rounded-[2rem] flex items-center justify-center border border-terracotta/20 shadow-sm">
                            <component :is="getSubjectIcon(subject.name)" class="w-12 h-12 text-terracotta" />
                        </div>
                        <div>
                            <div class="flex items-center gap-2 mb-2">
                                <Badge :class="['rounded-lg px-3 py-1 text-[10px] font-black uppercase tracking-widest border-0', getSubjectColor(subject.type)]">
                                    {{ subject.type }}
                                </Badge>
                                <span class="text-[10px] font-black text-warm-muted text-dark-muted uppercase tracking-widest">Code: #{{ subject.code }}</span>
                            </div>
                            <h1 class="text-4xl md:text-5xl font-black text-warm-text text-dark-text mb-2 tracking-tighter">
                                {{ subject.name }}
                            </h1>
                            <p class="text-lg text-warm-muted text-dark-muted font-medium">
                                Academic course specifications and curriculum details.
                            </p>
                        </div>
                    </div>
                    
                    <div v-if="isAdminOrTeacher" class="flex flex-wrap items-center gap-3">
                        <Link :href="route('subjects.edit', subject.id)">
                            <Button class="accent-terracotta text-white font-black rounded-2xl h-14 px-8 shadow-md shadow-terracotta/30">
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
                    <div class="card-warm rounded-[3rem] overflow-hidden">
                        <div class="p-8 border-b border-terracotta/20">
                            <h3 class="text-2xl font-black tracking-tighter flex items-center gap-3 text-warm-text text-dark-text">
                                <FileText class="w-6 h-6 text-terracotta" />
                                Course Syllabus & Overview
                            </h3>
                        </div>
                        <div class="p-8">
                            <div class="prose prose-slate dark:prose-invert max-w-none">
                                <p class="text-lg text-warm-muted text-dark-muted leading-relaxed font-medium">
                                    {{ subject.description || 'This course offers a comprehensive deep-dive into the core principles of ' + subject.name + '. Students will engage with both historical frameworks and contemporary applications, ensuring a robust mastery of the subject matter.' }}
                                </p>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mt-12">
                                    <div class="p-6 rounded-[2rem] bg-terracotta/5 border border-terracotta/20">
                                        <div class="flex items-center gap-4 mb-4">
                                            <div class="w-10 h-10 rounded-xl bg-terracotta/10 flex items-center justify-center text-terracotta">
                                                <Award class="w-5 h-5" />
                                            </div>
                                            <h4 class="font-black text-warm-text text-dark-text uppercase tracking-tighter">Learning Objectives</h4>
                                        </div>
                                        <ul class="space-y-2 text-sm text-warm-muted text-dark-muted font-bold">
                                            <li>• Master fundamental theoretical frameworks</li>
                                            <li>• Apply concepts to real-world scenarios</li>
                                            <li>• Develop critical analytical methodologies</li>
                                        </ul>
                                    </div>
                                    <div class="p-6 rounded-[2rem] bg-terracotta/5 border border-terracotta/20">
                                        <div class="flex items-center gap-4 mb-4">
                                            <div class="w-10 h-10 rounded-xl bg-amber/10 flex items-center justify-center text-amber">
                                                <Layers class="w-5 h-5" />
                                            </div>
                                            <h4 class="font-black text-warm-text text-dark-text uppercase tracking-tighter">Assessment Method</h4>
                                        </div>
                                        <p class="text-sm text-warm-muted text-dark-muted font-bold">
                                            Continuous assessment through periodic examinations, project-based learning, and interactive class participation modules.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Course Stats -->
                <div class="space-y-8">
                    <div class="card-warm rounded-[3rem] p-8">
                        <h3 class="text-xl font-black text-warm-text text-dark-text tracking-tighter mb-8 bg-terracotta/5 rounded-xl px-4 py-1 inline-block">Course Metrics</h3>
                        
                        <div class="space-y-6">
                            <div class="flex items-center justify-between p-4 rounded-2xl bg-terracotta/5 border border-terracotta/20 group">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 rounded-xl bg-terracotta/10 flex items-center justify-center text-terracotta group-hover:scale-110 transition-transform">
                                        <Binary class="w-5 h-5" />
                                    </div>
                                    <div>
                                        <p class="text-[10px] font-black text-warm-muted text-dark-muted uppercase tracking-widest">Course Code</p>
                                        <p class="text-sm font-black text-warm-text text-dark-text">{{ subject.code }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="flex items-center justify-between p-4 rounded-2xl bg-terracotta/5 border border-terracotta/20 group">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 rounded-xl bg-forest/10 flex items-center justify-center text-forest group-hover:scale-110 transition-transform">
                                        <Clock class="w-5 h-5" />
                                    </div>
                                    <div>
                                        <p class="text-[10px] font-black text-warm-muted text-dark-muted uppercase tracking-widest">Credit Load</p>
                                        <p class="text-sm font-black text-warm-text text-dark-text">{{ subject.credits || 3 }} Units</p>
                                    </div>
                                </div>
                            </div>

                            <div class="flex items-center justify-between p-4 rounded-2xl bg-terracotta/5 border border-terracotta/20 group">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 rounded-xl bg-amber/10 flex items-center justify-center text-amber group-hover:scale-110 transition-transform">
                                        <Users class="w-5 h-5" />
                                    </div>
                                    <div>
                                        <p class="text-[10px] font-black text-warm-muted text-dark-muted uppercase tracking-widest">Student Enrollment</p>
                                        <p class="text-sm font-black text-warm-text text-dark-text">124 Enrolled</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <Link :href="route('subjects.index')" class="block mt-8">
                            <Button variant="outline" class="w-full h-12 border-terracotta/20 text-warm-muted text-dark-muted hover:text-terracotta hover:bg-terracotta/5 font-black rounded-2xl">
                                <ArrowLeft class="w-4 h-4 mr-2" />
                                Back to Curriculum
                            </Button>
                        </Link>
                    </div>

                    <!-- Faculty / Mentor -->
                    <div class="card-warm rounded-[3rem] p-8">
                        <h3 class="text-xl font-black text-warm-text text-dark-text tracking-tighter mb-6">Principal Mentor</h3>
                        <div class="flex items-center gap-4">
                            <div class="w-16 h-16 rounded-[1.5rem] bg-terracotta flex items-center justify-center text-white font-black text-2xl shadow-md">
                                DR
                            </div>
                            <div>
                                <h4 class="text-lg font-black text-warm-text text-dark-text leading-none mb-1">Dr. Alex Rivers</h4>
                                <p class="text-xs font-bold text-warm-muted text-dark-muted uppercase tracking-wider">Senior Academic Lead</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </Sidebar>
</template>