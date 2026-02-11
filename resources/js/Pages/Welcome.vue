<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { ref, onMounted, onUnmounted, computed } from 'vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardFooter, CardHeader, CardTitle } from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';
import DarkModeToggle from '@/Components/DarkModeToggle.vue';
import { useTheme } from '@/composables/useTheme';

defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
    laravelVersion: String,
    phpVersion: String,
});

const isScrolled = ref(false);
const particles = ref([]);
const activeTestimonial = ref(0);
const mobileMenuOpen = ref(false);

// Initialize theme
const { updateDocumentClass } = useTheme();
if (typeof document !== 'undefined') {
    updateDocumentClass();
}

const handleScroll = () => {
    isScrolled.value = window.scrollY > 20;
};

const createParticle = () => {
    return {
        id: Math.random(),
        x: Math.random() * (typeof window !== 'undefined' ? window.innerWidth : 1920),
        y: Math.random() * (typeof window !== 'undefined' ? window.innerHeight : 1080),
        size: Math.random() * 4 + 2,
        speedX: (Math.random() - 0.5) * 0.5,
        speedY: (Math.random() - 0.5) * 0.5,
        opacity: Math.random() * 0.4 + 0.2
    };
};

const animateParticles = () => {
    particles.value = particles.value.map(particle => {
        particle.x += particle.speedX;
        particle.y += particle.speedY;
        
        if (particle.x < 0 || particle.x > window.innerWidth) particle.speedX *= -1;
        if (particle.y < 0 || particle.y > window.innerHeight) particle.speedY *= -1;
        
        return particle;
    });
};

const testimonials = [
    {
        name: "Sarah Johnson",
        role: "Principal, Riverside Academy",
        image: "👩‍💼",
        quote: "EduManage Pro transformed how we run our school. We've saved 20+ hours per week on administrative tasks.",
        rating: 5
    },
    {
        name: "Michael Chen",
        role: "Director, Tech Valley School",
        image: "👨‍💼",
        quote: "The attendance and grading features are incredible. Parents love the real-time updates!",
        rating: 5
    },
    {
        name: "Emily Rodriguez",
        role: "Administrator, Green Hills Academy",
        image: "👩‍🏫",
        quote: "Best investment we've made. The ROI was evident within the first month of implementation.",
        rating: 5
    }
];

const features = [
    {
        icon: "M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z",
        title: "Student Management",
        description: "Centralized student profiles, document storage, enrollment tracking, and seamless parent communication.",
        color: "bg-gradient-to-br from-blue-500 to-blue-700"
    },
    {
        icon: "M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z",
        title: "Attendance & Grades",
        description: "Real-time attendance tracking, smart grade management, automated GPA calculations, and instant report generation.",
        color: "bg-gradient-to-br from-green-500 to-green-700"
    },
    {
        icon: "M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z",
        title: "Finance & Billing",
        description: "Automated fee collection, payment tracking, professional invoicing, and comprehensive financial reporting.",
        color: "bg-gradient-to-br from-purple-500 to-purple-700"
    },
    {
        icon: "M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z",
        title: "Smart Scheduling",
        description: "Intelligent timetable management, automated class scheduling, and conflict-free calendar planning.",
        color: "bg-gradient-to-br from-orange-500 to-orange-700"
    },
    {
        icon: "M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9",
        title: "Communication Hub",
        description: "Instant messaging, announcements, email integration, and multi-channel parent-teacher communication.",
        color: "bg-gradient-to-br from-pink-500 to-pink-700"
    },
    {
        icon: "M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z",
        title: "Reports & Analytics",
        description: "Powerful insights, customizable reports, data visualization, and performance tracking dashboards.",
        color: "bg-gradient-to-br from-indigo-500 to-indigo-700"
    }
];

const nextTestimonial = () => {
    activeTestimonial.value = (activeTestimonial.value + 1) % testimonials.length;
};

const prevTestimonial = () => {
    activeTestimonial.value = activeTestimonial.value === 0 ? testimonials.length - 1 : activeTestimonial.value - 1;
};

