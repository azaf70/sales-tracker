<script setup lang="ts">
import AppLogoIcon from '@/components/AppLogoIcon.vue';
</script>

<template>
    <div class="flex aspect-square size-8 items-center justify-center rounded-md bg-sidebar-primary text-sidebar-primary-foreground dark:bg-sidebar-primary-foreground dark:text-sidebar-primary">
        <AppLogoIcon class="size-5 fill-current" />
    </div>
    <div class="ml-1 grid flex-1 text-left text-sm dark:text-white text-black">
        <span class="mb-0.5 truncate leading-tight font-semibold">Sales Tracker</span>
    </div>
</template>
