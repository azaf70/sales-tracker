<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Separator } from '@/components/ui/separator';
import { type NavItem } from '@/types';
import { Link, usePage } from '@inertiajs/vue3';
import { User, Lock, Palette, Settings } from 'lucide-vue-next';

const sidebarNavItems: NavItem[] = [
    {
        title: 'Profile',
        href: '/settings/profile',
        icon: User,
    },
    {
        title: 'Password',
        href: '/settings/password',
        icon: Lock,
    },
    {
        title: 'Appearance',
        href: '/settings/appearance',
        icon: Palette,
    },
];

const page = usePage();

const currentPath = page.props.ziggy?.location ? new URL(page.props.ziggy.location).pathname : '';
</script>

<template>
    <div class="container mx-auto max-w-7xl px-4 py-6">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center gap-3 mb-2">
                <Settings class="h-6 w-6 text-primary" />
                <h1 class="text-3xl font-bold tracking-tight">Settings</h1>
            </div>
            <p class="text-muted-foreground">
                Manage your account settings and preferences
            </p>
        </div>

        <div class="grid gap-6 lg:grid-cols-[280px_1fr]">
            <!-- Sidebar Navigation -->
            <Card class="h-fit">
                <CardHeader>
                    <CardTitle class="text-lg">Navigation</CardTitle>
                    <CardDescription>
                        Choose a section to configure
                    </CardDescription>
                </CardHeader>
                <CardContent class="p-0">
                    <nav class="flex flex-col">
                        <Button
                            v-for="item in sidebarNavItems"
                            :key="item.href"
                            variant="ghost"
                            :class="[
                                'justify-start rounded-none border-b border-border/50 last:border-b-0',
                                'px-4 text-left',
                                currentPath === item.href 
                                    ? 'bg-primary/5 text-primary border-r-2 border-r-primary' 
                                    : 'hover:bg-muted/50'
                            ]"
                            as-child
                        >
                            <Link :href="item.href" class="flex items-center gap-3">
                                <component 
                                    :is="item.icon" 
                                    class="h-4 w-4"
                                    :class="currentPath === item.href ? 'text-primary' : 'text-muted-foreground'"
                                />
                                <span class="font-medium">{{ item.title }}</span>
                            </Link>
                        </Button>
                    </nav>
                </CardContent>
            </Card>

            <!-- Main Content Area -->
            <div class="space-y-6">
                <slot />
            </div>
        </div>
    </div>
</template>
