<script setup>
import { useForm } from '@inertiajs/vue3';
import { ref, watch } from 'vue';
import { Button } from '@/Components/ui/button';
import { Input } from '@/Components/ui/input';
import { Label } from '@/Components/ui/label';
import { Badge } from '@/Components/ui/badge';
import { Avatar, AvatarFallback, AvatarImage } from '@/Components/ui/avatar';
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogHeader,
    DialogTitle,
    DialogFooter,
} from '@/Components/ui/dialog';
import {
    Plus,
    Save,
    User,
    Lock,
    Camera,
    Upload,
    X,
    Sparkles,
    RefreshCw,
    Phone,
    GraduationCap as GradIcon
} from 'lucide-vue-next';

const props = defineProps({
    open: {
        type: Boolean,
        default: false
    },
    classes: {
        type: Array,
        default: () => []
    },
    sections: {
        type: Array,
        default: () => []
    }
});

const emit = defineEmits(['update:open', 'success']);

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

// Reset form when dialog opens
watch(() => props.open, (newVal) => {
    if (newVal) {
        form.reset();
        form.admission_number = generateAdmissionNumber();
        form.roll_number = generateRollNumber();
        form.admission_date = new Date().toISOString().split('T')[0];
        form.status = 'active';
        photoPreview.value = null;
        photoFile.value = null;
    }
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
            emit('update:open', false);
            emit('success');
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

const closeDialog = () => {
    emit('update:open', false);
};
</script>

<template>
    <Dialog :open="open" @update:open="emit('update:open', $event)">
        <DialogContent class="w-[95vw] max-w-7xl max-h-[95vh] overflow-y-auto card-warm">
            <DialogHeader>
                <DialogTitle class="text-2xl font-black tracking-tight text-warm-text text-dark-text">Enroll New Student</DialogTitle>
                <DialogDescription class="text-warm-muted text-dark-muted">
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
                                    <h3 class="text-xl font-bold text-warm-text text-dark-text">{{ form.name || 'New Student' }}</h3>
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
                            <h3 class="flex items-center gap-2 text-base font-black text-warm-text text-dark-text">
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
                                    <p class="text-xs text-warm-muted text-dark-muted">PNG, JPG (Max 2MB)</p>
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
                            <h3 class="flex items-center gap-2 text-base font-black text-warm-text text-dark-text">
                                <User class="w-4 h-4 text-terracotta" />
                                Basic Information
                            </h3>
                        </div>
                        <div class="p-6 space-y-3">
                            <div class="space-y-1">
                                <Label for="name" class="text-sm text-warm-text text-dark-text">Full Name *</Label>
                                <Input id="name" v-model="form.name" type="text" placeholder="Enter full name" required class="border-terracotta/20 focus:ring-terracotta/30" />
                                <div v-if="form.errors.name" class="text-destructive text-xs">{{ form.errors.name }}</div>
                            </div>
                            <div class="space-y-1">
                                <Label for="email" class="text-sm text-warm-text text-dark-text">Email Address *</Label>
                                <Input id="email" v-model="form.email" type="email" placeholder="student@example.com" required class="border-terracotta/20 focus:ring-terracotta/30" />
                                <div v-if="form.errors.email" class="text-destructive text-xs">{{ form.errors.email }}</div>
                            </div>
                            <div class="space-y-1">
                                <Label for="password" class="text-sm text-warm-text text-dark-text">Password *</Label>
                                <Input id="password" v-model="form.password" type="password" placeholder="Enter password" required class="border-terracotta/20 focus:ring-terracotta/30" />
                                <div v-if="form.errors.password" class="text-destructive text-xs">{{ form.errors.password }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Academic Information -->
                <div class="card-warm">
                    <div class="p-6 border-b border-terracotta/20">
                        <h3 class="flex items-center gap-2 text-base font-black text-warm-text text-dark-text">
                            <GradIcon class="w-4 h-4 text-terracotta" />
                            Academic Information
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="space-y-1">
                                <Label class="text-sm text-warm-text text-dark-text">Admission Number</Label>
                                <div class="flex gap-2">
                                    <Input v-model="form.admission_number" readonly class="bg-terracotta/5 text-sm border-terracotta/20" />
                                    <Button type="button" variant="outline" size="icon" @click="regenerateNumbers" class="shrink-0 border-terracotta/20">
                                        <RefreshCw class="w-4 h-4" />
                                    </Button>
                                </div>
                            </div>
                            <div class="space-y-1">
                                <Label class="text-sm text-warm-text text-dark-text">Roll Number</Label>
                                <Input v-model="form.roll_number" readonly class="bg-terracotta/5 text-sm border-terracotta/20" />
                            </div>
                            <div class="space-y-1">
                                <Label for="admission_date" class="text-sm text-warm-text text-dark-text">Admission Date</Label>
                                <Input id="admission_date" v-model="form.admission_date" type="date" class="border-terracotta/20 focus:ring-terracotta/30" />
                            </div>
                            <div class="space-y-1">
                                <Label for="academic_class_id" class="text-sm text-warm-text text-dark-text">Class *</Label>
                                <select id="academic_class_id" v-model="form.academic_class_id" class="flex h-10 w-full rounded-md border border-terracotta/20 bg-background px-3 py-2 text-sm focus:ring-terracotta/30" required>
                                    <option value="">Select class</option>
                                    <option v-for="classItem in classes" :key="classItem.id" :value="classItem.id">{{ classItem.name }}</option>
                                </select>
                                <div v-if="form.errors.academic_class_id" class="text-destructive text-xs">{{ form.errors.academic_class_id }}</div>
                            </div>
                            <div class="space-y-1">
                                <Label for="section_id" class="text-sm text-warm-text text-dark-text">Section *</Label>
                                <select id="section_id" v-model="form.section_id" class="flex h-10 w-full rounded-md border border-terracotta/20 bg-background px-3 py-2 text-sm focus:ring-terracotta/30" required>
                                    <option value="">Select section</option>
                                    <option v-for="section in sections" :key="section.id" :value="section.id">{{ section.name }}</option>
                                </select>
                                <div v-if="form.errors.section_id" class="text-destructive text-xs">{{ form.errors.section_id }}</div>
                            </div>
                            <div class="space-y-1">
                                <Label for="status" class="text-sm text-warm-text text-dark-text">Status</Label>
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
                        <h3 class="flex items-center gap-2 text-base font-black text-warm-text text-dark-text">
                            <User class="w-4 h-4 text-terracotta" />
                            Personal Details
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="space-y-1">
                                <Label for="phone" class="text-sm text-warm-text text-dark-text">Phone Number</Label>
                                <Input id="phone" v-model="form.phone" type="tel" placeholder="+254..." class="border-terracotta/20 focus:ring-terracotta/30" />
                            </div>
                            <div class="space-y-1">
                                <Label for="date_of_birth" class="text-sm text-warm-text text-dark-text">Date of Birth</Label>
                                <Input id="date_of_birth" v-model="form.date_of_birth" type="date" class="border-terracotta/20 focus:ring-terracotta/30" />
                            </div>
                            <div class="space-y-1">
                                <Label for="gender" class="text-sm text-warm-text text-dark-text">Gender</Label>
                                <select id="gender" v-model="form.gender" class="flex h-10 w-full rounded-md border border-terracotta/20 bg-background px-3 py-2 text-sm focus:ring-terracotta/30">
                                    <option value="">Select gender</option>
                                    <option value="male">Male</option>
                                    <option value="female">Female</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>
                            <div class="space-y-1">
                                <Label for="blood_group" class="text-sm text-warm-text text-dark-text">Blood Group</Label>
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
                                <Label for="address" class="text-sm text-warm-text text-dark-text">Address</Label>
                                <Input id="address" v-model="form.address" type="text" placeholder="Enter full address" class="border-terracotta/20 focus:ring-terracotta/30" />
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Emergency Contact -->
                <div class="card-warm">
                    <div class="p-6 border-b border-terracotta/20">
                        <h3 class="flex items-center gap-2 text-base font-black text-warm-text text-dark-text">
                            <Phone class="w-4 h-4 text-terracotta" />
                            Emergency Contact
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="space-y-1">
                            <Label for="emergency_contact" class="text-sm text-warm-text text-dark-text">Emergency Contact Phone</Label>
                            <Input id="emergency_contact" v-model="form.emergency_contact" type="tel" placeholder="+254..." class="border-terracotta/20 focus:ring-terracotta/30" />
                        </div>
                    </div>
                </div>

                <DialogFooter class="gap-3 pt-4 border-t border-terracotta/20">
                    <Button type="button" variant="outline" @click="closeDialog" class="border-terracotta/20">
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
</template>
