<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { ref, onMounted, onUnmounted } from 'vue';
import { Button } from '@/Components/ui/button';
import { ChevronRight, ArrowRight, Star, Quote, CheckCircle2, PlayCircle, ChevronLeft, Sun, Moon } from 'lucide-vue-next';
import { useTheme } from '@/composables/useTheme';

defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
    laravelVersion: String,
    phpVersion: String,
});

const isScrolled = ref(false);
const activeTestimonial = ref(0);
const mobileMenuOpen = ref(false);

const { updateDocumentClass } = useTheme();

// Dark mode state — reads from <html> class set by useTheme
const isDark = ref(false);

if (typeof document !== 'undefined') {
    updateDocumentClass();
    isDark.value = document.documentElement.classList.contains('dark');
}

const toggleTheme = () => {
    isDark.value = !isDark.value;
    if (isDark.value) {
        document.documentElement.classList.add('dark');
        localStorage.setItem('theme', 'dark');
    } else {
        document.documentElement.classList.remove('dark');
        localStorage.setItem('theme', 'light');
    }
};

const handleScroll = () => {
    isScrolled.value = window.scrollY > 20;
};

const testimonials = [
    {
        name: "Amara Odhiambo",
        role: "Principal, Nairobi International School",
        initials: "AO",
        quote: "EduManage Pro transformed how we run our school. We've saved 20+ hours per week on administrative tasks across all three campuses.",
        rating: 5
    },
    {
        name: "David Kimani",
        role: "Director, Rift Valley Academy",
        initials: "DK",
        quote: "The attendance and grading features are exceptional. Parents across Nairobi love the real-time updates on their children's progress.",
        rating: 5
    },
    {
        name: "Fatuma Wanjiru",
        role: "Head of Administration, Mombasa Academy",
        initials: "FW",
        quote: "Best investment we've made for our institution. The ROI was clearly visible within the first term of implementation.",
        rating: 5
    }
];

const features = [
    {
        icon: "M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z",
        title: "Student Management",
        description: "Centralized student profiles, document storage, enrollment tracking, and seamless parent communication built for Kenyan schools.",
        accent: "terracotta"
    },
    {
        icon: "M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z",
        title: "Attendance & Grades",
        description: "Real-time tracking, smart grade management, automated GPA calculations, and KNEC-aligned report generation.",
        accent: "forest"
    },
    {
        icon: "M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z",
        title: "Finance & Billing",
        description: "Automated fee collection, M-Pesa integration, payment tracking, professional invoicing, and financial reporting.",
        accent: "amber"
    },
    {
        icon: "M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z",
        title: "Smart Scheduling",
        description: "Intelligent timetable management, automated class scheduling, and conflict-free calendar planning for all terms.",
        accent: "terracotta"
    },
    {
        icon: "M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9",
        title: "Communication Hub",
        description: "Instant messaging, SMS alerts, school-wide announcements, and multi-channel parent-teacher communication.",
        accent: "forest"
    },
    {
        icon: "M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z",
        title: "Reports & Analytics",
        description: "Powerful insights, customizable reports, data visualization, and term-end performance tracking dashboards.",
        accent: "amber"
    }
];

const nextTestimonial = () => {
    activeTestimonial.value = (activeTestimonial.value + 1) % testimonials.length;
};

const prevTestimonial = () => {
    activeTestimonial.value = activeTestimonial.value === 0 ? testimonials.length - 1 : activeTestimonial.value - 1;
};

let testimonialInterval = null;

onMounted(() => {
    window.addEventListener('scroll', handleScroll);
    testimonialInterval = setInterval(nextTestimonial, 5000);
});

onUnmounted(() => {
    window.removeEventListener('scroll', handleScroll);
    clearInterval(testimonialInterval);
});
</script>

<template>
<Head title="EduManage Pro — School Management for Kenyan Schools" />

