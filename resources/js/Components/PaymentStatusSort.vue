<script setup>
import DropdownLink from '@/Components/DropdownLink.vue'
import PaymentStatusIcon from './PaymentStatusIcon.vue'

defineProps({
    id: String,
    updateSort: Function,
})
</script>

<template>
    <DropdownLink as="button" @click="updateSort(id, 'PAID')">
        <PaymentStatusIcon status="PAID" />
    </DropdownLink>
    <DropdownLink as="button">
        <PaymentStatusIcon
            status="PARTIALLY PAID"
            @click="updateSort(id, 'PARTIALLY PAID')"
        />
    </DropdownLink>
    <DropdownLink as="button">
        <PaymentStatusIcon status="UNPAID" @click="updateSort(id, 'UNPAID')" />
    </DropdownLink>
</template>
