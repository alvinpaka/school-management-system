<script setup>
import { Head, Link, router, useForm } from '@inertiajs/vue3';
import { ref, watch } from 'vue';
import { usePage } from '@inertiajs/vue3';
import Sidebar from '@/Components/Sidebar.vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';
import Pagination from '@/components/ui/pagination.vue';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';
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
    GraduationCap,
    MoreHorizontal,
    FileSpreadsheet,
    UserPlus,
    Users,
    Save,
    User,
    Lock,
    Mail,
    Camera,
    Upload,
    X,
    Calendar,
    MapPin,
    AlertCircle,
    Sparkles,
    RefreshCw,
    Phone,
    GraduationCap as GradIcon
} from 'lucide-vue-next';

const { students, filters, classes, sections } = defineProps({
    students: Object,
    filters: Object,
    classes: Array,
    sections: Array
});

const searchQuery = ref(filters?.search || '');
const isCreateDialogOpen = ref(false);
const photoPreview = ref(null);
const photoFile = ref(null);

// Generate admission number automatically
const generateAdmissionNumber = () => {
    const year = new Date().getFullYear();
    const random = Math.floor(Math.random() * 10000);
    return `STD${year}${random.toString().padStart(4, '0')}`;
};

const generateRollNumber = () => {
    const year = new Date().getFullYear();
    const random = Math.floor(Math.random() * 10000);
    return `ROLL${year}${random.toString().padStart(4, '0')}`;
};

// Create student form
const form = useForm({
    name: '',
    email: '',
    password: '',
    academic_class_id: '',
    section_id: '',
    admission_number: generateAdmissionNumber(),
    roll_number: generateRollNumber(),
    date_of_birth: '',
    gender: '',
    phone: '',
    address: '',
    admission_date: new Date().toISOString().split('T')[0],
    status: 'active',
    photo: null,
    blood_group: '',
    emergency_contact: '',
});

const handlePhotoChange = (event) => {
    const file = event.target.files[0];
    if (file) {
        photoPreview.value = URL.createObjectURL(file);
        photoFile.value = file;
        form.photo = file;
    }
};

const removePhoto = () => {
    photoPreview.value = null;
    photoFile.value = null;
    form.photo = null;
};

const regenerateNumbers = () => {
    form.admission_number = generateAdmissionNumber();
    form.roll_number = generateRollNumber();
};

const getFormInitials = (name) => {
    if (!name) return 'ST';
    return name.split(' ').map(word => word[0]).join('').toUpperCase().slice(0, 2);
};

const submitForm = () => {
    form.post(route('students.store'), {
        forceFormData: true,
        onSuccess: () => {
            isCreateDialogOpen.value = false;
            form.reset();
            form.admission_number = generateAdmissionNumber();
            form.roll_number = generateRollNumber();
            form.admission_date = new Date().toISOString().split('T')[0];
            form.status = 'active';
            photoPreview.value = null;
            photoFile.value = null;
        },
    });
};

