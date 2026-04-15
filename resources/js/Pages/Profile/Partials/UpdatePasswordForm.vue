<script setup>
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const passwordInput = ref(null);
const currentPasswordInput = ref(null);

const form = useForm({
    current_password: '',
    password: '',
    password_confirmation: '',
});

const updatePassword = () => {
    form.put(route('password.update'), {
        preserveScroll: true,
        onSuccess: () => form.reset(),
        onError: () => {
            if (form.errors.password) {
                form.reset('password', 'password_confirmation');
                passwordInput.value.focus();
            }
            if (form.errors.current_password) {
                form.reset('current_password');
                currentPasswordInput.value.focus();
            }
        },
    });
};
</script>

<template>
    <section>
        <div class="card-warm">
            <div class="p-6">
                <form @submit.prevent="updatePassword" class="space-y-6">
                    <!-- Hidden username field for accessibility -->
                    <input 
                        type="text" 
                        :value="$page.props.auth.user.email" 
                        autocomplete="username" 
                        style="display: none;"
                        aria-hidden="true"
                        tabindex="-1"
                    />
                    
                    <div class="space-y-2">
                        <Label for="current_password" class="text-warm-text text-dark-text">Current Password</Label>
                        <Input
                            id="current_password"
                            ref="currentPasswordInput"
                            v-model="form.current_password"
                            type="password"
                            autocomplete="current-password"
                            class="border-terracotta/20 focus:ring-terracotta/30"
                            :class="{ 'border-destructive focus:ring-destructive/30': form.errors.current_password }"
                        />
                        <p v-if="form.errors.current_password" class="text-sm text-destructive">
                            {{ form.errors.current_password }}
                        </p>
                    </div>

                    <div class="space-y-2">
                        <Label for="password" class="text-warm-text text-dark-text">New Password</Label>
                        <Input
                            id="password"
                            ref="passwordInput"
                            v-model="form.password"
                            type="password"
                            autocomplete="new-password"
                            class="border-terracotta/20 focus:ring-terracotta/30"
                            :class="{ 'border-destructive focus:ring-destructive/30': form.errors.password }"
                        />
                        <p v-if="form.errors.password" class="text-sm text-destructive">
                            {{ form.errors.password }}
                        </p>
                    </div>

                    <div class="space-y-2">
                        <Label for="password_confirmation" class="text-warm-text text-dark-text">Confirm Password</Label>
                        <Input
                            id="password_confirmation"
                            v-model="form.password_confirmation"
                            type="password"
                            autocomplete="new-password"
                            class="border-terracotta/20 focus:ring-terracotta/30"
                            :class="{ 'border-destructive focus:ring-destructive/30': form.errors.password_confirmation }"
                        />
                        <p v-if="form.errors.password_confirmation" class="text-sm text-destructive">
                            {{ form.errors.password_confirmation }}
                        </p>
                    </div>

                    <div class="flex items-center gap-4">
                        <Button type="submit" :disabled="form.processing" class="accent-terracotta text-white">
                            Save
                        </Button>

                        <Transition
                            enter-active-class="transition ease-in-out"
                            enter-from-class="opacity-0"
                            leave-active-class="transition ease-in-out"
                            leave-from-class="opacity-0"
                            leave-to-class="opacity-0"
                        >
                            <p
                                v-if="form.recentlySuccessful"
                                class="text-sm text-forest"
                            >
                                Saved.
                            </p>
                        </Transition>
                    </div>
                </form>
            </div>
        </div>
    </section>
</template>