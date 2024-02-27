<script setup>
import OrderedTable from '@/Components/OrderedTable.vue'
import { formatMoney } from '@/helpers'
import { Link } from '@inertiajs/vue3'
import { computed } from 'vue'

const props = defineProps({
    products: Array,
    pagination: Object,
})

const products = {
    cols: [
        {
            name: 'Naam',
            id: 'name',
        },
        {
            name: 'Prijs',
            id: 'price',
        },
        {
            name: 'Datum',
            id: 'date',
            align: 'right',
        },
    ],
    rows: computed(() => props.products),
    pagination: computed(() => props.pagination),
    route: {
        name: 'product.show',
        paramKey: 'slug',
    },
}
</script>

<template>
    <OrderedTable :data="products" should-delete>
        <template v-slot="{ current }">
            <td class="pl-2 font-semibold">
                <Link
                    class="underline"
                    :href="route('product.show', { slug: current.slug })"
                >
                    {{ current.name }}
                </Link>
            </td>
            <td class="pl-2 font-semibold">
                {{ formatMoney(current.price) }}
            </td>
            <td align="right" class="pr-4">{{ current.created_at }}</td>
        </template>
    </OrderedTable>
</template>
