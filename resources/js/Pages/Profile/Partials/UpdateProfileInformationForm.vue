<script setup>
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Link, useForm, usePage } from '@inertiajs/vue3';

defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const user = usePage().props.auth.user;

const form = useForm({
    name: user.name,
    email: user.email,
});
</script>

<template>
    <section>
        <Card>
            <CardHeader>
                <CardTitle>Profile Information</CardTitle>
                <CardDescription>
                    Update your account's profile information and email address.
                </CardDescription>
            </CardHeader>
            <CardContent>
                <form
                    @submit.prevent="form.patch(route('profile.update'))"
                    class="space-y-6"
                >
                    <div class="space-y-2">
                        <Label for="name">Name</Label>
                        <Input
                            id="name"
                            type="text"
                            v-model="form.name"
                            required
                            autofocus
                            autocomplete="name"
                            :class="{ 'border-red-500': form.errors.name }"
                        />
                        <p v-if="form.errors.name" class="text-sm text-red-600 dark:text-red-400">
                            {{ form.errors.name }}
                        </p>
                    </div>

                    <div class="space-y-2">
                        <Label for="email">Email</Label>
                        <Input
                            id="email"
                            type="email"
                            v-model="form.email"
                            required
                            autocomplete="username"
                            :class="{ 'border-red-500': form.errors.email }"
                        />
                        <p v-if="form.errors.email" class="text-sm text-red-600 dark:text-red-400">
                            {{ form.errors.email }}
                        </p>
                    </div>

                    <div v-if="mustVerifyEmail && user.email_verified_at === null">
                        <p class="text-sm text-gray-800 dark:text-gray-200">
                            Your email address is unverified.
                            <Link
                                :href="route('verification.send')"
                                method="post"
                                as="button"
                                class="rounded-md text-sm text-gray-600 underline hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:text-gray-400 dark:hover:text-gray-100 dark:focus:ring-offset-gray-800"
                            >
                                Click here to re-send the verification email.
                            </Link>
                        </p>

                        <div
                            v-show="status === 'verification-link-sent'"
                            class="mt-2 text-sm font-medium text-green-600 dark:text-green-400"
                        >
                            A new verification link has been sent to your email address.
                        </div>
                    </div>

                    <div class="flex items-center gap-4">
                        <Button type="submit" :disabled="form.processing">
                            Save
                        </Button>

                        <Transition
                            enter-active-class="transition ease-in-out"
                            enter-from-class="opacity-0"
                            leave-active-class="transition ease-in-out"
                            leave-from-class="opacity-0"
                            leave-to-class="opacity-0"
                        >
                            <p v-if="form.recentlySuccessful" class="text-sm text-gray-600 dark:text-gray-400">Saved.</p>
                        </Transition>
                    </div>
                </form>
            </CardContent>
        </Card>
    </section>
</template>
