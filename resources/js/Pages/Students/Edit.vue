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
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import { 
    ArrowLeft,
    Save,
    User,
    Mail,
    GraduationCap,
    Calendar,
    MapPin,
    Camera,
    Upload,
    X,
    Phone,
    Users,
    Lock,
    AlertCircle
} from 'lucide-vue-next';

const props = defineProps({
    student: Object,
    classes: Array,
    sections: Array
});

const form = useForm({
    name: props.student.user.name || '',
    email: props.student.user.email || '',
    password: '',
    academic_class_id: props.student.academic_class_id || '',
    section_id: props.student.section_id || '',
    admission_number: props.student.admission_number || '',
    roll_number: props.student.roll_number || '',
    date_of_birth: props.student.date_of_birth || '',
    gender: props.student.gender ? props.student.gender.toLowerCase() : '',
    phone: props.student.phone || '',
    address: props.student.address || '',
    admission_date: props.student.admission_date || '',
    status: props.student.status ? props.student.status.toLowerCase() : 'active',
    photo: null,
    parent_name: props.student.parent_user?.user?.name || props.student.parent_name || '',
    parent_email: props.student.parent_user?.user?.email || props.student.parent_email || '',
    parent_phone: props.student.parent_user?.phone || props.student.parent_phone || '',
    emergency_contact: props.student.emergency_contact || '',
    blood_group: props.student.blood_group || '',
    medical_conditions: props.student.medical_conditions || '',
    previous_school: props.student.previous_school || '',
    transfer_certificate: props.student.transfer_certificate || '',
});

