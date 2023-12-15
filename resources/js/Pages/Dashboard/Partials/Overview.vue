<script setup>
import StatisticBlock from '@/Components/StatisticBlock.vue'
</script>

<template>
    <h3 class="text-lg font-bold leading-tight text-gray-800">Overview</h3>

    <div class="grid grid-cols-4 gap-24">
        <StatisticBlock>
            <template #heading> Aantal klanten </template>

            50
        </StatisticBlock>

        <StatisticBlock>
            <template #heading> Openstaand bedrag </template>

            € 5.716,<span class="ml-1 text-3xl font-normal">65</span>
        </StatisticBlock>

        <StatisticBlock>
            <template #heading> Aantal facturen </template>

            22
        </StatisticBlock>

        <StatisticBlock>
            <template #heading> Aantal producten </template>

            80
        </StatisticBlock>
    </div>
</template>
