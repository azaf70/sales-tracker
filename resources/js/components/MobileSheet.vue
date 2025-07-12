<template>
  <div
    v-if="isOpen"
    class="fixed inset-0 z-50 overflow-hidden"
    @click="closeOnOverlay"
  >
    <!-- Backdrop -->
    <div
      class="absolute inset-0 bg-black bg-opacity-50 transition-opacity"
      :class="{ 'opacity-0': !isOpen, 'opacity-100': isOpen }"
    ></div>

    <!-- Sheet -->
    <div
      class="absolute bottom-0 left-0 right-0 bg-white rounded-t-xl shadow-xl transform transition-transform duration-300 ease-out"
      :class="{ 'translate-y-full': !isOpen, 'translate-y-0': isOpen }"
      @click.stop
    >
      <!-- Handle -->
      <div class="flex justify-center pt-3 pb-2">
        <div class="w-12 h-1 bg-gray-300 rounded-full"></div>
      </div>

      <!-- Header -->
      <div v-if="title" class="px-6 py-4 border-b border-gray-200">
        <div class="flex items-center justify-between">
          <h3 class="text-lg font-semibold text-gray-900">{{ title }}</h3>
          <button
            @click="$emit('close')"
            class="p-2 text-gray-400 hover:text-gray-600"
          >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
          </button>
        </div>
      </div>

      <!-- Content -->
      <div class="px-6 py-4 max-h-[70vh] overflow-y-auto">
        <slot></slot>
      </div>

      <!-- Footer -->
      <div v-if="$slots.footer" class="px-6 py-4 border-t border-gray-200 bg-gray-50">
        <slot name="footer"></slot>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { onMounted, onUnmounted } from 'vue'

interface Props {
  isOpen: boolean
  title?: string
  closeOnOverlayClick?: boolean
}

const props = withDefaults(defineProps<Props>(), {
  closeOnOverlayClick: true
})

const emit = defineEmits<{
  close: []
}>()

const closeOnOverlay = () => {
  if (props.closeOnOverlayClick) {
    emit('close')
  }
}

// Handle escape key
const handleEscape = (event: KeyboardEvent) => {
  if (event.key === 'Escape' && props.isOpen) {
    emit('close')
  }
}

// Handle swipe down gesture
let startY = 0
let currentY = 0
let isDragging = false

const handleTouchStart = (event: TouchEvent) => {
  startY = event.touches[0].clientY
  isDragging = true
}

const handleTouchMove = (event: TouchEvent) => {
  if (!isDragging) return
  
  currentY = event.touches[0].clientY
  const deltaY = currentY - startY
  
  // Only allow downward swipe
  if (deltaY > 0) {
    event.preventDefault()
  }
}

const handleTouchEnd = (event: TouchEvent) => {
  if (!isDragging) return
  
  const deltaY = currentY - startY
  const threshold = 100 // Minimum swipe distance to close
  
  if (deltaY > threshold) {
    emit('close')
  }
  
  isDragging = false
}

onMounted(() => {
  document.addEventListener('keydown', handleEscape)
  document.addEventListener('touchstart', handleTouchStart, { passive: false })
  document.addEventListener('touchmove', handleTouchMove, { passive: false })
  document.addEventListener('touchend', handleTouchEnd)
})

onUnmounted(() => {
  document.removeEventListener('keydown', handleEscape)
  document.removeEventListener('touchstart', handleTouchStart)
  document.removeEventListener('touchmove', handleTouchMove)
  document.removeEventListener('touchend', handleTouchEnd)
})
</script>

<style scoped>
/* Prevent body scroll when sheet is open */
:deep(body) {
  overflow: hidden;
}
</style> 