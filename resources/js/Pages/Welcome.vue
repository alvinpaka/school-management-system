<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { ref, onMounted, onUnmounted, computed } from 'vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardFooter, CardHeader, CardTitle } from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';
import { ChevronRight, ArrowRight, Star, Quote, CheckCircle2, PlayCircle } from 'lucide-vue-next';
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
        color: "bg-blue-600"
    },
    {
        icon: "M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z",
        title: "Attendance & Grades",
        description: "Real-time attendance tracking, smart grade management, automated GPA calculations, and instant report generation.",
        color: "bg-green-600"
    },
    {
        icon: "M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z",
        title: "Finance & Billing",
        description: "Automated fee collection, payment tracking, professional invoicing, and comprehensive financial reporting.",
        color: "bg-purple-600"
    },
    {
        icon: "M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z",
        title: "Smart Scheduling",
        description: "Intelligent timetable management, automated class scheduling, and conflict-free calendar planning.",
        color: "bg-orange-600"
    },
    {
        icon: "M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9",
        title: "Communication Hub",
        description: "Instant messaging, announcements, email integration, and multi-channel parent-teacher communication.",
        color: "bg-pink-600"
    },
    {
        icon: "M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z",
        title: "Reports & Analytics",
        description: "Powerful insights, customizable reports, data visualization, and performance tracking dashboards.",
        color: "bg-indigo-600"
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

<div class="min-h-screen bg-mesh dark:bg-slate-950 text-foreground relative transition-colors duration-300">
    
    <!-- Hero Background Orbs -->
    <div class="fixed inset-0 pointer-events-none overflow-hidden z-0">
        <div class="absolute -top-[10%] -left-[10%] w-[40%] h-[40%] bg-indigo-500/10 dark:bg-indigo-600/5 blur-[120px] rounded-full animate-float" />
        <div class="absolute top-[20%] -right-[10%] w-[35%] h-[35%] bg-violet-500/10 dark:bg-violet-600/5 blur-[120px] rounded-full animate-float" style="animation-delay: 2s" />
        <div class="absolute -bottom-[10%] left-[20%] w-[30%] h-[30%] bg-emerald-500/10 dark:bg-emerald-600/5 blur-[120px] rounded-full animate-float" style="animation-delay: 4s" />
    </div>
    
    <div class="relative z-10">

    <!-- ================= NAVBAR ================= -->
    <header
        :class="[
            'fixed top-4 left-1/2 -translate-x-1/2 w-[95%] max-w-7xl z-50 transition-all duration-500 rounded-2xl',
            isScrolled 
                ? 'glass shadow-2xl shadow-indigo-500/10' 
                : 'bg-transparent border-b border-white/5'
        ]"
    >
        <div class="px-6 py-4 flex justify-between items-center text">
            <div class="flex items-center space-x-3 group cursor-pointer">
                <div class="w-11 h-11 bg-gradient-to-br from-indigo-600 to-violet-600 rounded-xl flex items-center justify-center shadow-lg shadow-indigo-500/30 group-hover:shadow-indigo-500/50 transition-all duration-300 group-hover:scale-110">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                </div>
                <div>
                    <h1 class="text-xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-indigo-600 to-violet-600 dark:from-indigo-400 dark:to-violet-400">
                        EduManage Pro
                    </h1>
                    <p class="text-[10px] uppercase tracking-widest text-gray-500 dark:text-gray-400 font-bold">Evolution in Education</p>
                </div>
            </div>

            <!-- Desktop Navigation -->
            <nav v-if="canLogin" class="hidden lg:flex items-center space-x-8">
                <a href="#features" class="text-sm font-semibold text-gray-600 dark:text-gray-300 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors duration-200">Features</a>
                <a href="#pricing" class="text-sm font-semibold text-gray-600 dark:text-gray-300 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors duration-200">Pricing</a>
                <a href="#testimonials" class="text-sm font-semibold text-gray-600 dark:text-gray-300 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors duration-200">Testimonials</a>

                <div class="h-6 w-px bg-gray-200 dark:bg-gray-800"></div>

                <DarkModeToggle variant="ghost" />

                <template v-if="$page.props.auth.user">
                    <Button
                        class="bg-indigo-600 hover:bg-indigo-700 text-white shadow-lg shadow-indigo-500/20 transition-all duration-300 rounded-xl"
                        as-child
                    >
                        <Link :href="route('dashboard')">
                            Go to Dashboard
                        </Link>
                    </Button>
                </template>

                <template v-else>
                    <Link :href="route('login')" class="text-sm font-semibold text-gray-600 dark:text-gray-300 hover:text-indigo-600 transition-colors">
                        Sign In
                    </Link>
                    <Button class="bg-indigo-600 hover:bg-indigo-700 text-white shadow-lg shadow-indigo-500/20 hover:shadow-indigo-500/40 transition-all duration-300 rounded-xl" as-child>
                        <Link :href="route('register')">
                            Get Started
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
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white"
                        as-child
                    >
                        <Link :href="route('dashboard')">Dashboard</Link>
                    </Button>
                    <template v-else>
                        <Button variant="outline" class="w-full" as-child>
                            <Link :href="route('login')">Log in</Link>
                        </Button>
                        <Button v-if="canRegister" class="w-full bg-blue-600 hover:bg-blue-700 text-white" as-child>
                            <Link :href="route('register')">Start Free Trial</Link>
                        </Button>
                    </template>
                </div>
            </div>
        </div>
    </header>

    <!-- ================= HERO ================= -->
    <section class="pt-48 md:pt-60 pb-24 md:pb-40 relative">
        <div class="max-w-7xl mx-auto px-6">
            
            <!-- Animated Hero Badge -->
            <div class="mb-10 flex justify-center animate-fade-in-up">
                <div class="inline-flex items-center glass px-6 py-2.5 rounded-full shadow-2xl shadow-indigo-500/10 border-white/20">
                    <div class="w-2.5 h-2.5 bg-emerald-500 rounded-full animate-pulse-glow mr-3" />
                    <span class="text-sm font-bold text-gray-700 dark:text-gray-200">
                        The future of school management is here
                    </span>
                </div>
            </div>

            <div class="text-center max-w-5xl mx-auto">
                <h2 class="text-6xl md:text-8xl lg:text-9xl font-black leading-tight mb-8 animate-fade-in-up">
                    <span class="block text-gray-900 dark:text-white mb-2 tracking-tighter">
                        Empower Your
                    </span>
                    <span class="block bg-clip-text text-transparent bg-gradient-to-r from-indigo-600 via-violet-600 to-indigo-600 animate-gradient pb-4 tracking-tighter">
                        Academic World
                    </span>
                </h2>

                <p class="text-xl md:text-2xl text-gray-600 dark:text-gray-300 max-w-3xl mx-auto mb-14 leading-relaxed font-medium animate-fade-in-up" style="animation-delay: 0.1s">
                    Streamline operations, inspire excellence, and connect your entire school community with the most advanced management ecosystem.
                </p>

                <div class="flex justify-center gap-6 flex-wrap mb-20 animate-fade-in-up" style="animation-delay: 0.2s">
                    <Button
                        v-if="!$page.props.auth.user"
                        size="lg"
                        class="bg-indigo-600 hover:bg-indigo-700 text-white text-lg px-10 py-7 shadow-[0_20px_50px_rgba(79,70,229,0.3)] hover:shadow-[0_20px_50px_rgba(79,70,229,0.5)] hover:scale-105 transition-all duration-300 rounded-2xl font-bold"
                        as-child
                    >
                        <Link :href="route('register')">
                            Start Free Journey
                            <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                            </svg>
                        </Link>
                    </Button>

                    <Button 
                        variant="ghost" 
                        size="lg" 
                        class="glass text-lg px-10 py-7 border-white/20 hover:bg-white/20 transition-all duration-300 rounded-2xl font-bold dark:text-white" 
                        as-child
                    >
                        <a href="#features">
                            See Innovation
                            <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                            </svg>
                        </a>
                    </Button>
                </div>
                <!-- Stats Section with improved design -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 max-w-4xl mx-auto animate-fade-in-up animation-delay-400">
                    <div class="group bg-white dark:bg-slate-900 border border-gray-200 dark:border-gray-800 p-8 rounded-2xl shadow-xl hover:shadow-2xl transition-all duration-300 hover:-translate-y-2">
                        <div class="text-5xl md:text-6xl mb-3 font-bold text-blue-600">
                            500+
                        </div>
                        <div class="text-gray-600 dark:text-gray-400 font-medium">Schools Trust Us</div>
                    </div>
                    <div class="group bg-white dark:bg-slate-900 border border-gray-200 dark:border-gray-800 p-8 rounded-2xl shadow-xl hover:shadow-2xl transition-all duration-300 hover:-translate-y-2">
                        <div class="text-5xl md:text-6xl mb-3 font-bold text-green-600">
                            50K+
                        </div>
                        <div class="text-gray-600 dark:text-gray-400 font-medium">Active Students</div>
                    </div>
                    <div class="group bg-white dark:bg-slate-900 border border-gray-200 dark:border-gray-800 p-8 rounded-2xl shadow-xl hover:shadow-2xl transition-all duration-300 hover:-translate-y-2">
                        <div class="text-5xl md:text-6xl mb-3 font-bold text-orange-600">
                            99.9%
                        </div>
                        <div class="text-gray-600 dark:text-gray-400 font-medium">Uptime SLA</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ================= FEATURES ================= -->
    <section id="features" class="py-32 md:py-48 relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center mb-24">
                <div class="inline-flex items-center glass px-6 py-2 rounded-full mb-8">
                    <span class="text-sm font-bold text-indigo-600 dark:text-indigo-400 uppercase tracking-widest">Innovation Hub</span>
                </div>
                <h3 class="text-5xl md:text-7xl font-black mb-8 text-gray-900 dark:text-white tracking-tighter">
                    Built for the Modern School
                </h3>
                <p class="text-lg md:text-xl text-gray-600 dark:text-gray-400 max-w-2xl mx-auto font-medium leading-relaxed">
                    Designed with precision to solve the most complex challenges in education management.
                </p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-10">
                <div 
                    v-for="(feature, index) in features" 
                    :key="index"
                    class="glass-card p-10 rounded-[2.5rem] group"
                >
                    <div :class="[feature.color, 'w-20 h-20 rounded-3xl flex items-center justify-center mb-8 shadow-2xl group-hover:scale-110 transition-transform duration-500 shadow-indigo-500/20']">
                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="feature.icon" />
                        </svg>
                    </div>
                    
                    <h4 class="text-2xl font-black mb-4 text-gray-900 dark:text-white tracking-tight">
                        {{ feature.title }}
                    </h4>
                    
                    <p class="text-gray-600 dark:text-gray-400 leading-relaxed font-medium">
                        {{ feature.description }}
                    </p>

                    <div class="mt-8 flex items-center text-indigo-600 dark:text-indigo-400 font-bold group-hover:translate-x-2 transition-transform duration-300 cursor-pointer">
                        Learn more
                        <ChevronRight class="w-5 h-5 ml-1" />
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ================= PRICING ================= -->
    <section id="pricing" class="py-32 md:py-48 relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center mb-24">
                <div class="inline-flex items-center glass px-6 py-2 rounded-full mb-8">
                    <span class="text-sm font-bold text-violet-600 dark:text-violet-400 uppercase tracking-widest">Pricing</span>
                </div>
                <h3 class="text-5xl md:text-7xl font-black mb-8 text-gray-900 dark:text-white tracking-tighter">
                    Plans that Scale with You
                </h3>
                <p class="text-lg md:text-xl text-gray-600 dark:text-gray-400 max-w-2xl mx-auto font-medium">
                    Transparent, flexible, and built for institutions of all sizes.
                </p>
            </div>

            <div class="grid md:grid-cols-3 gap-10 max-w-6xl mx-auto">
                <!-- Starter Plan -->
                <div class="glass-card p-10 rounded-[2.5rem] flex flex-col group border-white/10">
                    <div class="mb-8">
                        <h4 class="text-2xl font-black mb-2 text-gray-900 dark:text-white">Starter</h4>
                        <p class="text-gray-500 dark:text-gray-400 font-medium">For small private schools</p>
                    </div>
                    
                    <div class="mb-10">
                        <div class="flex items-baseline">
                            <span class="text-6xl font-black text-indigo-600 dark:text-indigo-400 tracking-tighter">Free</span>
                        </div>
                        <p class="text-gray-500 dark:text-gray-400 text-sm mt-3 font-medium tracking-wide">Forever independent</p>
                    </div>
                    
                    <ul class="space-y-5 mb-12 flex-1">
                        <li class="flex items-center text-gray-700 dark:text-gray-300 font-medium">
                            <CheckCircle2 class="w-6 h-6 text-emerald-500 mr-3 flex-shrink-0" />
                            Up to 50 Students
                        </li>
                        <li class="flex items-center text-gray-700 dark:text-gray-300 font-medium opacity-60">
                            <CheckCircle2 class="w-6 h-6 text-gray-400 mr-3 flex-shrink-0" />
                            Basic Features
                        </li>
                    </ul>
                    
                    <Button variant="ghost" class="w-full h-14 rounded-2xl border-2 border-indigo-600/20 hover:border-indigo-600 hover:bg-transparent text-indigo-600 dark:text-indigo-400 font-bold">
                        Start Now
                    </Button>
                </div>

                <!-- Professional Plan -->
                <div class="glass-card p-10 rounded-[2.5rem] flex flex-col relative scale-105 shadow-[0_30px_60px_-15px_rgba(79,70,229,0.3)] border-indigo-500/30">
                    <div class="absolute -top-5 left-1/2 -translate-x-1/2 px-6 py-2 bg-gradient-to-r from-indigo-600 to-violet-600 rounded-full shadow-lg">
                        <span class="text-white font-black text-xs uppercase tracking-widest">Most Popular</span>
                    </div>
                    
                    <div class="mb-8 pt-4">
                        <h4 class="text-2xl font-black mb-2 text-gray-900 dark:text-white">Professional</h4>
                        <p class="text-gray-500 dark:text-gray-400 font-medium">Scaling fast institutions</p>
                    </div>
                    
                    <div class="mb-10">
                        <div class="flex items-baseline">
                            <span class="text-6xl font-black text-indigo-600 dark:text-indigo-400 tracking-tighter">$49</span>
                            <span class="text-gray-500 dark:text-gray-400 font-bold ml-2">/mo</span>
                        </div>
                        <p class="text-gray-500 dark:text-gray-400 text-sm mt-3 font-medium tracking-wide">Billed annually</p>
                    </div>
                    
                    <ul class="space-y-5 mb-12 flex-1">
                        <li class="flex items-center text-gray-700 dark:text-gray-300 font-bold">
                            <CheckCircle2 class="w-6 h-6 text-indigo-500 mr-3 flex-shrink-0 shadow-glow" />
                            Up to 500 Students
                        </li>
                        <li class="flex items-center text-gray-700 dark:text-gray-300 font-bold">
                            <CheckCircle2 class="w-6 h-6 text-indigo-500 mr-3 flex-shrink-0 shadow-glow" />
                            All Core Features
                        </li>
                        <li class="flex items-center text-gray-700 dark:text-gray-300 font-bold">
                            <CheckCircle2 class="w-6 h-6 text-indigo-500 mr-3 flex-shrink-0 shadow-glow" />
                            Cloud Synchronization
                        </li>
                    </ul>
                    
                    <Button class="w-full h-14 bg-gradient-to-r from-indigo-600 to-violet-600 hover:from-indigo-700 hover:to-violet-700 text-white font-black rounded-2xl shadow-xl shadow-indigo-500/20">
                        Select Plan
                    </Button>
                </div>

                <!-- Enterprise Plan -->
                <div class="glass-card p-10 rounded-[2.5rem] flex flex-col border-white/10">
                    <div class="mb-8">
                        <h4 class="text-2xl font-black mb-2 text-gray-900 dark:text-white">Enterprise</h4>
                        <p class="text-gray-500 dark:text-gray-400 font-medium">Large scale campuses</p>
                    </div>
                    
                    <div class="mb-10">
                        <div class="flex items-baseline text-6xl font-black text-gray-900 dark:text-white tracking-tighter">
                            Custom
                        </div>
                        <p class="text-gray-500 dark:text-gray-400 text-sm mt-3 font-medium tracking-wide">Tailored to your needs</p>
                    </div>
                    
                    <ul class="space-y-5 mb-12 flex-1">
                        <li class="flex items-center text-gray-700 dark:text-gray-300 font-medium">
                            <CheckCircle2 class="w-6 h-6 text-gray-900 dark:text-white mr-3 flex-shrink-0" />
                            Unlimited Students
                        </li>
                        <li class="flex items-center text-gray-700 dark:text-gray-300 font-medium">
                            <CheckCircle2 class="w-6 h-6 text-gray-900 dark:text-white mr-3 flex-shrink-0" />
                            24h Dedicated Support
                        </li>
                    </ul>
                    
                    <Button variant="outline" class="w-full h-14 rounded-2xl border-gray-200 dark:border-gray-800 hover:bg-gray-50 dark:hover:bg-gray-900 text-gray-900 dark:text-white font-black">
                        Contact Us
                    </Button>
                </div>
            </div>
        </div>
    </section>

    <!-- ================= TESTIMONIALS ================= -->
    <section id="testimonials" class="py-32 md:py-48 relative">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center mb-20">
                <div class="inline-flex items-center glass px-6 py-2 rounded-full mb-8">
                    <span class="text-sm font-bold text-emerald-600 dark:text-emerald-400 uppercase tracking-widest">Global Trust</span>
                </div>
                <h3 class="text-5xl md:text-7xl font-black mb-8 text-gray-900 dark:text-white tracking-tighter">
                    Loved by Education Leaders
                </h3>
            </div>

            <div class="max-w-5xl mx-auto relative group">
                <div class="glass-card p-12 md:p-20 rounded-[3rem] text-center border-white/20">
                    <Quote class="w-16 h-16 text-indigo-500/20 absolute top-10 left-10" />
                    <div class="relative z-10">
                        <div class="text-7xl mb-10 animate-scale-up">{{ testimonials[activeTestimonial].image }}</div>
                        
                        <div class="flex justify-center mb-10">
                            <div class="flex space-x-1.5">
                                <Star v-for="i in 5" :key="i" class="w-6 h-6 text-yellow-400 fill-yellow-400" />
                            </div>
                        </div>
                        
                        <blockquote class="text-3xl md:text-5xl font-black text-gray-900 dark:text-white mb-10 leading-[1.1] tracking-tighter">
                            "{{ testimonials[activeTestimonial].quote }}"
                        </blockquote>
                        
                        <div>
                            <div class="font-black text-2xl text-indigo-600 dark:text-indigo-400">
                                {{ testimonials[activeTestimonial].name }}
                            </div>
                            <div class="text-gray-500 dark:text-gray-400 font-bold uppercase tracking-widest text-sm mt-1">
                                {{ testimonials[activeTestimonial].role }}
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Navigation Buttons -->
                <div class="absolute top-1/2 -translate-y-1/2 -left-6 md:-left-12">
                    <button @click="prevTestimonial" class="p-6 rounded-2xl glass hover:bg-white dark:hover:bg-gray-800 transition-all duration-300 shadow-xl border-white/30 group">
                        <ChevronRight class="w-6 h-6 rotate-180 text-gray-900 dark:text-white" />
                    </button>
                </div>
                <div class="absolute top-1/2 -translate-y-1/2 -right-6 md:-right-12">
                    <button @click="nextTestimonial" class="p-6 rounded-2xl glass hover:bg-white dark:hover:bg-gray-800 transition-all duration-300 shadow-xl border-white/30 group">
                        <ChevronRight class="w-6 h-6 text-gray-900 dark:text-white" />
                    </button>
                </div>
            </div>
        </div>
    </section>

    <!-- ================= CTA SECTION ================= -->
    <section class="py-32 md:py-48 px-6 relative overflow-hidden">
        <div class="max-w-7xl mx-auto">
            <div class="relative rounded-[4rem] overflow-hidden bg-indigo-600 px-10 py-24 md:p-32 text-center group">
                <!-- Animated Background for CTA -->
                <div class="absolute inset-0 bg-gradient-to-br from-indigo-600 via-violet-600 to-indigo-800 transition-all duration-700 group-hover:scale-110" />
                <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAiIGhlaWdodD0iMjAiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PHJlY3Qgd2lkdGg9IjIwIiBoZWlnaHQ9IjIwIiBmaWxsPSJub25lIi8+PGNpcmNsZSBjeD0iMSIgY3k9IjEiIHI9IjEiIGZpbGw9IndoaXRlIiBmaWxsLW9wYWNpdHk9IjAuMSIvPjwvc3ZnPg==')] opacity-30" />
                
                <div class="relative z-10 max-w-4xl mx-auto">
                    <h3 class="text-5xl md:text-8xl font-black text-white mb-10 tracking-tighter leading-none">
                        Ready for the <br/> Transformation?
                    </h3>
                    <p class="text-xl md:text-2xl text-indigo-100 mb-14 font-medium leading-relaxed">
                        Join 500+ forward-thinking schools that have already redefined their administrative legacy.
                    </p>
                    
                    <div class="flex justify-center gap-6 flex-wrap">
                        <Button
                            v-if="!$page.props.auth.user"
                            size="lg"
                            class="bg-white text-indigo-600 hover:bg-gray-100 text-xl px-12 py-8 shadow-2xl rounded-2xl font-black flex items-center gap-3 transition-all duration-300 hover:scale-105"
                            as-child
                        >
                            <Link :href="route('register')">
                                Get Started Free
                                <ArrowRight class="w-6 h-6" />
                            </Link>
                        </Button>
                        
                        <Button 
                            variant="ghost" 
                            size="lg" 
                            class="border-2 border-white/30 text-white hover:bg-white/10 text-xl px-12 py-8 rounded-2xl font-black flex items-center gap-3 transition-all duration-300 backdrop-blur-sm"
                        >
                            <PlayCircle class="w-6 h-6" />
                            Book a Demo
                        </Button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ================= FOOTER ================= -->
    <footer class="bg-gray-900 dark:bg-slate-950 text-gray-300 py-16 border-t border-gray-800">
        <div class="max-w-7xl mx-auto px-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-12 mb-12">
                <div>
                    <div class="flex items-center space-x-3 mb-6">
                        <div class="w-10 h-10 bg-blue-600 rounded-xl flex items-center justify-center shadow-lg">
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