<div class="min-h-screen bg-[#FAF7F2] dark:bg-[#0F0D0A] text-[#1A1612] dark:text-[#F5F0E8] relative transition-colors duration-300 font-['Georgia',_serif]">
    <!-- Background Orbs -->
    <div class="fixed inset-0 pointer-events-none overflow-hidden z-0">
        <div class="hero-orb-1 absolute -top-[15%] -left-[10%] w-[50%] h-[50%] blur-[100px] animate-float" />
        <div class="hero-orb-2 absolute top-[30%] -right-[5%] w-[40%] h-[40%] blur-[100px] animate-float" style="animation-delay: 3s" />
        <div class="hero-orb-3 absolute -bottom-[10%] left-[15%] w-[35%] h-[35%] blur-[100px] animate-float" style="animation-delay: 6s" />
    </div>

    <div class="savanna-texture fixed inset-0 pointer-events-none z-0 opacity-50" />

    <div class="relative z-10 font-body">

    <!-- ═══════════════════ NAVBAR ═══════════════════ -->
    <header :class="[
        'fixed top-4 left-1/2 -translate-x-1/2 w-[95%] max-w-7xl z-50 transition-all duration-500 rounded-2xl',
        isScrolled ? 'glass-warm shadow-xl shadow-[#C4622D]/10' : 'bg-transparent'
    ]">
        <div class="px-6 py-4 flex justify-between items-center">
            <!-- Logo -->
            <div class="flex items-center space-x-3 cursor-pointer group">
                <div class="w-11 h-11 accent-terracotta rounded-xl flex items-center justify-center shadow-md shadow-[#C4622D]/30 group-hover:shadow-[#C4622D]/50 transition-all duration-300 group-hover:scale-105">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                </div>
                <div>
                    <h1 class="font-display text-lg font-bold text-[#1A1612] dark:text-[#F5F0E8] leading-none">EduManage Pro</h1>
                    <p class="text-[9px] uppercase tracking-[0.2em] text-terracotta font-body font-semibold mt-0.5">Kenya · Education Management</p>
                </div>
            </div>

            <!-- Desktop Nav -->
            <nav v-if="canLogin" class="hidden lg:flex items-center space-x-8">
                <a href="#features" class="nav-link text-sm font-medium text-[#4A3728] dark:text-[#C8B89A] hover:text-[#C4622D] dark:hover:text-[#E07A45] transition-colors">Features</a>
                <a href="#pricing" class="nav-link text-sm font-medium text-[#4A3728] dark:text-[#C8B89A] hover:text-[#C4622D] dark:hover:text-[#E07A45] transition-colors">Pricing</a>
                <a href="#testimonials" class="nav-link text-sm font-medium text-[#4A3728] dark:text-[#C8B89A] hover:text-[#C4622D] dark:hover:text-[#E07A45] transition-colors">Testimonials</a>
                <div class="w-px h-5 bg-[#C4622D]/20"></div>

                <!-- Theme Toggle -->
                <button
                    @click="toggleTheme"
                    :title="isDark ? 'Switch to light mode' : 'Switch to dark mode'"
                    class="relative w-9 h-9 rounded-xl flex items-center justify-center border border-[#C4622D]/20 text-[#4A3728] dark:text-[#C8B89A] hover:border-[#C4622D]/50 hover:text-[#C4622D] dark:hover:text-[#E07A45] hover:bg-[#C4622D]/8 transition-all duration-300"
                >
                    <Sun v-if="isDark" class="w-4 h-4 transition-all duration-300 rotate-0 scale-100" />
                    <Moon v-else class="w-4 h-4 transition-all duration-300 rotate-0 scale-100" />
                </button>

                <template v-if="$page.props.auth.user">
                    <Button class="accent-terracotta text-white border-0 shadow-lg shadow-[#C4622D]/25 hover:shadow-[#C4622D]/40 hover:opacity-90 rounded-xl transition-all" as-child>
                        <Link :href="route('dashboard')">Dashboard</Link>
                    </Button>
                </template>
                <template v-else>
                    <Link :href="route('login')" class="text-sm font-medium text-[#4A3728] dark:text-[#C8B89A] hover:text-[#C4622D] transition-colors">Sign In</Link>
                    <Button class="accent-terracotta text-white border-0 shadow-lg shadow-[#C4622D]/25 hover:shadow-[#C4622D]/40 hover:opacity-90 rounded-xl transition-all font-semibold px-5" as-child>
                        <Link :href="route('register')">Get Started</Link>
                    </Button>
                </template>
            </nav>

            <!-- Mobile: Theme Toggle + Hamburger -->
            <div v-if="canLogin" class="lg:hidden flex items-center gap-2">
                <button
                    @click="toggleTheme"
                    :title="isDark ? 'Switch to light mode' : 'Switch to dark mode'"
                    class="w-9 h-9 rounded-xl flex items-center justify-center border border-[#C4622D]/20 text-[#4A3728] dark:text-[#C8B89A] hover:border-[#C4622D]/50 hover:text-[#C4622D] dark:hover:text-[#E07A45] transition-all duration-300"
                >
                    <Sun v-if="isDark" class="w-4 h-4" />
                    <Moon v-else class="w-4 h-4" />
                </button>
                <button @click="mobileMenuOpen = !mobileMenuOpen" class="p-2 rounded-lg hover:bg-[#C4622D]/10 transition-colors">
                    <svg class="w-5 h-5 text-[#1A1612] dark:text-[#F5F0E8]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path v-if="!mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path v-else stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div v-if="mobileMenuOpen && canLogin" class="lg:hidden mobile-menu-enter border-t border-warm card-warm rounded-b-2xl">
            <div class="px-6 py-5 space-y-4">
                <a href="#features" @click="mobileMenuOpen=false" class="block text-sm font-medium text-[#4A3728] dark:text-[#C8B89A]">Features</a>
                <a href="#pricing" @click="mobileMenuOpen=false" class="block text-sm font-medium text-[#4A3728] dark:text-[#C8B89A]">Pricing</a>
                <a href="#testimonials" @click="mobileMenuOpen=false" class="block text-sm font-medium text-[#4A3728] dark:text-[#C8B89A]">Testimonials</a>
                <div class="pt-4 border-t border-warm space-y-3">
                    <template v-if="$page.props.auth.user">
                        <Button class="w-full accent-terracotta text-white border-0 rounded-xl" as-child>
                            <Link :href="route('dashboard')">Dashboard</Link>
                        </Button>
                    </template>
                    <template v-else>
                        <Button variant="outline" class="w-full rounded-xl border-[#C4622D]/30 text-[#C4622D]" as-child>
                            <Link :href="route('login')">Sign In</Link>
                        </Button>
                        <Button v-if="canRegister" class="w-full accent-terracotta text-white border-0 rounded-xl" as-child>
                            <Link :href="route('register')">Get Started Free</Link>
                        </Button>
                    </template>
                </div>
            </div>
        </div>
    </header>

    <!-- ═══════════════════ HERO ═══════════════════ -->
    <section class="pt-48 md:pt-64 pb-24 md:pb-40 relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-6">

            <!-- Kicker Badge -->
            <div class="flex justify-center mb-10 animate-in">
                <div class="hero-badge inline-flex items-center gap-3 px-6 py-2.5 rounded-full">
                    <span class="w-2 h-2 rounded-full bg-[#2D5A3D] dark:bg-[#5AAA72] animate-pulse"></span>
                    <span class="font-body text-sm font-semibold text-[#4A3728] dark:text-[#C8B89A] tracking-wide">
                        Trusted by 500+ schools across East Africa
                    </span>
                </div>
            </div>

            <!-- Hero Headline -->
            <div class="text-center max-w-5xl mx-auto">
                <div class="mb-4 animate-in delay-100">
                    <span class="font-body text-xs uppercase tracking-[0.3em] font-semibold text-terracotta">Nairobi, Kenya · Est. 2019</span>
                </div>

                <h2 class="font-display font-black leading-[1.05] tracking-tight mb-8 animate-in delay-100">
                    <span class="block text-5xl md:text-7xl lg:text-8xl text-[#1A1612] dark:text-[#F5F0E8]">Where Academic</span>
                    <span class="block text-5xl md:text-7xl lg:text-8xl mt-1" style="background: linear-gradient(135deg, #C4622D 0%, #B5860A 50%, #2D5A3D 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">
                        Excellence Begins
                    </span>
                </h2>

                <!-- Decorative rule -->
                <div class="flex items-center justify-center gap-4 mb-10 animate-in delay-200">
                    <div class="h-px w-24 bg-gradient-to-r from-transparent to-[#C4622D]/50"></div>
                    <svg class="w-5 h-5 text-[#C4622D]" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 2L9.09 8.26L2 9.27L7 14.14L5.82 21.02L12 17.77L18.18 21.02L17 14.14L22 9.27L14.91 8.26L12 2Z"/>
                    </svg>
                    <div class="h-px w-24 bg-gradient-to-l from-transparent to-[#C4622D]/50"></div>
                </div>

                <p class="font-body text-lg md:text-xl text-[#5A4030] dark:text-[#A89070] max-w-3xl mx-auto mb-14 leading-relaxed animate-in delay-200">
                    Streamline every aspect of school management — from student enrollment to fee collection — with a platform built for the modern Kenyan institution.
                </p>

                <!-- CTAs -->
                <div class="flex justify-center gap-5 flex-wrap mb-20 animate-in delay-300">
                    <button
                        v-if="!$page.props.auth.user"
                        class="group inline-flex items-center gap-3 accent-terracotta text-white text-base font-semibold px-9 py-4 rounded-2xl shadow-xl shadow-[#C4622D]/30 hover:shadow-[#C4622D]/50 hover:scale-105 transition-all duration-300 font-body"
                        @click="$inertia ? $inertia.visit(route('register')) : null"
                    >
                        <Link :href="route('register')" class="flex items-center gap-2">
                            Begin Your Journey
                            <ArrowRight class="w-5 h-5 group-hover:translate-x-1 transition-transform" />
                        </Link>
                    </button>

                    <a
                        href="#features"
                        class="inline-flex items-center gap-3 text-base font-semibold px-9 py-4 rounded-2xl border border-[#C4622D]/30 text-[#C4622D] dark:text-[#E07A45] hover:bg-[#C4622D]/8 transition-all duration-300 font-body"
                    >
                        <PlayCircle class="w-5 h-5" />
                        Explore Features
                    </a>
                </div>

                <!-- Stats -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-5 max-w-3xl mx-auto animate-in delay-300">
                    <div class="card-warm rounded-2xl p-8 hover:-translate-y-1 transition-transform duration-300 group">
                        <div class="stat-number text-5xl font-bold text-terracotta mb-2">500+</div>
                        <div class="font-body text-sm text-[#5A4030] dark:text-[#A89070] font-medium uppercase tracking-wider">Schools</div>
                    </div>
                    <div class="card-warm rounded-2xl p-8 hover:-translate-y-1 transition-transform duration-300">
                        <div class="stat-number text-5xl font-bold text-forest dark:text-[#5AAA72] mb-2">50K+</div>
                        <div class="font-body text-sm text-[#5A4030] dark:text-[#A89070] font-medium uppercase tracking-wider">Students</div>
                    </div>
                    <div class="card-warm rounded-2xl p-8 hover:-translate-y-1 transition-transform duration-300">
                        <div class="stat-number text-5xl font-bold text-amber mb-2">99.9%</div>
                        <div class="font-body text-sm text-[#5A4030] dark:text-[#A89070] font-medium uppercase tracking-wider">Uptime SLA</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ═══════════════════ FEATURES ═══════════════════ -->
    <section id="features" class="py-32 md:py-44 relative">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center mb-20">
                <p class="font-body text-xs uppercase tracking-[0.3em] font-semibold text-terracotta mb-6">Our Platform</p>
                <h3 class="font-display text-5xl md:text-6xl font-black text-[#1A1612] dark:text-[#F5F0E8] mb-6 leading-tight">
                    Built for the<br/>Modern School
                </h3>
                <div class="flex items-center justify-center gap-3 mb-6">
                    <div class="h-px w-16 bg-[#C4622D]/30"></div>
                    <div class="w-1.5 h-1.5 rounded-full bg-[#C4622D]"></div>
                    <div class="h-px w-16 bg-[#C4622D]/30"></div>
                </div>
                <p class="font-body text-[#5A4030] dark:text-[#A89070] max-w-xl mx-auto text-lg leading-relaxed">
                    Precision tools designed to solve the most complex challenges in Kenyan education management.
                </p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                <div
                    v-for="(feature, index) in features"
                    :key="index"
                    class="card-warm rounded-3xl p-9 feature-card group cursor-default"
                >
                    <div :class="['accent-' + feature.accent, 'w-16 h-16 rounded-2xl flex items-center justify-center mb-7 shadow-lg group-hover:scale-110 transition-transform duration-400']">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="feature.icon" />
                        </svg>
                    </div>

                    <h4 class="font-display text-xl font-bold mb-3 text-[#1A1612] dark:text-[#F5F0E8]">
                        {{ feature.title }}
                    </h4>

                    <p class="font-body text-[#5A4030] dark:text-[#A89070] leading-relaxed text-sm">
                        {{ feature.description }}
                    </p>

                    <div :class="['mt-7 flex items-center text-sm font-semibold font-body group-hover:gap-2 transition-all', 'text-' + feature.accent]">
                        <span>Learn more</span>
                        <ChevronRight class="w-4 h-4 ml-1 group-hover:translate-x-1 transition-transform" />
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ═══════════════════ PRICING ═══════════════════ -->
    <section id="pricing" class="py-32 md:py-44 relative">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center mb-20">
                <p class="font-body text-xs uppercase tracking-[0.3em] font-semibold text-amber mb-6">Investment</p>
                <h3 class="font-display text-5xl md:text-6xl font-black text-[#1A1612] dark:text-[#F5F0E8] mb-6 leading-tight">
                    Plans that Scale<br/>with Your Institution
                </h3>
                <div class="flex items-center justify-center gap-3 mb-6">
                    <div class="h-px w-16 bg-[#B5860A]/30"></div>
                    <div class="w-1.5 h-1.5 rounded-full bg-[#B5860A]"></div>
                    <div class="h-px w-16 bg-[#B5860A]/30"></div>
                </div>
                <p class="font-body text-[#5A4030] dark:text-[#A89070] max-w-xl mx-auto text-lg">
                    Transparent and flexible, designed for institutions of every size across Kenya.
                </p>
            </div>

            <div class="grid md:grid-cols-3 gap-8 max-w-5xl mx-auto items-start">

                <!-- Starter -->
                <div class="card-warm rounded-3xl p-9 flex flex-col">
                    <div class="mb-7">
                        <p class="font-body text-xs uppercase tracking-[0.2em] text-[#5A4030] dark:text-[#A89070] font-semibold mb-2">Starter</p>
                        <h4 class="font-display text-2xl font-bold text-[#1A1612] dark:text-[#F5F0E8]">For growing schools</h4>
                    </div>
                    <div class="mb-8">
                        <div class="flex items-baseline gap-1">
                            <span class="font-display text-6xl font-black text-terracotta">Free</span>
                        </div>
                        <p class="font-body text-sm text-[#5A4030] dark:text-[#A89070] mt-2">Forever, no credit card required</p>
                    </div>
                    <ul class="space-y-4 mb-10 flex-1">
                        <li class="flex items-center gap-3 font-body text-sm text-[#3A2A1A] dark:text-[#C8B89A]">
                            <CheckCircle2 class="w-5 h-5 text-[#2D5A3D] dark:text-[#5AAA72] flex-shrink-0" />
                            Up to 50 Students
                        </li>
                        <li class="flex items-center gap-3 font-body text-sm text-[#3A2A1A]/50 dark:text-[#C8B89A]/50">
                            <CheckCircle2 class="w-5 h-5 text-[#888] flex-shrink-0" />
                            Basic Features Only
                        </li>
                    </ul>
                    <button class="w-full py-3.5 rounded-xl border border-[#C4622D]/30 text-[#C4622D] font-body font-semibold text-sm hover:bg-[#C4622D]/8 transition-colors">
                        Start Now
                    </button>
                </div>

                <!-- Professional (Featured) -->
                <div class="relative rounded-3xl p-9 flex flex-col" style="background: linear-gradient(160deg, #2A1A0E 0%, #1A2E1F 100%); border: 1px solid rgba(196,98,45,0.4);">
                    <div class="absolute -top-4 left-1/2 -translate-x-1/2">
                        <div class="accent-terracotta text-white text-[10px] font-body font-bold uppercase tracking-[0.2em] px-5 py-1.5 rounded-full shadow-md">
                            Most Popular
                        </div>
                    </div>
                    <div class="mb-7 pt-3">
                        <p class="font-body text-xs uppercase tracking-[0.2em] text-[#C8B89A]/60 font-semibold mb-2">Professional</p>
                        <h4 class="font-display text-2xl font-bold text-white">For scaling institutions</h4>
                    </div>
                    <div class="mb-8">
                        <div class="flex items-baseline gap-1">
                            <span class="font-body text-xl font-medium text-[#C8B89A]/70">KES</span>
                            <span class="font-display text-6xl font-black text-[#E07A45]">5,800</span>
                        </div>
                        <p class="font-body text-sm text-[#C8B89A]/60 mt-2">Per month, billed annually</p>
                    </div>
                    <ul class="space-y-4 mb-10 flex-1">
                        <li class="flex items-center gap-3 font-body text-sm text-[#F0E0C8]">
                            <CheckCircle2 class="w-5 h-5 text-[#E07A45] flex-shrink-0" /> Up to 500 Students
                        </li>
                        <li class="flex items-center gap-3 font-body text-sm text-[#F0E0C8]">
                            <CheckCircle2 class="w-5 h-5 text-[#E07A45] flex-shrink-0" /> All Core Features
                        </li>
                        <li class="flex items-center gap-3 font-body text-sm text-[#F0E0C8]">
                            <CheckCircle2 class="w-5 h-5 text-[#E07A45] flex-shrink-0" /> M-Pesa Integration
                        </li>
                        <li class="flex items-center gap-3 font-body text-sm text-[#F0E0C8]">
                            <CheckCircle2 class="w-5 h-5 text-[#E07A45] flex-shrink-0" /> Priority Support
                        </li>
                    </ul>
                    <button class="w-full py-3.5 rounded-xl accent-terracotta text-white font-body font-bold text-sm shadow-lg shadow-[#C4622D]/30 hover:opacity-90 transition-opacity">
                        Select Plan
                    </button>
                </div>

                <!-- Enterprise -->
                <div class="card-warm rounded-3xl p-9 flex flex-col">
                    <div class="mb-7">
                        <p class="font-body text-xs uppercase tracking-[0.2em] text-[#5A4030] dark:text-[#A89070] font-semibold mb-2">Enterprise</p>
                        <h4 class="font-display text-2xl font-bold text-[#1A1612] dark:text-[#F5F0E8]">For large campuses</h4>
                    </div>
                    <div class="mb-8">
                        <span class="font-display text-5xl font-black text-[#1A1612] dark:text-[#F5F0E8]">Custom</span>
                        <p class="font-body text-sm text-[#5A4030] dark:text-[#A89070] mt-2">Tailored to your institution's needs</p>
                    </div>
                    <ul class="space-y-4 mb-10 flex-1">
                        <li class="flex items-center gap-3 font-body text-sm text-[#3A2A1A] dark:text-[#C8B89A]">
                            <CheckCircle2 class="w-5 h-5 text-[#2D5A3D] dark:text-[#5AAA72] flex-shrink-0" /> Unlimited Students
                        </li>
                        <li class="flex items-center gap-3 font-body text-sm text-[#3A2A1A] dark:text-[#C8B89A]">
                            <CheckCircle2 class="w-5 h-5 text-[#2D5A3D] dark:text-[#5AAA72] flex-shrink-0" /> Dedicated Support 24/7
                        </li>
                        <li class="flex items-center gap-3 font-body text-sm text-[#3A2A1A] dark:text-[#C8B89A]">
                            <CheckCircle2 class="w-5 h-5 text-[#2D5A3D] dark:text-[#5AAA72] flex-shrink-0" /> Multi-Campus Management
                        </li>
                    </ul>
                    <button class="w-full py-3.5 rounded-xl border border-[#2D5A3D]/30 dark:border-[#5AAA72]/30 text-[#2D5A3D] dark:text-[#5AAA72] font-body font-semibold text-sm hover:bg-[#2D5A3D]/5 transition-colors">
                        Contact Sales
                    </button>
                </div>
            </div>
        </div>
    </section>

    <!-- ═══════════════════ TESTIMONIALS ═══════════════════ -->
    <section id="testimonials" class="py-32 md:py-44 relative">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center mb-20">
                <p class="font-body text-xs uppercase tracking-[0.3em] font-semibold text-forest dark:text-[#5AAA72] mb-6">Voices from the Field</p>
                <h3 class="font-display text-5xl md:text-6xl font-black text-[#1A1612] dark:text-[#F5F0E8] mb-4 leading-tight">
                    Trusted by Education<br/>Leaders Across Kenya
                </h3>
            </div>

            <div class="max-w-4xl mx-auto relative">
                <div class="card-warm rounded-[2.5rem] p-12 md:p-16 text-center relative overflow-hidden">
                    <!-- Decorative quote mark -->
                    <div class="absolute top-8 left-10 font-display text-9xl text-[#C4622D]/8 leading-none select-none">"</div>

                    <div class="relative z-10">
                        <!-- Stars -->
                        <div class="flex justify-center gap-1 mb-8">
                            <Star v-for="i in 5" :key="i" class="w-5 h-5 fill-[#B5860A] text-[#B5860A]" />
                        </div>

                        <!-- Quote -->
                        <blockquote class="font-display text-2xl md:text-3xl font-bold text-[#1A1612] dark:text-[#F5F0E8] mb-10 leading-snug">
                            "{{ testimonials[activeTestimonial].quote }}"
                        </blockquote>

                        <!-- Author -->
                        <div class="flex items-center justify-center gap-4">
                            <div class="testimonial-avatar w-12 h-12 rounded-full flex items-center justify-center text-white font-body font-bold text-sm shadow-lg">
                                {{ testimonials[activeTestimonial].initials }}
                            </div>
                            <div class="text-left">
                                <div class="font-display font-bold text-[#C4622D] dark:text-[#E07A45] text-lg leading-none mb-1">
                                    {{ testimonials[activeTestimonial].name }}
                                </div>
                                <div class="font-body text-xs uppercase tracking-[0.15em] text-[#5A4030] dark:text-[#A89070]">
                                    {{ testimonials[activeTestimonial].role }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Nav buttons -->
                <button @click="prevTestimonial" class="absolute top-1/2 -translate-y-1/2 -left-6 md:-left-7 card-warm p-4 rounded-2xl hover:shadow-md hover:border-[#C4622D]/30 transition-all border border-warm shadow-sm">
                    <ChevronLeft class="w-5 h-5 text-[#1A1612] dark:text-[#F5F0E8]" />
                </button>
                <button @click="nextTestimonial" class="absolute top-1/2 -translate-y-1/2 -right-6 md:-right-7 card-warm p-4 rounded-2xl hover:shadow-md hover:border-[#C4622D]/30 transition-all border border-warm shadow-sm">
                    <ChevronRight class="w-5 h-5 text-[#1A1612] dark:text-[#F5F0E8]" />
                </button>

                <!-- Dot indicators -->
                <div class="flex justify-center gap-2 mt-8">
                    <button
                        v-for="(_, i) in testimonials"
                        :key="i"
                        @click="activeTestimonial = i"
                        :class="['w-2 h-2 rounded-full transition-all duration-300', i === activeTestimonial ? 'bg-[#C4622D] w-6' : 'bg-[#C4622D]/25']"
                    />
                </div>
            </div>
        </div>
    </section>

    <!-- ═══════════════════ CTA ═══════════════════ -->
    <section class="py-24 md:py-36 px-6 relative">
        <div class="max-w-7xl mx-auto">
            <div class="cta-section cta-pattern relative rounded-[3rem] overflow-hidden px-10 py-24 md:p-28 text-center">
                <!-- Decorative glow orbs inside CTA -->
                <div class="absolute top-0 left-1/4 w-64 h-64 rounded-full blur-[80px] opacity-20" style="background: #C4622D;" />
                <div class="absolute bottom-0 right-1/4 w-48 h-48 rounded-full blur-[60px] opacity-15" style="background: #B5860A;" />

                <!-- Kenyan flag stripe accent -->
                <div class="absolute top-0 left-0 right-0 h-1 flex">
                    <div class="flex-1 bg-[#C4622D]"></div>
                    <div class="flex-1 bg-white/20"></div>
                    <div class="flex-1 bg-[#2D5A3D]"></div>
                    <div class="flex-1 bg-white/20"></div>
                    <div class="flex-1 bg-[#1A1612]"></div>
                </div>

                <div class="relative z-10 max-w-3xl mx-auto">
                    <p class="font-body text-xs uppercase tracking-[0.3em] font-semibold text-[#E07A45] mb-6">Begin Today</p>
                    <h3 class="font-display text-5xl md:text-7xl font-black text-warm-text dark:text-white mb-8 leading-none">
                        Ready for the<br/>Transformation?
                    </h3>
                    <p class="font-body text-lg text-warm-text text-dark-text dark:text-white/60 mb-12 leading-relaxed">
                        Join 500+ forward-thinking schools that have already elevated their institutions with EduManage Pro.
                    </p>
                    <div class="flex justify-center gap-5 flex-wrap">
                        <template v-if="!$page.props.auth.user">
                            <Button
                                size="lg"
                                class="accent-terracotta border-0 text-white font-body font-bold text-base px-10 py-6 rounded-2xl shadow-xl shadow-[#C4622D]/40 hover:shadow-[#C4622D]/60 hover:scale-105 transition-all duration-300"
                                as-child
                            >
                                <Link :href="route('register')" class="flex items-center gap-2">
                                    Get Started Free
                                    <ArrowRight class="w-5 h-5" />
                                </Link>
                            </Button>
                        </template>
                        <button class="inline-flex items-center gap-2 border dark:border-white text-warm-text text-dark-text dark:text-white font-body font-semibold text-base px-10 py-4 rounded-2xl hover:bg-white/10 transition-all">
                            <PlayCircle class="w-5 h-5" />
                            Book a Demo
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ═══════════════════ FOOTER ═══════════════════ -->
    <footer class="bg-[#100D08] dark:bg-[#0A0806] text-[#8A7060] border-t border-[#2A1E14] py-16">
        <div class="max-w-7xl mx-auto px-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-12 mb-12">
                <!-- Brand -->
                <div>
                    <div class="flex items-center gap-3 mb-5">
                        <div class="w-10 h-10 accent-terracotta rounded-xl flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                        </div>
                        <div>
                            <div class="font-display text-lg font-bold text-[#F5EEE0]">EduManage Pro</div>
                            <div class="font-body text-[10px] text-[#C4622D] uppercase tracking-widest font-semibold">Nairobi, Kenya</div>
                        </div>
                    </div>
                    <p class="font-body text-sm leading-relaxed">
                        The trusted school management platform built for East Africa's finest institutions.
                    </p>
                </div>

                <div>
                    <h4 class="font-body font-semibold text-[#F5EEE0] text-sm mb-5 uppercase tracking-widest">Platform</h4>
                    <ul class="space-y-3 text-sm">
                        <li><a href="#features" class="hover:text-[#E07A45] transition-colors">Features</a></li>
                        <li><a href="#pricing" class="hover:text-[#E07A45] transition-colors">Pricing</a></li>
                        <li><a href="#testimonials" class="hover:text-[#E07A45] transition-colors">Testimonials</a></li>
                        <li><a href="#" class="hover:text-[#E07A45] transition-colors">Integrations</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="font-body font-semibold text-[#F5EEE0] text-sm mb-5 uppercase tracking-widest">Company</h4>
                    <ul class="space-y-3 text-sm">
                        <li><a href="#" class="hover:text-[#E07A45] transition-colors">About Us</a></li>
                        <li><a href="#" class="hover:text-[#E07A45] transition-colors">Blog</a></li>
                        <li><a href="#" class="hover:text-[#E07A45] transition-colors">Careers</a></li>
                        <li><a href="#" class="hover:text-[#E07A45] transition-colors">Press Kit</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="font-body font-semibold text-[#F5EEE0] text-sm mb-5 uppercase tracking-widest">Support</h4>
                    <ul class="space-y-3 text-sm">
                        <li><a href="#" class="hover:text-[#E07A45] transition-colors">Help Centre</a></li>
                        <li><a href="#" class="hover:text-[#E07A45] transition-colors">Contact Us</a></li>
                        <li><a href="#" class="hover:text-[#E07A45] transition-colors">System Status</a></li>
                        <li><a href="#" class="hover:text-[#E07A45] transition-colors">Privacy Policy</a></li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-[#2A1E14] pt-8 flex flex-col md:flex-row justify-between items-center gap-4">
                <p class="font-body text-sm">
                    &copy; 2025 EduManage Pro. All rights reserved. · Nairobi, Kenya 🇰🇪
                </p>
                <div class="flex items-center gap-2 font-body text-xs text-[#5A4030]">
                    <div class="w-1.5 h-1.5 rounded-full bg-[#2D5A3D]"></div>
                    <span>All systems operational</span>
                </div>
            </div>
        </div>
    </footer>

    </div>
</div>
</template>