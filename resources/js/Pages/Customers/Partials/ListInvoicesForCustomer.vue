<script setup>
import OrderedTable from '@/Components/OrderedTable.vue'
import PaymentStatusIcon from '@/Components/PaymentStatusIcon.vue'
import PaymentStatusSort from '@/Components/PaymentStatusSort.vue'
import { formatMoney } from '@/helpers'
import { Link } from '@inertiajs/vue3'
import { computed } from 'vue'

const props = defineProps({
    slug: String,
    invoices: Array,
    pagination: Object,
})

const invoices = {
    cols: [
        {
            name: 'Datum',
            id: 'date',
        },
        {
            name: 'Factuur #',
            id: 'invoice-number',
        },
        {
            name: 'Betaald',
            id: 'paid',
            sortComponent: PaymentStatusSort,
        },
        {
            name: 'Openst. bedrag',
            id: 'due-amount',
        },
        {
            name: 'Totaal',
            id: 'total',
            align: 'right',
        },
    ],
    rows: computed(() => props.invoices),
    pagination: computed(() => props.pagination),
    route: {
        name: 'invoice.show',
        paramKey: 'invoice_number',
        parameter: props.slug,
    },
}
</script>

<template>
    <OrderedTable :data="invoices">
        <template v-slot="{ current }">
            <td class="pl-2">{{ current.invoice_date }}</td>
            <td class="pl-2 font-semibold">
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
            </td>
            <td class="pl-2">
                <PaymentStatusIcon :status="current.payment_status" />
            </td>
            <td class="pl-2 font-semibold">
                {{ formatMoney(current.due_amount) }}
            </td>
            <td class="pr-4 font-semibold" align="right">
                {{ formatMoney(current.total) }}
            </td>
        </template>
    </OrderedTable>
</template>
