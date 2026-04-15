<script setup>
import { useForm } from '@inertiajs/vue3';
import { ref, watch } from 'vue';
import { Button } from '@/Components/ui/button';
import { Input } from '@/Components/ui/input';
import { Label } from '@/Components/ui/label';
import { Badge } from '@/Components/ui/badge';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/Components/ui/select';
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogHeader,
    DialogTitle,
    DialogFooter,
} from '@/Components/ui/dialog';
import { Save, X, Megaphone, Bell, Calendar, Users, Edit2, Clock } from 'lucide-vue-next';

const props = defineProps({
    open: {
        type: Boolean,
        default: false
    },
    notice: {
        type: Object,
        default: null
    },
    isEditing: {
        type: Boolean,
        default: false
    },
    isViewing: {
        type: Boolean,
        default: false
    }
});

const emit = defineEmits(['update:open', 'success', 'close', 'edit']);

const form = useForm({
    title: '',
    content: '',
    type: 'general',
    priority: 'medium',
    target_audience: 'all',
    published_at: '',
    expires_at: '',
});

// Reset form when modal opens or notice changes
watch(() => props.open, (isOpen) => {
    if (isOpen) {
        if ((props.isEditing || props.isViewing) && props.notice) {
            // Populate form for editing or viewing
            form.title = props.notice.title;
            form.content = props.notice.content;
            form.type = props.notice.type;
            form.priority = props.notice.priority;
            form.target_audience = props.notice.target_audience;
            form.published_at = props.notice.published_at;
            form.expires_at = props.notice.expires_at;
        } else {
            // Reset form for creating
            form.reset();
            form.published_at = new Date().toISOString().split('T')[0];
        }
    }
});

const editNotice = () => {
    emit('edit');
};

const closeModal = () => {
    emit('update:open', false);
    emit('close');
    form.reset();
    form.clearErrors();
};

const submit = () => {
    if (props.isEditing) {
        form.put(route('notices.update', props.notice.id), {
            onSuccess: () => {
                closeModal();
                emit('success');
            },
        });
    } else {
        form.post(route('notices.store'), {
            onSuccess: () => {
                closeModal();
                emit('success');
            },
        });
    }
};

const noticeTypes = [
    { value: 'general', label: 'General', icon: Megaphone },
    { value: 'holiday', label: 'Holiday', icon: Calendar },
    { value: 'exam', label: 'Exam', icon: Bell },
    { value: 'meeting', label: 'Meeting', icon: Users },
    { value: 'policy', label: 'Policy', icon: Megaphone },
    { value: 'event', label: 'Event', icon: Calendar },
];

const priorities = [
    { value: 'low', label: 'Low', color: 'bg-blue-500' },
    { value: 'medium', label: 'Medium', color: 'bg-yellow-500' },
    { value: 'high', label: 'High', color: 'bg-red-500' },
];

const audiences = [
    { value: 'all', label: 'All' },
    { value: 'students', label: 'Students' },
    { value: 'parents', label: 'Parents' },
    { value: 'teachers', label: 'Teachers' },
    { value: 'staff', label: 'Staff' },
];
</script>

