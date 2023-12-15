<script setup>
import { computed, ref } from 'vue'

defineProps({
    modelValue: String,
    placeholder: String,
})

const emit = defineEmits(['update:modelValue'])

const countryCode = ref('countryCode')
const input = ref('input')

const update = () =>
    emit('update:modelValue', `${countryCode.value.value} ${input.value.value}`)
</script>

<template>
    <div class="relative rounded-md border border-gray-300 shadow-sm">
        <div class="absolute flex">
            <select
                ref="countryCode"
                @change="update"
                name="country-code"
                id="country-code"
                class="rounded-md border-none pr-7 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500"
            >
                <option value="+31">NL</option>
                <option value="+1">US</option>
                <option value="+44">UK</option>
            </select>
        </div>
        <input
            @input="update"
            ref="input"
            type="tel"
            class="w-full rounded-md border-none pl-16 placeholder:italic placeholder:text-gray-400 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500"
            :placeholder="placeholder"
        />
    </div>
</template>
