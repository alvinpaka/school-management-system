<script setup>
import { cn } from "@/lib/utils";

const props = defineProps({
  class: { type: null, required: false },
});
</script>

<template>
  <div
    :class="cn('h-full w-full flex-1 bg-primary transition-all', props.class)"
  />
</template>
