<script setup>
import { ExclamationCircleIcon } from '@heroicons/vue/24/outline'
import TextInput from './TextInput.vue'
import InputError from './InputError.vue'
import PhoneInput from './PhoneInput.vue'

defineProps({
    modelValue: String,
    placeholder: String,
    error: String,
    type: {
        type: String,
        default: 'text',
    },
})

const emit = defineEmits(['update:modelValue'])
</script>

<template>
    <div class="flex items-center self-center">
        <ExclamationCircleIcon
            v-show="error"
            class="mr-2 h-6 w-6 stroke-2 text-red-600"
        />
        <span class="font-semibold text-gray-500">
            <slot />
        </span>
    </div>

    <TextInput
        v-if="type != 'phone'"
        v-bind:model-value="modelValue"
        @update:model-value="(newValue) => $emit('update:modelValue', newValue)"
        :class="{ 'border border-red-500': error }"
        class="placeholder:italic placeholder:text-gray-400"
        :type="type"
        :placeholder="placeholder"
    />
    <PhoneInput
        v-else
        :class="{ 'border border-red-500': error }"
        v-bind:model-value="modelValue"
        @update:model-value="(newValue) => $emit('update:modelValue', newValue)"
        :placeholder="placeholder"
    />

    <div v-show="error" class="col-span-2 grid grid-cols-2">
        <InputError :message="error" class="col-start-2 px-2" />
    </div>
</template>
