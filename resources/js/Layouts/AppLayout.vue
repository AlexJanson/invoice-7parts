<script setup>
import { Head, router } from '@inertiajs/vue3'
import Banner from '@/Components/Banner.vue'
import ApplicationLogo from '@/Components/ApplicationLogo.vue'
import NavLink from '@/Components/NavLink.vue'
import QuickActions from '@/Components/QuickActions.vue'
import {
    ChevronRightIcon,
    UserCircleIcon,
    UsersIcon,
    HomeIcon,
    CubeTransparentIcon,
    DocumentPlusIcon,
    CurrencyEuroIcon,
} from '@heroicons/vue/20/solid'

defineProps({
    title: String,
})

const logout = () => {
    router.post(route('logout'))
}
</script>

<template>
    <div>
        <Head :title="title" />

        <Banner />

        <div class="flex h-screen w-full overflow-hidden bg-slate-900">
            <nav class="my-3 flex w-80 shrink-0 flex-col justify-between">
                <div>
                    <div class="m-8">
                        <ApplicationLogo class="w-48" />
                    </div>

                    <span class="mx-8 text-gray-500">Navigation</span>

                    <div class="mt-5">
                        <NavLink
                            :href="route('dashboard')"
                            :active="route().current('dashboard')"
                        >
                            <HomeIcon class="h-5 w-5" />
                            Dashboard
                        </NavLink>

                        <NavLink
                            :href="route('customers.index')"
                            :active="
                                route().current('customer.*') ||
                                route().current('customers.*')
                            "
                        >
                            <UsersIcon class="h-5 w-5" />
                            Klanten
                        </NavLink>

                        <NavLink
                            :href="route('products.index')"
                            :active="
                                route().current('product.*') ||
                                route().current('products.*')
                            "
                        >
                            <CubeTransparentIcon class="h-5 w-5" />
                            Producten
                        </NavLink>

                        <NavLink
                            :href="
                                route('invoices.index', {
                                    column: 'invoice-number',
                                    direction: 'ASC',
                                })
                            "
                            :active="
                                route().current('invoice.*') ||
                                route().current('invoices.*')
                            "
                        >
                            <DocumentPlusIcon class="h-5 w-5" />
                            Facturen
                        </NavLink>

                        <NavLink
                            :href="route('profile.show')"
                            :active="route().current('profile.show')"
                        >
                            <UserCircleIcon class="h-5 w-5" />
                            Profile
                        </NavLink>

                        <!-- <form method="POST" @submit.prevent="logout">
                            <NavLink>
                                Log Out
                            </NavLink>
                        </form> -->
                    </div>
                </div>

                <a
                    :href="route('profile.show')"
                    class="mx-3 flex items-center justify-between rounded-lg bg-gray-700/60 p-2 text-gray-50 duration-100 hover:bg-gray-600"
                >
                    <div class="flex items-center space-x-4">
                        <img
                            class="aspect-square w-12 rounded-lg object-contain"
                            :src="$page.props.auth.user.profile_photo_url"
                            alt="profile photo"
                        />

                        <span>{{ $page.props.auth.user.name }}</span>
                    </div>

                    <ChevronRightIcon class="w-6" />
                </a>
            </nav>

            <div
                id="main"
                class="relative m-3 ml-0 grow overflow-y-auto rounded-lg bg-white scrollbar:!h-1.5 scrollbar:!w-1.5 scrollbar:bg-transparent scrollbar-track:!rounded-r scrollbar-track:!bg-zinc-200 scrollbar-thumb:!rounded scrollbar-thumb:!bg-zinc-400"
            >
                <!-- Page Heading -->
                <header v-if="$slots.header">
                    <div class="px-8 py-10">
                        <slot name="header" />
                    </div>
                </header>

                <!-- Page Content -->
                <main class="px-8 py-10">
                    <slot />
                </main>

                <QuickActions class="fixed bottom-3 right-3" />
            </div>
        </div>
    </div>
</template>
