<script setup>
import { useForm } from '@inertiajs/vue3';
import { ref, watch } from 'vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Badge } from '@/components/ui/badge';
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogHeader,
    DialogTitle,
    DialogFooter,
} from '@/components/ui/dialog';
import {
    Save,
    User,
    Camera,
    X,
    Sparkles,
    RefreshCw,
    Phone,
    Briefcase,
    GraduationCap,
    UserCircle
} from 'lucide-vue-next';

const props = defineProps({
    open: {
        type: Boolean,
        default: false
    }
});

const emit = defineEmits(['update:open', 'success']);

const photoPreview = ref(null);
const photoFile = ref(null);

// Generate employee ID automatically
const generateEmployeeID = () => {
    const year = new Date().getFullYear();
    const random = Math.floor(Math.random() * 10000);
    return `EMP${year}${random.toString().padStart(4, '0')}`;
};

// Create teacher form
const form = useForm({
    name: '',
    email: '',
    password: '',
    employee_id: generateEmployeeID(),
    phone: '',
    employment_type: 'Full-time',
    role: 'teacher',
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

// Reset form when dialog opens
watch(() => props.open, (newVal) => {
    if (newVal) {
        form.reset();
        form.employee_id = generateEmployeeID();
        form.joining_date = new Date().toISOString().split('T')[0];
        form.employment_type = 'Full-time';
        form.role = 'teacher';
        form.status = 'Active';
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

const regenerateEmployeeID = () => {
    form.employee_id = generateEmployeeID();
};

const getFormInitials = (name) => {
    if (!name) return 'TC';
    return name.split(' ').map(word => word[0]).join('').toUpperCase().slice(0, 2);
};

const submitForm = () => {
    form.post(route('teachers.store'), {
        forceFormData: true,
        onSuccess: () => {
            emit('update:open', false);
            emit('success');
            form.reset();
            form.employee_id = generateEmployeeID();
            form.joining_date = new Date().toISOString().split('T')[0];
            form.employment_type = 'Full-time';
            form.role = 'teacher';
            form.status = 'Active';
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
                <DialogTitle class="text-2xl font-black tracking-tight text-warm-text dark:text-dark-text">Add New Staff Member</DialogTitle>
                <DialogDescription class="text-warm-muted dark:text-dark-muted">
                    Create a new faculty or staff member account with all details.
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
                                    <h3 class="text-xl font-bold text-warm-text dark:text-dark-text">{{ form.name || 'New Staff Member' }}</h3>
                                    <div class="flex flex-wrap items-center justify-center md:justify-start gap-2">
                                        <Badge variant="outline" class="gap-1 text-xs border-terracotta/20 text-terracotta">
                                            <Sparkles class="w-3 h-3" />
                                            Creating Profile
                                        </Badge>
                                        <Badge v-if="form.employee_id" variant="outline" class="text-xs border-terracotta/20">{{ form.employee_id }}</Badge>
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
                                <Input id="email" v-model="form.email" type="email" placeholder="staff@example.com" required class="border-terracotta/20 focus:ring-terracotta/30" />
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

                <!-- Employment Details -->
                <div class="card-warm">
                    <div class="p-6 border-b border-terracotta/20">
                        <h3 class="flex items-center gap-2 text-base font-black text-warm-text dark:text-dark-text">
                            <Briefcase class="w-4 h-4 text-terracotta" />
                            Employment Details
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="space-y-1">
                                <Label class="text-sm text-warm-text dark:text-dark-text">Employee ID</Label>
                                <div class="flex gap-2">
                                    <Input v-model="form.employee_id" readonly class="bg-terracotta/5 text-sm border-terracotta/20" />
                                    <Button type="button" variant="outline" size="icon" @click="regenerateEmployeeID" class="border-terracotta/20">
                                        <RefreshCw class="w-4 h-4" />
                                    </Button>
                                </div>
                            </div>
                            <div class="space-y-1">
                                <Label for="employment_type" class="text-sm text-warm-text dark:text-dark-text">Employment Type</Label>
                                <select id="employment_type" v-model="form.employment_type" class="flex h-10 w-full rounded-md border border-terracotta/20 bg-background px-3 py-2 text-sm focus:ring-2 focus:ring-terracotta/30">
                                    <option value="Full-time">Full-time</option>
                                    <option value="Part-time">Part-time</option>
                                    <option value="Contract">Contract</option>
                                    <option value="Temporary">Temporary</option>
                                    <option value="Intern">Intern</option>
                                </select>
                            </div>
                            <div class="space-y-1">
                                <Label for="role" class="text-sm text-warm-text dark:text-dark-text">Staff Role</Label>
                                <select id="role" v-model="form.role" class="flex h-10 w-full rounded-md border border-terracotta/20 bg-background px-3 py-2 text-sm focus:ring-2 focus:ring-terracotta/30">
                                    <option value="teacher">Teacher</option>
                                    <option value="librarian">Librarian</option>
                                    <option value="accountant">Accountant</option>
                                    <option value="receptionist">Receptionist</option>
                                </select>
                            </div>
                            <div class="space-y-1">
                                <Label for="joining_date" class="text-sm text-warm-text dark:text-dark-text">Joining Date</Label>
                                <Input id="joining_date" v-model="form.joining_date" type="date" class="border-terracotta/20 focus:ring-terracotta/30" />
                            </div>
                            <div class="space-y-1">
                                <Label for="status" class="text-sm text-warm-text dark:text-dark-text">Status</Label>
                                <select id="status" v-model="form.status" class="flex h-10 w-full rounded-md border border-terracotta/20 bg-background px-3 py-2 text-sm focus:ring-2 focus:ring-terracotta/30">
                                    <option value="Active">Active</option>
                                    <option value="Inactive">Inactive</option>
                                    <option value="Suspended">Suspended</option>
                                    <option value="On Leave">On Leave</option>
                                </select>
                            </div>
                            <div class="space-y-1">
                                <Label for="phone" class="text-sm text-warm-text dark:text-dark-text">Phone Number</Label>
                                <Input id="phone" v-model="form.phone" type="tel" placeholder="+254..." class="border-terracotta/20 focus:ring-terracotta/30" />
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Professional Info -->
                <div class="card-warm">
                    <div class="p-6 border-b border-terracotta/20">
                        <h3 class="flex items-center gap-2 text-base font-black text-warm-text dark:text-dark-text">
                            <GraduationCap class="w-4 h-4 text-terracotta" />
                            Professional Information
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="space-y-1">
                                <Label for="specialization" class="text-sm text-warm-text dark:text-dark-text">Specialization</Label>
                                <select id="specialization" v-model="form.specialization" class="flex h-10 w-full rounded-md border border-terracotta/20 bg-background px-3 py-2 text-sm focus:ring-2 focus:ring-terracotta/30">
                                    <option value="">Select specialization</option>
                                    <option value="English">English</option>
                                    <option value="Mathematics">Mathematics</option>
                                    <option value="Biology">Biology</option>
                                    <option value="Chemistry">Chemistry</option>
                                    <option value="Physics">Physics</option>
                                    <option value="History">History</option>
                                    <option value="Geography">Geography</option>
                                    <option value="Computer Studies">Computer Studies</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                            <div class="space-y-1">
                                <Label for="qualification" class="text-sm text-warm-text dark:text-dark-text">Qualification</Label>
                                <select id="qualification" v-model="form.qualification" class="flex h-10 w-full rounded-md border border-terracotta/20 bg-background px-3 py-2 text-sm focus:ring-2 focus:ring-terracotta/30">
                                    <option value="">Select qualification</option>
                                    <option value="Bachelor's Degree">Bachelor's Degree</option>
                                    <option value="Master's Degree">Master's Degree</option>
                                    <option value="PhD">PhD</option>
                                    <option value="Teaching Certificate">Teaching Certificate</option>
                                    <option value="Diploma in Education">Diploma in Education</option>
                                    <option value="B.Ed">B.Ed</option>
                                    <option value="M.Ed">M.Ed</option>
                                </select>
                            </div>
                            <div class="space-y-1">
                                <Label for="experience" class="text-sm text-warm-text dark:text-dark-text">Experience</Label>
                                <select id="experience" v-model="form.experience" class="flex h-10 w-full rounded-md border border-terracotta/20 bg-background px-3 py-2 text-sm focus:ring-2 focus:ring-terracotta/30">
                                    <option value="">Select experience</option>
                                    <option value="Less than 1 year">Less than 1 year</option>
                                    <option value="1-3 years">1-3 years</option>
                                    <option value="3-5 years">3-5 years</option>
                                    <option value="5-10 years">5-10 years</option>
                                    <option value="10+ years">10+ years</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Personal Details -->
                <div class="card-warm">
                    <div class="p-6 border-b border-terracotta/20">
                        <h3 class="flex items-center gap-2 text-base font-black text-warm-text dark:text-dark-text">
                            <UserCircle class="w-4 h-4 text-terracotta" />
                            Personal Details
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="space-y-1">
                                <Label for="date_of_birth" class="text-sm text-warm-text dark:text-dark-text">Date of Birth</Label>
                                <Input id="date_of_birth" v-model="form.date_of_birth" type="date" class="border-terracotta/20 focus:ring-terracotta/30" />
                            </div>
                            <div class="space-y-1">
                                <Label for="gender" class="text-sm text-warm-text dark:text-dark-text">Gender</Label>
                                <select id="gender" v-model="form.gender" class="flex h-10 w-full rounded-md border border-terracotta/20 bg-background px-3 py-2 text-sm focus:ring-2 focus:ring-terracotta/30">
                                    <option value="">Select gender</option>
                                    <option value="male">Male</option>
                                    <option value="female">Female</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>
                            <div class="space-y-1">
                                <Label for="blood_group" class="text-sm text-warm-text dark:text-dark-text">Blood Group</Label>
                                <select id="blood_group" v-model="form.blood_group" class="flex h-10 w-full rounded-md border border-terracotta/20 bg-background px-3 py-2 text-sm focus:ring-2 focus:ring-terracotta/30">
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
                            <div class="space-y-1 md:col-span-3">
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
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="space-y-1">
                                <Label for="emergency_name" class="text-sm text-warm-text dark:text-dark-text">Contact Name</Label>
                                <Input id="emergency_name" v-model="form.emergency_contact_name" type="text" placeholder="Emergency contact name" class="border-terracotta/20 focus:ring-terracotta/30" />
                            </div>
                            <div class="space-y-1">
                                <Label for="emergency_phone" class="text-sm text-warm-text dark:text-dark-text">Contact Phone</Label>
                                <Input id="emergency_phone" v-model="form.emergency_contact_phone" type="tel" placeholder="Emergency phone" class="border-terracotta/20 focus:ring-terracotta/30" />
                            </div>
                            <div class="space-y-1">
                                <Label for="emergency_relationship" class="text-sm text-warm-text dark:text-dark-text">Relationship</Label>
                                <Input id="emergency_relationship" v-model="form.emergency_contact_relationship" type="text" placeholder="e.g., Spouse, Parent" class="border-terracotta/20 focus:ring-terracotta/30" />
                            </div>
                        </div>
                    </div>
                </div>

                <DialogFooter class="gap-3 pt-4 border-t border-terracotta/20">
                    <Button type="button" variant="outline" @click="closeDialog" class="border-terracotta/20">
                        Cancel
                    </Button>
                    <Button type="submit" :disabled="form.processing" class="accent-terracotta text-white">
                        <Save class="w-4 h-4 mr-2" />
                        {{ form.processing ? 'Creating...' : 'Create Staff Member' }}
                    </Button>
                </DialogFooter>
            </form>
        </DialogContent>
    </Dialog>
</template>
