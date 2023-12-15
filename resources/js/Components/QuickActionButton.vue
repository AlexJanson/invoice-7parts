<script setup>
import { Link } from '@inertiajs/vue3'

defineProps({
    href: String,
    bgClass: String,
})
</script>

<template>
    <Link
        :href="href"
        :class="bgClass"
        class="group block w-full rounded-md py-1 pl-1.5 pr-2.5 text-base leading-5 text-white shadow-md transition duration-150 ease-in-out focus:outline-none"
    >
        <slot />
    </Link>
</template>
