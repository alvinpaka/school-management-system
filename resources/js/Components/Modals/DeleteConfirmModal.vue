<script setup>
import { Button } from '@/Components/ui/button';
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogHeader,
    DialogTitle,
    DialogFooter,
} from '@/Components/ui/dialog';
import { Trash2, AlertTriangle, X } from 'lucide-vue-next';

const props = defineProps({
    open: {
        type: Boolean,
        default: false
    },
    title: {
        type: String,
        default: 'Confirm Delete'
    },
    description: {
        type: String,
        default: 'Are you sure you want to delete this item? This action cannot be undone.'
    },
    itemName: {
        type: String,
        default: ''
    },
    processing: {
        type: Boolean,
        default: false
    }
});

const emit = defineEmits(['update:open', 'confirm', 'cancel']);

const closeModal = () => {
    emit('update:open', false);
    emit('cancel');
};

const confirmDelete = () => {
    emit('confirm');
};
</script>

<template>
    <Dialog :open="open" @update:open="$emit('update:open', $event)">
        <DialogContent class="sm:max-w-[450px] card-warm">
            <DialogHeader class="text-center">
                <div class="mx-auto w-12 h-12 bg-red-100 dark:bg-red-900/20 rounded-full flex items-center justify-center mb-4">
                    <AlertTriangle class="w-6 h-6 text-red-600 dark:text-red-400" />
                </div>
                <DialogTitle class="text-xl font-black text-warm-text text-dark-text text-center">
                    {{ title }}
                </DialogTitle>
                <DialogDescription class="text-warm-muted text-dark-muted text-center">
                    {{ description }}
                    <span v-if="itemName" class="font-semibold text-terracotta dark:text-terracotta block mt-1">"{{ itemName }}"</span>
                </DialogDescription>
            </DialogHeader>

            <DialogFooter class="gap-3 mt-6 flex flex-col sm:flex-row">
                <Button
                    type="button"
                    variant="outline"
                    @click="closeModal"
                    class="w-full sm:w-auto border-terracotta/20"
                >
                    <X class="w-4 h-4 mr-2" />
                    Cancel
                </Button>
                <Button
                    type="button"
                    @click="confirmDelete"
                    :disabled="processing"
                    class="w-full sm:w-auto bg-red-600 hover:bg-red-700 text-white"
                >
                    <Trash2 v-if="!processing" class="w-4 h-4 mr-2" />
                    <div v-else class="w-4 h-4 mr-2 border-2 border-white/30 border-t-white rounded-full animate-spin" />
                    Delete
                </Button>
            </DialogFooter>
        </DialogContent>
    </Dialog>
</template>
