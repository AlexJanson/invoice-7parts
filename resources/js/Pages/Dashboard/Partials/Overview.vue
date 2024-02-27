<script setup>
import StatisticBlock from '@/Components/StatisticBlock.vue'
import { formatMoney } from '@/helpers'
import { usePage } from '@inertiajs/vue3'
</script>

<template>
    <h3 class="text-lg font-bold leading-tight text-gray-800">Overview</h3>

    <div class="grid grid-cols-4 gap-24">
        <StatisticBlock>
            <template #heading> Aantal klanten </template>

            {{ usePage().props.customers }}
        </StatisticBlock>

        <StatisticBlock>
            <template #heading> Openstaand bedrag </template>

            <!-- € 5.716,<span class="ml-1 text-3xl font-normal">65</span> -->
            {{ formatMoney(usePage().props.dueAmount).split(',')[0] }},<span
                class="ml-1 text-3xl font-normal"
                >{{
                    formatMoney(usePage().props.dueAmount).split(',')[1]
                }}</span
            >
        </StatisticBlock>

        <StatisticBlock>
            <template #heading> Aantal facturen </template>

            {{ usePage().props.invoices }}
        </StatisticBlock>

        <StatisticBlock>
            <template #heading> Aantal producten </template>

            {{ usePage().props.products }}
        </StatisticBlock>
    </div>
</template>