<template>
    <Dialog :open="open" @update:open="$emit('update:open', $event)">
        <DialogContent class="sm:max-w-[800px] card-warm">
            <DialogHeader>
                <DialogTitle class="flex items-center gap-2 text-warm-text text-dark-text">
                    <div class="w-8 h-8 bg-terracotta rounded-lg flex items-center justify-center">
                        <Megaphone class="w-4 h-4 text-white" />
                    </div>
                    {{ isViewing ? 'Notice Details' : (isEditing ? 'Edit Notice' : 'Create Notice') }}
                </DialogTitle>
                <DialogDescription class="text-warm-muted text-dark-muted">
                    {{ isViewing ? 'View notice details.' : (isEditing ? 'Update the notice details below.' : 'Fill in the details to create a new notice.') }}
                </DialogDescription>
            </DialogHeader>

            <!-- VIEW MODE -->
            <div v-if="isViewing" class="space-y-6 mt-4">
                <!-- Title Display -->
                <div class="card-warm p-6 rounded-lg">
                    <div class="flex items-start justify-between gap-4">
                        <div class="flex-1">
                            <div class="flex items-center gap-3 mb-3">
                                <Badge :class="form.priority === 'high' ? 'bg-red-500 text-white' : form.priority === 'medium' ? 'bg-yellow-500 text-black' : 'bg-terracotta text-white'">
                                    {{ form.priority }}
                                </Badge>
                                <Badge variant="outline" class="capitalize border-terracotta/20 text-warm-text text-dark-text">
                                    {{ form.type }}
                                </Badge>
                            </div>
                            <h2 class="text-2xl font-black text-warm-text text-dark-text tracking-tighter">
                                {{ form.title }}
                            </h2>
                        </div>
                    </div>
                </div>

                <!-- Content Display -->
                <div class="card-warm p-6 rounded-lg">
                    <p class="text-lg text-warm-text text-dark-text leading-relaxed whitespace-pre-wrap">
                        {{ form.content }}
                    </p>
                </div>

                <!-- Info Grid -->
                <div class="grid grid-cols-3 gap-4">
                    <div class="card-warm p-4 rounded-lg">
                        <div class="flex items-center gap-2 text-warm-muted text-dark-muted mb-1">
                            <Users class="w-4 h-4 text-terracotta" />
                            <span class="text-sm">Target Audience</span>
                        </div>
                        <p class="font-semibold text-warm-text text-dark-text capitalize">{{ form.target_audience }}</p>
                    </div>
                    <div class="card-warm p-4 rounded-lg">
                        <div class="flex items-center gap-2 text-warm-muted text-dark-muted mb-1">
                            <Calendar class="w-4 h-4 text-terracotta" />
                            <span class="text-sm">Publish Date</span>
                        </div>
                        <p class="font-semibold text-warm-text text-dark-text">{{ form.published_at || 'Not set' }}</p>
                    </div>
                    <div class="card-warm p-4 rounded-lg">
                        <div class="flex items-center gap-2 text-warm-muted text-dark-muted mb-1">
                            <Clock class="w-4 h-4 text-terracotta" />
                            <span class="text-sm">Expiry Date</span>
                        </div>
                        <p class="font-semibold text-warm-text text-dark-text">{{ form.expires_at || 'Not set' }}</p>
                    </div>
                </div>

                <DialogFooter class="gap-2 mt-6">
                    <Button
                        type="button"
                        variant="outline"
                        @click="closeModal"
                        class="border-terracotta/20"
                    >
                        <X class="w-4 h-4 mr-2" />
                        Close
                    </Button>
                    <Button
                        type="button"
                        @click="editNotice"
                        class="accent-terracotta text-white"
                    >
                        <Edit2 class="w-4 h-4 mr-2" />
                        Edit Notice
                    </Button>
                </DialogFooter>
            </div>

            <!-- CREATE/EDIT FORM -->
            <form v-else @submit.prevent="submit" class="space-y-4 mt-4">
                <!-- Title -->
                <div class="space-y-1">
                    <Label for="title" class="text-sm text-warm-text text-dark-text">Title *</Label>
                    <Input
                        id="title"
                        v-model="form.title"
                        placeholder="Enter notice title"
                        required
                        class="border-terracotta/20 focus:ring-terracotta/30 bg-background"
                    />
                    <div v-if="form.errors.title" class="text-red-500 text-xs">{{ form.errors.title }}</div>
                </div>

                <!-- Content -->
                <div class="space-y-1">
                    <Label for="content" class="text-sm text-warm-text text-dark-text">Content *</Label>
                    <textarea
                        id="content"
                        v-model="form.content"
                        placeholder="Enter notice content"
                        required
                        rows="6"
                        class="flex min-h-[120px] w-full rounded-md border border-terracotta/20 bg-background px-3 py-2 text-sm text-warm-text text-dark-text placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-terracotta/30 focus:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 resize-none"
                    ></textarea>
                    <div v-if="form.errors.content" class="text-red-500 text-xs">{{ form.errors.content }}</div>
                </div>

                <!-- Type and Priority Row -->
                <div class="grid grid-cols-2 gap-4">
                    <!-- Type -->
                    <div class="space-y-1">
                        <Label class="text-sm text-warm-text text-dark-text">Type</Label>
                        <Select v-model="form.type">
                            <SelectTrigger class="border-terracotta/20 bg-background text-warm-text text-dark-text">
                                <SelectValue placeholder="Select type" />
                            </SelectTrigger>
                            <SelectContent class="bg-background border-terracotta/20">
                                <SelectItem
                                    v-for="type in noticeTypes"
                                    :key="type.value"
                                    :value="type.value"
                                    class="text-warm-text text-dark-text"
                                >
                                    <div class="flex items-center gap-2">
                                        <component :is="type.icon" class="w-4 h-4 text-terracotta" />
                                        {{ type.label }}
                                    </div>
                                </SelectItem>
                            </SelectContent>
                        </Select>
                    </div>

                    <!-- Priority -->
                    <div class="space-y-1">
                        <Label class="text-sm text-warm-text text-dark-text">Priority</Label>
                        <Select v-model="form.priority">
                            <SelectTrigger class="border-terracotta/20 bg-background text-warm-text text-dark-text">
                                <SelectValue placeholder="Select priority" />
                            </SelectTrigger>
                            <SelectContent class="bg-background border-terracotta/20">
                                <SelectItem
                                    v-for="priority in priorities"
                                    :key="priority.value"
                                    :value="priority.value"
                                    class="text-warm-text text-dark-text"
                                >
                                    <div class="flex items-center gap-2">
                                        <span :class="['w-2 h-2 rounded-full', priority.color]"></span>
                                        {{ priority.label }}
                                    </div>
                                </SelectItem>
                            </SelectContent>
                        </Select>
                    </div>
                </div>

                <!-- Target Audience -->
                <div class="space-y-1">
                    <Label class="text-sm text-warm-text text-dark-text">Target Audience</Label>
                    <Select v-model="form.target_audience">
                        <SelectTrigger class="border-terracotta/20 bg-background text-warm-text text-dark-text">
                            <SelectValue placeholder="Select audience" />
                        </SelectTrigger>
                        <SelectContent class="bg-background border-terracotta/20">
                            <SelectItem
                                v-for="audience in audiences"
                                :key="audience.value"
                                :value="audience.value"
                                class="text-warm-text text-dark-text"
                            >
                                {{ audience.label }}
                            </SelectItem>
                        </SelectContent>
                    </Select>
                </div>

                <!-- Dates Row -->
                <div class="grid grid-cols-2 gap-4">
                    <div class="space-y-1">
                        <Label for="published_at" class="text-sm text-warm-text text-dark-text">Publish Date</Label>
                        <Input
                            id="published_at"
                            v-model="form.published_at"
                            type="date"
                            class="border-terracotta/20 focus:ring-terracotta/30 bg-background"
                        />
                    </div>
                    <div class="space-y-1">
                        <Label for="expires_at" class="text-sm text-warm-text text-dark-text">Expiry Date</Label>
                        <Input
                            id="expires_at"
                            v-model="form.expires_at"
                            type="date"
                            class="border-terracotta/20 focus:ring-terracotta/30 bg-background"
                        />
                    </div>
                </div>

                <DialogFooter class="gap-2 mt-6">
                    <Button
                        type="button"
                        variant="outline"
                        @click="closeModal"
                        class="border-terracotta/20"
                    >
                        <X class="w-4 h-4 mr-2" />
                        Cancel
                    </Button>
                    <Button
                        type="submit"
                        :disabled="form.processing"
                        class="accent-terracotta text-white"
                    >
                        <Save v-if="!form.processing" class="w-4 h-4 mr-2" />
                        <div v-else class="w-4 h-4 mr-2 border-2 border-white/30 border-t-white rounded-full animate-spin" />
                        {{ isEditing ? 'Update Notice' : 'Create Notice' }}
                    </Button>
                </DialogFooter>
            </form>
        </DialogContent>
    </Dialog>
</template>
