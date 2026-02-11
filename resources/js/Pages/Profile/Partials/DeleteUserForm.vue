<script setup>
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
} from '@/components/ui/dialog';
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
        <Card>
            <CardHeader>
                <CardTitle class="text-red-600">Delete Account</CardTitle>
                <CardDescription>
                    Once your account is deleted, all of its resources and data will be permanently deleted. 
                    Before deleting your account, please download any data or information that you wish to retain.
                </CardDescription>
            </CardHeader>
            <CardContent>
                <Button variant="destructive" @click="confirmUserDeletion">
                    Delete Account
                </Button>
            </CardContent>
        </Card>

        <Dialog :open="confirmingUserDeletion" @update:open="closeModal">
            <DialogContent>
                <DialogHeader>
                    <DialogTitle>Are you sure you want to delete your account?</DialogTitle>
                    <DialogDescription>
                        Once your account is deleted, all of its resources and data will be permanently deleted. 
                        Please enter your password to confirm you would like to permanently delete your account.
                    </DialogDescription>
                </DialogHeader>
                
                <div class="space-y-4">
                    <div class="space-y-2">
                        <Label for="password" class="sr-only">Password</Label>
                        <Input
                            id="password"
                            ref="passwordInput"
                            v-model="form.password"
                            type="password"
                            placeholder="Password"
                            @keyup.enter="deleteUser"
                            :class="{ 'border-red-500': form.errors.password }"
                        />
                        <p v-if="form.errors.password" class="text-sm text-red-600 dark:text-red-400">
                            {{ form.errors.password }}
                        </p>
                    </div>
                </div>

                <DialogFooter>
                    <Button variant="outline" @click="closeModal">
                        Cancel
                    </Button>
                    <Button
                        variant="destructive"
                        :disabled="form.processing"
                        @click="deleteUser"
                    >
                        Delete Account
                    </Button>
                </DialogFooter>
            </DialogContent>
        </Dialog>
    </section>
</template>
