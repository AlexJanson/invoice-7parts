<script setup>
import { Link } from '@inertiajs/vue3'

defineProps({
    href: String,
    method: String,
    bgClass: String,
    as: {
        type: String,
        default: undefined,
    },
})
</script>

<template>
    <button
        v-if="as == 'button'"
        as="button"
        :class="bgClass"
        class="group block w-fit rounded-md py-2 pl-2 pr-3 text-base leading-5 text-white shadow-md transition duration-150 ease-in-out focus:outline-none"
    >
        <slot />
    </button>

    <Link
        v-else
        :href="href"
        :method="method != 'GET' ? 'button' : undefined"
        :class="bgClass"
        class="group block w-fit rounded-md py-2 pl-2 pr-3 text-base leading-5 text-white shadow-md transition duration-150 ease-in-out focus:outline-none"
    >
        <slot />
    </Link>
</template>
