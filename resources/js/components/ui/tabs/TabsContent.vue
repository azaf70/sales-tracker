<script setup lang="ts">
import { inject, computed } from 'vue'
import { cn } from '@/lib/utils'

interface TabsContentProps {
  value: string
  class?: string
}

const props = defineProps<TabsContentProps>()

const tabsContext = inject('tabs')

const isSelected = computed(() => {
  const currentValue = tabsContext?.value?.value || tabsContext?.defaultValue?.value
  return currentValue === props.value
})

const orientation = computed(() => tabsContext?.orientation.value || 'horizontal')

const classes = computed(() =>
  cn(
    'mt-2 ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2',
    orientation.value === 'horizontal' ? 'mt-2' : 'ml-2',
    props.class
  )
)
</script>

<template>
  <div
    v-show="isSelected"
    :class="classes"
    :data-state="isSelected ? 'active' : 'inactive'"
    :data-orientation="orientation"
    role="tabpanel"
  >
    <slot />
  </div>
</template> 