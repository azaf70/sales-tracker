<script setup lang="ts">
import AppSidebarLayout from '@/layouts/app/AppSidebarLayout.vue';
import AppHeaderLayout from '@/layouts/app/AppHeaderLayout.vue';
import AppMobileLayout from '@/layouts/app/AppMobileLayout.vue';
import type { BreadcrumbItemType } from '@/types';

interface Props {
    breadcrumbs?: BreadcrumbItemType[];
    variant?: 'header' | 'sidebar' | 'mobile';
}

withDefaults(defineProps<Props>(), {
    breadcrumbs: () => [],
    variant: 'mobile',
});
</script>

<template>
    <!-- Mobile layout by default -->
    <AppMobileLayout v-if="variant === 'mobile'">
        <slot />
    </AppMobileLayout>
    
    <!-- Header layout -->
    <AppHeaderLayout v-else-if="variant === 'header'" :breadcrumbs="breadcrumbs">
        <slot />
    </AppHeaderLayout>
    
    <!-- Sidebar layout for larger screens -->
    <AppSidebarLayout v-else :breadcrumbs="breadcrumbs">
        <slot />
    </AppSidebarLayout>
</template>
