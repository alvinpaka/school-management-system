<script setup>
import { Head, Link, router, useForm } from '@inertiajs/vue3';
import { ref, watch, onMounted } from 'vue';
import Sidebar from '@/Components/Sidebar.vue';
import { Button } from '@/Components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/Components/ui/card';
import { Badge } from '@/Components/ui/badge';
import { Avatar, AvatarFallback, AvatarImage } from '@/Components/ui/avatar';
import { Separator } from '@/Components/ui/separator';
import Pagination from '@/Components/ui/pagination.vue';
import { Input } from '@/Components/ui/input';
import { Label } from '@/Components/ui/label';
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogHeader,
    DialogTitle,
    DialogTrigger,
    DialogFooter,
} from '@/Components/ui/dialog';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuTrigger,
} from '@/Components/ui/dropdown-menu';
import { 
    Plus,
    Edit,
    Trash2,
    Search,
    Filter,
    Download,
    Eye,
    Phone,
    Mail,
    Users,
    User,
    Camera,
    Upload,
    X,
    AlertCircle,
    Briefcase,
    Heart,
    GraduationCap,
    Lock,
    MapPin,
    Save,
    Sparkles
} from 'lucide-vue-next';

const { parents, filters, students } = defineProps({
    parents: Object,
    filters: Object,
    students: Array
});

const searchQuery = ref(filters?.search || '');
const isCreateDialogOpen = ref(false);

// Photo upload
const photoPreview = ref(null);
const photoFile = ref(null);

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
    const fileInput = document.getElementById('parent_photo');
    if (fileInput) {
        fileInput.value = '';
    }
};

// Create parent form
const form = useForm({
    name: '',
    email: '',
    password: '',
    phone: '',
    address: '',
    occupation: '',
    relationship_to_student: 'Father',
    student_ids: [],
    photo: null,
});

// Student search
const studentSearch = ref('');
const searchResults = ref([]);
const selectedStudentsData = ref([]);

const searchStudents = async () => {
    if (studentSearch.value.trim() === '') {
        searchResults.value = [];
        return;
    }
    
    try {
        const response = await fetch(`/students/search?search=${encodeURIComponent(studentSearch.value)}`);
        if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
        const data = await response.json();
        searchResults.value = data.students || [];
    } catch (error) {
        console.error('Error searching students:', error);
        searchResults.value = [];
    }
};

const addStudent = (student) => {
    if (!form.student_ids.includes(student.id)) {
        form.student_ids.push(student.id);
        selectedStudentsData.value.push(student);
    }
};

const removeStudent = (student) => {
    const index = form.student_ids.indexOf(student.id);
    if (index > -1) {
        form.student_ids.splice(index, 1);
        selectedStudentsData.value = selectedStudentsData.value.filter(s => s.id !== student.id);
    }
};

const selectStudent = (student) => {
    addStudent(student);
    studentSearch.value = '';
    searchResults.value = [];
};

const getStudentById = (studentId) => {
    return selectedStudentsData.value.find(s => s.id === studentId);
};

const getStudentInitials = (student) => {
    if (!student || !student.user || !student.user.name) return "S";
    return student.user.name
        .split(" ")
        .map(word => word[0])
        .join("")
        .toUpperCase()
        .slice(0, 2);
};

const getInitials = (name) => {
    if (!name) return 'PA';
    return name
        .split(' ')
        .map(word => word[0])
        .join('')
        .toUpperCase()
        .slice(0, 2);
};

const getCurrentPhoto = () => {
    return photoPreview.value || null;
};

const submitForm = () => {
    form.post(route('parents.store'), {
        forceFormData: true,
        onSuccess: () => {
            isCreateDialogOpen.value = false;
            form.reset();
            photoPreview.value = null;
            photoFile.value = null;
            selectedStudentsData.value = [];
            studentSearch.value = '';
            searchResults.value = [];
        },
    });
};

const deleteParent = (id) => {
    if (confirm('Are you sure you want to delete this parent?')) {
        router.delete(route('parents.destroy', id));
    }
};

const handlePageChange = (page) => {
    const params = { page };
    if (searchQuery.value) {
        params.search = searchQuery.value;
    }
    router.get(route('parents.index'), params, { preserveState: true });
};

