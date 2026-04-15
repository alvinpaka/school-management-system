<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import { ref, watch, defineAsyncComponent } from 'vue';
import Sidebar from '@/Components/Sidebar.vue';

// Lazy load modal for faster initial page load
const AddTeacherModal = defineAsyncComponent({
    loader: () => import('@/Components/Modals/AddTeacherModal.vue'),
    loadingComponent: {
        template: '<div class="flex items-center justify-center p-8"><div class="animate-spin w-8 h-8 border-4 border-forest/20 border-t-forest rounded-full"></div></div>'
    },
    delay: 0,
    suspensible: true
});
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';
import Pagination from '@/components/ui/pagination.vue';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';
import { Separator } from '@/components/ui/separator';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';
import { 
    Edit,
    Trash2,
    Search,
    Filter,
    Eye,
    Mail,
    Phone,
    UserCheck,
    MoreHorizontal,
    FileSpreadsheet,
    UserPlus,
    Users,
    Briefcase,
    GraduationCap,
    Award
} from 'lucide-vue-next';

const { teachers, filters } = defineProps({
    teachers: Object,
    filters: Object
});

const searchQuery = ref(filters?.search || '');
const isCreateDialogOpen = ref(false);
const isModalLoading = ref(false);

const openAddTeacherModal = async () => {
    isModalLoading.value = true;
    await import('@/Components/Modals/AddTeacherModal.vue');
    isCreateDialogOpen.value = true;
    isModalLoading.value = false;
};

