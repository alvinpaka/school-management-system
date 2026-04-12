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
import { 
    Plus,
    Edit,
    Trash2,
    Search,
    Filter,
    Download,
    Eye,
    BookOpen,
    GraduationCap,
    Clock,
    Award,
    MoreVertical,
    Layers,
    Binary,
    Palette,
    Atom,
    Globe
} from 'lucide-vue-next';

const props = defineProps({
    subjects: Object,
    filters: Object
});

const page = usePage();
const user = computed(() => page.props.auth.user);
const userRoles = computed(() => user.value?.roles || []);
const isAdminOrTeacher = computed(() => userRoles.value.some(r => ['admin', 'teacher'].includes(r)));

const searchQuery = ref(props.filters?.search || '');

const deleteSubject = (id) => {
    if (confirm('Are you sure you want to delete this subject?')) {
        router.delete(route('subjects.destroy', id));
    }
};

const handlePageChange = (page) => {
    const params = { page };
    if (searchQuery.value) {
        params.search = searchQuery.value;
    }
    router.get(route('subjects.index'), params, { preserveState: true });
};

// Watch for search query changes
watch(searchQuery, (newValue) => {
    router.get(route('subjects.index'), { 
        search: newValue, 
        page: 1 
    }, { preserveState: true });
}, { debounce: 300 });

const getSubjectIcon = (name) => {
    const n = name.toLowerCase();
    if (n.includes('math') || n.includes('calc')) return Binary;
    if (n.includes('science') || n.includes('phys') || n.includes('chem')) return Atom;
    if (n.includes('art') || n.includes('design')) return Palette;
    if (n.includes('language') || n.includes('english') || n.includes('history')) return Globe;
    return BookOpen;
};

const getSubjectColor = (type) => {
    return type === 'Core' 
        ? 'text-indigo-600 bg-indigo-500/10 border-indigo-500/20' 
        : 'text-amber-600 bg-amber-500/10 border-amber-500/20';
};
</script>

