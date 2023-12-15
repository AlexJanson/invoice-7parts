<script setup>
const props = defineProps({
    label: {
        type: String,
        required: true,
    },
    modelValue: [String, Number],
    hideLabel: Boolean,
    type: {
        default: 'text',
    },
    description: String,
    error: String,
    disabled: Boolean,
})

const emit = defineEmits(['update:modelValue'])
</script>

<template>
    <div>
        <label
            :for="label"
            v-if="!hideLabel"
            class="mb-2 block text-base font-semibold text-gray-600"
        >
            {{ label }}
        </label>

        <div class="mb-2 text-sm text-gray-400" v-if="description">
            {{ description }}
        </div>

        <input
            v-if="type === 'text'"
            :value="modelValue"
            @input="emit('update:modelValue', $event.target.value)"
            :id="label"
            type="text"
            class="w-full rounded-md border-gray-300 py-1.5 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 disabled:cursor-not-allowed disabled:bg-gray-100 disabled:text-gray-500"
            :disabled="disabled"
        />

        <textarea
            v-if="type === 'textarea'"
            rows="5"
            :value="modelValue"
            @input="emit('update:modelValue', $event.target.value)"
            :id="label"
            class="w-full resize-none rounded-md border-gray-300 py-1.5 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 disabled:cursor-not-allowed disabled:bg-gray-100 disabled:text-gray-500 scrollbar:!h-1.5 scrollbar:!w-1.5 scrollbar:bg-transparent scrollbar-track:!rounded-r scrollbar-track:!bg-zinc-200 scrollbar-thumb:!rounded scrollbar-thumb:!bg-zinc-400"
        ></textarea>

        <div class="mt-2 text-sm text-red-600" v-if="error">
            {{ error }}
        </div>
    </div>
</template>
