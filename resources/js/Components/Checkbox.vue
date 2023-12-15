<script setup>
import { computed, ref } from 'vue'

const emit = defineEmits(['update:checked'])

const props = defineProps({
    checked: {
        type: [Array, Boolean],
        default: false,
    },
    value: {
        type: String,
        default: null,
    },
    indeterminate: {
        type: Boolean,
        default: false,
    },
})

const proxyChecked = computed({
    get() {
        return props.checked
    },

    set(val) {
        emit('update:checked', val)
    },
})

const input = ref()
defineExpose({
    checked: () => input.value.checked,
})
</script>

<template>
    <input
        v-model="proxyChecked"
        type="checkbox"
        :value="value"
        class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
        ref="input"
        :indeterminate="props.indeterminate"
    />
</template>
