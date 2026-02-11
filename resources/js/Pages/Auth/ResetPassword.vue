<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardFooter, CardHeader, CardTitle } from '@/components/ui/card';
import { Head, Link, useForm } from '@inertiajs/vue3';

const props = defineProps({
    email: {
        type: String,
        required: true,
    },
    token: {
        type: String,
        required: true,
    },
});

const form = useForm({
    token: props.token,
    email: props.email,
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('password.store'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Reset Password" />

        <Card class="w-full max-w-md mx-auto">
            <CardHeader class="space-y-1">
                <CardTitle class="text-2xl text-center">Reset Password</CardTitle>
                <CardDescription class="text-center">
                    Enter your new password below
                </CardDescription>
            </CardHeader>
            
            <CardContent>
                <form @submit.prevent="submit" class="space-y-4">
                    <div class="space-y-2">
                        <Label for="email">Email</Label>
                        <Input
                            id="email"
                            type="email"
                            v-model="form.email"
                            placeholder="Enter your email"
                            required
                            autofocus
                            autocomplete="username"
                            :class="{ 'border-destructive': form.errors.email }"
                        />
                        <p v-if="form.errors.email" class="text-sm text-destructive">
                            {{ form.errors.email }}
                        </p>
                    </div>

                    <div class="space-y-2">
                        <Label for="password">New Password</Label>
                        <Input
                            id="password"
                            type="password"
                            v-model="form.password"
                            placeholder="Enter your new password"
                            required
                            autocomplete="new-password"
                            :class="{ 'border-destructive': form.errors.password }"
                        />
                        <p v-if="form.errors.password" class="text-sm text-destructive">
                            {{ form.errors.password }}
                        </p>
                    </div>

                    <div class="space-y-2">
                        <Label for="password_confirmation">Confirm New Password</Label>
                        <Input
                            id="password_confirmation"
                            type="password"
                            v-model="form.password_confirmation"
                            placeholder="Confirm your new password"
                            required
                            autocomplete="new-password"
                            :class="{ 'border-destructive': form.errors.password_confirmation }"
                        />
                        <p v-if="form.errors.password_confirmation" class="text-sm text-destructive">
                            {{ form.errors.password_confirmation }}
                        </p>
                    </div>

                    <Button 
                        type="submit" 
                        class="w-full" 
                        :disabled="form.processing"
                    >
                        <span v-if="form.processing">Resetting...</span>
                        <span v-else>Reset Password</span>
                    </Button>
                </form>
            </CardContent>
            
            <CardFooter class="flex flex-col space-y-2">
                <div class="text-center text-sm text-muted-foreground">
                    <Link 
                        :href="route('login')" 
                        class="text-primary hover:underline underline-offset-4"
                    >
                        Back to login
                    </Link>
                </div>
            </CardFooter>
        </Card>
    </GuestLayout>
</template>
