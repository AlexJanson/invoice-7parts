<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { formatMoney } from '@/helpers'

defineProps({
    product: Object,
})
</script>

<template>
    <AppLayout :title="product.name">
        <template #header>
            <h1 class="text-3xl font-semibold leading-tight text-gray-800">
                {{ product.name }}
            </h1>
        </template>

        <div class="grid w-1/2 grid-cols-2 gap-4">
            <span class="font-semibold text-gray-500">Naam</span>
            <span class="font-semibold">{{ product.name }}</span>

            <span class="font-semibold text-gray-500">Prijs</span>
            <span class="font-semibold">{{ formatMoney(product.price) }}</span>

            <span class="font-semibold text-gray-500">BTW</span>
            <span class="font-semibold">{{ product.tax }}%</span>

            <span class="font-semibold text-gray-500">Prijs + BTW</span>
            <span class="font-semibold">{{
                formatMoney(product.price_incl_tax)
            }}</span>

            <span class="font-semibold text-gray-500">Eenheid</span>
            <span class="font-semibold capitalize">{{
                product.unit === 'hours' ? 'uren' : 'stuks'
            }}</span>

            <span class="mt-4 font-semibold text-gray-500">Beschrijving</span>
            <span class="col-span-2 font-medium">{{
                product.description
            }}</span>
        </div>
    </AppLayout>
</template>
