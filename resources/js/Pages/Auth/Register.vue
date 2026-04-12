<script setup>
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Alert, AlertDescription } from '@/components/ui/alert';
import { User, Mail, Lock, Eye, EyeOff, ArrowLeft, ArrowRight, ShieldCheck, Zap, BarChart3, GraduationCap, CheckCircle2 } from 'lucide-vue-next';
import DarkModeToggle from '@/Components/DarkModeToggle.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref, computed } from 'vue';

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
});

const showPassword = ref(false);
const showConfirmPassword = ref(false);

const passwordStrength = computed(() => {
    const password = form.password;
    if (!password) return { score: 0, label: '', color: '' };
    
    let score = 0;
    if (password.length >= 8) score++;
    if (password.length >= 12) score++;
    if (/[a-z]/.test(password)) score++;
    if (/[A-Z]/.test(password)) score++;
    if (/[0-9]/.test(password)) score++;
    if (/[^a-zA-Z0-9]/.test(password)) score++;
    
    if (score <= 2) return { score, label: 'Weak', color: 'bg-red-500 shadow-[0_0_10px_rgba(239,68,68,0.4)]' };
    if (score <= 4) return { score, label: 'Fair', color: 'bg-yellow-500 shadow-[0_0_10px_rgba(234,179,8,0.4)]' };
    if (score <= 5) return { score, label: 'Good', color: 'bg-indigo-500 shadow-[0_0_10px_rgba(99,102,241,0.4)]' };
    return { score, label: 'Extremely Strong', color: 'bg-emerald-500 shadow-[0_0_10px_rgba(16,185,129,0.4)]' };
});

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <Head title="Register - EduManage Pro" />

    <div class="min-h-screen flex bg-mesh dark:bg-slate-950 transition-colors duration-500">
        <!-- Left Side - Branding (hidden on mobile) -->
        <div class="hidden lg:flex lg:w-[45%] p-16 flex-col justify-between relative overflow-hidden">
            <!-- Decorative Orbs -->
            <div class="absolute -top-[10%] -left-[10%] w-[60%] h-[60%] bg-emerald-600/10 blur-[100px] rounded-full animate-float" />
            <div class="absolute -bottom-[10%] right-[10%] w-[50%] h-[50%] bg-indigo-600/10 blur-[100px] rounded-full animate-float" style="animation-delay: 2s" />
            
            <div class="relative z-10">
                <!-- Logo -->
                <Link href="/" class="flex items-center space-x-4 mb-20 group">
                    <div class="w-14 h-14 bg-gradient-to-br from-indigo-600 to-emerald-600 rounded-2xl flex items-center justify-center shadow-2xl shadow-indigo-500/20 transform transition-all duration-500 group-hover:scale-110">
                        <GraduationCap class="w-8 h-8 text-white" />
                    </div>
                    <div>
                        <h1 class="text-3xl font-black bg-clip-text text-transparent bg-gradient-to-r from-indigo-600 to-emerald-600 dark:from-indigo-400 dark:to-emerald-400">
                            EduManage Pro
                        </h1>
                        <p class="text-[10px] uppercase tracking-[0.2em] text-gray-400 font-bold">The Gold Standard</p>
                    </div>
                </Link>

                <!-- Onboarding Content -->
                <div class="space-y-16 max-w-lg">
                    <div class="animate-fade-in-up">
                        <h2 class="text-6xl font-black text-gray-900 dark:text-white mb-6 tracking-tighter leading-tight">
                            Start Your <br/> Legacy.
                        </h2>
                        <p class="text-xl text-gray-500 dark:text-gray-400 font-medium leading-relaxed">
                            Join over 500+ institutions worldwide and experience the most advanced school management system ever built.
                        </p>
                    </div>

                    <div class="space-y-8 animate-fade-in-up" style="animation-delay: 0.1s">
                        <div class="flex items-start space-x-6 group">
                            <div class="w-12 h-12 glass rounded-2xl flex items-center justify-center flex-shrink-0 transition-transform duration-300 group-hover:scale-110 group-hover:bg-emerald-50 dark:group-hover:bg-emerald-950">
                                <CheckCircle2 class="w-6 h-6 text-emerald-600 dark:text-emerald-400" />
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-1">Instant Activation</h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400 font-medium">30-day free trial. No credit card required.</p>
                            </div>
                        </div>

                        <div class="flex items-start space-x-6 group">
                            <div class="w-12 h-12 glass rounded-2xl flex items-center justify-center flex-shrink-0 transition-transform duration-300 group-hover:scale-110 group-hover:bg-indigo-50 dark:group-hover:bg-indigo-950">
                                <Zap class="w-6 h-6 text-indigo-600 dark:text-indigo-400" />
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-1">Auto-Configuration</h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400 font-medium">Smart setup tailored to your school type.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Social Proof -->
            <div class="glass p-8 rounded-[2rem] border-white/20 animate-fade-in-up" style="animation-delay: 0.2s">
                <div class="flex items-center space-x-4">
                    <div class="flex -space-x-3">
                        <div v-for="i in 4" :key="i" class="w-10 h-10 rounded-full border-2 border-white bg-gray-200 dark:border-slate-900" />
                    </div>
                    <p class="text-xs font-bold text-gray-500 dark:text-gray-400">
                        Join <span class="text-indigo-600 dark:text-indigo-400 text-sm font-black">1.2M+ users</span> already elevating education.
                    </p>
                </div>
            </div>
        </div>

        <!-- Right Side - Registration Form -->
        <div class="w-full lg:w-[55%] flex items-center justify-center p-6 md:p-12 relative">
            <div class="absolute top-8 right-8 z-20">
                <DarkModeToggle variant="ghost" class="glass rounded-xl h-12 w-12" />
            </div>

            <div class="w-full max-w-lg animate-scale-up">
                <!-- Mobile Logo -->
                <div class="lg:hidden mb-12 text-center">
                    <Link href="/" class="inline-flex items-center space-x-4 group">
                        <div class="w-14 h-14 bg-gradient-to-br from-indigo-600 to-emerald-600 rounded-2xl flex items-center justify-center shadow-xl transform transition-all duration-300 group-hover:scale-110">
                            <GraduationCap class="w-8 h-8 text-white" />
                        </div>
                        <span class="text-3xl font-black bg-clip-text text-transparent bg-gradient-to-r from-indigo-600 to-emerald-600">
                            EduManage Pro
                        </span>
                    </Link>
                </div>

                <div class="glass-card p-10 md:p-14 rounded-[3rem] border-white/20 relative">
                    <div class="relative z-10">
                        <div class="mb-10 text-center lg:text-left">
                            <h3 class="text-4xl font-black text-gray-900 dark:text-white mb-3 tracking-tighter">Register</h3>
                            <p class="text-gray-500 dark:text-gray-400 font-medium">Create your institutional legacy.</p>
                        </div>

                        <form @submit.prevent="submit" class="space-y-6">
                            <!-- Full Name -->
                            <div class="space-y-2">
                                <Label for="name" class="text-sm font-bold text-gray-700 dark:text-gray-300 ml-1">Full Name</Label>
                                <div class="relative group">
                                    <div class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-indigo-600 transition-colors">
                                        <User class="w-5 h-5" />
                                    </div>
                                    <Input
                                        id="name"
                                        v-model="form.name"
                                        placeholder="Principal Name / Admin Name"
                                        required
                                        autofocus
                                        class="h-14 pl-12 glass bg-white/50 dark:bg-slate-900/50 rounded-2xl border-white/10 focus:ring-2 focus:ring-indigo-500/20 transition-all font-medium"
                                        :class="{ 'border-red-500/50': form.errors.name }"
                                    />
                                </div>
                                <p v-if="form.errors.name" class="text-xs text-red-500 font-bold ml-1">{{ form.errors.name }}</p>
                            </div>

                            <!-- Email Address -->
                            <div class="space-y-2">
                                <Label for="email" class="text-sm font-bold text-gray-700 dark:text-gray-300 ml-1">Email Address</Label>
                                <div class="relative group">
                                    <div class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-indigo-600 transition-colors">
                                        <Mail class="w-5 h-5" />
                                    </div>
                                    <Input
                                        id="email"
                                        type="email"
                                        v-model="form.email"
                                        placeholder="admin@school-domain.com"
                                        required
                                        class="h-14 pl-12 glass bg-white/50 dark:bg-slate-900/50 rounded-2xl border-white/10 focus:ring-2 focus:ring-indigo-500/20 transition-all font-medium"
                                        :class="{ 'border-red-500/50': form.errors.email }"
                                    />
                                </div>
                                <p v-if="form.errors.email" class="text-xs text-red-500 font-bold ml-1">{{ form.errors.email }}</p>
                            </div>

                            <!-- Password -->
                            <div class="space-y-2">
                                <Label for="password" class="text-sm font-bold text-gray-700 dark:text-gray-300 ml-1">Secure Password</Label>
                                <div class="relative group">
                                    <div class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-indigo-600 transition-colors">
                                        <Lock class="w-5 h-5" />
                                    </div>
                                    <Input
                                        id="password"
                                        :type="showPassword ? 'text' : 'password'"
                                        v-model="form.password"
                                        placeholder="Create a strong password"
                                        required
                                        class="h-14 pl-12 pr-12 glass bg-white/50 dark:bg-slate-900/50 rounded-2xl border-white/10 focus:ring-2 focus:ring-indigo-500/20 transition-all font-medium"
                                        :class="{ 'border-red-500/50': form.errors.password }"
                                    />
                                    <button type="button" @click="showPassword = !showPassword" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-indigo-600 transition-colors">
                                        <Eye v-if="!showPassword" class="w-5 h-5" />
                                        <EyeOff v-else class="w-5 h-5" />
                                    </button>
                                </div>
                                
                                <!-- Visual Password Strength -->
                                <div v-if="form.password" class="px-1 pt-1 space-y-2 animate-fade-in">
                                    <div class="flex gap-1.5">
                                        <div 
                                            v-for="i in 6" 
                                            :key="i"
                                            class="h-1.5 flex-1 rounded-full bg-gray-200 dark:bg-gray-800 overflow-hidden"
                                        >
                                            <div 
                                                class="h-full transition-all duration-500"
                                                :class="[i <= passwordStrength.score ? passwordStrength.color : 'w-0']"
                                            />
                                        </div>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <span class="text-[10px] font-black uppercase tracking-wider" :class="passwordStrength.score > 2 ? 'text-indigo-600 dark:text-indigo-400' : 'text-red-500'">
                                            Strength: {{ passwordStrength.label }}
                                        </span>
                                    </div>
                                </div>
                                <p v-if="form.errors.password" class="text-xs text-red-500 font-bold ml-1">{{ form.errors.password }}</p>
                            </div>

                            <!-- Password Confirmation -->
                            <div class="space-y-2">
                                <Label for="password_confirmation" class="text-sm font-bold text-gray-700 dark:text-gray-300 ml-1">Confirm Password</Label>
                                <div class="relative group">
                                    <div class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-violet-600 transition-colors">
                                        <ShieldCheck class="w-5 h-5" />
                                    </div>
                                    <Input
                                        id="password_confirmation"
                                        :type="showConfirmPassword ? 'text' : 'password'"
                                        v-model="form.password_confirmation"
                                        placeholder="Repeat your password"
                                        required
                                        class="h-14 pl-12 pr-12 glass bg-white/50 dark:bg-slate-900/50 rounded-2xl border-white/10 focus:ring-2 focus:ring-violet-500/20 transition-all font-medium"
                                        :class="{ 'border-red-500/50': form.errors.password_confirmation }"
                                    />
                                    <button type="button" @click="showConfirmPassword = !showConfirmPassword" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-violet-600 transition-colors">
                                        <Eye v-if="!showConfirmPassword" class="w-5 h-5" />
                                        <EyeOff v-else class="w-5 h-5" />
                                    </button>
                                </div>
                            </div>

                            <Button
                                type="submit"
                                class="w-full h-14 mt-4 text-white font-black text-lg bg-gradient-to-r from-indigo-600 to-emerald-600 hover:from-indigo-700 hover:to-emerald-700 shadow-xl shadow-indigo-500/20 rounded-2xl transition-all duration-300 hover:scale-[1.02] flex items-center justify-center gap-3"
                                :disabled="form.processing"
                            >
                                <span v-if="!form.processing">Create Academy Profile</span>
                                <div v-else class="flex items-center gap-2">
                                    <div class="w-5 h-5 border-2 border-white/30 border-t-white rounded-full animate-spin" />
                                    <span>Building Database...</span>
                                </div>
                                <ArrowRight v-if="!form.processing" class="w-5 h-5" />
                            </Button>
                        </form>
                    </div>
                </div>

                <!-- Footer Actions -->
                <div class="mt-8 flex flex-col md:flex-row items-center justify-between gap-4 px-4 pb-8">
                    <p class="text-sm font-bold text-gray-500 dark:text-gray-400">
                        Already registered?
                        <Link :href="route('login')" class="text-indigo-600 dark:text-indigo-400 hover:underline ml-1 font-black">Sign in here</Link>
                    </p>
                    <Link href="/" class="text-sm font-bold text-gray-500 dark:text-gray-400 hover:text-indigo-600 flex items-center gap-2">
                        <ArrowLeft class="w-4 h-4" />
                        Back to site
                    </Link>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
/* Scoped effects */
.shadow-glow {
    filter: drop-shadow(0 0 10px rgba(16, 185, 129, 0.4));
}
</style>