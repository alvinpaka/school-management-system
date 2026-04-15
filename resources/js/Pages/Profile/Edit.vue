<script setup>
import DeleteUserForm from './Partials/DeleteUserForm.vue';
import UpdatePasswordForm from './Partials/UpdatePasswordForm.vue';
import UpdateProfileInformationForm from './Partials/UpdateProfileInformationForm.vue';
import { Head } from '@inertiajs/vue3';
import Sidebar from '@/Components/Sidebar.vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';
import { Separator } from '@/components/ui/separator';
import { Tabs, TabsContent, TabsList, TabsTrigger } from '@/components/ui/tabs';
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
} from '@/components/ui/dialog';
import { 
    User as UserIcon,
    Shield,
    Key,
    AlertCircle,
    Camera,
    Upload,
    X,
    Mail,
    Settings,
    Lock,
    UserCircle,
    Bell,
    CheckCircle
} from 'lucide-vue-next';
import { ref } from 'vue';
import { router, usePage } from '@inertiajs/vue3';

defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const page = usePage();
const activeTab = ref('profile');

const photoModalOpen = ref(false);
const photoPreview = ref(null);
const photoFile = ref(null);
const uploading = ref(false);
const errors = ref({});

// Photo upload functions
const handleFileChange = (event) => {
    const file = event.target.files[0];
    if (file) {
        photoFile.value = file;
        const reader = new FileReader();
        reader.onload = (e) => {
            photoPreview.value = e.target.result;
        };
        reader.readAsDataURL(file);
    }
};

const closePhotoModal = () => {
    photoModalOpen.value = false;
    photoPreview.value = null;
    photoFile.value = null;
    errors.value = {};
    // Reset file input
    const fileInput = document.getElementById('photo-input');
    if (fileInput) {
        fileInput.value = '';
    }
};

const openPhotoModal = () => {
    photoModalOpen.value = true;
    errors.value = {};
};

const removePhotoPreview = () => {
    photoPreview.value = null;
    photoFile.value = null;
    const fileInput = document.getElementById('photo-input');
    if (fileInput) {
        fileInput.value = '';
    }
};

const uploadPhoto = async () => {
    if (!photoFile.value) {
        errors.value.photo = 'Please select a photo to upload.';
        return;
    }

    uploading.value = true;
    errors.value = {};

    const formData = new FormData();
    formData.append('photo', photoFile.value);

    try {
        await router.post(route('photo.update'), formData, {
            forceFormData: true,
            onSuccess: () => {
                console.log('Upload successful');
                closePhotoModal();
                // Force complete page reload to get fresh data
                setTimeout(() => {
                    window.location.reload();
                }, 100);
            },
            onError: (error) => {
                if (error && error.response && error.response.data && error.response.data.errors) {
                    errors.value = error.response.data.errors;
                } else if (error && error.errors) {
                    errors.value = error.errors;
                } else {
                    errors.value = { photo: 'Upload failed. Please try again.' };
                }
            }
        });
    } catch (error) {
        errors.value = { photo: 'Upload failed. Please try again.' };
    } finally {
        uploading.value = false;
    }
};

const getInitials = (name) => {
    if (!name) return 'U';
    return name
        .split(' ')
        .map(word => word[0])
        .join('')
        .toUpperCase()
        .slice(0, 2);
};

const getUserRole = () => {
    return page.props.auth.user.role || 'User';
};

const getRoleColor = (role) => {
    const colors = {
        'admin': 'bg-terracotta/10 text-terracotta border-terracotta/20',
        'teacher': 'bg-forest/10 text-forest border-forest/20',
        'student': 'bg-terracotta/10 text-terracotta border-terracotta/20',
        'parent': 'bg-amber/10 text-amber border-amber/20',
    };
    return colors[role?.toLowerCase()] || 'bg-warm-muted/10 text-warm-muted border-warm-muted/20';
};
</script>

