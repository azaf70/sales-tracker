<script setup lang="ts">
import { ref, watch } from 'vue'
import { router } from '@inertiajs/vue3'
import { Home, Building2, TrendingUp, DollarSign, User } from 'lucide-vue-next'
import { CurvedBottomNavigation } from 'bottom-navigation-vue'

const selected = ref(3)

const navigationItems = [
  { id: 1, name: 'Businesses', href: '/businesses', icon: Building2 },
  { id: 2, name: 'Sales', href: '/sales', icon: TrendingUp },
  { id: 3, name: 'Dashboard', href: '/dashboard', icon: Home },
  { id: 4, name: 'Investments', href: '/investments', icon: DollarSign },
  { id: 5, name: 'Profile', href: '/settings/profile', icon: User }
]

const options = navigationItems.map((item, idx) => ({
  id: item.id,
  icon: item.icon,
  title: item.name,
  route: item.href,
}))

watch(selected, (val) => {
  const option = options[val]
  if (option && option.route) {
    router.visit(option.route)
  }
})
</script>

<template>
  <div class="min-h-screen bg-background flex flex-col">
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
      style="position: fixed; left: 0; right: 0; bottom: 0; z-index: 50;"
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