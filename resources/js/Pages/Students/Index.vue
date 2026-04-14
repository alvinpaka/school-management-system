<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import { ref, watch, defineAsyncComponent } from 'vue';
import Sidebar from '@/Components/Sidebar.vue';

// Lazy load modal for faster initial page load
const EnrollStudentModal = defineAsyncComponent({
    loader: () => import('@/Components/Modals/EnrollStudentModal.vue'),
    loadingComponent: {
        template: '<div class="flex items-center justify-center p-8"><div class="animate-spin w-8 h-8 border-4 border-terracotta/20 border-t-terracotta rounded-full"></div></div>'
    },
    delay: 0,
    suspensible: true
});
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
    Edit,
    Trash2,
    Search,
    Filter,
    Eye,
    MoreHorizontal,
    FileSpreadsheet,
    UserPlus,
    Users
} from 'lucide-vue-next';

const { students, filters, classes, sections, canEnroll } = defineProps({
    students: Object,
    filters: Object,
    classes: Array,
    sections: Array,
    canEnroll: {
        type: Boolean,
        default: true
    }
});

const searchQuery = ref(filters?.search || '');
const isCreateDialogOpen = ref(false);
const isModalLoading = ref(false);

const openEnrollModal = async () => {
    isModalLoading.value = true;
    await import('@/Components/Modals/EnrollStudentModal.vue');
    isCreateDialogOpen.value = true;
    isModalLoading.value = false;
};

const handleEnrollSuccess = () => {
    // Optional: add success notification logic here
};

const handlePageChange = (page) => {
    const params = { page };
    if (searchQuery.value) {
        params.search = searchQuery.value;
    }
    router.get(route('students.index'), params, { preserveState: true });
};

// Watch for search query changes
watch(searchQuery, (newValue) => {
    router.get(route('students.index'), { 
        search: newValue, 
        page: 1 
    }, { preserveState: true });
}, { debounce: 300 });

const deleteStudent = (id) => {
    if (confirm('Are you sure you want to delete this student?')) {
        router.delete(route('students.destroy', id));
    }
};

const getInitials = (name) => {
    return name?.split(' ').map(word => word[0]).join('').toUpperCase().slice(0, 2) || 'ST';
};
</script>

