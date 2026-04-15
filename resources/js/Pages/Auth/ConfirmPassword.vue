<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import { Input } from '@/Components/ui/input';
import { Label } from '@/Components/ui/label';
import { Button } from '@/Components/ui/button';
import { Card, CardContent, CardDescription, CardFooter, CardHeader, CardTitle } from '@/Components/ui/card';
import { Head, Link, useForm } from '@inertiajs/vue3';

const form = useForm({
    password: '',
});

const submit = () => {
    form.post(route('password.confirm'), {
        onFinish: () => form.reset(),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Confirm Password" />

        <Card class="w-full max-w-md mx-auto card-warm">
            <CardHeader class="space-y-1">
                <CardTitle class="text-2xl text-center text-warm-text text-dark-text">Confirm Password</CardTitle>
                <CardDescription class="text-center text-warm-muted text-dark-muted">
                    This is a secure area of the application. Please confirm your password before continuing.
                </CardDescription>
            </CardHeader>
            
            <CardContent>
                <form @submit.prevent="submit" class="space-y-4">
                    <div class="space-y-2">
                        <Label for="password">Password</Label>
                        <Input
                            id="password"
                            type="password"
                            v-model="form.password"
                            placeholder="Enter your password"
                            required
                            autocomplete="current-password"
                            autofocus
                            :class="{ 'border-destructive': form.errors.password }"
                        />
                        <p v-if="form.errors.password" class="text-sm text-destructive">
                            {{ form.errors.password }}
                        </p>
                    </div>

                    <Button 
                        type="submit" 
                        class="w-full" 
                        :disabled="form.processing"
                    >
                        <span v-if="form.processing">Confirming...</span>
                        <span v-else>Confirm</span>
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
