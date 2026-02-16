<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import Sidebar from '@/Components/Sidebar.vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';
import { ref } from 'vue';
import { Camera, Upload, X, User } from 'lucide-vue-next';

const props = defineProps({
    user: Object
});

const photoPreview = ref(null);
const photoFile = ref(null);
const uploading = ref(false);
const errors = ref({});

const handleFileChange = (event) => {
    console.log('handleFileChange called');
    console.log('event.target.files:', event.target.files);
    
    const file = event.target.files[0];
    console.log('selected file:', file);
    
    if (file) {
        console.log('Setting photoFile.value');
        photoFile.value = file;
        const reader = new FileReader();
        reader.onload = (e) => {
            console.log('FileReader loaded, setting photoPreview');
            photoPreview.value = e.target.result;
        };
        reader.readAsDataURL(file);
    } else {
        console.log('No file selected');
    }
};

const clearPhoto = () => {
    photoPreview.value = null;
    photoFile.value = null;
    document.getElementById('photo-input').value = '';
};

const uploadPhoto = async () => {
    console.log('uploadPhoto called');
    console.log('photoFile.value:', photoFile.value);
    
    if (!photoFile.value) {
        console.log('No file selected');
        errors.value.photo = 'Please select a photo to upload.';
        return;
    }

    console.log('Starting upload...');
    uploading.value = true;
    errors.value = {};

    const formData = new FormData();
    formData.append('photo', photoFile.value);

    try {
        const response = await router.post(route('photo.update'), formData, {
            forceFormData: true,
            onSuccess: () => {
                console.log('Upload successful');
                // Clear preview and file selection after successful upload
                photoPreview.value = null;
                photoFile.value = null;
                document.getElementById('photo-input').value = '';
                router.reload();
            },
            onError: (error) => {
                console.log('Upload error:', error);
                // Handle different error response structures
                if (error && error.response && error.response.data && error.response.data.errors) {
                    errors.value = error.response.data.errors;
                } else if (error && error.errors) {
                    errors.value = error.errors;
                } else if (error && typeof error === 'object') {
                    errors.value = { photo: 'Upload failed. Please check your file and try again.' };
                } else {
                    errors.value = { photo: 'Upload failed. Please try again.' };
                }
            }
        });
    } catch (error) {
        console.error('Upload failed:', error);
        errors.value = { photo: 'Upload failed. Please try again.' };
    } finally {
        console.log('Upload finished');
        uploading.value = false;
    }
};

const removePhoto = async () => {
    if (!confirm('Are you sure you want to remove your photo?')) {
        return;
    }

    try {
        await router.delete(route('photo.destroy'), {
            onSuccess: () => {
                router.reload();
            }
        });
    } catch (error) {
        console.error('Failed to remove photo:', error);
    }
};

const getPhotoUrl = () => {
    if (photoPreview.value) {
        return photoPreview.value;
    }
    if (props.user.photo) {
        return `/storage/${props.user.photo}`;
    }
    return null;
};
</script>

