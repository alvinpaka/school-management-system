<script setup>
import { Head, useForm, Link } from '@inertiajs/vue3';
import { ref } from 'vue';
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
    GraduationCap,
    Calendar,
    Camera,
    Upload,
    X,
    Phone,
    MapPin,
    Users,
    AlertCircle,
    Sparkles,
    RefreshCw
} from 'lucide-vue-next';

const props = defineProps({
    classes: Array,
    sections: Array
});

const form = useForm({
    name: '',
    email: '',
    password: '',
    academic_class_id: '',
    section_id: '',
    admission_number: '', // Will be auto-generated
    roll_number: '', // Will be auto-generated
    date_of_birth: '',
    gender: '',
    phone: '',
    address: '',
    admission_date: new Date().toISOString().split('T')[0],
    status: 'active',
    photo: null,
    parent_name: '',
    parent_email: '',
    parent_phone: '',
    emergency_contact: '',
    blood_group: '',
    medical_conditions: '',
    previous_school: '',
    transfer_certificate: '',
});

// Generate admission number automatically
const generateAdmissionNumber = () => {
    const year = new Date().getFullYear();
    const random = Math.floor(Math.random() * 10000);
    return `STD${year}${random.toString().padStart(4, '0')}`;
};

// Generate roll number automatically
const generateRollNumber = () => {
    const year = new Date().getFullYear();
    const random = Math.floor(Math.random() * 10000);
    return `ROLL${year}${random.toString().padStart(4, '0')}`;
};

// Set generated numbers when component mounts
form.admission_number = generateAdmissionNumber();
form.roll_number = generateRollNumber();

const submit = () => {
    form.post(route('students.store'));
};

// Handle photo upload and preview
const photoPreview = ref(null);
const photoFile = ref(null);

const handlePhotoChange = (event) => {
    const file = event.target.files[0];
    if (file) {
        // Create preview URL
        photoPreview.value = URL.createObjectURL(file);
        photoFile.value = file;
        // Store file in form data
        form.photo = file;
    }
};

const removePhoto = () => {
    photoPreview.value = null;
    photoFile.value = null;
    form.photo = null;
    // Reset file input
    const fileInput = document.getElementById('photo');
    if (fileInput) {
        fileInput.value = '';
    }
};

const getInitials = (name) => {
    if (!name) return 'ST';
    return name
        .split(' ')
        .map(word => word[0])
        .join('')
        .toUpperCase()
        .slice(0, 2);
};

const regenerateAdmissionNumber = () => {
    form.admission_number = generateAdmissionNumber();
    form.roll_number = generateRollNumber();
};
</script>

