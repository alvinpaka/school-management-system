<script setup>
import { computed } from 'vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardFooter, CardHeader, CardTitle } from '@/components/ui/card';
import { Head, Link, useForm } from '@inertiajs/vue3';

const props = defineProps({
    status: {
        type: String,
    },
});

const form = useForm({});

const submit = () => {
    form.post(route('verification.send'));
};

const verificationLinkSent = computed(
    () => props.status === 'verification-link-sent',
);
</script>

<template>
    <GuestLayout>
        <Head title="Email Verification" />

        <Card class="w-full max-w-md mx-auto card-warm">
            <CardHeader class="space-y-1">
                <CardTitle class="text-2xl text-center text-warm-text text-dark-text">Verify Your Email</CardTitle>
                <CardDescription class="text-center text-warm-muted text-dark-muted">
                    Thanks for signing up! Please check your email for a verification link
                </CardDescription>
            </CardHeader>
            
            <CardContent>
                <div
                    class="mb-4 text-sm font-medium text-terracotta bg-terracotta/10 dark:bg-terracotta/20 p-3 rounded-md"
                    v-if="verificationLinkSent"
                >
                    A new verification link has been sent to the email address you
                    provided during registration.
                </div>

                <p class="text-sm text-muted-foreground mb-6">
                    Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn't receive the email, we will gladly send you another.
                </p>

                <form @submit.prevent="submit">
                    <Button 
                        type="submit" 
                        class="w-full mb-4" 
                        :disabled="form.processing"
                    >
                        <span v-if="form.processing">Sending...</span>
                        <span v-else>Resend Verification Email</span>
                    </Button>
                </form>
            </CardContent>
            
            <CardFooter class="flex flex-col space-y-2">
                <div class="text-center text-sm text-muted-foreground">
                    <Link
                        :href="route('logout')"
                        method="post"
                        as="button"
                        class="text-primary hover:underline underline-offset-4"
                    >
                        Log Out
                    </Link>
                </div>
            </CardFooter>
        </Card>
    </GuestLayout>
</template>
