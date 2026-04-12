<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import { ref, watch } from 'vue';
import Sidebar from '@/Components/Sidebar.vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';
import Pagination from '@/components/ui/pagination.vue';
import { Input } from '@/components/ui/input';
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
                <div class="p-2 bg-violet-600/10 rounded-lg">
                    <Layers class="w-4 h-4 text-violet-600 dark:text-violet-400" />
                </div>
                <span class="font-black text-sm uppercase tracking-wider text-gray-500">Academic Structure</span>
            </div>
        </template>

        <div class="space-y-8 animate-fade-in-up">
            <!-- Header Section -->
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
                <div>
                    <h1 class="text-4xl font-black text-gray-900 dark:text-white tracking-tighter mb-2">Classes</h1>
                    <p class="text-lg text-gray-500 dark:text-gray-400 font-medium tracking-tight">
                        Organizing <span class="text-violet-600 dark:text-violet-400 font-bold">{{ classes.total }}</span> distinct academic groups.
                    </p>
                </div>
                <div class="flex items-center gap-3">
                    <Button variant="outline" class="glass h-12 rounded-2xl border-white/10 font-bold px-6">
                        <FileSpreadsheet class="w-4 h-4 mr-2 text-indigo-500" />
                        Structure Audit
                    </Button>
                    <Link :href="route('classes.create')">
                        <Button class="h-12 rounded-2xl bg-violet-600 hover:bg-violet-700 text-white font-black px-8 shadow-xl shadow-violet-500/20">
                            <Plus class="w-4 h-4 mr-2" />
                            Initialize Class
                        </Button>
                    </Link>
                </div>
            </div>

            <!-- Filters & Data Table Card -->
            <div class="glass-card rounded-[2.5rem] border-white/20 overflow-hidden">
                <!-- Search & Filters Header -->
                <div class="p-8 border-b border-white/5 bg-white/30 dark:bg-slate-900/10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                    <div class="relative w-full max-w-md group">
                        <div class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-violet-600 transition-colors">
                            <Search class="w-5 h-5" />
                        </div>
                        <Input 
                            v-model="searchQuery"
                            placeholder="Identify classes by name or level..." 
                            class="h-14 pl-12 glass bg-white/50 dark:bg-slate-900/50 rounded-[1.25rem] border-white/10 focus:ring-2 focus:ring-violet-500/20 text-base font-medium"
                        />
                    </div>
                    <div class="flex items-center gap-2">
                        <Button variant="outline" class="glass h-14 w-14 rounded-[1.25rem] border-white/10 p-0">
                            <Filter class="w-5 h-5 text-gray-500" />
                        </Button>
                        <div class="h-8 w-[1px] bg-white/10 mx-2 hidden md:block"></div>
                        <div class="text-sm font-bold text-gray-400">
                            {{ classes.total }} active classes
                        </div>
                    </div>
                </div>

                <!-- Premium Table -->
                <div class="overflow-x-auto overflow-y-hidden custom-scrollbar">
                    <table class="w-full border-collapse">
                        <thead>
                            <tr class="text-[10px] font-black uppercase tracking-[0.2em] text-gray-400 border-b border-white/5">
                                <th class="text-left py-6 px-8 whitespace-nowrap">Academic Division</th>
                                <th class="text-left py-6 px-8 whitespace-nowrap">Sections</th>
                                <th class="text-left py-6 px-8 whitespace-nowrap">Lead Educator</th>
                                <th class="text-left py-6 px-8 whitespace-nowrap">Capacity</th>
                                <th class="text-right py-6 px-8 whitespace-nowrap">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5">
                            <tr v-for="classItem in classes.data" :key="classItem.id" class="group hover:bg-violet-600/[0.02] transition-colors duration-300">
                                <td class="py-6 px-8">
                                    <div class="flex items-center gap-4">
                                        <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-violet-500 to-indigo-600 flex items-center justify-center text-white shadow-lg group-hover:scale-110 transition-transform duration-500">
                                            <BookOpen class="w-7 h-7" />
                                        </div>
                                        <div class="min-w-0">
                                            <p class="font-black text-gray-900 dark:text-white truncate tracking-tight text-lg mb-0.5">{{ classItem.name }}</p>
                                            <p class="text-[11px] font-black text-violet-500 uppercase tracking-widest">Level {{ classItem.grade_level }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-6 px-8">
                                    <div class="flex flex-wrap gap-1.5 max-w-[200px]">
                                        <Badge 
                                            v-for="section in classItem.sections" 
                                            :key="section.id" 
                                            class="bg-indigo-500/10 text-indigo-600 border-0 font-black text-[10px]"
                                        >
                                            Sec {{ section.name }}
                                        </Badge>
                                        <p v-if="!classItem.sections?.length" class="text-[10px] font-bold text-gray-400 italic">No sections initialized</p>
                                    </div>
                                </td>
                                <td class="py-6 px-8">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-lg bg-gray-100 dark:bg-slate-800 flex items-center justify-center text-gray-400 border border-white/10">
                                            <UserCircle class="w-5 h-5" />
                                        </div>
                                        <p class="text-sm font-bold text-gray-700 dark:text-gray-300 truncate max-w-[150px]">
                                            {{ classItem.class_teacher?.user?.name || 'Unassigned' }}
                                        </p>
                                    </div>
                                </td>
                                <td class="py-6 px-8">
                                    <div class="flex items-center gap-2">
                                        <div class="p-2 bg-emerald-500/10 rounded-lg">
                                            <Users class="w-3.5 h-3.5 text-emerald-600" />
                                        </div>
                                        <p class="font-black text-gray-900 dark:text-white tracking-tighter">{{ classItem.total_students || 0 }} <span class="text-gray-400 text-[10px] lowercase transition-colors group-hover:text-emerald-500">students</span></p>
                                    </div>
                                </td>
                                <td class="py-6 px-8 text-right">
                                    <DropdownMenu>
                                        <DropdownMenuTrigger as-child>
                                            <Button variant="ghost" class="h-10 w-10 p-0 glass hover:bg-white/10 rounded-xl border-white/10">
                                                <MoreHorizontal class="w-5 h-5 text-gray-400" />
                                            </Button>
                                        </DropdownMenuTrigger>
                                        <DropdownMenuContent align="end" class="glass-card border-white/20 rounded-2xl p-2 w-48 shadow-2xl">
                                            <DropdownMenuItem as-child>
                                                <Link :href="route('classes.show', classItem.id)" class="flex items-center p-3 rounded-xl cursor-pointer hover:bg-violet-600/10 hover:text-violet-600 font-bold transition-all">
                                                    <Eye class="w-4 h-4 mr-3" />
                                                    Explore Unit
                                                </Link>
                                            </DropdownMenuItem>
                                            <DropdownMenuItem as-child>
                                                <Link :href="route('classes.edit', classItem.id)" class="flex items-center p-3 rounded-xl cursor-pointer hover:bg-amber-600/10 hover:text-amber-600 font-bold transition-all">
                                                    <Edit class="w-4 h-4 mr-3" />
                                                    Modify Class
                                                </Link>
                                            </DropdownMenuItem>
                                            <DropdownMenuItem @click="deleteClass(classItem.id)" class="flex items-center p-3 rounded-xl cursor-pointer hover:bg-red-600/10 text-red-500 font-bold transition-all">
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
                <div class="p-8 border-t border-white/5 bg-gray-50/5 dark:bg-slate-900/20">
                    <Pagination 
                        :data="classes" 
                        @page-change="handlePageChange"
                    />
                </div>
            </div>
        </div>
    </Sidebar>
</template>

<style scoped>
.custom-scrollbar::-webkit-scrollbar {
  width: 5px;
}
.custom-scrollbar::-webkit-scrollbar-track {
  background: transparent;
}
.custom-scrollbar::-webkit-scrollbar-thumb {
  background: rgba(139, 92, 246, 0.1);
  border-radius: 10px;
}
</style>
