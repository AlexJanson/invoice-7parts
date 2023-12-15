<script setup>
import { computed } from 'vue'

const props = defineProps({
    status: String,
})

const status = computed(() => {
    return {
        UNPAID: {
            color: 'bg-red-600',
            text: 'Onbetaald',
        },
        'PARTIALLY PAID': {
            color: 'bg-amber-600',
            text: 'Gedeeltelijk betaald',
        },
        PAID: {
            color: 'bg-green-600',
            text: 'Betaald',
        },
    }[props.status]
})
</script>

<template>
    <div class="flex items-center gap-2">
        <div
            :class="status.color"
            class="grid h-4 w-4 place-items-center rounded-full bg-opacity-20"
        >
            <div :class="status.color" class="h-1.5 w-1.5 rounded-full"></div>
        </div>

        <span class="capitalize">{{ status.text.toLowerCase() }}</span>
    </div>
</template>
