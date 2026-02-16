<script setup>
import { ChevronLeftIcon, ChevronRightIcon, ChevronsLeftIcon, ChevronsRightIcon } from 'lucide-vue-next';
import { Button } from '@/components/ui/button';

const props = defineProps({
    data: {
        type: Object,
        required: true
    }
});

const emit = defineEmits(['page-change']);

const goToPage = (page) => {
    if (page >= 1 && page <= props.data.last_page) {
        emit('page-change', page);
    }
};

const getPaginationNumbers = () => {
    const current = props.data.current_page;
    const last = props.data.last_page;
    const delta = 2;
    const range = [];
    const rangeWithDots = [];

    for (
        let i = Math.max(2, current - delta);
        i <= Math.min(last - 1, current + delta);
        i++
    ) {
        range.push(i);
    }

    if (current - delta > 2) {
        rangeWithDots.push(1, '...');
    } else {
        rangeWithDots.push(1);
    }

    rangeWithDots.push(...range);

    if (current + delta < last - 1) {
        rangeWithDots.push('...', last);
    } else if (last > 1) {
        rangeWithDots.push(last);
    }

    return rangeWithDots;
};
</script>

<template>
    <div class="flex flex-col sm:flex-row items-center justify-between px-2 py-4 bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700">
        <div class="text-sm text-gray-700 dark:text-gray-300 mb-4 sm:mb-0">
            Showing 
            <span class="font-medium">{{ data.from || 0 }}</span>
            to
            <span class="font-medium">{{ data.to || 0 }}</span>
            of
            <span class="font-medium">{{ data.total }}</span>
            results
        </div>
        
        <div class="flex items-center space-x-1">
            <!-- First Page -->
            <Button
                variant="outline"
                size="sm"
                @click="goToPage(1)"
                :disabled="data.current_page === 1"
                class="hidden sm:flex"
            >
                <ChevronsLeftIcon class="w-4 h-4" />
            </Button>
            
            <!-- Previous Page -->
            <Button
                variant="outline"
                size="sm"
                @click="goToPage(data.current_page - 1)"
                :disabled="data.current_page === 1"
            >
                <ChevronLeftIcon class="w-4 h-4" />
            </Button>
            
            <!-- Page Numbers -->
            <div class="flex items-center space-x-1">
                <Button
                    v-for="page in getPaginationNumbers()"
                    :key="page"
                    variant="outline"
                    size="sm"
                    @click="goToPage(page)"
                    :disabled="page === '...'"
                    :class="{
                        'bg-blue-600 text-white hover:bg-blue-700': page === data.current_page,
                        'cursor-not-allowed': page === '...'
                    }"
                >
                    {{ page }}
                </Button>
            </div>
            
            <!-- Next Page -->
            <Button
                variant="outline"
                size="sm"
                @click="goToPage(data.current_page + 1)"
                :disabled="data.current_page === data.last_page"
            >
                <ChevronRightIcon class="w-4 h-4" />
            </Button>
            
            <!-- Last Page -->
            <Button
                variant="outline"
                size="sm"
                @click="goToPage(data.last_page)"
                :disabled="data.current_page === data.last_page"
                class="hidden sm:flex"
            >
                <ChevronsRightIcon class="w-4 h-4" />
            </Button>
        </div>
    </div>
</template>
