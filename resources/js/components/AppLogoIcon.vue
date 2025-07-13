<script setup lang="ts">
import type { HTMLAttributes } from 'vue';

defineOptions({
    inheritAttrs: false,
});

interface Props {
    className?: HTMLAttributes['class'];
}

defineProps<Props>();
</script>

<template>
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" :class="className" v-bind="$attrs">
        <path
            fill="currentColor"
            fill-rule="evenodd"
            clip-rule="evenodd"
            d="M3 3C2.44772 3 2 3.44772 2 4V20C2 20.5523 2.44772 21 3 21H21C21.5523 21 22 20.5523 22 20V4C22 3.44772 21.5523 3 21 3H3ZM4 19V5H20V19H4Z"
        />
        <path
            fill="currentColor"
            d="M12 7C11.4477 7 11 7.44772 11 8V16C11 16.5523 11.4477 17 12 17C12.5523 17 13 16.5523 13 16V8C13 7.44772 12.5523 7 12 7Z"
        />
        <path
            fill="currentColor" 
            d="M7 11C6.44772 11 6 11.4477 6 12V16C6 16.5523 6.44772 17 7 17C7.55228 17 8 16.5523 8 16V12C8 11.4477 7.55228 11 7 11Z"
        />
        <path
            fill="currentColor"
            d="M16 13C16 12.4477 16.4477 12 17 12C17.5523 12 18 12.4477 18 13V16C18 16.5523 17.5523 17 17 17C16.4477 17 16 16.5523 16 16V13Z"
        />
    </svg>
</template>
