<script setup>
import { Money3Component } from 'v-money3'

const props = defineProps({
    label: {
        type: String,
        required: true,
    },
    modelValue: [String, Number],
    hideLabel: Boolean,
    description: String,
    error: String,
    disabled: Boolean,
    align: String,
})

const emit = defineEmits(['update:modelValue'])

const config = {
    prefix: '€ ',
    thousands: '.',
    decimal: ',',
    precision: 2,
    disableNegative: true,
}

function update(value) {
    emit('update:modelValue', parseInt(value.replace('.', '')))
}
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

        <Money3Component
            :id="label"
            v-bind="config"
            :model-value.number="modelValue / 100"
            @update:model-value="(value) => update(value)"
            class="w-full rounded-md border-gray-300 py-1.5 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 disabled:cursor-not-allowed disabled:bg-gray-100 disabled:text-gray-500"
            :class="{
                'text-right': align === 'right',
            }"
            :disabled="disabled"
        />

        <div class="mt-2 text-sm text-gray-400" v-if="description">
            {{ description }}
        </div>

        <div class="mt-2 text-sm text-red-600" v-if="error">
            {{ error }}
        </div>
    </div>
</template>