<template>
    <Head title="Profile | EduManage Pro" />

    <Sidebar>
        <template #header-title>
            <div class="flex items-center space-x-3">
                <UserCircle class="w-5 h-5 text-terracotta" />
                <span class="font-semibold text-warm-text text-dark-text">My Profile</span>
            </div>
        </template>

        <div class="mx-auto max-w-7xl space-y-6 animate-fade-in-up">
            <!-- Header Card with User Photo and Quick Info -->
            <div class="card-warm overflow-hidden">
                <div class="h-32 accent-terracotta"></div>
                <div class="relative pt-0 pb-6 p-6">
                    <div class="flex flex-col md:flex-row md:items-end md:justify-between -mt-16 md:-mt-20">
                        <!-- User Avatar and Basic Info -->
                        <div class="flex flex-col md:flex-row items-center md:items-end space-y-4 md:space-y-0 md:space-x-6">
                            <div class="relative group">
                                <Avatar class="w-32 h-32 md:w-40 md:h-40 border-4 border-white dark:border-dark-bg shadow-xl">
                                    <AvatarImage 
                                        v-if="$page.props.auth.user.photo"
                                        :src="`/storage/${$page.props.auth.user.photo}`" 
                                        :alt="$page.props.auth.user.name"
                                    />
                                    <AvatarFallback class="text-3xl md:text-4xl font-bold bg-terracotta text-white">
                                        {{ getInitials($page.props.auth.user.name) }}
                                    </AvatarFallback>
                                </Avatar>
                                <!-- Photo overlay on hover -->
                                <div 
                                    @click="openPhotoModal"
                                    class="absolute inset-0 bg-black/50 rounded-full opacity-0 group-hover:opacity-100 transition-opacity cursor-pointer flex items-center justify-center"
                                >
                                    <Camera class="w-8 h-8 text-white" />
                                </div>
                                <div class="absolute bottom-2 right-2 w-5 h-5 bg-forest rounded-full border-4 border-white dark:border-dark-bg"></div>
                            </div>
                            
                            <div class="text-center md:text-left space-y-2 mb-2">
                                <h1 class="text-2xl md:text-3xl font-black text-warm-text text-dark-text">
                                    {{ $page.props.auth.user.name }}
                                </h1>
                                <div class="flex flex-wrap items-center justify-center md:justify-start gap-3">
                                    <Badge :class="getRoleColor(getUserRole())" class="border-0">
                                        {{ getUserRole() }}
                                    </Badge>
                                    <div class="flex items-center text-sm text-warm-muted text-dark-muted">
                                        <Mail class="w-4 h-4 mr-1.5 text-terracotta" />
                                        {{ $page.props.auth.user.email }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Quick Stats or Action Buttons -->
                        <div class="flex items-center justify-center md:justify-end space-x-2 mt-4 md:mt-0">
                            <Button variant="outline" size="sm" @click="openPhotoModal" class="border-terracotta/20 text-terracotta hover:bg-terracotta/10">
                                <Camera class="w-4 h-4 mr-2" />
                                Change Photo
                            </Button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Email Verification Alert -->
            <div v-if="mustVerifyEmail" class="card-warm border-amber/20 bg-amber/5">
                <div class="p-6">
                    <div class="flex items-start space-x-3">
                        <AlertCircle class="w-5 h-5 text-amber flex-shrink-0 mt-0.5" />
                        <div class="flex-1">
                            <h3 class="text-sm font-medium text-amber">
                                Email Verification Required
                            </h3>
                            <p class="text-sm text-amber/80 mt-1">
                                Before proceeding, please check your email for a verification link.
                            </p>
                            <p v-if="status === 'verification-link-sent'" class="flex items-center text-sm text-forest mt-2">
                                <CheckCircle class="w-4 h-4 mr-1.5" />
                                A new verification link has been sent to your email address.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content with Tabs -->
            <Tabs v-model="activeTab" default-value="profile" class="w-full">
                <TabsList class="p-1 bg-terracotta/5 border border-terracotta/20 rounded-2xl mb-6 inline-flex h-14">
                    <TabsTrigger value="profile" class="rounded-xl px-6 font-black uppercase text-[10px] tracking-widest data-[state=active]:bg-white dark:data-[state=active]:bg-dark-bg data-[state=active]:text-terracotta data-[state=active]:shadow-sm gap-2">
                        <UserIcon class="w-4 h-4" />
                        <span class="hidden sm:inline">Profile</span>
                    </TabsTrigger>
                    <TabsTrigger value="security" class="rounded-xl px-6 font-black uppercase text-[10px] tracking-widest data-[state=active]:bg-white dark:data-[state=active]:bg-dark-bg data-[state=active]:text-terracotta data-[state=active]:shadow-sm gap-2">
                        <Shield class="w-4 h-4" />
                        <span class="hidden sm:inline">Security</span>
                    </TabsTrigger>
                    <TabsTrigger value="settings" class="rounded-xl px-6 font-black uppercase text-[10px] tracking-widest data-[state=active]:bg-white dark:data-[state=active]:bg-dark-bg data-[state=active]:text-terracotta data-[state=active]:shadow-sm gap-2">
                        <Settings class="w-4 h-4" />
                        <span class="hidden sm:inline">Settings</span>
                    </TabsTrigger>
                </TabsList>

                <!-- Profile Tab -->
                <TabsContent value="profile" class="space-y-6 mt-6">
                    <!-- Profile Information -->
                    <div class="card-warm">
                        <div class="p-6 border-b border-terracotta/20">
                            <h3 class="text-lg font-black text-warm-text text-dark-text flex items-center gap-2">
                                <UserIcon class="w-5 h-5 text-terracotta" />
                                Profile Information
                            </h3>
                            <p class="text-sm text-warm-muted text-dark-muted mt-1">
                                Update your account's profile information and email address.
                            </p>
                        </div>
                        <div class="p-6">
                            <UpdateProfileInformationForm :must-verify-email="mustVerifyEmail" :status="status" />
                        </div>
                    </div>
                </TabsContent>

                <!-- Security Tab -->
                <TabsContent value="security" class="space-y-6 mt-6">
                    <!-- Update Password -->
                    <div class="card-warm">
                        <div class="p-6 border-b border-terracotta/20">
                            <h3 class="text-lg font-black text-warm-text text-dark-text flex items-center gap-2">
                                <Key class="w-5 h-5 text-terracotta" />
                                Update Password
                            </h3>
                            <p class="text-sm text-warm-muted text-dark-muted mt-1">
                                Ensure your account is using a long, random password to stay secure.
                            </p>
                        </div>
                        <div class="p-6">
                            <UpdatePasswordForm />
                        </div>
                    </div>

                    <!-- Two-Factor Authentication (Placeholder) -->
                    <div class="card-warm">
                        <div class="p-6 border-b border-terracotta/20">
                            <h3 class="text-lg font-black text-warm-text text-dark-text flex items-center gap-2">
                                <Shield class="w-5 h-5 text-terracotta" />
                                Two-Factor Authentication
                            </h3>
                            <p class="text-sm text-warm-muted text-dark-muted mt-1">
                                Add additional security to your account using two-factor authentication
                            </p>
                        </div>
                        <div class="p-6">
                            <div class="text-center py-8 text-warm-muted text-dark-muted">
                                <Shield class="w-12 h-12 mx-auto mb-3 opacity-50 text-terracotta" />
                                <p>Two-factor authentication not yet configured</p>
                                <Button variant="link" class="mt-2 text-terracotta">Enable 2FA</Button>
                            </div>
                        </div>
                    </div>
                </TabsContent>

                <!-- Settings Tab -->
                <TabsContent value="settings" class="space-y-6 mt-6">
                    <!-- Delete Account -->
                    <div class="card-warm border-destructive/20">
                        <div class="p-6 border-b border-destructive/20">
                            <h3 class="text-lg font-black text-destructive flex items-center gap-2">
                                <AlertCircle class="w-5 h-5" />
                                Delete Account
                            </h3>
                            <p class="text-sm text-destructive/70 mt-1">
                                Permanently delete your account. This action cannot be undone.
                            </p>
                        </div>
                        <div class="p-6">
                            <div class="p-4 bg-destructive/5 border border-destructive/20 rounded-lg mb-4">
                                <div class="flex items-start gap-3">
                                    <AlertCircle class="w-5 h-5 text-destructive flex-shrink-0 mt-0.5" />
                                    <div>
                                        <h4 class="font-medium text-destructive mb-1">
                                            Warning: This action is irreversible
                                        </h4>
                                        <p class="text-sm text-destructive/80">
                                            Once you delete your account, all of your resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <DeleteUserForm />
                        </div>
                    </div>
                </TabsContent>
            </Tabs>
        </div>

        <!-- Photo Upload Modal -->
        <Dialog :open="photoModalOpen" @update:open="closePhotoModal">
            <DialogContent class="sm:max-w-md card-warm">
                <DialogHeader>
                    <DialogTitle class="flex items-center gap-2 text-warm-text text-dark-text">
                        <Camera class="w-5 h-5 text-terracotta" />
                        Upload Profile Photo
                    </DialogTitle>
                    <DialogDescription class="text-warm-muted text-dark-muted">
                        Upload your profile photo for identification. Passport size recommended (400x500px). Maximum file size: 2MB.
                    </DialogDescription>
                </DialogHeader>
                
                <div class="space-y-4">
                    <!-- Photo Preview -->
                    <div class="flex items-center justify-center">
                        <div class="relative">
                            <Avatar class="w-40 h-40 border-4 border-terracotta/20">
                                <AvatarImage 
                                    v-if="photoPreview || $page.props.auth.user.photo"
                                    :src="photoPreview || `/storage/${$page.props.auth.user.photo}`" 
                                    :alt="$page.props.auth.user.name"
                                />
                                <AvatarFallback class="text-4xl font-bold bg-terracotta text-white">
                                    {{ getInitials($page.props.auth.user.name) }}
                                </AvatarFallback>
                            </Avatar>
                            <Button
                                v-if="photoPreview"
                                type="button"
                                variant="destructive"
                                size="icon"
                                class="absolute -top-2 -right-2 w-8 h-8 rounded-full shadow-lg"
                                @click="removePhotoPreview"
                            >
                                <X class="w-4 h-4" />
                            </Button>
                        </div>
                    </div>

                    <!-- File Input -->
                    <div>
                        <label class="block text-sm font-medium text-warm-text text-dark-text mb-2">
                            Select Photo
                        </label>
                        <div class="border-2 border-dashed border-terracotta/30 rounded-lg p-6 text-center hover:border-terracotta transition-colors">
                            <Upload class="w-12 h-12 mx-auto text-terracotta/50 mb-3" />
                            <label 
                                for="photo-input" 
                                class="cursor-pointer text-terracotta hover:text-terracotta/80 font-medium"
                            >
                                Click to upload
                            </label>
                            <p class="text-sm text-warm-muted text-dark-muted mt-1">
                                or drag and drop
                            </p>
                            <p class="text-xs text-warm-muted text-dark-muted mt-1">
                                PNG, JPG or JPEG (MAX. 2MB)
                            </p>
                            <input
                                id="photo-input"
                                type="file"
                                accept="image/jpeg,image/jpg,image/png"
                                @change="handleFileChange"
                                class="hidden"
                            />
                        </div>
                    </div>

                    <!-- File Info -->
                    <div v-if="photoFile" class="flex items-center gap-2 text-sm text-warm-muted text-dark-muted p-3 bg-terracotta/5 rounded-lg">
                        <Camera class="w-4 h-4 text-terracotta" />
                        <span class="font-medium">{{ photoFile.name }}</span>
                        <span class="text-xs">({{ (photoFile.size / 1024).toFixed(2) }} KB)</span>
                    </div>

                    <!-- Error Messages -->
                    <div v-if="errors.photo" class="flex items-center gap-2 text-sm text-destructive p-3 bg-destructive/10 rounded-lg">
                        <AlertCircle class="w-4 h-4" />
                        {{ errors.photo }}
                    </div>
                </div>

                <DialogFooter>
                    <Button variant="outline" @click="closePhotoModal" class="border-terracotta/20">
                        Cancel
                    </Button>
                    <Button 
                        @click="uploadPhoto" 
                        :disabled="!photoFile || uploading"
                        class="accent-terracotta text-white"
                    >
                        <Upload class="w-4 h-4 mr-2" />
                        {{ uploading ? 'Uploading...' : 'Upload Photo' }}
                    </Button>
                </DialogFooter>
            </DialogContent>
        </Dialog>
    </Sidebar>
</template>