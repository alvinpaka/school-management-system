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
    Phone,
    Briefcase,
    Camera,
    Upload,
    X,
    Calendar,
    MapPin,
    AlertCircle,
    Sparkles,
    RefreshCw,
    GraduationCap,
    Award,
    UserCircle
} from 'lucide-vue-next';

const form = useForm({
    name: '',
    email: '',
    password: '',
    employee_id: '',
    phone: '',
    employment_type: 'Full-time',
    role: 'teacher', // New field for role selection
    status: 'Active',
    specialization: '',
    qualification: '',
    experience: '',
    date_of_birth: '',
    gender: '',
    address: '',
    joining_date: new Date().toISOString().split('T')[0],
    photo: null,
    emergency_contact_name: '',
    emergency_contact_phone: '',
    emergency_contact_relationship: '',
    blood_group: '',
});

// Generate employee ID automatically
const generateEmployeeID = () => {
    const year = new Date().getFullYear();
    const random = Math.floor(Math.random() * 10000);
    return `EMP${year}${random.toString().padStart(4, '0')}`;
};

// Set generated employee ID when component mounts
form.employee_id = generateEmployeeID();

const submit = () => {
    form.post(route('teachers.store'), {
        forceFormData: true
    });
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
    if (!name) return 'TC';
    return name
        .split(' ')
        .map(word => word[0])
        .join('')
        .toUpperCase()
        .slice(0, 2);
};

const regenerateEmployeeID = () => {
    form.employee_id = generateEmployeeID();
};
</script>

