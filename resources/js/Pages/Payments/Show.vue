<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { formatMoney } from '@/helpers'

defineProps({
    payment: Object,
})
</script>

<template>
    <AppLayout :title="`Betaling #${payment.id}`">
        <template #header>
            <h1 class="text-3xl font-semibold leading-tight text-gray-800">
                Betaling #{{ payment.id }}
            </h1>
        </template>

        <div class="grid w-1/2 grid-cols-2 gap-4">
            <span class="font-semibold text-gray-500">Factuur #</span>
            <span class="font-semibold"
                >#{{ payment.invoice.invoice_number }}</span
            >

            <span class="font-semibold text-gray-500">Klant</span>
            <span class="font-semibold">{{ payment.customer.name }}</span>

            <span class="font-semibold text-gray-500">Datum betaald</span>
            <span class="font-semibold">{{ payment.payment_date }}</span>

            <span class="font-semibold text-gray-500">Bedrag</span>
            <span class="font-semibold">{{ formatMoney(payment.amount) }}</span>

            <span class="mt-4 font-semibold text-gray-500">Opmerkingen</span>
            <span class="col-span-2 font-medium">{{ payment.comments }}</span>
        </div>
    </AppLayout>
</template>
