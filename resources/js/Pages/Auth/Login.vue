<script setup>
import { Checkbox } from '@/Components/ui/checkbox';
import { Input } from '@/Components/ui/input';
import { Label } from '@/Components/ui/label';
import { Button } from '@/Components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/Components/ui/card';
import { Alert, AlertDescription } from '@/Components/ui/alert';
import { Mail, Lock, Eye, EyeOff, ArrowLeft, ArrowRight, ShieldCheck, Zap, BarChart3, GraduationCap } from 'lucide-vue-next';
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
    <Head title="Login - EduManage Pro" />

    <div class="min-h-screen flex bg-warm-bg bg-dark-bg transition-colors duration-500">
        <!-- Left Side - Branding (hidden on mobile) -->
        <div class="hidden lg:flex lg:w-[45%] p-16 flex-col justify-between relative overflow-hidden">
            <!-- Decorative Orbs -->
            <div class="absolute -top-[10%] -left-[10%] w-[60%] h-[60%] bg-terracotta/10 blur-[60px] rounded-full animate-float" />
            <div class="absolute -bottom-[10%] right-[10%] w-[50%] h-[50%] bg-amber-500/10 blur-[60px] rounded-full animate-float" style="animation-delay: 2s" />
            
            <div class="relative z-10">
                <!-- Logo -->
                <Link href="/" class="flex items-center space-x-4 mb-20 group">
                    <div class="w-14 h-14 bg-terracotta rounded-2xl flex items-center justify-center shadow-2xl shadow-terracotta/20 transform transition-all duration-500 group-hover:scale-110">
                        <GraduationCap class="w-8 h-8 text-white" />
                    </div>
                    <div>
                        <h1 class="text-3xl font-black text-warm-text text-dark-text">
                            EduManage <span class="text-terracotta">Pro</span>
                        </h1>
                        <p class="text-[10px] uppercase tracking-[0.2em] text-warm-muted text-dark-muted font-bold">Nairobi · Kenya</p>
                    </div>
                </Link>

                <!-- Welcome Content -->
                <div class="space-y-16 max-w-lg">
                    <div class="animate-fade-in-up">
                        <h2 class="text-6xl font-black text-warm-text text-dark-text mb-6 tracking-tighter leading-tight">
                            Welcome <br/> Back.
                        </h2>
                        <p class="text-xl text-warm-muted text-dark-muted font-medium leading-relaxed">
                            Sign in to your specialized dashboard to continue managing your institution's excellence.
                        </p>
                    </div>

                    <div class="space-y-8 animate-fade-in-up" style="animation-delay: 0.1s">
                        <div class="flex items-start space-x-6 group">
                            <div class="w-12 h-12 bg-warm-bg border border-warm-border shadow-sm rounded-2xl flex items-center justify-center flex-shrink-0 transition-transform duration-300 group-hover:scale-110 group-hover:bg-terracotta/10">
                                <ShieldCheck class="w-6 h-6 text-terracotta" />
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-warm-text text-dark-text mb-1">Secure Ecosystem</h3>
                                <p class="text-sm text-warm-muted text-dark-muted font-medium">Enterprise-grade data encryption.</p>
                            </div>
                        </div>

                        <div class="flex items-start space-x-6 group">
                            <div class="w-12 h-12 bg-warm-bg border border-warm-border shadow-sm rounded-2xl flex items-center justify-center flex-shrink-0 transition-transform duration-300 group-hover:scale-110 group-hover:bg-terracotta/10">
                                <Zap class="w-6 h-6 text-terracotta" />
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-warm-text text-dark-text mb-1">Optimized Speed</h3>
                                <p class="text-sm text-warm-muted text-dark-muted font-medium">Lightning-fast cloud synchronization.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer Stats -->
            <div class="bg-warm-bg border border-warm-border shadow-sm p-8 rounded-[2rem] animate-fade-in-up" style="animation-delay: 0.2s">
                <div class="grid grid-cols-3 gap-4">
                    <div class="text-center">
                        <div class="text-2xl font-black text-terracotta">1k+</div>
                        <div class="text-[10px] uppercase font-bold text-warm-muted text-dark-muted tracking-wider">Schools</div>
                    </div>
                    <div class="text-center border-x border-warm-border border-dark-border">
                        <div class="text-2xl font-black text-terracotta">500k</div>
                        <div class="text-[10px] uppercase font-bold text-warm-muted text-dark-muted tracking-wider">Students</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-black text-terracotta">99.9%</div>
                        <div class="text-[10px] uppercase font-bold text-warm-muted text-dark-muted tracking-wider">Uptime</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Side - Login Form -->
        <div class="w-full lg:w-[55%] flex items-center justify-center p-6 md:p-12 relative">
            <div class="w-full max-w-lg animate-scale-up">
                <!-- Mobile Logo -->
                <div class="lg:hidden mb-12 text-center overflow-visible">
                    <Link href="/" class="inline-flex items-center space-x-4 group">
                        <div class="w-14 h-14 bg-terracotta rounded-2xl flex items-center justify-center shadow-xl transform transition-all duration-300 group-hover:scale-110">
                            <GraduationCap class="w-8 h-8 text-white" />
                        </div>
                        <span class="text-3xl font-black text-warm-text text-dark-text">
                            EduManage <span class="text-terracotta">Pro</span>
                        </span>
                    </Link>
                </div>

                <div class="bg-warm-bg bg-dark-bg border border-warm-border border-dark-border shadow-sm rounded-xl p-10 md:p-14 rounded-[3rem] relative">
                    <!-- Glow effect -->
                    <div class="absolute -top-24 -right-24 w-48 h-48 bg-terracotta/10 blur-[50px] rounded-full pointer-events-none" />
                    <div class="absolute -bottom-24 -left-24 w-48 h-48 bg-amber-500/10 blur-[50px] rounded-full pointer-events-none" />

                    <div class="relative z-10">
                        <div class="mb-10 text-center lg:text-left">
                            <h3 class="text-4xl font-black text-warm-text text-dark-text mb-3 tracking-tighter">Sign In</h3>
                            <p class="text-warm-muted text-dark-muted font-medium">Continue your educational evolution.</p>
                        </div>

                        <!-- Status Message -->
                        <Alert v-if="status" class="mb-8 bg-background border border-border shadow-sm bg-emerald-500/5 border-emerald-500/20 text-emerald-600 dark:text-emerald-400 rounded-2xl">
                            <AlertDescription>{{ status }}</AlertDescription>
                        </Alert>

                        <form @submit.prevent="submit" class="space-y-8">
                            <div class="space-y-3">
                                <Label for="email" class="text-sm font-bold text-warm-text text-dark-text ml-1">Email Address</Label>
                                <div class="relative group">
                                    <div class="absolute left-4 top-1/2 -translate-y-1/2 text-warm-muted transition-colors group-focus-within:text-terracotta">
                                        <Mail class="w-5 h-5" />
                                    </div>
                                    <Input
                                        id="email"
                                        type="email"
                                        v-model="form.email"
                                        placeholder="admin@edu.pro"
                                        required
                                        autofocus
                                        class="h-14 pl-12 bg-warm-bg bg-dark-bg border border-warm-border border-dark-border shadow-sm rounded-2xl focus:ring-2 focus:ring-terracotta/20 transition-all text-base font-medium"
                                        autocomplete="username"
                                        :class="{ 'border-red-500/50': form.errors.email }"
                                    />
                                </div>
                                <p v-if="form.errors.email" class="text-xs text-destructive font-bold ml-1 animate-fade-in">{{ form.errors.email }}</p>
                            </div>

                            <div class="space-y-3">
                                <div class="flex justify-between items-center ml-1">
                                    <Label for="password" class="text-sm font-bold text-warm-text text-dark-text">Password</Label>
                                    <Link v-if="canResetPassword" :href="route('password.request')" class="text-xs font-bold text-terracotta hover:underline">
                                        Forgot?
                                    </Link>
                                </div>
                                <div class="relative group">
                                    <div class="absolute left-4 top-1/2 -translate-y-1/2 text-warm-muted transition-colors group-focus-within:text-terracotta">
                                        <Lock class="w-5 h-5" />
                                    </div>
                                    <Input
                                        id="password"
                                        :type="showPassword ? 'text' : 'password'"
                                        v-model="form.password"
                                        placeholder="••••••••"
                                        required
                                        class="h-14 pl-12 pr-12 bg-warm-bg bg-dark-bg border border-warm-border border-dark-border shadow-sm rounded-2xl focus:ring-2 focus:ring-terracotta/20 transition-all text-base font-medium"
                                        autocomplete="current-password"
                                        :class="{ 'border-red-500/50': form.errors.password }"
                                    />
                                    <button type="button" @click="showPassword = !showPassword" class="absolute right-4 top-1/2 -translate-y-1/2 text-warm-muted hover:text-terracotta transition-colors">
                                        <Eye v-if="!showPassword" class="w-5 h-5" />
                                        <EyeOff v-else class="w-5 h-5" />
                                    </button>
                                </div>
                                <p v-if="form.errors.password" class="text-xs text-destructive font-bold ml-1 animate-fade-in">{{ form.errors.password }}</p>
                            </div>

                            <div class="flex items-center space-x-3 ml-1">
                                <Checkbox id="remember" v-model:checked="form.remember" class="w-5 h-5 rounded-lg border-warm-border border-dark-border data-[state=checked]:bg-terracotta data-[state=checked]:border-terracotta" />
                                <Label for="remember" class="text-sm font-bold text-warm-muted text-dark-muted cursor-pointer">Stay signed in</Label>
                            </div>

                            <Button
                                type="submit"
                                class="w-full h-14 text-white font-black text-lg bg-terracotta hover:bg-terracotta/90 shadow-xl shadow-terracotta/20 rounded-2xl transition-all duration-300 hover:scale-[1.02] flex items-center justify-center gap-3"
                                :disabled="form.processing"
                            >
                                <span v-if="!form.processing">Secure Sign In</span>
                                <div v-else class="flex items-center gap-2">
                                    <div class="w-5 h-5 border-2 border-white/30 border-t-white rounded-full animate-spin" />
                                    <span>Authorizing...</span>
                                </div>
                                <ArrowRight v-if="!form.processing" class="w-5 h-5" />
                            </Button>
                        </form>
                    </div>
                </div>

                <!-- Footer Actions -->
                <div class="mt-8 flex flex-col md:flex-row items-center justify-between gap-4 px-4">
                    <p class="text-sm font-bold text-warm-muted text-dark-muted">
                        New here?
                        <Link :href="route('register')" class="text-terracotta hover:underline ml-1">Create an account</Link>
                    </p>
                    <Link href="/" class="text-sm font-bold text-warm-muted text-dark-muted hover:text-terracotta flex items-center gap-2">
                        <ArrowLeft class="w-4 h-4" />
                        Back to site
                    </Link>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
/* Minimal scoped CSS for specialized effects */
.shadow-glow {
    filter: drop-shadow(0 0 10px rgba(79, 70, 229, 0.4));
}
</style>