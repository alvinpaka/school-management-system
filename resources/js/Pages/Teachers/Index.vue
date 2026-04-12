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
    Mail,
    Phone,
    UserCheck,
    MoreHorizontal,
    FileSpreadsheet,
    UserPlus,
    Users,
    Briefcase
} from 'lucide-vue-next';

const { teachers, filters } = defineProps({
    teachers: Object,
    filters: Object
});

const searchQuery = ref(filters?.search || '');

const deleteTeacher = (id) => {
    if (confirm('Are you sure you want to delete this teacher?')) {
        router.delete(route('teachers.destroy', id));
    }
};

const handlePageChange = (page) => {
    const params = { page };
    if (searchQuery.value) {
        params.search = searchQuery.value;
    }
    router.get(route('teachers.index'), params, { preserveState: true });
};

// Watch for search query changes
watch(searchQuery, (newValue) => {
    router.get(route('teachers.index'), { 
        search: newValue, 
        page: 1 
    }, { preserveState: true });
}, { debounce: 300 });

const getInitials = (name) => {
    return name?.split(' ').map(word => word[0]).join('').toUpperCase().slice(0, 2) || 'TR';
};
</script>

<template>
    <Head title="Teachers | EduManage Pro" />

    <Sidebar>
        <template #header-title>
            <div class="flex items-center gap-2">
                <div class="p-2 bg-emerald-600/10 rounded-lg">
                    <UserCheck class="w-4 h-4 text-emerald-600 dark:text-emerald-400" />
                </div>
                <span class="font-black text-sm uppercase tracking-wider text-gray-500">Faculty Directory</span>
            </div>
        </template>

        <div class="space-y-8 animate-fade-in-up">
            <!-- Header Section -->
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
                <div>
                    <h1 class="text-4xl font-black text-gray-900 dark:text-white tracking-tighter mb-2">Teachers</h1>
                    <p class="text-lg text-gray-500 dark:text-gray-400 font-medium tracking-tight">
                        Empowering <span class="text-emerald-600 dark:text-emerald-400 font-bold">{{ teachers.total }}</span> expert educators.
                    </p>
                </div>
                <div class="flex items-center gap-3">
                    <Button variant="outline" class="glass h-12 rounded-2xl border-white/10 font-bold px-6">
                        <FileSpreadsheet class="w-4 h-4 mr-2 text-blue-500" />
                        Export List
                    </Button>
                    <Link :href="route('teachers.create')">
                        <Button class="h-12 rounded-2xl bg-emerald-600 hover:bg-emerald-700 text-white font-black px-8 shadow-xl shadow-emerald-500/20">
                            <UserPlus class="w-4 h-4 mr-2" />
                            Add Faculty Member
                        </Button>
                    </Link>
                </div>
            </div>

            <!-- Filters & Data Table Card -->
            <div class="glass-card rounded-[2.5rem] border-white/20 overflow-hidden">
                <!-- Search & Filters Header -->
                <div class="p-8 border-b border-white/5 bg-white/30 dark:bg-slate-900/10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                    <div class="relative w-full max-w-md group">
                        <div class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-emerald-600 transition-colors">
                            <Search class="w-5 h-5" />
                        </div>
                        <Input 
                            v-model="searchQuery"
                            placeholder="Search by name, email or employee ID..." 
                            class="h-14 pl-12 glass bg-white/50 dark:bg-slate-900/50 rounded-[1.25rem] border-white/10 focus:ring-2 focus:ring-emerald-500/20 text-base font-medium"
                        />
                    </div>
                    <div class="flex items-center gap-2">
                        <Button variant="outline" class="glass h-14 w-14 rounded-[1.25rem] border-white/10 p-0">
                            <Filter class="w-5 h-5 text-gray-500" />
                        </Button>
                        <div class="h-8 w-[1px] bg-white/10 mx-2 hidden md:block"></div>
                        <div class="text-sm font-bold text-gray-400">
                            {{ teachers.total }} active educators
                        </div>
                    </div>
                </div>

                <!-- Premium Table -->
                <div class="overflow-x-auto overflow-y-hidden custom-scrollbar">
                    <table class="w-full border-collapse">
                        <thead>
                            <tr class="text-[10px] font-black uppercase tracking-[0.2em] text-gray-400 border-b border-white/5">
                                <th class="text-left py-6 px-8 whitespace-nowrap">Faculty Member</th>
                                <th class="text-left py-6 px-8 whitespace-nowrap">Specialization</th>
                                <th class="text-left py-6 px-8 whitespace-nowrap">Contact Info</th>
                                <th class="text-left py-6 px-8 whitespace-nowrap">Status</th>
                                <th class="text-right py-6 px-8 whitespace-nowrap">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5">
                            <tr v-for="teacher in teachers.data" :key="teacher.id" class="group hover:bg-emerald-600/[0.02] transition-colors duration-300">
                                <td class="py-6 px-8">
                                    <div class="flex items-center gap-4">
                                        <div class="relative">
                                            <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-gray-100 to-gray-200 dark:from-slate-800 dark:to-slate-900 overflow-hidden border-2 border-white/20 shadow-lg group-hover:scale-110 transition-transform duration-500">
                                                <img 
                                                    v-if="teacher.user.photo" 
                                                    :src="`/storage/${teacher.user.photo}`" 
                                                    :alt="teacher.user.name"
                                                    class="w-full h-full object-cover"
                                                />
                                                <div v-else class="h-full w-full flex items-center justify-center text-emerald-600 font-black text-xl">
                                                    {{ getInitials(teacher.user.name) }}
                                                </div>
                                            </div>
                                            <div class="absolute -bottom-1 -right-1 w-4 h-4 bg-emerald-500 border-2 border-white dark:border-slate-950 rounded-full shadow-lg"></div>
                                        </div>
                                        <div class="min-w-0">
                                            <p class="font-black text-gray-900 dark:text-white truncate tracking-tight text-lg mb-0.5">{{ teacher.user.name }}</p>
                                            <p class="text-[11px] font-mono font-bold text-gray-400 uppercase tracking-tighter">ID: {{ teacher.employee_id }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-6 px-8">
                                    <div class="flex flex-col gap-1.5">
                                        <Badge class="w-fit bg-emerald-500/10 text-emerald-600 border-0 font-black text-[10px]">{{ teacher.specialization || 'Senior Educator' }}</Badge>
                                        <div class="flex items-center gap-1.5 text-[10px] font-black text-gray-400 uppercase tracking-widest">
                                            <Briefcase class="w-3 h-3" />
                                            Full Time
                                        </div>
                                    </div>
                                </td>
                                <td class="py-6 px-8">
                                    <div class="flex flex-col gap-1">
                                        <div class="flex items-center gap-2 text-sm font-bold text-gray-700 dark:text-gray-300">
                                            <Mail class="w-3.5 h-3.5 text-gray-400" />
                                            <span class="truncate max-w-[150px]">{{ teacher.user.email }}</span>
                                        </div>
                                        <div class="flex items-center gap-2 text-[11px] font-bold text-gray-400">
                                            <Phone class="w-3.5 h-3.5 text-gray-400" />
                                            {{ teacher.phone || '+1 (555) 000-0000' }}
                                        </div>
                                    </div>
                                </td>
                                <td class="py-6 px-8">
                                    <Badge class="bg-emerald-500/10 text-emerald-600 border-0 font-black px-4 py-1 rounded-full text-[10px]">ACTIVE</Badge>
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
                                                <Link :href="route('teachers.show', teacher.id)" class="flex items-center p-3 rounded-xl cursor-pointer hover:bg-emerald-600/10 hover:text-emerald-600 font-bold transition-all">
                                                    <Eye class="w-4 h-4 mr-3" />
                                                    View Profile
                                                </Link>
                                            </DropdownMenuItem>
                                            <DropdownMenuItem as-child>
                                                <Link :href="route('teachers.edit', teacher.id)" class="flex items-center p-3 rounded-xl cursor-pointer hover:bg-amber-600/10 hover:text-amber-600 font-bold transition-all">
                                                    <Edit class="w-4 h-4 mr-3" />
                                                    Edit Details
                                                </Link>
                                            </DropdownMenuItem>
                                            <DropdownMenuItem @click="deleteTeacher(teacher.id)" class="flex items-center p-3 rounded-xl cursor-pointer hover:bg-red-600/10 text-red-500 font-bold transition-all">
                                                <Trash2 class="w-4 h-4 mr-3" />
                                                Remove Faculty
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
                        :data="teachers" 
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
  background: rgba(16, 185, 129, 0.1);
  border-radius: 10px;
}
</style>
