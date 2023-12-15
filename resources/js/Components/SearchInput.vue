<script setup>
import { onMounted, ref } from 'vue'
import { MagnifyingGlassIcon } from '@heroicons/vue/20/solid'

defineProps({
    modelValue: String,
})

defineEmits(['update:modelValue'])

const input = ref(null)

onMounted(() => {
    if (input.value.hasAttribute('autofocus')) {
        input.value.focus()
    }
})

defineExpose({ focus: () => input.value.focus() })
</script>

<template>
    <div class="flex w-full items-center border-none pl-0">
        <MagnifyingGlassIcon class="ml-3 mr-6 h-6 w-6" />

        <input
            ref="input"
            class="mt-1 block w-full rounded-md border-gray-300"
            :value="modelValue"
            type="text"
            placeholder="Search results"
            @input="$emit('update:modelValue', $event.target.value)"
            autofocus
        />
    </div>
</template>