<template>
    <Head title="Add Staff Member" />

    <Sidebar>
        <template #header-title>
            <div class="flex items-center space-x-3">
                <Link :href="route('teachers.index')">
                    <Button variant="ghost" size="sm" class="gap-2">
                        <ArrowLeft class="w-4 h-4" />
                        Teachers
                    </Button>
                </Link>
                <Separator orientation="vertical" class="h-6" />
                <span class="font-semibold">Add New Staff Member</span>
            </div>
        </template>

        <div class="mx-auto max-w-7xl space-y-6">
            <!-- Header Card -->
            <Card class="overflow-hidden">
                <div class="h-24 bg-emerald-500 "></div>
                <CardContent class="relative pt-0 pb-6">
                    <div class="flex flex-col md:flex-row md:items-end md:justify-between -mt-12">
                        <div class="flex flex-col md:flex-row items-center md:items-end space-y-4 md:space-y-0 md:space-x-6">
                            <Avatar class="w-24 h-24 md:w-32 md:h-32 border-4 border-white dark:border-gray-950 shadow-xl">
                                <AvatarImage 
                                    v-if="photoPreview"
                                    :src="photoPreview" 
                                    alt="Staff member preview"
                                />
                                <AvatarFallback class="text-2xl md:text-3xl font-bold bg-emerald-500 text-white">
                                    {{ getInitials(form.name) }}
                                </AvatarFallback>
                            </Avatar>
                            
                            <div class="text-center md:text-left space-y-2 mb-2">
                                <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white">
                                    {{ form.name || 'New Staff Member' }}
                                </h1>
                                <div class="flex flex-wrap items-center justify-center md:justify-start gap-2">
                                    <Badge variant="outline" class="gap-1.5">
                                        <Sparkles class="w-3 h-3" />
                                        Creating Profile
                                    </Badge>
                                    <Badge v-if="form.employee_id" variant="outline" class="text-xs">
                                        {{ form.employee_id }}
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
                            Upload a profile photo for staff member (JPG, PNG - Max 2MB)
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
                                            alt="Staff member preview"
                                        />
                                        <AvatarFallback class="text-4xl font-bold bg-emerald-500 text-white">
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
                                <div class="border-2 border-dashed border-gray-300 dark:border-gray-700 rounded-lg p-6 text-center hover:border-emerald-500 dark:hover:border-emerald-500 transition-colors">
                                    <Upload class="w-12 h-12 mx-auto text-gray-400 mb-3" />
                                    <div class="space-y-2">
                                        <Label 
                                            for="photo" 
                                            class="cursor-pointer text-emerald-600 hover:text-emerald-700 dark:text-emerald-400 dark:hover:text-emerald-300 font-medium"
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
                            Enter staff member's basic personal details
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
                                    placeholder="staff@example.com"
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
                                    placeholder="e.g., +256752919613"
                                />
                                <div v-if="form.errors.phone" class="flex items-center gap-2 text-red-600 text-sm">
                                    <AlertCircle class="w-4 h-4" />
                                    {{ form.errors.phone }}
                                </div>
                            </div>

                            <div class="space-y-2">
                                <Label for="employment_type" class="flex items-center gap-2">
                                    <Briefcase class="w-4 h-4 text-gray-500" />
                                    Employment Type
                                </Label>
                                <select
                                    id="employment_type"
                                    v-model="form.employment_type"
                                    class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
                                >
                                    <option value="Full-time">Full-time</option>
                                    <option value="Part-time">Part-time</option>
                                    <option value="Contract">Contract</option>
                                    <option value="Temporary">Temporary</option>
                                    <option value="Intern">Intern</option>
                                </select>
                                <div v-if="form.errors.employment_type" class="flex items-center gap-2 text-red-600 text-sm">
                                    <AlertCircle class="w-4 h-4" />
                                    {{ form.errors.employment_type }}
                                </div>
                            </div>

                            <div class="space-y-2">
                                <Label for="role" class="flex items-center gap-2">
                                    <UserCircle class="w-4 h-4 text-gray-500" />
                                    Staff Role
                                </Label>
                                <select
                                    id="role"
                                    v-model="form.role"
                                    class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
                                >
                                    <option value="">Select Staff Role</option>
                                    <option value="teacher">Teacher</option>
                                    <option value="librarian">Librarian</option>
                                    <option value="accountant">Accountant</option>
                                    <option value="receptionist">Receptionist</option>
                                </select>
                                <div v-if="form.errors.role" class="flex items-center gap-2 text-red-600 text-sm">
                                    <AlertCircle class="w-4 h-4" />
                                    {{ form.errors.role }}
                                </div>
                            </div>

                            <div class="space-y-2">
                                <Label for="status" class="flex items-center gap-2">
                                    <UserCircle class="w-4 h-4 text-gray-500" />
                                    Status
                                </Label>
                                <select
                                    id="status"
                                    v-model="form.status"
                                    class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
                                >
                                    <option value="Active">Active</option>
                                    <option value="Inactive">Inactive</option>
                                    <option value="Suspended">Suspended</option>
                                    <option value="On Leave">On Leave</option>
                                </select>
                                <div v-if="form.errors.status" class="flex items-center gap-2 text-red-600 text-sm">
                                    <AlertCircle class="w-4 h-4" />
                                    {{ form.errors.status }}
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
                                    <UserCircle class="w-4 h-4 text-gray-500" />
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

                <!-- Professional Information -->
                <Card>
                    <CardHeader>
                        <CardTitle class="flex items-center gap-2">
                            <Briefcase class="w-5 h-5" />
                            Professional Information
                        </CardTitle>
                        <CardDescription>
                            Set up staff member's professional details and qualifications
                        </CardDescription>
                    </CardHeader>
                    <CardContent>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <Label class="flex items-center gap-2">
                                    <Briefcase class="w-4 h-4 text-gray-500" />
                                    Employee ID
                                </Label>
                            
                                <div class="px-4 py-2 rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-sm font-medium">
                                    {{ form.employee_id }}
                                </div>
                            
                                <p class="text-xs text-muted-foreground">
                                    This ID is system generated and cannot be modified.
                                </p>
                            </div>

                            <div class="space-y-2">
                                <Label for="specialization" class="flex items-center gap-2">
                                    <GraduationCap class="w-4 h-4 text-gray-500" />
                                    Specialization
                                </Label>
                                <select
                                    id="specialization"
                                    v-model="form.specialization"
                                    class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
                                >
                                    <option value="">Select specialization</option>
                                    <optgroup label="📘 O-Level Core Subjects">
                                        <option value="English">English</option>
                                        <option value="Mathematics">Mathematics</option>
                                        <option value="Biology">Biology</option>
                                        <option value="Chemistry">Chemistry</option>
                                        <option value="Physics">Physics</option>
                                        <option value="History & Political Education">History & Political Education</option>
                                        <option value="Geography">Geography</option>
                                    </optgroup>
                                    <optgroup label="🎨 O-Level Electives">
                                        <option value="Kiswahili">Kiswahili</option>
                                        <option value="French">French</option>
                                        <option value="German">German</option>
                                        <option value="Arabic">Arabic</option>
                                        <option value="Literature in English">Literature in English</option>
                                        <option value="Fine Art">Fine Art</option>
                                        <option value="Performing Arts">Performing Arts</option>
                                        <option value="Computer Studies (ICT)">Computer Studies (ICT)</option>
                                        <option value="Agriculture">Agriculture</option>
                                        <option value="Entrepreneurship">Entrepreneurship</option>
                                        <option value="Nutrition & Food Technology">Nutrition & Food Technology</option>
                                        <option value="Local Languages">Local Languages</option>
                                    </optgroup>
                                    <optgroup label="📚 A-Level Subjects">
                                        <option value="Biology">Biology</option>
                                        <option value="Chemistry">Chemistry</option>
                                        <option value="Physics">Physics</option>
                                        <option value="Mathematics">Mathematics</option>
                                        <option value="Economics">Economics</option>
                                        <option value="History">History</option>
                                        <option value="Geography">Geography</option>
                                        <option value="Literature in English">Literature in English</option>
                                        <option value="Entrepreneurship">Entrepreneurship</option>
                                        <option value="Subsidiary Maths">Subsidiary Maths</option>
                                        <option value="Subsidiary ICT">Subsidiary ICT</option>
                                        <option value="General Paper">General Paper</option>
                                    </optgroup>
                                    <option value="Other">Other</option>
                                </select>
                                <div v-if="form.errors.specialization" class="flex items-center gap-2 text-red-600 text-sm">
                                    <AlertCircle class="w-4 h-4" />
                                    {{ form.errors.specialization }}
                                </div>
                            </div>

                            <div class="space-y-2">
                                <Label for="qualification" class="flex items-center gap-2">
                                    <Award class="w-4 h-4 text-gray-500" />
                                    Qualification
                                </Label>
                                <select
                                    id="qualification"
                                    v-model="form.qualification"
                                    class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
                                >
                                    <option value="">Select qualification</option>
                                    <option value="High School Diploma">High School Diploma</option>
                                    <option value="Associate Degree">Associate Degree</option>
                                    <option value="Bachelor's Degree">Bachelor's Degree</option>
                                    <option value="Master's Degree">Master's Degree</option>
                                    <option value="PhD">PhD</option>
                                    <option value="Postgraduate Diploma">Postgraduate Diploma</option>
                                    <option value="Teaching Certificate">Teaching Certificate</option>
                                    <option value="Diploma in Education">Diploma in Education</option>
                                    <option value="B.Ed">B.Ed</option>
                                    <option value="M.Ed">M.Ed</option>
                                    <option value="Other">Other</option>
                                </select>
                                <div v-if="form.errors.qualification" class="flex items-center gap-2 text-red-600 text-sm">
                                    <AlertCircle class="w-4 h-4" />
                                    {{ form.errors.qualification }}
                                </div>
                            </div>

                            <div class="space-y-2">
                                <Label for="experience" class="flex items-center gap-2">
                                    <Award class="w-4 h-4 text-gray-500" />
                                    Experience
                                </Label>
                                <select
                                    id="experience"
                                    v-model="form.experience"
                                    class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
                                >
                                    <option value="">Select experience</option>
                                    <option value="Less than 1 year">Less than 1 year</option>
                                    <option value="1 year">1 year</option>
                                    <option value="2 years">2 years</option>
                                    <option value="3 years">3 years</option>
                                    <option value="4 years">4 years</option>
                                    <option value="5 years">5 years</option>
                                    <option value="6 years">6 years</option>
                                    <option value="7 years">7 years</option>
                                    <option value="8 years">8 years</option>
                                    <option value="9 years">9 years</option>
                                    <option value="10 years">10 years</option>
                                    <option value="11-15 years">11-15 years</option>
                                    <option value="16-20 years">16-20 years</option>
                                    <option value="More than 20 years">More than 20 years</option>
                                </select>
                                <div v-if="form.errors.experience" class="flex items-center gap-2 text-red-600 text-sm">
                                    <AlertCircle class="w-4 h-4" />
                                    {{ form.errors.experience }}
                                </div>
                            </div>

                            <div class="space-y-2">
                                <Label for="joining_date" class="flex items-center gap-2">
                                    <Calendar class="w-4 h-4 text-gray-500" />
                                    Joining Date
                                </Label>
                                <Input
                                    id="joining_date"
                                    v-model="form.joining_date"
                                    type="date"
                                />
                                <div v-if="form.errors.joining_date" class="flex items-center gap-2 text-red-600 text-sm">
                                    <AlertCircle class="w-4 h-4" />
                                    {{ form.errors.joining_date }}
                                </div>
                            </div>
                        </div>
                    </CardContent>
                </Card>

                <!-- Emergency Contact -->
                <Card>
                    <CardHeader>
                        <CardTitle class="flex items-center gap-2">
                            <Phone class="w-5 h-5" />
                            Emergency Contact
                        </CardTitle>
                        <CardDescription>
                            Emergency contact information for staff member
                        </CardDescription>
                    </CardHeader>
                    <CardContent>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <Label for="emergency_contact_name" class="flex items-center gap-2">
                                    <User class="w-4 h-4 text-gray-500" />
                                    Contact Name
                                </Label>
                                <Input
                                    id="emergency_contact_name"
                                    v-model="form.emergency_contact_name"
                                    type="text"
                                    placeholder="Enter emergency contact name"
                                />
                                <div v-if="form.errors.emergency_contact_name" class="flex items-center gap-2 text-red-600 text-sm">
                                    <AlertCircle class="w-4 h-4" />
                                    {{ form.errors.emergency_contact_name }}
                                </div>
                            </div>

                            <div class="space-y-2">
                                <Label for="emergency_contact_phone" class="flex items-center gap-2">
                                    <Phone class="w-4 h-4 text-gray-500" />
                                    Phone Number
                                </Label>
                                <Input
                                    id="emergency_contact_phone"
                                    v-model="form.emergency_contact_phone"
                                    type="tel"
                                    placeholder="+256 700 000 000"
                                />
                                <div v-if="form.errors.emergency_contact_phone" class="flex items-center gap-2 text-red-600 text-sm">
                                    <AlertCircle class="w-4 h-4" />
                                    {{ form.errors.emergency_contact_phone }}
                                </div>
                            </div>

                            <div class="space-y-2">
                                <Label for="emergency_contact_relationship" class="flex items-center gap-2">
                                    <UserCircle class="w-4 h-4 text-gray-500" />
                                    Relationship
                                </Label>
                                <select
                                    id="emergency_contact_relationship"
                                    v-model="form.emergency_contact_relationship"
                                    class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
                                >
                                    <option value="">Select relationship</option>
                                    <option value="spouse">Spouse</option>
                                    <option value="parent">Parent</option>
                                    <option value="sibling">Sibling</option>
                                    <option value="relative">Relative</option>
                                    <option value="friend">Friend</option>
                                    <option value="other">Other</option>
                                </select>
                                <div v-if="form.errors.emergency_contact_relationship" class="flex items-center gap-2 text-red-600 text-sm">
                                    <AlertCircle class="w-4 h-4" />
                                    {{ form.errors.emergency_contact_relationship }}
                                </div>
                            </div>

                            <div class="space-y-2">
                                <Label for="blood_group" class="flex items-center gap-2">
                                    <AlertCircle class="w-4 h-4 text-gray-500" />
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
                                    <option value="AB+">AB+</option>
                                    <option value="AB-">AB-</option>
                                    <option value="O+">O+</option>
                                    <option value="O-">O-</option>
                                </select>
                                <div v-if="form.errors.blood_group" class="flex items-center gap-2 text-red-600 text-sm">
                                    <AlertCircle class="w-4 h-4" />
                                    {{ form.errors.blood_group }}
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
                            Set up login credentials for staff member
                        </CardDescription>
                    </CardHeader>
                    <CardContent>
                        <div class="space-y-4">
                            <div class="p-4 bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800 rounded-lg">
                                <div class="flex items-start gap-3">
                                    <AlertCircle class="w-5 h-5 text-emerald-600 dark:text-emerald-400 mt-0.5" />
                                    <div class="flex-1">
                                        <h4 class="font-medium text-emerald-900 dark:text-emerald-200 mb-1">
                                            Password Setup
                                        </h4>
                                        <p class="text-sm text-emerald-700 dark:text-emerald-300">
                                            Create a secure password for staff member account. The staff member will use this password along with their email to log in.
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
                                <Link :href="route('teachers.index')">
                                    <Button variant="outline" type="button">
                                        <X class="w-4 h-4 mr-2" />
                                        Cancel
                                    </Button>
                                </Link>
                                <Button type="submit" :disabled="form.processing" size="lg" class="gap-2">
                                    <Save class="w-4 h-4" />
                                    {{ form.processing ? 'Creating...' : 'Create Staff Member' }}
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