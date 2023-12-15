<script setup>
import OrderedTable from '@/Components/OrderedTable.vue'
import { formatMoney } from '@/helpers'
import { Link } from '@inertiajs/vue3'
import { computed } from 'vue'

const props = defineProps({
    customers: Array,
    pagination: Object,
})

const customers = {
    cols: [
        {
            name: 'Naam',
            id: 'name',
        },
        {
            name: 'Telefoon #',
            id: 'phone',
        },
        {
            name: 'Openst. bedrag',
            id: 'due-amount',
        },
    ],
    rows: computed(() => props.customers),
    pagination: computed(() => props.pagination),
    route: {
        name: 'customer.show',
        paramKey: 'slug',
    },
}
</script>

<template>
    <OrderedTable :data="customers">
        <template v-slot="{ current }">
            <td class="w-1/3 font-semibold">
                <Link
                    class="underline"
                    :href="route('customer.show', { slug: current.slug })"
                >
                    {{ current.name }}
                </Link>
            </td>
            <td class="w-1/3">{{ current.phone }}</td>
            <td class="font-semibold">{{ formatMoney(current.due_amount) }}</td>
        </template>
    </OrderedTable>
</template>
