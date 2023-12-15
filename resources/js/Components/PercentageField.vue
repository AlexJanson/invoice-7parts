<script setup>
import { Money3Component } from 'v-money3'

const props = defineProps({
    label: {
        type: String,
        required: true,
    },
    max: {
        type: Number,
        required: false,
        default: 100,
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
    suffix: ' %',
    thousands: '.',
    decimal: ',',
    precision: 0,
    disableNegative: true,
    min: 0,
    max: props.max,
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

        <div class="mb-2 text-sm text-gray-400" v-if="description">
            {{ description }}
        </div>

        <Money3Component
            :id="label"
            v-bind="config"
            :model-value.number="modelValue"
            @update:model-value="
                (value) =>
                    emit('update:modelValue', parseInt(value.replace('.', '')))
            "
            class="w-full rounded-md border-gray-300 py-1.5 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 disabled:cursor-not-allowed disabled:bg-gray-100 disabled:text-gray-500"
            :class="{
                'text-right': align === 'right',
            }"
            :disabled="disabled"
        />

        <div class="mt-2 text-sm text-red-600" v-if="error">
            {{ error }}
        </div>
    </div>
</template>