const handleAddTeacherSuccess = () => {
    // Optional: add success notification logic here
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

const deleteTeacher = (id) => {
    if (confirm('Are you sure you want to delete this teacher?')) {
        router.delete(route('teachers.destroy', id));
    }
};

const getInitials = (name) => {
    return name?.split(' ').map(word => word[0]).join('').toUpperCase().slice(0, 2) || 'TR';
};
</script>

<template>
    <Head title="Teachers | EduManage Pro" />

    <Sidebar>
        <template #header-title>
            <div class="flex items-center gap-2">
                <div class="p-2 bg-terracotta/10 rounded-lg">
                    <UserCheck class="w-4 h-4 text-terracotta" />
                </div>
                <span class="font-black text-sm uppercase tracking-wider text-warm-muted text-dark-muted">Faculty Directory</span>
            </div>
        </template>

        <div class="space-y-8 animate-fade-in-up">
            <!-- Header Section -->
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
                <div>
                    <h1 class="text-4xl font-black text-warm-text text-dark-text tracking-tighter mb-2">Teachers</h1>
                    <p class="text-lg text-warm-muted text-dark-muted font-medium tracking-tight">
                        Empowering <span class="text-terracotta font-bold">{{ teachers.total }}</span> expert educators.
                    </p>
                </div>
                <div class="flex items-center gap-3">
                    <Button variant="outline" class="bg-white bg-dark-bg border border-terracotta/20 shadow-sm h-12 rounded-2xl font-bold px-6 text-warm-muted text-dark-muted hover:text-terracotta">
                        <FileSpreadsheet class="w-4 h-4 mr-2 text-forest" />
                        Export List
                    </Button>
                    <Button @click="openAddTeacherModal" :disabled="isModalLoading" class="h-12 rounded-2xl accent-forest text-white font-black px-8 shadow-xl shadow-forest/30">
                        <UserPlus class="w-4 h-4 mr-2" />
                        Add Faculty Member
                    </Button>
                    <AddTeacherModal
                        v-model:open="isCreateDialogOpen"
                        @success="handleAddTeacherSuccess"
                    />
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
                            placeholder="Search by name, email or employee ID..." 
                            class="h-14 pl-12 bg-white bg-dark-bg border border-terracotta/20 shadow-sm rounded-[1.25rem] focus:ring-2 focus:ring-terracotta/30 text-base font-medium"
                        />
                    </div>
                    <div class="flex items-center gap-2">
                        <Button variant="outline" class="bg-white bg-dark-bg border border-terracotta/20 shadow-sm h-14 w-14 rounded-[1.25rem] p-0">
                            <Filter class="w-5 h-5 text-warm-muted text-dark-muted" />
                        </Button>
                        <div class="h-8 w-[1px] bg-terracotta/20 mx-2 hidden md:block"></div>
                        <div class="text-sm font-bold text-warm-muted text-dark-muted">
                            {{ teachers.total }} active educators
                        </div>
                    </div>
                </div>

                <!-- Premium Table -->
                <div class="overflow-x-auto overflow-y-hidden custom-scrollbar">
                    <table class="w-full border-collapse">
                        <thead>
                            <tr class="text-[10px] font-black uppercase tracking-[0.2em] text-warm-muted text-dark-muted border-b border-terracotta/20">
                                <th class="text-left py-6 px-8 whitespace-nowrap">Faculty Member</th>
                                <th class="text-left py-6 px-8 whitespace-nowrap">Specialization</th>
                                <th class="text-left py-6 px-8 whitespace-nowrap">Contact Info</th>
                                <th class="text-left py-6 px-8 whitespace-nowrap">Status</th>
                                <th class="text-right py-6 px-8 whitespace-nowrap">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-terracotta/20">
                            <tr v-for="teacher in teachers.data" :key="teacher.id" class="group hover:bg-terracotta/5 transition-colors duration-300">
                                <td class="py-6 px-8">
                                    <div class="flex items-center gap-4">
                                        <div class="relative">
                                            <div class="w-14 h-14 rounded-2xl bg-terracotta/5 overflow-hidden border-2 border-terracotta/20 shadow-lg group-hover:scale-110 transition-transform duration-500">
                                                <img 
                                                    v-if="teacher.user.photo" 
                                                    :src="`/storage/${teacher.user.photo}`" 
                                                    :alt="teacher.user.name"
                                                    class="w-full h-full object-cover"
                                                />
                                                <div v-else class="h-full w-full flex items-center justify-center text-terracotta font-black text-xl">
                                                    {{ getInitials(teacher.user.name) }}
                                                </div>
                                            </div>
                                            <div class="absolute -bottom-1 -right-1 w-4 h-4 bg-forest border-2 border-white dark:border-dark-bg rounded-full shadow-lg"></div>
                                        </div>
                                        <div class="min-w-0">
                                            <p class="font-black text-warm-text text-dark-text truncate tracking-tight text-lg mb-0.5">{{ teacher.user.name }}</p>
                                            <p class="text-[11px] font-mono font-bold text-warm-muted text-dark-muted uppercase tracking-tighter">ID: {{ teacher.employee_id }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-6 px-8">
                                    <div class="flex flex-col gap-1.5">
                                        <Badge class="w-fit bg-terracotta/10 text-terracotta border-0 font-black text-[10px] capitalize">{{ teacher.user?.roles?.[0]?.name || 'Teacher' }}</Badge>
                                        <div class="flex items-center gap-1.5 text-[10px] font-black text-warm-muted text-dark-muted uppercase tracking-widest">
                                            <Briefcase class="w-3 h-3" />
                                            {{ teacher.employment_type || 'Full Time' }}
                                        </div>
                                    </div>
                                </td>
                                <td class="py-6 px-8">
                                    <div class="flex flex-col gap-1">
                                        <div class="flex items-center gap-2 text-sm font-bold text-warm-text text-dark-text">
                                            <Mail class="w-3.5 h-3.5 text-terracotta" />
                                            <span class="truncate max-w-[150px]">{{ teacher.user.email }}</span>
                                        </div>
                                        <div class="flex items-center gap-2 text-[11px] font-bold text-warm-muted text-dark-muted">
                                            <Phone class="w-3.5 h-3.5 text-terracotta" />
                                            {{ teacher.phone || '+254 700 000 000' }}
                                        </div>
                                    </div>
                                </td>
                                <td class="py-6 px-8">
                                    <Badge class="bg-forest/10 text-forest border-0 font-black px-4 py-1 rounded-full text-[10px]">ACTIVE</Badge>
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
                                                <Link :href="route('teachers.show', teacher.id)" class="flex items-center p-3 rounded-xl cursor-pointer hover:bg-terracotta/10 hover:text-terracotta font-bold transition-all">
                                                    <Eye class="w-4 h-4 mr-3" />
                                                    View Profile
                                                </Link>
                                            </DropdownMenuItem>
                                            <DropdownMenuItem as-child>
                                                <Link :href="route('teachers.edit', teacher.id)" class="flex items-center p-3 rounded-xl cursor-pointer hover:bg-amber/10 hover:text-amber font-bold transition-all">
                                                    <Edit class="w-4 h-4 mr-3" />
                                                    Edit Details
                                                </Link>
                                            </DropdownMenuItem>
                                            <DropdownMenuItem @click="deleteTeacher(teacher.id)" class="flex items-center p-3 rounded-xl cursor-pointer hover:bg-destructive/10 text-destructive font-bold transition-all">
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
                <div class="p-8 border-t border-terracotta/20 bg-terracotta/5">
                    <Pagination 
                        :data="teachers" 
                        @page-change="handlePageChange"
                    />
                </div>
            </div>
        </div>
    </Sidebar>
</template>