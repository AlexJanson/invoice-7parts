<script setup>
import OrderedTable from '@/Components/OrderedTable.vue'
import { formatMoney } from '@/helpers'
// import { Link } from '@inertiajs/vue3'
import { computed } from 'vue'

const props = defineProps({
    payments: Array,
    pagination: Object,
})

const payments = {
    cols: [
        {
            name: 'Datum',
            id: 'date',
        },
        // {
        //     name: 'Klant',
        //     id: 'customer',
        // },
        // {
        //     name: 'Factuur #',
        //     id: 'invoice-number',
        // },
        {
            name: 'Bedrag',
            id: 'amount',
            align: 'right',
        },
    ],
    rows: computed(() => props.payments),
    pagination: computed(() => props.pagination),
    route: {
        name: 'payment.show',
        paramKey: 'id',
    },
}
</script>

<template>
    <OrderedTable :data="payments" should-delete>
        <template v-slot="{ current }">
            <td class="pl-2">{{ current.payment_date }}</td>
            <!-- <td class="w-1/3 pl-2 font-semibold">
                <Link
                    class="underline"
                    :href="route('customer.show', { slug: current.slug })"
                >
                    {{ current.name }}
                </Link>
            </td> -->
            <!-- <td class="pl-2 font-semibold">
                <Link
                    class="underline"
                    :href="
                        route('invoice.show', {
                            invoice_number: current.invoice_number,
                        })
                    "
                >
                    #{{ current.invoice_number }}
                </Link>
            </td> -->
            <td class="pr-4 font-semibold" align="right">
                {{ formatMoney(current.amount) }}
            </td>
        </template>
    </OrderedTable>
</template>
