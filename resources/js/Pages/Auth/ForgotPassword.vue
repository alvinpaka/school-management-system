<script setup>
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Alert, AlertDescription } from '@/components/ui/alert';
import DarkModeToggle from '@/Components/DarkModeToggle.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

defineProps({
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
});

const submit = () => {
    form.post(route('password.email'));
};
</script>

<template>
    <Head title="Forgot Password - EduManage Pro" />

    <div class="min-h-screen flex bg-gray-50 dark:bg-gray-900">
        <!-- Left Side - Branding & Info (hidden on mobile) -->
        <div class="hidden lg:flex lg:w-1/2 bg-indigo-700 p-16 flex-col justify-between text-white relative overflow-hidden">
            <!-- Background Pattern -->
            <div class="absolute inset-0 opacity-10">
                <div class="absolute top-10 left-10 w-32 h-32 bg-white rounded-full"></div>
                <div class="absolute bottom-20 right-20 w-48 h-48 bg-white rounded-full"></div>
                <div class="absolute top-1/2 left-1/4 w-24 h-24 bg-white rounded-full"></div>
            </div>
            
            <div class="relative z-10">
                <!-- Logo -->
                <Link href="/" class="flex items-center space-x-4 mb-16 group">
                    <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center shadow-2xl transform transition-all duration-300 group-hover:scale-110 group-hover:bg-white/30">
                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-4xl font-bold">EduManage Pro</h1>
                        <p class="text-xl text-white/80">School Management Platform</p>
                    </div>
                </Link>

                <!-- Welcome & Features -->
                <div class="space-y-12">
                    <div>
                        <h2 class="text-5xl font-bold mb-6">Forgot Password?</h2>
                        <p class="text-xl text-white/80 leading-relaxed">
                            No worries! We'll send you a password reset link to your email address.
                        </p>
                    </div>

                    <div class="space-y-10">
                        <div class="flex items-start space-x-5 group">
                            <div class="w-14 h-14 bg-white/10 rounded-xl flex items-center justify-center flex-shrink-0 transform transition-all duration-300 group-hover:scale-110 group-hover:bg-white/20">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-xl font-semibold mb-2">Email Delivery</h3>
                                <p class="text-white/80">Instant password reset link sent to your inbox</p>
                            </div>
                        </div>

                        <div class="flex items-start space-x-5 group">
                            <div class="w-14 h-14 bg-white/10 rounded-xl flex items-center justify-center flex-shrink-0 transform transition-all duration-300 group-hover:scale-110 group-hover:bg-white/20">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-xl font-semibold mb-2">Secure Reset</h3>
                                <p class="text-white/80">Safe and secure password recovery process</p>
                            </div>
                        </div>

                        <div class="flex items-start space-x-5 group">
                            <div class="w-14 h-14 bg-white/10 rounded-xl flex items-center justify-center flex-shrink-0 transform transition-all duration-300 group-hover:scale-110 group-hover:bg-white/20">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-xl font-semibold mb-2">Quick Access</h3>
                                <p class="text-white/80">Get back to your account in minutes</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stats -->
            <div class="grid grid-cols-3 gap-6 relative z-10">
                <div class="text-center p-6 rounded-xl bg-white/10 backdrop-blur-sm transform transition-all duration-300 hover:scale-105 hover:bg-white/20">
                    <div class="text-4xl font-bold">1000+</div>
                    <div class="text-white/80 text-sm mt-1">Schools</div>
                </div>
                <div class="text-center p-6 rounded-xl bg-white/10 backdrop-blur-sm transform transition-all duration-300 hover:scale-105 hover:bg-white/20">
                    <div class="text-4xl font-bold">500k+</div>
                    <div class="text-white/80 text-sm mt-1">Students</div>
                </div>
                <div class="text-center p-6 rounded-xl bg-white/10 backdrop-blur-sm transform transition-all duration-300 hover:scale-105 hover:bg-white/20">
                    <div class="text-4xl font-bold">99%</div>
                    <div class="text-white/80 text-sm mt-1">Satisfaction</div>
                </div>
            </div>
        </div>

        <!-- Right Side - Forgot Password Form -->
        <div class="w-full lg:w-1/2 flex items-center justify-center p-8 relative">
            <!-- Dark Mode Toggle -->
            <div class="absolute top-8 right-8">
                <DarkModeToggle variant="outline" />
            </div>
            
            <div class="w-full max-w-md">
                <!-- Mobile Logo -->
                <div class="lg:hidden mb-12 text-center">
                    <Link href="/" class="inline-flex items-center space-x-4 group">
                        <div class="w-14 h-14 bg-indigo-700 rounded-xl flex items-center justify-center shadow-lg transform transition-all duration-300 group-hover:scale-110 group-hover:bg-indigo-800">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                        </div>
                        <span class="text-3xl font-bold text-indigo-700">
                            EduManage Pro
                        </span>
                    </Link>
                </div>

                <Card class="border-0 shadow-2xl bg-white/90 dark:bg-gray-800/90 backdrop-blur-sm">
                    <CardHeader class="space-y-2 pb-8">
                        <CardTitle class="text-3xl font-bold text-center text-gray-900 dark:text-white">Forgot Password</CardTitle>
                        <CardDescription class="text-center text-base text-gray-600 dark:text-gray-300">
                            Enter your email address and we'll send you a link to reset your password
                        </CardDescription>
                    </CardHeader>

                    <CardContent class="space-y-8">
                        <!-- Status Message -->
                        <Alert v-if="status" class="bg-green-50 dark:bg-green-900/20 border-green-200 dark:border-green-800">
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <AlertDescription class="text-green-800 dark:text-green-200">
                                {{ status }}
                            </AlertDescription>
                        </Alert>

                        <form @submit.prevent="submit" class="space-y-7">
                            <!-- Email Field -->
                            <div class="space-y-3">
                                <Label for="email">Email Address</Label>
                                <div class="relative">
                                    <div class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 dark:text-gray-400">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                                        </svg>
                                    </div>
                                    <Input
                                        id="email"
                                        type="email"
                                        v-model="form.email"
                                        placeholder="name@school.com"
                                        required
                                        autofocus
                                        autocomplete="username"
                                        class="pl-11 h-12 bg-white dark:bg-gray-800 border-gray-300 dark:border-gray-700"
                                        :class="{ 'border-red-500 dark:border-red-500 focus-visible:ring-red-500': form.errors.email }"
                                    />
                                </div>
                                <p v-if="form.errors.email" class="text-sm text-red-600 flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    {{ form.errors.email }}
                                </p>
                            </div>

                            <!-- Submit Button -->
                            <Button
                                type="submit"
                                class="w-full h-12 text-white font-semibold bg-indigo-700 hover:bg-indigo-800 shadow-lg transition-all duration-200 hover:shadow-xl transform hover:scale-105"
                                :disabled="form.processing"
                            >
                                <svg v-if="form.processing" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                {{ form.processing ? 'Sending...' : 'Email Password Reset Link' }}
                            </Button>
                        </form>

                        <!-- Back to Login -->
                        <div class="text-center">
                            <Link 
                                :href="route('login')" 
                                class="inline-flex items-center gap-2"
                            >
                                Back to sign in
                                <svg class="w-5 h-5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                </svg>
                            </Link>
                        </div>
                    </CardContent>
                </Card>

                <!-- Back to Homepage -->
                <div class="mt-8 text-center">
                    <Link href="/" class="text-sm text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white inline-flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Back to homepage
                    </Link>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
/* Custom animations */
@keyframes float {
    0%, 100% {
        transform: translateY(0px);
    }
    50% {
        transform: translateY(-10px);
    }
}

@keyframes pulse-slow {
    0%, 100% {
        opacity: 0.1;
    }
    50% {
        opacity: 0.2;
    }
}

/* Floating background elements */
.absolute .bg-white {
    animation: float 6s ease-in-out infinite;
}

.absolute .bg-white:nth-child(2) {
    animation-delay: 2s;
}

.absolute .bg-white:nth-child(3) {
    animation-delay: 4s;
}

/* Enhanced transitions */
* {
    transition-property: color, background-color, border-color, transform, box-shadow;
    transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
    transition-duration: 300ms;
}

/* Card hover effects */
.transform:hover {
    transform: translateY(-2px);
}

/* Custom scrollbar */
::-webkit-scrollbar {
    width: 8px;
}

::-webkit-scrollbar-track {
    background: #f1f1f1;
}

::-webkit-scrollbar-thumb {
    background: #888;
    border-radius: 4px;
}

::-webkit-scrollbar-thumb:hover {
    background: #555;
}
</style>