// Watch for search query changes
watch(searchQuery, (newValue) => {
    router.get(route('parents.index'), { 
        search: newValue, 
        page: 1 
    }, { preserveState: true });
}, { debounce: 300 });
</script>

<template>
    <Head title="Parents Management | EduManage Pro" />

    <Sidebar>
        <template #header-title>
            <div class="flex items-center space-x-3">
                <Users class="w-5 h-5 text-terracotta" />
                <span class="font-semibold text-warm-text text-dark-text">Parents</span>
            </div>
        </template>

        <div class="mx-auto max-w-7xl space-y-6 animate-fade-in-up">
            <!-- Page Header -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-4xl font-black text-warm-text text-dark-text tracking-tighter mb-2">Parents</h1>
                    <p class="text-warm-muted text-dark-muted font-medium">Manage all parents in the system</p>
                </div>
                <div class="flex gap-2">
                    <Dialog v-model:open="isCreateDialogOpen">
                        <DialogTrigger as-child>
                            <Button class="accent-terracotta text-white font-black rounded-2xl h-12 px-6 shadow-xl shadow-terracotta/30">
                                <Plus class="w-4 h-4 mr-2" />
                                Add Parent
                            </Button>
                        </DialogTrigger>
                        <DialogContent class="w-[95vw] max-w-7xl max-h-[95vh] overflow-y-auto p-0 card-warm">
                            <!-- Header Banner -->
                            <div class="h-20 accent-terracotta relative">
                                <div class="absolute -bottom-10 left-6 flex items-end">
                                    <Avatar class="w-20 h-20 border-4 border-white dark:border-dark-bg shadow-xl">
                                        <AvatarImage v-if="getCurrentPhoto()" :src="getCurrentPhoto()" :alt="form.name" />
                                        <AvatarFallback class="text-2xl font-bold bg-terracotta text-white">
                                            {{ getInitials(form.name) }}
                                        </AvatarFallback>
                                    </Avatar>
                                </div>
                            </div>
                            
                            <div class="pt-12 pb-6 px-6">
                                <DialogHeader class="mb-6">
                                    <div class="flex items-center gap-2 mb-1">
                                        <Badge variant="outline" class="gap-1.5 text-xs border-terracotta/20 text-terracotta">
                                            <Sparkles class="w-3 h-3" />
                                            Creating Profile
                                        </Badge>
                                        <Badge v-if="form.relationship_to_student" variant="outline" class="text-xs border-terracotta/20">
                                            {{ form.relationship_to_student }}
                                        </Badge>
                                    </div>
                                    <DialogTitle class="text-2xl font-black tracking-tight text-warm-text text-dark-text">{{ form.name || 'New Parent' }}</DialogTitle>
                                    <DialogDescription class="text-warm-muted text-dark-muted">
                                        Create a new parent/guardian account and link to students.
                                    </DialogDescription>
                                </DialogHeader>
                                
                                <form @submit.prevent="submitForm" class="space-y-5">
                                    <!-- Profile Photo Section -->
                                    <div class="card-warm">
                                        <div class="p-6 border-b border-terracotta/20">
                                            <h3 class="flex items-center gap-2 text-base font-black text-warm-text text-dark-text">
                                                <Camera class="w-4 h-4 text-terracotta" />
                                                Profile Photo
                                            </h3>
                                            <p class="text-xs text-warm-muted text-dark-muted mt-1">Upload a profile photo (JPG, PNG - Max 2MB)</p>
                                        </div>
                                        <div class="p-6">
                                            <div class="flex flex-col sm:flex-row items-start gap-4">
                                                <div class="relative">
                                                    <Avatar class="w-24 h-24 border-2 border-terracotta/20">
                                                        <AvatarImage v-if="photoPreview" :src="photoPreview" alt="Parent preview" />
                                                        <AvatarFallback class="text-xl font-bold bg-terracotta text-white">
                                                            {{ getInitials(form.name) }}
                                                        </AvatarFallback>
                                                    </Avatar>
                                                    <Button
                                                        v-if="photoPreview"
                                                        type="button"
                                                        variant="destructive"
                                                        size="icon"
                                                        class="absolute -top-2 -right-2 w-7 h-7 rounded-full shadow-lg"
                                                        @click="removePhoto"
                                                    >
                                                        <X class="w-3 h-3" />
                                                    </Button>
                                                </div>
                                                <div class="flex-1">
                                                    <div class="border-2 border-dashed border-terracotta/30 rounded-lg p-4 text-center hover:border-terracotta transition-colors">
                                                        <Upload class="w-8 h-8 mx-auto text-warm-muted text-dark-muted mb-2" />
                                                        <Label for="parent_photo" class="cursor-pointer text-terracotta hover:text-terracotta/80 font-medium text-sm">
                                                            Click to upload
                                                        </Label>
                                                        <p class="text-xs text-warm-muted text-dark-muted mt-1">PNG, JPG (MAX. 2MB)</p>
                                                        <Input id="parent_photo" type="file" accept="image/*" @change="handlePhotoChange" class="hidden" />
                                                    </div>
                                                    <div v-if="form.errors.photo" class="flex items-center gap-2 text-destructive text-sm mt-2">
                                                        <AlertCircle class="w-4 h-4" />
                                                        {{ form.errors.photo }}
                                                    </div>
                                                    <div v-if="photoFile" class="flex items-center gap-2 text-xs text-warm-muted text-dark-muted mt-2">
                                                        <Camera class="w-3 h-3" />
                                                        <span class="font-medium">{{ photoFile.name }}</span>
                                                        <span>({{ (photoFile.size / 1024).toFixed(2) }} KB)</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Personal Information -->
                                    <div class="card-warm">
                                        <div class="p-6 border-b border-terracotta/20">
                                            <h3 class="flex items-center gap-2 text-base font-black text-warm-text text-dark-text">
                                                <User class="w-4 h-4 text-terracotta" />
                                                Personal Information
                                            </h3>
                                        </div>
                                        <div class="p-6 space-y-4">
                                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                                <div class="space-y-2">
                                                    <Label for="name" class="flex items-center gap-2 text-sm font-medium text-warm-text text-dark-text">
                                                        <User class="w-3 h-3 text-terracotta" />
                                                        Full Name *
                                                    </Label>
                                                    <Input id="name" v-model="form.name" type="text" placeholder="Enter full name" required class="h-10 border-terracotta/20 focus:ring-terracotta/30" />
                                                    <div v-if="form.errors.name" class="flex items-center gap-2 text-destructive text-sm">
                                                        <AlertCircle class="w-3 h-3" />
                                                        {{ form.errors.name }}
                                                    </div>
                                                </div>
                                                <div class="space-y-2">
                                                    <Label for="email" class="flex items-center gap-2 text-sm font-medium text-warm-text text-dark-text">
                                                        <Mail class="w-3 h-3 text-terracotta" />
                                                        Email Address *
                                                    </Label>
                                                    <Input id="email" v-model="form.email" type="email" placeholder="parent@example.com" required class="h-10 border-terracotta/20 focus:ring-terracotta/30" />
                                                    <div v-if="form.errors.email" class="flex items-center gap-2 text-destructive text-sm">
                                                        <AlertCircle class="w-3 h-3" />
                                                        {{ form.errors.email }}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                                <div class="space-y-2">
                                                    <Label for="phone" class="flex items-center gap-2 text-sm font-medium text-warm-text text-dark-text">
                                                        <Phone class="w-3 h-3 text-terracotta" />
                                                        Phone Number *
                                                    </Label>
                                                    <Input id="phone" v-model="form.phone" type="tel" placeholder="+254 700 000 000" required class="h-10 border-terracotta/20 focus:ring-terracotta/30" />
                                                    <div v-if="form.errors.phone" class="flex items-center gap-2 text-destructive text-sm">
                                                        <AlertCircle class="w-3 h-3" />
                                                        {{ form.errors.phone }}
                                                    </div>
                                                </div>
                                                <div class="space-y-2">
                                                    <Label for="occupation" class="flex items-center gap-2 text-sm font-medium text-warm-text text-dark-text">
                                                        <Briefcase class="w-3 h-3 text-terracotta" />
                                                        Occupation
                                                    </Label>
                                                    <Input id="occupation" v-model="form.occupation" type="text" placeholder="Enter occupation" class="h-10 border-terracotta/20 focus:ring-terracotta/30" />
                                                    <div v-if="form.errors.occupation" class="flex items-center gap-2 text-destructive text-sm">
                                                        <AlertCircle class="w-3 h-3" />
                                                        {{ form.errors.occupation }}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="space-y-2">
                                                <Label for="address" class="flex items-center gap-2 text-sm font-medium text-warm-text text-dark-text">
                                                    <MapPin class="w-3 h-3 text-terracotta" />
                                                    Address *
                                                </Label>
                                                <Input id="address" v-model="form.address" type="text" placeholder="Enter full address" required class="h-10 border-terracotta/20 focus:ring-terracotta/30" />
                                                <div v-if="form.errors.address" class="flex items-center gap-2 text-destructive text-sm">
                                                    <AlertCircle class="w-3 h-3" />
                                                    {{ form.errors.address }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Relationship Information -->
                                    <div class="card-warm">
                                        <div class="p-6 border-b border-terracotta/20">
                                            <h3 class="flex items-center gap-2 text-base font-black text-warm-text text-dark-text">
                                                <Heart class="w-4 h-4 text-terracotta" />
                                                Relationship & Students
                                            </h3>
                                        </div>
                                        <div class="p-6 space-y-4">
                                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                                <div class="space-y-2">
                                                    <Label for="relationship_to_student" class="flex items-center gap-2 text-sm font-medium text-warm-text text-dark-text">
                                                        <Users class="w-3 h-3 text-terracotta" />
                                                        Relationship *
                                                    </Label>
                                                    <select
                                                        id="relationship_to_student"
                                                        v-model="form.relationship_to_student"
                                                        class="flex h-10 w-full rounded-md border border-terracotta/20 bg-background px-3 py-2 text-sm focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-terracotta/30"
                                                        required
                                                    >
                                                        <option value="">Select relationship</option>
                                                        <option value="Father">Father</option>
                                                        <option value="Mother">Mother</option>
                                                        <option value="Guardian">Guardian</option>
                                                        <option value="Other">Other</option>
                                                    </select>
                                                    <div v-if="form.errors.relationship_to_student" class="flex items-center gap-2 text-destructive text-sm">
                                                        <AlertCircle class="w-3 h-3" />
                                                        {{ form.errors.relationship_to_student }}
                                                    </div>
                                                </div>
                                                <div class="space-y-2">
                                                    <Label class="flex items-center gap-2 text-sm font-medium text-warm-text text-dark-text">
                                                        <GraduationCap class="w-3 h-3 text-terracotta" />
                                                        Search Students *
                                                    </Label>
                                                    <div class="relative">
                                                        <Input
                                                            v-model="studentSearch"
                                                            type="text"
                                                            placeholder="Search by name or admission..."
                                                            class="w-full pr-10 h-10 border-terracotta/20 focus:ring-terracotta/30"
                                                            @input="searchStudents"
                                                        />
                                                        <Button type="button" variant="ghost" size="sm" class="absolute right-1 top-1/2 -translate-y-1/2 h-8 w-8 p-0" @click="searchStudents">
                                                            <Search class="w-4 h-4 text-terracotta" />
                                                        </Button>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <!-- Search Results -->
                                            <div v-if="searchResults.length > 0" class="p-3 bg-terracotta/5 rounded-lg border border-terracotta/20">
                                                <div class="text-sm font-medium text-warm-text text-dark-text mb-2">Search Results:</div>
                                                <div class="space-y-1 max-h-32 overflow-y-auto">
                                                    <div 
                                                        v-for="student in searchResults" 
                                                        :key="student.id"
                                                        class="flex items-center justify-between p-2 hover:bg-terracotta/10 rounded cursor-pointer transition-colors"
                                                        @click="selectStudent(student)"
                                                    >
                                                        <div>
                                                            <div class="font-medium text-sm text-warm-text text-dark-text">{{ student.user.name }}</div>
                                                            <div class="text-xs text-warm-muted text-dark-muted">{{ student.admission_number }}</div>
                                                        </div>
                                                        <Button size="sm" variant="outline" @click.stop="addStudent(student)" class="border-terracotta/20">
                                                            <Plus class="w-3 h-3" />
                                                        </Button>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Selected Students -->
                                            <div v-if="form.student_ids.length > 0">
                                                <div class="text-sm font-medium text-warm-text text-dark-text mb-2">Selected Students:</div>
                                                <div class="space-y-2">
                                                    <div 
                                                        v-for="studentId in form.student_ids" 
                                                        :key="studentId"
                                                        class="flex items-center justify-between p-2 bg-forest/5 border border-forest/20 rounded-lg"
                                                    >
                                                        <div class="flex items-center gap-2">
                                                            <div class="w-7 h-7 bg-forest text-white rounded-full flex items-center justify-center text-xs font-medium">
                                                                {{ getStudentInitials(getStudentById(studentId)) }}
                                                            </div>
                                                            <div>
                                                                <div class="font-medium text-sm text-warm-text text-dark-text">{{ getStudentById(studentId)?.user?.name }}</div>
                                                                <div class="text-xs text-warm-muted text-dark-muted">{{ getStudentById(studentId)?.admission_number }}</div>
                                                            </div>
                                                        </div>
                                                        <Button type="button" variant="ghost" size="sm" @click="removeStudent({id: studentId})" class="text-destructive hover:text-destructive hover:bg-destructive/10 h-8 w-8 p-0">
                                                            <X class="w-4 h-4" />
                                                        </Button>
                                                    </div>
                                                </div>
                                            </div>
                                            <div v-else-if="studentSearch.trim() === ''" class="p-3 bg-terracotta/5 rounded-lg border border-terracotta/20">
                                                <div class="text-sm text-warm-muted text-dark-muted text-center">
                                                    No students selected. Search above to add students.
                                                </div>
                                            </div>
                                            <div v-if="form.errors.student_ids" class="flex items-center gap-2 text-destructive text-sm">
                                                <AlertCircle class="w-3 h-3" />
                                                {{ form.errors.student_ids }}
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Security -->
                                    <div class="card-warm">
                                        <div class="p-6 border-b border-terracotta/20">
                                            <h3 class="flex items-center gap-2 text-base font-black text-warm-text text-dark-text">
                                                <Lock class="w-4 h-4 text-terracotta" />
                                                Security
                                            </h3>
                                        </div>
                                        <div class="p-6">
                                            <div class="p-3 bg-terracotta/5 border border-terracotta/20 rounded-lg mb-4">
                                                <div class="flex items-start gap-2">
                                                    <AlertCircle class="w-4 h-4 text-terracotta mt-0.5" />
                                                    <div class="text-xs text-warm-muted text-dark-muted">
                                                        Create a secure password for the parent account.
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="space-y-2">
                                                <Label for="password" class="flex items-center gap-2 text-sm font-medium text-warm-text text-dark-text">
                                                    <Lock class="w-3 h-3 text-terracotta" />
                                                    Password *
                                                </Label>
                                                <Input id="password" v-model="form.password" type="password" placeholder="Enter secure password" required class="h-10 border-terracotta/20 focus:ring-terracotta/30" />
                                                <p class="text-xs text-warm-muted text-dark-muted">Minimum 8 characters recommended</p>
                                                <div v-if="form.errors.password" class="flex items-center gap-2 text-destructive text-sm">
                                                    <AlertCircle class="w-3 h-3" />
                                                    {{ form.errors.password }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <DialogFooter class="gap-3 pt-2 border-t border-terracotta/20">
                                        <Button type="button" variant="outline" @click="isCreateDialogOpen = false" class="border-terracotta/20">
                                            <X class="w-4 h-4 mr-2" />
                                            Cancel
                                        </Button>
                                        <Button type="submit" :disabled="form.processing" class="accent-terracotta text-white">
                                            <Save class="w-4 h-4 mr-2" />
                                            {{ form.processing ? 'Creating...' : 'Create Parent' }}
                                        </Button>
                                    </DialogFooter>
                                </form>
                            </div>
                        </DialogContent>
                    </Dialog>
                </div>
            </div>

            <!-- Parents List -->
            <div class="card-warm rounded-xl overflow-hidden">
                <div class="p-6 border-b border-terracotta/20 bg-terracotta/5">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div>
                            <h3 class="text-xl font-black text-warm-text text-dark-text">All Parents</h3>
                            <p class="text-sm text-warm-muted text-dark-muted">{{ parents.total }} registered parents</p>
                        </div>
                        <div class="flex items-center space-x-2">
                            <div class="relative">
                                <Search class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-warm-muted text-dark-muted" />
                                <input
                                    type="text"
                                    v-model="searchQuery"
                                    placeholder="Search parents..."
                                    class="pl-10 pr-4 py-2 border border-terracotta/20 rounded-lg focus:ring-2 focus:ring-terracotta/30 focus:border-terracotta bg-white bg-dark-bg text-warm-text text-dark-text"
                                />
                            </div>
                            <Button variant="outline" size="sm" class="border-terracotta/20">
                                <Filter class="w-4 h-4" />
                            </Button>
                        </div>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="bg-terracotta/5">
                            <tr class="text-[10px] font-black uppercase tracking-[0.2em] text-warm-muted text-dark-muted border-b border-terracotta/20">
                                <th class="px-6 py-4">Name</th>
                                <th class="px-6 py-4">Phone</th>
                                <th class="px-6 py-4">Address</th>
                                <th class="px-6 py-4">Occupation</th>
                                <th class="px-6 py-4">Relationship</th>
                                <th class="px-6 py-4">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-terracotta/20">
                            <tr v-for="parent in parents.data" :key="parent.id" class="hover:bg-terracotta/5 transition-colors duration-300">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 rounded-full overflow-hidden bg-terracotta/10 border-2 border-terracotta/20 mr-3">
                                            <img 
                                                v-if="parent.user?.photo" 
                                                :src="`/storage/${parent.user.photo}`" 
                                                :alt="parent.user?.name"
                                                class="w-full h-full object-cover"
                                            />
                                            <div v-else class="flex items-center justify-center h-full">
                                                <span class="text-terracotta font-bold text-xs">{{ parent.user?.name?.charAt(0) || 'P' }}</span>
                                            </div>
                                        </div>
                                        <div>
                                            <div class="font-medium text-warm-text text-dark-text">{{ parent.user?.name || 'Unknown' }}</div>
                                            <div class="text-sm text-warm-muted text-dark-muted">{{ parent.user?.email || 'No email' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <Phone class="w-4 h-4 mr-2 text-terracotta" />
                                        <span class="text-warm-text text-dark-text">{{ parent.phone || 'N/A' }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-warm-text text-dark-text">{{ parent.address || 'N/A' }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-warm-text text-dark-text">{{ parent.occupation || 'N/A' }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <Badge :class="parent.relationship_to_student === 'Father' ? 'bg-terracotta/10 text-terracotta border-0' : 'bg-forest/10 text-forest border-0'">
                                        {{ parent.relationship_to_student }}
                                    </Badge>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <DropdownMenu>
                                        <DropdownMenuTrigger as-child>
                                            <Button variant="ghost" size="sm" class="hover:bg-terracotta/10">
                                                <Eye class="w-4 h-4 text-terracotta" />
                                            </Button>
                                        </DropdownMenuTrigger>
                                        <DropdownMenuContent align="end" class="card-warm rounded-2xl p-2 shadow-2xl">
                                            <DropdownMenuItem as-child>
                                                <Link :href="route('parents.show', parent.id)" class="flex items-center p-2 rounded-xl hover:bg-terracotta/10">
                                                    <Eye class="w-4 h-4 mr-2" />
                                                    View Details
                                                </Link>
                                            </DropdownMenuItem>
                                            <DropdownMenuItem as-child>
                                                <Link :href="route('parents.edit', parent.id)" class="flex items-center p-2 rounded-xl hover:bg-terracotta/10">
                                                    <Edit class="w-4 h-4 mr-2" />
                                                    Edit
                                                </Link>
                                            </DropdownMenuItem>
                                            <DropdownMenuItem @click="deleteParent(parent.id)" class="flex items-center p-2 rounded-xl text-destructive hover:bg-destructive/10">
                                                <Trash2 class="w-4 h-4 mr-2" />
                                                Delete
                                            </DropdownMenuItem>
                                        </DropdownMenuContent>
                                    </DropdownMenu>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                <div class="p-6 border-t border-terracotta/20 bg-terracotta/5">
                    <Pagination 
                        :data="parents" 
                        @page-change="handlePageChange"
                    />
                </div>
            </div>
        </div>
    </Sidebar>
</template>