<script setup>
import { Head, useForm, Link } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';
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
    Lock,
    Phone,
    MapPin,
    Camera,
    Upload,
    X,
    AlertCircle,
    Sparkles,
    Briefcase,
    Heart,
    Users,
    GraduationCap,
    Search,
    Plus
} from 'lucide-vue-next';

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

const students = ref([]);
const searchResults = ref([]);
const selectedStudentsData = ref([]);
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

const selectStudent = (student) => {
    addStudent(student);
    studentSearch.value = '';
    searchResults.value = [];
};

onMounted(() => {
    // Initial load - no need to fetch all students
});

const submit = () => {
    form.post(route('parents.store'));
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

const getStudentById = (studentId) => {
    return students.value.find(s => s.id === studentId) || 
           selectedStudentsData.value.find(s => s.id === studentId);
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
const getCurrentPhoto = () => {
    if (photoPreview.value) {
        return photoPreview.value;
    }
    return null;
};
</script>

<template>
    <Head title="Add Parent" />

    <Sidebar>
        <template #header-title>
            <div class="flex items-center space-x-3">
                <Link :href="route('parents.index')">
                    <Button variant="ghost" size="sm" class="gap-2">
                        <ArrowLeft class="w-4 h-4" />
                        Parents
                    </Button>
                </Link>
                <Separator orientation="vertical" class="h-6" />
                <span class="font-semibold">Add New Parent</span>
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
                                    {{ form.name || 'New Parent' }}
                                </h1>
                                <div class="flex flex-wrap items-center justify-center md:justify-start gap-2">
                                    <Badge variant="outline" class="gap-1.5">
                                        <Sparkles class="w-3 h-3" />
                                        Creating Profile
                                    </Badge>
                                    <Badge v-if="form.relationship_to_student" variant="outline" class="text-xs">
                                        {{ form.relationship_to_student }}
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
                            Upload a profile photo for the parent (JPG, PNG - Max 2MB)
                        </CardDescription>
                    </CardHeader>
                    <CardContent>
                        <div class="flex flex-col md:flex-row items-start gap-6">
                            <!-- Current Photo Preview -->
                            <div class="flex flex-col items-center space-y-4">
                                <div class="relative">
                                    <Avatar class="w-40 h-40 border-4 border-gray-200 dark:border-gray-800">
                                        <AvatarImage 
                                            v-if="photoPreview"
                                            :src="photoPreview" 
                                            alt="Parent preview"
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
                                    Photo Selected
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
                            Enter the parent's basic personal details
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
                                        type="text"
                                        placeholder="Search by name or admission number..."
                                        class="w-full pr-10"
                                        @input="searchStudents"
                                    />
                                    <Button
                                        type="button"
                                        variant="ghost"
                                        size="sm"
                                        class="absolute right-2 top-1/2"
                                        @click="searchStudents"
                                    >
                                        <Search class="w-4 h-4" />
                                    </Button>
                                </div>
                                <div v-if="searchResults.length > 0" class="mt-2 p-2 bg-gray-50 dark:bg-gray-800 rounded-lg border">
                                    <div class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Search Results:</div>
                                    <div class="space-y-1 max-h-32 overflow-y-auto">
                                        <div 
                                            v-for="student in searchResults" 
                                            :key="student.id"
                                            class="flex items-center justify-between p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded cursor-pointer"
                                            @click="selectStudent(student)"
                                        >
                                            <div class="flex-1">
                                                <div>
                                                    <div class="font-medium text-gray-900 dark:text-white">{{ student.user.name }}</div>
                                                    <div class="text-sm text-gray-500 dark:text-gray-400">{{ student.admission_number }}</div>
                                                </div>
                                                <Button
                                                    size="sm"
                                                    variant="outline"
                                                    @click.stop="addStudent(student)"
                                                >
                                                    <Plus class="w-4 h-4" />
                                                </Button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Selected Students Display -->
                            <div v-if="form.student_ids.length > 0" class="mt-4">
                                <div class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Selected Students:</div>
                                <div class="space-y-2">
                                    <div 
                                        v-for="studentId in form.student_ids" 
                                        :key="studentId"
                                        class="flex items-center justify-between p-3 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg"
                                    >
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 bg-green-500 text-white rounded-full flex items-center justify-center text-sm font-medium">
                                                {{ getStudentInitials(getStudentById(studentId)) }}
                                            </div>
                                            <div>
                                                <div class="font-medium text-gray-900 dark:text-white">{{ getStudentById(studentId)?.user?.name }}</div>
                                                <div class="text-sm text-gray-500 dark:text-gray-400">{{ getStudentById(studentId)?.admission_number }}</div>
                                            </div>
                                        </div>
                                        <Button
                                            type="button"
                                            variant="outline"
                                            size="sm"
                                            @click="removeStudent({id: studentId})"
                                            class="text-red-600 hover:text-red-700 hover:bg-red-50"
                                        >
                                            <X class="w-4 h-4" />
                                        </Button>
                                    </div>
                                </div>
                            </div>
                            
                            <div v-else-if="studentSearch.trim() === ''" class="mt-2 p-3 bg-gray-50 dark:bg-gray-800 rounded-lg border">
                                <div class="text-sm text-gray-500 dark:text-gray-400 text-center">
                                    No students selected. Search above to add students.
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
                            Set up login credentials for the parent
                        </CardDescription>
                    </CardHeader>
                    <CardContent>
                        <div class="space-y-4">
                            <div class="p-4 bg-purple-50 dark:bg-purple-900/20 border border-purple-200 dark:border-purple-800 rounded-lg">
                                <div class="flex items-start gap-3">
                                    <AlertCircle class="w-5 h-5 text-purple-600 dark:text-purple-400 mt-0.5" />
                                    <div class="flex-1">
                                        <h4 class="font-medium text-purple-900 dark:text-purple-200 mb-1">
                                            Password Setup
                                        </h4>
                                        <p class="text-sm text-purple-700 dark:text-purple-300">
                                            Create a secure password for the parent account. The parent will use this password along with their email to log in.
                                        </p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="space-y-2 max-w-md">
                                <Label for="password" class="flex items-center gap-2">
                                    <Lock class="w-4 h-4 text-gray-500" />
                                    Password *
                                </Label>
                                <Input
                                    id="password"
                                    v-model="form.password"
                                    type="password"
                                    placeholder="Enter secure password"
                                    required
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
                                <Link :href="route('parents.index')">
                                    <Button variant="outline" type="button">
                                        <X class="w-4 h-4 mr-2" />
                                        Cancel
                                    </Button>
                                </Link>
                                <Button type="submit" :disabled="form.processing" size="lg" class="gap-2">
                                    <Save class="w-4 h-4" />
                                    {{ form.processing ? 'Creating...' : 'Create Parent' }}
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