<script setup>
import DeleteUserForm from './Partials/DeleteUserForm.vue';
import UpdatePasswordForm from './Partials/UpdatePasswordForm.vue';
import UpdateProfileInformationForm from './Partials/UpdateProfileInformationForm.vue';
import { Head } from '@inertiajs/vue3';
import Sidebar from '@/Components/Sidebar.vue';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';
import { 
    User,
    Shield,
    Key,
    AlertCircle
} from 'lucide-vue-next';

defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});
</script>

<template>
    <Head title="Profile" />

    <Sidebar>
        <template #header-title>
            <div class="flex items-center space-x-3">
                <User class="w-5 h-5" />
                <span>Profile</span>
            </div>
        </template>

        <div class="mx-auto max-w-7xl space-y-6">
            <!-- Email Verification Alert -->
            <div v-if="mustVerifyEmail" class="mb-6">
                <Card class="border-amber-200 dark:border-amber-800">
                    <CardContent class="p-4">
                        <div class="flex items-start space-x-3">
                            <AlertCircle class="w-5 h-5 text-amber-600 dark:text-amber-400 flex-shrink-0 mt-0.5" />
                            <div class="flex-1">
                                <h3 class="text-sm font-medium text-amber-800 dark:text-amber-200">
                                    Email Verification Required
                                </h3>
                                <p class="text-sm text-amber-700 dark:text-amber-300 mt-1">
                                    Before proceeding, please check your email for a verification link.
                                </p>
                                <p v-if="status === 'verification-link-sent'" class="text-sm text-amber-600 dark:text-amber-400 mt-2">
                                    A new verification link has been sent to your email address.
                                </p>
                            </div>
                        </div>
                    </CardContent>
                </Card>
            </div>

            <!-- Profile Information -->
            <Card>
                <CardHeader>
                    <CardTitle class="flex items-center space-x-2">
                        <User class="w-5 h-5" />
                        <span>Profile Information</span>
                    </CardTitle>
                    <CardDescription>
                        Update your account's profile information and email address.
                    </CardDescription>
                </CardHeader>
                <CardContent>
                    <UpdateProfileInformationForm :must-verify-email="mustVerifyEmail" :status="status" />
                </CardContent>
            </Card>

            <!-- Update Password -->
            <Card>
                <CardHeader>
                    <CardTitle class="flex items-center space-x-2">
                        <Key class="w-5 h-5" />
                        <span>Update Password</span>
                    </CardTitle>
                    <CardDescription>
                        Ensure your account is using a long, random password to stay secure.
                    </CardDescription>
                </CardHeader>
                <CardContent>
                    <UpdatePasswordForm class="mt-6" />
                </CardContent>
            </Card>

            <!-- Delete Account -->
            <Card class="border-red-200 dark:border-red-800">
                <CardHeader>
                    <CardTitle class="flex items-center space-x-2 text-red-600 dark:text-red-400">
                        <Shield class="w-5 h-5" />
                        <span>Delete Account</span>
                    </CardTitle>
                    <CardDescription>
                        Permanently delete your account. This action cannot be undone.
                    </CardDescription>
                </CardHeader>
                <CardContent>
                    <DeleteUserForm class="mt-6" />
                </CardContent>
            </Card>
        </div>
    </Sidebar>
</template>
