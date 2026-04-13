<script setup>
import { Head, Link, router, usePage, useForm } from '@inertiajs/vue3';
import { ref, watch, computed } from 'vue';
import Sidebar from '@/Components/Sidebar.vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';
import Pagination from '@/components/ui/pagination.vue';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
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
const isCreateDialogOpen = ref(false);

// Create subject form
const form = useForm({
    name: '',
    code: '',
    type: 'theory',
});

const submitForm = () => {
    form.post(route('subjects.store'), {
        onSuccess: () => {
            isCreateDialogOpen.value = false;
            form.reset();
        },
    });
};

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
        ? 'text-terracotta bg-terracotta/10 border-terracotta/20' 
        : 'text-amber bg-amber/10 border-amber/20';
};
</script>

<template>
    <Head :title="isAdminOrTeacher ? 'Subjects | EduManage Pro' : 'My Curriculum | EduManage Pro'" />

    <Sidebar>
        <template #header-title>
            <div class="flex items-center gap-2">
                <div class="p-2 bg-terracotta/10 rounded-lg">
                    <Layers class="w-4 h-4 text-terracotta" />
                </div>
                <span class="font-black text-sm uppercase tracking-wider text-warm-muted dark:text-dark-muted">
                    {{ isAdminOrTeacher ? 'Curriculum Registry' : 'My Subjects' }}
                </span>
            </div>
        </template>

        <div class="space-y-8 animate-fade-in-up">
            <!-- Dramatic Header Section -->
            <div class="relative overflow-hidden card-warm rounded-[2.5rem] p-8 md:p-12">
                <div class="absolute -top-24 -right-24 w-64 h-64 bg-terracotta/5 blur-[80px] rounded-full animate-float"></div>
                <div class="absolute -bottom-24 -left-24 w-64 h-64 bg-forest/5 blur-[80px] rounded-full animate-float" style="animation-delay: 2s"></div>
                
                <div class="relative z-10">
                    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">
                        <div>
                            <h1 class="text-4xl md:text-5xl font-black text-warm-text dark:text-dark-text mb-2 tracking-tighter">
                                {{ isAdminOrTeacher ? 'Subjects & Electives' : 'My Academic Courses' }}
                            </h1>
                            <p class="text-lg text-warm-muted dark:text-dark-muted font-medium">
                                {{ isAdminOrTeacher 
                                    ? 'Define and manage the educational curriculum and subject specialties.' 
                                    : 'Explore your enrolled subjects and academic course materials.' }}
                            </p>
                        </div>
                        
                        <!-- Search and Add Course on the same line -->
                        <div class="flex items-center gap-3">
                            <div class="relative group">
                                <Search class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-warm-muted dark:text-dark-muted group-focus-within:text-terracotta transition-colors" />
                                <input
                                    type="text"
                                    v-model="searchQuery"
                                    placeholder="Search courses..."
                                    class="pl-11 pr-4 h-14 w-full sm:w-64 bg-white dark:bg-dark-bg border border-terracotta/20 shadow-sm rounded-2xl text-sm font-bold focus:ring-4 focus:ring-terracotta/10 transition-all outline-none text-warm-text dark:text-dark-text"
                                />
                            </div>
                            <Dialog v-if="isAdminOrTeacher" v-model:open="isCreateDialogOpen">
                                <DialogTrigger as-child>
                                    <Button class="accent-terracotta text-white font-black rounded-2xl h-14 px-6 shadow-xl shadow-terracotta/30 whitespace-nowrap">
                                        <Plus class="w-4 h-4 mr-2" />
                                        Add Course
                                    </Button>
                                </DialogTrigger>
                                <DialogContent class="w-[95vw] max-w-6xl max-h-[90vh] overflow-y-auto card-warm">
                                    <DialogHeader>
                                        <DialogTitle class="text-2xl font-black tracking-tight text-warm-text dark:text-dark-text">Add New Course</DialogTitle>
                                        <DialogDescription class="text-warm-muted dark:text-dark-muted">
                                            Create a new subject or course for the curriculum.
                                        </DialogDescription>
                                    </DialogHeader>
                                    <form @submit.prevent="submitForm" class="space-y-6 py-4">
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                            <div class="space-y-2">
                                                <Label for="name" class="font-medium text-warm-text dark:text-dark-text">Subject Name *</Label>
                                                <Input
                                                    id="name"
                                                    v-model="form.name"
                                                    type="text"
                                                    placeholder="e.g., Mathematics"
                                                    required
                                                    class="h-11 border-terracotta/20 focus:ring-terracotta/30"
                                                />
                                                <div v-if="form.errors.name" class="text-destructive text-sm">{{ form.errors.name }}</div>
                                            </div>
                                            <div class="space-y-2">
                                                <Label for="code" class="font-medium text-warm-text dark:text-dark-text">Subject Code *</Label>
                                                <Input
                                                    id="code"
                                                    v-model="form.code"
                                                    type="text"
                                                    placeholder="e.g., MATH"
                                                    required
                                                    class="h-11 border-terracotta/20 focus:ring-terracotta/30"
                                                />
                                                <div v-if="form.errors.code" class="text-destructive text-sm">{{ form.errors.code }}</div>
                                            </div>
                                        </div>
                                        <div class="space-y-2">
                                            <Label for="type" class="font-medium text-warm-text dark:text-dark-text">Subject Type *</Label>
                                            <select
                                                id="type"
                                                v-model="form.type"
                                                class="flex h-11 w-full rounded-md border border-terracotta/20 bg-background px-3 py-2 text-sm focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-terracotta/30 text-warm-text dark:text-dark-text"
                                                required
                                            >
                                                <option value="theory">Theory</option>
                                                <option value="practical">Practical</option>
                                                <option value="elective">Elective</option>
                                            </select>
                                            <div v-if="form.errors.type" class="text-destructive text-sm">{{ form.errors.type }}</div>
                                        </div>
                                        <DialogFooter class="gap-3 pt-4 border-t border-terracotta/20">
                                            <Button type="button" variant="outline" @click="isCreateDialogOpen = false" class="border-terracotta/20">
                                                Cancel
                                            </Button>
                                            <Button type="submit" :disabled="form.processing" class="accent-terracotta text-white">
                                                <Plus class="w-4 h-4 mr-2" />
                                                {{ form.processing ? 'Creating...' : 'Create Subject' }}
                                            </Button>
                                        </DialogFooter>
                                    </form>
                                </DialogContent>
                            </Dialog>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stats Bar -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div class="card-warm p-6 rounded-[2rem] group">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 rounded-2xl bg-terracotta/10 flex items-center justify-center text-terracotta">
                            <BookOpen class="w-6 h-6" />
                        </div>
                        <span class="text-2xl font-black tracking-tighter text-warm-text dark:text-dark-text">{{ subjects.total || 0 }}</span>
                    </div>
                    <p class="text-[10px] font-black uppercase text-warm-muted dark:text-dark-muted tracking-widest">Total Subjects</p>
                </div>
                
                <div class="card-warm p-6 rounded-[2rem] group">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 rounded-2xl bg-forest/10 flex items-center justify-center text-forest">
                            <GraduationCap class="w-6 h-6" />
                        </div>
                        <span class="text-2xl font-black tracking-tighter text-forest">
                            {{ subjects.data.filter(s => s.type === 'Core').length }}
                        </span>
                    </div>
                    <p class="text-[10px] font-black uppercase text-warm-muted dark:text-dark-muted tracking-widest">Core Requirements</p>
                </div>

                <div class="card-warm p-6 rounded-[2rem] group">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 rounded-2xl bg-amber/10 flex items-center justify-center text-amber">
                            <Layers class="w-6 h-6" />
                        </div>
                        <span class="text-2xl font-black tracking-tighter text-amber">
                            {{ subjects.data.filter(s => s.type !== 'Core').length }}
                        </span>
                    </div>
                    <p class="text-[10px] font-black uppercase text-warm-muted dark:text-dark-muted tracking-widest">Elective Courses</p>
                </div>

                <div class="card-warm p-6 rounded-[2rem] group">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 rounded-2xl bg-terracotta/10 flex items-center justify-center text-terracotta">
                            <Clock class="w-6 h-6" />
                        </div>
                        <span class="text-2xl font-black tracking-tighter text-warm-text dark:text-dark-text">42h</span>
                    </div>
                    <p class="text-[10px] font-black uppercase text-warm-muted dark:text-dark-muted tracking-widest">Weekly Load</p>
                </div>
            </div>

            <!-- Course Card Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-8">
                <div 
                    v-for="subject in subjects.data" 
                    :key="subject.id"
                    class="card-warm rounded-[3rem] overflow-hidden group hover:scale-[1.03] hover:shadow-2xl hover:shadow-terracotta/10 transition-all duration-500"
                >
                    <div class="p-8 pb-4 relative overflow-hidden">
                        <div class="absolute -top-10 -right-10 w-32 h-32 bg-terracotta/5 blur-[40px] rounded-full group-hover:scale-150 transition-transform duration-1000"></div>
                        <div class="flex items-start justify-between relative z-10">
                            <div class="w-16 h-16 bg-white dark:bg-dark-bg rounded-3xl flex items-center justify-center border border-terracotta/20 shadow-lg group-hover:rotate-6 transition-transform">
                                <component :is="getSubjectIcon(subject.name)" class="w-8 h-8 text-terracotta" />
                            </div>
                            <div v-if="isAdminOrTeacher" class="flex items-center gap-2">
                                <DropdownMenu>
                                    <DropdownMenuTrigger as-child>
                                        <Button variant="ghost" class="w-10 h-10 p-0 rounded-xl hover:bg-terracotta/10">
                                            <MoreVertical class="w-4 h-4 text-warm-muted dark:text-dark-muted" />
                                        </Button>
                                    </DropdownMenuTrigger>
                                    <DropdownMenuContent align="end" class="w-48 card-warm rounded-2xl p-2">
                                        <DropdownMenuItem as-child>
                                            <Link :href="route('subjects.edit', subject.id)" class="rounded-xl p-3 font-bold gap-3 flex items-center hover:bg-terracotta/10">
                                                <Edit class="w-4 h-4 text-terracotta" />
                                                Edit Course
                                            </Link>
                                        </DropdownMenuItem>
                                        <DropdownMenuItem @click="deleteSubject(subject.id)" class="rounded-xl p-3 font-bold gap-3 flex items-center text-destructive hover:bg-destructive/10">
                                            <Trash2 class="w-4 h-4" />
                                            Delete
                                        </DropdownMenuItem>
                                    </DropdownMenuContent>
                                </DropdownMenu>
                            </div>
                        </div>
                    </div>
                    <div class="p-8 pt-4">
                        <div class="mb-6">
                            <div class="flex items-center gap-2 mb-2">
                                <Badge :class="['rounded-lg px-3 py-1 text-[10px] font-black uppercase tracking-widest border-0', getSubjectColor(subject.type)]">
                                    {{ subject.type }}
                                </Badge>
                                <span class="text-[10px] font-black text-warm-muted dark:text-dark-muted uppercase tracking-widest">ID: #{{ subject.code }}</span>
                            </div>
                            <h3 class="text-2xl font-black text-warm-text dark:text-dark-text tracking-tighter mb-2">{{ subject.name }}</h3>
                            <p class="text-sm text-warm-muted dark:text-dark-muted font-medium leading-relaxed line-clamp-2">
                                {{ subject.description || 'Comprehensive study of fundamental concepts and advanced theory in ' + subject.name + '.' }}
                            </p>
                        </div>

                        <div class="grid grid-cols-2 gap-4 py-6 border-t border-terracotta/20">
                            <div>
                                <p class="text-[10px] font-black text-warm-muted dark:text-dark-muted uppercase tracking-widest mb-1">Credits</p>
                                <p class="text-lg font-black text-warm-text dark:text-dark-text">{{ subject.credits || 3 }} Units</p>
                            </div>
                            <div>
                                <p class="text-[10px] font-black text-warm-muted dark:text-dark-muted uppercase tracking-widest mb-1">Status</p>
                                <div class="flex items-center gap-2">
                                    <div class="w-2 h-2 rounded-full bg-forest animate-pulse"></div>
                                    <p class="text-sm font-bold text-warm-text dark:text-dark-text uppercase tracking-wider">Active</p>
                                </div>
                            </div>
                        </div>

                        <Link :href="route('subjects.show', subject.id)">
                            <Button class="w-full mt-2 h-12 bg-white dark:bg-dark-bg border border-terracotta/20 shadow-sm text-warm-text dark:text-dark-text hover:text-terracotta hover:bg-terracotta/5 font-black rounded-2xl group/btn">
                                Course Details
                                <Eye class="w-4 h-4 ml-2 group-hover/btn:translate-x-1 transition-transform" />
                            </Button>
                        </Link>
                    </div>
                </div>
            </div>

            <!-- Empty State -->
            <div v-if="subjects.data.length === 0" class="card-warm rounded-[3rem] p-20 text-center">
                <div class="w-20 h-20 bg-terracotta/5 rounded-3xl flex items-center justify-center mx-auto mb-6">
                    <BookOpen class="w-10 h-10 text-terracotta/30" />
                </div>
                <h3 class="text-2xl font-black text-warm-text dark:text-dark-text tracking-tighter mb-2">No Courses Found</h3>
                <p class="text-warm-muted dark:text-dark-muted font-medium max-w-xs mx-auto mb-8">Refine your search or add a new subject to the curriculum registry.</p>
                <Button variant="outline" class="rounded-2xl border-terracotta/20 font-bold px-8" @click="searchQuery = ''">
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