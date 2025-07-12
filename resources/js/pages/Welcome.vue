<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3'
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card'
import { Button } from '@/components/ui/button'
import { Badge } from '@/components/ui/badge'
import { Building2, TrendingUp, Users, PoundSterling, ArrowRight, CheckCircle } from 'lucide-vue-next'

const features = [
  {
    icon: Building2,
    title: 'Family Business Management',
    description: 'Create and manage multiple family businesses with ease'
  },
  {
    icon: Users,
    title: 'Member Collaboration',
    description: 'Invite family members and track ownership percentages'
  },
  {
    icon: TrendingUp,
    title: 'Investment Tracking',
    description: 'Monitor investments, profits, and financial performance'
  },
  {
    icon: PoundSterling,
    title: 'UK-Focused',
    description: 'Built for British families with GBP currency support'
  }
]
</script>

<template>
  <Head title="Welcome to Sales Tracker" />
  
  <div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-purple-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900">
    <!-- Header -->
    <header class="p-4">
      <nav class="flex items-center justify-between max-w-4xl mx-auto">
        <div class="flex items-center gap-2">
          <div class="w-8 h-8 bg-gradient-to-br from-blue-600 to-purple-600 rounded-lg flex items-center justify-center">
            <Building2 class="w-5 h-5 text-white" />
          </div>
          <span class="font-bold text-xl text-gray-900 dark:text-white">Sales Tracker</span>
        </div>
        
        <div class="flex items-center gap-2">
          <Link v-if="$page.props.auth.user" :href="route('dashboard')" as="button">
            <Button variant="outline" size="sm">
              Dashboard
            </Button>
          </Link>
          <template v-else>
            <Link :href="route('login')" as="button">
              <Button variant="ghost" size="sm">
                Log in
              </Button>
            </Link>
            <Link :href="route('register')" as="button">
              <Button size="sm">
                Get Started
              </Button>
            </Link>
          </template>
        </div>
      </nav>
    </header>

    <!-- Hero Section -->
    <main class="px-4 py-8 max-w-4xl mx-auto">
      <div class="text-center space-y-6 mb-12">
        <Badge variant="secondary" class="mb-4">
          Family Business Investment Tracker
        </Badge>
        
        <h1 class="text-4xl sm:text-5xl lg:text-6xl font-bold text-gray-900 dark:text-white leading-tight">
          Track Your Family's
          <span class="bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">
            Business Success
          </span>
        </h1>
        
        <p class="text-xl text-gray-600 dark:text-gray-300 max-w-2xl mx-auto leading-relaxed">
          Manage family business investments, track profits, and distribute earnings with transparency. 
          Built for UK families who want to grow together.
        </p>
        
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
          <Link :href="route('register')" as="button">
            <Button size="lg" class="text-lg px-8 py-4">
              Start Free Trial
              <ArrowRight class="ml-2 h-5 w-5" />
            </Button>
          </Link>
          <Link :href="route('login')" as="button">
            <Button variant="outline" size="lg" class="text-lg px-8 py-4">
              Sign In
            </Button>
          </Link>
        </div>
      </div>

      <!-- Features Grid -->
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-12">
        <Card v-for="feature in features" :key="feature.title" class="border-0 shadow-lg hover:shadow-xl transition-shadow">
          <CardHeader>
            <div class="w-12 h-12 bg-gradient-to-br from-blue-100 to-purple-100 dark:from-blue-900/20 dark:to-purple-900/20 rounded-lg flex items-center justify-center mb-4">
              <component :is="feature.icon" class="w-6 h-6 text-blue-600 dark:text-blue-400" />
            </div>
            <CardTitle class="text-xl">{{ feature.title }}</CardTitle>
          </CardHeader>
          <CardContent>
            <CardDescription class="text-base">{{ feature.description }}</CardDescription>
          </CardContent>
        </Card>
      </div>

      <!-- Stats Section -->
      <Card class="bg-gradient-to-r from-blue-600 to-purple-600 text-white border-0 shadow-xl">
        <CardContent class="p-8">
          <div class="grid grid-cols-1 sm:grid-cols-3 gap-8 text-center">
            <div>
              <div class="text-3xl font-bold mb-2">£2.5M+</div>
              <div class="text-blue-100">Total Investments Tracked</div>
            </div>
            <div>
              <div class="text-3xl font-bold mb-2">500+</div>
              <div class="text-blue-100">Family Businesses</div>
            </div>
            <div>
              <div class="text-3xl font-bold mb-2">98%</div>
              <div class="text-blue-100">Satisfaction Rate</div>
            </div>
          </div>
        </CardContent>
      </Card>

      <!-- CTA Section -->
      <div class="text-center mt-12 space-y-6">
        <h2 class="text-3xl font-bold text-gray-900 dark:text-white">
          Ready to Start Your Family's Success Story?
        </h2>
        <p class="text-lg text-gray-600 dark:text-gray-300">
          Join thousands of UK families who trust Sales Tracker to manage their business investments.
        </p>
        <Link :href="route('register')" as="button">
          <Button size="lg" class="text-lg px-8 py-4 bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700">
            Create Your First Business
            <ArrowRight class="ml-2 h-5 w-5" />
          </Button>
        </Link>
      </div>
    </main>

    <!-- Footer -->
    <footer class="mt-16 p-8 border-t border-gray-200 dark:border-gray-700">
      <div class="max-w-4xl mx-auto text-center text-gray-600 dark:text-gray-400">
        <p>&copy; 2024 Sales Tracker. Built for UK families.</p>
      </div>
    </footer>
  </div>
</template>
