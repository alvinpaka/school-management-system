<script setup>
import { Head, useForm, Link } from '@inertiajs/vue3';
import { ref } from 'vue';
import axios from 'axios';
import Sidebar from '@/Components/Sidebar.vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Label } from '@/components/ui/label';
import { Input } from '@/components/ui/input';
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';
import { Separator } from '@/components/ui/separator';
import { Badge } from '@/components/ui/badge';
import { 
    ArrowLeft,
    Save,
    User,
    Mail,
    Phone,
    MapPin,
    Camera,
    Upload,
    X,
    Lock,
    AlertCircle,
    Briefcase,
    Heart,
    Users,
    GraduationCap,
    Plus
} from 'lucide-vue-next';

const props = defineProps({
    parent: Object
});

const form = useForm({
    name: props.parent.user?.name || '',
    email: props.parent.user?.email || '',
    password: '',
    phone: props.parent.phone,
    address: props.parent.address,
    occupation: props.parent.occupation,
    relationship_to_student: props.parent.relationship_to_student,
    student_ids: props.parent.students?.map(s => s.id) || [],
    photo: null,
});

const students = ref([]);
const searchResults = ref([]);
const selectedStudentsData = ref(props.parent.students || []);
const studentSearch = ref('');

const searchStudents = async () => {
    if (studentSearch.value.trim() === '') {
        searchResults.value = [];
        return;
    }
    
    try {
        const response = await fetch(`/students/search?search=${encodeURIComponent(studentSearch.value)}`);
        
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        const data = await response.json();
        
        students.value = data.students || [];
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

const submit = () => {
    if (form.photo instanceof File) {
        const formData = new FormData();
        
        Object.keys(form.data()).forEach(key => {
            const value = form.data()[key];
            if (key === 'photo' && value instanceof File) {
                formData.append(key, value);
            } else if (key === 'student_ids' && Array.isArray(value)) {
                value.forEach(studentId => {
                    formData.append('student_ids[]', studentId);
                });
            } else if (value !== null && value !== undefined && value !== '') {
                formData.append(key, value);
            }
        });
        
        formData.append('_method', 'PUT');
        
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || 
                          document.querySelector('input[name="_token"]')?.value || '';
        if (csrfToken) {
            formData.append('_token', csrfToken);
        }
        
        form.processing = true;
        axios.post(route('parents.update', props.parent.id), formData, {
            headers: {
                'Content-Type': 'multipart/form-data',
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        }).then(response => {
            window.location.href = response.data.redirect;
        }).catch(error => {
            if (error.response && error.response.status === 422) {
                form.errors = error.response.data.errors;
            }
            form.processing = false;
        });
    } else {
        form.put(route('parents.update', props.parent.id));
    }
};

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
    const fileInput = document.getElementById('photo');
    if (fileInput) {
        fileInput.value = '';
    }
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
    if (photoPreview.value) {
        return photoPreview.value;
    }
    if (props.parent.user?.photo) {
        return `/storage/${props.parent.user.photo}`;
    }
    return null;
};
</script>

<template>
    <Head title="Edit Parent | EduManage Pro" />

    <Sidebar>
        <template #header-title>
            <div class="flex items-center space-x-3">
                <Link :href="route('parents.show', parent.id)">
                    <Button variant="ghost" size="sm" class="gap-2 hover:bg-terracotta/10 text-warm-text dark:text-dark-text">
                        <ArrowLeft class="w-4 h-4" />
                        Back to Profile
                    </Button>
                </Link>
                <Separator orientation="vertical" class="h-6 bg-terracotta/20" />
                <span class="font-semibold text-warm-text dark:text-dark-text">Edit Parent</span>
            </div>
        </template>

        <div class="mx-auto max-w-7xl space-y-6">
            <!-- Header Card -->
            <div class="card-warm overflow-hidden">
                <div class="h-24 accent-terracotta"></div>
                <div class="relative pt-0 pb-6 p-6">
                    <div class="flex flex-col md:flex-row md:items-end md:justify-between -mt-12">
                        <div class="flex flex-col md:flex-row items-center md:items-end space-y-4 md:space-y-0 md:space-x-6">
                            <Avatar class="w-24 h-24 md:w-32 md:h-32 border-4 border-white dark:border-dark-bg shadow-xl">
                                <AvatarImage 
                                    v-if="getCurrentPhoto()"
                                    :src="getCurrentPhoto()" 
                                    :alt="form.name"
                                />
                                <AvatarFallback class="text-2xl md:text-3xl font-bold bg-terracotta text-white">
                                    {{ getInitials(form.name) }}
                                </AvatarFallback>
                            </Avatar>
                            
                            <div class="text-center md:text-left space-y-2 mb-2">
                                <h1 class="text-2xl md:text-3xl font-bold text-warm-text dark:text-dark-text">
                                    {{ form.name || 'Parent Name' }}
                                </h1>
                                <div class="flex flex-wrap items-center justify-center md:justify-start gap-2">
                                    <Badge variant="outline" class="text-xs border-terracotta/20 text-terracotta">
                                        {{ form.relationship_to_student || 'Parent/Guardian' }}
                                    </Badge>
                                    <Badge variant="outline" class="text-xs border-amber/20 text-amber">
                                        Editing Profile
                                    </Badge>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Form -->
            <form @submit.prevent="submit" class="space-y-6">
                <!-- Photo Upload Card -->
                <div class="card-warm">
                    <div class="p-6 border-b border-terracotta/20">
                        <h3 class="flex items-center gap-2 text-lg font-black text-warm-text dark:text-dark-text">
                            <Camera class="w-5 h-5 text-terracotta" />
                            Profile Photo
                        </h3>
                        <p class="text-sm text-warm-muted dark:text-dark-muted mt-1">
                            Upload a new profile photo for the parent (JPG, PNG - Max 2MB)
                        </p>
                    </div>
                    <div class="p-6">
                        <div class="flex flex-col md:flex-row items-start gap-6">
                            <!-- Current Photo Preview -->
                            <div class="flex flex-col items-center space-y-4">
                                <div class="relative">
                                    <Avatar class="w-40 h-40 border-4 border-terracotta/20">
                                        <AvatarImage 
                                            v-if="getCurrentPhoto()"
                                            :src="getCurrentPhoto()" 
                                            :alt="form.name"
                                        />
                                        <AvatarFallback class="text-4xl font-bold bg-terracotta text-white">
                                            {{ getInitials(form.name) }}
                                        </AvatarFallback>
                                    </Avatar>
                                    <Button
                                        v-if="photoPreview"
                                        type="button"
                                        variant="destructive"
                                        size="icon"
                                        class="absolute -top-2 -right-2 w-8 h-8 rounded-full shadow-lg"
                                        @click="removePhoto"
                                    >
                                        <X class="w-4 h-4" />
                                    </Button>
                                </div>
                                <Badge v-if="photoPreview" variant="secondary" class="text-xs bg-terracotta/10 text-terracotta">
                                    New Photo Selected
                                </Badge>
                            </div>

                            <!-- Upload Section -->
                            <div class="flex-1 space-y-4">
                                <div class="border-2 border-dashed border-terracotta/30 rounded-lg p-6 text-center hover:border-terracotta transition-colors">
                                    <Upload class="w-12 h-12 mx-auto text-warm-muted dark:text-dark-muted mb-3" />
                                    <div class="space-y-2">
                                        <Label 
                                            for="photo" 
                                            class="cursor-pointer text-terracotta hover:text-terracotta/80 font-medium"
                                        >
                                            Click to upload
                                        </Label>
                                        <p class="text-sm text-warm-muted dark:text-dark-muted">
                                            or drag and drop
                                        </p>
                                        <p class="text-xs text-warm-muted dark:text-dark-muted">
                                            PNG, JPG or JPEG (MAX. 2MB)
                                        </p>
                                    </div>
                                    <Input
                                        id="photo"
                                        type="file"
                                        accept="image/*"
                                        @change="handlePhotoChange"
                                        class="hidden"
                                    />
                                </div>
                                <div v-if="form.errors.photo" class="flex items-center gap-2 text-destructive text-sm">
                                    <AlertCircle class="w-4 h-4" />
                                    {{ form.errors.photo }}
                                </div>
                                <div v-if="photoFile" class="flex items-center gap-2 text-sm text-warm-muted dark:text-dark-muted">
                                    <Camera class="w-4 h-4" />
                                    <span class="font-medium">{{ photoFile.name }}</span>
                                    <span class="text-xs">({{ (photoFile.size / 1024).toFixed(2) }} KB)</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Personal Information -->
                <div class="card-warm">
                    <div class="p-6 border-b border-terracotta/20">
                        <h3 class="flex items-center gap-2 text-lg font-black text-warm-text dark:text-dark-text">
                            <User class="w-5 h-5 text-terracotta" />
                            Personal Information
                        </h3>
                        <p class="text-sm text-warm-muted dark:text-dark-muted mt-1">
                            Update the parent's basic personal details
                        </p>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <Label for="name" class="flex items-center gap-2 text-warm-text dark:text-dark-text">
                                    <User class="w-4 h-4 text-terracotta" />
                                    Full Name *
                                </Label>
                                <Input
                                    id="name"
                                    v-model="form.name"
                                    type="text"
                                    placeholder="Enter full name"
                                    required
                                    autofocus
                                    class="border-terracotta/20 focus:ring-terracotta/30"
                                />
                                <div v-if="form.errors.name" class="flex items-center gap-2 text-destructive text-sm">
                                    <AlertCircle class="w-4 h-4" />
                                    {{ form.errors.name }}
                                </div>
                            </div>

                            <div class="space-y-2">
                                <Label for="email" class="flex items-center gap-2 text-warm-text dark:text-dark-text">
                                    <Mail class="w-4 h-4 text-terracotta" />
                                    Email Address *
                                </Label>
                                <Input
                                    id="email"
                                    v-model="form.email"
                                    type="email"
                                    placeholder="parent@example.com"
                                    required
                                    autocomplete="username"
                                    class="border-terracotta/20 focus:ring-terracotta/30"
                                />
                                <div v-if="form.errors.email" class="flex items-center gap-2 text-destructive text-sm">
                                    <AlertCircle class="w-4 h-4" />
                                    {{ form.errors.email }}
                                </div>
                            </div>

                            <div class="space-y-2">
                                <Label for="phone" class="flex items-center gap-2 text-warm-text dark:text-dark-text">
                                    <Phone class="w-4 h-4 text-terracotta" />
                                    Phone Number *
                                </Label>
                                <Input
                                    id="phone"
                                    v-model="form.phone"
                                    type="tel"
                                    placeholder="+254 700 000 000"
                                    required
                                    class="border-terracotta/20 focus:ring-terracotta/30"
                                />
                                <div v-if="form.errors.phone" class="flex items-center gap-2 text-destructive text-sm">
                                    <AlertCircle class="w-4 h-4" />
                                    {{ form.errors.phone }}
                                </div>
                            </div>

                            <div class="space-y-2">
                                <Label for="occupation" class="flex items-center gap-2 text-warm-text dark:text-dark-text">
                                    <Briefcase class="w-4 h-4 text-terracotta" />
                                    Occupation
                                </Label>
                                <Input
                                    id="occupation"
                                    v-model="form.occupation"
                                    type="text"
                                    placeholder="Enter occupation"
                                    class="border-terracotta/20 focus:ring-terracotta/30"
                                />
                                <div v-if="form.errors.occupation" class="flex items-center gap-2 text-destructive text-sm">
                                    <AlertCircle class="w-4 h-4" />
                                    {{ form.errors.occupation }}
                                </div>
                            </div>

                            <div class="space-y-2 md:col-span-2">
                                <Label for="address" class="flex items-center gap-2 text-warm-text dark:text-dark-text">
                                    <MapPin class="w-4 h-4 text-terracotta" />
                                    Address *
                                </Label>
                                <Input
                                    id="address"
                                    v-model="form.address"
                                    type="text"
                                    placeholder="Enter full address"
                                    required
                                    class="border-terracotta/20 focus:ring-terracotta/30"
                                />
                                <div v-if="form.errors.address" class="flex items-center gap-2 text-destructive text-sm">
                                    <AlertCircle class="w-4 h-4" />
                                    {{ form.errors.address }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Relationship Information -->
                <div class="card-warm">
                    <div class="p-6 border-b border-terracotta/20">
                        <h3 class="flex items-center gap-2 text-lg font-black text-warm-text dark:text-dark-text">
                            <Heart class="w-5 h-5 text-terracotta" />
                            Relationship Information
                        </h3>
                        <p class="text-sm text-warm-muted dark:text-dark-muted mt-1">
                            Relationship to student and family details
                        </p>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <Label for="relationship_to_student" class="flex items-center gap-2 text-warm-text dark:text-dark-text">
                                    <Users class="w-4 h-4 text-terracotta" />
                                    Relationship to Student *
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
                                    <AlertCircle class="w-4 h-4" />
                                    {{ form.errors.relationship_to_student }}
                                </div>
                            </div>
                            
                            <div class="space-y-2">
                                <Label for="student_ids" class="flex items-center gap-2 text-warm-text dark:text-dark-text">
                                    <GraduationCap class="w-4 h-4 text-terracotta" />
                                    Search & Select Students *
                                </Label>
                                <div class="relative">
                                    <Input
                                        id="student_search"
                                        v-model="studentSearch"
                                        @input="searchStudents"
                                        type="text"
                                        placeholder="Search students by name or admission number..."
                                        class="w-full border-terracotta/20 focus:ring-terracotta/30"
                                    />
                                </div>
                                
                                <!-- Search Results -->
                                <div v-if="searchResults.length > 0" class="mt-2 max-h-40 overflow-y-auto border border-terracotta/20 rounded-lg">
                                    <div 
                                        v-for="student in searchResults" 
                                        :key="student.id"
                                        @click="addStudent(student)"
                                        class="p-3 hover:bg-terracotta/5 cursor-pointer border-b border-terracotta/20 last:border-b-0 transition-colors"
                                    >
                                        <div class="flex items-center justify-between">
                                            <div>
                                                <div class="font-medium text-warm-text dark:text-dark-text">
                                                    {{ student.user.name }}
                                                </div>
                                                <div class="text-sm text-warm-muted dark:text-dark-muted">
                                                    {{ student.admission_number }} • {{ student.academic_class?.name }}
                                                </div>
                                            </div>
                                            <Plus class="w-4 h-4 text-forest" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Selected Students Display -->
                            <div v-if="selectedStudentsData.length > 0" class="mt-4 md:col-span-2">
                                <div class="text-sm font-medium text-warm-text dark:text-dark-text mb-2">Selected Students:</div>
                                <div class="space-y-2">
                                    <div 
                                        v-for="student in selectedStudentsData" 
                                        :key="student.id"
                                        class="flex items-center justify-between p-3 bg-forest/5 border border-forest/20 rounded-lg"
                                    >
                                        <div>
                                            <div class="font-medium text-warm-text dark:text-dark-text">
                                                {{ student.user.name }}
                                            </div>
                                            <div class="text-sm text-warm-muted dark:text-dark-muted">
                                                {{ student.admission_number }} • {{ student.academic_class?.name }}
                                            </div>
                                        </div>
                                        <button
                                            @click="removeStudent(student)"
                                            class="text-destructive hover:text-destructive/80 transition-colors"
                                        >
                                            <X class="w-4 h-4" />
                                        </button>
                                    </div>
                                </div>
                            </div>
                            
                            <div v-else-if="studentSearch.trim() === ''" class="mt-2 p-3 bg-terracotta/5 rounded-lg border border-terracotta/20">
                                <div class="text-sm text-warm-muted dark:text-dark-muted text-center">
                                    {{ selectedStudentsData.length > 0 ? 'No additional students selected. Search above to add more students.' : 'No students selected. Search above to add students.' }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Security -->
                <div class="card-warm">
                    <div class="p-6 border-b border-terracotta/20">
                        <h3 class="flex items-center gap-2 text-lg font-black text-warm-text dark:text-dark-text">
                            <Lock class="w-5 h-5 text-terracotta" />
                            Security Settings
                        </h3>
                        <p class="text-sm text-warm-muted dark:text-dark-muted mt-1">
                            Update parent account password
                        </p>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            <div class="p-4 bg-amber/5 border border-amber/20 rounded-lg">
                                <div class="flex items-start gap-3">
                                    <AlertCircle class="w-5 h-5 text-amber mt-0.5" />
                                    <div class="flex-1">
                                        <h4 class="font-medium text-amber mb-1">
                                            Password Update Notice
                                        </h4>
                                        <p class="text-sm text-amber/80">
                                            Leave the password field empty to keep the current password. Only fill this field if you want to change the parent's password.
                                        </p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="space-y-2 max-w-md">
                                <Label for="password" class="flex items-center gap-2 text-warm-text dark:text-dark-text">
                                    <Lock class="w-4 h-4 text-terracotta" />
                                    New Password
                                </Label>
                                <Input
                                    id="password"
                                    v-model="form.password"
                                    type="password"
                                    placeholder="Enter new password"
                                    autocomplete="new-password"
                                    class="border-terracotta/20 focus:ring-terracotta/30"
                                />
                                <p class="text-xs text-warm-muted dark:text-dark-muted">
                                    Minimum 8 characters recommended
                                </p>
                                <div v-if="form.errors.password" class="flex items-center gap-2 text-destructive text-sm">
                                    <AlertCircle class="w-4 h-4" />
                                    {{ form.errors.password }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="card-warm">
                    <div class="p-6">
                        <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                            <p class="text-sm text-warm-muted dark:text-dark-muted">
                                * Required fields must be filled
                            </p>
                            <div class="flex items-center gap-3">
                                <Link :href="route('parents.show', parent.id)">
                                    <Button variant="outline" type="button" class="border-terracotta/20">
                                        <X class="w-4 h-4 mr-2" />
                                        Cancel
                                    </Button>
                                </Link>
                                <Button type="submit" :disabled="form.processing" size="lg" class="accent-terracotta text-white">
                                    <Save class="w-4 h-4 mr-2" />
                                    {{ form.processing ? 'Updating...' : 'Update Parent' }}
                                </Button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </Sidebar>
</template>