<template>
    <Head :title="isAdminOrTeacher ? 'Subjects' : 'My Curriculum'" />

    <Sidebar>
        <template #header-title>
            <div class="flex items-center gap-2">
                <div class="p-2 bg-indigo-600/10 rounded-lg">
                    <Layers class="w-4 h-4 text-indigo-600" />
                </div>
                <span class="font-black text-sm uppercase tracking-wider text-gray-500">
                    {{ isAdminOrTeacher ? 'Curriculum Registry' : 'My Subjects' }}
                </span>
            </div>
        </template>

        <div class="space-y-8 animate-fade-in-up">
            <!-- Dramatic Header Section -->
            <div class="relative overflow-hidden glass-card rounded-[2.5rem] border-white/20 p-8 md:p-12">
                <div class="absolute -top-24 -right-24 w-64 h-64 bg-indigo-600/10 blur-[80px] rounded-full animate-float"></div>
                <div class="absolute -bottom-24 -left-24 w-64 h-64 bg-blue-600/10 blur-[80px] rounded-full animate-float" style="animation-delay: 2s"></div>
                
                <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-8">
                    <div>
                        <h1 class="text-4xl md:text-5xl font-black text-gray-900 dark:text-white mb-2 tracking-tighter">
                            {{ isAdminOrTeacher ? 'Subjects & Electives' : 'My Academic Courses' }}
                        </h1>
                        <p class="text-lg text-gray-500 dark:text-gray-400 font-medium">
                            {{ isAdminOrTeacher 
                                ? 'Define and manage the educational curriculum and subject specialties.' 
                                : 'Explore your enrolled subjects and academic course materials.' }}
                        </p>
                    </div>
                    
                    <div class="flex flex-wrap items-center gap-3">
                        <div class="relative group">
                            <Search class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 group-focus-within:text-indigo-500 transition-colors" />
                            <input
                                type="text"
                                v-model="searchQuery"
                                placeholder="Search courses..."
                                class="pl-11 pr-4 h-14 w-64 glass bg-white/50 border-white/10 rounded-2xl text-sm font-bold focus:ring-4 focus:ring-indigo-500/10 transition-all outline-none"
                            />
                        </div>
                        <Link v-if="isAdminOrTeacher" :href="route('subjects.create')">
                            <Button class="bg-indigo-600 hover:bg-indigo-700 text-white font-black rounded-2xl h-14 px-8 shadow-xl shadow-indigo-500/20">
                                <Plus class="w-5 h-5 mr-2" />
                                Add Course
                            </Button>
                        </Link>
                    </div>
                </div>
            </div>

            <!-- Stats Bar -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div class="glass-card p-6 rounded-[2rem] border-white/10 group">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 rounded-2xl bg-indigo-500/10 flex items-center justify-center text-indigo-600">
                            <BookOpen class="w-6 h-6" />
                        </div>
                        <span class="text-2xl font-black tracking-tighter">{{ subjects.total || 0 }}</span>
                    </div>
                    <p class="text-[10px] font-black uppercase text-gray-400 tracking-widest">Total Subjects</p>
                </div>
                
                <div class="glass-card p-6 rounded-[2rem] border-white/10 group">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 rounded-2xl bg-emerald-500/10 flex items-center justify-center text-emerald-600">
                            <GraduationCap class="w-6 h-6" />
                        </div>
                        <span class="text-2xl font-black tracking-tighter text-emerald-600">
                            {{ subjects.data.filter(s => s.type === 'Core').length }}
                        </span>
                    </div>
                    <p class="text-[10px] font-black uppercase text-gray-400 tracking-widest">Core Requirements</p>
                </div>

                <div class="glass-card p-6 rounded-[2rem] border-white/10 group">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 rounded-2xl bg-amber-500/10 flex items-center justify-center text-amber-600">
                            <Layers class="w-6 h-6" />
                        </div>
                        <span class="text-2xl font-black tracking-tighter text-amber-600">
                            {{ subjects.data.filter(s => s.type !== 'Core').length }}
                        </span>
                    </div>
                    <p class="text-[10px] font-black uppercase text-gray-400 tracking-widest">Elective Courses</p>
                </div>

                <div class="glass-card p-6 rounded-[2rem] border-white/10 group">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 rounded-2xl bg-blue-500/10 flex items-center justify-center text-blue-600">
                            <Clock class="w-6 h-6" />
                        </div>
                        <span class="text-2xl font-black tracking-tighter">42h</span>
                    </div>
                    <p class="text-[10px] font-black uppercase text-gray-400 tracking-widest">Weekly Load</p>
                </div>
            </div>

            <!-- Course Card Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-8">
                <Card 
                    v-for="subject in subjects.data" 
                    :key="subject.id"
                    class="glass border-white/10 rounded-[3rem] overflow-hidden group hover:scale-[1.03] hover:shadow-2xl hover:shadow-indigo-500/10 transition-all duration-500"
                >
                    <CardHeader class="p-8 pb-4 relative overflow-hidden">
                        <div class="absolute -top-10 -right-10 w-32 h-32 bg-indigo-500/5 blur-[40px] rounded-full group-hover:scale-150 transition-transform duration-1000"></div>
                        <div class="flex items-start justify-between relative z-10">
                            <div class="w-16 h-16 bg-white/50 dark:bg-slate-800 rounded-3xl flex items-center justify-center border border-white/10 shadow-lg group-hover:rotate-6 transition-transform">
                                <component :is="getSubjectIcon(subject.name)" class="w-8 h-8 text-indigo-600" />
                            </div>
                            <div v-if="isAdminOrTeacher" class="flex items-center gap-2">
                                <DropdownMenu>
                                    <DropdownMenuTrigger as-child>
                                        <Button variant="ghost" class="w-10 h-10 p-0 rounded-xl hover:bg-white/10">
                                            <MoreVertical class="w-4 h-4" />
                                        </Button>
                                    </DropdownMenuTrigger>
                                    <DropdownMenuContent align="end" class="w-48 glass rounded-2xl p-2 border-white/10">
                                        <DropdownMenuItem as-child>
                                            <Link :href="route('subjects.edit', subject.id)" class="rounded-xl p-3 font-bold gap-3 flex items-center">
                                                <Edit class="w-4 h-4 text-indigo-600" />
                                                Edit Course
                                            </Link>
                                        </DropdownMenuItem>
                                        <DropdownMenuItem @click="deleteSubject(subject.id)" class="rounded-xl p-3 font-bold gap-3 flex items-center text-rose-600 focus:text-rose-600">
                                            <Trash2 class="w-4 h-4" />
                                            Delete
                                        </DropdownMenuItem>
                                    </DropdownMenuContent>
                                </DropdownMenu>
                            </div>
                        </div>
                    </CardHeader>
                    <CardContent class="p-8 pt-4">
                        <div class="mb-6">
                            <div class="flex items-center gap-2 mb-2">
                                <Badge :class="['rounded-lg px-3 py-1 text-[10px] font-black uppercase tracking-widest border-0', getSubjectColor(subject.type)]">
                                    {{ subject.type }}
                                </Badge>
                                <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">ID: #{{ subject.code }}</span>
                            </div>
                            <h3 class="text-2xl font-black text-gray-900 dark:text-white tracking-tighter mb-2">{{ subject.name }}</h3>
                            <p class="text-sm text-gray-500 font-medium leading-relaxed line-clamp-2">
                                {{ subject.description || 'Comprehensive study of fundamental concepts and advanced theory in ' + subject.name + '.' }}
                            </p>
                        </div>

                        <div class="grid grid-cols-2 gap-4 py-6 border-t border-white/10">
                            <div>
                                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Credits</p>
                                <p class="text-lg font-black text-gray-900 dark:text-white">{{ subject.credits }} Units</p>
                            </div>
                            <div>
                                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Status</p>
                                <div class="flex items-center gap-2">
                                    <div class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></div>
                                    <p class="text-sm font-bold text-gray-900 dark:text-white uppercase tracking-wider">Active</p>
                                </div>
                            </div>
                        </div>

                        <Link :href="route('subjects.show', subject.id)">
                            <Button class="w-full mt-2 h-12 glass border-white/10 text-gray-700 hover:text-indigo-600 hover:bg-white/50 font-black rounded-2xl group/btn">
                                Course Details
                                <Eye class="w-4 h-4 ml-2 group-hover/btn:translate-x-1 transition-transform" />
                            </Button>
                        </Link>
                    </CardContent>
                </Card>
            </div>

            <!-- Empty State -->
            <div v-if="subjects.data.length === 0" class="glass rounded-[3rem] border-white/10 p-20 text-center">
                <div class="w-20 h-20 bg-slate-100 dark:bg-slate-800 rounded-3xl flex items-center justify-center mx-auto mb-6">
                    <BookOpen class="w-10 h-10 text-slate-300" />
                </div>
                <h3 class="text-2xl font-black text-gray-900 dark:text-white tracking-tighter mb-2">No Courses Found</h3>
                <p class="text-gray-500 font-medium max-w-xs mx-auto mb-8">Refine your search or add a new subject to the curriculum registry.</p>
                <Button variant="outline" class="rounded-2xl border-white/10 font-bold px-8" @click="searchQuery = ''">
                    Clear Filters
                </Button>
            </div>

            <!-- Pagination -->
            <div class="mt-8 flex justify-center">
                <Pagination 
                    :data="subjects" 
                    @page-change="handlePageChange"
                />
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
