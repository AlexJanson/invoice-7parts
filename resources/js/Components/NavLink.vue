<script setup>
import { computed } from 'vue'
import { Link } from '@inertiajs/vue3'

const props = defineProps({
    href: String,
    active: Boolean,
})

const classes = computed(() => {
    return props.active
        ? 'flex items-center gap-8 text-gray-400 font-medium my-4 text-white'
        : 'flex items-center gap-8 text-gray-400 font-medium my-4 hover:text-white'
})
</script>

<template>
    <Link :href="href" :class="classes">
        <div
            class="h-8 w-1 rounded-r bg-white"
            :class="{ invisible: !active }"
        />
        <div
            class="mr-12 flex w-full items-center gap-2 rounded px-2 py-1.5 transition duration-100 hover:bg-gray-300/25 active:bg-gray-300/50"
            :class="{ 'bg-gray-200/10': active }"
        >
            <slot />
        </div>
    </Link>
</template>
