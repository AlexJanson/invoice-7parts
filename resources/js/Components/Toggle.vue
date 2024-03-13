<template>
    <label class="inline-flex cursor-pointer items-center">
        <input
            type="checkbox"
            :checked="modelValue"
            class="peer sr-only"
            @change="emit('update:modelValue', $event.target.checked)"
        />
        <div
            class="peer relative h-6 w-11 rounded-full bg-gray-200 after:absolute after:start-[2px] after:top-[2px] after:h-5 after:w-5 after:rounded-full after:border after:border-gray-300 after:bg-white after:transition-all after:content-[''] peer-checked:bg-blue-600 peer-checked:after:translate-x-full peer-checked:after:border-white peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rtl:peer-checked:after:-translate-x-full"
        />
        <span class="ms-3 font-medium text-gray-700">
            <slot />
        </span>
    </label>
</template>

<script setup>
defineProps({
    modelValue: Boolean,
})

const emit = defineEmits(['update:modelValue'])
</script>
