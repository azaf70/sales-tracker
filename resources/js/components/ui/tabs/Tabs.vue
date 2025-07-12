<script setup lang="ts">
import { provide, toRef } from 'vue'
import { cn } from '@/lib/utils'

interface TabsProps {
  defaultValue?: string
  value?: string
  onValueChange?: (value: string) => void
  orientation?: 'horizontal' | 'vertical'
  dir?: 'ltr' | 'rtl'
  class?: string
}

const props = withDefaults(defineProps<TabsProps>(), {
  orientation: 'horizontal',
  dir: 'ltr',
})

const emit = defineEmits<{
  'update:value': [value: string]
}>()

const value = toRef(props, 'value')
const defaultValue = toRef(props, 'defaultValue')

provide('tabs', {
  value,
  defaultValue,
  onValueChange: (newValue: string) => {
    emit('update:value', newValue)
    props.onValueChange?.(newValue)
  },
  orientation: toRef(props, 'orientation'),
  dir: toRef(props, 'dir'),
})
</script>

<template>
  <div :class="cn('', props.class)">
    <slot />
  </div>
</template> 