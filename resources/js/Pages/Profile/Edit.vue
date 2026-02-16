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
        'admin': 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400 border-red-200 dark:border-red-800',
        'teacher': 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400 border-emerald-200 dark:border-emerald-800',
        'student': 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400 border-blue-200 dark:border-blue-800',
        'parent': 'bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-400 border-purple-200 dark:border-purple-800',
    };
    return colors[role?.toLowerCase()] || 'bg-gray-100 text-gray-700 dark:bg-gray-900/30 dark:text-gray-400 border-gray-200 dark:border-gray-800';
};
</script>

<template>
    <Head title="Profile" />

    <Sidebar>
        <template #header-title>
            <div class="flex items-center space-x-3">
                <UserCircle class="w-5 h-5" />
                <span class="font-semibold">My Profile</span>
            </div>
        </template>

        <div class="mx-auto max-w-7xl space-y-6">
            <!-- Header Card with User Photo and Quick Info -->
            <Card class="overflow-hidden">
                <div class="h-32 bg-indigo-500"></div>
                <CardContent class="relative pt-0 pb-6">
                    <div class="flex flex-col md:flex-row md:items-end md:justify-between -mt-16 md:-mt-20">
                        <!-- User Avatar and Basic Info -->
                        <div class="flex flex-col md:flex-row items-center md:items-end space-y-4 md:space-y-0 md:space-x-6">
                            <div class="relative group">
                                <Avatar class="w-32 h-32 md:w-40 md:h-40 border-4 border-white dark:border-gray-950 shadow-xl">
                                    <AvatarImage 
                                        v-if="$page.props.auth.user.photo"
                                        :src="`/storage/${$page.props.auth.user.photo}`" 
                                        :alt="$page.props.auth.user.name"
                                    />
                                    <AvatarFallback class="text-3xl md:text-4xl font-bold bg-indigo-500 text-white">
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
                                <div class="absolute bottom-2 right-2 w-5 h-5 bg-emerald-500 rounded-full border-4 border-white dark:border-gray-950"></div>
                            </div>
                            
                            <div class="text-center md:text-left space-y-2 mb-2">
                                <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white">
                                    {{ $page.props.auth.user.name }}
                                </h1>
                                <div class="flex flex-wrap items-center justify-center md:justify-start gap-3">
                                    <Badge :class="getRoleColor(getUserRole())" class="border">
                                        {{ getUserRole() }}
                                    </Badge>
                                    <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                                        <Mail class="w-4 h-4 mr-1.5" />
                                        {{ $page.props.auth.user.email }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Quick Stats or Action Buttons -->
                        <div class="flex items-center justify-center md:justify-end space-x-2 mt-4 md:mt-0">
                            <Button variant="outline" size="sm" @click="openPhotoModal">
                                <Camera class="w-4 h-4 mr-2" />
                                Change Photo
                            </Button>
                        </div>
                    </div>
                </CardContent>
            </Card>

            <!-- Email Verification Alert -->
            <Card v-if="mustVerifyEmail" class="border-amber-200 dark:border-amber-800 bg-amber-50 dark:bg-amber-900/20">
                <CardContent class="pt-6">
                    <div class="flex items-start space-x-3">
                        <AlertCircle class="w-5 h-5 text-amber-600 dark:text-amber-400 flex-shrink-0 mt-0.5" />
                        <div class="flex-1">
                            <h3 class="text-sm font-medium text-amber-800 dark:text-amber-200">
                                Email Verification Required
                            </h3>
                            <p class="text-sm text-amber-700 dark:text-amber-300 mt-1">
                                Before proceeding, please check your email for a verification link.
                            </p>
                            <p v-if="status === 'verification-link-sent'" class="flex items-center text-sm text-emerald-600 dark:text-emerald-400 mt-2">
                                <CheckCircle class="w-4 h-4 mr-1.5" />
                                A new verification link has been sent to your email address.
                            </p>
                        </div>
                    </div>
                </CardContent>
            </Card>

            <!-- Main Content with Tabs -->
            <Tabs v-model="activeTab" default-value="profile" class="w-full">
                <TabsList class="grid w-full grid-cols-3 lg:w-auto lg:inline-grid">
                    <TabsTrigger value="profile" class="gap-2">
                        <div class="flex items-center justify-center">
                          <UserIcon class="w-4 h-4" />
                        </div>
                        <span class="hidden sm:inline">Profile</span>
                    </TabsTrigger>
                    <TabsTrigger value="security" class="gap-2">
                        <div class="flex items-center justify-center">
                            <Shield class="w-4 h-4" />
                        </div>
                        <span class="hidden sm:inline">Security</span>
                    </TabsTrigger>
                    <TabsTrigger value="settings" class="gap-2">
                        <div class="flex items-center justify-center">
                            <Settings class="w-4 h-4" />
                        </div>
                        <span class="hidden sm:inline">Settings</span>
                    </TabsTrigger>
                </TabsList>

                <!-- Profile Tab -->
                <TabsContent value="profile" class="space-y-6 mt-6">
                    <!-- Profile Information -->
                    <Card>
                        <CardHeader>
                            <CardTitle class="flex items-center gap-2">
                                <UserIcon class="w-5 h-5" />
                                Profile Information
                            </CardTitle>
                            <CardDescription>
                                Update your account's profile information and email address.
                            </CardDescription>
                        </CardHeader>
                        <CardContent>
                            <UpdateProfileInformationForm :must-verify-email="mustVerifyEmail" :status="status" />
                        </CardContent>
                    </Card>
                </TabsContent>

                <!-- Security Tab -->
                <TabsContent value="security" class="space-y-6 mt-6">
                    <!-- Update Password -->
                    <Card>
                        <CardHeader>
                            <CardTitle class="flex items-center gap-2">
                                <Key class="w-5 h-5" />
                                Update Password
                            </CardTitle>
                            <CardDescription>
                                Ensure your account is using a long, random password to stay secure.
                            </CardDescription>
                        </CardHeader>
                        <CardContent>
                            <UpdatePasswordForm />
                        </CardContent>
                    </Card>

                    <!-- Two-Factor Authentication (Placeholder) -->
                    <Card>
                        <CardHeader>
                            <CardTitle class="flex items-center gap-2">
                                <Shield class="w-5 h-5" />
                                Two-Factor Authentication
                            </CardTitle>
                            <CardDescription>
                                Add additional security to your account using two-factor authentication
                            </CardDescription>
                        </CardHeader>
                        <CardContent>
                            <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                                <Shield class="w-12 h-12 mx-auto mb-3 opacity-50" />
                                <p>Two-factor authentication not yet configured</p>
                                <Button variant="link" class="mt-2">Enable 2FA</Button>
                            </div>
                        </CardContent>
                    </Card>
                </TabsContent>

                <!-- Settings Tab -->
                <TabsContent value="settings" class="space-y-6 mt-6">
                    <!-- Delete Account -->
                    <Card class="border-red-200 dark:border-red-800">
                        <CardHeader>
                            <CardTitle class="flex items-center gap-2 text-red-600 dark:text-red-400">
                                <AlertCircle class="w-5 h-5" />
                                Delete Account
                            </CardTitle>
                            <CardDescription>
                                Permanently delete your account. This action cannot be undone.
                            </CardDescription>
                        </CardHeader>
                        <CardContent>
                            <div class="p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg mb-4">
                                <div class="flex items-start gap-3">
                                    <AlertCircle class="w-5 h-5 text-red-600 dark:text-red-400 mt-0.5 flex-shrink-0" />
                                    <div>
                                        <h4 class="font-medium text-red-900 dark:text-red-200 mb-1">
                                            Warning: This action is irreversible
                                        </h4>
                                        <p class="text-sm text-red-700 dark:text-red-300">
                                            Once you delete your account, all of your resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <DeleteUserForm />
                        </CardContent>
                    </Card>
                </TabsContent>
            </Tabs>
        </div>

        <!-- Photo Upload Modal -->
        <Dialog :open="photoModalOpen" @update:open="closePhotoModal">
            <DialogContent class="sm:max-w-md">
                <DialogHeader>
                    <DialogTitle class="flex items-center gap-2">
                        <Camera class="w-5 h-5" />
                        Upload Profile Photo
                    </DialogTitle>
                    <DialogDescription>
                        Upload your profile photo for identification. Passport size recommended (400x500px). Maximum file size: 2MB.
                    </DialogDescription>
                </DialogHeader>
                
                <div class="space-y-4">
                    <!-- Photo Preview -->
                    <div class="flex items-center justify-center">
                        <div class="relative">
                            <Avatar class="w-40 h-40 border-4 border-gray-200 dark:border-gray-800">
                                <AvatarImage 
                                    v-if="photoPreview || $page.props.auth.user.photo"
                                    :src="photoPreview || `/storage/${$page.props.auth.user.photo}`" 
                                    :alt="$page.props.auth.user.name"
                                />
                                <AvatarFallback class="text-4xl font-bold bg-indigo-500 text-white">
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
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Select Photo
                        </label>
                        <div class="border-2 border-dashed border-gray-300 dark:border-gray-700 rounded-lg p-6 text-center hover:border-indigo-500 dark:hover:border-indigo-500 transition-colors">
                            <Upload class="w-12 h-12 mx-auto text-gray-400 mb-3" />
                            <label 
                                for="photo-input" 
                                class="cursor-pointer text-indigo-600 hover:text-indigo-700 dark:text-indigo-400 dark:hover:text-indigo-300 font-medium"
                            >
                                Click to upload
                            </label>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                or drag and drop
                            </p>
                            <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">
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
                    <div v-if="photoFile" class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400 p-3 bg-gray-50 dark:bg-gray-800 rounded-lg">
                        <Camera class="w-4 h-4" />
                        <span class="font-medium">{{ photoFile.name }}</span>
                        <span class="text-xs">({{ (photoFile.size / 1024).toFixed(2) }} KB)</span>
                    </div>

                    <!-- Error Messages -->
                    <div v-if="errors.photo" class="flex items-center gap-2 text-sm text-red-600 dark:text-red-400 p-3 bg-red-50 dark:bg-red-900/20 rounded-lg">
                        <AlertCircle class="w-4 h-4" />
                        {{ errors.photo }}
                    </div>
                </div>

                <DialogFooter>
                    <Button variant="outline" @click="closePhotoModal">
                        Cancel
                    </Button>
                    <Button 
                        @click="uploadPhoto" 
                        :disabled="!photoFile || uploading"
                    >
                        <Upload class="w-4 h-4 mr-2" />
                        {{ uploading ? 'Uploading...' : 'Upload Photo' }}
                    </Button>
                </DialogFooter>
            </DialogContent>
        </Dialog>
    </Sidebar>
</template>