const submit = () => {
    const formData = new FormData();
    
    Object.keys(form.data()).forEach(key => {
        const value = form.data()[key];
        if (key === 'photo' && value instanceof File) {
            formData.append(key, value);
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
    
    axios.post(route('students.update', props.student.id), formData, {
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
    if (!name || typeof name !== 'string') return 'ST';
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
    if (props.student.user?.photo) {
        return `/storage/${props.student.user.photo}`;
    }
    return null;
};
</script>

<template>
    <Head title="Edit Student | EduManage Pro" />

    <Sidebar>
        <template #header-title>
            <div class="flex items-center space-x-3">
                <Link :href="route('students.show', student.id)">
                    <Button variant="ghost" size="sm" class="gap-2 hover:bg-terracotta/10 text-warm-text dark:text-dark-text">
                        <ArrowLeft class="w-4 h-4" />
                        Back to Profile
                    </Button>
                </Link>
                <Separator orientation="vertical" class="h-6 bg-terracotta/20" />
                <span class="font-semibold text-warm-text dark:text-dark-text">Edit Student</span>
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
                                    {{ form.name }}
                                </h1>
                                <div class="flex flex-wrap items-center justify-center md:justify-start gap-2">
                                    <Badge variant="outline" class="text-xs border-terracotta/20 text-terracotta">
                                        {{ student.admission_number }}
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
                            Upload a new profile photo for the student (JPG, PNG - Max 2MB)
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
                            Update the student's basic personal details
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
                                    placeholder="student@example.com"
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
                                    Phone Number
                                </Label>
                                <Input
                                    id="phone"
                                    v-model="form.phone"
                                    type="tel"
                                    placeholder="+254 700 000 000"
                                    class="border-terracotta/20 focus:ring-terracotta/30"
                                />
                                <div v-if="form.errors.phone" class="flex items-center gap-2 text-destructive text-sm">
                                    <AlertCircle class="w-4 h-4" />
                                    {{ form.errors.phone }}
                                </div>
                            </div>

                            <div class="space-y-2">
                                <Label for="date_of_birth" class="flex items-center gap-2 text-warm-text dark:text-dark-text">
                                    <Calendar class="w-4 h-4 text-terracotta" />
                                    Date of Birth
                                </Label>
                                <Input
                                    id="date_of_birth"
                                    v-model="form.date_of_birth"
                                    type="date"
                                    class="border-terracotta/20 focus:ring-terracotta/30"
                                />
                                <div v-if="form.errors.date_of_birth" class="flex items-center gap-2 text-destructive text-sm">
                                    <AlertCircle class="w-4 h-4" />
                                    {{ form.errors.date_of_birth }}
                                </div>
                            </div>

                            <div class="space-y-2">
                                <Label for="gender" class="flex items-center gap-2 text-warm-text dark:text-dark-text">
                                    <User class="w-4 h-4 text-terracotta" />
                                    Gender
                                </Label>
                                <select
                                    id="gender"
                                    v-model="form.gender"
                                    class="flex h-10 w-full rounded-md border border-terracotta/20 bg-background px-3 py-2 text-sm focus:ring-2 focus:ring-terracotta/30"
                                >
                                    <option value="">Select gender</option>
                                    <option value="male">Male</option>
                                    <option value="female">Female</option>
                                    <option value="other">Other</option>
                                </select>
                                <div v-if="form.errors.gender" class="flex items-center gap-2 text-destructive text-sm">
                                    <AlertCircle class="w-4 h-4" />
                                    {{ form.errors.gender }}
                                </div>
                            </div>

                            <div class="space-y-2 md:col-span-2">
                                <Label for="address" class="flex items-center gap-2 text-warm-text dark:text-dark-text">
                                    <MapPin class="w-4 h-4 text-terracotta" />
                                    Address
                                </Label>
                                <Input
                                    id="address"
                                    v-model="form.address"
                                    type="text"
                                    placeholder="Enter full address"
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

                <!-- Academic Information -->
                <div class="card-warm">
                    <div class="p-6 border-b border-terracotta/20">
                        <h3 class="flex items-center gap-2 text-lg font-black text-warm-text dark:text-dark-text">
                            <GraduationCap class="w-5 h-5 text-terracotta" />
                            Academic Information
                        </h3>
                        <p class="text-sm text-warm-muted dark:text-dark-muted mt-1">
                            Update the student's academic details and enrollment information
                        </p>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <Label for="admission_number" class="flex items-center gap-2 text-warm-text dark:text-dark-text">
                                    <GraduationCap class="w-4 h-4 text-terracotta" />
                                    Admission Number *
                                </Label>
                                <Input
                                    id="admission_number"
                                    v-model="form.admission_number"
                                    type="text"
                                    placeholder="e.g., STD2024001"
                                    required
                                    readonly
                                    class="bg-terracotta/5 cursor-not-allowed border-terracotta/20"
                                />
                                <div v-if="form.errors.admission_number" class="flex items-center gap-2 text-destructive text-sm">
                                    <AlertCircle class="w-4 h-4" />
                                    {{ form.errors.admission_number }}
                                </div>
                            </div>

                            <div class="space-y-2">
                                <Label for="roll_number" class="flex items-center gap-2 text-warm-text dark:text-dark-text">
                                    <GraduationCap class="w-4 h-4 text-terracotta" />
                                    Roll Number *
                                </Label>
                                <Input
                                    id="roll_number"
                                    v-model="form.roll_number"
                                    type="text"
                                    placeholder="e.g., ROLL20260001"
                                    readonly
                                    class="bg-terracotta/5 cursor-not-allowed border-terracotta/20"
                                />
                                <div v-if="form.errors.roll_number" class="flex items-center gap-2 text-destructive text-sm">
                                    <AlertCircle class="w-4 h-4" />
                                    {{ form.errors.roll_number }}
                                </div>
                            </div>

                            <div class="space-y-2">
                                <Label for="academic_class_id" class="flex items-center gap-2 text-warm-text dark:text-dark-text">
                                    <GraduationCap class="w-4 h-4 text-terracotta" />
                                    Class *
                                </Label>
                                <select
                                    id="academic_class_id"
                                    v-model="form.academic_class_id"
                                    class="flex h-10 w-full rounded-md border border-terracotta/20 bg-background px-3 py-2 text-sm focus:ring-2 focus:ring-terracotta/30"
                                    required
                                >
                                    <option value="">Select a class</option>
                                    <option v-for="classItem in classes" :key="classItem.id" :value="classItem.id">
                                        {{ classItem.name }}
                                    </option>
                                </select>
                                <div v-if="form.errors.academic_class_id" class="flex items-center gap-2 text-destructive text-sm">
                                    <AlertCircle class="w-4 h-4" />
                                    {{ form.errors.academic_class_id }}
                                </div>
                            </div>

                            <div class="space-y-2">
                                <Label for="section_id" class="flex items-center gap-2 text-warm-text dark:text-dark-text">
                                    <GraduationCap class="w-4 h-4 text-terracotta" />
                                    Section *
                                </Label>
                                <select
                                    id="section_id"
                                    v-model="form.section_id"
                                    class="flex h-10 w-full rounded-md border border-terracotta/20 bg-background px-3 py-2 text-sm focus:ring-2 focus:ring-terracotta/30"
                                    required
                                >
                                    <option value="">Select a section</option>
                                    <option v-for="section in sections" :key="section.id" :value="section.id">
                                        {{ section.name }}
                                    </option>
                                </select>
                                <div v-if="form.errors.section_id" class="flex items-center gap-2 text-destructive text-sm">
                                    <AlertCircle class="w-4 h-4" />
                                    {{ form.errors.section_id }}
                                </div>
                            </div>

                            <div class="space-y-2">
                                <Label for="admission_date" class="flex items-center gap-2 text-warm-text dark:text-dark-text">
                                    <Calendar class="w-4 h-4 text-terracotta" />
                                    Admission Date *
                                </Label>
                                <Input
                                    id="admission_date"
                                    v-model="form.admission_date"
                                    type="date"
                                    class="border-terracotta/20 focus:ring-terracotta/30"
                                />
                                <div v-if="form.errors.admission_date" class="flex items-center gap-2 text-destructive text-sm">
                                    <AlertCircle class="w-4 h-4" />
                                    {{ form.errors.admission_date }}
                                </div>
                            </div>

                            <div class="space-y-2">
                                <Label for="status" class="flex items-center gap-2 text-warm-text dark:text-dark-text">
                                    <GraduationCap class="w-4 h-4 text-terracotta" />
                                    Status
                                </Label>
                                <select
                                    id="status"
                                    v-model="form.status"
                                    class="flex h-10 w-full rounded-md border border-terracotta/20 bg-background px-3 py-2 text-sm focus:ring-2 focus:ring-terracotta/30"
                                >
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                    <option value="graduated">Graduated</option>
                                    <option value="transferred">Transferred</option>
                                </select>
                                <div v-if="form.errors.status" class="flex items-center gap-2 text-destructive text-sm">
                                    <AlertCircle class="w-4 h-4" />
                                    {{ form.errors.status }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Medical Information -->
                <div class="card-warm">
                    <div class="p-6 border-b border-terracotta/20">
                        <h3 class="flex items-center gap-2 text-lg font-black text-warm-text dark:text-dark-text">
                            <Users class="w-5 h-5 text-terracotta" />
                            Medical Information
                        </h3>
                        <p class="text-sm text-warm-muted dark:text-dark-muted mt-1">
                            Add medical details and emergency contact information
                        </p>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <Label for="emergency_contact" class="flex items-center gap-2 text-warm-text dark:text-dark-text">
                                    <Phone class="w-4 h-4 text-terracotta" />
                                    Emergency Contact
                                </Label>
                                <Input
                                    id="emergency_contact"
                                    v-model="form.emergency_contact"
                                    type="tel"
                                    placeholder="+254 700 000 000"
                                    class="border-terracotta/20 focus:ring-terracotta/30"
                                />
                                <div v-if="form.errors.emergency_contact" class="flex items-center gap-2 text-destructive text-sm">
                                    <AlertCircle class="w-4 h-4" />
                                    {{ form.errors.emergency_contact }}
                                </div>
                            </div>

                            <div class="space-y-2">
                                <Label for="blood_group" class="flex items-center gap-2 text-warm-text dark:text-dark-text">
                                    <Users class="w-4 h-4 text-terracotta" />
                                    Blood Group
                                </Label>
                                <select
                                    id="blood_group"
                                    v-model="form.blood_group"
                                    class="flex h-10 w-full rounded-md border border-terracotta/20 bg-background px-3 py-2 text-sm focus:ring-2 focus:ring-terracotta/30"
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
                                <div v-if="form.errors.blood_group" class="flex items-center gap-2 text-destructive text-sm">
                                    <AlertCircle class="w-4 h-4" />
                                    {{ form.errors.blood_group }}
                                </div>
                            </div>

                            <div class="space-y-2 md:col-span-2">
                                <Label for="medical_conditions" class="flex items-center gap-2 text-warm-text dark:text-dark-text">
                                    <Users class="w-4 h-4 text-terracotta" />
                                    Medical Conditions
                                </Label>
                                <textarea
                                    id="medical_conditions"
                                    v-model="form.medical_conditions"
                                    placeholder="Enter any known medical conditions, allergies, or special health requirements"
                                    class="flex min-h-[80px] w-full rounded-md border border-terracotta/20 bg-background px-3 py-2 text-sm focus:ring-2 focus:ring-terracotta/30"
                                ></textarea>
                                <div v-if="form.errors.medical_conditions" class="flex items-center gap-2 text-destructive text-sm">
                                    <AlertCircle class="w-4 h-4" />
                                    {{ form.errors.medical_conditions }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Academic History -->
                <div class="card-warm">
                    <div class="p-6 border-b border-terracotta/20">
                        <h3 class="flex items-center gap-2 text-lg font-black text-warm-text dark:text-dark-text">
                            <GraduationCap class="w-5 h-5 text-terracotta" />
                            Academic History
                        </h3>
                        <p class="text-sm text-warm-muted dark:text-dark-muted mt-1">
                            Previous educational background and transfer information
                        </p>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <Label for="previous_school" class="flex items-center gap-2 text-warm-text dark:text-dark-text">
                                    <GraduationCap class="w-4 h-4 text-terracotta" />
                                    Previous School
                                </Label>
                                <Input
                                    id="previous_school"
                                    v-model="form.previous_school"
                                    type="text"
                                    placeholder="Enter previous school name"
                                    class="border-terracotta/20 focus:ring-terracotta/30"
                                />
                                <div v-if="form.errors.previous_school" class="flex items-center gap-2 text-destructive text-sm">
                                    <AlertCircle class="w-4 h-4" />
                                    {{ form.errors.previous_school }}
                                </div>
                            </div>

                            <div class="space-y-2">
                                <Label for="transfer_certificate" class="flex items-center gap-2 text-warm-text dark:text-dark-text">
                                    <GraduationCap class="w-4 h-4 text-terracotta" />
                                    Transfer Certificate
                                </Label>
                                <Input
                                    id="transfer_certificate"
                                    v-model="form.transfer_certificate"
                                    type="text"
                                    placeholder="Transfer certificate number or status"
                                    class="border-terracotta/20 focus:ring-terracotta/30"
                                />
                                <div v-if="form.errors.transfer_certificate" class="flex items-center gap-2 text-destructive text-sm">
                                    <AlertCircle class="w-4 h-4" />
                                    {{ form.errors.transfer_certificate }}
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
                            Update student account password
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
                                            Leave the password field empty to keep the current password. Only fill this field if you want to change the student's password.
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
                                <Link :href="route('students.show', student.id)">
                                    <Button variant="outline" type="button" class="border-terracotta/20">
                                        <X class="w-4 h-4 mr-2" />
                                        Cancel
                                    </Button>
                                </Link>
                                <Button type="submit" :disabled="form.processing" size="lg" class="accent-terracotta text-white">
                                    <Save class="w-4 h-4 mr-2" />
                                    {{ form.processing ? 'Updating...' : 'Update Student' }}
                                </Button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </Sidebar>
</template>