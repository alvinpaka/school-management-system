<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';

defineProps({
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
    laravelVersion: {
        type: String,
        required: true,
    },
    phpVersion: {
        type: String,
        required: true,
    },
});

const isScrolled = ref(false);
const activeFeature = ref(0);

const features = [
    {
        title: 'Student Management',
        description: 'Comprehensive student profiles, enrollment tracking, document management, and parent communication.',
        icon: 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z',
        color: 'from-blue-500 to-cyan-500'
    },
    {
        title: 'Attendance Tracking',
        description: 'Real-time attendance recording with automated reports, alerts, and detailed analytics.',
        icon: 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4',
        color: 'from-green-500 to-emerald-500'
    },
    {
        title: 'Grade Management',
        description: 'Easy grade entry, automatic GPA calculation, report card generation, and analytics.',
        icon: 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z',
        color: 'from-purple-500 to-violet-500'
    }
];

onMounted(() => {
    window.addEventListener('scroll', () => {
        isScrolled.value = window.scrollY > 50;
    });

    // Auto rotate features
    setInterval(() => {
        activeFeature.value = (activeFeature.value + 1) % features.length;
    }, 4000);
});
</script>

<template>
    <Head title="Welcome - School Management System" />

    <div class="min-h-screen bg-gradient-to-br from-gray-50 via-blue-50/50 to-purple-50/50 dark:from-gray-900 dark:via-gray-900 dark:to-purple-900/20 overflow-hidden">
        <!-- Animated background elements -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="absolute -top-40 -right-40 w-80 h-80 bg-purple-300 rounded-full mix-blend-multiply filter blur-3xl opacity-20 dark:opacity-10 animate-pulse"></div>
            <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-blue-300 rounded-full mix-blend-multiply filter blur-3xl opacity-20 dark:opacity-10 animate-pulse delay-1000"></div>
            <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-96 h-96 bg-cyan-300 rounded-full mix-blend-multiply filter blur-3xl opacity-10 dark:opacity-5 animate-pulse delay-500"></div>
        </div>

        <!-- Header -->
        <header 
            :class="[
                'sticky top-0 z-50 transition-all duration-300',
                isScrolled ? 'bg-white/90 dark:bg-gray-900/90 backdrop-blur-xl shadow-lg' : 'bg-transparent'
            ]"
        >
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center py-4 md:py-6">
                    <!-- Logo and Brand -->
                    <Link href="/" class="flex items-center space-x-3 group">
                        <div class="relative">
                            <div class="absolute inset-0 bg-gradient-to-r from-indigo-600 to-purple-600 rounded-2xl blur-lg opacity-70 group-hover:opacity-90 transition-opacity"></div>
                            <div class="relative bg-gradient-to-br from-indigo-600 to-purple-600 p-3 rounded-xl shadow-xl">
                                <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                </svg>
                            </div>
                        </div>
                        <div>
                            <h1 class="text-2xl font-bold bg-gradient-to-r from-gray-900 to-gray-700 dark:from-white dark:to-gray-300 bg-clip-text text-transparent">
                                EduManage Pro
                            </h1>
                            <p class="text-xs text-gray-600 dark:text-gray-400 font-medium tracking-wide">Next-Gen School Management</p>
                        </div>
                    </Link>

                    <!-- Navigation -->
                    <nav v-if="canLogin" class="flex items-center space-x-2">
                        <Link
                            v-if="$page.props.auth.user"
                            :href="route('dashboard')"
                            class="group relative inline-flex items-center px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-300 overflow-hidden"
                        >
                            <span class="absolute inset-0 bg-gradient-to-r from-purple-600 to-indigo-600 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></span>
                            <span class="relative flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                                </svg>
                                Dashboard
                            </span>
                        </Link>
                        <template v-else>
                            <Link
                                :href="route('login')"
                                class="px-6 py-3 text-gray-700 dark:text-gray-200 font-medium rounded-xl hover:bg-gray-100/50 dark:hover:bg-gray-800/50 transition-all duration-300 backdrop-blur-sm"
                            >
                                Log in
                            </Link>
                            <Link
                                v-if="canRegister"
                                :href="route('register')"
                                class="group relative inline-flex items-center px-8 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-semibold rounded-xl shadow-xl hover:shadow-2xl transform hover:-translate-y-0.5 hover:scale-[1.02] transition-all duration-300 overflow-hidden"
                            >
                                <span class="absolute inset-0 bg-gradient-to-r from-purple-600 to-pink-600 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></span>
                                <span class="relative flex items-center">
                                    Get Started
                                    <svg class="w-5 h-5 ml-2 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                                    </svg>
                                </span>
                            </Link>
                        </template>
                    </nav>
                </div>
            </div>
        </header>

        <!-- Hero Section -->
        <section class="relative py-20 md:py-32 overflow-hidden">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
                <div class="text-center mb-20">
                    <div class="inline-flex items-center px-4 py-2 rounded-full bg-gradient-to-r from-indigo-100 to-purple-100 dark:from-indigo-900/30 dark:to-purple-900/30 mb-6">
                        <span class="text-sm font-semibold text-indigo-600 dark:text-indigo-400">
                            Trusted by 500+ Schools Worldwide
                        </span>
                    </div>
                    
                    <h1 class="text-5xl md:text-7xl font-bold mb-8 leading-tight">
                        <span class="block text-gray-900 dark:text-white mb-2">Revolutionizing</span>
                        <span class="bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600 bg-clip-text text-transparent">
                            School Management
                        </span>
                    </h1>
                    
                    <p class="text-xl md:text-2xl text-gray-600 dark:text-gray-300 max-w-3xl mx-auto mb-10 leading-relaxed">
                        An all-in-one platform powered by AI to automate school operations, enhance learning outcomes, and simplify administration.
                    </p>
                    
                    <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
                        <Link
                            v-if="!$page.props.auth.user"
                            :href="route('register')"
                            class="group relative inline-flex items-center px-10 py-4 bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-bold rounded-2xl shadow-2xl hover:shadow-3xl transform hover:-translate-y-1 hover:scale-[1.02] transition-all duration-300 overflow-hidden"
                        >
                            <span class="absolute inset-0 bg-gradient-to-r from-purple-600 to-pink-600 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></span>
                            <span class="relative flex items-center text-lg">
                                Start Free Trial
                                <svg class="w-6 h-6 ml-3 transform group-hover:translate-x-2 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                                </svg>
                            </span>
                        </Link>
                        
                        <a
                            href="#features"
                            class="inline-flex items-center px-10 py-4 bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm text-gray-800 dark:text-gray-200 font-bold rounded-2xl shadow-xl hover:shadow-2xl transform hover:-translate-y-1 transition-all duration-300 group"
                        >
                            <svg class="w-6 h-6 mr-3 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 10v4a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Watch Demo
                        </a>
                    </div>
                </div>

                <!-- Interactive Dashboard Preview -->
                <div class="relative max-w-6xl mx-auto">
                    <div class="absolute inset-0 bg-gradient-to-r from-indigo-500/20 to-purple-500/20 rounded-3xl blur-3xl"></div>
                    <div class="relative bg-gradient-to-br from-white/80 to-gray-50/80 dark:from-gray-900/80 dark:to-gray-800/80 backdrop-blur-xl rounded-2xl shadow-2xl overflow-hidden border border-white/20 dark:border-gray-700/50">
                        <div class="p-8">
                            <div class="flex items-center space-x-4 mb-6">
                                <div class="flex space-x-2">
                                    <div class="w-3 h-3 rounded-full bg-red-400"></div>
                                    <div class="w-3 h-3 rounded-full bg-yellow-400"></div>
                                    <div class="w-3 h-3 rounded-full bg-green-400"></div>
                                </div>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                <div>
                                    <div class="h-48 bg-gradient-to-br from-blue-50 to-cyan-50 dark:from-blue-900/20 dark:to-cyan-900/20 rounded-xl p-6 mb-4">
                                        <div class="animate-pulse">
                                            <div class="h-4 bg-blue-200 dark:bg-blue-700 rounded w-3/4 mb-4"></div>
                                            <div class="h-4 bg-blue-200 dark:bg-blue-700 rounded w-1/2 mb-4"></div>
                                            <div class="h-32 bg-gradient-to-r from-blue-100 to-cyan-100 dark:from-blue-800/30 dark:to-cyan-800/30 rounded-lg"></div>
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    <div class="h-48 bg-gradient-to-br from-purple-50 to-pink-50 dark:from-purple-900/20 dark:to-pink-900/20 rounded-xl p-6">
                                        <div class="animate-pulse">
                                            <div class="h-4 bg-purple-200 dark:bg-purple-700 rounded w-3/4 mb-4"></div>
                                            <div class="h-4 bg-purple-200 dark:bg-purple-700 rounded w-1/2 mb-4"></div>
                                            <div class="grid grid-cols-3 gap-4">
                                                <div class="h-8 bg-purple-100 dark:bg-purple-800 rounded"></div>
                                                <div class="h-8 bg-purple-100 dark:bg-purple-800 rounded"></div>
                                                <div class="h-8 bg-purple-100 dark:bg-purple-800 rounded"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Features Showcase -->
        <section id="features" class="py-24 relative">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-20">
                    <h2 class="text-4xl md:text-5xl font-bold text-gray-900 dark:text-white mb-6">
                        <span class="bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent">
                            Intelligent Features
                        </span>
                        That Scale With You
                    </h2>
                    <p class="text-xl text-gray-600 dark:text-gray-300 max-w-3xl mx-auto">
                        From small private schools to large districts, our platform adapts to your needs
                    </p>
                </div>

                <!-- Interactive Feature Carousel -->
                <div class="grid lg:grid-cols-2 gap-12 items-center mb-20">
                    <div>
                        <div class="space-y-8">
                            <div 
                                v-for="(feature, index) in features" 
                                :key="index"
                                @mouseenter="activeFeature = index"
                                :class="[
                                    'p-6 rounded-2xl transition-all duration-500 cursor-pointer',
                                    activeFeature === index 
                                        ? 'bg-gradient-to-r from-white to-gray-50 dark:from-gray-800 dark:to-gray-900 shadow-2xl transform -translate-y-1 border-l-4 border-indigo-500' 
                                        : 'bg-white/50 dark:bg-gray-800/50 shadow-lg hover:shadow-xl'
                                ]"
                            >
                                <div class="flex items-start space-x-4">
                                    <div :class="`p-3 rounded-xl bg-gradient-to-br ${feature.color}`">
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="feature.icon"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">
                                            {{ feature.title }}
                                        </h3>
                                        <p class="text-gray-600 dark:text-gray-300">
                                            {{ feature.description }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Animated Feature Display -->
                    <div class="relative h-[400px]">
                        <div class="absolute inset-0 bg-gradient-to-br from-indigo-500/10 to-purple-500/10 rounded-3xl blur-3xl"></div>
                        <div class="relative h-full flex items-center justify-center">
                            <div class="relative w-64 h-64">
                                <div 
                                    v-for="(feature, index) in features" 
                                    :key="index"
                                    v-show="activeFeature === index"
                                    class="absolute inset-0 flex items-center justify-center"
                                >
                                    <div :class="`w-48 h-48 bg-gradient-to-br ${feature.color} rounded-3xl shadow-2xl flex items-center justify-center transform transition-all duration-1000 ${activeFeature === index ? 'scale-100 opacity-100' : 'scale-90 opacity-0'}`">
                                        <svg class="w-24 h-24 text-white opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" :d="feature.icon"/>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Feature Grid -->
                <div class="grid md:grid-cols-3 gap-8">
                    <div class="group bg-gradient-to-br from-white to-gray-50 dark:from-gray-800 dark:to-gray-900 rounded-2xl p-8 shadow-xl hover:shadow-2xl transform hover:-translate-y-2 transition-all duration-300">
                        <div class="inline-flex p-4 rounded-xl bg-gradient-to-br from-blue-500 to-cyan-500 mb-6 group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <h4 class="text-2xl font-bold text-gray-900 dark:text-white mb-3">Smart Scheduling</h4>
                        <p class="text-gray-600 dark:text-gray-300 mb-4">
                            AI-powered timetable generation that considers teacher availability, room capacity, and student preferences.
                        </p>
                        <div class="flex items-center text-indigo-600 dark:text-indigo-400 font-medium">
                            <span>Learn more</span>
                            <svg class="w-5 h-5 ml-2 transform group-hover:translate-x-2 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                            </svg>
                        </div>
                    </div>

                    <div class="group bg-gradient-to-br from-white to-gray-50 dark:from-gray-800 dark:to-gray-900 rounded-2xl p-8 shadow-xl hover:shadow-2xl transform hover:-translate-y-2 transition-all duration-300">
                        <div class="inline-flex p-4 rounded-xl bg-gradient-to-br from-purple-500 to-pink-500 mb-6 group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                            </svg>
                        </div>
                        <h4 class="text-2xl font-bold text-gray-900 dark:text-white mb-3">Communication Hub</h4>
                        <p class="text-gray-600 dark:text-gray-300 mb-4">
                            Integrated messaging, announcements, and notifications with translation support for diverse communities.
                        </p>
                        <div class="flex items-center text-indigo-600 dark:text-indigo-400 font-medium">
                            <span>Learn more</span>
                            <svg class="w-5 h-5 ml-2 transform group-hover:translate-x-2 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                            </svg>
                        </div>
                    </div>

                    <div class="group bg-gradient-to-br from-white to-gray-50 dark:from-gray-800 dark:to-gray-900 rounded-2xl p-8 shadow-xl hover:shadow-2xl transform hover:-translate-y-2 transition-all duration-300">
                        <div class="inline-flex p-4 rounded-xl bg-gradient-to-br from-green-500 to-emerald-500 mb-6 group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <h4 class="text-2xl font-bold text-gray-900 dark:text-white mb-3">Financial Suite</h4>
                        <p class="text-gray-600 dark:text-gray-300 mb-4">
                            Automated billing, payment tracking, scholarship management, and comprehensive financial reporting.
                        </p>
                        <div class="flex items-center text-indigo-600 dark:text-indigo-400 font-medium">
                            <span>Learn more</span>
                            <svg class="w-5 h-5 ml-2 transform group-hover:translate-x-2 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Testimonials -->
        <section class="py-24 bg-gradient-to-br from-gray-50 to-blue-50/50 dark:from-gray-900 dark:to-blue-900/20">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16">
                    <h3 class="text-4xl font-bold text-gray-900 dark:text-white mb-4">
                        Loved by Educational Institutions
                    </h3>
                    <p class="text-xl text-gray-600 dark:text-gray-300">
                        See what our customers say about us
                    </p>
                </div>
                
                <div class="grid md:grid-cols-3 gap-8">
                    <div class="bg-white dark:bg-gray-800 rounded-2xl p-8 shadow-xl">
                        <div class="flex items-center mb-6">
                            <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-cyan-500 rounded-full flex items-center justify-center text-white font-bold text-lg">
                                JS
                            </div>
                            <div class="ml-4">
                                <h4 class="font-bold text-gray-900 dark:text-white">Jane Smith</h4>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Principal, Lincoln High</p>
                            </div>
                        </div>
                        <p class="text-gray-700 dark:text-gray-300 italic">
                            "EduManage Pro reduced our administrative workload by 70%. The AI-powered scheduling alone saved us 20 hours per week."
                        </p>
                    </div>
                    
                    <div class="bg-white dark:bg-gray-800 rounded-2xl p-8 shadow-xl transform md:-translate-y-4">
                        <div class="flex items-center mb-6">
                            <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-pink-500 rounded-full flex items-center justify-center text-white font-bold text-lg">
                                MR
                            </div>
                            <div class="ml-4">
                                <h4 class="font-bold text-gray-900 dark:text-white">Michael Roberts</h4>
                                <p class="text-sm text-gray-600 dark:text-gray-400">IT Director, Springfield District</p>
                            </div>
                        </div>
                        <p class="text-gray-700 dark:text-gray-300 italic">
                            "Implementation was seamless. The support team was exceptional and the platform scales perfectly with our district's growth."
                        </p>
                    </div>
                    
                    <div class="bg-white dark:bg-gray-800 rounded-2xl p-8 shadow-xl">
                        <div class="flex items-center mb-6">
                            <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-emerald-500 rounded-full flex items-center justify-center text-white font-bold text-lg">
                                SC
                            </div>
                            <div class="ml-4">
                                <h4 class="font-bold text-gray-900 dark:text-white">Sarah Chen</h4>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Registrar, Oakwood Academy</p>
                            </div>
                        </div>
                        <p class="text-gray-700 dark:text-gray-300 italic">
                            "Parent engagement increased by 40% since we started using the communication hub. It's transformed how we connect with families."
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA Section -->
        <section class="py-24 relative overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-br from-indigo-600 via-purple-600 to-pink-600"></div>
            <div class="absolute inset-0 bg-[url('data:image/svg+xml;charset=UTF-8,%3csvg xmlns=%27http://www.w3.org/2000/svg%27 width=%2760%27 height=%2760%27 viewBox=%270 0 60 60%27%3e%3cg fill=%27none%27 fill-rule=%27evenodd%27%3e%3cg fill=%27%23ffffff%27 fill-opacity=%270.05%27%3e%3cpath d=%27M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z%27/%3e%3c/g%3e%3c/svg%3e%27')]"></div>
            
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10">
                <h3 class="text-4xl md:text-5xl font-bold text-white mb-6">
                    Ready to Transform Your School?
                </h3>
                <p class="text-xl text-indigo-100 mb-8 max-w-2xl mx-auto">
                    Join thousands of educational institutions using EduManage Pro to streamline their operations and enhance learning experiences.
                </p>
                
                <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
                    <Link
                        v-if="!$page.props.auth.user"
                        :href="route('register')"
                        class="group inline-flex items-center px-10 py-4 bg-white text-indigo-600 font-bold rounded-2xl shadow-2xl hover:shadow-3xl transform hover:-translate-y-1 hover:scale-[1.02] transition-all duration-300"
                    >
                        <span class="relative">
                            Start 30-Day Free Trial
                            <svg class="w-6 h-6 ml-3 inline transform group-hover:translate-x-2 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                            </svg>
                        </span>
                    </Link>
                    
                    <a href="#" class="inline-flex items-center px-10 py-4 bg-white/10 backdrop-blur-sm text-white font-bold rounded-2xl border-2 border-white/30 hover:bg-white/20 transition-all duration-300">
                        Schedule a Demo
                    </a>
                </div>
                
                <p class="text-indigo-200 mt-6 text-sm">
                    No credit card required • Cancel anytime • 24/7 Support
                </p>
            </div>
        </section>

        <!-- Footer -->
        <footer class="pt-16 pb-8 bg-gray-900 text-gray-400 relative overflow-hidden">
            <div class="absolute inset-0 bg-[url('data:image/svg+xml;charset=UTF-8,%3csvg xmlns=%27http://www.w3.org/2000/svg%27 width=%2720%27 height=%2720%27 viewBox=%270 0 20 20%27%3e%3cg fill=%27%239C92AC%27 fill-opacity=%270.05%27 fill-rule=%27evenodd%27%3e%3ccircle cx=%273%27 cy=%273%27 r=%273%27/%3e%3ccircle cx=%2713%27 cy=%2713%27 r=%273%27/%3e%3c/g%3e%3c/svg%3e%27')]"></div>
            
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
                <div class="grid md:grid-cols-2 lg:grid-cols-5 gap-12 mb-12">
                    <div class="lg:col-span-2">
                        <div class="flex items-center space-x-3 mb-6">
                            <div class="bg-gradient-to-br from-indigo-600 to-purple-600 p-3 rounded-xl">
                                <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-2xl font-bold text-white">EduManage Pro</h2>
                                <p class="text-sm mt-1">Next-Generation School Management</p>
                            </div>
                        </div>
                        <p class="text-gray-400 max-w-md">
                            Empowering educational institutions with cutting-edge technology to create better learning environments and streamline administrative processes.
                        </p>
                    </div>
                    
                    <div>
                        <h5 class="text-white font-bold mb-6 text-lg">Platform</h5>
                        <ul class="space-y-3">
                            <li><a href="#" class="hover:text-white transition-colors duration-200">Features</a></li>
                            <li><a href="#" class="hover:text-white transition-colors duration-200">Pricing</a></li>
                            <li><a href="#" class="hover:text-white transition-colors duration-200">API</a></li>
                            <li><a href="#" class="hover:text-white transition-colors duration-200">Documentation</a></li>
                        </ul>
                    </div>
                    
                    <div>
                        <h5 class="text-white font-bold mb-6 text-lg">Resources</h5>
                        <ul class="space-y-3">
                            <li><a href="#" class="hover:text-white transition-colors duration-200">Blog</a></li>
                            <li><a href="#" class="hover:text-white transition-colors duration-200">Webinars</a></li>
                            <li><a href="#" class="hover:text-white transition-colors duration-200">Help Center</a></li>
                            <li><a href="#" class="hover:text-white transition-colors duration-200">Community</a></li>
                        </ul>
                    </div>
                    
                    <div>
                        <h5 class="text-white font-bold mb-6 text-lg">Company</h5>
                        <ul class="space-y-3">
                            <li><a href="#" class="hover:text-white transition-colors duration-200">About Us</a></li>
                            <li><a href="#" class="hover:text-white transition-colors duration-200">Careers</a></li>
                            <li><a href="#" class="hover:text-white transition-colors duration-200">Contact</a></li>
                            <li><a href="#" class="hover:text-white transition-colors duration-200">Partners</a></li>
                        </ul>
                    </div>
                </div>
                
                <div class="border-t border-gray-800 pt-8">
                    <div class="flex flex-col md:flex-row justify-between items-center space-y-4 md:space-y-0">
                        <p class="text-sm">
                            &copy; 2024 EduManage Pro. All rights reserved.<br>
                            Built with Laravel v{{ laravelVersion }} (PHP v{{ phpVersion }})
                        </p>
                        <div class="flex items-center space-x-6">
                            <a href="#" class="hover:text-white transition-colors duration-200">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/>
                                </svg>
                            </a>
                            <a href="#" class="hover:text-white transition-colors duration-200">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/>
                                </svg>
                            </a>
                            <a href="#" class="hover:text-white transition-colors duration-200">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
    </div>
</template>