<template>
    <Head title="Update Photo" />

    <Sidebar>
        <template #header-title>
            Update Photo
        </template>

        <div class="mx-auto max-w-2xl">
            <!-- Page Header -->
            <div class="mb-6">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Update Photo</h2>
                        <p class="text-gray-600 dark:text-gray-400">Upload your passport size photo</p>
                    </div>
                    <div class="flex items-center space-x-3">
                        <Link :href="route('profile.edit')">
                            <Button variant="outline">
                                Back to Profile
                            </Button>
                        </Link>
                    </div>
                </div>
            </div>

            <!-- Photo Upload Card -->
            <Card>
                <CardHeader>
                    <CardTitle>Passport Size Photo</CardTitle>
                    <CardDescription>
                        Upload a professional passport size photo (35mm x 45mm or 350px x 450px)
                    </CardDescription>
                </CardHeader>
                <CardContent>
                    <div class="space-y-6">
                        <!-- Current Photo -->
                        <div class="flex flex-col items-center">
                            <div class="relative">
                                <div class="w-48 h-56 rounded-lg overflow-hidden bg-gray-100 dark:bg-gray-800 border-2 border-dashed border-gray-300 dark:border-gray-600">
                                    <img 
                                        v-if="getPhotoUrl()" 
                                        :src="getPhotoUrl()" 
                                        :alt="user.name"
                                        class="w-full h-full object-cover"
                                    />
                                    <div v-else class="flex items-center justify-center h-full">
                                        <User class="w-16 h-16 text-gray-400" />
                                    </div>
                                </div>
                                
                                <!-- Remove Photo Button -->
                                <button
                                    v-if="props.user.photo && !photoPreview"
                                    @click="removePhoto"
                                    class="absolute top-2 right-2 bg-red-500 text-white rounded-full p-1 hover:bg-red-600 transition-colors"
                                >
                                    <X class="w-4 h-4" />
                                </button>
                            </div>
                            
                            <div class="mt-4 text-center">
                                <p class="text-sm font-medium text-gray-900 dark:text-white">{{ user.name }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                    {{ user.email }}
                                </p>
                            </div>
                        </div>

                        <!-- Upload Section -->
                        <div class="border-t pt-6">
                            <div class="space-y-4">
                                <div>
                                    <label for="photo-input" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Select Photo
                                    </label>
                                    <div class="flex items-center justify-center w-full">
                                        <label for="photo-input" class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 dark:bg-gray-800 hover:bg-gray-100 dark:hover:bg-gray-700">
                                            <Upload class="w-8 h-8 text-gray-400 mb-2" />
                                            <span class="text-sm text-gray-600 dark:text-gray-400">
                                                Click to upload or drag and drop
                                            </span>
                                            <span class="text-xs text-gray-500">
                                                PNG, JPG, JPEG up to 2MB (350x450px)
                                            </span>
                                        </label>
                                        <input
                                            id="photo-input"
                                            type="file"
                                            accept="image/jpeg,image/jpg,image/png"
                                            @change="handleFileChange"
                                            class="hidden"
                                        />
                                    </div>
                                </div>

                                <!-- Preview -->
                                <div v-if="photoPreview" class="flex items-center justify-center">
                                    <div class="relative">
                                        <img 
                                            :src="photoPreview" 
                                            alt="Photo preview"
                                            class="w-32 h-40 object-cover rounded-lg"
                                        />
                                        <button
                                            @click="clearPhoto"
                                            class="absolute top-2 right-2 bg-red-500 text-white rounded-full p-1 hover:bg-red-600 transition-colors"
                                        >
                                            <X class="w-4 h-4" />
                                        </button>
                                    </div>
                                </div>

                                <!-- Error Messages -->
                                <div v-if="errors.photo" class="text-sm text-red-600 dark:text-red-400">
                                    {{ errors.photo }}
                                </div>

                                <!-- Upload Button -->
                                <div class="flex justify-center">
                                    <Button
                                        @click="uploadPhoto"
                                        :disabled="!photoFile || uploading"
                                        class="w-full sm:w-auto"
                                    >
                                        <Upload class="w-4 h-4 mr-2" />
                                        {{ uploading ? 'Uploading...' : 'Upload Photo' }}
                                    </Button>
                                </div>
                            </div>
                        </div>

                        <!-- Requirements -->
                        <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
                            <h4 class="text-sm font-medium text-blue-900 dark:text-blue-100 mb-2">
                                Photo Requirements:
                            </h4>
                            <ul class="text-sm text-blue-800 dark:text-blue-200 space-y-1">
                                <li>• Passport size (35mm x 45mm or 350px x 450px)</li>
                                <li>• Recent photo (taken within last 6 months)</li>
                                <li>• Plain background (white or light colored)</li>
                                <li>• Face clearly visible, looking directly at camera</li>
                                <li>• No sunglasses or hats (unless religious/medical)</li>
                                <li>• File format: JPEG, JPG, or PNG</li>
                                <li>• Maximum file size: 2MB</li>
                            </ul>
                        </div>
                    </div>
                </CardContent>
            </Card>
        </div>
    </Sidebar>
</template>