<template>
    <Head title="Add Student" />

    <Sidebar>
        <template #header-title>
            <div class="flex items-center space-x-3">
                <Link :href="route('students.index')">
                    <Button variant="ghost" size="sm" class="gap-2">
                        <ArrowLeft class="w-4 h-4" />
                        Students
                    </Button>
                </Link>
                <Separator orientation="vertical" class="h-6" />
                <span class="font-semibold">Add New Student</span>
            </div>
        </template>

        <div class="mx-auto max-w-7xl space-y-6">
            <!-- Header Card -->
            <Card class="overflow-hidden">
                <div class="h-24 bg-blue-500"></div>
                <CardContent class="relative pt-0 pb-6">
                    <div class="flex flex-col md:flex-row md:items-end md:justify-between -mt-12">
                        <div class="flex flex-col md:flex-row items-center md:items-end space-y-4 md:space-y-0 md:space-x-6">
                            <Avatar class="w-24 h-24 md:w-32 md:h-32 border-4 border-white dark:border-gray-950 shadow-xl">
                                <AvatarImage 
                                    v-if="photoPreview"
                                    :src="photoPreview" 
                                    alt="Student preview"
                                />
                                <AvatarFallback class="text-2xl md:text-3xl font-bold bg-blue-500 text-white">
                                    {{ getInitials(form.name) }}
                                </AvatarFallback>
                            </Avatar>
                            
                            <div class="text-center md:text-left space-y-2 mb-2">
                                <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white">
                                    {{ form.name || 'New Student' }}
                                </h1>
                                <div class="flex flex-wrap items-center justify-center md:justify-start gap-2">
                                    <Badge variant="outline" class="gap-1.5">
                                        <Sparkles class="w-3 h-3" />
                                        Creating Profile
                                    </Badge>
                                    <Badge v-if="form.admission_number" variant="outline" class="text-xs">
                                        {{ form.admission_number }}
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
                            Upload a profile photo for the student (JPG, PNG - Max 2MB)
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
                                            alt="Student preview"
                                        />
                                        <AvatarFallback class="text-4xl font-bold bg-blue-500 text-white">
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
                                <div class="border-2 border-dashed border-gray-300 dark:border-gray-700 rounded-lg p-6 text-center hover:border-blue-500 dark:hover:border-blue-500 transition-colors">
                                    <Upload class="w-12 h-12 mx-auto text-gray-400 mb-3" />
                                    <div class="space-y-2">
                                        <Label 
                                            for="photo" 
                                            class="cursor-pointer text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300 font-medium"
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
                            Enter the student's basic personal details
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
                                    placeholder="student@example.com"
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
                                    Phone Number
                                </Label>
                                <Input
                                    id="phone"
                                    v-model="form.phone"
                                    type="tel"
                                    placeholder="+256 700 000 000"
                                />
                                <div v-if="form.errors.phone" class="flex items-center gap-2 text-red-600 text-sm">
                                    <AlertCircle class="w-4 h-4" />
                                    {{ form.errors.phone }}
                                </div>
                            </div>

                            <div class="space-y-2">
                                <Label for="date_of_birth" class="flex items-center gap-2">
                                    <Calendar class="w-4 h-4 text-gray-500" />
                                    Date of Birth
                                </Label>
                                <Input
                                    id="date_of_birth"
                                    v-model="form.date_of_birth"
                                    type="date"
                                />
                                <div v-if="form.errors.date_of_birth" class="flex items-center gap-2 text-red-600 text-sm">
                                    <AlertCircle class="w-4 h-4" />
                                    {{ form.errors.date_of_birth }}
                                </div>
                            </div>

                            <div class="space-y-2">
                                <Label for="gender" class="flex items-center gap-2">
                                    <User class="w-4 h-4 text-gray-500" />
                                    Gender
                                </Label>
                                <select
                                    id="gender"
                                    v-model="form.gender"
                                    class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
                                >
                                    <option value="">Select gender</option>
                                    <option value="male">Male</option>
                                    <option value="female">Female</option>
                                    <option value="other">Other</option>
                                </select>
                                <div v-if="form.errors.gender" class="flex items-center gap-2 text-red-600 text-sm">
                                    <AlertCircle class="w-4 h-4" />
                                    {{ form.errors.gender }}
                                </div>
                            </div>

                            <div class="space-y-2 md:col-span-2">
                                <Label for="address" class="flex items-center gap-2">
                                    <MapPin class="w-4 h-4 text-gray-500" />
                                    Address
                                </Label>
                                <Input
                                    id="address"
                                    v-model="form.address"
                                    type="text"
                                    placeholder="Enter full address"
                                />
                                <div v-if="form.errors.address" class="flex items-center gap-2 text-red-600 text-sm">
                                    <AlertCircle class="w-4 h-4" />
                                    {{ form.errors.address }}
                                </div>
                            </div>
                        </div>
                    </CardContent>
                </Card>

                <!-- Academic Information -->
                <Card>
                    <CardHeader>
                        <CardTitle class="flex items-center gap-2">
                            <GraduationCap class="w-5 h-5" />
                            Academic Information
                        </CardTitle>
                        <CardDescription>
                            Set up the student's academic details and enrollment information
                        </CardDescription>
                    </CardHeader>
                    <CardContent>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <Label for="admission_number" class="flex items-center gap-2">
                                    <GraduationCap class="w-4 h-4 text-gray-500" />
                                    Admission Number *
                                </Label>
                                <div class="flex gap-2">
                                    <Input
                                        id="admission_number"
                                        v-model="form.admission_number"
                                        type="text"
                                        placeholder="e.g., STD2024001"
                                        required
                                        readonly
                                        class="flex-1 bg-gray-50 dark:bg-gray-800 cursor-not-allowed"
                                    />
                                    <Button 
                                        type="button" 
                                        variant="outline" 
                                        size="icon"
                                        @click="regenerateAdmissionNumber"
                                        title="Generate new admission and roll numbers"
                                    >
                                        <RefreshCw class="w-4 h-4" />
                                    </Button>
                                </div>
                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                    Auto-generated. Click refresh to generate a new one.
                                </p>
                                <div v-if="form.errors.admission_number" class="flex items-center gap-2 text-red-600 text-sm">
                                    <AlertCircle class="w-4 h-4" />
                                    {{ form.errors.admission_number }}
                                </div>
                            </div>

                            <div class="space-y-2">
                                <Label for="roll_number" class="flex items-center gap-2">
                                    <GraduationCap class="w-4 h-4 text-gray-500" />
                                    Roll Number
                                </Label>
                                <Input
                                    id="roll_number"
                                    v-model="form.roll_number"
                                    type="text"
                                    placeholder="e.g., ROLL20260001"
                                    readonly
                                    class="bg-gray-50 dark:bg-gray-800 cursor-not-allowed"
                                />
                                <div v-if="form.errors.roll_number" class="flex items-center gap-2 text-red-600 text-sm">
                                    <AlertCircle class="w-4 h-4" />
                                    {{ form.errors.roll_number }}
                                </div>
                            </div>

                            <div class="space-y-2">
                                <Label for="academic_class_id" class="flex items-center gap-2">
                                    <GraduationCap class="w-4 h-4 text-gray-500" />
                                    Class *
                                </Label>
                                <select
                                    id="academic_class_id"
                                    v-model="form.academic_class_id"
                                    class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
                                    required
                                >
                                    <option value="">Select a class</option>
                                    <option v-for="classItem in classes" :key="classItem.id" :value="classItem.id">
                                        {{ classItem.name }}
                                    </option>
                                </select>
                                <div v-if="form.errors.academic_class_id" class="flex items-center gap-2 text-red-600 text-sm">
                                    <AlertCircle class="w-4 h-4" />
                                    {{ form.errors.academic_class_id }}
                                </div>
                            </div>

                            <div class="space-y-2">
                                <Label for="section_id" class="flex items-center gap-2">
                                    <GraduationCap class="w-4 h-4 text-gray-500" />
                                    Section *
                                </Label>
                                <select
                                    id="section_id"
                                    v-model="form.section_id"
                                    class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
                                    required
                                >
                                    <option value="">Select a section</option>
                                    <option v-for="section in sections" :key="section.id" :value="section.id">
                                        {{ section.name }}
                                    </option>
                                </select>
                                <div v-if="form.errors.section_id" class="flex items-center gap-2 text-red-600 text-sm">
                                    <AlertCircle class="w-4 h-4" />
                                    {{ form.errors.section_id }}
                                </div>
                            </div>

                            <div class="space-y-2">
                                <Label for="admission_date" class="flex items-center gap-2">
                                    <Calendar class="w-4 h-4 text-gray-500" />
                                    Admission Date
                                </Label>
                                <Input
                                    id="admission_date"
                                    v-model="form.admission_date"
                                    type="date"
                                />
                                <div v-if="form.errors.admission_date" class="flex items-center gap-2 text-red-600 text-sm">
                                    <AlertCircle class="w-4 h-4" />
                                    {{ form.errors.admission_date }}
                                </div>
                            </div>

                            <div class="space-y-2">
                                <Label for="status" class="flex items-center gap-2">
                                    <GraduationCap class="w-4 h-4 text-gray-500" />
                                    Status
                                </Label>
                                <select
                                    id="status"
                                    v-model="form.status"
                                    class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
                                >
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                    <option value="graduated">Graduated</option>
                                    <option value="transferred">Transferred</option>
                                </select>
                                <div v-if="form.errors.status" class="flex items-center gap-2 text-red-600 text-sm">
                                    <AlertCircle class="w-4 h-4" />
                                    {{ form.errors.status }}
                                </div>
                            </div>
                        </div>
                    </CardContent>
                </Card>

                <!-- Parent/Guardian Information - HIDDEN -->
                <!-- <Card>
                    <CardHeader>
                        <CardTitle class="flex items-center gap-2">
                            <Users class="w-5 h-5" />
                            Parent/Guardian Information
                        </CardTitle>
                        <CardDescription>
                            Enter parent or guardian contact details
                        </CardDescription>
                    </CardHeader>
                    <CardContent>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <Label for="parent_name" class="flex items-center gap-2">
                                    <User class="w-4 h-4 text-gray-500" />
                                    Parent/Guardian Name
                                </Label>
                                <Input
                                    id="parent_name"
                                    v-model="form.parent_name"
                                    type="text"
                                    placeholder="Enter parent name"
                                />
                                <div v-if="form.errors.parent_name" class="flex items-center gap-2 text-red-600 text-sm">
                                    <AlertCircle class="w-4 h-4" />
                                    {{ form.errors.parent_name }}
                                </div>
                            </div>

                            <div class="space-y-2">
                                <Label for="parent_email" class="flex items-center gap-2">
                                    <Mail class="w-4 h-4 text-gray-500" />
                                    Parent Email
                                </Label>
                                <Input
                                    id="parent_email"
                                    v-model="form.parent_email"
                                    type="email"
                                    placeholder="parent@example.com"
                                />
                                <div v-if="form.errors.parent_email" class="flex items-center gap-2 text-red-600 text-sm">
                                    <AlertCircle class="w-4 h-4" />
                                    {{ form.errors.parent_email }}
                                </div>
                            </div>

                            <div class="space-y-2">
                                <Label for="parent_phone" class="flex items-center gap-2">
                                    <Phone class="w-4 h-4 text-gray-500" />
                                    Parent Phone
                                </Label>
                                <Input
                                    id="parent_phone"
                                    v-model="form.parent_phone"
                                    type="tel"
                                    placeholder="+256 700 000 000"
                                />
                                <div v-if="form.errors.parent_phone" class="flex items-center gap-2 text-red-600 text-sm">
                                    <AlertCircle class="w-4 h-4" />
                                    {{ form.errors.parent_phone }}
                                </div>
                            </div>
                        </div>
                    </CardContent>
                </Card> -->

                <!-- Medical Information -->
                <Card>
                    <CardHeader>
                        <CardTitle class="flex items-center gap-2">
                            <Users class="w-5 h-5" />
                            Medical Information
                        </CardTitle>
                        <CardDescription>
                            Add medical details and emergency contact information
                        </CardDescription>
                    </CardHeader>
                    <CardContent>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <Label for="emergency_contact" class="flex items-center gap-2">
                                    <Phone class="w-4 h-4 text-gray-500" />
                                    Emergency Contact
                                </Label>
                                <Input
                                    id="emergency_contact"
                                    v-model="form.emergency_contact"
                                    type="tel"
                                    placeholder="+256 700 000 000"
                                />
                                <div v-if="form.errors.emergency_contact" class="flex items-center gap-2 text-red-600 text-sm">
                                    <AlertCircle class="w-4 h-4" />
                                    {{ form.errors.emergency_contact }}
                                </div>
                            </div>

                            <div class="space-y-2">
                                <Label for="blood_group" class="flex items-center gap-2">
                                    <Users class="w-4 h-4 text-gray-500" />
                                    Blood Group
                                </Label>
                                <select
                                    id="blood_group"
                                    v-model="form.blood_group"
                                    class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
                                >
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
                                <div v-if="form.errors.blood_group" class="flex items-center gap-2 text-red-600 text-sm">
                                    <AlertCircle class="w-4 h-4" />
                                    {{ form.errors.blood_group }}
                                </div>
                            </div>

                            <div class="space-y-2 md:col-span-2">
                                <Label for="medical_conditions" class="flex items-center gap-2">
                                    <Users class="w-4 h-4 text-gray-500" />
                                    Medical Conditions
                                </Label>
                                <textarea
                                    id="medical_conditions"
                                    v-model="form.medical_conditions"
                                    placeholder="Enter any known medical conditions, allergies, or special health requirements"
                                    class="flex min-h-[80px] w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
                                ></textarea>
                                <div v-if="form.errors.medical_conditions" class="flex items-center gap-2 text-red-600 text-sm">
                                    <AlertCircle class="w-4 h-4" />
                                    {{ form.errors.medical_conditions }}
                                </div>
                            </div>
                        </div>
                    </CardContent>
                </Card>

                <!-- Academic History -->
                <Card>
                    <CardHeader>
                        <CardTitle class="flex items-center gap-2">
                            <GraduationCap class="w-5 h-5" />
                            Academic History
                        </CardTitle>
                        <CardDescription>
                            Previous educational background and transfer information
                        </CardDescription>
                    </CardHeader>
                    <CardContent>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <Label for="previous_school" class="flex items-center gap-2">
                                    <GraduationCap class="w-4 h-4 text-gray-500" />
                                    Previous School
                                </Label>
                                <Input
                                    id="previous_school"
                                    v-model="form.previous_school"
                                    type="text"
                                    placeholder="Enter previous school name"
                                />
                                <div v-if="form.errors.previous_school" class="flex items-center gap-2 text-red-600 text-sm">
                                    <AlertCircle class="w-4 h-4" />
                                    {{ form.errors.previous_school }}
                                </div>
                            </div>

                            <div class="space-y-2">
                                <Label for="transfer_certificate" class="flex items-center gap-2">
                                    <GraduationCap class="w-4 h-4 text-gray-500" />
                                    Transfer Certificate
                                </Label>
                                <Input
                                    id="transfer_certificate"
                                    v-model="form.transfer_certificate"
                                    type="text"
                                    placeholder="Transfer certificate number or status"
                                />
                                <div v-if="form.errors.transfer_certificate" class="flex items-center gap-2 text-red-600 text-sm">
                                    <AlertCircle class="w-4 h-4" />
                                    {{ form.errors.transfer_certificate }}
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
                            Set up login credentials for the student
                        </CardDescription>
                    </CardHeader>
                    <CardContent>
                        <div class="space-y-4">
                            <div class="p-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg">
                                <div class="flex items-start gap-3">
                                    <AlertCircle class="w-5 h-5 text-blue-600 dark:text-blue-400 mt-0.5" />
                                    <div class="flex-1">
                                        <h4 class="font-medium text-blue-900 dark:text-blue-200 mb-1">
                                            Password Setup
                                        </h4>
                                        <p class="text-sm text-blue-700 dark:text-blue-300">
                                            Create a secure password for the student account. The student will use this password along with their email to log in.
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
                                <Link :href="route('students.index')">
                                    <Button variant="outline" type="button">
                                        <X class="w-4 h-4 mr-2" />
                                        Cancel
                                    </Button>
                                </Link>
                                <Button type="submit" :disabled="form.processing" size="lg" class="gap-2">
                                    <Save class="w-4 h-4" />
                                    {{ form.processing ? 'Creating...' : 'Create Student' }}
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