const deleteStudent = (id) => {
    if (confirm('Are you sure you want to delete this student?')) {
        router.delete(route('students.destroy', id));
    }
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
                        Manage and monitor your institution's <span class="text-terracotta font-bold">{{ students.total }}</span> scholars.
                    </p>
                </div>
                <div class="flex items-center gap-3">
                    <Button variant="outline" class="bg-white dark:bg-dark-bg border border-terracotta/20 shadow-sm h-12 rounded-2xl font-bold px-6 text-warm-muted dark:text-dark-muted hover:text-terracotta">
                        <FileSpreadsheet class="w-4 h-4 mr-2 text-forest" />
                        Export Data
                    </Button>
                    <Dialog v-model:open="isCreateDialogOpen">
                        <DialogTrigger as-child>
                            <Button class="h-12 rounded-2xl accent-terracotta text-white font-black px-8 shadow-xl shadow-terracotta/30">
                                <UserPlus class="w-4 h-4 mr-2" />
                                Enroll Student
                            </Button>
                        </DialogTrigger>
                        <DialogContent class="w-[95vw] max-w-7xl max-h-[95vh] overflow-y-auto card-warm">
                            <DialogHeader>
                                <DialogTitle class="text-2xl font-black tracking-tight text-warm-text dark:text-dark-text">Enroll New Student</DialogTitle>
                                <DialogDescription class="text-warm-muted dark:text-dark-muted">
                                    Create a new student enrollment with all required details.
                                </DialogDescription>
                            </DialogHeader>
                            <form @submit.prevent="submitForm" class="space-y-6 py-4">
                                <!-- Header Preview Card -->
                                <div class="card-warm overflow-hidden">
                                    <div class="h-20 accent-terracotta"></div>
                                    <div class="relative pt-0 pb-4 p-6">
                                        <div class="flex flex-col md:flex-row md:items-end -mt-10">
                                            <div class="flex flex-col md:flex-row items-center md:items-end space-y-3 md:space-y-0 md:space-x-4">
                                                <Avatar class="w-20 h-20 border-4 border-white dark:border-dark-bg shadow-lg">
                                                    <AvatarImage v-if="photoPreview" :src="photoPreview" />
                                                    <AvatarFallback class="text-xl font-bold bg-terracotta text-white">
                                                        {{ getFormInitials(form.name) }}
                                                    </AvatarFallback>
                                                </Avatar>
                                                <div class="text-center md:text-left space-y-1 mb-1">
                                                    <h3 class="text-xl font-bold text-warm-text dark:text-dark-text">{{ form.name || 'New Student' }}</h3>
                                                    <div class="flex flex-wrap items-center justify-center md:justify-start gap-2">
                                                        <Badge variant="outline" class="gap-1 text-xs border-terracotta/20 text-terracotta">
                                                            <Sparkles class="w-3 h-3" />
                                                            Creating Profile
                                                        </Badge>
                                                        <Badge v-if="form.admission_number" variant="outline" class="text-xs border-terracotta/20">{{ form.admission_number }}</Badge>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <!-- Photo Upload -->
                                    <div class="card-warm">
                                        <div class="p-6 border-b border-terracotta/20">
                                            <h3 class="flex items-center gap-2 text-base font-black text-warm-text dark:text-dark-text">
                                                <Camera class="w-4 h-4 text-terracotta" />
                                                Profile Photo
                                            </h3>
                                        </div>
                                        <div class="p-6">
                                            <div class="flex flex-col items-center space-y-3">
                                                <Avatar class="w-24 h-24 border-2 border-terracotta/20">
                                                    <AvatarImage v-if="photoPreview" :src="photoPreview" />
                                                    <AvatarFallback class="text-2xl font-bold bg-terracotta text-white">{{ getFormInitials(form.name) }}</AvatarFallback>
                                                </Avatar>
                                                <div class="flex flex-col items-center space-y-2 w-full">
                                                    <Label for="photo" class="cursor-pointer text-terracotta hover:text-terracotta/80 font-medium text-sm">
                                                        Click to upload photo
                                                    </Label>
                                                    <Input id="photo" type="file" accept="image/*" @change="handlePhotoChange" class="hidden" />
                                                    <p class="text-xs text-warm-muted dark:text-dark-muted">PNG, JPG (Max 2MB)</p>
                                                    <Button v-if="photoPreview" type="button" variant="outline" size="sm" @click="removePhoto" class="text-destructive border-destructive/20">
                                                        <X class="w-3 h-3 mr-1" /> Remove
                                                    </Button>
                                                </div>
                                                <div v-if="form.errors.photo" class="text-destructive text-sm">{{ form.errors.photo }}</div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Basic Info -->
                                    <div class="card-warm">
                                        <div class="p-6 border-b border-terracotta/20">
                                            <h3 class="flex items-center gap-2 text-base font-black text-warm-text dark:text-dark-text">
                                                <User class="w-4 h-4 text-terracotta" />
                                                Basic Information
                                            </h3>
                                        </div>
                                        <div class="p-6 space-y-3">
                                            <div class="space-y-1">
                                                <Label for="name" class="text-sm text-warm-text dark:text-dark-text">Full Name *</Label>
                                                <Input id="name" v-model="form.name" type="text" placeholder="Enter full name" required class="border-terracotta/20 focus:ring-terracotta/30" />
                                                <div v-if="form.errors.name" class="text-destructive text-xs">{{ form.errors.name }}</div>
                                            </div>
                                            <div class="space-y-1">
                                                <Label for="email" class="text-sm text-warm-text dark:text-dark-text">Email Address *</Label>
                                                <Input id="email" v-model="form.email" type="email" placeholder="student@example.com" required class="border-terracotta/20 focus:ring-terracotta/30" />
                                                <div v-if="form.errors.email" class="text-destructive text-xs">{{ form.errors.email }}</div>
                                            </div>
                                            <div class="space-y-1">
                                                <Label for="password" class="text-sm text-warm-text dark:text-dark-text">Password *</Label>
                                                <Input id="password" v-model="form.password" type="password" placeholder="Enter password" required class="border-terracotta/20 focus:ring-terracotta/30" />
                                                <div v-if="form.errors.password" class="text-destructive text-xs">{{ form.errors.password }}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Academic Information -->
                                <div class="card-warm">
                                    <div class="p-6 border-b border-terracotta/20">
                                        <h3 class="flex items-center gap-2 text-base font-black text-warm-text dark:text-dark-text">
                                            <GradIcon class="w-4 h-4 text-terracotta" />
                                            Academic Information
                                        </h3>
                                    </div>
                                    <div class="p-6">
                                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                            <div class="space-y-1">
                                                <Label class="text-sm text-warm-text dark:text-dark-text">Admission Number</Label>
                                                <div class="flex gap-2">
                                                    <Input v-model="form.admission_number" readonly class="bg-terracotta/5 text-sm border-terracotta/20" />
                                                    <Button type="button" variant="outline" size="icon" @click="regenerateNumbers" class="shrink-0 border-terracotta/20">
                                                        <RefreshCw class="w-4 h-4" />
                                                    </Button>
                                                </div>
                                            </div>
                                            <div class="space-y-1">
                                                <Label class="text-sm text-warm-text dark:text-dark-text">Roll Number</Label>
                                                <Input v-model="form.roll_number" readonly class="bg-terracotta/5 text-sm border-terracotta/20" />
                                            </div>
                                            <div class="space-y-1">
                                                <Label for="admission_date" class="text-sm text-warm-text dark:text-dark-text">Admission Date</Label>
                                                <Input id="admission_date" v-model="form.admission_date" type="date" class="border-terracotta/20 focus:ring-terracotta/30" />
                                            </div>
                                            <div class="space-y-1">
                                                <Label for="academic_class_id" class="text-sm text-warm-text dark:text-dark-text">Class *</Label>
                                                <select id="academic_class_id" v-model="form.academic_class_id" class="flex h-10 w-full rounded-md border border-terracotta/20 bg-background px-3 py-2 text-sm focus:ring-terracotta/30" required>
                                                    <option value="">Select class</option>
                                                    <option v-for="classItem in classes" :key="classItem.id" :value="classItem.id">{{ classItem.name }}</option>
                                                </select>
                                                <div v-if="form.errors.academic_class_id" class="text-destructive text-xs">{{ form.errors.academic_class_id }}</div>
                                            </div>
                                            <div class="space-y-1">
                                                <Label for="section_id" class="text-sm text-warm-text dark:text-dark-text">Section *</Label>
                                                <select id="section_id" v-model="form.section_id" class="flex h-10 w-full rounded-md border border-terracotta/20 bg-background px-3 py-2 text-sm focus:ring-terracotta/30" required>
                                                    <option value="">Select section</option>
                                                    <option v-for="section in sections" :key="section.id" :value="section.id">{{ section.name }}</option>
                                                </select>
                                                <div v-if="form.errors.section_id" class="text-destructive text-xs">{{ form.errors.section_id }}</div>
                                            </div>
                                            <div class="space-y-1">
                                                <Label for="status" class="text-sm text-warm-text dark:text-dark-text">Status</Label>
                                                <select id="status" v-model="form.status" class="flex h-10 w-full rounded-md border border-terracotta/20 bg-background px-3 py-2 text-sm focus:ring-terracotta/30">
                                                    <option value="active">Active</option>
                                                    <option value="inactive">Inactive</option>
                                                    <option value="graduated">Graduated</option>
                                                    <option value="transferred">Transferred</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Personal Details -->
                                <div class="card-warm">
                                    <div class="p-6 border-b border-terracotta/20">
                                        <h3 class="flex items-center gap-2 text-base font-black text-warm-text dark:text-dark-text">
                                            <User class="w-4 h-4 text-terracotta" />
                                            Personal Details
                                        </h3>
                                    </div>
                                    <div class="p-6">
                                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                            <div class="space-y-1">
                                                <Label for="phone" class="text-sm text-warm-text dark:text-dark-text">Phone Number</Label>
                                                <Input id="phone" v-model="form.phone" type="tel" placeholder="+254..." class="border-terracotta/20 focus:ring-terracotta/30" />
                                            </div>
                                            <div class="space-y-1">
                                                <Label for="date_of_birth" class="text-sm text-warm-text dark:text-dark-text">Date of Birth</Label>
                                                <Input id="date_of_birth" v-model="form.date_of_birth" type="date" class="border-terracotta/20 focus:ring-terracotta/30" />
                                            </div>
                                            <div class="space-y-1">
                                                <Label for="gender" class="text-sm text-warm-text dark:text-dark-text">Gender</Label>
                                                <select id="gender" v-model="form.gender" class="flex h-10 w-full rounded-md border border-terracotta/20 bg-background px-3 py-2 text-sm focus:ring-terracotta/30">
                                                    <option value="">Select gender</option>
                                                    <option value="male">Male</option>
                                                    <option value="female">Female</option>
                                                    <option value="other">Other</option>
                                                </select>
                                            </div>
                                            <div class="space-y-1">
                                                <Label for="blood_group" class="text-sm text-warm-text dark:text-dark-text">Blood Group</Label>
                                                <select id="blood_group" v-model="form.blood_group" class="flex h-10 w-full rounded-md border border-terracotta/20 bg-background px-3 py-2 text-sm focus:ring-terracotta/30">
                                                    <option value="">Select blood group</option>
                                                    <option value="A+">A+</option>
                                                    <option value="A-">A-</option>
                                                    <option value="B+">B+</option>
                                                    <option value="B-">B-</option>
                                                    <option value="O+">O+</option>
                                                    <option value="O-">O-</option>
                                                    <option value="AB+">AB+</option>
                                                    <option value="AB-">AB-</option>
                                                </select>
                                            </div>
                                            <div class="space-y-1 md:col-span-2">
                                                <Label for="address" class="text-sm text-warm-text dark:text-dark-text">Address</Label>
                                                <Input id="address" v-model="form.address" type="text" placeholder="Enter full address" class="border-terracotta/20 focus:ring-terracotta/30" />
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Emergency Contact -->
                                <div class="card-warm">
                                    <div class="p-6 border-b border-terracotta/20">
                                        <h3 class="flex items-center gap-2 text-base font-black text-warm-text dark:text-dark-text">
                                            <Phone class="w-4 h-4 text-terracotta" />
                                            Emergency Contact
                                        </h3>
                                    </div>
                                    <div class="p-6">
                                        <div class="space-y-1">
                                            <Label for="emergency_contact" class="text-sm text-warm-text dark:text-dark-text">Emergency Contact Phone</Label>
                                            <Input id="emergency_contact" v-model="form.emergency_contact" type="tel" placeholder="+254..." class="border-terracotta/20 focus:ring-terracotta/30" />
                                        </div>
                                    </div>
                                </div>

                                <DialogFooter class="gap-3 pt-4 border-t border-terracotta/20">
                                    <Button type="button" variant="outline" @click="isCreateDialogOpen = false" class="border-terracotta/20">
                                        Cancel
                                    </Button>
                                    <Button type="submit" :disabled="form.processing" class="accent-terracotta text-white">
                                        <Save class="w-4 h-4 mr-2" />
                                        {{ form.processing ? 'Enrolling...' : 'Enroll Student' }}
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