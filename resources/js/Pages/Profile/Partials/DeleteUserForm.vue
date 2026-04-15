<script setup>
import { Button } from '@/Components/ui/button';
import { Input } from '@/Components/ui/input';
import { Label } from '@/Components/ui/label';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/Components/ui/card';
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
} from '@/Components/ui/dialog';
import { useForm } from '@inertiajs/vue3';
import { nextTick, ref } from 'vue';

const confirmingUserDeletion = ref(false);
const passwordInput = ref(null);

const form = useForm({
    password: '',
});

const confirmUserDeletion = () => {
    confirmingUserDeletion.value = true;

    nextTick(() => passwordInput.value.focus());
};

const deleteUser = () => {
    form.delete(route('profile.destroy'), {
        preserveScroll: true,
        onSuccess: () => closeModal(),
        onError: () => passwordInput.value.focus(),
        onFinish: () => form.reset(),
    });
};

const closeModal = () => {
    confirmingUserDeletion.value = false;

    form.clearErrors();
    form.reset();
};
</script>

<template>
    <section class="space-y-6">
        <div class="card-warm border-destructive/20">
            <div class="p-6 border-b border-destructive/20">
                <h3 class="text-lg font-black text-destructive">Delete Account</h3>
                <p class="text-sm text-destructive/70 mt-1">
                    Once your account is deleted, all of its resources and data will be permanently deleted. 
                    Before deleting your account, please download any data or information that you wish to retain.
                </p>
            </div>
            <div class="p-6">
                <Button variant="destructive" @click="confirmUserDeletion" class="bg-destructive hover:bg-destructive/90 text-white shadow-md shadow-destructive/20">
                    Delete Account
                </Button>
            </div>
        </div>

        <Dialog :open="confirmingUserDeletion" @update:open="closeModal">
            <DialogContent class="card-warm rounded-[2.5rem] shadow-2xl">
                <DialogHeader>
                    <DialogTitle class="text-2xl font-black tracking-tighter text-destructive">Are you sure you want to delete your account?</DialogTitle>
                    <DialogDescription class="text-warm-muted text-dark-muted font-medium">
                        Once your account is deleted, all of its resources and data will be permanently deleted. 
                        Please enter your password to confirm you would like to permanently delete your account.
                    </DialogDescription>
                </DialogHeader>
                
                <div class="space-y-4">
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
                        <Label for="password" class="sr-only">Password</Label>
                        <Input
                            id="password"
                            ref="passwordInput"
                            v-model="form.password"
                            type="password"
                            placeholder="Password"
                            @keyup.enter="deleteUser"
                            class="border-terracotta/20 focus:ring-terracotta/30"
                            :class="{ 'border-destructive focus:ring-destructive/30': form.errors.password }"
                        />
                        <p v-if="form.errors.password" class="text-sm text-destructive">
                            {{ form.errors.password }}
                        </p>
                    </div>
                </div>

                <DialogFooter>
                    <Button variant="outline" @click="closeModal" class="border-terracotta/20">
                        Cancel
                    </Button>
                    <Button
                        variant="destructive"
                        :disabled="form.processing"
                        @click="deleteUser"
                        class="bg-destructive hover:bg-destructive/90 text-white shadow-md shadow-destructive/20"
                    >
                        Delete Account
                    </Button>
                </DialogFooter>
            </DialogContent>
        </Dialog>
    </section>
</template>