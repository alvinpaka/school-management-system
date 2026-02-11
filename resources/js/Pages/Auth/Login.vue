<script setup>
import { Checkbox } from '@/components/ui/checkbox';
import GuestLayout from '@/Layouts/GuestLayout.vue'; // Keeping if your app uses it, but we remove it from template for cleaner structure
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Alert, AlertDescription } from '@/components/ui/alert';
import DarkModeToggle from '@/Components/DarkModeToggle.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const showPassword = ref(false);

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <Head title="Log in - EduManage Pro" />

    <div class="min-h-screen flex bg-gray-50 dark:bg-gray-900">
        <!-- Left Side - Branding & Info (hidden on mobile) -->
        <div class="hidden lg:flex lg:w-1/2 bg-gradient-to-br from-blue-700 via-purple-700 to-pink-700 p-16 flex-col justify-between text-white">
            <div>
                <!-- Logo -->
                <Link href="/" class="flex items-center space-x-4 mb-16">
                    <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center shadow-2xl">
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
                        <h2 class="text-5xl font-bold mb-6">Welcome Back!</h2>
                        <p class="text-xl text-white/80 leading-relaxed">
                            Sign in to access your school management dashboard and continue making education better.
                        </p>
                    </div>

                    <div class="space-y-10">
                        <div class="flex items-start space-x-5">
                            <div class="w-14 h-14 bg-white/10 rounded-xl flex items-center justify-center flex-shrink-0">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-xl font-semibold mb-2">Secure & Compliant</h3>
                                <p class="text-white/80">Bank-level encryption and full data protection</p>
                            </div>
                        </div>

                        <div class="flex items-start space-x-5">
                            <div class="w-14 h-14 bg-white/10 rounded-xl flex items-center justify-center flex-shrink-0">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-xl font-semibold mb-2">Fast & Reliable</h3>
                                <p class="text-white/80">99.9% uptime with lightning-fast performance</p>
                            </div>
                        </div>

                        <div class="flex items-start space-x-5">
                            <div class="w-14 h-14 bg-white/10 rounded-xl flex items-center justify-center flex-shrink-0">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 012-2h8a2 2 0 012 2v6m-6-8V9a2 2 0 00-2-2h-2a2 2 0 00-2 2v2m6 0h4" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-xl font-semibold mb-2">Insightful Analytics</h3>
                                <p class="text-white/80">Real-time reports and performance tracking</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stats -->
            <div class="grid grid-cols-3 gap-6">
                <div class="text-center p-6 rounded-xl bg-white/10 backdrop-blur-sm">
                    <div class="text-4xl font-bold">1000+</div>
                    <div class="text-white/80 text-sm mt-1">Schools</div>
                </div>
                <div class="text-center p-6 rounded-xl bg-white/10 backdrop-blur-sm">
                    <div class="text-4xl font-bold">500k+</div>
                    <div class="text-white/80 text-sm mt-1">Students</div>
                </div>
                <div class="text-center p-6 rounded-xl bg-white/10 backdrop-blur-sm">
                    <div class="text-4xl font-bold">99%</div>
                    <div class="text-white/80 text-sm mt-1">Satisfaction</div>
                </div>
            </div>
        </div>

        <!-- Right Side - Login Form -->
        <div class="w-full lg:w-1/2 flex items-center justify-center p-8 relative">
            <!-- Dark Mode Toggle -->
            <div class="absolute top-8 right-8">
                <DarkModeToggle variant="outline" />
            </div>
            
            <div class="w-full max-w-md">
                <!-- Mobile Logo -->
                <div class="lg:hidden mb-12 text-center">
                    <Link href="/" class="inline-flex items-center space-x-4">
                        <div class="w-14 h-14 bg-gradient-to-br from-blue-700 to-purple-700 rounded-xl flex items-center justify-center shadow-lg">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                        </div>
                        <span class="text-3xl font-bold bg-gradient-to-r from-blue-700 to-purple-700 bg-clip-text text-transparent">
                            EduManage Pro
                        </span>
                    </Link>
                </div>

                <Card class="border-0 shadow-2xl bg-white/90 dark:bg-gray-800/90 backdrop-blur-sm">
                    <CardHeader class="space-y-2 pb-8">
                        <CardTitle class="text-3xl font-bold text-center text-gray-900 dark:text-white">Sign In</CardTitle>
                        <CardDescription class="text-center text-base text-gray-600 dark:text-gray-300">
                            Enter your credentials to access your dashboard
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
                            <!-- Email -->
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
                                <p v-if="form.errors.email" class="text-sm text-red-600 dark:text-red-600 flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    {{ form.errors.email }}
                                </p>
                            </div>

                            <!-- Password -->
                            <div class="space-y-3">
                                <Label for="password">Password</Label>
                                <div class="relative">
                                    <div class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 dark:text-gray-400">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                        </svg>
                                    </div>
                                    <Input
                                        id="password"
                                        :type="showPassword ? 'text' : 'password'"
                                        v-model="form.password"
                                        placeholder="Enter your password"
                                        required
                                        autocomplete="current-password"
                                        class="pl-11 pr-11 h-12 bg-white dark:bg-gray-800 border-gray-300 dark:border-gray-700"
                                        :class="{ 'border-red-500 dark:border-red-500 focus-visible:ring-red-500': form.errors.password }"
                                    />
                                    <button type="button" @click="showPassword = !showPassword" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200">
                                        <svg v-if="!showPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        <svg v-else class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                        </svg>
                                    </button>
                                </div>
                                <p v-if="form.errors.password" class="text-sm text-red-600 dark:text-red-600 flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    {{ form.errors.password }}
                                </p>
                            </div>

                            <!-- Remember & Forgot -->
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-2">
                                    <Checkbox id="remember" v-model:checked="form.remember" />
                                    <Label for="remember" class="text-sm font-medium cursor-pointer">Remember me</Label>
                                </div>
                                <Link v-if="canResetPassword" :href="route('password.request')" class="text-sm font-medium">
                                    Forgot password?
                                </Link>
                            </div>

                            <!-- Submit -->
                            <Button
                                type="submit"
                                class="w-full h-12 text-white font-semibold bg-gradient-to-r from-blue-700 to-purple-700 hover:from-blue-800 hover:to-purple-800 shadow-lg transition-all duration-200"
                                :disabled="form.processing"
                            >
                                <svg v-if="form.processing" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                {{ form.processing ? 'Signing in...' : 'Sign In' }}
                            </Button>
                        </form>
                    </CardContent>
                </Card>

                <!-- Sign Up Section -->
                <div class="mt-6 text-center">
                    <p class="text-gray-600 dark:text-gray-300 mb-4">Don't have an account yet?</p>
                    <Link 
                        :href="route('register')" 
                        class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white font-semibold rounded-lg shadow-lg transition-all duration-200 group"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                        </svg>
                        Create Account
                        <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                        </svg>
                    </Link>
                </div>

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
.bg-clip-text {
    -webkit-background-clip: text;
    background-clip: text;
    -webkit-text-fill-color: transparent;
}
</style>