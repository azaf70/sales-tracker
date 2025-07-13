<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import SettingsLayout from '@/layouts/settings/Layout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { type BreadcrumbItem } from '@/types';
import { Lock, Shield, CheckCircle } from 'lucide-vue-next';

const breadcrumbItems: BreadcrumbItem[] = [
    {
        title: 'Password settings',
        href: '/settings/password',
    },
];

const passwordInput = ref<HTMLInputElement | null>(null);
const currentPasswordInput = ref<HTMLInputElement | null>(null);

const form = useForm({
    current_password: '',
    password: '',
    password_confirmation: '',
});

const updatePassword = () => {
    form.put(route('password.update'), {
        preserveScroll: true,
        onSuccess: () => form.reset(),
        onError: (errors: any) => {
            if (errors.password) {
                form.reset('password', 'password_confirmation');
                if (passwordInput.value instanceof HTMLInputElement) {
                    passwordInput.value.focus();
                }
            }

            if (errors.current_password) {
                form.reset('current_password');
                if (currentPasswordInput.value instanceof HTMLInputElement) {
                    currentPasswordInput.value.focus();
                }
            }
        },
    });
};
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbItems">
        <Head title="Password settings" />

        <SettingsLayout>
            <div class="mx-auto max-w-lg w-full space-y-10 py-8">
                <!-- Password Header -->
                <div class="flex items-center gap-4 mb-4">
                    <div class="flex h-8 w-8 items-center justify-center rounded-full bg-primary/10">
                        <Lock class="h-4 w-4 text-primary" />
                    </div>
                    <div>
                        <h1 class="text-lg font-bold tracking-tight">Password</h1>
                    </div>
                </div>

                <!-- Password Update Card -->
                <Card>
                    <CardHeader class="mb-2">
                        <CardTitle class="flex items-center gap-2">
                            <Shield class="h-5 w-5" />
                            Update Password
                        </CardTitle>
                        <CardDescription>
                            Ensure your account is using a long, random password to stay secure.
                        </CardDescription>
                    </CardHeader>
                    <CardContent>
                        <form @submit.prevent="updatePassword" class="space-y-4">
                            <div class="grid gap-2">
                                <Label for="current_password">Current Password</Label>
                                <Input
                                    id="current_password"
                                    ref="currentPasswordInput"
                                    v-model="form.current_password"
                                    type="password"
                                    autocomplete="current-password"
                                    placeholder="Enter your current password"
                                    :class="{ 'border-destructive': form.errors.current_password }"
                                />
                                <InputError :message="form.errors.current_password" />
                            </div>

                            <div class="grid gap-2">
                                <Label for="password">New Password</Label>
                                <Input
                                    id="password"
                                    ref="passwordInput"
                                    v-model="form.password"
                                    type="password"
                                    autocomplete="new-password"
                                    placeholder="Enter your new password"
                                    :class="{ 'border-destructive': form.errors.password }"
                                />
                                <InputError :message="form.errors.password" />
                            </div>

                            <div class="grid gap-2">
                                <Label for="password_confirmation">Confirm New Password</Label>
                                <Input
                                    id="password_confirmation"
                                    v-model="form.password_confirmation"
                                    type="password"
                                    autocomplete="new-password"
                                    placeholder="Confirm your new password"
                                    :class="{ 'border-destructive': form.errors.password_confirmation }"
                                />
                                <InputError :message="form.errors.password_confirmation" />
                            </div>

                            <div class="flex items-center gap-4 pt-4">
                                <Button type="submit" :disabled="form.processing">
                                    <span v-if="form.processing">Updating...</span>
                                    <span v-else>Update password</span>
                                </Button>

                                <Transition
                                    enter-active-class="transition ease-in-out duration-200"
                                    enter-from-class="opacity-0 translate-y-1"
                                    leave-active-class="transition ease-in-out duration-200"
                                    leave-to-class="opacity-0 translate-y-1"
                                >
                                    <div v-show="form.recentlySuccessful" class="flex items-center gap-2 text-sm text-green-600 dark:text-green-400 font-medium">
                                        <CheckCircle class="h-4 w-4" />
                                        Password updated successfully!
                                    </div>
                                </Transition>
                            </div>
                        </form>
                    </CardContent>
                </Card>
            </div>
        </SettingsLayout>
    </AppLayout>
</template>
