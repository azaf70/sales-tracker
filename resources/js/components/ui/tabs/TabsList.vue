<script setup lang="ts">
import { inject, computed } from 'vue'
import { cn } from '@/lib/utils'

interface TabsListProps {
  class?: string
}

const props = withDefaults(defineProps<TabsListProps>(), {})

const tabsContext = inject('tabs')

const orientation = computed(() => tabsContext?.orientation.value || 'horizontal')

const classes = computed(() =>
  cn(
    'inline-flex h-10 items-center justify-center rounded-md bg-muted p-1 text-muted-foreground',
    orientation.value === 'horizontal' ? 'w-full' : 'h-auto w-10 flex-col',
    props.class
  )
)
</script>

<template>
  <div :class="classes" role="tablist">
    <slot />
  </div>
</template> 