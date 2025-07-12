<script setup lang="ts">
import { inject, computed } from 'vue'
import { cn } from '@/lib/utils'

interface TabsTriggerProps {
  value: string
  disabled?: boolean
  class?: string
}

const props = withDefaults(defineProps<TabsTriggerProps>(), {
  disabled: false,
})

const tabsContext = inject('tabs')

const isSelected = computed(() => {
  const currentValue = tabsContext?.value?.value || tabsContext?.defaultValue?.value
  return currentValue === props.value
})

const orientation = computed(() => tabsContext?.orientation.value || 'horizontal')

const handleClick = () => {
  if (!props.disabled && tabsContext?.onValueChange) {
    tabsContext.onValueChange(props.value)
  }
}

const classes = computed(() =>
  cn(
    'inline-flex items-center justify-center whitespace-nowrap rounded-sm px-3 py-1.5 text-sm font-medium ring-offset-background transition-all focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50',
    isSelected.value
      ? 'bg-background text-foreground shadow-sm'
      : 'hover:bg-background hover:text-foreground',
    orientation.value === 'horizontal' ? 'flex-1' : 'w-full',
    props.class
  )
)
</script>

<template>
  <button
    :class="classes"
    :disabled="disabled"
    :data-state="isSelected ? 'active' : 'inactive'"
    :data-orientation="orientation"
    role="tab"
    type="button"
    @click="handleClick"
  >
    <slot />
  </button>
</template> 