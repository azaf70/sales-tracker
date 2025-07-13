<script setup lang="ts">
import { Head, useForm, usePage } from '@inertiajs/vue3';

import DeleteUser from '@/components/DeleteUser.vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Separator } from '@/components/ui/separator';
import AppLayout from '@/layouts/AppLayout.vue';
import SettingsLayout from '@/layouts/settings/Layout.vue';
import { type BreadcrumbItem, type User } from '@/types';
import { User as UserIcon, Mail, Shield, CheckCircle } from 'lucide-vue-next';

interface Props {
    mustVerifyEmail: boolean;
    status?: string;
}

defineProps<Props>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Profile settings',
        href: '/settings/profile',
    },
];

const page = usePage();
const user = page.props.auth.user as User;

const form = useForm({
    name: user.name,
    email: user.email,
});

const submit = () => {
    form.patch(route('profile.update'), {
        preserveScroll: true,
    });
};
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <Head title="Profile settings" />

        <SettingsLayout>
            <div class="mx-auto max-w-lg w-full space-y-10 py-8">
                <!-- Profile Header -->
                <div class="flex items-center gap-4 mb-4">
                    <div class="flex h-8 w-8 items-center justify-center rounded-full bg-primary/10">
                        <UserIcon class="h-4 w-4 text-primary" />
                    </div>
                    <div>
                        <h1 class="text-xl font-bold tracking-tight">Profile</h1>
                    </div>
                </div>

                <!-- Profile Information Card -->
                <Card>
                    <CardHeader class="mb-2">
                        <CardTitle class="flex items-center gap-2">
                            <UserIcon class="h-5 w-5" />
                            Profile
                        </CardTitle>
                        <CardDescription>
                            Update your account's profile information and email address.
                        </CardDescription>
                    </CardHeader>
                    <CardContent>
                        <form @submit.prevent="submit" class="space-y-4">
                            <div class="grid gap-2">
                                <Label for="name">Full Name</Label>
                                <Input 
                                    id="name" 
                                    v-model="form.name" 
                                    required 
                                    autocomplete="name" 
                                    placeholder="Enter your full name"
                                    :class="{ 'border-destructive': form.errors.name }"
                                />
                                <InputError :message="form.errors.name" />
                            </div>

                            <div class="grid gap-2">
                                <Label for="email">Email Address</Label>
                                <Input
                                    id="email"
                                    type="email"
                                    v-model="form.email"
                                    required
                                    autocomplete="username"
                                    placeholder="Enter your email address"
                                    :class="{ 'border-destructive': form.errors.email }"
                                />
                                <InputError :message="form.errors.email" />
                            </div>

                            <!-- Email Verification Status -->
                            <div v-if="mustVerifyEmail && !user.email_verified_at" class="rounded-lg border border-amber-200 bg-amber-50 p-4 dark:border-amber-800 dark:bg-amber-950">
                                <div class="flex items-start gap-3">
                                    <Shield class="h-5 w-5 text-amber-600 dark:text-amber-400 mt-0.5" />
                                    <div class="space-y-2">
                                        <p class="text-sm font-medium text-amber-800 dark:text-amber-200">
                                            Email verification required
                                        </p>
                                        <p class="text-sm text-amber-700 dark:text-amber-300">
                                            Your email address is unverified. Please check your inbox for a verification link.
                                        </p>
                                        <Button 
                                            variant="outline" 
                                            size="sm"
                                            :href="route('verification.send')"
                                            method="post"
                                            as="button"
                                            class="border-amber-200 text-amber-700 hover:bg-amber-100 dark:border-amber-800 dark:text-amber-300 dark:hover:bg-amber-900"
                                        >
                                            <Mail class="h-4 w-4 mr-2" />
                                            Resend verification email
                                        </Button>
                                    </div>
                                </div>
                            </div>

                            <div v-else-if="user.email_verified_at" class="rounded-lg border border-green-200 bg-green-50 p-4 dark:border-green-800 dark:bg-green-950">
                                <div class="flex items-center gap-3">
                                    <CheckCircle class="h-5 w-5 text-green-600 dark:text-green-400" />
                                    <div>
                                        <p class="text-sm font-medium text-green-800 dark:text-green-200">
                                            Email verified
                                        </p>
                                        <p class="text-sm text-green-700 dark:text-green-300">
                                            Your email address has been verified successfully.
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div v-if="status === 'verification-link-sent'" class="rounded-lg border border-green-200 bg-green-50 p-4 dark:border-green-800 dark:bg-green-950">
                                <div class="flex items-center gap-3">
                                    <CheckCircle class="h-5 w-5 text-green-600 dark:text-green-400" />
                                    <p class="text-sm font-medium text-green-800 dark:text-green-200">
                                        Verification email sent successfully!
                                    </p>
                                </div>
                            </div>

                            <div class="flex items-center gap-4 pt-4">
                                <Button type="submit" :disabled="form.processing">
                                    <span v-if="form.processing">Saving...</span>
                                    <span v-else>Save changes</span>
                                </Button>

                                <Transition
                                    enter-active-class="transition ease-in-out duration-200"
                                    enter-from-class="opacity-0 translate-y-1"
                                    leave-active-class="transition ease-in-out duration-200"
                                    leave-to-class="opacity-0 translate-y-1"
                                >
                                    <p v-show="form.recentlySuccessful" class="text-sm text-green-600 dark:text-green-400 font-medium">
                                        Changes saved successfully!
                                    </p>
                                </Transition>
                            </div>
                        </form>
                    </CardContent>
                </Card>

                <Separator />

                <!-- Danger Zone -->
                <DeleteUser />
            </div>
        </SettingsLayout>
    </AppLayout>
</template>
