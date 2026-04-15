<script setup>
import { Head, Link, router, useForm } from '@inertiajs/vue3';
import { ref, watch } from 'vue';
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
    Users,
    Calendar,
    MoreHorizontal,
    FileSpreadsheet,
    Layers,
    UserCircle,
    BookOpen
} from 'lucide-vue-next';

const { classes, filters } = defineProps({
    classes: Object,
    filters: Object
});

const searchQuery = ref(filters?.search || '');
const isCreateDialogOpen = ref(false);

// Create class form
const form = useForm({
    name: '',
    code: '',
    sections: ['A']
});

const addSection = () => {
    form.sections.push('');
};

const removeSection = (index) => {
    form.sections.splice(index, 1);
};

const submitForm = () => {
    form.post(route('classes.store'), {
        onSuccess: () => {
            isCreateDialogOpen.value = false;
            form.reset();
            form.sections = ['A'];
        },
    });
};

const deleteClass = (id) => {
    if (confirm('Are you sure you want to delete this class? Sections will also be removed.')) {
        router.delete(route('classes.destroy', id));
    }
};

const handlePageChange = (page) => {
    const params = { page };
    if (searchQuery.value) {
        params.search = searchQuery.value;
    }
    router.get(route('classes.index'), params, { preserveState: true });
};

// Watch for search query changes
watch(searchQuery, (newValue) => {
    router.get(route('classes.index'), { 
        search: newValue, 
        page: 1 
    }, { preserveState: true });
}, { debounce: 300 });
</script>

