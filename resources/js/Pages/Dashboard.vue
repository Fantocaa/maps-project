<script setup>
import AuthenticatedLayout from "@/Layouts/Authenticated.vue";
import Button from "@/Components/Button.vue";
import { GithubIcon } from "@/Components/Icons/brands";
import { Link } from "@inertiajs/vue3";
</script>

<template>
    <AuthenticatedLayout title="Dashboard">
        <template #header>
            <div
                class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between"
            >
                <h2 class="text-xl font-semibold leading-tight">Dashboard</h2>
                <!-- <Button
                    external
                    variant="black"
                    target="_blank"
                    class="items-center gap-2 max-w-xs"
                    v-slot="{ iconSizeClasses }"
                    href="https://github.com/kamona-wd/kui-laravel-breeze"
                >
                    <GithubIcon aria-hidden="true" :class="iconSizeClasses" />

                    <span>Star on Github</span>
                </Button> -->
            </div>
        </template>

        <div class="flex w-full justify-between gap-8">
            <div
                class="p-6 overflow-hidden bg-white rounded-md shadow-md dark:bg-dark-eval-1 w-full"
            >
                You're logged in!
            </div>
            <div class="flex w-1/2 gap-4">
                <Link
                    href="/maps/user"
                    class="inline-flex items-center transition-colors font-medium select-none disabled:opacity-50 disabled:cursor-not-allowed focus:outline-none focus:ring focus:ring-offset-2 focus:ring-offset-white dark:focus:ring-offset-dark-eval-2 px-5 py-2 bg-blue-500 text-white hover:bg-blue-600 focus:ring-blue-500 rounded-md justify-center gap-2"
                >
                    Maps User
                </Link>
                <Link
                    href="/maps/superuser"
                    class="inline-flex items-center transition-colors font-medium select-none disabled:opacity-50 disabled:cursor-not-allowed focus:outline-none focus:ring focus:ring-offset-2 focus:ring-offset-white dark:focus:ring-offset-dark-eval-2 px-5 py-2 bg-blue-500 text-white hover:bg-blue-600 focus:ring-blue-500 rounded-md justify-center gap-2"
                >
                    Maps Super User
                </Link>
                <Link
                    href="/maps/admin"
                    class="inline-flex items-center transition-colors font-medium select-none disabled:opacity-50 disabled:cursor-not-allowed focus:outline-none focus:ring focus:ring-offset-2 focus:ring-offset-white dark:focus:ring-offset-dark-eval-2 px-5 py-2 bg-blue-500 text-white hover:bg-blue-600 focus:ring-blue-500 rounded-md justify-center gap-2"
                >
                    Maps Admin
                </Link>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
