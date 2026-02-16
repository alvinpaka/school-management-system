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
    // Check if form has photo to handle properly
    if (form.photo instanceof File) {
        // Create FormData manually to ensure all fields are included
        const formData = new FormData();
        
        // Add all form fields
        Object.keys(form.data()).forEach(key => {
            const value = form.data()[key];
            if (key === 'photo' && value instanceof File) {
                formData.append(key, value);
            } else if (key === 'student_ids' && Array.isArray(value)) {
                // Handle array properly - append each item separately
                value.forEach(studentId => {
                    formData.append('student_ids[]', studentId);
                });
            } else if (value !== null && value !== undefined && value !== '') {
                formData.append(key, value);
            }
        });
        
        // Add _method for PUT request
        formData.append('_method', 'PUT');
        
        // Get CSRF token
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || 
                          document.querySelector('input[name="_token"]')?.value || '';
        if (csrfToken) {
            formData.append('_token', csrfToken);
        }
        
        // Submit using axios directly
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
                // Handle validation errors
                form.errors = error.response.data.errors;
            }
            form.processing = false;
        });
    } else {
        // Normal form submission without photo
        form.put(route('parents.update', props.parent.id));
    }
};

// Handle photo upload and preview
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
    <Head title="Edit Parent" />

    <Sidebar>
        <template #header-title>
            <div class="flex items-center space-x-3">
                <Link :href="route('parents.show', parent.id)">
                    <Button variant="ghost" size="sm" class="gap-2">
                        <ArrowLeft class="w-4 h-4" />
                        Back to Profile
                    </Button>
                </Link>
                <Separator orientation="vertical" class="h-6" />
                <span class="font-semibold">Edit Parent</span>
            </div>
        </template>

        <div class="mx-auto max-w-7xl space-y-6">
            <!-- Header Card -->
            <Card class="overflow-hidden">
                <div class="h-24 bg-purple-500"></div>
                <CardContent class="relative pt-0 pb-6">
                    <div class="flex flex-col md:flex-row md:items-end md:justify-between -mt-12">
                        <div class="flex flex-col md:flex-row items-center md:items-end space-y-4 md:space-y-0 md:space-x-6">
                            <Avatar class="w-24 h-24 md:w-32 md:h-32 border-4 border-white dark:border-gray-950 shadow-xl">
                                <AvatarImage 
                                    v-if="getCurrentPhoto()"
                                    :src="getCurrentPhoto()" 
                                    :alt="form.name"
                                />
                                <AvatarFallback class="text-2xl md:text-3xl font-bold bg-purple-500 text-white">
                                    {{ getInitials(form.name) }}
                                </AvatarFallback>
                            </Avatar>
                            
                            <div class="text-center md:text-left space-y-2 mb-2">
                                <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white">
                                    {{ form.name || 'Parent Name' }}
                                </h1>
                                <div class="flex flex-wrap items-center justify-center md:justify-start gap-2">
                                    <Badge variant="outline" class="text-xs">
                                        {{ form.relationship_to_student || 'Parent/Guardian' }}
                                    </Badge>
                                    <Badge variant="outline" class="text-xs">
                                        Editing Profile
                                    </Badge>
                                </div>
                            </div>
                        </div>
                    </div>
                </CardContent>
            </Card>

            <!-- Main Form -->
            <form @submit.prevent="submit" class="space-y-6">
                <!-- Photo Upload Card -->
                <Card>
                    <CardHeader>
                        <CardTitle class="flex items-center gap-2">
                            <Camera class="w-5 h-5" />
                            Profile Photo
                        </CardTitle>
                        <CardDescription>
                            Upload a new profile photo for the parent (JPG, PNG - Max 2MB)
                        </CardDescription>
                    </CardHeader>
                    <CardContent>
                        <div class="flex flex-col md:flex-row items-start gap-6">
                            <!-- Current Photo Preview -->
                            <div class="flex flex-col items-center space-y-4">
                                <div class="relative">
                                    <Avatar class="w-40 h-40 border-4 border-gray-200 dark:border-gray-800">
                                        <AvatarImage 
                                            v-if="getCurrentPhoto()"
                                            :src="getCurrentPhoto()" 
                                            :alt="form.name"
                                        />
                                        <AvatarFallback class="text-4xl font-bold bg-purple-500 text-white">
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
                                <Badge v-if="photoPreview" variant="secondary" class="text-xs">
                                    New Photo Selected
                                </Badge>
                            </div>

                            <!-- Upload Section -->
                            <div class="flex-1 space-y-4">
                                <div class="border-2 border-dashed border-gray-300 dark:border-gray-700 rounded-lg p-6 text-center hover:border-purple-500 dark:hover:border-purple-500 transition-colors">
                                    <Upload class="w-12 h-12 mx-auto text-gray-400 mb-3" />
                                    <div class="space-y-2">
                                        <Label 
                                            for="photo" 
                                            class="cursor-pointer text-purple-600 hover:text-purple-700 dark:text-purple-400 dark:hover:text-purple-300 font-medium"
                                        >
                                            Click to upload
                                        </Label>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">
                                            or drag and drop
                                        </p>
                                        <p class="text-xs text-gray-400 dark:text-gray-500">
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
                                <div v-if="form.errors.photo" class="flex items-center gap-2 text-red-600 text-sm">
                                    <AlertCircle class="w-4 h-4" />
                                    {{ form.errors.photo }}
                                </div>
                                <div v-if="photoFile" class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400">
                                    <Camera class="w-4 h-4" />
                                    <span class="font-medium">{{ photoFile.name }}</span>
                                    <span class="text-xs">({{ (photoFile.size / 1024).toFixed(2) }} KB)</span>
                                </div>
                            </div>
                        </div>
                    </CardContent>
                </Card>

                <!-- Personal Information -->
                <Card>
                    <CardHeader>
                        <CardTitle class="flex items-center gap-2">
                            <User class="w-5 h-5" />
                            Personal Information
                        </CardTitle>
                        <CardDescription>
                            Update the parent's basic personal details
                        </CardDescription>
                    </CardHeader>
                    <CardContent>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <Label for="name" class="flex items-center gap-2">
                                    <User class="w-4 h-4 text-gray-500" />
                                    Full Name *
                                </Label>
                                <Input
                                    id="name"
                                    v-model="form.name"
                                    type="text"
                                    placeholder="Enter full name"
                                    required
                                    autofocus
                                />
                                <div v-if="form.errors.name" class="flex items-center gap-2 text-red-600 text-sm">
                                    <AlertCircle class="w-4 h-4" />
                                    {{ form.errors.name }}
                                </div>
                            </div>

                            <div class="space-y-2">
                                <Label for="email" class="flex items-center gap-2">
                                    <Mail class="w-4 h-4 text-gray-500" />
                                    Email Address *
                                </Label>
                                <Input
                                    id="email"
                                    v-model="form.email"
                                    type="email"
                                    placeholder="parent@example.com"
                                    required
                                    autocomplete="username"
                                />
                                <div v-if="form.errors.email" class="flex items-center gap-2 text-red-600 text-sm">
                                    <AlertCircle class="w-4 h-4" />
                                    {{ form.errors.email }}
                                </div>
                            </div>

                            <div class="space-y-2">
                                <Label for="phone" class="flex items-center gap-2">
                                    <Phone class="w-4 h-4 text-gray-500" />
                                    Phone Number *
                                </Label>
                                <Input
                                    id="phone"
                                    v-model="form.phone"
                                    type="tel"
                                    placeholder="+256 700 000 000"
                                    required
                                />
                                <div v-if="form.errors.phone" class="flex items-center gap-2 text-red-600 text-sm">
                                    <AlertCircle class="w-4 h-4" />
                                    {{ form.errors.phone }}
                                </div>
                            </div>

                            <div class="space-y-2">
                                <Label for="occupation" class="flex items-center gap-2">
                                    <Briefcase class="w-4 h-4 text-gray-500" />
                                    Occupation
                                </Label>
                                <Input
                                    id="occupation"
                                    v-model="form.occupation"
                                    type="text"
                                    placeholder="Enter occupation"
                                />
                                <div v-if="form.errors.occupation" class="flex items-center gap-2 text-red-600 text-sm">
                                    <AlertCircle class="w-4 h-4" />
                                    {{ form.errors.occupation }}
                                </div>
                            </div>

                            <div class="space-y-2 md:col-span-2">
                                <Label for="address" class="flex items-center gap-2">
                                    <MapPin class="w-4 h-4 text-gray-500" />
                                    Address *
                                </Label>
                                <Input
                                    id="address"
                                    v-model="form.address"
                                    type="text"
                                    placeholder="Enter full address"
                                    required
                                />
                                <div v-if="form.errors.address" class="flex items-center gap-2 text-red-600 text-sm">
                                    <AlertCircle class="w-4 h-4" />
                                    {{ form.errors.address }}
                                </div>
                            </div>
                        </div>
                    </CardContent>
                </Card>

                <!-- Relationship Information -->
                <Card>
                    <CardHeader>
                        <CardTitle class="flex items-center gap-2">
                            <Heart class="w-5 h-5" />
                            Relationship Information
                        </CardTitle>
                        <CardDescription>
                            Relationship to student and family details
                        </CardDescription>
                    </CardHeader>
                    <CardContent>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <Label for="relationship_to_student" class="flex items-center gap-2">
                                    <Users class="w-4 h-4 text-gray-500" />
                                    Relationship to Student *
                                </Label>
                                <select
                                    id="relationship_to_student"
                                    v-model="form.relationship_to_student"
                                    class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
                                    required
                                >
                                    <option value="">Select relationship</option>
                                    <option value="Father">Father</option>
                                    <option value="Mother">Mother</option>
                                    <option value="Guardian">Guardian</option>
                                    <option value="Other">Other</option>
                                </select>
                                <div v-if="form.errors.relationship_to_student" class="flex items-center gap-2 text-red-600 text-sm">
                                    <AlertCircle class="w-4 h-4" />
                                    {{ form.errors.relationship_to_student }}
                                </div>
                            </div>
                            
                            <div class="space-y-2">
                                <Label for="student_ids" class="flex items-center gap-2">
                                    <GraduationCap class="w-4 h-4 text-gray-500" />
                                    Search & Select Students *
                                </Label>
                                <div class="relative">
                                    <Input
                                        id="student_search"
                                        v-model="studentSearch"
                                        @input="searchStudents"
                                        type="text"
                                        placeholder="Search students by name or admission number..."
                                        class="w-full"
                                    />
                                    </div>
                                
                                <!-- Search Results -->
                                <div v-if="searchResults.length > 0" class="mt-2 max-h-40 overflow-y-auto border border-gray-200 dark:border-gray-700 rounded-lg">
                                    <div 
                                        v-for="student in searchResults" 
                                        :key="student.id"
                                        @click="addStudent(student)"
                                        class="p-3 hover:bg-gray-50 dark:hover:bg-gray-800 cursor-pointer border-b border-gray-100 dark:border-gray-800 last:border-b-0"
                                    >
                                        <div class="flex items-center justify-between">
                                            <div>
                                                <div class="font-medium text-gray-900 dark:text-white">
                                                    {{ student.user.name }}
                                                </div>
                                                <div class="text-sm text-gray-500 dark:text-gray-400">
                                                    {{ student.admission_number }} • {{ student.academic_class?.name }}
                                                </div>
                                            </div>
                                            <Plus class="w-4 h-4 text-green-600" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Selected Students Display -->
                            <div v-if="selectedStudentsData.length > 0" class="mt-4">
                                <div class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Selected Students:</div>
                                <div class="space-y-2">
                                    <div 
                                        v-for="student in selectedStudentsData" 
                                        :key="student.id"
                                        class="flex items-center justify-between p-3 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg"
                                    >
                                        <div>
                                            <div class="font-medium text-gray-900 dark:text-white">
                                                {{ student.user.name }}
                                            </div>
                                            <div class="text-sm text-gray-500 dark:text-gray-400">
                                                {{ student.admission_number }} • {{ student.academic_class?.name }}
                                            </div>
                                        </div>
                                        <button
                                            @click="removeStudent(student)"
                                            class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300"
                                        >
                                            <X class="w-4 h-4" />
                                        </button>
                                    </div>
                                </div>
                            </div>
                            
                            <div v-else-if="studentSearch.trim() === ''" class="mt-2 p-3 bg-gray-50 dark:bg-gray-800 rounded-lg border">
                                <div class="text-sm text-gray-500 dark:text-gray-400 text-center">
                                    {{ selectedStudentsData.length > 0 ? 'No additional students selected. Search above to add more students.' : 'No students selected. Search above to add students.' }}
                                </div>
                            </div>
                        </div>
                    </CardContent>
                </Card>

                <!-- Security -->
                <Card>
                    <CardHeader>
                        <CardTitle class="flex items-center gap-2">
                            <Lock class="w-5 h-5" />
                            Security Settings
                        </CardTitle>
                        <CardDescription>
                            Update parent account password
                        </CardDescription>
                    </CardHeader>
                    <CardContent>
                        <div class="space-y-4">
                            <div class="p-4 bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 rounded-lg">
                                <div class="flex items-start gap-3">
                                    <AlertCircle class="w-5 h-5 text-amber-600 dark:text-amber-400 mt-0.5" />
                                    <div class="flex-1">
                                        <h4 class="font-medium text-amber-900 dark:text-amber-200 mb-1">
                                            Password Update Notice
                                        </h4>
                                        <p class="text-sm text-amber-700 dark:text-amber-300">
                                            Leave the password field empty to keep the current password. Only fill this field if you want to change the parent's password.
                                        </p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="space-y-2 max-w-md">
                                <Label for="password" class="flex items-center gap-2">
                                    <Lock class="w-4 h-4 text-gray-500" />
                                    New Password
                                </Label>
                                <Input
                                    id="password"
                                    v-model="form.password"
                                    type="password"
                                    placeholder="Enter new password"
                                    autocomplete="new-password"
                                />
                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                    Minimum 8 characters recommended
                                </p>
                                <div v-if="form.errors.password" class="flex items-center gap-2 text-red-600 text-sm">
                                    <AlertCircle class="w-4 h-4" />
                                    {{ form.errors.password }}
                                </div>
                            </div>
                        </div>
                    </CardContent>
                </Card>

                <!-- Form Actions -->
                <Card>
                    <CardContent class="pt-6">
                        <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                * Required fields must be filled
                            </p>
                            <div class="flex items-center gap-3">
                                <Link :href="route('parents.show', parent.id)">
                                    <Button variant="outline" type="button">
                                        <X class="w-4 h-4 mr-2" />
                                        Cancel
                                    </Button>
                                </Link>
                                <Button type="submit" :disabled="form.processing" size="lg">
                                    <Save class="w-4 h-4 mr-2" />
                                    {{ form.processing ? 'Updating...' : 'Update Parent' }}
                                </Button>
                            </div>
                        </div>
                    </CardContent>
                </Card>
            </form>
        </div>
    </Sidebar>
</template>

<style scoped>
/* Custom select styling to match shadcn-vue Input component */
select {
    transition: all 0.2s;
}

select:focus {
    outline: none;
}
</style>