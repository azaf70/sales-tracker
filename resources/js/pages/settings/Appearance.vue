<script setup lang="ts">
import { ref, computed } from 'vue'
import { router, usePage } from '@inertiajs/vue3'
import { route } from 'ziggy-js'
import { Home, Building2, TrendingUp, DollarSign, User } from 'lucide-vue-next'
import AppLogo from '@/components/AppLogo.vue'
import { Avatar, AvatarImage, AvatarFallback } from '@/components/ui/avatar'
import Breadcrumbs from '@/components/Breadcrumbs.vue'
import { useInitials } from '@/composables/useInitials'
import type { BreadcrumbItemType } from '@/types'

const props = defineProps<{ breadcrumbs?: BreadcrumbItemType[] }>()

const page = usePage()
const auth = computed(() => page.props.auth)
const { getInitials } = useInitials()

// Define navigation items with Ziggy route names
const navigationItems = [
  { id: 1, name: 'Businesses', routeName: 'businesses.index', icon: Building2 },
  { id: 2, name: 'Sales', routeName: 'sales.index', icon: TrendingUp },
  { id: 3, name: 'Dashboard', routeName: 'dashboard', icon: Home },
  { id: 4, name: 'Investments', routeName: 'investments.index', icon: DollarSign },
  { id: 5, name: 'Profile', routeName: 'settings.profile', icon: User },
]

// Map navigation items to options for vue-bottom-navigation
const options = navigationItems.map((item) => ({
  id: item.id,
  icon: item.icon,
  title: item.name,
}))

// Track the selected ID and animation state
const selectedId = ref(3) // Default to Dashboard
const isNavigating = ref(false)

// Compute selected ID based on current route
const selected = computed({
  get: () => {
    if (isNavigating.value) return selectedId.value
    const currentRoute = page.props.ziggy?.name || route().current()
    console.log('Current route:', currentRoute) // Debug log
    const matchedItem = navigationItems.find((item) => currentRoute === item.routeName || currentRoute?.includes(item.routeName))
    return matchedItem ? matchedItem.id : 3 // Default to Dashboard
  },
  set: (newId) => {
    if (newId === selectedId.value && route().current() === navigationItems.find((item) => item.id === newId)?.routeName) {
      console.log('Navigation blocked: Already on route', route().current()) // Debug log
      return // Prevent redundant navigation
    }
    selectedId.value = newId
    isNavigating.value = true

    const selectedItem = navigationItems.find((item) => item.id === newId)
    console.log('Navigating to:', selectedItem?.routeName, route(selectedItem?.routeName)) // Debug log

    // Delay navigation to allow animation to complete
    setTimeout(() => {
      if (selectedItem) {
        router.visit(route(selectedItem.routeName), {
          preserveState: true,
          preserveScroll: true,
          onFinish: () => {
            isNavigating.value = false
            console.log('Navigation complete, current route:', route().current()) // Debug log
          },
          onError: (errors) => {
            console.error('Navigation error:', errors) // Debug log
            isNavigating.value = false
          },
        })
      } else {
        isNavigating.value = false
      }
    }, 300) // Match animation duration
  },
})
</script>

<template>
  <div class="min-h-screen bg-background flex flex-col">
    <!-- Header -->
    <header class="sticky top-0 z-40 flex flex-col bg-background border-b border-border">
      <div class="flex items-center justify-between h-14 px-4">
        <AppLogo />
        <Avatar class="h-8 w-8">
          <AvatarImage v-if="auth.user?.avatar" :src="auth.user.avatar" :alt="auth.user.name" />
          <AvatarFallback>{{ getInitials(auth.user?.name) }}</AvatarFallback>
        </Avatar>
      </div>
      <div v-if="props.breadcrumbs && props.breadcrumbs.length > 1" class="px-4 pb-2">
        <Breadcrumbs :breadcrumbs="props.breadcrumbs" />
      </div>
    </header>
    <main class="flex-1 pb-24">
      <slot />
    </main>
    <CurvedBottomNavigation
      :options="options"
      v-model="selected"
      :foregroundColor="'var(--primary)'"
      :backgroundColor="'var(--background)'"
      :badgeColor="'var(--warning, #facc15)'"
      :iconColor="'var(--muted-foreground)'"
      style="position: fixed; left: 0; right: 0; bottom: 0; z-index: 50; will-change: transform; backface-visibility: hidden;"
    >
      <template #icon="{ props }">
        <component :is="props.icon" class="h-6 w-6" />
      </template>
      <template #title="{ props }">
        <span class="text-xs font-medium hidden sm:inline">{{ props.title }}</span>
      </template>
    </CurvedBottomNavigation>
  </div>
</template>