onMounted(() => {
    window.addEventListener('scroll', handleScroll);
    
    // Initialize particles
    for (let i = 0; i < 30; i++) {
        particles.value.push(createParticle());
    }
    
    let animationId;
    const animate = () => {
        animateParticles();
        animationId = requestAnimationFrame(animate);
    };
    animate();
    
    // Auto-rotate testimonials
    const testimonialInterval = setInterval(() => {
        nextTestimonial();
    }, 5000);
    
    onUnmounted(() => {
        cancelAnimationFrame(animationId);
        clearInterval(testimonialInterval);
        window.removeEventListener('scroll', handleScroll);
    });
});
</script>

<template>
<Head title="EduManage Pro - Transform Your School Management" />

<div class="min-h-screen bg-gradient-to-br from-slate-50 to-blue-50 dark:from-slate-950 dark:to-blue-950 text-foreground relative overflow-hidden transition-colors duration-300">
    
    <!-- Animated Background Particles -->
    <div class="fixed inset-0 pointer-events-none overflow-hidden">
        <div 
            v-for="particle in particles" 
            :key="particle.id"
            class="absolute rounded-full bg-gradient-to-r from-blue-400 to-purple-400 dark:from-blue-600 dark:to-purple-600 blur-sm"
            :style="{
                left: particle.x + 'px',
                top: particle.y + 'px',
                width: particle.size + 'px',
                height: particle.size + 'px',
                opacity: particle.opacity
            }"
        />
    </div>
    
    <!-- Gradient mesh overlay -->
    <div class="fixed inset-0 pointer-events-none bg-gradient-to-br from-blue-500/5 via-purple-500/5 to-pink-500/5 dark:from-blue-500/10 dark:via-purple-500/10 dark:to-pink-500/10" />
    
    <div class="relative z-10">

    <!-- ================= NAVBAR ================= -->
    <header
        :class="[
            'fixed w-full z-50 transition-all duration-500',
            isScrolled 
                ? 'bg-white/80 dark:bg-slate-900/80 backdrop-blur-xl border-b border-gray-200 dark:border-gray-800 shadow-lg shadow-blue-500/5' 
                : 'bg-transparent border-b border-transparent'
        ]"
    >
        <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
            <div class="flex items-center space-x-3 group cursor-pointer">
                <div class="w-12 h-12 bg-gradient-to-br from-blue-600 to-purple-600 rounded-2xl flex items-center justify-center shadow-lg shadow-blue-500/30 group-hover:shadow-blue-500/50 transition-all duration-300 group-hover:scale-110">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                </div>
                <div>
                    <h1 class="text-2xl font-bold bg-gradient-to-r from-blue-600 to-purple-600 dark:from-blue-400 dark:to-purple-400 bg-clip-text text-transparent">
                        EduManage Pro
                    </h1>
                    <p class="text-xs text-blue-600 dark:text-blue-400 font-medium">School Management Platform</p>
                </div>
            </div>

            <!-- Desktop Navigation -->
            <nav v-if="canLogin" class="hidden lg:flex items-center space-x-8">
                <a href="#features" class="text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 font-medium transition-colors duration-200">Features</a>
                <a href="#pricing" class="text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 font-medium transition-colors duration-200">Pricing</a>
                <a href="#testimonials" class="text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 font-medium transition-colors duration-200">Testimonials</a>

                <DarkModeToggle variant="ghost" />

                <Button
                    v-if="$page.props.auth.user"
                    class="bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white shadow-lg shadow-blue-500/30 hover:shadow-blue-500/50 transition-all duration-300"
                    as-child
                >
                    <Link :href="route('dashboard')">
                        Dashboard
                    </Link>
                </Button>

                <template v-else>
                    <Button variant="ghost" class="text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400" as-child>
                        <Link :href="route('login')">
                            Log in
                        </Link>
                    </Button>

                    <Button v-if="canRegister" class="bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white shadow-lg shadow-blue-500/30 hover:shadow-blue-500/50 transition-all duration-300" as-child>
                        <Link :href="route('register')">
                            Start Free Trial
                        </Link>
                    </Button>
                </template>
            </nav>

            <!-- Mobile Menu Button -->
            <button 
                v-if="canLogin"
                @click="mobileMenuOpen = !mobileMenuOpen"
                class="lg:hidden p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors"
            >
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path v-if="!mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    <path v-else stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Mobile Menu -->
        <div 
            v-if="mobileMenuOpen && canLogin"
            class="lg:hidden border-t border-gray-200 dark:border-gray-800 bg-white/95 dark:bg-slate-900/95 backdrop-blur-xl"
        >
            <div class="px-6 py-4 space-y-4">
                <a href="#features" class="block text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 font-medium">Features</a>
                <a href="#pricing" class="block text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 font-medium">Pricing</a>
                <a href="#testimonials" class="block text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 font-medium">Testimonials</a>
                <div class="pt-4 border-t border-gray-200 dark:border-gray-800 space-y-3">
                    <Button
                        v-if="$page.props.auth.user"
                        class="w-full bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white"
                        as-child
                    >
                        <Link :href="route('dashboard')">Dashboard</Link>
                    </Button>
                    <template v-else>
                        <Button variant="outline" class="w-full" as-child>
                            <Link :href="route('login')">Log in</Link>
                        </Button>
                        <Button v-if="canRegister" class="w-full bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white" as-child>
                            <Link :href="route('register')">Start Free Trial</Link>
                        </Button>
                    </template>
                </div>
            </div>
        </div>
    </header>

    <!-- ================= HERO ================= -->
    <section class="pt-32 md:pt-40 pb-20 md:pb-32 relative">
        <div class="max-w-7xl mx-auto px-6">
            
            <!-- Animated Hero Badge -->
            <div class="mb-8 flex justify-center animate-fade-in-up">
                <div class="inline-flex items-center bg-gradient-to-r from-blue-50 to-purple-50 dark:from-blue-950 dark:to-purple-950 border border-blue-200 dark:border-blue-800 px-5 py-2.5 rounded-full shadow-lg shadow-blue-500/10">
                    <div class="w-2.5 h-2.5 bg-green-500 rounded-full animate-pulse mr-3" />
                    <span class="text-sm font-semibold bg-gradient-to-r from-blue-600 to-purple-600 dark:from-blue-400 dark:to-purple-400 bg-clip-text text-transparent">
                        Trusted by 500+ schools worldwide
                    </span>
                </div>
            </div>

            <div class="text-center max-w-5xl mx-auto">
                <h2 class="text-5xl md:text-7xl lg:text-8xl font-bold leading-tight mb-8 animate-fade-in-up animation-delay-100">
                    <span class="block bg-gradient-to-r from-gray-900 via-blue-900 to-purple-900 dark:from-white dark:via-blue-200 dark:to-purple-200 bg-clip-text text-transparent mb-4">
                        Transform Your
                    </span>
                    <span class="block bg-gradient-to-r from-blue-600 via-purple-600 to-pink-600 bg-clip-text text-transparent">
                        School Management
                    </span>
                </h2>

                <p class="text-xl md:text-2xl text-gray-600 dark:text-gray-300 max-w-3xl mx-auto mb-12 leading-relaxed animate-fade-in-up animation-delay-200">
                    Streamline administration, boost academic performance, and enhance communication with our all-in-one SaaS platform.
                </p>

                <div class="flex justify-center gap-4 flex-wrap mb-16 animate-fade-in-up animation-delay-300">
                    <Button
                        v-if="!$page.props.auth.user"
                        size="lg"
                        class="bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white text-lg px-8 py-6 shadow-2xl shadow-blue-500/30 hover:shadow-blue-500/50 hover:scale-105 transition-all duration-300"
                        as-child
                    >
                        <Link :href="route('register')">
                            Start 30-Day Free Trial
                            <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                            </svg>
                        </Link>
                    </Button>

                    <Button 
                        variant="outline" 
                        size="lg" 
                        class="text-lg px-8 py-6 border-2 hover:border-blue-600 hover:bg-blue-50 dark:hover:bg-blue-950 transition-all duration-300" 
                        as-child
                    >
                        <a href="#features">
                            Explore Features
                            <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                            </svg>
                        </a>
                    </Button>
                </div>
                
                <!-- Stats Section with improved design -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 max-w-4xl mx-auto animate-fade-in-up animation-delay-400">
                    <div class="group bg-white dark:bg-slate-900 border border-gray-200 dark:border-gray-800 p-8 rounded-2xl shadow-xl hover:shadow-2xl transition-all duration-300 hover:-translate-y-2">
                        <div class="text-5xl md:text-6xl mb-3 font-bold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">
                            500+
                        </div>
                        <div class="text-gray-600 dark:text-gray-400 font-medium">Schools Trust Us</div>
                    </div>
                    <div class="group bg-white dark:bg-slate-900 border border-gray-200 dark:border-gray-800 p-8 rounded-2xl shadow-xl hover:shadow-2xl transition-all duration-300 hover:-translate-y-2">
                        <div class="text-5xl md:text-6xl mb-3 font-bold bg-gradient-to-r from-green-600 to-emerald-600 bg-clip-text text-transparent">
                            50K+
                        </div>
                        <div class="text-gray-600 dark:text-gray-400 font-medium">Active Students</div>
                    </div>
                    <div class="group bg-white dark:bg-slate-900 border border-gray-200 dark:border-gray-800 p-8 rounded-2xl shadow-xl hover:shadow-2xl transition-all duration-300 hover:-translate-y-2">
                        <div class="text-5xl md:text-6xl mb-3 font-bold bg-gradient-to-r from-orange-600 to-pink-600 bg-clip-text text-transparent">
                            99.9%
                        </div>
                        <div class="text-gray-600 dark:text-gray-400 font-medium">Uptime SLA</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ================= FEATURES ================= -->
    <section id="features" class="py-20 md:py-32 relative bg-white dark:bg-slate-900/50">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center mb-20">
                <div class="inline-flex items-center bg-blue-100 dark:bg-blue-950 px-4 py-2 rounded-full mb-6">
                    <span class="text-sm font-semibold text-blue-600 dark:text-blue-400">Features</span>
                </div>
                <h3 class="text-4xl md:text-6xl font-bold mb-6 bg-gradient-to-r from-gray-900 to-blue-900 dark:from-white dark:to-blue-200 bg-clip-text text-transparent">
                    Everything Your School Needs
                </h3>
                <p class="text-lg md:text-xl text-gray-600 dark:text-gray-400 max-w-2xl mx-auto">
                    Powerful tools designed to simplify management and improve outcomes
                </p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                <div 
                    v-for="(feature, index) in features" 
                    :key="index"
                    class="group relative bg-gradient-to-br from-white to-gray-50 dark:from-slate-900 dark:to-slate-800 p-8 rounded-3xl border border-gray-200 dark:border-gray-800 hover:border-blue-500 dark:hover:border-blue-500 transition-all duration-500 hover:shadow-2xl hover:shadow-blue-500/10 hover:-translate-y-2"
                >
                    <div class="absolute inset-0 bg-gradient-to-br from-blue-500/5 to-purple-500/5 rounded-3xl opacity-0 group-hover:opacity-100 transition-opacity duration-500" />
                    
                    <div class="relative">
                        <div :class="[feature.color, 'w-16 h-16 rounded-2xl flex items-center justify-center mb-6 shadow-lg group-hover:scale-110 transition-transform duration-300']">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="feature.icon" />
                            </svg>
                        </div>
                        
                        <h4 class="text-2xl font-bold mb-4 text-gray-900 dark:text-white">
                            {{ feature.title }}
                        </h4>
                        
                        <p class="text-gray-600 dark:text-gray-400 leading-relaxed">
                            {{ feature.description }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ================= PRICING ================= -->
    <section id="pricing" class="py-20 md:py-32 relative">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center mb-20">
                <div class="inline-flex items-center bg-purple-100 dark:bg-purple-950 px-4 py-2 rounded-full mb-6">
                    <span class="text-sm font-semibold text-purple-600 dark:text-purple-400">Pricing</span>
                </div>
                <h3 class="text-4xl md:text-6xl font-bold mb-6 bg-gradient-to-r from-gray-900 to-purple-900 dark:from-white dark:to-purple-200 bg-clip-text text-transparent">
                    Simple, Transparent Pricing
                </h3>
                <p class="text-lg md:text-xl text-gray-600 dark:text-gray-400">
                    Choose the plan that fits your institution perfectly
                </p>
            </div>

            <div class="grid md:grid-cols-3 gap-8 max-w-6xl mx-auto">
                <!-- Starter Plan -->
                <div class="group relative bg-white dark:bg-slate-900 p-8 rounded-3xl border-2 border-gray-200 dark:border-gray-800 hover:border-blue-500 dark:hover:border-blue-500 transition-all duration-500 hover:shadow-2xl hover:-translate-y-2">
                    <div class="mb-6">
                        <h4 class="text-2xl font-bold mb-2 text-gray-900 dark:text-white">Starter</h4>
                        <p class="text-gray-600 dark:text-gray-400 text-sm">Perfect for small schools</p>
                    </div>
                    
                    <div class="mb-8">
                        <div class="flex items-baseline">
                            <span class="text-5xl font-bold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">Free</span>
                        </div>
                        <p class="text-gray-600 dark:text-gray-400 text-sm mt-2">Forever free</p>
                    </div>
                    
                    <ul class="space-y-4 mb-8">
                        <li class="flex items-start">
                            <svg class="w-6 h-6 text-green-500 mr-3 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <span class="text-gray-700 dark:text-gray-300">Up to 100 Students</span>
                        </li>
                        <li class="flex items-start">
                            <svg class="w-6 h-6 text-green-500 mr-3 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <span class="text-gray-700 dark:text-gray-300">Basic Attendance Tracking</span>
                        </li>
                        <li class="flex items-start">
                            <svg class="w-6 h-6 text-green-500 mr-3 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <span class="text-gray-700 dark:text-gray-300">Email Support</span>
                        </li>
                        <li class="flex items-start">
                            <svg class="w-6 h-6 text-green-500 mr-3 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <span class="text-gray-700 dark:text-gray-300">Mobile App Access</span>
                        </li>
                    </ul>
                    
                    <Button variant="outline" class="w-full border-2 hover:border-blue-600 hover:bg-blue-50 dark:hover:bg-blue-950 transition-colors">
                        Get Started
                    </Button>
                </div>

                <!-- Professional Plan -->
                <div class="group relative bg-gradient-to-br from-blue-600 to-purple-600 p-8 rounded-3xl shadow-2xl shadow-blue-500/30 transform scale-105 hover:scale-110 transition-all duration-500">
                    <div class="absolute -top-4 left-1/2 transform -translate-x-1/2">
                        <div class="bg-gradient-to-r from-orange-500 to-pink-500 px-4 py-1.5 rounded-full shadow-lg">
                            <span class="text-white font-bold text-sm">Most Popular</span>
                        </div>
                    </div>
                    
                    <div class="mb-6">
                        <h4 class="text-2xl font-bold mb-2 text-white">Professional</h4>
                        <p class="text-blue-100 text-sm">For growing institutions</p>
                    </div>
                    
                    <div class="mb-8">
                        <div class="flex items-baseline">
                            <span class="text-5xl font-bold text-white">$49</span>
                            <span class="text-blue-100 ml-2">/month</span>
                        </div>
                        <p class="text-blue-100 text-sm mt-2">Billed monthly</p>
                    </div>
                    
                    <ul class="space-y-4 mb-8">
                        <li class="flex items-start">
                            <svg class="w-6 h-6 text-green-300 mr-3 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <span class="text-white">Up to 500 Students</span>
                        </li>
                        <li class="flex items-start">
                            <svg class="w-6 h-6 text-green-300 mr-3 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <span class="text-white">All Advanced Features</span>
                        </li>
                        <li class="flex items-start">
                            <svg class="w-6 h-6 text-green-300 mr-3 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <span class="text-white">Priority Support</span>
                        </li>
                        <li class="flex items-start">
                            <svg class="w-6 h-6 text-green-300 mr-3 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <span class="text-white">Custom Branding</span>
                        </li>
                        <li class="flex items-start">
                            <svg class="w-6 h-6 text-green-300 mr-3 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <span class="text-white">API Access</span>
                        </li>
                    </ul>
                    
                    <Button class="w-full bg-white text-purple-600 hover:bg-gray-100 font-bold shadow-lg hover:shadow-xl transition-all">
                        Get Started
                    </Button>
                </div>

                <!-- Enterprise Plan -->
                <div class="group relative bg-white dark:bg-slate-900 p-8 rounded-3xl border-2 border-gray-200 dark:border-gray-800 hover:border-purple-500 dark:hover:border-purple-500 transition-all duration-500 hover:shadow-2xl hover:-translate-y-2">
                    <div class="mb-6">
                        <h4 class="text-2xl font-bold mb-2 text-gray-900 dark:text-white">Enterprise</h4>
                        <p class="text-gray-600 dark:text-gray-400 text-sm">For large institutions</p>
                    </div>
                    
                    <div class="mb-8">
                        <div class="flex items-baseline">
                            <span class="text-5xl font-bold bg-gradient-to-r from-purple-600 to-pink-600 bg-clip-text text-transparent">$99</span>
                            <span class="text-gray-600 dark:text-gray-400 ml-2">/month</span>
                        </div>
                        <p class="text-gray-600 dark:text-gray-400 text-sm mt-2">Billed monthly</p>
                    </div>
                    
                    <ul class="space-y-4 mb-8">
                        <li class="flex items-start">
                            <svg class="w-6 h-6 text-green-500 mr-3 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <span class="text-gray-700 dark:text-gray-300">Unlimited Students</span>
                        </li>
                        <li class="flex items-start">
                            <svg class="w-6 h-6 text-green-500 mr-3 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <span class="text-gray-700 dark:text-gray-300">Custom Features</span>
                        </li>
                        <li class="flex items-start">
                            <svg class="w-6 h-6 text-green-500 mr-3 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <span class="text-gray-700 dark:text-gray-300">24/7 Dedicated Support</span>
                        </li>
                        <li class="flex items-start">
                            <svg class="w-6 h-6 text-green-500 mr-3 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <span class="text-gray-700 dark:text-gray-300">On-premise Deployment</span>
                        </li>
                        <li class="flex items-start">
                            <svg class="w-6 h-6 text-green-500 mr-3 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <span class="text-gray-700 dark:text-gray-300">SLA Guarantee</span>
                        </li>
                    </ul>
                    
                    <Button variant="outline" class="w-full border-2 hover:border-purple-600 hover:bg-purple-50 dark:hover:bg-purple-950 transition-colors">
                        Contact Sales
                    </Button>
                </div>
            </div>
        </div>
    </section>

    <!-- ================= TESTIMONIALS ================= -->
    <section id="testimonials" class="py-20 md:py-32 relative bg-white dark:bg-slate-900/50">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center mb-20">
                <div class="inline-flex items-center bg-green-100 dark:bg-green-950 px-4 py-2 rounded-full mb-6">
                    <span class="text-sm font-semibold text-green-600 dark:text-green-400">Testimonials</span>
                </div>
                <h3 class="text-4xl md:text-6xl font-bold mb-6 bg-gradient-to-r from-gray-900 to-green-900 dark:from-white dark:to-green-200 bg-clip-text text-transparent">
                    Loved by Educators
                </h3>
                <p class="text-lg md:text-xl text-gray-600 dark:text-gray-400">
                    See what school leaders are saying about EduManage Pro
                </p>
            </div>

            <div class="max-w-4xl mx-auto relative">
                <div class="bg-gradient-to-br from-white to-gray-50 dark:from-slate-900 dark:to-slate-800 p-12 rounded-3xl border border-gray-200 dark:border-gray-800 shadow-2xl">
                    <div class="text-center">
                        <div class="text-6xl mb-6">{{ testimonials[activeTestimonial].image }}</div>
                        
                        <div class="flex justify-center mb-6">
                            <div class="flex space-x-1">
                                <svg v-for="i in 5" :key="i" class="w-6 h-6 text-yellow-400 fill-current" viewBox="0 0 20 20">
                                    <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                                </svg>
                            </div>
                        </div>
                        
                        <blockquote class="text-2xl md:text-3xl font-medium text-gray-900 dark:text-white mb-8 leading-relaxed">
                            "{{ testimonials[activeTestimonial].quote }}"
                        </blockquote>
                        
                        <div>
                            <div class="font-bold text-xl text-gray-900 dark:text-white">
                                {{ testimonials[activeTestimonial].name }}
                            </div>
                            <div class="text-gray-600 dark:text-gray-400">
                                {{ testimonials[activeTestimonial].role }}
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Navigation Buttons -->
                <div class="flex justify-center mt-8 space-x-4">
                    <button 
                        @click="prevTestimonial"
                        class="w-12 h-12 rounded-full bg-white dark:bg-slate-800 border-2 border-gray-200 dark:border-gray-700 hover:border-blue-500 dark:hover:border-blue-500 flex items-center justify-center transition-all duration-300 hover:shadow-lg"
                    >
                        <svg class="w-6 h-6 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                    </button>
                    
                    <div class="flex items-center space-x-2">
                        <button 
                            v-for="(_, index) in testimonials" 
                            :key="index"
                            @click="activeTestimonial = index"
                            :class="[
                                'w-3 h-3 rounded-full transition-all duration-300',
                                activeTestimonial === index 
                                    ? 'bg-blue-600 w-8' 
                                    : 'bg-gray-300 dark:bg-gray-700 hover:bg-gray-400 dark:hover:bg-gray-600'
                            ]"
                        />
                    </div>
                    
                    <button 
                        @click="nextTestimonial"
                        class="w-12 h-12 rounded-full bg-white dark:bg-slate-800 border-2 border-gray-200 dark:border-gray-700 hover:border-blue-500 dark:hover:border-blue-500 flex items-center justify-center transition-all duration-300 hover:shadow-lg"
                    >
                        <svg class="w-6 h-6 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </section>

    <!-- ================= CTA SECTION ================= -->
    <section class="py-20 md:py-32 relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-blue-600 via-purple-600 to-pink-600" />
        <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNjAiIGhlaWdodD0iNjAiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PGRlZnM+PHBhdHRlcm4gaWQ9ImdyaWQiIHdpZHRoPSI2MCIgaGVpZ2h0PSI2MCIgcGF0dGVyblVuaXRzPSJ1c2VyU3BhY2VPblVzZSI+PHBhdGggZD0iTSAxMCAwIEwgMCAwIDAgMTAiIGZpbGw9Im5vbmUiIHN0cm9rZT0id2hpdGUiIHN0cm9rZS1vcGFjaXR5PSIwLjEiIHN0cm9rZS13aWR0aD0iMSIvPjwvcGF0dGVybj48L2RlZnM+PHJlY3Qgd2lkdGg9IjEwMCUiIGhlaWdodD0iMTAwJSIgZmlsbD0idXJsKCNncmlkKSIvPjwvc3ZnPg==')] opacity-20" />
        
        <div class="max-w-4xl mx-auto px-6 text-center relative z-10">
            <h3 class="text-4xl md:text-6xl font-bold text-white mb-6">
                Ready to Transform Your School?
            </h3>
            <p class="text-xl md:text-2xl text-blue-100 mb-12 leading-relaxed">
                Join 500+ schools already using EduManage Pro to streamline operations and improve student outcomes.
            </p>
            
            <div class="flex justify-center gap-4 flex-wrap">
                <Button
                    v-if="!$page.props.auth.user && canRegister"
                    size="lg"
                    class="bg-white text-purple-600 hover:bg-gray-100 text-lg px-8 py-6 shadow-2xl hover:shadow-3xl hover:scale-105 transition-all duration-300"
                    as-child
                >
                    <Link :href="route('register')">
                        Start Your Free Trial
                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                        </svg>
                    </Link>
                </Button>
                
                <Button 
                    variant="outline" 
                    size="lg" 
                    class="bg-transparent border-2 border-white text-white hover:bg-white hover:text-purple-600 text-lg px-8 py-6 transition-all duration-300"
                >
                    Schedule a Demo
                </Button>
            </div>
            
            <p class="text-blue-100 mt-8 text-sm">
                No credit card required • 30-day free trial • Cancel anytime
            </p>
        </div>
    </section>

    <!-- ================= FOOTER ================= -->
    <footer class="bg-gray-900 dark:bg-slate-950 text-gray-300 py-16 border-t border-gray-800">
        <div class="max-w-7xl mx-auto px-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-12 mb-12">
                <div>
                    <div class="flex items-center space-x-3 mb-6">
                        <div class="w-10 h-10 bg-gradient-to-br from-blue-600 to-purple-600 rounded-xl flex items-center justify-center shadow-lg">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                        </div>
                        <div>
                            <span class="font-bold text-xl text-white">EduManage Pro</span>
                            <p class="text-xs text-blue-400">School Management Platform</p>
                        </div>
                    </div>
                    <p class="text-sm text-gray-400 leading-relaxed">
                        The modern school management platform trusted by educators worldwide.
                    </p>
                </div>
                
                <div>
                    <h4 class="font-semibold mb-4 text-lg text-white">Product</h4>
                    <ul class="space-y-3 text-sm">
                        <li><a href="#features" class="text-gray-400 hover:text-blue-400 transition-colors">Features</a></li>
                        <li><a href="#pricing" class="text-gray-400 hover:text-blue-400 transition-colors">Pricing</a></li>
                        <li><a href="#testimonials" class="text-gray-400 hover:text-blue-400 transition-colors">Testimonials</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-blue-400 transition-colors">Integrations</a></li>
                    </ul>
                </div>
                
                <div>
                    <h4 class="font-semibold mb-4 text-lg text-white">Company</h4>
                    <ul class="space-y-3 text-sm">
                        <li><a href="#" class="text-gray-400 hover:text-blue-400 transition-colors">About Us</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-blue-400 transition-colors">Blog</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-blue-400 transition-colors">Careers</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-blue-400 transition-colors">Press Kit</a></li>
                    </ul>
                </div>
                
                <div>
                    <h4 class="font-semibold mb-4 text-lg text-white">Support</h4>
                    <ul class="space-y-3 text-sm">
                        <li><a href="#" class="text-gray-400 hover:text-blue-400 transition-colors">Help Center</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-blue-400 transition-colors">Contact Us</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-blue-400 transition-colors">System Status</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-blue-400 transition-colors">Privacy Policy</a></li>
                    </ul>
                </div>
            </div>
            
            <div class="border-t border-gray-800 pt-8 flex flex-col md:flex-row justify-between items-center">
                <p class="text-sm text-gray-400 mb-4 md:mb-0">
                    &copy; 2024 EduManage Pro. All rights reserved.
                </p>
                
                <div class="flex space-x-6">
                    <a href="#" class="text-gray-400 hover:text-blue-400 transition-colors">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/>
                        </svg>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-blue-400 transition-colors">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/>
                        </svg>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-blue-400 transition-colors">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </footer>

    </div>
</div>
</template>