<template>
    <Head title="Academic Classes | EduManage Pro" />

    <Sidebar>
        <template #header-title>
            <div class="flex items-center gap-2">
                <div class="p-2 bg-terracotta/10 rounded-lg">
                    <Layers class="w-4 h-4 text-terracotta" />
                </div>
                <span class="font-black text-sm uppercase tracking-wider text-warm-muted text-dark-muted">Academic Structure</span>
            </div>
        </template>

        <div class="space-y-8 animate-fade-in-up">
            <!-- Header Section -->
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
                <div>
                    <h1 class="text-4xl font-black text-warm-text text-dark-text tracking-tighter mb-2">Classes</h1>
                    <p class="text-lg text-warm-muted text-dark-muted font-medium tracking-tight">
                        Organizing <span class="text-terracotta font-bold">{{ classes.total }}</span> distinct academic groups.
                    </p>
                </div>
                <div class="flex items-center gap-3">
                    <Button variant="outline" class="bg-white bg-dark-bg border border-terracotta/20 shadow-sm h-12 rounded-2xl font-bold px-6 text-warm-muted text-dark-muted hover:text-terracotta">
                        <FileSpreadsheet class="w-4 h-4 mr-2 text-forest" />
                        Structure Audit
                    </Button>
                    <Dialog v-model:open="isCreateDialogOpen">
                        <DialogTrigger as-child>
                            <Button class="h-12 rounded-2xl accent-terracotta text-white font-black px-8 shadow-xl shadow-terracotta/30">
                                <Plus class="w-4 h-4 mr-2" />
                                Initialize Class
                            </Button>
                        </DialogTrigger>
                        <DialogContent class="w-[95vw] max-w-6xl max-h-[90vh] overflow-y-auto card-warm">
                            <DialogHeader>
                                <DialogTitle class="text-2xl font-black tracking-tight text-warm-text text-dark-text">Initialize New Class</DialogTitle>
                                <DialogDescription class="text-warm-muted text-dark-muted">
                                    Create a new academic class with its sections.
                                </DialogDescription>
                            </DialogHeader>
                            <form @submit.prevent="submitForm" class="space-y-6 py-4">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="space-y-2">
                                        <Label for="name" class="font-medium text-warm-text text-dark-text">Class Name *</Label>
                                        <Input
                                            id="name"
                                            v-model="form.name"
                                            type="text"
                                            placeholder="e.g., Grade 10"
                                            required
                                            class="h-11 border-terracotta/20 focus:ring-terracotta/30"
                                        />
                                        <div v-if="form.errors.name" class="text-destructive text-sm">{{ form.errors.name }}</div>
                                    </div>
                                    <div class="space-y-2">
                                        <Label for="code" class="font-medium text-warm-text text-dark-text">Class Code *</Label>
                                        <Input
                                            id="code"
                                            v-model="form.code"
                                            type="text"
                                            placeholder="e.g., G10"
                                            required
                                            class="h-11 border-terracotta/20 focus:ring-terracotta/30"
                                        />
                                        <div v-if="form.errors.code" class="text-destructive text-sm">{{ form.errors.code }}</div>
                                    </div>
                                </div>
                                <div class="space-y-3">
                                    <Label class="font-medium text-warm-text text-dark-text">Sections *</Label>
                                    <div v-for="(section, index) in form.sections" :key="index" class="flex items-center space-x-2">
                                        <Input
                                            v-model="form.sections[index]"
                                            type="text"
                                            class="flex-1 h-11 border-terracotta/20 focus:ring-terracotta/30"
                                            placeholder="Section name"
                                            required
                                        />
                                        <Button
                                            type="button"
                                            variant="outline"
                                            size="sm"
                                            @click="removeSection(index)"
                                            :disabled="form.sections.length === 1"
                                            class="text-destructive hover:text-destructive border-terracotta/20 h-11 px-3"
                                        >
                                            <Trash2 class="w-4 h-4" />
                                        </Button>
                                    </div>
                                    <Button
                                        type="button"
                                        variant="outline"
                                        size="sm"
                                        @click="addSection"
                                        class="w-full h-11 border-terracotta/20"
                                    >
                                        <Plus class="w-4 h-4 mr-2" />
                                        Add Section
                                    </Button>
                                    <div v-if="form.errors.sections" class="text-destructive text-sm">{{ form.errors.sections }}</div>
                                </div>
                                <DialogFooter class="gap-3 pt-4 border-t border-terracotta/20">
                                    <Button type="button" variant="outline" @click="isCreateDialogOpen = false" class="border-terracotta/20">
                                        Cancel
                                    </Button>
                                    <Button type="submit" :disabled="form.processing" class="accent-terracotta text-white">
                                        <Plus class="w-4 h-4 mr-2" />
                                        {{ form.processing ? 'Creating...' : 'Create Class' }}
                                    </Button>
                                </DialogFooter>
                            </form>
                        </DialogContent>
                    </Dialog>
                </div>
            </div>

            <!-- Filters & Data Table Card -->
            <div class="card-warm rounded-xl rounded-[2.5rem] overflow-hidden">
                <!-- Search & Filters Header -->
                <div class="p-8 border-b border-terracotta/20 bg-terracotta/5 flex flex-col md:flex-row md:items-center justify-between gap-6">
                    <div class="relative w-full max-w-md group">
                        <div class="absolute left-4 top-1/2 -translate-y-1/2 text-warm-muted text-dark-muted group-focus-within:text-terracotta transition-colors">
                            <Search class="w-5 h-5" />
                        </div>
                        <Input 
                            v-model="searchQuery"
                            placeholder="Identify classes by name or level..." 
                            class="h-14 pl-12 bg-white bg-dark-bg border border-terracotta/20 shadow-sm rounded-[1.25rem] focus:ring-2 focus:ring-terracotta/30 text-base font-medium"
                        />
                    </div>
                    <div class="flex items-center gap-2">
                        <Button variant="outline" class="bg-white bg-dark-bg border border-terracotta/20 shadow-sm h-14 w-14 rounded-[1.25rem] p-0">
                            <Filter class="w-5 h-5 text-warm-muted text-dark-muted" />
                        </Button>
                        <div class="h-8 w-[1px] bg-terracotta/20 mx-2 hidden md:block"></div>
                        <div class="text-sm font-bold text-warm-muted text-dark-muted">
                            {{ classes.total }} active classes
                        </div>
                    </div>
                </div>

                <!-- Premium Table -->
                <div class="overflow-x-auto overflow-y-hidden custom-scrollbar">
                    <table class="w-full border-collapse">
                        <thead>
                            <tr class="text-[10px] font-black uppercase tracking-[0.2em] text-warm-muted text-dark-muted border-b border-terracotta/20">
                                <th class="text-left py-6 px-8 whitespace-nowrap">Academic Division</th>
                                <th class="text-left py-6 px-8 whitespace-nowrap">Sections</th>
                                <th class="text-left py-6 px-8 whitespace-nowrap">Capacity</th>
                                <th class="text-right py-6 px-8 whitespace-nowrap">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-terracotta/20">
                            <tr v-for="classItem in classes.data" :key="classItem.id" class="group hover:bg-terracotta/5 transition-colors duration-300">
                                <td class="py-6 px-8">
                                    <div class="flex items-center gap-4">
                                        <div class="w-14 h-14 rounded-2xl bg-terracotta/10 flex items-center justify-center text-terracotta shadow-lg group-hover:scale-110 transition-transform duration-500">
                                            <BookOpen class="w-7 h-7" />
                                        </div>
                                        <div class="min-w-0">
                                            <p class="font-black text-warm-text text-dark-text truncate tracking-tight text-lg mb-0.5">{{ classItem.name }}</p>
                                            <p class="text-[11px] font-black text-terracotta uppercase tracking-widest">Level {{ classItem.grade_level }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-6 px-8">
                                    <div class="flex flex-wrap gap-1.5 max-w-[200px]">
                                        <Badge 
                                            v-for="section in classItem.sections" 
                                            :key="section.id" 
                                            class="bg-terracotta/10 text-terracotta border-0 font-black text-[10px]"
                                        >
                                            Sec {{ section.name }}
                                        </Badge>
                                        <p v-if="!classItem.sections?.length" class="text-[10px] font-bold text-warm-muted text-dark-muted italic">No sections initialized</p>
                                    </div>
                                </td>
                                <td class="py-6 px-8">
                                    <div class="flex items-center gap-2">
                                        <div class="p-2 bg-forest/10 rounded-lg">
                                            <Users class="w-3.5 h-3.5 text-forest" />
                                        </div>
                                        <p class="font-black text-warm-text text-dark-text tracking-tighter">{{ classItem.total_students || 0 }} <span class="text-warm-muted text-dark-muted text-[10px] lowercase transition-colors group-hover:text-forest">students</span></p>
                                    </div>
                                </td>
                                <td class="py-6 px-8 text-right">
                                    <DropdownMenu>
                                        <DropdownMenuTrigger as-child>
                                            <Button variant="ghost" class="h-10 w-10 p-0 bg-white bg-dark-bg border border-terracotta/20 shadow-sm hover:bg-terracotta/10 rounded-xl">
                                                <MoreHorizontal class="w-5 h-5 text-warm-muted text-dark-muted" />
                                            </Button>
                                        </DropdownMenuTrigger>
                                        <DropdownMenuContent align="end" class="card-warm rounded-xl rounded-2xl p-2 w-48 shadow-2xl">
                                            <DropdownMenuItem as-child>
                                                <Link :href="route('classes.show', classItem.id)" class="flex items-center p-3 rounded-xl cursor-pointer hover:bg-terracotta/10 hover:text-terracotta font-bold transition-all">
                                                    <Eye class="w-4 h-4 mr-3" />
                                                    Explore Unit
                                                </Link>
                                            </DropdownMenuItem>
                                            <DropdownMenuItem as-child>
                                                <Link :href="route('classes.edit', classItem.id)" class="flex items-center p-3 rounded-xl cursor-pointer hover:bg-amber/10 hover:text-amber font-bold transition-all">
                                                    <Edit class="w-4 h-4 mr-3" />
                                                    Modify Class
                                                </Link>
                                            </DropdownMenuItem>
                                            <DropdownMenuItem @click="deleteClass(classItem.id)" class="flex items-center p-3 rounded-xl cursor-pointer hover:bg-destructive/10 text-destructive font-bold transition-all">
                                                <Trash2 class="w-4 h-4 mr-3" />
                                                Decommission
                                            </DropdownMenuItem>
                                        </DropdownMenuContent>
                                    </DropdownMenu>
                                 </td>
                             </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Premium Pagination Footer -->
                <div class="p-8 border-t border-terracotta/20 bg-terracotta/5">
                    <Pagination 
                        :data="classes" 
                        @page-change="handlePageChange"
                    />
                </div>
            </div>
        </div>
    </Sidebar>
</template>