<template>
    <Head title="Students | EduManage Pro" />

    <Sidebar>
        <template #header-title>
            <div class="flex items-center gap-2">
                <div class="p-2 bg-terracotta/10 rounded-lg">
                    <Users class="w-4 h-4 text-terracotta" />
                </div>
                <span class="font-black text-sm uppercase tracking-wider text-warm-muted dark:text-dark-muted">Student Directory</span>
            </div>
        </template>

        <div class="space-y-8 animate-fade-in-up">
            <!-- Header Section -->
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
                <div>
                    <h1 class="text-4xl font-black text-warm-text dark:text-dark-text tracking-tighter mb-2">Students</h1>
                    <p class="text-lg text-warm-muted dark:text-dark-muted font-medium tracking-tight">
                        <span v-if="canEnroll">Manage and monitor your institution's <span class="text-terracotta font-bold">{{ students.total }}</span> scholars.</span>
                        <span v-else>View students in your assigned classes <span class="text-terracotta font-bold">({{ students.total }})</span>.</span>
                    </p>
                </div>
                <div class="flex items-center gap-3">
                    <Button variant="outline" class="bg-white dark:bg-dark-bg border border-terracotta/20 shadow-sm h-12 rounded-2xl font-bold px-6 text-warm-muted dark:text-warm-muted hover:text-terracotta">
                        <FileSpreadsheet class="w-4 h-4 mr-2 text-forest" />
                        Export Data
                    </Button>
                    <Button v-if="canEnroll" @click="openEnrollModal" :disabled="isModalLoading" class="h-12 rounded-2xl accent-terracotta text-white font-black px-8 shadow-xl shadow-terracotta/30">
                        <UserPlus class="w-4 h-4 mr-2" />
                        Enroll Student
                    </Button>
                    <EnrollStudentModal
                        v-if="canEnroll"
                        v-model:open="isCreateDialogOpen"
                        :classes="classes"
                        :sections="sections"
                        @success="handleEnrollSuccess"
                    />
                </div>
            </div>

            <!-- Filters & Data Table Card -->
            <div class="card-warm rounded-xl rounded-[2.5rem] overflow-hidden">
                <!-- Search & Filters Header -->
                <div class="p-8 border-b border-terracotta/20 bg-terracotta/5 flex flex-col md:flex-row md:items-center justify-between gap-6">
                    <div class="relative w-full max-w-md group">
                        <div class="absolute left-4 top-1/2 -translate-y-1/2 text-warm-muted dark:text-dark-muted group-focus-within:text-terracotta transition-colors">
                            <Search class="w-5 h-5" />
                        </div>
                        <Input 
                            v-model="searchQuery"
                            placeholder="Find students by name, email or admission number..." 
                            class="h-14 pl-12 bg-white dark:bg-dark-bg border border-terracotta/20 shadow-sm rounded-[1.25rem] focus:ring-2 focus:ring-terracotta/30 text-base font-medium"
                        />
                    </div>
                    <div class="flex items-center gap-2">
                        <Button variant="outline" class="bg-white dark:bg-dark-bg border border-terracotta/20 shadow-sm h-14 w-14 rounded-[1.25rem] p-0">
                            <Filter class="w-5 h-5 text-warm-muted dark:text-dark-muted" />
                        </Button>
                        <div class="h-8 w-[1px] bg-terracotta/20 mx-2 hidden md:block"></div>
                        <div class="text-sm font-bold text-warm-muted dark:text-dark-muted">
                            Showing {{ students.from }}-{{ students.to }} of {{ students.total }}
                        </div>
                    </div>
                </div>

                <!-- Premium Table -->
                <div class="overflow-x-auto overflow-y-hidden custom-scrollbar">
                    <table class="w-full border-collapse">
                        <thead>
                            <tr class="text-[10px] font-black uppercase tracking-[0.2em] text-warm-muted dark:text-dark-muted border-b border-terracotta/20">
                                <th class="text-left py-6 px-8 whitespace-nowrap">Identity</th>
                                <th class="text-left py-6 px-8 whitespace-nowrap">Academic Class</th>
                                <th class="text-left py-6 px-8 whitespace-nowrap">Admission Info</th>
                                <th class="text-left py-6 px-8 whitespace-nowrap">Status</th>
                                <th class="text-right py-6 px-8 whitespace-nowrap">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-terracotta/20">
                            <tr v-for="student in students.data" :key="student.id" class="group hover:bg-terracotta/5 transition-colors duration-300">
                                <td class="py-6 px-8">
                                    <div class="flex items-center gap-4">
                                        <div class="relative">
                                            <div class="w-14 h-14 rounded-2xl bg-terracotta/5 overflow-hidden border-2 border-terracotta/20 shadow-lg group-hover:scale-110 transition-transform duration-500">
                                                <img 
                                                    v-if="student.user.photo" 
                                                    :src="`/storage/${student.user.photo}`" 
                                                    :alt="student.user.name"
                                                    class="w-full h-full object-cover"
                                                />
                                                <div v-else class="h-full w-full flex items-center justify-center text-terracotta font-black text-xl">
                                                    {{ getInitials(student.user.name) }}
                                                </div>
                                            </div>
                                            <div class="absolute -bottom-1 -right-1 w-4 h-4 bg-forest border-2 border-white dark:border-dark-bg rounded-full shadow-lg"></div>
                                        </div>
                                        <div class="min-w-0">
                                            <p class="font-black text-warm-text dark:text-dark-text truncate tracking-tight text-lg mb-0.5">{{ student.user.name }}</p>
                                            <p class="text-xs font-bold text-warm-muted dark:text-dark-muted truncate">{{ student.user.email }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-6 px-8">
                                    <div class="flex flex-col gap-1.5">
                                        <Badge class="w-fit bg-terracotta/10 text-terracotta border-0 font-black text-[10px]">{{ student.academic_class.name }}</Badge>
                                        <p class="text-[10px] font-black text-warm-muted dark:text-dark-muted uppercase tracking-widest">{{ student.section.name }} Section</p>
                                    </div>
                                </td>
                                <td class="py-6 px-8">
                                    <div class="flex flex-col">
                                        <p class="font-mono text-sm font-bold text-warm-text dark:text-dark-text tracking-tighter">#{{ student.admission_number }}</p>
                                        <p class="text-[11px] font-bold text-warm-muted dark:text-dark-muted">Enrolled {{ new Date(student.created_at).getFullYear() }}</p>
                                    </div>
                                </td>
                                <td class="py-6 px-8">
                                    <Badge class="bg-forest/10 text-forest border-0 font-black px-4 py-1 rounded-full text-[10px]">ACTIVE</Badge>
                                </td>
                                <td class="py-6 px-8 text-right">
                                    <DropdownMenu>
                                        <DropdownMenuTrigger as-child>
                                            <Button variant="ghost" class="h-10 w-10 p-0 bg-white dark:bg-dark-bg border border-terracotta/20 shadow-sm hover:bg-terracotta/10 rounded-xl">
                                                <MoreHorizontal class="w-5 h-5 text-warm-muted dark:text-dark-muted" />
                                            </Button>
                                        </DropdownMenuTrigger>
                                        <DropdownMenuContent align="end" class="card-warm rounded-xl rounded-2xl p-2 w-48 shadow-2xl">
                                            <DropdownMenuItem as-child>
                                                <Link :href="route('students.show', student.id)" class="flex items-center p-3 rounded-xl cursor-pointer hover:bg-terracotta/10 hover:text-terracotta font-bold transition-all">
                                                    <Eye class="w-4 h-4 mr-3" />
                                                    View Profile
                                                </Link>
                                            </DropdownMenuItem>
                                            <DropdownMenuItem as-child>
                                                <Link :href="route('students.edit', student.id)" class="flex items-center p-3 rounded-xl cursor-pointer hover:bg-amber/10 hover:text-amber font-bold transition-all">
                                                    <Edit class="w-4 h-4 mr-3" />
                                                    Edit Record
                                                </Link>
                                            </DropdownMenuItem>
                                            <DropdownMenuItem @click="deleteStudent(student.id)" class="flex items-center p-3 rounded-xl cursor-pointer hover:bg-destructive/10 text-destructive font-bold transition-all">
                                                <Trash2 class="w-4 h-4 mr-3" />
                                                Archive Student
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
                        :data="students" 
                        @page-change="handlePageChange"
                    />
                </div>
            </div>
        </div>
    </Sidebar>
</template>